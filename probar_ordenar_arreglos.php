<?

//ordenar de mayor a menor (orden inverso... Reverse order) 
$alumnos = array(234,545,6755,122,33,545,1999); 
rsort($alumnos); 
foreach ($alumnos as $key => $val) { 
    echo "alumnos[" . $key . "] = " . $val . "<br>"; 
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
</body>
</html>