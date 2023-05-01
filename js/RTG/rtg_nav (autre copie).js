/*
 
 glyphicon glyphicon-globe
 glyphicon glyphicon-picture
 
 glyphicon glyphicon-eye-open
 glyphicon glyphicon-pencil
 glyphicon glyphicon-plus
 
 <span class="glyphicon glyphicon-globe"></span>
 
 */

var rtg_flag = {};
rtg_flag['Coup de coeur']  = '<span class="btn btn-default" title="Coup de coeur" style="color:red"><b>&#10084;</b></span>'
rtg_flag['Voie majeur'] = '<span   class="btn btn-default"  title="Voie majeur" style="color:gold"><b>&#9733;</b></span>'

function rtg_getColor(cotation)
{
	if (cotation == '' || cotation == null) {cotation = 'Default';}

	color = "#000000";
	switch (cotation[0])
	{
		case '1':
			color="#00DD00"
			break;
		case '2':
			color="#00DD00"
			break;
		case '3':
			color="#00DD00"
			break;
		case '4':
			color="#00BB00"
			break;
		case '5':
			color="#168EF7"
			break;
		case '6':
			color="#F88017"
			break;
		case '7':
			color="#FF0000"
			break;
		case '8':
			color="#222222"
			break;
		case '9':
			color="#000000"
			break;
		case 'n':
			color="#000000"
			break;
		default:
			color="#ADADAD"
			break;

	}
	return color
}
function rtg_getQColor(Q)
{
	if (Q== '' || Q== null) {Q= 0;}

	color = "#000000";
	switch (Q)
	{
		case '2':
			color="#00FF00"
			break;
		case '1':
			color="#00DD00"
			break;
		case '0':
			color="#AAAAAA"
			break;
		case '-1':
			color="#168EF7"
			break;
		case '-2':
			color="#FF0000"
			break;
		case '-3':
			color="#333333"
			break;
	}
	return color
}
function rtg_genButton(txt,icon,style,click,action,id,forceText)
{
	if (rtg_isAllow(action,id))
	{
		if (forceText == 'all')
			return 	'<span><button type="button" class="btn '+style+' btn-xs" onclick="'+click+'"><span class="glyphicon '+icon+'">&#160;</span>'+txt+'</button></span>'
		else
			return '<span class="visible-xs visible-sm"><button type="button" class="btn '+style+' btn-xs" onclick="'+click+'"> <span class="glyphicon '+icon+'"></span></button></span>'+
				'<span class="visible-lg visible-md"><button type="button" class="btn '+style+' btn-xs" onclick="'+click+'"><span class="glyphicon '+icon+'">&#160;</span>'+txt+'</button></span>'
	}
	else
	{
		return "";
	}
}
function rtg_genButtonTxt(txt,icon,style,click,action,id,forceText)
{
	if (rtg_isAllow(action,id))
	{
		return '<span class="visible-xs visible-sm"><button type="button" class="btn '+style+' btn-xs" onclick="'+click+'">'+txt+'</button></span>'+
			'<span class="visible-lg visible-md"><button type="button" class="btn '+style+' btn-xs" onclick="'+click+'"><span class="glyphicon '+icon+'">&#160;</span>'+txt+'</button></span>'
	}
	else
	{
		return "";
	}
}
function rtg_genItemTitle(txt,icon)
{
	return '<span class="visible-xs visible-sm"><img src="'+baseUrl+'img/'+icon+'.png" Class="rtgitem" title="'+txt+'"/></span>'+
		'<span class="visible-lg visible-md"><img src="'+baseUrl+'img/'+icon+'.png" Class="rtgitem" title="'+txt+'"/></span>'

}
function rtg_genThTitle(txt,icon)
{
	return '<span class="visible-xs visible-sm"><img src="'+baseUrl+'img/'+icon+'.png" Class="rtgitem" title="'+txt+'"/></span>'+
		'<span class="visible-lg visible-md">'+txt+'</span>'

}



function rtg_getHtml(url)
{
	var msg = "<p>Page "+url+" non présente</p>";
	$.ajax({
		url: url,
		dataType:"HTML",
		type:"GET",
		async: false
	}).done(function(data) { msg = data;});
	return msg;
}

function rtg_dialog_img(title,src)
{


	$('#popupTitle').html(title);
	$('#popupBody').html('<img class="full" src="'+src+'"/>');
	$(function() {
		$('#event-modal').modal({
			backdrop: true
		});
	});


}

function rtg_dialog(title,msg)
{


	$('#popupTitle').html(title);
	$('#popupBody').html(msg);
	$(function() {
		$('#event-modal').modal({
			backdrop: true
		});
	});


}


function rtg_loadDiv(url,div)
{
	$.ajax({
		type: "GET",
		url: url
	}).done(function(data) {
		$(div).html(data);
	});
}






function rtg_getWCotBarGraph(stats)
{
	var t=0
	for (var i = 3; i <= 9; i++) {
		if (stats["c"][i] > 0)
		{	t+= stats["c"][i]; }
	}
	var r=""
	for (var i = 3; i <= 9; i++) {
		if (stats["c"][i] > 0)
		{
			var pc = stats["c"][i]/t*100;
			r +=  '<div class="progress-bar" role="progressbar" aria-valuenow="'+pc+'" aria-valuemin="0" aria-valuemax="100" style="width: '+pc+'%; background-color:'+  rtg_getColor(""+i) +'">'+stats["c"][i]+'</div>'
		}
	}
	return '<div class="progress progress-striped">'+r+'</div>'
}

function rtg_getStatsGraph(stats,attr,nb)
{
	if (!nb) {nb=3}
	////console.log('Graph : '+attr+" / "+nb)
	var t=0
	for (var i in stats[attr]) {	t+= stats[attr][i]; }
	var r=""
	var nbc = 0;
	for (key in stats[attr]) {
		if (nbc<nb)
		{
			var pc = Math.ceil(stats[attr][key]/t*100);
			var c = "d"
			var txt = +pc+'%'
			if (attr == 'c')	{c=key; txt =  stats[attr][key]+' ('+pc+'%)';}
			r +=  '<div class="col-md-12"><div class="col-md-5">'+key+'</div><div class="col-md-7"><div class="progress progress-striped">'+'<div class="progress-bar" role="progressbar" aria-valuenow="'+pc+'" aria-valuemin="0" aria-valuemax="100" style="width: '+pc+'%; background-color:'+  rtg_getColor(c[0]) +'">'+txt+'</div>'+'</div></div></div>'
		}
		nbc = nbc+1
	}
	return r
}


function rtg_setNavW(wid)
{
	var data  = rtg_getWdatas(wid);
	rtg_setNavSP(data["sp"]);
	var dataSP  = rtg_getSPdatas(data["sp"]);
	if (data["name"] && data["name"] != dataSP["descc"])
	{

		$('#rtg_nav_w').click(function (){rtg_viewWDetails(wid); return false;});
		$('#rtg_nav_w').attr("href",baseUrl+'w/'+wid+'/'+data["name"]);
		$('#rtg_nav_w').attr("onclick","rtg_viewWDetails("+wid+");return false;");
		$('#rtg_nav_w').html(data["name"]);
		$('#rtg_nav_w').show()
	}
}

function rtg_setNavSP(spid)
{
	var data  = rtg_getSPdatas(spid);
	rtg_setNavSC(data["sc"]);
	if (data['w'].length > 0)
	{
		$('#rtg_nav_sp').click(function (){rtg_viewSPDetails('+spid+'); return false;});
		$('#rtg_nav_sp').attr("onclick","rtg_viewSPDetails("+spid+");return false;");
		$('#rtg_nav_sp').attr("href",baseUrl+'sp/'+spid+'/'+data["descc"]);
		$('#rtg_nav_sp').html(data["descc"]);

		$('#rtg_nav_sp').show()
	}
	else if (data['w'].length > 0)
	{
		// rien a faire, il est déja caché
	}
	else
	{
		$('#rtg_nav_sp').click(function (){rtg_viewSPDetails('+spid+'); return false;});
		$('#rtg_nav_sp').attr("onclick","rtg_viewSPDetails("+spid+");return false;");
		$('#rtg_nav_sp').attr("href",baseUrl+'sp/'+spid);
		$('#rtg_nav_sp').html('Nouveau départ');

		$('#rtg_nav_sp').show()
	}
}

function rtg_setNavSC(scid)
{
	var data  = rtg_getSCdatas(scid);
	rtg_setNavSI(data["si"]);


	if (data["groupe"] != '')
	{
		$('#rtg_nav_sc_groupe').click(function (){rtg_viewSCDetails('+scid+'); return false;});
		$('#rtg_nav_sc_groupe').attr("onclick","rtg_viewSCDetails("+scid+");return false;");
		$('#rtg_nav_sc_groupe').attr("href",baseUrl+'sc/'+scid+'/'+data["name"]);
		$('#rtg_nav_sc_groupe').html(data["groupe"]);
		$('#rtg_nav_sc_groupe').show()
	}

	$('#rtg_nav_sc').click(function (){rtg_viewSCDetails('+scid+'); return false;});
	$('#rtg_nav_sc').attr("onclick","rtg_viewSCDetails("+scid+");return false;");
	$('#rtg_nav_sc').attr("href",baseUrl+'sc/'+scid+'/'+data["name"]);
	$('#rtg_nav_sc').html(data["name"]);



	$('#rtg_nav_sc').show()
}

function rtg_setNavSI(siid)
{
	var data  = rtg_getSIdatas(siid);
	rtg_setNavInit()

	$('#rtg_nav_si').click(function (){rtg_viewSIDetails('+siid+'); return false;});
	$('#rtg_nav_si').attr("onclick","rtg_viewSIDetails("+siid+");return false;");
	$('#rtg_nav_si').attr("href",baseUrl+'si/'+siid+'/'+data["name"]);
	$('#rtg_nav_si').html(data["name"]);

	$('#rtg_nav_si').show()
}
function rtg_setNavInit()
{
	$('#rtg_nav_si').hide()
	$('#rtg_nav_sp').hide()
	$('#rtg_nav_sc').hide()
	$('#rtg_nav_sc_groupe').hide()
	$('#rtg_nav_w').hide()
}



function rtg_switchMap()
{
	$( '#map-container' ).show()
	$( '#topo-container' ).hide()
	$('#rtg_switch_map').addClass( "btn-primary" );
	$('#rtg_switch_topo').removeClass( "btn-primary" );

}
function rtg_switchTopo()
{
	$( '#map-container' ).hide()
	$( '#topo-container' ).show()
	$('#rtg_switch_map').removeClass( "btn-primary" );
	$('#rtg_switch_topo').addClass( "btn-primary" );

}




function rtg_getWDetails(wid)
{
	var data = rtg_getWdatas(wid)
	var dataSI  = rtg_getSIdatas(data['si'])
	var statsSP = rtg_getSPStats(data['sp']);
	var name;
	var dataSP = rtg_getSPdatas(data['sp']);
	if (data['name'] && dataSP['descc'] != data['name'])
	{
		name =  dataSP['descc']+' &gt; '+data['name'];
	}
	else
	{
		name = dataSP['descc'];
	}
	name += " &#160; "+rtg_getFlag(data['t']);
	console.log(data)
	var detail =  '<div class="panel panel-default"><div class="panel-heading"><b>Voie : '+ name +'</b>'+



		'</div><div class="panel-body">'+
		'<table class="table table-hover">'+
		'<tr><td>'+rtg_genItemTitle("Cotation","c")+'</td><td><button class="btn" style="background-color:'+rtg_getColor(data["cot"][0])+'; color:white;"> '+data["cot"]+'</button></td></tr>'+
		'<tr><td>'+rtg_genItemTitle("Longueur","h")+'</td><td>'+data["h"]+'m</td></tr>';


	if (dataSI['type'] == 'Falaise')
		detail +=  	'<tr><td>'+rtg_genItemTitle("Degaines","d")+'</td><td>'+data["nbd"]+'</td></tr>'

	if (dataSI['type'] == 'Block')
		detail +=  	'<tr><td>'+rtg_genItemTitle("Depart","td")+'</td><td>'+data["td"]+'</td></tr>'


	detail +=	'<tr><td>'+rtg_genItemTitle("Type","t")+'</td><td>'+rtg_viewType(data['t'],'profil')+'</td></tr>'+
		'<tr><td>'+rtg_genItemTitle("Exposition","e")+'</td><td>'+dataSP['e']+'</td></tr>'+

		'</table>'+

		'<span class="pull-right">'

	if (dataSI['urlachat'] && !rtg_isAllow("SIRead",data['si'])) {
		detail += '<a id="rtg_url_achat" class="btn btn-danger glyphicon glyphicon-euro" href="'+dataSI['urlachat']+'" target="_blank" onclick="piwikTracker.trackEvent(\'topo\',\'Click lien achat\', \''+dataSI['name']+'\')"><span class="hidden-sm hidden-xs">&#160;Acheter la version officielle</span></a>'
	}

	if (dataSI['public'] <= 1 || rtg_isAllow("SIRead",data['si'])) {
		detail += '<a id="rtg_print_si" class="btn-sm btn-danger glyphicon glyphicon-print" href="'+baseUrl+'print/'+data['si']+'" target="_blank" onclick="piwikTracker.trackEvent(\'topo\',\'Click lien impression\', \''+dataSI['name']+'\')"><span class="hidden-sm hidden-xs">&#160;Version imprimable</span></a>'
	}

	detail += rtg_genButton('Modifier la voie','glyphicon-pencil','btn-primary','rtg_updateW('+wid+')','SIWrite',data['si'])+
		rtg_genButton('Modifier le depart','glyphicon-pencil','btn-primary','rtg_updateSP('+data['sp']+')','SIWrite',data['si'])+
		rtg_genButton('Ajouter une voie','glyphicon-plus','btn-primary','rtg_duplicateW('+wid+')','SIWrite',data['si'])+
		rtg_getBtnMesVoies(wid)+
		'</span>'+

		'</div></div>';
	return detail;
}

