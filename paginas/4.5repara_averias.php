<?php
include 'conexion.php';
conectarse();
error_reporting(0);
$m=$_GET['g1'];
$anio=$_GET['an'];

$f_inicio="";
$f_fin="";

//$year = date('Y');
$year = $anio;
//$m=01;

    $mes = mktime( 0, 0, 0, $m, 1, $year); 
    $numeroDeDias = intval(date("t",$mes));
    $f_inicio= "01"."-".(substr("0".$m, -2))."-".$year;
    $f_fin= $numeroDeDias."-".(substr("0".$m, -2))."-".$year;

/*$consulta="select
(select ub.ubicacion from tbl_ubicacion ub, tbl_instalacion ins where ins.id_instalacion=vta_orden_trabajo.id_instalacion and ub.id_ubicacion=ins.id_provincia)
provincia, razon_social,replace(replace( 
case when telefono<>'' then telefono else case when movil_claro<>'' then  left (movil_claro,10) else  left (movil_movistar,10) end end,'-',' '),'/',' ') as telefono,
'NO CONMUTADA', 'TELEFONICO', diagnostico_tecnico, (to_char(fecha_reporte, 'dd/mm/YYYY ') || to_char(hora_reporte, 'HH24:MI')) AS fecha_reporte, 
(to_char(fecha_solucion, 'dd/mm/YYYY ') || to_char(hora_solucion, 'HH24:MI')) Fecha_Solucion, 
age((fecha_solucion || ' ' || hora_solucion)::timestamp,(fecha_reporte || ' ' || hora_reporte)::timestamp ) tiempo_solucion, 
recomendacion
from vta_orden_trabajo 
where fecha_reporte between '".$f_inicio."' and '".$f_fin."' and estado='9' and hora_solucion is not null and diagnostico_tecnico not like '%INSTALACION%'
and recomendacion not in ('') and telefono is not null and movil_claro is not null and movil_movistar is not null and ot_procedente=true and recomendacion not like('%POR DES-INSTALACION DESDE ADMINISTRACION%')
and LOWER(recomendacion) not like upper('%compu%') and LOWER(recomendacion) not like('%red%') and LOWER(recomendacion) not like('%mal ponchado%')
and LOWER(recomendacion) not like('%virus%') and LOWER(recomendacion) not like('%formateo%') and LOWER(recomendacion) not like('%formatear%')
and LOWER(recomendacion) not like('%cortapico%')
order by tiempo_solucion limit 112";*/

$consulta="select (select ub.ubicacion from tbl_ubicacion ub, tbl_instalacion ins where ins.id_instalacion=s.id_instalacion and ub.id_ubicacion=ins.id_provincia) provincia, 
    razon_social,
    replace(substr(
        case 
          when telefono<>'' then telefono 
          else case 
            when movil_claro<>'' then movil_claro else movil_movistar 
          end 
        end,1,

        case 
          when 
          strpos(case when telefono<>'' then telefono else case when movil_claro<>'' then movil_claro else movil_movistar end end, '/')>0 
          then strpos(case when telefono<>'' then telefono else case when movil_claro<>'' then movil_claro else movil_movistar end end, '/')-1 
          else 10 
        end),'-', ' ') as telefono,
    'NO CONMUTADA', 
    'TELEFONICO',
    problema, 
    to_char(fecha_llamada, 'dd/mm/YYYY ') || to_char(hora_llamada, 'HH24:MI') AS fecha_reporte,
    (to_char(fecha_solucion, 'dd/mm/YYYY ') || to_char((hora_solucion)+ ' 1 minute'::interval , 'HH24:MI')) AS fecha_solucion, 
    to_char( age((fecha_solucion || ' ' || hora_solucion)::timestamp, (fecha_llamada || ' ' || hora_llamada)::timestamp), 'dd/mm/YYYY HH24:MI:SS' ) as tiempo_solucion, 
    recomendacion,
    e.*
    from vta_soporte s
    join tbl_encuesta_arcotel e on e.id_encuesta_arcotel=s.id_encuesta_arcotel
    where fecha_llamada between '".$f_inicio."' and '".$f_fin."' and estado='s' and recomendacion not in ('') and telefono not in ('') and e.procedente=true and e.codigo='4.5'
    order by tiempo_solucion";
