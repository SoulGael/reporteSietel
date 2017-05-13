<?php  
include 'conexion.php';
conectarse();
$id=$_POST['g1'];

if(pg_query("delete from tbl_sietel_notin where id_sietel=".$id."")){
	echo "<div class='alert alert-success'><strong>✓</strong> Eliminado Correctamente</div>";
}else{
	echo "<div class='alert alert-danger'><strong>Error</strong> No se puede Eliminar Intentelo Nuevamente por favor</div>";
}
?>