<?php
include 'paginas/conexion.php';
conectarse();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Saitel - Reportes SIETEL</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>
    <script src="js/jquery-1.9.1.js"></script>

    <script src="js/alertify.min.js"></script>
    <link rel="stylesheet" href="css/alertify.core.css" />
    <link rel="stylesheet" href="css/alertify.default.css" id="toggleCSS" />
    <script src="js/busqueda.js"></script>


    <!--Css u Js para el Calendario-->
    <!--
    <link rel="stylesheet" href="css/jquery-ui.css">
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/jquery-ui.js"></script>

    <script>
    $(function() {
      $( "#datepicker" ).datepicker({
        showOn: "button",
        buttonImage: "img/calendar.gif",
        buttonImageOnly: true,
        buttonText: "Select date",
        changeMonth: true,
      changeYear: true
      });
    });
    </script>
    -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]
    <script>-->

  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Sistema de Reportes para SIETEL</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Reportes SIETEL</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" role="form">
            
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <p><STRONG>INSTRUCCIONES: </STRONG></p>
        <p>* Si no se encuentra el Formato en la Lista debe ponerlo como: No se da el Servicio </p>
        <p>* Si sale un error en el reporte guardar la ciudad del error exactamente como esta el nombre del archivo, en la columna que sale en la SIETEL con la del archivo generado.</p>
      </div>
    </div>
    <br>

    <div class="container">
      <!-- Example row of columns -->
      <div class="input-group input-group-sm" STYLE="z-index:1">
        <span class="input-group-addon">Registro de Nuevas Prov, Ciu, Parr</span>
          <select id="admin" class="form-control" onchange=nuevo(this)>
            <option>Seleccione una Opcion</option>
            <option value="p">Provincias no Registradas</option>
            <option value="c">Cuidades no Registradas</option>
            <option value="q">Parroquias no Registradas</option>
            <option value="e">Eliminar Registro</option>
          </select>
        <span class="input-group-addon">Prov, Ciu, Parr no registradas</span>
          <select id="regist" class="form-control">
            <?php
              $consulta="select id_sietel, nombre from tbl_sietel_notin order by nombre";
              $resultado=pg_query($consulta) or die (pg_last_error());
              while($tabla=pg_fetch_array($resultado))
              {
                echo "<option value=".$tabla['id_sietel'].">".$tabla['nombre']."</option>";
              } 
            ?> 
          </select>
        </span>
      </div>
      <li class="divider"></li>

      <div class="input-group input-group-sm" STYLE="z-index:1">
        <span class="input-group-addon">Perido</span>
        <select id="trimestre" class="form-control">
          <option value=1>Enero-Marzo</option>
          <option value=4>Abril-Junio</option>
          <option value=7>Julio-Septiembre</option>
          <option value=10>Octubre-Diciembre</option>
        </select>
        <span class="input-group-addon">Año</span>
        <select id="anio" class="form-control">
            <?php
              $year = date('Y');
              $fin = $year+1;
              for ($i=$year-1; $i <$fin ; $i++) { 
                echo "<option value=".$i.">".$i."</option>";
              }
            ?> 
        </select>
        <span class="input-group-addon">Tipo de Reporte</span>
        <select id="formato" class="form-control">
          <optgroup label="Reporte de Usuarios">
            <option value='1'>SNT-ISP-01 Dial Up</option>
            <option value='2'>SNT-ISP-02 Alta Velocidad</option>
            <option value='3'>SNT-ISP-09 Total Ingresos F.</option>
          </optgroup>
          <optgroup label="Reporte de Calidad">
            <option value='5'>4.1 Relación con el cliente</option>
            <option value='6'>4.2-4.3 Porcentaje reclamos Generales</option>
            <option value='7'>4.4 Porcentaje Reclamos Facturas</option>
            <option value='8'>4.5 Tiempo promedio reparacion averias</option>
            <option value='9'>4.6 Porcentaje modems utilizados</option>
            <option value='10'>4.7 Porcentaje reclam. capacidad de canal</option>
            <option value='11'>Anexo 2 Capacidad internacional contratada.</option>
          </optgroup>
          <optgroup label="Reporte de Tarifas">
            <option value='12'>Reporte de Tarifas Dedicado</option>
            <option value='13'>Reporte de Tarifas Dial up</option>
          </optgroup>
        </select>
        <span class="input-group-addon" class="form-control">
        </span>
        <button type="button" class="btn btn-success form-control" onclick=gener(this)>Generar</button>
      </div>

      <script type="text/javascript">

      function agregarciudades(){ 
        consuciudades(provincia.value);                        
      }

      function cap_i_generar(){
        window.open('paginas/anexo_cap_inter.php');
      }

      function cap_i(id){
        /*alert(document.getElementById("capinterup"+id).value);
        alert(document.getElementById("capinterdown"+id).value);*/
        cap_inter_modi(document.getElementById("capinterup"+id).value,document.getElementById("capinterdown"+id).value,document.getElementById("capinterproveedor"+id).value,id);
      }

      function cap_i_elim(id){
       cap_i_el(id);
      }

      function cap_nuevo(){
        cap_nue(document.getElementById("provincia").value,
          document.getElementById("ciudades").value,
          document.getElementById("capup").value,
          document.getElementById("capdown").value,
          document.getElementById("provedor").value);
      }

      function gener(){
        //alert(trimestre.value+"-"+formato.value);
        var valorformato=formato.value;
        if(valorformato==1)
        {
          document.getElementById("resultados").innerHTML='<div class="alert alert-info alert-dismissable">'+
          '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
          '<strong>¡Atento! No</strong> Prestamos este servicio'+
          '</div>';
        }
        if(valorformato==2)
        {
          var mes=parseInt(trimestre.value);
          document.getElementById("resultados").innerHTML='<div class="alert alert-info alert-dismissable">'+
          '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
          '<strong>¡Atento!</strong> Al momento de subir esta información se generaran generada automáticamente de los formularios SNT-ISP-03, SNT-ISP-04, SNTISP-05, SNT-ISP-06, SNT-ISP-07 y SNT-ISP-08.'+
          '</div>';
          //window.open('1LineasDedicadas.php?fec='+fe+'&des='+desde.value+'&has='+hasta.value);
          window.open('paginas/02altavelocidad.php?g1='+(mes)+'&an='+anio.value);
          window.open('paginas/02altavelocidad.php?g1='+(mes+1)+'&an='+anio.value);
          window.open('paginas/02altavelocidad.php?g1='+(mes+2)+'&an='+anio.value);
        }
        if(valorformato==3)
        {
          tot_in(trimestre.value,anio.value);
        }
        if(valorformato==5)
        {
          var mesTri=parseInt(trimestre.value);
          var cont="";
          if (mesTri==1||mesTri==4) {
            cont="Enero-Junio";
            mesTri=1;
            window.open('paginas/4.1relacioncliente.php?g1='+(mesTri)+'&an='+anio.value+'&tri='+cont);
            document.getElementById("resultados").innerHTML='<div class="alert alert-info alert-dismissable">'+
            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
            '<strong>Recuerde!</strong>La Relacion con el cliente es semestral.'+
            '</div>';
          }
          if (mesTri==7||mesTri==10) {
            mesTri=7;
            cont="Julio-Diciembre";
            window.open('paginas/4.1relacioncliente.php?g1='+(mesTri)+'&an='+anio.value+'&tri='+cont);
            document.getElementById("resultados").innerHTML='<div class="alert alert-info alert-dismissable">'+
            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
            '<strong>Recuerde!</strong>Relacion con el cliente es semestral.'+
            '</div>';
          }
        }

        if(valorformato==6)
        {
          var mes=parseInt(trimestre.value);
          //alert(trimestre.value+" "+anio.value);
          rec_gen(trimestre.value,anio.value);
          window.open('paginas/4.2por_rec.php?g1='+(mes)+'&an='+anio.value);
          window.open('paginas/4.2por_rec.php?g1='+(mes+1)+'&an='+anio.value);
          window.open('paginas/4.2por_rec.php?g1='+(mes+2)+'&an='+anio.value);
        }

        if (valorformato==7) 
        {
          document.getElementById("resultados").innerHTML='<div class="alert alert-info alert-dismissable">'+
          '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
          '<strong>¡Atento! No</strong> Prestamos este Servicio'+
          '</div>';
        };

        if (valorformato==8) 
          {
            var mes=parseInt(trimestre.value);
            //alert(trimestre.value+" "+anio.value);
            //rec_gen(trimestre.value,anio.value);
            window.open('paginas/4.5repara_averias.php?g1='+(mes)+'&an='+anio.value);
            window.open('paginas/4.5repara_averias.php?g1='+(mes+1)+'&an='+anio.value);
            window.open('paginas/4.5repara_averias.php?g1='+(mes+2)+'&an='+anio.value);
          };

          if (valorformato==9) 
          {
            document.getElementById("resultados").innerHTML='<div class="alert alert-info alert-dismissable">'+
          '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
          '<strong>¡Atento! No</strong> Prestamos este Servicio'+
          '</div>';
          };

        if (valorformato==10) 
          {
            var mes=parseInt(trimestre.value);
            //alert(trimestre.value+" "+anio.value);
            rec_canal(trimestre.value,anio.value);
            window.open('paginas/4.7recl_canal.php?g1='+(mes)+'&an='+anio.value);
            window.open('paginas/4.7recl_canal.php?g1='+(mes+1)+'&an='+anio.value);
            window.open('paginas/4.7recl_canal.php?g1='+(mes+2)+'&an='+anio.value);
          };

        if (valorformato==11) 
          {
            cap_inter(trimestre.value,anio.value);
            //var mes=parseInt(trimestre.value);
            //alert(trimestre.value+" "+anio.value);
            //rec_canal(trimestre.value,anio.value);
            //window.open('paginas/anexo_cap_inter.php');
          };

        if (valorformato==12) 
          {
            var mes=parseInt(trimestre.value);
            //alert(trimestre.value+" "+anio.value);
            rec_canal(trimestre.value,anio.value);
            window.open('paginas/repo_tarif_ded.php?g1='+(mes)+'&an='+anio.value);
            window.open('paginas/repo_tarif_ded.php?g1='+(mes+1)+'&an='+anio.value);
            window.open('paginas/repo_tarif_ded.php?g1='+(mes+2)+'&an='+anio.value);
          };

        if (valorformato==13) 
          {
            //var mes=parseInt(trimestre.value);
            //alert(trimestre.value+" "+anio.value);
            //rec_canal(trimestre.value,anio.value);
            document.getElementById("resultados").innerHTML='<div class="alert alert-info alert-dismissable">'+
          '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
          '<strong>¡Atento! No</strong> Prestamos este Servicio'+
          '</div>';
          };



        //generar(trimestre.value, formato.value);
      }

      function nuevo(){
        var posicion=document.getElementById('regist').options.selectedIndex; //posicion
        var conte=(document.getElementById('regist').options[posicion].text); //valor
        if(admin.value=="p")
        {
          var n1 = prompt("Nueva Provincia");
          if(n1!=null)
          {
            nuev(1,n1);
          }
        }
        if(admin.value=="c")
        {
          var n2 = prompt("Nueva Ciudad");
          if(n2!=null)
          {
            nuev(2,n2);
          }
        }
        if(admin.value=="q")
        {
          var n3 = prompt("Nueva Parroquia");
          if(n3!=null)
          {
            nuev(3,n3);
          }
        }
        if(admin.value=="e")
        {
          confirmar=confirm("¿Seguro que desea eliminar "+conte+"?"); 
          if (confirmar) 
          {
            // si pulsamos en aceptar
            //alert(regist.value);
            elim(regist.value);
          }
        }

          //alert(conte);
      }
      </script>
      <!--<p>Date: <input type="text" id="datepicker"></p>-->

      <br>
      <div id="resultados">
      </div> 

      <script type="text/javascript">

      function imprimo(sal){

        for(var i=1;i<document.getElementById("selectable").rows.length;i++)
        {                        
          var elemento = document.getElementById("condiciones"+i+"");
          if(elemento.checked)
          {
            //ids+=selectable.rows[i].cells[1].childNodes[0].nodeValue+',';
            //guardarimpr(selectable.rows[i].cells[1].childNodes[0].nodeValue);
            alert(" Elemento: " + elemento.value 
              + "\n Seleccionado: " + selectable.rows[i].cells[1].childNodes[0].nodeValue
              + "\n Valor: " + provincia.value);                          
          }
        };
        //imprimirpdf(ids);
      }


      </script>
      
    </div> <!-- /container -->



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
