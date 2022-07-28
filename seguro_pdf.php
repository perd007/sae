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
		

	$this->Image('imagenes/logoHeader2.jpg', 10, 10, 50);	
	$this->SetFont('Arial', 'B', 10);
	$this->SetDrawColor(255, 255, 255); $this->SetFillColor(255, 255, 255); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 14);
	$this->SetWidths(array(200));
	$this->SetAligns(array('C'));
	$this->Ln(20);
	$this->Row(array('DATOS DEL ASEGURADO'));
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
mysql_select_db($database_conexion, $conexion);
$query_clientes = "SELECT * FROM clientes where cedula='$_GET[cedula]' ";
$clientes = mysql_query($query_clientes, $conexion) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetDrawColor(255, 255, 255);$pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(50, 150));
	$pdf->SetAligns(array('L', 'L', ));
	$pdf->Ln(20);
	$pdf->Row(array('NOMBRES Y APELLIDOS:', $row_clientes["nombres"]));
	$pdf->Ln(2);
	$pdf->Row(array('CEDULA O RIF:', $row_clientes["cedula"]));
	$pdf->Ln(2);
	$pdf->Row(array('TELEFONO:', $row_clientes["telefono"]));
	$pdf->Ln(2);
	$pdf->Row(array('DIRECCION:', $row_clientes["direccion"]));
	$pdf->Ln(2);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
	$pdf->Ln(20);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 14);
	$pdf->SetWidths(array(200));
	$pdf->SetAligns(array('C'));
	$pdf->Ln(3);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(5, $y, 200, 0.1, "DF");
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->Ln(3);
	$pdf->Row(array('DATOS DE LA MOTO'));
	$pdf->Ln(3);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetDrawColor(255, 255, 255);$pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(15,50, 135));
	$pdf->SetAligns(array('L','L', 'L', ));
	$pdf->Ln(20);
	$pdf->Row(array('','MARCA:', $_GET["marca"]));
	$pdf->Ln(2);
	$pdf->Row(array('','MODELO:', $_GET["modelo"]));
	$pdf->Ln(2);
	$pdf->Row(array('','CHASIS:',$_GET["chasis"]));
	$pdf->Ln(2);
	$pdf->Row(array('','PLACA:', $_GET["placa"]));
	$pdf->Ln(2);
	$pdf->Row(array('','FECHA:', $_GET["ano"]));
	$pdf->Ln(2);
	$pdf->Row(array('','MOTOR:',$_GET["motor"]));
	$pdf->Ln(2);
	$pdf->Row(array('','COLOR:', $_GET["color"]));
	$pdf->Ln(2);


// 
$pdf->Output();


?>