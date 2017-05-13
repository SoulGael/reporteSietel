<?php  
include 'conexion.php';
conectarse();
$up=$_POST['up'];
$down=$_POST['down'];
$id=$_POST['id'];
$prov=$_POST['pro'];

	if(pg_query("update tbl_capacidad_inter set capacidad_interup='".$up."', capacidad_interdown='".$down."', proveedor='".$prov."' where id_capacidad=".$id.""))
	{
		echo "<td colspan='7'><div class='alert alert-success'>Guardado Correctamente</div></td>";
	}
	else
	{
	echo "<td colspan='7'><div class='alert alert-danger'>No se pudo Modificar Intentelo Nuevamente</div></td>";
	}
?>