//fecha_reporte, hora_reporte;"; 
$resultado=pg_query($consulta) or die (pg_last_error());

    if(pg_num_rows($resultado)>0){

        if (PHP_SAPI == 'cli')
            die('Este archivo solo se puede ver desde un navegador web');

        /** Se agrega la libreria PHPExcel */
        require_once 'lib/PHPExcel/PHPExcel.php';

        // Se crea el objeto PHPExcel
        $objPHPExcel = new PHPExcel();

        // Se asignan las propiedades del libro
        $objPHPExcel->getProperties()->setCreator("Romero Giovanni-0995988018") //Autor
                             ->setLastModifiedBy("Saitel") //Ultimo usuario que lo modificó
                             ->setTitle("4.5 Tiempo promedio reparacion averias")
                             ->setSubject("Reporte Sietel")
                             ->setDescription("Reporte Tiempo promedio reparacion de averias")
                             ->setKeywords("reparacion de averias")
                             ->setCategory("Reportes Saitel a Sietel");

        $tituloReporte = "TIEMPO PROMEDIO DE REPARACIÓN DE AVERÍAS (TÉCNICAS)";
        $subtituloReporte = "1) DATOS DEL INGRESO DEL RECLAMO";
        $subsubtituloReporte = "2) DETALLES DEL RECLAMO";
        $titulosColumnas = array('ITEM', 'PROVINCIA', 'NOMBRE DE LA PERSONA QUE REALIZA EL REQUERIMIENTO',
            'NÚMERO TELEFÓNICO DE CONTACTO DEL USUARIO','TIPO DE CONEXIÓN (CONMUTADA O NO CONMUTADA)',
            'CANAL DE REQUERIMIENTO (PERSONALIZADO, TELEFÓNICO, OFICIO, CORREO ELECTRÓNICO, PÁGINA WEB)', 'TIPO DE AVERÍA', 
            'FECHA Y HORA DE REPORTE DE LA AVERÍA (dd/mm/aaaa hh:mm)','FECHA Y HORA DE REPARACIÓN DE LA AVERÍA (dd/mm/aaaa hh:mm)',
            'TIEMPO DE REPARACIÓN DE LA AVERÍA (calculo en HORAS) ( Campo No obligatorio)','DESCRIPCIÓN DE LA SOLUCIÓN');
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->mergeCells('A1:K1')
                    ->mergeCells('A2:E2')
                    ->mergeCells('F2:K2');
                        
        // Se agregan los titulos del reporte
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1',$tituloReporte)
                    ->setCellValue('A2',$subtituloReporte)
                    ->setCellValue('H2',$subsubtituloReporte)
                    ->setCellValue('A3',  $titulosColumnas[0])
                    ->setCellValue('B3',  $titulosColumnas[1])
                    ->setCellValue('C3',  $titulosColumnas[2])
                    ->setCellValue('D3',  $titulosColumnas[3])
                    ->setCellValue('E3',  $titulosColumnas[4])
                    ->setCellValue('F3',  $titulosColumnas[5])
                    ->setCellValue('G3',  $titulosColumnas[6])
                    ->setCellValue('H3',  $titulosColumnas[7])
                    ->setCellValue('I3',  $titulosColumnas[8])
                    ->setCellValue('J3',  $titulosColumnas[9])
                    ->setCellValue('K3',  $titulosColumnas[10]);
        
        //Se agregan los datos de los alumnos
        $i = 4;
        while ($fila =pg_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  ($i-3))
                    ->setCellValue('B'.$i,  $fila[0])
                    ->setCellValue('C'.$i,  $fila[1])
                    ->setCellValue('D'.$i,  $fila[2])
                    ->setCellValue('E'.$i,  $fila[3])
                    ->setCellValue('F'.$i,  $fila[4])
                    ->setCellValue('G'.$i,  $fila[5])
                    ->setCellValue('H'.$i,  $fila[6])
                    ->setCellValue('I'.$i,  $fila[7])
                    ->setCellValue('J'.$i,  $fila[8])
                    ->setCellValue('K'.$i,  $fila[9]);
                    $i++;
        }
        
        $estiloTituloReporte = array(
            'font' => array(
                'name'      => 'Calibri',
                'bold'      => false,
                'italic'    => false,
                'strike'    => false,
                'size' =>12
                //'color'     => array('rgb' => '96BEE6')
            ),
            'fill' => array(
                'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb' => '96BEE6')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN                    
                )
            ), 
            'alignment' =>  array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    //'wrap'          => TRUE
            )
        );

        $estiloTituloColumnas = array(
            'font' => array(
                'name'      => 'Arial',
                'bold'      => true,                          
                'color'     => array(
                    'rgb' => 'FFFFFF'
                )
            ),
            'fill'  => array(
                'type'      => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
                'startcolor' => array(
                    'rgb' => 'c47cf2'
                ),
                'endcolor'   => array(
                    'argb' => 'FF431a5d'
                )
            ),
            'borders' => array(
                'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
                    'color' => array(
                        'rgb' => '143860'
                    )
                ),
                'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
                    'color' => array(
                        'rgb' => '143860'
                    )
                )
            ),
            'alignment' =>  array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap'          => TRUE
            ));
            
        $estiloInformacion = new PHPExcel_Style();
        $estiloInformacion->applyFromArray(
            array(
                'font' => array(
                'name'      => 'Arial',               
                'color'     => array(
                    'rgb' => '000000'
                )
            ),
            'fill'  => array(
                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                'color'     => array('argb' => 'FFd9b7f4')
            ),
            'borders' => array(
                'left'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN ,
                    'color' => array(
                        'rgb' => '3a2a47'
                    )
                )             
            )
        ));
         
        $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A2:K2')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A3:K3')->applyFromArray($estiloTituloReporte);
        //$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($estiloTituloColumnas);     
        //$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:D".($i-1));
        
        for($i = 'A'; $i <= 'K'; $i++){
            $objPHPExcel->setActiveSheetIndex(0)            
                ->getColumnDimension($i)->setAutoSize(TRUE);
        }
        
        // Se asigna el nombre a la hoja
        $objPHPExcel->getActiveSheet()->setTitle('Hoja1');

        // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
        $objPHPExcel->setActiveSheetIndex(0);
        // Inmovilizar paneles 
        //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);
        ini_set("memory_limit","1000M");
        ini_set('max_execution_time', 300);

        // Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="4.5 Tiempo promedio reparacion averias '.$f_inicio.'.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
        
    }
    else{
        print_r('No hay resultados para mostrar');
    }

?>