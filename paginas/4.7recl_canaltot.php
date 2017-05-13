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
    
    $consulta="select i.id_instalacion
from (((((tbl_instalacion as I inner join tbl_prefactura as P on P.id_instalacion=I.id_instalacion) 
inner join vta_provincia as PR on PR.id_provincia=I.id_provincia)
inner join vta_ciudad as C on C.id_ciudad=I.id_ciudad)
inner join vta_parroquia as PA on PA.id_parroquia=I.id_parroquia) 
inner join tbl_cliente as CL on I.id_cliente=CL.id_cliente) 
inner join vta_plan_servicio as PS on PS.id_plan_servicio=I.id_plan_actual 
where fecha_prefactura between '".$f_inicio."' and '".$f_fin."' 
and P.fecha_emision is not null 
and PR.provincia not in(select nombre from tbl_sietel_notin where tipo=1)
and C.ciudad not in (select nombre from tbl_sietel_notin where tipo=2)
and PA.parroquia not in(select nombre from tbl_sietel_notin where tipo=3);";

    $resultado=pg_query($consulta) or die (pg_last_error());

    echo '<div class="alert alert-info alert-success">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Clientes '.$f_inicio.' al '.$f_fin.': </strong> '.pg_num_rows($resultado).'
          </div>';

} 

 ?>