function rtg_getSPDetails(spid)
{
	//////console.log('rtg_getSPDetails '+spid)
	var data  = rtg_getSPdatas(spid)
	var dataSI  = rtg_getSIdatas(data['si'])
	var detail = ""
	if (data['w'].length > 0)
	{
		var stats = rtg_getSPStats(spid);
		var dataW =  rtg_getWdatas(data['w'][0]);
		detail =  '<div class="panel panel-default"><div class="panel-heading"><b>Depart : '+ data['descc'] +'</b>'+


			'</div><div class="panel-body">'+
			'<table class="table table-hover">'+

			'<tr><td>'+rtg_genItemTitle("Cotation","c")+'</td><td>'+rtg_getStatsGraph(stats,"c",10)+'</td></tr>'+
			'<tr><td>'+rtg_genItemTitle("Longueur","h")+'</td><td>'+stats["hmin"]+'m à '+stats["hmax"]+'m</td></tr>'

		if (dataSI['type'] == 'Falaise')
			detail +=  		'<tr><td>'+rtg_genItemTitle("Degaines","d")+'</td><td>'+stats["dmin"]+' à '+stats["dmax"]+'</td></tr>'

		detail +=  		'<tr><td>'+rtg_genItemTitle("Type","t")+'</td><td>'+rtg_getStatsGraph(stats,"t.profil")+'</td></tr>'+
			'<tr><td>'+rtg_genItemTitle("Exposition","e")+'</td><td>'+rtg_getStatsGraph(stats,"e",1)+'</td></tr>'+
			'</table>'+

			'<span class="pull-right">'+
			rtg_genButton('Modifier','glyphicon-plus','btn-primary','rtg_updateSP('+spid+')','SIWrite',data['si'])+
			rtg_genButton('Ajouter une voie','glyphicon-plus','btn-primary','rtg_insertW('+spid+')','SIWrite',data['si'])+
			'</span>'+
			'</div></div>';
	}
	else
	{
		//////console.log('rtg_getSPDetails nouveau : '+spid)
		detail =  '<div class="panel panel-default"><div class="panel-heading"><b>Nouveau depart : </b>'+
			'</div><div class="panel-body">'+
			'<span class="pull-right">'+
			rtg_genButton('Modifier','glyphicon-plus','btn-primary','rtg_updateSP('+spid+')','SIWrite',data['si'])+
			rtg_genButton('Ajouter une voie','glyphicon-plus','btn-primary','rtg_insertW('+spid+')','SIWrite',data['si'])+
			'</span>'+
			'</div></div>';
	}
	return detail
}

function rtg_getSCDetails(scid)
{
	var data  = rtg_getSCdatas(scid)
	var dataSI  = rtg_getSIdatas(data['si'])
	var stats = rtg_getSCStats(scid);
	var detail =  '<div class="panel panel-default"><div class="panel-heading"><b>Secteur : '+ data['name'] +'</b></div>'+


		'<div class="panel-body">'+
		'<table class="table table-hover">'+
		'<tr><td>'+rtg_genItemTitle("Cotation","c")+'</td><td>'+rtg_getStatsGraph(stats,"c",10)+'</td></tr>'+
		'<tr><td>'+rtg_genItemTitle("Longueur","h")+'</td><td>'+stats["hmin"]+'m à '+stats["hmax"]+'m</td></tr>'

	if (dataSI['type'] == 'Falaise')
		detail += '<tr><td>'+rtg_genItemTitle("Degaines","d")+'</td><td>'+stats["dmin"]+' à '+stats["dmax"]+'</td></tr>'

	detail += '<tr><td>'+rtg_genItemTitle("Type","t")+'</td><td>'+rtg_getStatsGraph(stats,"t.profil")+'</td></tr>'+
		'<tr><td>'+rtg_genItemTitle("Exposition","e")+'</td><td>'+rtg_getStatsGraph(stats,"e",1)+'</td></tr>'+
		'</table>'+


		'<span class="pull-right">'

	if (dataSI['urlachat'] && !rtg_isAllow("SIRead",data['si'])) {
		detail += '<a id="rtg_url_achat" class="btn btn-danger glyphicon glyphicon-euro" href="'+dataSI['urlachat']+'" target="_blank" onclick="piwikTracker.trackEvent(\'topo\',\'Click lien achat\', \''+dataSI['name']+'\')"><span class="hidden-sm hidden-xs">&#160;Acheter la version officielle</span></a>'
	}

	if (dataSI['public'] <= 1 || rtg_isAllow("SIRead",data['si'])) {
		detail += '<a id="rtg_print_si" class="btn-sm btn-danger glyphicon glyphicon-print" href="'+baseUrl+'print/'+data['si']+'" target="_blank" onclick="piwikTracker.trackEvent(\'topo\',\'Click lien impression\', \''+dataSI['name']+'\')"><span class="hidden-sm hidden-xs">&#160;Version imprimable</span></a>'
	}

	detail += rtg_genButton('Modifier','glyphicon-plus','btn-primary','rtg_updateSC('+scid+')','SIWrite',data['si'])+
		rtg_genButton('Ajouter un depart de voie','glyphicon-plus','btn-primary','rtg_insertSP('+scid+')','SIWrite',data['si'])+
		'</span>'+

		'</div></div>';
	return detail
}

function rtg_getSIDetails(siid)
{
	var data  = rtg_getSIdatas(siid)
	var stats = rtg_getSIStats(siid);

	var detail =  '<div class="panel panel-default"><div class="panel-heading"><b>Site : '+ data['name'] +'</b></div><div class="panel-body">'+
		'<table class="table table-hover">'+
		'<tr><td>'+rtg_genItemTitle("Cotation","c")+'</td><td>'+rtg_getStatsGraph(stats,"c",10)+'</td></tr>'+
		'<tr><td>'+rtg_genItemTitle("Longueur","h")+'</td><td>'+stats["hmin"]+'m à '+stats["hmax"]+'m</td></tr>';

	if (stats["dmax"] < 100 && dataSI['type'] == 'Falaise'){   detail += 	'<tr><td>'+rtg_genItemTitle("Degaines","d")+'</td><td>'+stats["dmin"]+' à '+stats["dmax"]+'</td></tr>'; }

	detail += 	'<tr><td>'+rtg_genItemTitle("Type","t")+'</td><td>'+rtg_getStatsGraph(stats,"t.profil")+'</td></tr>'+
		'<tr><td>'+rtg_genItemTitle("Exposition","e")+'</td><td>'+rtg_getStatsGraph(stats,"e",1)+'</td></tr>'+
		'</table>'



	detail += '<span class="pull-right">'

	if (data['urlachat'] && !rtg_isAllow("SIRead",data['si'])) {
		detail += '<a id="rtg_url_achat" class="btn btn-danger glyphicon glyphicon-euro" href="'+data['urlachat']+'" target="_blank" onclick="piwikTracker.trackEvent(\'topo\',\'Click lien achat\', \''+data['name']+'\')"><span class="hidden-sm hidden-xs">&#160;Acheter la version officielle</span></a>'
	}

	if (data['public'] <= 1 || rtg_isAllow("SIRead",siid)) {
		detail += '<a id="rtg_print_si" class="btn-sm btn-danger glyphicon glyphicon-print" href="'+baseUrl+'print/'+siid+'" target="_blank" onclick="piwikTracker.trackEvent(\'topo\',\'Click lien impression\', \''+data['name']+'\')"><span class="hidden-sm hidden-xs">&#160;Version imprimable</span></a>'
	}






	detail += rtg_genButton('Modifier','glyphicon-plus','btn-primary','rtg_updateSI('+siid+')','SIWrite',data['si'])+
		rtg_genButton('Ajouter un secteur','glyphicon-plus','btn-primary','rtg_insertSC('+siid+')','SIWrite',data['si'])+
		rtg_genButton('Ajouter un point d`interet','glyphicon-plus','btn-primary','rtg_insertPI('+siid+')','SIWrite',data['si'])+
		'</span>'+



		'</div></div>';
	return detail
}


function rtg_viewType(type,typeName,viewTxt)
{
	var rhtml="" ;
	for (var t in type[typeName]) {
		rhtml = rhtml+'<span class="tagtype"><img src="'+baseUrl+'img/'+typeName+'_'+ type[typeName][t]+'.png" alt="'+type[typeName][t]+'" title="'+type[typeName][t]+'">';
		rhtml = rhtml+'<span class="libelle hidden-xs hidden-sm">'+type[typeName][t]+'</span>';
		rhtml = rhtml+'</span>'
	}
	return rhtml;
}


function rtg_affinfo(s)
{
	if (s)
	{
		return s;
	}
	else
	{
		return "-";
	}
}
function rtg_getPannel(titre,html)
{
	return '<div class="panel panel-default"><div class="panel-heading"><b>'+titre+'</b></div><div class="panel-body">'+html+'</div></div>'
}


function rtg_AddComp(info,position) {
	var d = rtg_complements[info.name]
	var vs = d.valeurs;
	if (d.position != position)
		return "";

	if (d)
	{

		ht = "";
		switch (d.mode)
		{
			default:
				ht += '<td>'+d.nom+'</td><td>'+info.value +'</td>';
				break;
			case	"url":
				ht += '<td>'+d.nom+'</td><td><a href="'+info.value +'" target=_blank>'+ info.value.replace(/.*\/([^/]*)/g, "$1") +'</a></td>';
				break;
			case  'logo':
			case	"image":
				var randp = Date();
				ht += '<td colspan="2"><a href="javascript:rtg_dialog_img(\''+d.nom+'\',\''+baseUrl+'bddimg/comp/F.'+info.value +'.jpg?s='+randp+'\')"><img src="'+baseUrl+'bddimg/comp/T.'+info.value +'.jpg?s='+randp+'" class="img-thumbnail"/></a></td>';
				break;
			case "qualite":
				var result = $.grep(vs, function(e){ return e.val == info.value; });
				ht += '<td>'+d.nom+'</td><td>'
				//ht += result[0].aff
				ht += '&#160;<div class="progress progress-striped"><div class="progress-bar" role="progressbar" aria-valuenow="13" aria-valuemin="0" aria-valuemax="100" style="width: 100%; background-color:'+rtg_getQColor(info.value)+'">'+result[0].aff +'</div></div>';
				ht += '</td>';
				rtg_getQColor
				break;
			case "select":
				var result = $.grep(vs, function(e){ return e.val == info.value; });
				ht += '<td>'+d.nom+'</td><td>'+result[0].aff +'</td>';
				break;
		}

		return ht;
	}
}
function rtg_getWDesc(wid)
{
	var r=""
	var data = rtg_getWdatas(wid)



	r +=rtg_getSPDesc(data['sp'],'non')
	if (data['descl'])
	{
		r += rtg_getPannel('Description de la voie',rtg_toHtml(data['descl']));
	}
	////console.log(data['t'])
	r += '<div class="panel panel-default">'+
		'<div class="panel-heading"><b>Informations complémentaires</b></div>'+
		'<div class="panel-body">'+
		'<div class="col-md-12"><table class="table table-hover">'+
		'<tr><th colspan="4">Type</th></tr>'+
		'<tr><td>Voie</td><td>'+rtg_viewType(data['t'],'profil')+'</td>'+
		'<td>Prises</td><td>'+rtg_viewType(data['t'],'prises')+'</td></tr>'+
		'<tr><td>Escalade</td><td>'+rtg_viewType(data['t'],'escalade')+'</td></tr>'+
		'</table></div>';

	r += '</div></div>';

	return r
}

function rtg_getSPDesc(spid,complement)
{
	var data  = rtg_getSPdatas(spid)
	r=""
	if (data['descl'])
	{
		r += rtg_getPannel('Description du depart de voie',rtg_toHtml(data['descl']));
	}



	return r
}

function rtg_getSCDesc(scid)
{
	var data  = rtg_getSCdatas(scid)
	r="";
	if (data['descl'])
	{
		r+=rtg_getPannel('Description du secteur',rtg_toHtml(data['descl']));
	}

	return r
}

function rtg_getSIDesc(siid)
{
	var data  = rtg_getSIdatas(siid)
	r="";
	if (data['descl'])
	{
		r+=rtg_getPannel('Description du site',rtg_toHtml(data['descl']));
	}

	return r
}

