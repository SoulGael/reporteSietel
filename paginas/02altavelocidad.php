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

$consulta="select get_mes(P.fecha_prefactura) as periodo, PR.provincia, C.ciudad, PA.parroquia,
    I.razon_social, I.direccion_instalacion,
    REPLACE(substr(I.telefono, 1, case when strpos(I.telefono, '/')>0 then strpos(I.telefono, '/')-1 else 10 end),'-',' ') as telefono,
    '1','CNT','MEDIO INALAMBRICO',
    PS.burst_limit as up_link, PS.burst_limit as down_link, replace(
            replace(
                replace(
                    replace(
                        replace(
                            replace(
                                replace(
                                    replace(PS.solo_plan, 'BANDAMAX HOME (RESIDENCIAL)','RESIDENCIAL'), 
                                    'SERVICIO DE CORREO (SMALL)','CIBERCAFE'), 
                                'BANDAMAX PYMES (SMALL)','CIBERCAFE'),
                            'ESPECIAL',' '),
                        'SMALL','CIBERCAFE'),
                    'NOCTURNO','RESIDENCIAL'),
                'PROFESIONAL GEPON (CIBERCAFE)','CIBERCAFE'),
            'CIBERCAFE PYME FFTH','CIBERCAFE'),
    (PS.comparticion::int ||' : 1') 
    from vta_prefactura_todas P
    inner join vta_instalacion I on P.id_instalacion=I.id_instalacion
    inner join vta_provincia PR on PR.id_provincia=I.id_provincia
    inner join vta_ciudad C on C.id_ciudad=I.id_ciudad
    inner join vta_parroquia as PA on PA.id_parroquia=I.id_parroquia
    inner join vta_plan_servicio as PS on PS.id_plan_servicio=I.id_plan_actual 
    where P.fecha_prefactura between '".$f_inicio."' and '".$f_fin."'
    and P.fecha_emision is not null
    and PR.provincia not in(select nombre from tbl_sietel_notin where tipo=1)
    and C.ciudad not in (select nombre from tbl_sietel_notin where tipo=2)
    and PA.parroquia not in(select nombre from tbl_sietel_notin where tipo=3)
    order by (P.fecha_prefactura), PR.provincia, C.ciudad, PA.parroquia, I.razon_social;"; 


$resultado=pg_query($consulta) or die (pg_last_error());

    if(pg_num_rows($resultado)>0){

        if (PHP_SAPI == 'cli')
            die('Este archivo solo se puede ver desde un navegador web');

        /** Se agrega la libreria PHPExcel */
        require_once 'lib/PHPExcel/PHPExcel.php';

        // Se crea el objeto PHPExcel
        $objPHPExcel = new PHPExcel();

        // Se asignan las propiedades del libro
        $objPHPExcel->getProperties()->setCreator("Romero Giovanni-0987868133") //Autor
                             ->setLastModifiedBy("Saitel") //Ultimo usuario que lo modificó
                             ->setTitle("SNT-ISP-02 Alta Velocidad")
                             ->setSubject("Reporte Sietel")
                             ->setDescription("Reporte Lineas Dedicadas")
                             ->setKeywords("Lineas Dedicadas Saitel")
                             ->setCategory("Reportes Saitel a Sietel");

        $tituloReporte = "REPORTE DE SERVICIO CUENTAS ALTA VELOCIDAD";
        $subtituloReporte = "ACCESO NO CONMUTADO - LÍNEAS DEDICADAS";
        $subsubtituloReporte = "MES";
        $titulosColumnas = array('Provincia', 'Cantón', 'Parroquia', 'Nombre del Usuario', 'Dirección', 'Teléfono', 
            'Número estimado de usuarios por cuenta', 'Empresa proveedora del canal (Portador)', 
            'Tipo de enlace: Cobre, Cable Coaxial, Fibra Óptica, Medio Inalámbrico', 'Ancho de banda Up Link (Kbps)', 
            'Ancho de banda Down Link (Kbps)', 'Tipo de Cliente (Residencial, Corporativo, Cibercafé)', 'Nivel de Compartición');
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->mergeCells('A1:N1')
                    ->mergeCells('B2:N2')
                    ->mergeCells('A2:A3');
                        
        // Se agregan los titulos del reporte
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1',$tituloReporte)
                    ->setCellValue('B2',$subtituloReporte)
                    ->setCellValue('A2',$subsubtituloReporte)
                    ->setCellValue('B3',  $titulosColumnas[0])
                    ->setCellValue('C3',  $titulosColumnas[1])
                    ->setCellValue('D3',  $titulosColumnas[2])
                    ->setCellValue('E3',  $titulosColumnas[3])
                    ->setCellValue('F3',  $titulosColumnas[4])
                    ->setCellValue('G3',  $titulosColumnas[5])
                    ->setCellValue('H3',  $titulosColumnas[6])
                    ->setCellValue('I3',  $titulosColumnas[7])
                    ->setCellValue('J3',  $titulosColumnas[8])
                    ->setCellValue('K3',  $titulosColumnas[9])
                    ->setCellValue('L3',  $titulosColumnas[10])
                    ->setCellValue('M3',  $titulosColumnas[11])
                    ->setCellValue('N3',  $titulosColumnas[12]);
        
        //Se agregan los datos de los alumnos
        $i = 4;
        while ($fila =pg_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  $fila[0])
                    ->setCellValue('B'.$i,  $fila[1])
                    ->setCellValue('C'.$i,  $fila[2])
                    ->setCellValue('D'.$i,  $fila[3])
                    ->setCellValue('E'.$i,  $fila[4])
                    ->setCellValue('F'.$i,  $fila[5])
                    ->setCellValue('G'.$i,  $fila[6])
                    ->setCellValue('H'.$i,  $fila[7])
                    ->setCellValue('I'.$i,  $fila[8])
                    ->setCellValue('J'.$i,  $fila[9])
                    ->setCellValue('K'.$i,  $fila[10])
                    ->setCellValue('L'.$i,  $fila[11])
                    ->setCellValue('M'.$i,  $fila[12])
                    ->setCellValue('N'.$i,  $fila[13]);
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
         
        $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A2:N2')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A3:N3')->applyFromArray($estiloTituloReporte);
        //$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($estiloTituloColumnas);     
        //$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:D".($i-1));
        
        for($i = 'A'; $i <= 'N'; $i++){
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
        header('Content-Disposition: attachment;filename="SNT-ISP-02 Alta Velocidad'.$f_inicio.'.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
        
    }
    else{
        print_r('No hay resultados para mostrar');
    }

?>