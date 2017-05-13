<?php  
include 'conexion.php';
conectarse();
$id=$_POST['g1'];
$nomb=$_POST['g2'];

if(strcmp($id, 'p') == 0)
{
	echo "<div class='alert alert-success'><strong>".$nomb."</strong> Provincia Ingresada Correctamente</div>";
}
if(strcmp($id, 'c') == 0)
{
	echo "<div class='alert alert-success'><strong>".$nomb."</strong> Ciudad Ingresada Correctamente</div>";
}
if(strcmp($id, 'q') == 0)
{
	echo "<div class='alert alert-success'><strong>".$nomb."</strong> Parroquia Ingresada Correctamente</div>";
}

//if(pg_query("update tbl_comun set nombre='".$nomb."' where id_comun='".$id."'"))
//{
//	echo "<div class='alert alert-success'><strong>".$nomb."</strong> Modificado Correctamente</div>";
//}
//else {
//	echo "<div class='alert alert-danger'><strong>".$nomb."</strong> No se pudo Modificar Intentelo Nuevamente</div>";
//}
?>