function rtg_getComp_lateral(data)
{
	r="";
	comp = "";
	if (data['comp'].length > 0)
	{


		for (var i=0;i<data['comp'].length;i++)
		{

			comp_t = rtg_AddComp(data['comp'][i],'lateral');
			if (comp_t != "")
			{
				comp += '<tr>';
				comp += comp_t;
				comp +=  '</tr>';
			}

		}

	}
	if (comp != "")
	{
		r += ' <div class="panel panel-default"><div class="panel-heading">Compléments</div><div class="panel-body">'
		r += '<table class="table">';
		r += comp;
		r += '</table>';
		r += '</div></div>';
		return r
	}
}
function rtg_getComp_central(data)
{
	r="";
	comp = ""
	if (data['comp'].length > 0)
	{
		for (var i=0;i<data['comp'].length;i++)
		{

			comp_t = rtg_AddComp(data['comp'][i],'central');
			if (comp_t)
			{
				comp += '<div class="col-md-6"><table><tr>';
				comp += comp_t;
				comp +=  '</tr></table></div>';
			}
		}

	}
	if (comp != "")
	{
		r += ' <div class="panel panel-default"><div class="panel-body">'
		r += comp;
		r += '</div></div>';
		return r
	}
	return comp;
}

function rtg_cleanNextPrev()
{
	$( '#topo-container-prev div' ).hide();
	$( '#topo-container-next div' ).hide();
}

function rtg_setNext(action)
{
	//$( '#topo-container-next a' ).attr('onclick',action);
	$( '#topo-container-next div' ).attr('onclick',action);
	$( '#topo-container-next div' ).show();
	//////console.log("Next : "+action)
}

function rtg_setPrev(action)
{
	//$( '#topo-container-prev a' ).attr('onclick',action);
	$( '#topo-container-prev div' ).attr('onclick',action);
	$( '#topo-container-prev div' ).show();
	//////console.log("Prev : "+action)
}


function rtg_getPathW(wid)
{
	var data  = rtg_getWdatas(wid);
	var path = rtg_getPathSP(data["sp"]);
	var dataSP  = rtg_getSPdatas(data["sp"]);
	if (data["name"] && data["name"] != dataSP["descc"])
	{
		return path +' > '+data["name"];
	}
	return path;
}

function rtg_getPathSP(spid)
{
	var data  = rtg_getSPdatas(spid);
	var path = rtg_getPathSC(data["sc"]);
	if (data['w'].length > 0)
	{
		return path +' > '+data["descc"];
	}
	return path;
}

function rtg_getPathSC(scid)
{
	var data  = rtg_getSCdatas(scid);
	var path = rtg_getPathSI(data["si"]);
	return path +' > '+data["name"]
}

function rtg_getPathSI(siid)
{
	var data  = rtg_getSIdatas(siid);
	return data["name"];
}

function rtg_newLocation(type,id,title)
{
	var uri = "/"+type+"/"+id+"/"+title;
	var state = {
		"canBeAnything": true
	};
	history.pushState(state, title, uri);
	// piwik
	switch(type) {
		case "sp":
			var title= rtg_getPathSP(id);
			break;
		case "sc":
			var title= rtg_getPathSC(id);
			break;
		case "si":
			var title= rtg_getPathSI(id);
			break;
		case "w":
			var title= rtg_getPathW(id);
			break;
	}
	document.title = title;
	piwikTracker.trackPageView(title);
	//trackPageView(title);
	//expect(history.state).toEqual(state);
	rtg_updateCommentList(type,id)
	$( 'div#map-under' ).html("");
}
var AddCommentAction = function() {rtg_dialog('Ajouter un commentaire',rtg_getHtml('form_comment.php?elemType=0&elemId=0'));}
function rtg_updateCommentList(type,id)
{
	var idt = new Date().getTime()
	AddCommentAction = function() {rtg_dialog('Ajouter un commentaire',rtg_getHtml('form_comment.php?elemType='+type+"&elemId="+id));}

	var commentsHtml = rtg_getHtml(baseUrl+'rpc/rtg_comments.php?elemType='+type+"&elemId="+id+"&s="+idt);
	if (commentsHtml )
	{
		$( 'div#commentsListe' ).html(''+commentsHtml+'');
	}
	else
	{
		$( 'div#commentsListe' ).html('');
	}
}
function rtg_viewWDetails(wid)
{

	infowindow.close();
	var data= rtg_getWdatas(wid)
	var dataSP = rtg_getSPdatas(data['sp'])

	if (data['name'])
		rtg_newLocation('w',wid,data['name']);//window.location.hash = "w-" + wid+"-"+data['name']
	else
		rtg_newLocation('w',wid,dataSP['descc']);//window.location.hash = "w-" + wid+"-"+dataSP['descc']
	var dataSC = rtg_getSCdatas(dataSP['sc'])
	rtg_viewMapBound(rtg_getSCStats(dataSP['sc']));

	$( 'div#details' ).html( rtg_getWDetails(wid) );
	$( 'div#desc' ).html( rtg_getComp_central(data)+rtg_getWDesc(wid) );
	$( 'div#comp' ).html( rtg_getComp_lateral(data) );

	var datasi = rtg_getSIdatas(data['si'])
	$( 'div#pi1' ).html(rtg_getPIDescc(datasi['pi'],'climbingcrew') +  rtg_getPIDescc(datasi['pi'],'caf') + rtg_getPIDescc(datasi['pi'],'ffme') + rtg_getPIDescc(datasi['pi'],'partenaire') )
	$( 'div#pi2' ).html( rtg_getPIDescc(datasi['pi'],'p') + rtg_getPIDescc(datasi['pi'],'acces') + rtg_getPIDescc(datasi['pi'],'pdv') )
	rtg_updateChangelog('w-'+wid);

	$( 'div#lists' ).html( "" );
	$( '#map-container' ).hide()
	$( '#topo-container' ).show()
	rtg_viewWTopo(wid,'topo-container-img');



	if (dataSC["w"].length > 1)
	{
		//////console.log(wid+" - "+dataSC["w"])
		rtg_cleanNextPrev();
		for (var i=0;i<dataSC["w"].length;i++)
		{

			if (dataSC["w"][i] == wid)
			{
				if (i > 0) {rtg_setPrev('rtg_viewWDetails('+dataSC["w"][(i-1)]+')') }
				if ( (i+1) < dataSC["w"].length) { 	rtg_setNext('rtg_viewWDetails('+dataSC["w"][(i+1)]+')') }
			}
		}
	}

	rtg_setNavW(wid);
	rtg_switchTopo();
	$( '#rtg_switch_zone' ).show()
}

function rtg_viewSPDetails(spid)
{

	var data  = rtg_getSPdatas(spid)
	//window.location.hash = "sp-" + spid+"-"+data['descc']	
	rtg_newLocation('sp',spid,data['descc']);
	var dataSC  = rtg_getSCdatas(data['sc'])
	var dataSI  = rtg_getSIdatas(dataSC['si'])

	if (data['w'].length != 1)
	{
		rtg_viewMapBound(rtg_getSCStats(data['sc']));

		infowindow.close();
		$( 'div#details' ).html( rtg_getSPDetails(spid) );
		$( 'div#desc' ).html( rtg_getComp_central(data)+rtg_getSPDesc(spid) );
		$( 'div#comp' ).html( rtg_getComp_lateral(data));
		var datasi = rtg_getSIdatas(data['si'])
		$( 'div#pi1' ).html( rtg_getPIDescc(datasi['pi'],'climbingcrew') + rtg_getPIDescc(datasi['pi'],'caf') + rtg_getPIDescc(datasi['pi'],'ffme') + rtg_getPIDescc(datasi['pi'],'partenaire') )
		$( 'div#pi2' ).html( rtg_getPIDescc(datasi['pi'],'p') + rtg_getPIDescc(datasi['pi'],'acces') + rtg_getPIDescc(datasi['pi'],'pdv') )
		rtg_updateChangelog('sp-'+spid);


		if (data['w'].length > 0)
		{
			rtg_viewSPTopo(spid,'topo-container-img');

			if (dataSC["w"].length > 1)
			{
				////////console.log(wid+" - "+dataSC["w"])
				rtg_cleanNextPrev();
				for (var i=0;i<dataSC["w"].length;i++)
				{

					if (dataSC["w"][i] == spid)
					{
						if (i > 0) {rtg_setPrev('rtg_viewWDetails('+dataSC["w"][(i-1)]+')') }
						if ( (i+1) < dataSC["w"].length) { 	rtg_setNext('rtg_viewWDetails('+dataSC["w"][(i+1)]+')') }
					}
				}
			}


			$( 'div#lists' ).html( rtg_getWListWidthPanel(  data["w"],dataSI) );
		}
		rtg_switchTopo();
		rtg_setNavSP(spid);
		$( '#rtg_switch_zone' ).show()
	}
	else
	{
		rtg_viewWDetails(data['w'][0]);

	}

}

function rtg_viewSCDetails(scid)
{

	var data  = rtg_getSCdatas(scid)
	//window.location.hash = "sc-" + scid+"-"+data['name']
	rtg_newLocation('sc',scid,data['name']);
	var stats = rtg_getSCStats(scid);
	rtg_viewMapBound(stats);

	infowindow.close();
	$( 'div#details' ).html( rtg_getSCDetails(scid) );
	$( 'div#desc' ).html( rtg_getComp_central(data)+rtg_getSCDesc(scid) );
	$( 'div#comp' ).html( rtg_getComp_lateral(data) );

	var datasi = rtg_getSIdatas(data['si'])
	$( 'div#pi1' ).html( rtg_getPIDescc(datasi['pi'],'climbingcrew') +  rtg_getPIDescc(datasi['pi'],'caf') + rtg_getPIDescc(datasi['pi'],'ffme') + rtg_getPIDescc(datasi['pi'],'partenaire') )
	$( 'div#pi2' ).html( rtg_getPIDescc(datasi['pi'],'p') + rtg_getPIDescc(datasi['pi'],'acces') + rtg_getPIDescc(datasi['pi'],'pdv') )
	rtg_updateChangelog('sc-'+scid);

	var dataSI  = rtg_getSIdatas(data['si'])
	$( 'div#lists' ).html( '<div class="panel panel-default"><div class="panel-body">'+  rtg_getSPList(  data["sp"],dataSI) +'</div>' );
	rtg_viewSCTopo(scid,'topo-container-img');



	//////console.log(scid+" - "+dataSI["sc"])
	rtg_cleanNextPrev();
	for (var i=0;i<dataSI["sc"].length;i++)
	{
		if (dataSI["sc"][i] == scid)
		{
			if (i > 0) { 	rtg_setPrev('rtg_viewSCDetails('+dataSI["sc"][(i-1)]+')') }
			if ( (i+1) < dataSI["sc"].length) { 	rtg_setNext('rtg_viewSCDetails('+dataSI["sc"][(i+1)]+')') }
		}
	}

	rtg_switchTopo();
	rtg_setNavSC(scid);
	$( '#rtg_switch_zone' ).show()

}

function rtg_viewSIDetails(siid)
{

	var data  = rtg_getSIdatas(siid)
	//window.location.hash = "si-" + siid+"-"+data['name']
	//window.location = "/si/" + siid+"/"+data['name']
	rtg_newLocation('si',siid,data['name']);
	var stats = rtg_getSIStats(siid);
	rtg_viewMapBound(stats)



	$( '#topo-container' ).hide()
	rtg_cleanNextPrev();
	infowindow.close();
	rtg_setNavSI(siid);
	$( 'div#details' ).html( rtg_getSIDetails(siid) );
	$( 'div#desc' ).html( rtg_getComp_central(data)+rtg_getSIDesc(siid) );
	$( 'div#comp' ).html( rtg_getComp_lateral(data) );
	$( 'div#pi1' ).html(  rtg_getPIDescc(data['pi'],'climbingcrew') + rtg_getPIDescc(data['pi'],'caf') + rtg_getPIDescc(data['pi'],'ffme') + rtg_getPIDescc(data['pi'],'partenaire') )
	$( 'div#pi2' ).html( rtg_getPIDescc(data['pi'],'p') + rtg_getPIDescc(data['pi'],'acces') + rtg_getPIDescc(data['pi'],'pdv') )
	rtg_updateChangelog('si-'+siid);

	//$( 'div#lists' ).html( rtg_getSCList(data["sc"]) );
	if (data["sc"]){	  $( 'div#lists' ).html( rtg_getSCList(data["sc"]) ); }
	rtg_switchMap();
	$( '#rtg_switch_zone' ).hide()
}


