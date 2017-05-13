<?php  
include 'conexion.php';
conectarse();
$q=$_POST['prov'];
$consulta="select id_ubicacion, ubicacion 
from tbl_ubicacion 
where id_padre='".$q."'";
$resultado=pg_query($consulta) or die (pg_last_error());

if(pg_num_rows($resultado)==0){

echo '<b>No hay sugerencias</b>';

}else{
	echo "<option> </option>";
    while($tabla=pg_fetch_array($resultado))
        {
            echo "<option value=".$tabla['id_ubicacion'].">".$tabla['ubicacion']."</option>";
        }
}

 ?>