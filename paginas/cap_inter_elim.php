<?php  
include 'conexion.php';
conectarse();
$id=$_POST['id'];

	if(pg_query("delete from tbl_capacidad_inter where id_capacidad=".$id.""))
	{
		echo "<td colspan='7'><div class='alert alert-success'>Eliminado Correctamente</div></td>";
	}
	else
	{
	echo "<td colspan='7'><div class='alert alert-danger'>No se pudo Eliminar Intentelo Nuevamente</div></td>";
	}
?>