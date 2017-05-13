<?php
function conectarse() {
	//if (!($conexion = pg_connect("host='138.185.136.142' port=5432 dbname='db_isp' user='postgres' password='postgres'"))) 
    if (!($conexion = pg_connect("host='localhost' port=5432 dbname='db_isp' user='postgres' password='postgres'"))) 
    {			
        exit();
    }
    else {    
    }
    return $conexion;
    pg_close($conexion);
}
conectarse();
?>