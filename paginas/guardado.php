<?php  
include 'conexion.php';
conectarse();
$id=$_POST['id'];
$nombre=$_POST['no'];
$activo=$_POST['ac'];

$ids = explode(",", $id);
$longitud=count($ids);
$nombres = explode(",", $nombre);

for($n=0;$n<$longitud;$n++)
{
	if(pg_query("update tbl_activo set id_categoria='".$activo."' where id_activo='".$ids[$n]."'"))
	{
		echo "<div class='alert alert-success'><strong>".$nombres[$n]."</strong> en <strong>".$activo."</strong> Guardado Correctamente</div>";
	}
	else
	{
	echo "<div class='alert alert-danger'><strong>".$nombres[$n]."</strong> en <strong>".$activo."</strong>  No se pudo Guardar Intentelo Nuevamente</div>";
	}
}
?>