function rtg_viewMapBound(stats)
{
	//rtg_switchMap()
	if (stats)
	{
		////console.log('rtg_viewMapBound');
		////console.log(stats["mLat"] - stats["MLat"]);
		if (stats['hmin'] < 10000 )
		{
			var d = 0.0005;
			if (    (stats["MLat"] - stats["mLat"]) < d
				&& (stats["MLon"] - stats["mLon"]) < d)
			{
				//console.log("ReBound center !"+stats["mLat"]+"/"+stats["mLon"]);
				var bounds = new google.maps.LatLngBounds();
				bounds.extend(new google.maps.LatLng(stats["mLat"], stats["mLon"]));
				bounds.extend(new google.maps.LatLng(stats["MLat"], stats["MLon"]));
				map.setCenter(bounds.getCenter());
				map.setZoom(17);
			}
			else
			{
				if (stats["mLat"]==stats["MLat"])
				{
					stats["mLat"] = stats["mLat"] - d;
					stats["MLat"] = stats["MLat"] - (d*-1);
				}
				if (stats["mLon"]==stats["MLon"])
				{
					stats["mLon"] = stats["mLon"] - d*2;
					stats["MLon"] = stats["MLon"] - (d*-2);
				}

				//console.log("ReBound !"+stats["mLat"]+"/"+stats["mLon"]+"/"+stats["MLat"]+"/"+stats["MLon"]);
				var bounds = new google.maps.LatLngBounds();
				bounds.extend(new google.maps.LatLng(stats["mLat"],  stats["mLon"] ));
				bounds.extend(new google.maps.LatLng(stats["MLat"],  stats["MLon"] ));
				map.fitBounds(bounds);

			}
		}
		rtg_refreshMap();
	}
}

function rtg_initStatsVar()
{
	var s = {
		cmin:10,
		cmax:0,
		hmin:10000,
		hmax:0,
		dmin:100,
		dmax:0,
		mLat:100,
		MLat:0,
		mLon:100,
		MLon:0,
		e:{},
		c:{},
		't.profil':{},
		't.prises':{},
		't.escalade':{},
		't.equipement':{},
		't.pied':{}
	};
	return  s;
}
var rtg_statistiques = {}

function rtg_getSIStats(siid)
{
	if (rtg_statistiques["Site§"+siid])
	{
		return rtg_statistiques["Site§"+siid];
	}
	else
	{
		preLoadDataSI(siid)
		var dataSI  = rtg_getSIdatas(siid)

		swids = []
		for (var i=0;i<dataSI["sc"].length;i++)
		{
			var dataSC = rtg_getSCdatas(dataSI["sc"][i]);
			//console.log('read sc '+dataSI["sc"][i]);
			swids = swids.concat(dataSC["w"]);
			//for (var j=0;j<dataSC["w"].length;j++) { swids.push(dataSC["w"][j]); } 
		}
		rtg_statistiques["Site§"+siid] = rtg_genStatFromWIds(swids,siid);
		return rtg_statistiques["Site§"+siid];
	}
}

function rtg_getSCStats(scid)
{
	if (rtg_statistiques["Secteur"+scid])
	{
		return rtg_statistiques["Secteur"+scid];
	}
	else
	{
		var data = rtg_getSCdatas(scid);
		swids = data["w"];
		rtg_statistiques["Secteur"+scid] = rtg_genStatFromWIds(swids);
		return rtg_statistiques["Secteur"+scid];
	}
}

function rtg_getSPStats(spid)
{
	if (rtg_statistiques["SP"+spid])
	{
		return rtg_statistiques["SP"+spid];
	}
	else
	{
		var data = rtg_getSPdatas(spid);
		swids = data["w"];
		rtg_statistiques["SP"+spid] = rtg_genStatFromWIds(swids);
		return rtg_statistiques["SP"+spid];
	}
}

function rtg_genStatFromWIds(swids,siid)
{
	try {
		var stats = rtg_initStatsVar();
		if (siid)
		{
			var dataSI =  rtg_getSIdatas(siid);
			if (parseInt(dataSI['hmax']))  stats['hmax'] = parseInt(dataSI['hmax']);
			if (parseInt(dataSI['hmin'])) stats['hmin']  = parseInt(dataSI['hmin']);
			if (parseInt(dataSI['lat'])) stats["mLat"] =  dataSI['lat'];
			if (parseInt(dataSI['lat']))  stats["MLat"] =  dataSI['lat'];
			if (parseInt(dataSI['lon']))  stats["mLon"] =  dataSI['lon'];
			if (parseInt(dataSI['lon']))  stats["MLon"] =  dataSI['lon'];
		}


		for (var i = 0; i < swids.length; i++)
		{
			try{
				var wInfo = rtg_getWdatas(swids[i]);


				var spInfo = rtg_getSPdatas(wInfo['sp']);
				if (wInfo["cot"])
				{
					var cot = ""+wInfo["cot"][0];
					if (stats["c"][cot] >= 1)
					{
						stats["c"][cot]++;
					}
					else
					{
						stats["c"][cot] = 1;
					}
				}
				if (wInfo["t"])
				{
					for (key in wInfo["t"])
					{
						try {
							var ak = "t."+key;
							if (stats[ak])
							{
								for (var j=0; j < wInfo["t"][key].length;j++ )
								{
									if (stats[ak][ wInfo["t"][key][j] ] >= 1)
									{
										stats[ak][ wInfo["t"][key][j] ]++;
									}
									else
									{
										stats[ak][ wInfo["t"][key][j] ] = 1;
									}
								}
							}
						}catch(err){
							console.log(err);
							////console.log('Ajouter le type '+key+' dans la fonction initStatsVar');
						}


					}

				}

				if (spInfo["e"])
				{
					for (var j=0; j < spInfo["e"].length;j++ )
					{
						if (stats['e'][ spInfo["e"][j] ] >= 1)
						{
							stats['e'][ spInfo["e"][j] ]++;
						}
						else
						{
							stats['e'][ spInfo["e"][j] ] = 1;
						}
					}

				}


				if (parseInt(cot) > parseInt(stats["cmax"])) {stats["cmax"] = cot;}
				if (parseInt(cot) < parseInt(stats["cmin"])) {stats["cmin"] = cot;}
				if (parseInt(wInfo["h"]) > parseInt(stats["hmax"])) {stats["hmax"] = wInfo["h"];}

				if (parseInt(wInfo["h"]) > 0 && parseInt(wInfo["h"]) < parseInt(stats["hmin"])) {stats["hmin"] = wInfo["h"];}
				if (parseInt(wInfo["nbd"]) > parseInt(stats["dmax"])) {stats["dmax"] = wInfo["nbd"];}
				if (parseInt(wInfo["nbd"]) > 0 && parseInt(wInfo["nbd"]) < parseInt(stats["dmin"])) {stats["dmin"] = wInfo["nbd"];}


				var wInfSP = rtg_getSPdatas(wInfo['sp']);
				if (wInfSP["lat"] < stats["mLat"]) {stats["mLat"] = wInfSP["lat"];}
				if (wInfSP["lat"] > stats["MLat"]) {stats["MLat"] = wInfSP["lat"];}
				if (wInfSP["lon"] < stats["mLon"]) {stats["mLon"] = wInfSP["lon"];}
				if (wInfSP["lon"] > stats["MLon"]) {stats["MLon"] = wInfSP["lon"];}
			}catch(err){
				console.log(err);
				////console.log('Ajouter le type '+key+' dans la fonction initStatsVar');
			}


		}
		return stats;
	}
	catch(err){
		console.log(err)
	}
}

function rtg_getWListWidthPanel(ws,dataSI)
{
	var list =  '<div class="panel panel-default"><div class="panel-body">'
	list += '<table class="table table-hover">'
	list += '<tr><th>&#160;</th><th>'+rtg_genThTitle("Nom","n")+'</th> <th>'+rtg_genThTitle("Cotation","c")+'</th> <th class="hidden-xs hidden-sm  hidden-md">'+rtg_genThTitle("Longueur","h")+'</th>'

	if (dataSI['type'] == 'Falaise')
		list += '<th class="hidden-xs hidden-sm hidden-md">'+rtg_genThTitle("Nb dégaines","d")+'</th>'

	if (dataSI['type'] == 'Block')
		list += '<th class="">'+rtg_genThTitle("Départ","td")+'</th>'

	list += '<th class="hidden-xs hidden-sm">'+rtg_genThTitle("Description","desc")+'</th> <th class="hidden-xs hidden-sm hidden-md">'+rtg_genThTitle("Type","t")+'</th> <th>&#160;</th> </tr>';
	list +=rtg_viewWList(ws,'','','',dataSI)
	list += '</table></div>'
	return list
}
function rtg_getFlag(type)
{
	flag =""

	if (type['recomandation'])
	{

		for (var j=0;j<type['recomandation'].length;j++)
		{
			if (rtg_flag[type['recomandation'][j]])
				flag += rtg_flag[type['recomandation'][j]];
		}
	}
	return flag;
}
function rtg_getBtnMesVoies(wid)
{
	var wInfo = rtg_getWdatas(wid);
	var mesvoies = "";
	var mvBsi = rtg_getMesVoiesdatas(wInfo["si"]);

	if (mvBsi && mvBsi[wid])
	{
		if (mvBsi[wid]["v"] > 0)
			btnclass = "btn-success"
		else
			btnclass = "btn-warning"
		mesvoies = rtg_genButton('x'+mvBsi[wid]['nb'],'glyphicon-check',btnclass,'rtg_dialog(\'Mes voies\',rtg_getHtml(\'form_mesvoies.php?wid='+wid+'\'));','true',wInfo['si'])
	}
	else
	{
		mesvoies = rtg_genButton('x0','glyphicon-check','btn-info','rtg_dialog(\'Mes voies\',rtg_getHtml(\'form_mesvoies.php?wid='+wid+'\'));','true',wInfo['si'])

	}
	return mesvoies

}
function rtg_getwDescText(wInfo)
{
	var t =""
	if (wInfo['descc'])
	{
		t = wInfo['descc'];
		if (wInfo['descl'])
		{
			t = t + "..."
		}
	}
	else
	{
		if (wInfo['descl'])
		{
			t= wInfo['descl'].substring(0,11) + "...";
		}
	}
	return t;
}

function rtg_viewWList(ws,SPInfo,style,pidc,dataSI)
{
	var list =  '';
	//SPInfo=""
	for (var i = 0; i < ws.length; i++) {
		var wInfo = rtg_getWdatas(ws[i]);

		var spInfo = rtg_getSPdatas(wInfo['sp'])
		if (wInfo)
		{
			flags = rtg_getFlag(wInfo['t']);
			mesvoies = rtg_getBtnMesVoies(ws[i])
			var idc = (i+1);
			if (pidc > 0)
			{
				idc = pidc
				if (ws.length > 1)
					idc =  pidc +'.'+ (i+1)
			}


			btnview = rtg_genButtonTxt(idc,'glyphicon-eye-open','btn-primary','rtg_viewWDetails('+ws[i]+')','true',wInfo['si']);

			name = wInfo['name']
			if (!name && spInfo)
				name = spInfo['descc']




			list += '<tr'+style+'><td>'+btnview+'</td><td>'+name+'</td><td><button class="btn" style="background-color:'+rtg_getColor(wInfo['cot'][0])+'; color:white;">'+wInfo['cot']+'</button>'+flags+'</td><td class="hidden-xs hidden-sm hidden-md">'+wInfo['h']+'</td>'
			if (dataSI['type'] == "Falaise")
				list += '<td class="hidden-xs hidden-sm hidden-md">'+wInfo['nbd']+'</td>'
			if (dataSI['type'] == "Block")
				list += '<td class="">'+wInfo['td']+'</td>'

			list += '<td class="hidden-xs hidden-sm">'+rtg_getwDescText(wInfo)+'</td><td class="hidden-xs hidden-sm hidden-md">'+rtg_viewType(wInfo['t'],'profil','true')+'</td><td>'+mesvoies+'</td></tr>';
		}
		SPInfo=""
	}
	return list;
}

function rtg_getSPList(spids,dataSI)
{
	var list =  '<table class="table table-hover table-responsive">';
	list += '<thead><tr><th>&#160;</th><th>'+rtg_genThTitle("Nom","n")+'</th> <th>'+rtg_genThTitle("Cotation","c")+'</th> <th class="hidden-xs hidden-sm hidden-md">'+rtg_genThTitle("Longueur","h")+'</th>'

	if (dataSI['type'] == "Falaise")
		list += '<th class="hidden-xs hidden-sm hidden-md">'+rtg_genThTitle("Degaine","d")+'</th>'
	if (dataSI['type'] == "Block")
		list += '<th class="">'+rtg_genThTitle("Départ","td")+'</th>'

	list += '<th class="hidden-xs hidden-sm">'+rtg_genThTitle("Description","desc")+'</th> <th class="hidden-xs hidden-sm hidden-md">'+rtg_genThTitle("Type","t")+'</th> <th>&#160;</th> </tr></thead><tbody>';

	for (var i = 0; i < spids.length; i++)
	{
		var style=' class="row'+(i % 2)+'"'
		var data = rtg_getSPdatas(spids[i]);
		//var stats = rtg_getSCStats(spids[i]);
		var SPInfo = '<td rowspan="'+ data["w"].length +'" class="hidden-xs">'+rtg_genButton((i+1),'glyphicon-eye-open','','rtg_viewSPDetails('+spids[i]+')','true',data['si'],'all')+'</td>';
		list += rtg_viewWList(data["w"],SPInfo,style,(i+1),dataSI);
	}

	list += '</tbody></table>'
	return list
}

