<?php 
include 'conexion.php';
conectarse();
$m=$_POST['g1'];
$anio=$_POST['g2'];

$f_inicio="";
$f_fin="";
$cont=$m+2;

//$year = date('Y');
$year = $anio;
//$m=01;
for ($i=$m; $i <=$cont ; $i++) {
    $mes = mktime( 0, 0, 0, $i, 1, $year); 
    $numeroDeDias = intval(date("t",$mes));
    $f_inicio= "01"."-".(substr("0".$i, -2))."-".$year;
    $f_fin= $numeroDeDias."-".(substr("0".$i, -2))."-".$year;
    
    /*$consulta="select get_mes('".$f_inicio."'), sum(subtotal) from tbl_prefactura where fecha_prefactura between '".$f_inicio."' and '".$f_fin."'";*/

    $consulta="select get_mes('".$f_inicio."'), sum(subtotal) from tbl_prefactura 
    where fecha_prefactura between '".$f_inicio."' and '".$f_fin."'
    and fecha_emision is not null";

    $resultado=pg_query($consulta) or die (pg_last_error());

    if(pg_num_rows($resultado)==0){
    echo '<b>No hay </b>';

    }
    else{
        while($fila=pg_fetch_array($resultado)){
        echo '<div class="alert alert-info alert-success">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>'.$fila[0].' '.$year.':</strong> '.$fila[1].'</strong>
          </div>';
        }
    }
} 

 ?>