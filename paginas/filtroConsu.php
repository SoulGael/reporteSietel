<?php 
include 'conexion.php';
conectarse();
$q=$_POST['q'];
$cont=1;

$consulta="select id_activo, descripcion, valor_compra from tbl_activo where UPPER(descripcion) like (UPPER('%".$q."%')) and id_categoria='UIP-1' order by descripcion limit 100";

$resultado=pg_query($consulta) or die (pg_last_error());

if(pg_num_rows($resultado)==0){
echo '<b>No hay </b>';

}
else{
        echo '<table id="selectable" name="selectable" class="table table-hover">';
        echo '<thead bgcolor="#FF4900">
                <tr>
                <th>N°</th>
                <th>Descripcion</th>
                <th>Valor de Compra</th>        
                <th colspan="2"><input name="checktodos" type="checkbox" onchange=seleccionar(this)> </th>
                </tr>
              </thead>';
    while($fila=pg_fetch_array($resultado)){
        //echo '<tr onclick=salida(this)>';
        echo '<tr>';
        echo '<td>';
        echo $fila[0];
        echo '</td>';
        echo '<td>';
        echo $fila[1];
        echo '</td>';
        echo '<td>';
        echo $fila[2];
        echo '</td>';
        echo '<td>';        
        echo '<input type="checkbox" name="condi" value="'.$fila[0].'" id="condiciones'.$cont.'">';
        echo '</td>';            
        echo '</tr>';
        $cont++;
    }
  echo '</table>';
    
    }

 ?>