function rtg_getSCList(scids)
{
	var list = ""
	for (var i = 0; i < scids.length; i++) {

		var data = rtg_getSCdatas(scids[i]);
		var stats = rtg_getSCStats(scids[i]);
		list += '<div class="col-md-4"><div class="panel panel-default">'+
			'<div class="panel-heading"><b>Secteur : '+data["name"]+'</b>'+'</div>';
		list +=	'<div class="panel-body"><div>'+rtg_getWCotBarGraph(stats)+'</div>';
		list +=	'<div class="img-thumbnail-crop"><a onclick="rtg_viewSCDetails('+scids[i]+')"><img src="'+baseUrl+'bddimg/sc/W.'+scids[i]+'.jpg" id="topo-img" class="img-rounded img-thumbnail" /></a></div>'
		list +=	 '<div><span class="pull-right">'+
			rtg_genButton('Voir le secteur','glyphicon-eye-open','btn-primary','rtg_viewSCDetails('+scids[i]+')','true',data['si'])+
			'</span></div></div>'
		list +=	'</div></div>'




	}

	return list
}


function rtg_myrendre(lat,lon,cat,type,libelle)
{
	var action = 'm’y rendre'
	if (lat > 0 && lon > 0)
	{
		if (navigator.platform.toLowerCase().indexOf("android") > -1
			|| navigator.platform.toLowerCase().indexOf("linux arm") > -1)
		{

			return '<a target="myrendre" class="btn btn-right  btn-default glyphicon glyphicon-road" href="geo:' +lat+','+lon+',u=35" title="M’y rendre" onclick="piwikTracker.trackEvent(\''+cat+'\',\''+action+'\', \''+type+' - '+libelle+'\')"></a>'
		} else if(navigator.platform.toLowerCase().indexOf("iphone") > -1
			|| navigator.platform.toLowerCase().indexOf("ipad") > -1) {
			return '<a target="myrendre" class="btn btn-right  btn-default glyphicon glyphicon-road" href="http://maps.apple.com/?ll=' +lat+','+lon+';u=35" title="M’y rendre"  onclick="piwikTracker.trackEvent(\''+cat+'\',\''+action+'\', \''+type+' - '+libelle+'\')"></a>'
		}else{
			return '<a target="myrendre" class="btn btn-right  btn-default glyphicon glyphicon-road" href="https://www.google.com/maps/dir//+' +lat+'+'+lon+'" title="M’y rendre"  onclick="piwikTracker.trackEvent(\''+cat+'\',\''+action+'\', \''+type+' - '+libelle+'\')"></a>'
		}
	}
	return "";
}


function rtg_getSPInfos(spid)
{
	var data = rtg_getSPdatas(spid)
	var dataW =  rtg_getWdatas(data['w'][0]);
	var stats = rtg_getSPStats(spid);
	var c =  '<div id="content">'+
		'<h4><a onclick="rtg_viewSPDetails('+spid+')">'+ dataW['name'] +'</a></h4>'+
		'<div>'+ data['descc'] +'</div>'+
		'<div>Hauteur  maximun '+ stats['hmax'] +'m</div>'+
		rtg_getWCotBarGraph(stats)+
		'<div>'+rtg_genButton('Voir les détails de ce depart','glyphicon-eye-open','btn-primary','rtg_viewSPDetails('+spid+')','true',data['si'])+'</div>'+
		'</div>';
	return c;
}

function rtg_getSCInfos(scid)
{
	var data = rtg_getSCdatas(scid);
	var stats = rtg_getSCStats(scid);

	var c =  '<div id="content">'+
		'<h4><a onclick="rtg_viewSCDetails('+scid+')">Secteur '+ data['name'] +'</a></h4>'+
		'<div>'+ data['descc'] +'</div>'+
		'<div>Hauteur  '+ stats['hmin'] +'m - '+ stats['hmax'] +'m</div>'+
		rtg_getWCotBarGraph(stats)+
		'<div>'+rtg_genButton('Voir les détails du secteur','glyphicon-eye-open','btn-primary','rtg_viewSCDetails('+scid+')','true',data['si'])+'</div>'+
		'</div>';
	return c;
}

function rtg_getSIInfos(siid)
{


	var data = rtg_getSIdatas(siid);
	var stats = rtg_getSIStats(siid);

	var c =  '<div id="content">'+
		'<h4><a onclick="rtg_viewSIDetails('+siid+')">'+ data['name'] +'</a></h4>'+
		'<div>'+ data['descc'] +'</div>'+
		rtg_getWCotBarGraph(stats)+

		'<div>'+rtg_genButton('Voir les détails du site','glyphicon-eye-open','btn-primary','rtg_viewSIDetails('+siid+')','true',data['si'])+'</div>'+
		'</div>';
	return c;
}

function rtg_getPIInfos(piid)
{
	var data = rtg_getPIdatas(piid);
	titre = "Point d'interet"
	if (rtg_pi[data['type']])
	{
		titre = rtg_pi[data['type']]['libelle'];
	}


	var c =  '<div id="content" style="width:200px;">'+
		'<h4>'+ titre +'</h4>'+
		'<div>'+ data['descc'] +'</div>'+
		'<div>'+rtg_genButton('Modification','glyphicon-eye-open','btn-primary','rtg_dialog(\'Mise à jour d`un point d`intérêt\',rtg_getHtml(baseUrl+\''+baseUrl+'form_pi.php?action=piUpdate&pi_id='+piid+'\'));','PIWrite',piid)+'</div>'+
		// '<div>'+rtg_genButton('Voir les détails','glyphicon-eye-open','btn-primary','rtg_viewPIDetails('+piid+')','SIRead',data['id'])+'</div>'+
		'</div>';
	return c;

}

function rtg_getPIDescc(piids,type)
{
	r = ''
	for (var i = 0; i < piids.length; i++)
	{
		id = piids[i];
		var data = rtg_getPIdatas(id);



		if (rtg_pi[data['type']] && data['type'] == type)
		{
			var nfo = rtg_pi[data['type']];

			r +=  '<div class="panel panel-default"><div class="panel-heading"><a onclick="rtg_dialog(\''+nfo.libelle+'\',rtg_getHtml(baseUrl+\'view_pi.php?id='+ id +'\'));"><img src="'+baseUrl+'img/' + nfo.icon + '" />&#160;<b>'+nfo.libelle+'</b></a>' +   rtg_myrendre(data['lat'],data['lon'],'PI',data['type'],nfo.libelle) +  '</div><div class="panel-body">'+ data['descc']  +'</div></div>';

		}
	}
	return r;
}

