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
		

	
	$this->Image('imagenes/logoHeader2.jpg', 20, 10, 100);	
	$this->SetFont('Arial', 'B', 10);
	$this->SetDrawColor(255, 255, 255); $this->SetFillColor(255, 255, 255); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 10);
	$this->SetWidths(array(180));
	$this->SetAligns(array('C'));
	$this->Ln(20);
	$this->Row(array('REPORTE DE VENTAS DE PRODUCTOS Y SERVICIOS'));
	$this->Ln(10);
	$this->SetWidths(array(100,100));
	$this->SetAligns(array('L','L'));
	$this->Row(array('DESDE: '.$_POST["fecha1"],'HASTA: '.$_POST["fecha2"]));
	$this->Ln(10);
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
	$pdf->SetWidths(array(80, 30, 70));
	$pdf->SetAligns(array('L', 'C', 'C'));
	$pdf->Row(array('Producto', 'Tipo', 'Cantidad Vendida'));
	$pdf->Ln(2);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(5, $y, 200, 0.1, "DF");
	$pdf->Ln(2);
	$pdf->SetDrawColor(255, 255, 255);$pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 10);
	

		
 //consultamos todos los productos registrados
mysql_select_db($database_conexion, $conexion);
	$strConsulta = "SELECT * FROM productos ";
	// ejecutamos la consulta
	$productos = mysql_query($strConsulta, $conexion) or die(mysql_error());
	// listamos la tabla de electore 
	$numfilas = mysql_num_rows($productos);
	
$contador=0;
for ($i=0; $i<$numfilas; $i++)   { 		

$fila = mysql_fetch_array($productos);  



// Realizamos nuestra consulta de las facturas entre las fechas seleccionadas
mysql_select_db($database_conexion, $conexion);
$query_facturas = "SELECT * FROM facturas where fecha>='$_POST[fecha1]' and fecha<='$_POST[fecha2]' and  transaccion!='DEVOLUCION'";
$facturas = mysql_query($query_facturas, $conexion) or die(mysql_error());
$row_facturas = mysql_fetch_assoc($facturas);
$totalRows_facturas = mysql_num_rows($facturas);

	do{

		//consultamos los productos de la factura
			$query_facturas_productos = "SELECT * FROM ventas_productos where factura='$row_facturas[numero]' and serie='$row_facturas[serie]'";
			$facturas_productos = mysql_query($query_facturas_productos, $conexion) or die(mysql_error());
			$row_facturas_productos = mysql_fetch_assoc($facturas_productos);
			$totalRows_facturas_productos = mysql_num_rows($facturas_productos);
	
			do{
		
				if($fila["id_productos"]==$row_facturas_productos["id_producto"]){
					$producto_venta[$i]=$fila["id_productos"];
					$producto_nombre[$i]=$fila["nombre"];
					$producto_tipo[$i]=$fila["tipo"];
					$producto_cantidad[$i]=$producto_cantidad[$i] + $row_facturas_productos["cantidad"];
					$validador[$i]=1;
					$contador++;
				}
			}while($row_facturas_productos = mysql_fetch_assoc($facturas_productos));
	}while($row_facturas = mysql_fetch_assoc($facturas));
}//fin del for
		
//imprimir los resultados
for ($j=0; $j<$numfilas; $j++)   {

	
	if($validador[$j]==1){
		$pdf->Row(array($producto_nombre[$j], $producto_tipo[$j], $producto_cantidad[$j] ));
	} 
}

$pdf->Output();


?>