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


$consulta="select get_mes(p.periodo), i.ciudad, pi.plan, to_char(t.vigente_hasta-(10000),'DD/MM/YYYY'), count(pi.plan), 
replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(pi.plan, 'RESIDENCIAL GEPON','RESIDENCIAL'),'SMALL GEPON','CIBERCAFE'),'PROFESIONAL GEPON (SMALL)','CIBERCAFE'), 'BANDAMAX HOME (RESIDENCIAL)','RESIDENCIAL'), 'SERVICIO DE CORREO (SMALL)','CIBERCAFE'), 'BANDAMAX PYMES (SMALL)','CIBERCAFE'), 'ESPECIAL',' '),'SMALL','CIBERCAFE'),'NOCTURNO','RESIDENCIAL'),'PROFESIONAL GEPON (CIBERCAFE)','CIBERCAFE'),'CIBERCAFE PYME FFTH','CIBERCAFE') as solo,
t.costo_plan, (((ps.burst_limit)::numeric)/1000)::numeric(13,2) as downlink, (((ps.burst_limit)::numeric)/1000)::numeric(13,2) as uplink, (pi.comparticion::int ||':1'), 'WIMAX'
from tbl_prefactura p 
join vta_instalacion i on i.id_instalacion=p.id_instalacion
join tbl_tarifa t on i.id_plan_actual=t.id_plan_servicio
join tbl_plan_servicio ps on t.id_plan_servicio=ps.id_plan_servicio
join tbl_plan_isp pi on pi.id_plan_isp=ps.id_plan_isp
where p.periodo between '".$f_inicio."' and '".$f_fin."'
and p.fecha_emision is not null
group by get_mes(p.periodo), i.ciudad, t.vigente_hasta, pi.plan, t.costo_plan, ps.burst_limit, pi.comparticion
order by i.ciudad";

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
                             ->setTitle("Reporte de Tarifas Dedicadas")
                             ->setSubject("Reporte Sietel")
                             ->setDescription("Reporte Tarifa Dedicadas")
                             ->setKeywords("Tarifas Dedicadas")
                             ->setCategory("Reportes Saitel a Sietel");

        $tituloReporte = "TARIFAS DE INTERNET FIJO DEDICADO";
        $tituloReporte1 = "PLAN TARIFARIO";
        $tituloReporte2 = "CARACTERISTICAS TECNICAS";

        $titulosColumnas = array('MES', 'CIUDAD', 'NOMBRE COMERCIAL DEL PLAN TARIFARIO',
            'FECHA DE VIGENCIA DEL PLAN TARIFARIO','CUENTAS','CANTIDAD ABONADOS/CLIENTES', 'TIPO (RESIDENCIAL, CORPORATIVO, CIBERCAFE)',
            'TARIFA MENSUAL [USD] (incluido impuestos)','VELOCIDAD','DOWNLINK [Mbps]','UPLINK [Mbps]','NIVEL DE COMPARTICIÓN [X:1]',
            'TECNOLOGÍA  (ADSL, HFC, FTTH, WIMAX, WIFI, OTROS)','OBSERVACIONES (Opcional)');
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->mergeCells('A1:L1')
                    ->mergeCells('A2:F2')
                    ->mergeCells('H2:K2')
                    ->mergeCells('A3:A4')
                    ->mergeCells('B3:B4')
                    ->mergeCells('C3:C4')
                    ->mergeCells('D3:D4')
                    ->mergeCells('E3:F3')
                    ->mergeCells('G3:G4')
                    ->mergeCells('H3:I3')
                    ->mergeCells('J3:J4')
                    ->mergeCells('K3:K4')
                    ->mergeCells('L2:L4');
                        
        // Se agregan los titulos del reporte
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1',$tituloReporte)
                    ->setCellValue('A2',$tituloReporte1)
                    ->setCellValue('H2',$tituloReporte2)
                    ->setCellValue('G2','')
                    ->setCellValue('A3',  $titulosColumnas[0])
                    ->setCellValue('B3',  $titulosColumnas[1])
                    ->setCellValue('C3',  $titulosColumnas[2])
                    ->setCellValue('D3',  $titulosColumnas[3])
                    ->setCellValue('E3',  $titulosColumnas[4])
                    ->setCellValue('E4',  $titulosColumnas[5])
                    ->setCellValue('F4',  $titulosColumnas[6])
                    ->setCellValue('G3',  $titulosColumnas[7])
                    ->setCellValue('H3',  $titulosColumnas[8])
                    ->setCellValue('H4',  $titulosColumnas[9])
                    ->setCellValue('I4',  $titulosColumnas[10])
                    ->setCellValue('J3',  $titulosColumnas[11])
                    ->setCellValue('K3',  $titulosColumnas[12])
                    ->setCellValue('L2',  $titulosColumnas[13]);
        
        //Se agregan los datos de los alumnos
        $i = 5;
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
                    ->setCellValue('L'.$i,  '');
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
         
        $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A2:L2')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A3:L3')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A4:L4')->applyFromArray($estiloTituloReporte);
        //$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($estiloTituloColumnas);     
        //$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:D".($i-1));
        
        for($i = 'A'; $i <= 'L'; $i++){
            $objPHPExcel->setActiveSheetIndex(0)            
                ->getColumnDimension($i)->setAutoSize(TRUE);
        }
        
        // Se asigna el nombre a la hoja
        $objPHPExcel->getActiveSheet()->setTitle('Hoja1');

        // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
        $objPHPExcel->setActiveSheetIndex(0);
        // Inmovilizar paneles 
        //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,5);
        ini_set("memory_limit","1000M");
        ini_set('max_execution_time', 300);

        // Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte de Tarifas Dedicado'.$f_inicio.'.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
        
    }
    else{
        print_r('No hay resultados para mostrar');
    }

?>