var betaCreator
var BetaCreatorReady = 0
var rtg_trace_ratio = 700
function rtg_WTopo(wid,divid)
{


	var data = rtg_getWdatas(wid)
	////console.log(data)
	var dataSP = rtg_getSPdatas(data['sp'])
	var cotation = data['cot'][0];
	var paths = '{"items":['+ rtg_cleanPath(data['p']).replace(/#[0-9a-fA-F]{6}/gm ,rtg_getColor(data['cot'][0])).replace(/"t":"D","ta":"l","tb":true/gm,'"t":"","ta":"l","tb":false').replace(/"t":"F","ta":"l","tb":true/gm,'"t":"","ta":"l","tb":false') + ']}';

	paths = paths.replace(/({"it":[^}]*)#......("[^}]*"t":"[D|F])/gm,'$1#FFFFFF$2')

	BetaCreatorReady = 0

	document.getElementById(divid).innerHTML = '<img src="'+baseUrl+'bddimg/sc/v.F.'+dataSP['sc']+'.jpg" id="topo-img"/>';

	var img = new Image();
	img.src = document.getElementById('topo-img').src;
	img.onload = function() {

		var r = img.height / rtg_trace_ratio;
		////console.log('R:'+r)
		var reg=new RegExp('"is":[^,]*,', "g");
		paths = paths.replace(reg,'"is":'+r+',');
		////console.log(paths)

		paths = paths.replace(/.lc.:false/gm,'"lc":true')


		BetaCreator(document.getElementById('topo-img'), function() {
			betaCreator = this;
			betaCreator.loadData(paths);
			BetaCreatorReady = 1
		},{
			/*zoom: 'contain',
            height: 500,
            width: 25,*/

		});
	}
}

function rtg_SCTopo(scid,divid)
{
	var dataSC = rtg_getSCdatas(scid)
	var dataSI = rtg_getSIdatas(dataSC['si'])
	var paths = new Array();
	var x=0;
	for (var j = 0; j < dataSC["sp"].length; j++) {
		var dataSP = rtg_getSPdatas(dataSC["sp"][j])
		var idc = x+1;
		for (var i = 0; i < dataSP["w"].length; i++) {
			var data = rtg_getWdatas(dataSP["w"][i])

			var p = rtg_cleanPath(data['p']);
			p= p.replace( /#[0-9a-fA-F]{6}/gm ,rtg_getColor(data['cot'][0]))
			p= p.replace( /({"it":[^}]*)#......("[^}]*"t":"[D|F])/gm ,     '$1#FFFFFF$2')

			if (dataSP["w"].length > 1)
			{
				p= p.replace(/"t":"F"/gm,'"t":"'+idc+'.'+(i+1)+'"')
				p= p.replace(/"t":"D","ta":"l","tb":true/gm,'"t":"","ta":"l","tb":false')
			}
			else
			{
				p= p.replace(/"t":"D"/gm ,'"t":"'+idc+'"')
				p= p.replace(/"t":"F","ta":"l","tb":true/gm,'"t":"","ta":"l","tb":false')
			}


			if (i==0)
			{
				p= p.replace(/.fl.:[^,]*/gm,'"fl":0')
			}
			else
			{
				p= p.replace(/.fl.:[^,]*/gm,'"fl":10')
			}
			p= p.replace(/.it.:1/gm,'"it":9')
			p= p.replace(/.it.:1/gm,'"it":9')
			p= p.replace(/.lc.:false/gm,'"lc":true')

			paths.push(p )

			//idc = ""
		}
		x = x+1;
	}



	BetaCreatorReady = 0
	document.getElementById(divid).innerHTML = '<img src="'+baseUrl+'bddimg/sc/v.F.'+scid+'.jpg" id="topo-img" />';
	var img = new Image();
	img.src = document.getElementById('topo-img').src;
	img.onload = function() {

		var r = img.height / rtg_trace_ratio;

		//////console.log('R:'+r)

		var reg=new RegExp('"is":[^,]*,', "g");
		paths = '{"items":['+ paths + ']}'
		paths = paths.replace(reg,'"is":'+r+',');
		//console.log(paths)


		BetaCreator(document.getElementById('topo-img'), function() {

			betaCreator = this;
			betaCreator.loadData(paths);
			BetaCreatorReady = 1
		},{
			/*zoom: 'contain',
            height: 500,
            width: 25,*/

		});
	}
}

function rtg_SCFullTopo(scid,divid)
{
	return;
	var dataSC = rtg_getSCdatas(scid)
	var dataSI = rtg_getSIdatas(dataSC['si'])
	var paths = new Array();
	var x=0;
	for (var j = 0; j < dataSC["sp"].length; j++) {
		var dataSP = rtg_getSPdatas(dataSC["sp"][j])
		var idc = x+1;
		for (var i = 0; i < dataSP["w"].length; i++) {
			var data = rtg_getWdatas(dataSP["w"][i])

			var p = rtg_cleanPath(data['p']);
			p= p.replace( /#[0-9a-fA-F]{6}/gm ,rtg_getColor(data['cot'][0]))
			p= p.replace( /({"it":[^}]*)#......("[^}]*"t":"[D|F])/gm ,     '$1#FFFFFF$2')

			if (dataSP["w"].length > 1)
			{
				p= p.replace(/"t":"F"/gm,'"t":"'+idc+'.'+(i+1)+'"')
				p= p.replace(/"t":"D","ta":"l","tb":true/gm,'"t":"","ta":"l","tb":false')
			}
			else
			{
				p= p.replace(/"t":"D"/gm ,'"t":"'+idc+'"')
				p= p.replace(/"t":"F","ta":"l","tb":true/gm,'"t":"","ta":"l","tb":false')
			}

			if (i==0)
			{
				p= p.replace(/.fl.:[^,]*/gm,'"fl":0')
			}
			else
			{
				p= p.replace(/.fl.:[^,]*/gm,'"fl":10')
			}
			p= p.replace(/.it.:1/gm,'"it":9')
			p= p.replace(/.it.:1/gm,'"it":9')
			p= p.replace(/.lc.:false/gm,'"lc":true')

			paths.push(p )
			//idc = ""
		}
		x = x+1;
	}



	BetaCreatorReady = 0
	document.getElementById(divid).innerHTML = '<img src="'+baseUrl+'bddimg/sc/v.F.'+scid+'.jpg" id="topo-img" />';
	var img = new Image();
	img.src = document.getElementById('topo-img').src;
	img.onload = function() {

		var r = img.height / rtg_trace_ratio;

		//////console.log('R:'+r)

		var reg=new RegExp('"is":[^,]*,', "g");
		paths = '{"items":['+ paths + ']}'
		paths = paths.replace(reg,'"is":'+r+',');
		//////console.log(paths)


		BetaCreator(document.getElementById('topo-img'), function() {

			betaCreator = this;
			betaCreator.loadData(paths);
			BetaCreatorReady = 1
		},{
			/*zoom: 'contain',
            height: 500,
            width: 25,*/

		});
	}
}


function rtg_SPTopo(spid,divid)
{

	var dataSP = rtg_getSPdatas(spid)
	var paths = new Array();
	for (var i = 0; i < dataSP["w"].length; i++) {
		var data = rtg_getWdatas(dataSP["w"][i])
		paths.push(rtg_cleanPath(data['p']).replace( /#[0-9a-fA-F]{6}/gm ,rtg_getColor(data['cot'][0])).replace( /({"it":[^}]*)#......("[^}]*"t":"[D|F])/gm , '$1#FFFFFF$2').replace(/"t":"D","ta":"l","tb":true/gm,'"t":"","ta":"l","tb":false').replace(/"t":"F"/gm,'"t":"'+(i+1)+'"') )
	}
	BetaCreatorReady = 0
	document.getElementById(divid).innerHTML = '<img src="'+baseUrl+'bddimg/sc/v.F.'+dataSP['sc']+'.jpg" id="topo-img" />';

	var img = new Image();
	img.src = document.getElementById('topo-img').src;
	img.onload = function() {

		var r = img.height / rtg_trace_ratio;
		//////console.log('R:'+r)
		var reg=new RegExp('"is":[^,]*,', "g");
		paths = '{"items":['+ paths + ']}'
		paths = paths.replace(reg,'"is":'+r+',');
		//////console.log(paths)
		paths = paths.replace(/.lc.:false/gm,'"lc":true')


		BetaCreator(document.getElementById('topo-img'), function() {

			betaCreator = this;
			betaCreator.loadData( paths );
			BetaCreatorReady = 1
		},{
			/*zoom: 'contain',
            height: 500,
            width: 25,*/
		});
	}
}
function rtg_cleanPath(s)
{
	return s.replace(/^\{"items":\[/,'').replace(/\]\}$/,'');
}


function rtg_viewWTopo(wid,divid)
{
	document.getElementById(divid).innerHTML = "<a href=\"javascript:rtg_dialog('Voie','<img class=\\\'full\\\' src=\\\'"+baseUrl+"bddimg/w/F."+wid+".jpg\\\' />')\"><img src='"+baseUrl+"bddimg/w/W."+wid+".jpg' /></a>";
}

function rtg_viewSCTopo(scid,divid)
{
	document.getElementById(divid).innerHTML = "<a href=\"javascript:rtg_dialog('Secteur','<img class=\\\'full\\\' src=\\\'"+baseUrl+"bddimg/sc/F."+scid+".jpg\\\' />')\"><img src='"+baseUrl+"bddimg/sc/W."+scid+".jpg' /></a>";
	//document.getElementById(divid).innerHTML = '<img src="'+baseUrl+'bddimg/sc/W.'+scid+'.jpg"  />';
}

function rtg_viewSPTopo(spid,divid)
{
	document.getElementById(divid).innerHTML = "<a href=\"javascript:rtg_dialog('Départ','<img class=\\\'full\\\' src=\\\'"+baseUrl+"bddimg/sp/F."+spid+".jpg\\\' />')\"><img src='bddimg/sp/W."+spid+".jpg' /></a>";
	//document.getElementById(divid).innerHTML = '<img src="'+baseUrl+'bddimg/sp/W.'+spid+'.jpg"  />';
}



// Deletes all markers in the array by removing references to them.
function rtg_deletePI() {
	for (var i = 0; i < pis.length; i++) {
		pis[i].setMap(null);
	}
	pis = [];
}
// Deletes all markers in the array by removing references to them.
function rtg_deleteSP() {
	for (var i = 0; i < sps.length; i++) {
		sps[i].setMap(null);
	}
	sps = [];
}

// Deletes all polygone in the array by removing references to them.
function rtg_deleteSC() {
	for (var i = 0; i < scs.length; i++) {
		scs[i].setMap(null);
	}
	scs = [];
}

function rtg_deleteSI() {
	for (var i = 0; i < sis.length; i++) {
		sis[i].setMap(null);
	}
	sis = [];
}


function rtg_addSC(data)
{
	var color = rtg_getColor(data[4]);
	var pPath = []
	var pi
	var oldLat = 0
	// for (var i = 0; i < data[2].length ; i++)
	// {
	// var p = new google.maps.LatLng(data[2][i][0], data[2][i][1])
	// pPath.push(p)
	// if (oldLat < data[2][i][0])
	// {
	// pi = new google.maps.LatLng(data[2][i][0], data[2][i][1])
	// oldLat = data[2][i][0]
	// }
	// }

	var dataSC = rtg_getSCdatas(data[1]);
	if (dataSC["sp"])
		for (var i = 0; i < dataSC["sp"].length ; i++)
		{
			var dataSP = rtg_getSPdatas(dataSC["sp"][i])
			var p = new google.maps.LatLng(dataSP["lat"], dataSP["lon"])
			pPath.push(p)
			if (oldLat < dataSP["lat"])
			{
				pi = new google.maps.LatLng(dataSP["lat"], dataSP["lon"])
				oldLat = dataSP["lat"]
			}
		}
	for (var i = dataSC["sp"].length-1 ; i >= 0  ; i--)
	{
		var dataSP = rtg_getSPdatas(dataSC["sp"][i])
		var p = new google.maps.LatLng(dataSP["lat"], dataSP["lon"])
		pPath.push(p)
	}


	// Define the LatLng coordinates for the polygon's path. Note that there's
	// no need to specify the final coordinates to complete the polygon, because
	// The Google Maps JavaScript API will automatically draw the closing side.
	var zone = new google.maps.Polygon({
		paths: pPath,
		strokeColor: color,
		strokeOpacity: 0.5,
		strokeWeight: 20,
		fillColor: color,
		fillOpacity: 0.5
	});
	scs.push(zone)
	google.maps.event.addListener(zone, 'click', function(event) {
			var marker = new google.maps.Marker({
				position: pi
				,map: map
				,icon: "none"
			});
			var info = rtg_getSCInfos(data[1]);
			//google.maps.event.addListener(infowindow, 'domready', function() {  });
			infoWindow = new google.maps.InfoWindow();
			infowindow.setContent( info );
			infowindow.open( map,marker);
		}
	);

	google.maps.event.addListener(zone, 'dblclick', function(event) {
			rtg_viewSCDetails(data[1])
			infowindow.close();
		}
	);


	zone.setMap(map);
}

function rtg_addSP(data)
{
	//,icon: "img/c" + data[3] + ".jpg"
	var dataSP = rtg_getSPdatas(data[1]);
	isDraggable=false
	if (dataSP && rtg_isAllow('SIWrite',dataSP['si']))
	{
		isDraggable=true
	}


	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(data[2][0], data[2][1])
		,map: map
		,draggable:isDraggable
		,icon: {
			path: google.maps.SymbolPath.CIRCLE,
			scale: 5,
			strokeWeight: 2,
			strokeColor: rtg_getColor(data[3]),
			fillColor:  rtg_getColor(data[5]),
			fillOpacity: 1
		}
	});
	sps.push(marker)
	google.maps.event.addListener(marker, 'click', function(event) {
			//google.maps.event.addListener(infowindow, 'domready', function() {  });
			var info = rtg_getSPInfos(data[1]);
			infoWindow = new google.maps.InfoWindow();
			infowindow.setContent( info );
			infowindow.open(map, marker);
		}
	)

	google.maps.event.addListener(marker, 'dblclick', function(event) {
			rtg_viewSPDetails(data[1])
			infowindow.close();
		}
	);

	if (rtg_isAllow('SIWrite',dataSP['si']))
	{
		google.maps.event.addListener(marker, 'dragend', function (event) {
			rtg_dialog('Mise à jour du départ (coordonnée)',rtg_getHtml(baseUrl+"form_sp.php?action=spUpdate&id="+data[1]+"&lat="+this.position.lat()+"&lon="+this.position.lng()));
		});
	}

}





function rtg_addPI(data)
{
	var nfo = rtg_pi[data[3]]
	var dataPI = rtg_getPIdatas(data[1]);
	isDraggable=false
	if (rtg_isAllow('SIWrite',dataPI['si']))
	{
		isDraggable=true
	}
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(data[2][0], data[2][1])
		,map: map
		,icon: baseUrl+'img/'+nfo.icon
		,draggable:isDraggable
	});
	pis.push(marker)
	google.maps.event.addListener(marker, 'click', function(event) {

			//var info = rtg_getPIInfos(data[1]);
			//infoWindow = new google.maps.InfoWindow();
			//infowindow.setContent( info );
			//infowindow.open(map, marker);
			var id = data[1];
			var dataPI = rtg_getPIdatas(id);
			var nfo = rtg_pi[dataPI['type']];
			rtg_dialog(nfo.libelle,rtg_getHtml(baseUrl+'view_pi.php?id='+ id));
		}
	)
	if (rtg_isAllow('PIWrite',data[1]))
	{
		google.maps.event.addListener(marker, 'dragend', function (event) {
			rtg_dialog('Mise à jour d`un point d`interet',rtg_getHtml(baseUrl+"form_pi.php?action=piUpdate&pi_id="+data[1]+"&lat="+this.position.lat()+"&lon="+this.position.lng()));
		});
	}
}


var iconsMaps = {}
iconsMaps["si-Defaut"] =  "M 0,0 m0,0.0c-12,-13.06003 -5.661,-7.64633 -26.907,-12.64633l-137.5034,0l0,0l50.382,0l94.46439,0c6.123,0 11.99701,2.4913 16.32901,6.9243c4.32999,4.43602 6.763,10.44902 6.763,16.72202l0,59.114l0,0l0,35.4686l0,0c0,13.057 1.66199,21.645 -23.09201,23.645l-23.23199,0l-23.23201,0l-25.81899,32.12601l-19.2817,-33.063l-36.2817,-0.063l37.37575,0l17.37576,33l24.7515,-32c-12.7541,0 43.90739,-0.588 58.90739,-0.64499l13,-9l0,-52.46861l-1,-17l0.125,-4.88931l0.125,-4.88919l0.125,-4.88921l0.06201,-2.4447l0.06299,-2.4446z m-20,65.05545c-2.74695,-4.18672 -6.12448,-7.58061 -9.4743,-9.95508c-3.95551,2.43814 -8.7348,3.91502 -13.97955,3.91502l-13.31708,-3.07671c-4.01248,-0.69937 -21.63602,-19.10165 -24.38278,-14.91496c-6.39882,9.72943 12.10242,29.4881 17.62196,32.07664c2.47147,1.16422 0.21088,3.3902 1.90857,7.91931c1.69774,4.5291 0.79297,3.45247 -3.50301,7.96294c-4.29596,4.51044 -14.27826,5.61351 -9.39322,13.83423c4.88503,8.2207 28.5451,-2.54167 29.45158,-7.56828c0.90585,5.02661 4.41484,17.4688 8.20464,17.4688c6.31647,0 11.45267,-9.01131 11.45267,-20.14206c0,-2.5016 -0.2753,-4.89937 -0.7692,-7.10565c2.69153,2.17743 5.30038,3.0463 7.77274,1.88208c5.51968,-2.58855 4.80566,-12.56688 -1.59302,-22.29628zm-23.45386,-9.83321c10.62857,0 19.25122,-7.2739 19.25122,-16.23871s-8.62265,-16.24432 -19.25122,-16.24432c-10.62859,0 -19.252,7.27385 -19.252,16.24432s8.62341,16.23871 19.252,16.23871z m-150,-45.00l0,0c0,-13.06001 10.3382,-23.6463 23.09219,-23.6463l10.49699,0l0,0c16.79402,0 81.58801,1 98.38202,1c-4.461,47.29129 -76.922,93.58229 -81.38202,140.8743l-27.49699,0c-12.754,0 -23.09219,-10.588 -23.09219,-23.645l0,0l0,-35.46898l0,0l0,-59.11401l0,-0.00001z"
iconsMaps["si-Multi"] =  "M 65,-170 m0,0c-12,-13.06 -5.661,-7.64633 -26.907,-12.6463l-137.503,0l0,0l50.382,0l94.464,0c6.123,0 11.997,2.4913 16.329,6.92427c4.32999,4.43602 6.763,10.44902 6.763,16.72203l0,59.114l0,0l0,35.469l0,0c0,13.057 1.66199,21.645 -23.092,23.645l-23.232,0l-23.232,0l-25.819,32.12601l-19.282,-33.063l-36.28101,-0.063l37.375,0l17.37611,33l24.7515,-32c-12.7541,0 43.9074,-0.588 58.9074,-0.645l13,-8.99999l0,-52.469l-1,-17l0.125,-4.8893l0.125,-4.8892l0.125,-4.8892l0.06201,-2.4447l0.06299,-2.4446l-3.5,-30.557z m-199,11l0,0c0,-13.06001 10.3382,-23.6463 23.09219,-23.6463l10.49698,0l0,0c16.79404,0 81.58801,1 98.38203,1c65.539,5.29129 124.078,113.58229 -29.38203,139.8743l-79.49698,1c-12.754,0 -23.09219,-10.588 -23.09219,-23.645l0,0l0,-35.46898l0,0l0,-59.11401l0,-0.00001z"
iconsMaps["si-Falaise"] =  "M 0,0 m0,0.0c-12,-13.06003 -5.661,-7.64633 -26.907,-12.64633l-137.5034,0l0,0l50.382,0l94.46439,0c6.123,0 11.99701,2.4913 16.32901,6.9243c4.32999,4.43602 6.763,10.44902 6.763,16.72202l0,59.114l0,0l0,35.4686l0,0c0,13.057 1.66199,21.645 -23.09201,23.645l-23.23199,0l-23.23201,0l-25.81899,32.12601l-19.2817,-33.063l-36.2817,-0.063l37.37575,0l17.37576,33l24.7515,-32c-12.7541,0 43.90739,-0.588 58.90739,-0.64499l13,-9l0,-52.46861l-1,-17l0.125,-4.88931l0.125,-4.88919l0.125,-4.88921l0.06201,-2.4447l0.06299,-2.4446z m-20,65.05545c-2.74695,-4.18672 -6.12448,-7.58061 -9.4743,-9.95508c-3.95551,2.43814 -8.7348,3.91502 -13.97955,3.91502l-13.31708,-3.07671c-4.01248,-0.69937 -21.63602,-19.10165 -24.38278,-14.91496c-6.39882,9.72943 12.10242,29.4881 17.62196,32.07664c2.47147,1.16422 0.21088,3.3902 1.90857,7.91931c1.69774,4.5291 0.79297,3.45247 -3.50301,7.96294c-4.29596,4.51044 -14.27826,5.61351 -9.39322,13.83423c4.88503,8.2207 28.5451,-2.54167 29.45158,-7.56828c0.90585,5.02661 4.41484,17.4688 8.20464,17.4688c6.31647,0 11.45267,-9.01131 11.45267,-20.14206c0,-2.5016 -0.2753,-4.89937 -0.7692,-7.10565c2.69153,2.17743 5.30038,3.0463 7.77274,1.88208c5.51968,-2.58855 4.80566,-12.56688 -1.59302,-22.29628zm-23.45386,-9.83321c10.62857,0 19.25122,-7.2739 19.25122,-16.23871s-8.62265,-16.24432 -19.25122,-16.24432c-10.62859,0 -19.252,7.27385 -19.252,16.24432s8.62341,16.23871 19.252,16.23871z m-150,-45.00l0,0c0,-13.06001 10.3382,-23.6463 23.09219,-23.6463l10.49699,0l0,0c16.79402,0 81.58801,1 98.38202,1c-4.461,47.29129 -76.922,93.58229 -81.38202,140.8743l-27.49699,0c-12.754,0 -23.09219,-10.588 -23.09219,-23.645l0,0l0,-35.46898l0,0l0,-59.11401l0,-0.00001z"
iconsMaps["si-Block"] =  "M 0,0 m0,0m0,0c-12,-13.06 -5.661,-7.64633 -26.907,-12.6463l-137.503,0l0,0l50.382,0l94.464,0c6.123,0 11.997,2.4913 16.329,6.92427c4.32999,4.43602 6.763,10.44902 6.763,16.72203l0,59.114l0,0l0,35.469l0,0c0,13.057 1.66199,21.645 -23.092,23.645l-23.232,0l-23.232,0l-25.819,32.12601l-19.282,-33.063l-36.28101,-0.063l37.375,0l17.37611,33l24.7515,-32c-12.7541,0 43.9074,-0.588 58.9074,-0.645l13,-8.99999l0,-52.469l-1,-17l0.125,-4.8893l0.125,-4.8892l0.125,-4.8892l0.06201,-2.4447l0.06299,-2.4446l-3.5,-30.557zm-20,65.0555c-2.747,-4.1868 -6.1245,-7.5807 -9.4743,-9.9551c-3.9555,2.4381 -8.7348,3.915 -13.9795,3.915l-13.3171,-3.0767c-4.0125,-0.6994 -21.6361,-19.1017 -24.3828,-14.915c-6.3988,9.7295 12.1024,29.4881 17.622,32.0767c2.4714,1.1642 0.2108,3.3902 1.9085,7.9193c1.6978,4.5291 0.793,3.4524 -3.503,7.9629c-4.2959,4.5104 -14.27821,5.6135 -9.3932,13.8344c4.885,8.221 28.5451,-2.542 29.4516,-7.56841c0.9058,5.02641 4.4148,17.46841 8.2046,17.46841c6.3165,0 11.4527,-9.011 11.4527,-20.1417c0,-2.5016 -0.2753,-4.8994 -0.7692,-7.1056c2.6915,2.1774 5.3004,3.0463 7.7727,1.882c5.5197,-2.5885 4.8057,-12.5668 -1.593,-22.2962zm-23.4539,-9.8333c10.6286,0 19.2513,-7.2739 19.2513,-16.2387s-8.6227,-16.2443 -19.2513,-16.2443c-10.6286,0 -19.252,7.2739 -19.252,16.2443s8.6234,16.2387 19.252,16.2387zm-150.0001,-45l0,0c0,-13.05997 10.338,-23.6463 23.092,-23.6463l10.49699,0l0,0c-26.20599,112 50.843,-19.2913 73.382,21c22.539,40.2913 -38.922,37.5823 -26.382,120.8741l-57.497,0c-12.754,0 -23.092,-10.588 -23.092,-23.645l0,0l0,-35.4687l0,0l0,-59.1141l0,0z"
iconsMaps["si-SAE"] =    "M 0,0 m0,-0.84375m17.40235,-9.28125c-0.60938,-1.2475 -86.3446,-48.35727 -110.12185,-62.84943l-107.128,57.90234l-3.16406,1.89844l109.66716,-61.73828l80.84681,46.33984c14.34956,8.85938 30.91497,17.21005 31.72744,18.94771c0.81247,1.73766 -17.49481,0.21855 -18.76044,2.69469l3.05859,76.19994l0,0l0,35.469l0,0c0,13.057 1.66199,21.645 -23.092,23.645l-23.232,0l-23.232,0l-25.819,32.12601l-19.282,-33.063l-36.28101,-0.063l37.375,0l17.37611,33l24.7515,-32c-12.7541,0 43.9074,-0.588 58.9074,-0.645l13,-8.99999l0,-52.469l-1,-17l0.125,-4.8893l-0.50781,-4.8892l-0.19141,-5.2056l0.06201,-1.81189l-1.62451,-40.83522c5.9349,-0.16613 17.14844,-0.51653 16.53906,-1.76403zm-37.40235,74.33675c-2.747,-4.1868 -6.1245,-7.5807 -9.4743,-9.9551c-3.9555,2.4381 -8.7348,3.915 -13.9795,3.915l-13.3171,-3.0767c-4.0125,-0.6994 -21.6361,-19.1017 -24.3828,-14.915c-6.3988,9.7295 12.1024,29.4881 17.622,32.0767c2.4714,1.1642 0.2108,3.3902 1.9085,7.9193c1.6978,4.5291 0.793,3.4524 -3.503,7.9629c-4.2959,4.5104 -14.27821,5.6135 -9.3932,13.8344c4.885,8.221 28.5451,-2.542 29.4516,-7.56841c0.9058,5.02641 4.4148,17.46841 8.2046,17.46841c6.3165,0 11.4527,-9.011 11.4527,-20.1417c0,-2.5016 -0.2753,-4.8994 -0.7692,-7.1056c2.6915,2.1774 5.3004,3.0463 7.7727,1.882c5.5197,-2.5885 4.8057,-12.5668 -1.593,-22.2962zm-23.4539,-9.8333c10.6286,0 19.2513,-7.2739 19.2513,-16.2387s-8.6227,-16.2443 -19.2513,-16.2443c-10.6286,0 -19.252,7.2739 -19.252,16.2443s8.6234,16.2387 19.252,16.2387zm-150.0001,-45l0,0c0,-13.05997 10.338,-23.6463 23.092,-23.6463l-30.95222,0.31641l30.05859,-17.71875c51.71979,-1.25 41.87425,37.05635 67.53435,50.54297c25.6601,13.48662 18.47253,48.78543 9.41879,106.6241l-76.0595,2.10938c-12.754,0 -23.092,-10.588 -23.092,-23.645l0,0l0,-35.4687l0,0l0,-59.1141l0,0z"
iconsMaps["si-SAE"] =    "M 0,0 m0,-0.28125m17.4023,-9.28125c-0.6093,-1.2475 -72.0008,-41.6073 -110.1218,-62.8494l-107.12851,57.9023l-3.16399,1.8985l109.66759,-61.7383l80.8468,46.3398c14.34957,8.8594 30.915,17.2101 31.7274,18.94773c0.8125,1.73765 -17.49477,0.21855 -18.76039,2.69468l3.05859,76.19989l0,0l0,35.4693l0,0c0,13.057 1.66199,21.645 -23.092,23.645l-23.232,0l-23.232,0l-25.819,32.12599l-19.282,-33.063l-58.21901,-0.063l59.313,0l17.37611,33.00001l24.7515,-32c-12.7541,0 43.9074,-0.58801 58.9074,-0.645l13,-9l0,-52.4693l-1,-17l0.125,-4.8892l-0.50781,-4.8893l-0.19141,-5.2056l0.06201,-1.8118l-2.46826,-40.41339c5.9349,-0.16614 17.99217,-0.93841 17.38277,-2.18591zm-37.4023,74.3367c-2.747,-4.1868 -6.1245,-7.5806 -9.4743,-9.9551c-3.9555,2.4382 -8.7348,3.915 -13.9795,3.915l-13.3171,-3.0766c-4.0125,-0.6995 -21.6361,-19.1017 -24.3828,-14.915c-6.3988,9.7295 12.1024,29.4881 17.622,32.0767c2.4714,1.1642 0.2108,3.3902 1.9085,7.9192c1.6978,4.5291 0.793,3.4524 -3.503,7.9629c-4.2959,4.5104 -14.27821,5.61349 -9.3932,13.8347c4.885,8.221 28.5451,-2.54221 29.4516,-7.5687c0.9058,5.0265 4.4148,17.4687 8.2046,17.4687c6.3165,0 11.4527,-9.01099 11.4527,-20.1419c0,-2.5017 -0.2753,-4.8994 -0.7692,-7.1056c2.6915,2.1774 5.3004,3.0462 7.7727,1.8819c5.5197,-2.58849 4.8057,-12.5668 -1.593,-22.2962zm-23.4539,-9.8333c10.6286,0 19.2513,-7.2739 19.2513,-16.2386s-8.6227,-16.2443 -19.2513,-16.2443c-10.6286,0 -19.252,7.2738 -19.252,16.2443s8.6234,16.2386 19.252,16.2386zm-150.0001,-44.99995l0,0c0,-13.05997 10.338,-23.64625 23.092,-23.64625l-30.952,0.3164l103.46459,-57.375c43.7042,18.5781 92.92115,44.2282 112.2531,58.1367c19.332,13.90847 -59.5743,-16.6052 -183.7997,-0.5322l-0.966,140.7654c-12.754,0 -23.092,-10.025 -23.092,-23.082l0,0l0,-35.4689l0,0l0,-59.11415l0,0z"

function rtg_addSI(data)
{
	var scol = '#FFFFFF';
	if (data[0] != 'si-Multi')
	{
		var dataSI = rtg_getSIdatas(data[1]);
		if (dataSI['public'] == 0)
			scol = '#FFFF00';
	}
	else
	{
		scol = '#FFFFFF';
	}

	if (iconsMaps[data[0]])
		svg = iconsMaps[data[0]]
	else
	{
		console.log("pas d icon pour le type : "+data[0])
		svg = iconsMaps["si-Defaut"]
	}




	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(data[2][0], data[2][1])
		,map: map
		,icon: {
			path: svg
			,scale: .15,
			strokeWeight: 1.5,
			strokeColor: scol,

			fillColor:  rtg_getColor(data[4]),
			fillOpacity: 1
		}
		,zIndex: 1
		,optimized: false
	});
	sis.push(marker)
	if (data[0] != 'si-Multi')
	{
		google.maps.event.addListener(marker, 'click', function(event) {
				//google.maps.event.addListener(infowindow, 'domready', function() {  });
				var info = rtg_getSIInfos(data[1]);
				infoWindow = new google.maps.InfoWindow();
				infowindow.setContent( info );
				infowindow.open(map, marker);
				// preload data du SI
				preLoadDataSI(data[1]);
			}
		)
		google.maps.event.addListener(marker, 'dblclick', function(event) {
				rtg_viewSIDetails(data[1])
				infowindow.close();
			}
		);
	}
	else
	{
		var img = '/img/number.php?n='+data[6]
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(data[2][0], data[2][1])
			,map: map
			,icon: img
			,zIndex: 10
			,optimized: false
		});
		sis.push(marker)
		sis.push(marker)
		google.maps.event.addListener(marker, 'click', function() {
			map.setZoom(map.getZoom()+1);
			map.setCenter(marker.getPosition());
		});


	}

}





