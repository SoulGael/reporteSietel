<?php 
include 'conexion.php';
conectarse();

$consulta="SELECT i.id_capacidad,(select ubicacion from tbl_ubicacion u where i.nodo_princ=u.id_ubicacion), 
(select ubicacion from tbl_ubicacion u where i.provincia=u.id_ubicacion),
(select ubicacion from tbl_ubicacion u where i.ciudad=u.id_ubicacion),i.capacidad_interup, i.capacidad_interdown, i.proveedor 
FROM tbl_capacidad_inter i";

 echo '<div class="alert alert-info alert-success">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Atención </strong>Estos son los valores actuales de la capacidad internacional si esta de acuerdo precione <button type="button" class="btn btn-success btn-xs" onclick=cap_i_generar()>Aceptar</button> caso contrario por favor reviselas y modifiquelas si es necesario
          </div>';
echo '<table class="table table-hover">';
        echo '<thead>
                <tr>
                <th>Nodo Principal</th>
                <th>Provincia</th>
                <th>Ciudad</th>
                <th>Capacidad Internacional(Subida)</th>
                <th>Capacidad Internacional(Bajada)</th>
                <th>Proveedor</th>
            </tr>
          </thead>';
echo '<tbody>'; 
echo '<td>';
echo "<input class='form-control' type='text' id='txtnodo' disabled>";
echo '</td>';
echo '<td>';
echo '<select class="form-control" id="provincia" onChange="agregarciudades(this.form)">';
$consultapro="select distinct ubicacion, id_ubicacion from tbl_ubicacion, tbl_instalacion where id_ubicacion=id_provincia order by ubicacion";
$resultadopro=pg_query($consultapro) or die (pg_last_error());
while($tablapro=pg_fetch_array($resultadopro))
{
    echo "<option value=".$tablapro['id_ubicacion'].">".$tablapro['ubicacion']."</option>";
} 
echo '</select>';
echo '</td>';
echo '<td>';
echo "<select class='form-control' id='ciudades'>";
echo "</select>";
echo '</td>';
echo '<td>';
echo "<input class='form-control' type='text' id='capup'>";
echo '</td>';
echo '<td>';
echo "<input class='form-control' type='text' id='capdown'>";
echo '</td>';
echo '<td>';
echo "<input class='form-control' type='text' id='provedor'>";
echo '</td>';
echo '<td>';
echo "<button type='button' class='btn btn-success' onclick=cap_nuevo()>Guardar</button>";
echo '</td>';
echo '</tbody>'; 

$resultado=pg_query($consulta) or die (pg_last_error());

if(pg_num_rows($resultado)==0){
    echo '<b>No hay </b>';
}

else{
    while($fila=pg_fetch_array($resultado)){
        echo '<tbody id='.$fila[0].'>'; 
            echo '<td>';
            echo "<input class='form-control' type='text' value='".$fila[1]."' disabled>";
            echo '</td>';
            echo '<td>';
            echo "<input class='form-control' type='text' value='".$fila[2]."' disabled>";
            echo '</td>';
            echo '<td>';
            echo "<input class='form-control' type='text' value='".$fila[3]."' disabled>";
            echo '</td>';
            echo '<td>';
            echo "<input class='form-control' type='text' id='capinterup".$fila[0]."' value='".$fila[4]."'>";
            echo '</td>';
            echo '<td>';
            echo "<input class='form-control' type='text' id='capinterdown".$fila[0]."' value='".$fila[5]."'>";
            echo '</td>';
            echo '<td>';
            echo "<input class='form-control' type='text' id='capinterproveedor".$fila[0]."' value='".$fila[6]."'>";
            echo '</td>';
            echo '<td>';
            echo "<button type='button' class='btn btn-success' onclick=cap_i(".$fila[0].")>Modificar</button>";
            echo '</td>';
            echo '<td>';
            echo "<button type='button' class='btn btn-danger' onclick=cap_i_elim(".$fila[0].")>Eliminar</button>";
            echo '</td>';
        echo '</tbody>'; 
      }
  }
?>