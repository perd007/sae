<?php require_once('Connections/conexion.php'); ?>
<?php 
require('fpdf/fpdf.php');


//obtener registro


class PDF extends FPDF
	{
var $widths;
var $aligns;

//Funcion para definir el tamaño de las columnas
	function SetWidths($w)
	{
    $this->widths=$w;
}
	 
	function SetAligns($a)
	{
	    $this->aligns=$a;
}
//Funcion para Mostrar los datos en filas 
function Row($data)
	{
    $nb=0;
    for($i=0;$i<count($data);$i++)
	        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
   $h=4*$nb;
   $this->CheckPageBreak($h);
	    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
	        $x=$this->GetX();
        $y=$this->GetY();
	        $this->Rect($x,$y,$w,$h);
	        $this->MultiCell($w,4,$data[$i],0,$a);
	        $this->SetXY($x+$w,$y);
		


    }
	    $this->Ln($h);
	}
	 
	function CheckPageBreak($h)
	{
	    if($this->GetY()+$h>$this->PageBreakTrigger)
	        $this->AddPage($this->CurOrientation);
	}
	 
	function NbLines($w,$txt)
	{
	    $cw=&$this->CurrentFont['cw'];
	    if($w==0)
	        $w=$this->w-$this->rMargin-$this->x;
	    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	    $s=str_replace("\r",'',$txt);
	    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
	        $nb--;
    $sep=-1;
	    $i=0;
    $j=0;
	    $l=0;
	    $nl=1;
    while($i<$nb)
	    {
	        $c=$s[$i];
	        if($c=="\n")
	        {
            $i++;
	            $sep=-1;
	            $j=$i;
            $l=0;
	            $nl++;
	            continue;
	        }
        if($c==' ')
	            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
	        {
	            if($sep==-1)
	            {
	                if($i==$j)
	                    $i++;
	            }
	            else
	                $i=$sep+1;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	        }
	        else
	            $i++;
	    }
	    return $nl;
	}
	
	
//Funcion para el Encabezado
	function Header()
	{
		
		
		
		
		if($_GET["tipo"]=="Juridica") {$documento="RIF:";}
		if($_GET["tipo"]=="Natural") {$documento="CEDULA:";}
    
	
	$this->Image('imagenes/logoHeader2.jpg', 10, 10, 50);	
	$this->SetFont('Arial', 'B', 12);
	$this->SetDrawColor(255, 255, 255); $this->SetFillColor(255, 255, 255); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', '', 12);
	$this->SetWidths(array(40, 90,  80));
	$this->SetAligns(array('L', 'L', 'L', 'L'));
	$this->Ln(20);
	$this->Row(array('FACTURA:', $_GET["factura"],  'SERIE: '.$_GET["serie"]));
	$this->Row(array('CLIENTE:', $_GET["nombres"],  'FECHA: '.$_GET["fecha"]));
	$this->Row(array($documento, $_GET["cliente"],  '', ''));
	$this->Row(array('TELEFONO:', $_GET["telefono"], '', ''));
	$this->SetWidths(array(30,170));
	$this->SetAligns(array('L', 'L'));
	$this->Row(array('DIRECCION:', $_GET["direccion"]));
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(0, 0, 0); $this->SetTextColor(0, 0, 0);
	$y=$this->GetY();
	$this->Rect(5, $y, 200, 0.1, "DF");
	$this->Ln(1);
	}
//---------------------------------------------------
	 //funcion para el pie de Pagina
	function Footer()
	{
		
	    $this->SetY(-15);
	    $this->SetFont('Arial','B',10);
		$this->SetDrawColor(0, 0, 0); $this->SetFillColor(0, 0, 0); $this->SetTextColor(0, 0, 0);
		$y=$this->GetY();
		$this->Rect(5, $y, 200, 0.1, "DF");
		$this->Cell(170,5,'Sub-Total: '.$_GET["sub"],0,0,'R');
		$this->Ln(1);
		$this->Cell(170,10,'IVA 12%: '.$_GET["iva"],0,0,'R');
		$this->Ln(1);
		$this->Cell(170,15,'Neto: '.$_GET["total"],0,0,'R');
	}
	}
	


// creamos el objeto FPDF
	$pdf=new PDF('P','mm','Letter'); // vertical, milimetros y tamaño
	$pdf->Open();
	$pdf->SetFillColor(210,0,0);
	$pdf->AddPage(); // agregamos la pagina
	$pdf->SetMargins(10,10,10); // definimos los margenes en este caso estan en milimetros
	 // dejamos un pequeño espacio de 10 milimetros
	

//obtener los regsitro


//agregamos encabezados de productos
	$pdf->SetDrawColor(255, 255, 255);$pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetWidths(array(20, 120, 25, 15, 20));
	$pdf->SetAligns(array('L', 'L', 'C', 'C', 'C'));
	$pdf->Row(array('Codigo', 'Descripcion', 'Cantidad', 'Precio', 'Neto'));
	$pdf->SetFont('Arial', '', 10);
	


// Realizamos nuestra consulta
	mysql_select_db($database_conexion, $conexion);
	$strConsulta = "SELECT * FROM ventas_productos where factura='$_GET[factura]' and serie='$_GET[serie]'";
	
	// ejecutamos la consulta
	$productos = mysql_query($strConsulta, $conexion) or die(mysql_error());
		
	// listamos la tabla de electore 
	$numfilas = mysql_num_rows($productos);
for ($i=0; $i<$numfilas; $i++)   {       
	
	$fila = mysql_fetch_array($productos);                  
	
  	$strConsulta2 = "SELECT * FROM productos where id_productos='$fila[id_producto]'";
	$productos2 = mysql_query($strConsulta2, $conexion) or die(mysql_error());
	$fila2 = mysql_fetch_array($productos2);
	
	// los mostramos con la función Row
	$precioNeto=$fila['precio']/1.12;
	$total=round($precioNeto, 2)*$fila['cantidad'];
	$pdf->Row(array($fila2['codigo'], $fila2['nombre'], $fila['cantidad'], round($precioNeto, 2), $total));
		}


$pdf->Output();


?>