function rtg_plotMapInfos(datas)
{
	rtg_deleteSP()
	rtg_deleteSC()
	rtg_deleteSI()
	rtg_deletePI()
	var zoomLevel = map.getZoom();
	for (var i = 0; i < datas.length ; i++)
	{
		switch(datas[i][0]) {
			case "sp":
				if (zoomLevel > 18)
				{
					rtg_addSP(datas[i])
				}
				break;
			case "sc":
				if (zoomLevel > 15)
				{
					rtg_addSC(datas[i])
				}
				break;
			case "si-Falaise":
			case "si-SAE":
			case "si-Block":
			case "si-Multi":
			case "si":
				if (zoomLevel <= 15)
				{
					rtg_addSI(datas[i])
				}
				break;
			case "pi":
				//if (zoomLevel <= 15)
			{
				if (rtg_pi[datas[i][3]])
				{
					var nfo = rtg_pi[datas[i][3]]

					if (zoomLevel > nfo.zoomlevel)
					{
						rtg_addPI(datas[i]);
					}
				}
			}
				break;
		}

	}
}




var swLatSAV = 0
var swLngSAV = 0
var neLatSAV = 0
var neLngSAV = 0
var zoomLevelSAV = 10;
function rtg_refreshMap()
{
	var zoomLevel = map.getZoom();
	var bounds    = map.getBounds();

	if (!bounds)
		return;

	var swPoint = bounds.getSouthWest();
	var nePoint = bounds.getNorthEast();
	var swLat = swPoint.lat();
	var swLng = swPoint.lng();
	var neLat = nePoint.lat();
	var neLng = nePoint.lng();


	var delta = 0.005
	// si ca a bcp bougé
	if ( zoomLevelSAV != zoomLevel
		|| Math.abs(swLat - swLatSAV) > (Math.abs(swLatSAV - neLatSAV)/3)
		|| Math.abs(swLng - swLngSAV) > (Math.abs(neLngSAV - swLngSAV)/3))
	{


		swLatSAV = swLat
		swLngSAV = swLng
		neLatSAV = neLat
		neLngSAV = neLng
		zoomLevelSAV = zoomLevel

		rtg_getMapInfos({
				"swLat":swLat,
				"swLng":swLng,
				"neLat":neLat,
				"neLng":neLng,
				"zoomLevel":zoomLevel
			},
			function(data,status){
				if (rtg_init_val == 1)
				{
					var siL = []
					var piL = []
					// prechagement des donnees
					for (var i = 0; i < data.length ; i++)
					{
						switch(data[i][0]) {
							case "si-Falaise":
							case "si-SAE":
							case "si-Block":
							case "si":
								siL.push(data[i][1])
								break;
							case "pi":
								piL.push(data[i][1])
								break;
						}

					}

					rtg_getSIdatas(siL);
					rtg_getPIdatas(piL);
				}
				rtg_plotMapInfos(data);
				if (rtg_init_val == 1)
				{
					rtg_setPrgBar('#initPrgBar',100,'Chargement fini','progress-bar-info');
					rtg_init_val = 0;
					//$( 'div#changeLog' ).html(rtg_getHtml(baseUrl+'rpc/rtg_changelog.php'));
					var urlcl = baseUrl+'rpc/rtg_changelog.php'
					$( 'div#changeLog' ).html(rtg_genButton('Historique','fa fa-calendar fa-lg','btn-primary', 'rtg_dialog(\'Historique\',rtg_getHtml(\''+urlcl+'\'))','',''))
					setTimeout("$('#event-modal').modal('hide');" ,500);
				}

			});
	}
}


