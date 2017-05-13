function nuev(id,nomb){	
	var xmlhttp;
	var ge1=id;
	var ge2=nomb;

	document.getElementById("resultados").innerHTML="";

	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("resultados").innerHTML=xmlhttp.responseText;
		}
	}
	
	//alert("sip"+ge1+ge2);
	for(var n=0;n<1;n++){
		xmlhttp.open("POST","paginas/nuevo.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("g1="+ge1+"&g2="+ge2);
	}
}

function elim(id){	
	var xmlhttp;
	var ge1=id;
	document.getElementById("resultados").innerHTML="";

	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("resultados").innerHTML=xmlhttp.responseText;
		}
	}
	//alert("sip"+ge1);
	for(var n=0;n<1;n++){
		xmlhttp.open("POST","paginas/eliminar.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("g1="+ge1);
	}
}

function tot_in(mes,ani){	
	var xmlhttp;
	var ge1=mes;
	var ge2=ani;
	document.getElementById("resultados").innerHTML="";

	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("resultados").innerHTML=xmlhttp.responseText;
		}
	}
	//alert("sip"+ge1);
	for(var n=0;n<1;n++){
		xmlhttp.open("POST","paginas/03total_ing.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("g1="+ge1+"&g2="+ge2);
	}
}


function rec_gen(me,an){
	var xmlhttp;
	var g1=me;
	var g2=an;
	document.getElementById("resultados").innerHTML="";
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("resultados").innerHTML=xmlhttp.responseText;
		}
	}

	for(var n=0;n<1;n++){
		xmlhttp.open("POST","paginas/4.2por_rectot.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("g1="+g1+"&g2="+g2);
	}
}

function rec_canal(me,an){	
	var xmlhttp;
	var g1=me;
	var g2=an;
	document.getElementById("resultados").innerHTML="";
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("resultados").innerHTML=xmlhttp.responseText;
		}
	}
	for(var n=0;n<1;n++){
		xmlhttp.open("POST","paginas/4.7recl_canaltot.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("g1="+g1+"&g2="+g2);
	}
}

function cap_inter(me,an){
	var xmlhttp;
	var g1=me;
	var g2=an;

	document.getElementById("resultados").innerHTML="";
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("resultados").innerHTML=xmlhttp.responseText;
		}
	}
	for(var n=0;n<1;n++){
		xmlhttp.open("POST","paginas/cap_inter.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("g1="+g1+"&g2="+g2);
	}
}

function cap_inter_modi(up,down,prov,id){
	var xmlhttp;
	var u=up;
	var d=down;
	var i=id;
	var p=prov;

	document.getElementById(i).innerHTML="";
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById(i).innerHTML=xmlhttp.responseText;
		}
	}
	for(var n=0;n<1;n++){
		xmlhttp.open("POST","paginas/cap_inter_mod.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("up="+u+"&down="+d+"&id="+i+"&pro="+prov);
	}
}

function cap_i_el(id){	
	var xmlhttp;
	var i=id;

	document.getElementById(i).innerHTML="";
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById(i).innerHTML=xmlhttp.responseText;
		}
	}
	for(var n=0;n<1;n++){
		xmlhttp.open("POST","paginas/cap_inter_elim.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("id="+i);
	}
}

function consuciudades(prov){	
	var xmlhttp;
	var i=prov;
	document.getElementById("ciudades").innerHTML="";

	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("ciudades").innerHTML=xmlhttp.responseText;
		}
	}
	
	for(var n=0;n<1;n++){
		xmlhttp.open("POST","paginas/registronotif_consultaciu.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("prov="+i);
	}
}

function cap_nue(prov,cui,up,down,provedor){	
	var xmlhttp;
	var p=prov;
	var c=cui;
	var u=up;
	var d=down;
	var pr=provedor;

	document.getElementById("resultados").innerHTML="";
	
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("resultados").innerHTML=xmlhttp.responseText;
		}
	}

	for(var n=0;n<1;n++){
		xmlhttp.open("POST","paginas/cap_inter_nuevo.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("prov="+p+"&ciu="+c+"&up="+u+"&down="+d+"&proved="+pr);
	}
}