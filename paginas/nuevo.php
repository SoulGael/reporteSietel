<?php  
include 'conexion.php';
conectarse();
$v1=$_POST['g1'];
$nombre=$_POST['g2'];

if(pg_query("insert into tbl_sietel_notin (nombre, tipo) values ('".$nombre."',".$v1.")"))
{
	echo "<div class='alert alert-success'><strong>".$nombre."</strong> Guardado Correctamente</div>";
}
else {
	echo "<div class='alert alert-danger'><strong>".$nombre."</strong> No se pudo Guardar Intentelo Nuevamente</div>";
}
?>