function getPathData() {
	if (!betaCreator)
		return;
	alert(betaCreator.getData());
}

// init
var infowindow = new google.maps.InfoWindow();
var sps = [];
var scs = [];
var sis = [];
var pis = [];
var map;
var topo;


function rtg_makeIGNMapType(layer, name, maxZoom) {
	return new google.maps.ImageMapType({
		getTileUrl: function(coord, zoom) {
			return "http://gpp3-wxs.ign.fr/" + rtg_GEOPORTAIL_KEY + "/geoportail/wmts?LAYER=" +
				layer +
				"&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0" +
				"&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&TILEMATRIX=" +
				zoom + "&TILEROW=" + coord.y + "&TILECOL=" + coord.x;
		},
		tileSize: new google.maps.Size(256,256),
		name: name,
		maxZoom: maxZoom
	});
}

function rtg_logout()
{
	var r = rtg_getFromBdd("rpc/rtg_auth.php",{action:'LOGOUT'});
	////console.log('Logout: ');
	////console.log(r);
	rtg_switchLogin();
	return false;
}

function rtg_switchLogin()
{
	rtg_cleanUserRight()
	$( '.login' ).hide()
	$( '.logout' ).hide()
	$( '.admin' ).hide()
	$( '.SIWrite' ).hide()
	auth = rtg_getFromBdd("rpc/rtg_auth.php",{});
	if (auth['Auth'] == 'true')
	{
		$( '#UserName' ).html(auth['UserName'])
		$( '.logout' ).show()
		if (rtg_hasRight('admin'))
		{
			$( '.admin' ).show()
		}
		if (rtg_hasRight('SIWrite'))
		{
			$( '.SIWrite' ).show()
		}
		var sms = rtg_readCookie('sms')
		//if (sms < 1)
		//	 $.ajax({url: 'https://smsapi.free-mobile.fr/sendmsg?user=17191311&pass=iJsgQaJTLJA7lk&msg=RTG : '+auth['UserName'],type:"GET"})
		rtg_createCookie('sms','1',1);
	}
	else
	{
		$( '.login' ).show()
	}
}

function rtg_createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function rtg_readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}


function rtg_toHtml(text)
{
	return rtg_markdownrender.makeHtml(text.replace(/§/g,"\n"));
}


function rtg_loadNewHash()
{
	//var hash = location.replace('/','-');
	var hash= '';
	if (initHash)
	{
		hash = initHash;
		initHash = "";
	}
	if(hash != ''){
		var h = hash.split('-');
		switch (h[0])
		{


			case 'si':
				preLoadDataSI(h[1])
				rtg_viewSIDetails(h[1]);
				rtg_updateChangelog(hash);
				return;
				break;
			case 'sc':
				var dataSC = rtg_getSCdatas(h[1]);
				preLoadDataSI(dataSC['si']);
				rtg_viewSCDetails(h[1]);
				rtg_updateChangelog(hash);
				return;
				break;
			case 'sp':
				var dataSP = rtg_getSPdatas(h[1]);
				preLoadDataSI(dataSP['si']);
				rtg_viewSPDetails(h[1]);
				rtg_updateChangelog(hash);
				return;
				break;
			case 'w':
				var dataW = rtg_getWdatas(h[1]);
				preLoadDataSI(dataW['si']);
				rtg_viewWDetails(h[1]);
				rtg_updateChangelog(hash);
				return;
				break;
			case 'pi':
				rtg_init_val=2;
				var id = h[1];
				var dataPI = rtg_getPIdatas(id);
				var nfo = rtg_pi[dataPI['type']];
				rtg_dialog(nfo.libelle,rtg_getHtml(baseUrl+'view_pi.php?id='+ id));
				return;
				break;

		}

	}

	piwikTracker.trackPageView('Home');

}

function rtg_updateChangelog(hash)
{
	var urlcl = baseUrl+'rpc/rtg_changelog.php?hash='+hash
	$( 'div#changeLog' ).html(rtg_genButton('Historique','fa fa-calendar fa-lg','btn-primary', 'rtg_dialog(\'Historique\',rtg_getHtml(\''+urlcl+'\'))','',''))

	/*var changeLogHtml = rtg_getHtml(baseUrl+'rpc/rtg_changelog.php?hash='+hash);
if (changeLogHtml)
{
$( 'div#changeLog' ).html(changeLogHtml);
}
else
{
$( 'div#changeLog' ).html('');
}*/
}

function rtg_reinitMap()
{
	rtg_initMap();
	setTimeout("rtg_refreshMap()" ,200);
}

function rtg_initMap()
{
	ignMapType = new google.maps.ImageMapType({
		getTileUrl: function(coord, zoom) {
			return "http://gpp3-wxs.ign.fr/" + rtg_GEOPORTAIL_KEY + "/geoportail/wmts?LAYER=" +
				layer +
				"&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0" +
				"&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&TILEMATRIX=" +
				zoom + "&TILEROW=" + coord.y + "&TILECOL=" + coord.x;
		},
		tileSize: new google.maps.Size(256,256),
		name: name,
		maxZoom: 20
	});


	// initialise la carte google maps
	map = new google.maps.Map(document.getElementById('map-container'), {
		zoom: 10,
		center: new google.maps.LatLng(46.314594, 4.72),
		mapTypeId: google.maps.MapTypeId.HYBRID,
		//mapTypeId: 'IGN',
		mapTypeControlOptions: { mapTypeIds: [
				'IGN', 'Geoportail','Altitudes',
				google.maps.MapTypeId.ROADMAP,
				google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.SATELLITE],

			style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR }
	});

	map.mapTypes.set('IGN', rtg_makeIGNMapType("GEOGRAPHICALGRIDSYSTEMS.MAPS", "IGN", 18));
	map.mapTypes.set('Geoportail', rtg_makeIGNMapType("ORTHOIMAGERY.ORTHOPHOTOS", "Geoportail", 19));
	map.mapTypes.set('Altitudes', rtg_makeIGNMapType("ELEVATION.SLOPES", "Altitudes", 14));




	google.maps.event.addListener(map, 'domready',  function() {  rtg_refreshMap();  });
	google.maps.event.addListener(map, 'zoom_changed', 	function() {  setTimeout("rtg_refreshMap()" ,200);  });
	google.maps.event.addListener(map, 'center_changed',  function() {  setTimeout("rtg_refreshMap()" ,200);  });

}

function rtg_reInit()
{
	var idt = new Date().getTime()
	AddCommentAction = function() {rtg_dialog('Ajouter un commentaire',rtg_getHtml('form_comment.php?elemType=0&elemId=0'));}
	var commentsHtml = rtg_getHtml(baseUrl+'rpc/rtg_commentslist.php?nb=10&s='+idt);
	if (commentsHtml )
	{
		$( 'div#commentsListe' ).html(''+commentsHtml+'');
	}
	else
	{
		$( 'div#commentsListe' ).html('');
	}

	$('div#details').html( rtg_getHtml(baseUrl+"legende.php") );
	$( 'div#map-under' ).html(rtg_getHtml(baseUrl+"accueil.php"));

	$( 'div#desc' ).html( '');
	$( 'div#lists' ).html('');
	$( 'div#comp' ).html('');
	$( 'div#pi1' ).html('')
	$( 'div#pi2' ).html('')



	$( '#rtg_switch_zone' ).hide()
	rtg_switchMap();
	rtg_setNavInit();
	rtg_initMap()
	history.pushState({"canBeAnything": true}, "Ready To Grimpe - Topos d'escalades numériques gratuits", "/");
}

var rtg_init_val = 1;
function rtg_Init()
{

	rtg_dialog('Chargement','<div class="row"><div class="col-md-12" id="initPrgBar"> </div></div>');
	rtg_setPrgBar('#initPrgBar',0,'Initialisation de la carte','progress-bar-info');

	var idt = new Date().getTime()
	AddCommentAction = function() {rtg_dialog('Ajouter un commentaire',rtg_getHtml('form_comment.php?elemType=0&elemId=0'));}
	var commentsHtml = rtg_getHtml(baseUrl+'rpc/rtg_commentslist.php?nb=10&s='+idt);
	if (commentsHtml )
	{
		$( 'div#commentsListe' ).html(''+commentsHtml+'');
	}
	else
	{
		$( 'div#commentsListe' ).html('');
	}



	rtg_markdownrender = new Showdown.converter();

	rtg_initMap()

	rtg_setPrgBar('#initPrgBar',10,'Initialisation de l\'interface','progress-bar-info');
	$('div#details').html( rtg_getHtml(baseUrl+"legende.php") );
	$( 'div#map-under' ).html(rtg_getHtml(baseUrl+"accueil.php"));
	$( '#rtg_switch_zone' ).hide()
	rtg_switchMap();
	rtg_setNavInit();
	setTimeout(" rtg_setPrgBar('#initPrgBar',40,'Chargement des donnés','progress-bar-info'); rtg_refreshMap();",1000);

	rtg_switchLogin();

	// on met la legende
	rtg_setPrgBar('#initPrgBar',20,'Lecture du contexte','progress-bar-info');

	$(document).ready(function() {

		$(window).bind( 'hashchange', function(e) {
			rtg_loadNewHash();
		});
	});


	if (initHash)
		rtg_loadNewHash();
	else

		piwikTracker.trackPageView('Home');


	//var apropos = rtg_readCookie('apropos')
	////console.log('apropos : '+apropos)
	//if (apropos < 1)
	//	  rtg_dialog('Ready To Grimpe',rtg_getHtml(baseUrl+'apropos.htm'))
	//rtg_createCookie('apropos','1',180);

}
