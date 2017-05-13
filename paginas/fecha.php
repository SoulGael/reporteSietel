<?php
$year = date('Y');
//$week = date('W');
//$fechaInicioSemana  = date('d-m-Y', strtotime($year . 'W' . str_pad($week , 2, '0', STR_PAD_LEFT)));
//echo "fecha:".date('d-m-Y', strtotime($fechaInicioSemana.' 1 day'));

$m=01;
$fin=$m+11;
for ($i=$m; $i <=$fin ; $i++) { 
	$mes = mktime( 0, 0, 0, $i, 1, $year); 
	$numeroDeDias = intval(date("t",$mes));
	echo "01"."-".(substr("0".$i, -2))."-".$year."<br>";
	echo $numeroDeDias."-".(substr("0".$i, -2))."-".$year."<br><br>";
}
//echo $mes;
//echo "El mes de ".date("F Y",$mes)." tiene ".date("t",$mes)." dias";

//$mes= date('m',2)."-".date('Y'); 
//echo $mes;




//echo " Desde 01-$mes  hasta $fin-$mes";  
?>