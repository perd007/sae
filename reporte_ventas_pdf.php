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
	$this->Row(array('REPORTE DE VENTAS'));
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
	$pdf->SetWidths(array(20, 20, 23, 23, 23,  23, 23, 23, 23));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->Row(array('Factura','Serie','Efectivo', 'Debito', 'Cheque', 'Deposito', 'IVA', 'Retenciones', 'Total'));
	$pdf->Ln(2);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(5, $y, 200, 0.1, "DF");
	$pdf->Ln(2);
	$pdf->SetDrawColor(255, 255, 255);$pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 10);
	


// Realizamos nuestra consulta
mysql_select_db($database_conexion, $conexion);
$query_facturas = "SELECT * FROM facturas where fecha>='$_POST[fecha1] 00:00:00' and fecha<='$_POST[fecha2] 23:59:59' and  transaccion!='DEVOLUCION'";
//$query_facturas = "SELECT * FROM facturas ";
$facturas = mysql_query($query_facturas, $conexion) or die(mysql_error());
$row_facturas = mysql_fetch_assoc($facturas);
$totalRows_facturas = mysql_num_rows($facturas);
		
        
		

         
	$efectivo=0;
	$debito=0;
	$cheque=0;
	$deposito=0;
//OBTENEMOS EL DINERO DE LAS CAJAS
do{
	
	
$iva=$iva + $row_facturas["iva"];	
	
	//validamos si los pagos fueron deun solo tipo
	if($row_facturas["efectivo"]<0 and $row_facturas["debito"]=="" and $row_facturas["cheque"]=="" and $row_facturas["deposito"]==""){
		$efectivo=$efectivo + $row_facturas["total"];
	}
	if($row_facturas["debito"]<0 and $row_facturas["efectivo"]=="" and $row_facturas["cheque"]=="" and $row_facturas["deposito"]==""){
		$debito=$debito + $row_facturas["total"];
		
	}
	if($row_facturas["efectivo"]=="" and $row_facturas["debito"]=="" and $row_facturas["cheque"]<0 and $row_facturas["deposito"]==""){
		$cheque=$cheque + $row_facturas["total"];
	}
	if($row_facturas["efectivo"]=="" and $row_facturas["debito"]=="" and $row_facturas["cheque"]=="" and $row_facturas["deposito"]<0){
		$deposito=$deposito + $row_facturas["total"];
	}
	//validamos si los pago fueron combinados y continuamos sumando
	
	if($row_facturas["efectivo"]>0 or $row_facturas["debito"]>0 or $row_facturas["cheque"]>0 or $row_facturas["deposito"]>0){
		if($row_facturas["efectivo"]!="") $efectivo=$efectivo + $row_facturas["efectivo"];
		if($row_facturas["debito"]!="") $debito=$debito + $row_facturas["debito"];
		if($row_facturas["cheque"]!="") $cheque=$cheque + $row_facturas["cheque"];
		if($row_facturas["deposito"]!="") $deposito=$deposito + $row_facturas["deposito"];
	}
	//sumamos las retenciones
	if($row_facturas["retencion"]!=""){
		$retencion=$retencion + $row_facturas["retencion"];
	}
		
	
//si el valor es null igualar a cero
$efe=0;
$deb=0;
$che=0;
$rete=0;
$dep=0;

if($row_facturas["efectivo"]!="") $efe=$row_facturas["efectivo"];
if($row_facturas["debito"]!="") $deb=$row_facturas["debito"];
if($row_facturas["cheque"]!="") $che=$row_facturas["cheque"];
if($row_facturas["deposito"]!="") $dep=$row_facturas["deposito"];
if($row_facturas["efectivo"]<0) $efe=$row_facturas["total"];
if($row_facturas["debito"]<0) $deb=$row_facturas["total"];
if($row_facturas["cheque"]<0) $che=$row_facturas["total"];
if($row_facturas["deposito"]<0) $dep=$row_facturas["total"];
if($row_facturas["retencion"]!="") $rete=$row_facturas["retencion"];

$pdf->Row(array($row_facturas["numero"], $row_facturas["serie"], number_format($efe,"2",",","."), number_format($deb,"2",",","."),number_format($che,"2",",","."), number_format($dep,"2",",","."), number_format($row_facturas["iva"],"2",",","."), number_format($rete,"2",",","."),number_format($row_facturas["total"],"2",",",".")));

}while($row_facturas = mysql_fetch_assoc($facturas));

$total=$efectivo+$debito+$cheque;
$pdf->Ln(2);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(5, $y, 200, 0.1, "DF");
	$pdf->Ln(2);
	$pdf->SetDrawColor(255, 255, 255);$pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 10);						
	$pdf->SetWidths(array(20, 20, 23, 23, 23, 23, 23, 23, 23));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->Row(array('','',number_format($efectivo,"2",",","."), number_format($debito,"2",",","."), number_format($cheque,"2",",","."), number_format($deposito,"2",",","."), number_format($iva,"2",",","."), number_format($retencion,"2",",","."), number_format($total,"2",",",".")));
		


$pdf->Output();


?>