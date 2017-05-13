<?php
include 'conexion.php';
conectarse();
error_reporting(0);
$m=$_GET['g1'];
$anio=$_GET['an'];
$trimestre=$_GET['tri'];

$f_inicio="";
$f_fin="";

//$year = date('Y');
$year = $anio;
$m_f=$m+5;
$m_fin=substr("0".$m_f, -2);


    $mes = mktime( 0, 0, 0, $m_fin, 1, $year); 
    $numeroDeDias = intval(date("t",$mes));
    $f_inicio= "01"."-".(substr("0".$m, -2))."-".$year;
    $f_fin= $numeroDeDias."-".(substr("0".$m_fin, -2))."-".$year;

$consulta="select CASE WHEN id_sucursal=1 THEN 'IMBABURA'
            WHEN id_sucursal=2 THEN 'ORELLANA'
            WHEN id_sucursal=3 THEN 'PICHINCHA'
            WHEN id_sucursal=4 THEN 'CARCHI'
            WHEN id_sucursal=5 THEN 'MANABI'
            WHEN id_sucursal=6 THEN 'MANABI'
            WHEN id_sucursal=7 THEN 'PICHINCHA'
            WHEN id_sucursal=8 THEN 'PICHINCHA'
            WHEN id_sucursal=9 THEN 'COTOPAXI'
            WHEN id_sucursal=10 THEN 'ESMERALDAS'
            WHEN id_sucursal=11 THEN 'PICHINCHA'
            WHEN id_sucursal=12 THEN 'ORELLANA'
       END, ceiling((p1::int+p2::int)/2), p3::int, ceiling((p4::int+p5::int)/2), c1||' '||c2||' '||c3||' '||c4||' '||c5 
from tbl_encuesta where fecha  between '".$f_inicio."' and '".$f_fin."'"; 

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
                             ->setTitle("4.1 Relacion con el cliente")
                             ->setSubject("Reporte Sietel")
                             ->setDescription("Reporte Relacion con el Cliente")
                             ->setKeywords("Relacion con el Cliente")
                             ->setCategory("Reportes Saitel a Sietel");

        $tituloReporte = "RELACIÓN CON EL CLIENTE";
        $subtituloReporte = "1) PERIODO DE MEDICIÓN DE LA ENCUESTA";
        $subtituloReporte1 = "2) PROVINCIA";
        $subtituloReporte2= "3) NÚMERO DE ENCUESTADOS";
        $subtituloReporte3 = "4) TIPO DE CONEXIÓN (Conmutada /No Conmutada)";
        $subtituloReporte4 = "5) ÍNDICE CI";
        $subtituloReporte5 = "PERCEPCIÓN GENERAL DEL TRATO AL CLIENTE";
        $subtituloReporte6 = "AMABILIDAD (Valor entre 1 y 5)";
        $subtituloReporte7 = "DISPONIBILIDAD (Valor entre 1 y 5)";
        $subtituloReporte8 = "RAPIDÉZ (Valor entre 1 y 5)"; 
        $subtituloReporte9 = "6) RELACIÓN CON EL CLIENTE (No obligatorio )";
        $subtituloReporte10 = "7) OBSERVACIONES (No obligatorio )";
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->mergeCells('A1:I1')
                    ->mergeCells('A2:A4')
                    ->mergeCells('B2:B4')
                    ->mergeCells('C2:C4')
                    ->mergeCells('D2:D4')
                    ->mergeCells('E2:G2')
                    ->mergeCells('E3:G3')
                    ->mergeCells('H2:H4')
                    ->mergeCells('I2:I4');
                        
        // Se agregan los titulos del reporte
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1',$tituloReporte)
                    ->setCellValue('A2',$subtituloReporte)
                    ->setCellValue('B2',$subtituloReporte1)
                    ->setCellValue('C2',$subtituloReporte2)
                    ->setCellValue('D2',$subtituloReporte3)
                    ->setCellValue('E2',$subtituloReporte4)
                    ->setCellValue('E3',$subtituloReporte5)
                    ->setCellValue('E4',$subtituloReporte6)
                    ->setCellValue('F4',$subtituloReporte7)
                    ->setCellValue('G4',$subtituloReporte8)
                    ->setCellValue('H2',$subtituloReporte9)
                    ->setCellValue('I2',$subtituloReporte10);
        
        //Se agregan los datos de los alumnos
        $i = 5;
        while ($fila =pg_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  $trimestre)
                    ->setCellValue('B'.$i,  $fila[0])
                    ->setCellValue('C'.$i,  1)
                    ->setCellValue('D'.$i,  "NO CONMUTADA")
                    ->setCellValue('E'.$i,  $fila[1])
                    ->setCellValue('F'.$i,  $fila[2])
                    ->setCellValue('G'.$i,  $fila[3])
                    ->setCellValue('H'.$i,  "")
                    ->setCellValue('I'.$i,  $fila[4]);
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
         
        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->applyFromArray($estiloTituloReporte);
        //$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($estiloTituloColumnas);     
        //$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:D".($i-1));
        
        for($i = 'A'; $i <= 'I'; $i++){
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
        ini_set('max_execution_time', 3000);

        // Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="4.1 Relacion con el cliente'.$trimestre.' '.$year.'.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
        
    }
    else{
        print_r('No hay resultados para mostrar');
    }

?>