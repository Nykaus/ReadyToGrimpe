


function rtg_setDatas(form,wid)
{
	rtg_setPrgBar('#savPrgBar',50,'Envoie des données','progress-bar-info');
	var data = form.serializeArray();
	console.log(data)
	var s = rtg_setToBdd('rpc/rtg_setdatas.php',data);
	console.log(s)
	if (s['Status'] == "OK")
	{
		rtg_setPrgBar('#savPrgBar',100,'Sauvegarde completée','progress-bar-success');
		return s['id'];
	}
	else
	{
		rtg_setPrgBar('#savPrgBar',100,'Erreur '& s['Message'],'progress-bar-danger');
	}
	rtg_clearCacheData();
}

function rtg_setDatasW(form,wid,noGenImg)
{
	var data = form.serializeArray();
	//////console.log(data)
	rtg_setPrgBar('#savPrgBar',50,'Supresssion du cache','progress-bar-info');
	rtg_clearCacheData()
	rtg_setPrgBar('#savPrgBar',60,'Sauvegarde des données','progress-bar-info');
	var s = rtg_setToBdd('rpc/rtg_setdatas.php',data);
	if (s['Status'] == "OK")
	{
		wid = s['id'];
		if (!noGenImg)
		{
			rtg_setPrgBar('#savPrgBar',70,'Génération des images','progress-bar-info');
			rtg_generateImgs(wid);
		}else{
			rtg_setPrgBar('#savPrgBar',100,'Sauvegarde completée','progress-bar-success');
		}
		return wid;
	}
	else
	{
		rtg_setPrgBar('#savPrgBar',100,'Erreur '& s['Message'],'progress-bar-danger');
	}
	
	
}

function rtg_modifyImgDialog(img,type)
{
	var srcO=""
	$("#modify-modal-imgF").val('');
	$("#modify-modal-imgW").val('');
	$("#modify-modal-imgT").val('');
	document.getElementById('modify-modal-imgO').innerHTML= '<img src="img/loading.gif" id="modify-modal-imgO-img" >';
	rtg_img_resize("bddimg/"+type+"/O."+img+".jpg",1080,function(resizesrc) {if (resizesrc) {srcO = resizesrc; console.log("img O ok");} });
	window.setTimeout (
		function(){
		if (srcO == "")
		{	
			rtg_img_resize("bddimg/"+type+"/F."+img+".jpg",
				1080,
				function(resizesrc) {
					srcO = resizesrc;
			        	var postO = {'type':type, 'id':img, 'h':'O', 'img':resizesrc};
					rtg_setToBdd("rpc/rtg_setimg.php",postO);
	                                console.log("img F ok + setimg");
				});	
		}
		window.setTimeout (
			function(){
				var paths = rtg_getHtml(baseUrl+"bddimg/"+type+"/"+img+".json?rand="+Date.now());
				console.log(paths);
				
				if (paths.substring(0, 1) != "{") {paths = "{\"items\":[]}";}
				
				console.log(paths);
			
				$("#modify-modal-img-path").val(paths);
				var imgBC = new Image();
				imgBC.src = document.getElementById('modify-modal-imgO-img').src = srcO;
				imgBC.onload = function() {
					BetaCreator(document.getElementById('modify-modal-imgO-img'), function() {
								betaCreator = this;
								betaCreator.loadData(paths);
	
							  },{
							   onChange: function() { $('#modify-modal-img-path').val(betaCreator.getData());},
								height: 700,
								width: 1100
							  });
				}
			        $('#modify-modal-update-path').unbind( "click" );
			    	$('#modify-modal-update-path').click(function() {
			    		var srcPath = $("#modify-modal-img-path").val();
				    	betaCreator.loadData(srcPath);
			    	});
			    	
				$('#modify-modal-action').unbind( "click" );
			    	$('#modify-modal-action').click(function() {
			    	
			    			$('#modify-modal-img-path').val(betaCreator.getData());
			    			var srcPath = $("#modify-modal-img-path").val();
						var postF = {'type':type, 'id':img, 'path': srcPath };
						rtg_setToBdd("rpc/rtg_setimg.php",postF);
			    	
			    	
						var pimg = betaCreator.getImage(1, 'jpg', 0)
						rtg_img_resize(pimg,1080,function(resizesrc) {
							var post = {'type':type, 'id':img, 'h':'F', 'img':resizesrc};
							rtg_setToBdd("rpc/rtg_setimg.php",post)
						})
						
						rtg_img_resize(pimg,500,function(resizesrc) {
							var post = {'type':type, 'id':img, 'h':'W', 'img':resizesrc};
							rtg_setToBdd("rpc/rtg_setimg.php",post)
						})
						
						
						rtg_img_resize(pimg,140,function(resizesrc) {
							var post = {'type':type, 'id':img, 'h':'T', 'img':resizesrc};
							rtg_setToBdd("rpc/rtg_setimg.php",post)
						})
						
						$('#modify-modal').modal('hide');
				});		
		$('#modify-modal').modal({backdrop: true});
        	},100);
	},5000);
}

function rtg_UploadImgDialog(img,type,name)
{
	$("#upload-modal-imgF").val('');
	$("#upload-modal-imgW").val('');
	$("#upload-modal-imgT").val('');
	$('#upload-modal-action').unbind( "click" );
    	$('#upload-modal-action').click(function() {
			rtg_imgFile_handleFileSelect('upload-modal-selectImg','upload-modal-imgF',1080);
			rtg_imgFile_handleFileSelect('upload-modal-selectImg','upload-modal-imgW',500);
			rtg_imgFile_handleFileSelect('upload-modal-selectImg','upload-modal-imgT',140);
			
			
			window.setTimeout (
				function(){
				var srcO = $("#upload-modal-imgF").val();
			        var postO = {'type':type, 'id':name, 'h':'O', 'img':srcO};
				rtg_setToBdd("rpc/rtg_setimg.php",postF);
			        var postF = {'type':type, 'id':name, 'h':'F', 'img':srcO};
				rtg_setToBdd("rpc/rtg_setimg.php",postF);
				
				
				var srcW = $("#upload-modal-imgW").val();
				var postW = {'type':type, 'id':name, 'h':'W', 'img':srcW};
				rtg_setToBdd("rpc/rtg_setimg.php",postW);
				
				var srcT = $("#upload-modal-imgT").val();
				var postT = {'type':type, 'id':name, 'h':'T', 'img':srcT};
				$('#'+img).attr('src',srcT);
				rtg_setToBdd("rpc/rtg_setimg.php",postT);			
				$('#upload-modal').modal('hide');},1000);
	});
    	$('#upload-modal').modal({backdrop: true});
}

function rtg_UploadGpxDialog(gpxid)
{
	$("#upload-modal-imgT").val('');
	$('#upload-modal-action').unbind( "click" );
    	$('#upload-modal-action').click(function() {
			rtg_gpxFile_handleFileSelect('upload-modal-selectImg','upload-modal-imgT');
			window.setTimeout (function(){
						var srcT = $("#upload-modal-imgT").val();
console.log(srcT);
						var postT = {'id':gpxid, 'gpx':srcT};
						rtg_setToBdd("rpc/rtg_setgpx.php",postT);			
						$('#upload-modal').modal('hide');
				},2000);

	});
    	$('#upload-modal').modal({backdrop: true});
}

function rtg_img_resize(src,MAX_HEIGHT,end)
{
	var image = new Image();
	image.src = src;
	image.onload = function(){
		var canvas = document.createElement('canvas');
		if(image.height > MAX_HEIGHT) {
			image.width *= MAX_HEIGHT / image.height;
			image.height = MAX_HEIGHT;
		}
		var ctx = canvas.getContext("2d");
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		canvas.width = image.width;
		canvas.height = image.height;
		ctx.drawImage(image, 0, 0, image.width, image.height);
		var resizesrc = canvas.toDataURL('image/jpeg');
		//////console.log(resizesrc);
		
		end(resizesrc);
	};
	
}
function rtg_imgFile_handleFileSelect(inputFileId,inputImgId,maxHeight)
{               
                if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
                    alert('The File APIs are not fully supported in this browser.');
                    return;
                }   

                input = document.getElementById(inputFileId);
                if (!input) {
                  alert("Um, couldn't find the fileinput element.");
               }
               else if (!input.files) {
                  alert("This browser doesn't seem to support the `files` property of file inputs.");
               }
               else if (!input.files[0]) {
                  //////console.log('pas de photo a uploader');
               }
               else {
                  file = input.files[0];
                  var fr = new FileReader();
                  fr.readAsDataURL(file);
                  fr.onload = function() {
					  rtg_img_resize(fr.result,maxHeight,function(resizesrc) {$('#'+inputImgId).val(resizesrc);})
				  };
               }
}
function rtg_gpxFile_handleFileSelect(inputFileId,inputGpxId)
{               
                if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
                    alert('The File APIs are not fully supported in this browser.');
                    return;
                }   

                input = document.getElementById(inputFileId);
                if (!input) {
                  alert("Um, couldn't find the fileinput element.");
               }
               else if (!input.files) {
                  alert("This browser doesn't seem to support the `files` property of file inputs.");
               }
               else if (!input.files[0]) {
                  //////console.log('pas de photo a uploader');
               }
               else {
                  file = input.files[0];
                  var fr = new FileReader();
                  fr.readAsDataURL(file);
                  fr.onload = function() {
					  $('#'+inputGpxId).val(fr.result);
				  };
               }
}

function rtg_setPrgBar(id,pc,info,style)
{
	$(id).html('<div class="progress"><div class="progress-bar '+style+'" role="progressbar" aria-valuenow="'+pc+'" aria-valuemin="0" aria-valuemax="100" style="width: '+pc+'%;">'+info+'</div></div>');
}

function rtg_generateImgs(wid)
{
	rtg_clearCacheData()
	
	// plot 1er img
	rtg_setPrgBar('#savPrgBar',70,'Génération de l\'image de la voie','progress-bar-info');
	rtg_WTopo(wid,'img-gen')
	var dataW = rtg_getWdatas(wid)
	var dataSP = rtg_getSPdatas(dataW['sp'])
	
	window.setTimeout (
		// sav 1er img
		function(){
			rtg_setImg("w",wid,betaCreator.getImage(1, 'jpg', 0));
			// plot 2eme img
			rtg_setPrgBar('#savPrgBar',80,'Génération de l\'image du départ','progress-bar-info');
			rtg_SPTopo(dataW['sp'],'img-gen')
			// sav 2eme img
			window.setTimeout(
			function(){
					rtg_setImg("sp",dataW['sp'],betaCreator.getImage(1, 'jpg', 0));
					// plot 3eme img
					rtg_setPrgBar('#savPrgBar',90,'Génération de l\'image du secteur','progress-bar-info');
					rtg_SCTopo(dataSP['sc'],'img-gen')
					// sav 3eme img
					window.setTimeout(
					function(){
						rtg_setImg("sc",dataSP['sc'],betaCreator.getImage(1, 'jpg', 0));
						rtg_setPrgBar('#savPrgBar',100,'Sauvegarde completée','progress-bar-success');
					}, 2000);
			}, 2000);
		}, 2000);
}


function rtg_setImg(ptype,pid,pimg)
{
	/*
    var img = new Image();
	img.src = "data:image/  png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAIAAAACDbGyAAAAAXNSR0IArs4c6QAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9oMCRUiMrIBQVkAAAAZdEVYdENvbW1lbnQAQ3JlYXRlZCB3aXRoIEdJTVBXgQ4XAAAADElEQVQI12NgoC4AAABQAAEiE+h1AAAAAElFTkSuQmCC";
    /// create an off-screen canvas
    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext("2d");
    
    var canvasCopy = document.createElement("canvas");
    var copyContext = canvasCopy.getContext("2d");
    /// set its dimension to target size
    canvas.width = 1024;
    canvas.height = 700;


		var ratio = 1;
		var maxWidth = 900;
		var maxHeight = 700;
		
        if(img.width > maxWidth)
            ratio = maxWidth / img.width;
        else if(img.height > maxHeight)
            ratio = maxHeight / img.height;

        canvasCopy.width = img.width;
        canvasCopy.height = img.height;
        copyContext.drawImage(img, 0, 0);

        canvas.width = img.width * ratio;
        canvas.height = img.height * ratio;
        ctx.drawImage(canvasCopy, 0, 0, canvasCopy.width, canvasCopy.height, 0, 0, canvas.width, canvas.height);




    /// draw source image into the off-screen canvas:
    //ctx.drawImage(img, 0, 0, width, height);

    /// encode image to data-uri with base64 version of compressed image
    //canvasCopy.toDataURL();
	
	pimg = canvasCopy.toDataURL();
	*/
	
	rtg_img_resize(pimg,1080,function(resizesrc) {
			var post = {type:ptype, id:pid, h:'F', img:resizesrc};
			//////console.log('1080 : '+post);
			rtg_setToBdd("rpc/rtg_setimg.php",post)
			})
    rtg_img_resize(pimg,500,function(resizesrc) {
			var post = {type:ptype, id:pid, h:'W', img:resizesrc};
			//////console.log('720 : '+post);
			rtg_setToBdd("rpc/rtg_setimg.php",post)
			})    
}


function rtg_clearCacheData()
{
	rtg_plotMapInfos_datainfos = [];
	rtg_getWInfos_datainfos = {};
	rtg_getSPInfos_datainfos = {};
	rtg_getSCInfos_datainfos = {};
	rtg_getSIInfos_datainfos = {};
	rtg_clearUserCacheData();
	
}
function rtg_clearUserCacheData()
{
	rtg_getMesVoiesInfos_datainfos = {};
}

function rtg_insertSC(siid)
{
	var html = rtg_getHtml(baseUrl+"form_sc.php?action=scInsert&siid="+siid);
	rtg_dialog('Création d\'un secteur',html);
}
function rtg_updateSC(spid)
{
	var html = rtg_getHtml(baseUrl+"form_sc.php?action=scUpdate&id="+spid);
	rtg_dialog('Mise à jour',html);
}
function rtg_updateSP(spid)
{
	 //////console.log('rtg_updateSP '+spid)
		rtg_dialog('Mise à jour',rtg_getHtml(baseUrl+"form_sp.php?action=spUpdate&id="+spid));
}
function rtg_insertSP(scid)
{
	rtg_switchMap()
	var marker = new google.maps.Marker({
            position: map.getCenter(), //map Coordinates where user right clicked
            map: map,
            draggable:true, //set marker draggable
            animation: google.maps.Animation.DROP, //bounce animation
            title:"New",
            icon: {
				path: google.maps.SymbolPath.CIRCLE,
				scale: 5,
				strokeWeight: 2,
				strokeColor: rtg_getColor('n'),
				fillColor:  rtg_getColor('n'),
				fillOpacity: 1
			}
        });
		google.maps.event.addListener(marker, 'click', function(event) { 
							rtg_dialog('Création d\'un départ',rtg_getHtml(baseUrl+"form_sp.php?action=spInsert&sc_id="+scid+"&lat="+this.position.lat()+"&lon="+this.position.lng()));
		});
                
}
function rtg_insertPI(siid)
{
	rtg_switchMap()
	var marker = new google.maps.Marker({
            position: map.getCenter(), //map Coordinates where user right clicked
            map: map,
            draggable:true, //set marker draggable
            animation: google.maps.Animation.DROP, //bounce animation
            title:"New",
            icon: {
				path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
				scale: 5,
				strokeWeight: 2,
				strokeColor: rtg_getColor('n'),
				fillColor:  rtg_getColor('n'),
				fillOpacity: 1
			}
        });
		google.maps.event.addListener(marker, 'click', function(event) { 
							rtg_dialog('Création d`un point d`interet',rtg_getHtml(baseUrl+"form_pi.php?action=piInsert&si_id="+siid+"&lat="+this.position.lat()+"&lon="+this.position.lng()));
		});
                
}

function rtg_insertSI()
{
	rtg_switchMap()
	var marker = new google.maps.Marker({
            position: map.getCenter(), //map Coordinates where user right clicked
            map: map,
            draggable:true, //set marker draggable
            animation: google.maps.Animation.DROP, //bounce animation
            title:"New",
            icon: {
	                path: 'M 0,0 '
				  +'m0,0.0c-12,-13.06003 -5.661,-7.64633 -26.907,-12.64633l-137.5034,0l0,0l50.382,0l94.46439,0c6.123,0 11.99701,2.4913 16.32901,6.9243c4.32999,4.43602 6.763,10.44902 6.763,16.72202l0,59.114l0,0l0,35.4686l0,0c0,13.057 1.66199,21.645 -23.09201,23.645l-23.23199,0l-23.23201,0l-25.81899,32.12601l-19.2817,-33.063l-36.2817,-0.063l37.37575,0l17.37576,33l24.7515,-32c-12.7541,0 43.90739,-0.588 58.90739,-0.64499l13,-9l0,-52.46861l-1,-17l0.125,-4.88931l0.125,-4.88919l0.125,-4.88921l0.06201,-2.4447l0.06299,-2.4446z'
				  +'m-20,65.05545c-2.74695,-4.18672 -6.12448,-7.58061 -9.4743,-9.95508c-3.95551,2.43814 -8.7348,3.91502 -13.97955,3.91502l-13.31708,-3.07671c-4.01248,-0.69937 -21.63602,-19.10165 -24.38278,-14.91496c-6.39882,9.72943 12.10242,29.4881 17.62196,32.07664c2.47147,1.16422 0.21088,3.3902 1.90857,7.91931c1.69774,4.5291 0.79297,3.45247 -3.50301,7.96294c-4.29596,4.51044 -14.27826,5.61351 -9.39322,13.83423c4.88503,8.2207 28.5451,-2.54167 29.45158,-7.56828c0.90585,5.02661 4.41484,17.4688 8.20464,17.4688c6.31647,0 11.45267,-9.01131 11.45267,-20.14206c0,-2.5016 -0.2753,-4.89937 -0.7692,-7.10565c2.69153,2.17743 5.30038,3.0463 7.77274,1.88208c5.51968,-2.58855 4.80566,-12.56688 -1.59302,-22.29628zm-23.45386,-9.83321c10.62857,0 19.25122,-7.2739 19.25122,-16.23871s-8.62265,-16.24432 -19.25122,-16.24432c-10.62859,0 -19.252,7.27385 -19.252,16.24432s8.62341,16.23871 19.252,16.23871z'
				  +'m-150,-45.00l0,0c0,-13.06001 10.3382,-23.6463 23.09219,-23.6463l10.49699,0l0,0c16.79402,0 81.58801,1 98.38202,1c-4.461,47.29129 -76.922,93.58229 -81.38202,140.8743l-27.49699,0c-12.754,0 -23.09219,-10.588 -23.09219,-23.645l0,0l0,-35.46898l0,0l0,-59.11401l0,-0.00001z'

	                ,scale: .15,
        	        strokeWeight: 1.5,
        	        strokeColor: '#FF0000',
				fillColor:  rtg_getColor('-'),
				fillOpacity: 1
			}
        });
		google.maps.event.addListener(marker, 'click', function(event) { 
							rtg_dialog('Nouveau site',rtg_getHtml(baseUrl+"form_si.php?action=siInsert&lat="+this.position.lat()+"&lon="+this.position.lng()));
		});
                
}

function rtg_updateSI(siid)
{
	rtg_dialog('Mise à jour',rtg_getHtml(baseUrl+"form_si.php?action=siUpdate&id="+siid));
}
function rtg_insertW(spid)
{
	var html = rtg_getHtml(baseUrl+"form_w.php?action=wInsert&spid="+spid);
	rtg_dialog('Création d\'une voie',html);
}
function rtg_updateW(wid)
{
	rtg_dialog('Mise à jour',rtg_getHtml(baseUrl+"form_w.php?action=wUpdate&id="+wid));
}
function rtg_duplicateW(wid)
{
	rtg_dialog('Dupliquer',rtg_getHtml(baseUrl+"form_w.php?action=wCopy&id="+wid));
}


function rtg_setToBdd(url,post)
 {
   var r;
    $.ajax({
        url: baseUrl+url,
        dataType:"JSON",
        type:"POST",
        async: false,
        data: post,
	complete: function (xhr, datas){
		try {	
			eval('r='+xhr["responseText"]);
		}
		catch(err){
			console.log(xhr["responseText"])
		}
	}
    })
    return r;
 }

 function rtg_getFromBdd(url,post)
 {
   var r;
   //var dataPost="?type="+post["type"]+"&id="+post["id"]
    $.ajax({
        url: baseUrl+url,//+dataPost,
        dataType:"JSON",
        type:"POST",
        async: false,
        data: post,
	complete: function (xhr, datas){
		try {	
			eval('r='+xhr["responseText"]);
		}
		catch(err){
			console.log("Error parse : " + xhr["responseText"])
			r = {id:"ERROR"};
		}
	}
    })
    return r;
 }
  
  
  function rtg_getMapInfos(data,endFunc)
  {
	// mode deconnecter
	
	if (rtg_plotMapInfos_datainfos.length > 1)
	{
		rtg_plotMapInfos(rtg_plotMapInfos_datainfos)
		return endFunc(rtg_plotMapInfos_datainfos)
	}
	else
	{
		// lit la base
		$.post(baseUrl+"rpc/rtg_refreshMap.php",data,endFunc);
	}
  }


  function rtg_getMesVoiesdatas(siid)
  {
    if (siid)
	{
	    if (rtg_getMesVoiesInfos_datainfos[siid]) {  return rtg_getMesVoiesInfos_datainfos[siid]; }
	    var r = rtg_getFromBdd("rpc/rtg_getdatas.php",{type:"mesvoies",id:siid});
	    if (r['multi'])
	    {
		 rtg_getMesVoiesInfos_datainfos[siid] = {};
		 for (var i = 0; i < r['multi'].length; i++) {
			 rtg_getMesVoiesInfos_datainfos[siid][r['multi'][i]['wid']] = r['multi'][i];
		 }
  	         return rtg_getMesVoiesInfos_datainfos[siid]
	    }

	}
  }


  function rtg_getWdatas(wid,multi)
  {
    if (wid)
	{
    if (rtg_getWInfos_datainfos[wid]) {  return rtg_getWInfos_datainfos[wid]; }
    var r = rtg_getFromBdd("rpc/rtg_getdatas.php",{type:"w",id:wid});
    		 ////console.log(wid+" "+r);
    if (r['multi'])
	{
		 for (var i = 0; i < r['multi'].length; i++) {
			 ////console.log('mem W : '+r['multi'][i]['id']);
			 rtg_getWInfos_datainfos[r['multi'][i]['id']] = r['multi'][i];
		 }
 		 if (multi == 'oui')
		 	return r;
	}
	else
	{
		rtg_getWInfos_datainfos[r['id']] = r;
	}
    		return rtg_getWInfos_datainfos[wid]
	}
  }
  
  function rtg_getSPdatas(spid,multi)
  {
    if (spid)
	{
    if (rtg_getSPInfos_datainfos[spid]) {  return rtg_getSPInfos_datainfos[spid]; }
    var r = rtg_getFromBdd("rpc/rtg_getdatas.php",{type:"sp",id:spid});
    if (r['multi'])
	{
		 for (var i = 0; i < r['multi'].length; i++) {
			 ////console.log('mem SP : '+r['multi'][i]['id']);
			 rtg_getSPInfos_datainfos[r['multi'][i]['id']] = r['multi'][i];
		 }
 		 if (multi == 'oui')
		 	return r;

	}
	else
	{
		rtg_getSPInfos_datainfos[r['id']] = r;
	}
    	return rtg_getSPInfos_datainfos[spid]
	}
  }
 
  function rtg_getSCdatas(scid,multi)
  {
	if (scid)
	{
	if (rtg_getSCInfos_datainfos[scid]) {  return rtg_getSCInfos_datainfos[scid]; }
	var r = rtg_getFromBdd("rpc/rtg_getdatas.php",{type:"sc",id:scid});
	if (r['multi'])
	{
		 for (var i = 0; i < r['multi'].length; i++) {
			 ////console.log('mem SC : '+r['multi'][i]['id']);
			 rtg_getSCInfos_datainfos[r['multi'][i]['id']] = r['multi'][i];
		 }
		 if (multi == 'oui')
		 	return r;
	}
	else
	{
		rtg_getSCInfos_datainfos[r['id']] = r;
	}
	return rtg_getSCInfos_datainfos[scid];
	}
  }

  function rtg_getSIdatas(siid)
  {
  	////console.log('read si '+siid)
  	if (siid)
	{
	if (rtg_getSIInfos_datainfos[siid]) {  return rtg_getSIInfos_datainfos[siid]; }
	var r = rtg_getFromBdd("rpc/rtg_getdatas.php",{type:"si",id:siid});
	
	if (r['multi'])
	{
		 for (var i = 0; i < r['multi'].length; i++) {
			 ////console.log('mem SI : '+r['multi'][i]['id']);
			 rtg_getSIInfos_datainfos[r['multi'][i]['id']] = r['multi'][i];
		 }
	}
	else
	{
		rtg_getSIInfos_datainfos[r['id']] = r;
	}	
	return rtg_getSIInfos_datainfos[siid];
	}
  }
  
  function rtg_getPIdatas(piid)
  {
  	////console.log('read pi '+piid)
  	if (piid)
	{
	if (rtg_getPIInfos_datainfos[piid]) {  return rtg_getPIInfos_datainfos[piid]; }
	var r = rtg_getFromBdd("rpc/rtg_getdatas.php",{type:"pi",id:piid});
	
	if (r['multi'])
	{
		 for (var i = 0; i < r['multi'].length; i++) {
			 ////console.log('mem SI : '+r['multi'][i]['id']);
			 rtg_getPIInfos_datainfos[r['multi'][i]['id']] = r['multi'][i];
		 }
	}
	else
	{
		rtg_getPIInfos_datainfos[r['id']] = r;
	}	
	return rtg_getPIInfos_datainfos[piid];
	}
  }
  
  function preLoadDataSI(siid)
  {
	  // si deja en cache, on ne relie pas 
	  // if (rtg_getSIInfos_datainfos[siid]) {  return }

	  var dataSI  = rtg_getSIdatas(siid);
	  //console.log(dataSI)
	  if (dataSI)
	  {

		  //console.log(dataSI['sc'])
		  var datasSC = rtg_getSCdatas(dataSI['sc'],'oui');
		  if (datasSC  && datasSC['multi'])
		  {
			  var sp = [];
			  for (var i = 0; i < datasSC['multi'].length; i++)
			  {
					 sp = sp.concat(datasSC['multi'][i]['sp']);
			  }
			  if (sp.length > 0)
			  {
				  var datasSP = rtg_getSPdatas(sp,'oui');
				  if (datasSP['multi'])
			          {
					  var  w = [];
					  for (var i = 0; i < datasSP['multi'].length; i++)
					  {
							 w = w.concat(datasSP['multi'][i]['w']);
					  }
					  //////console.log(w);
					  //if (w.lenght > 0)
					  //{
						var datasW  = rtg_getWdatas(w,'oui');
					  //}
				  }
		  	}
		  }
	  }
	  
  }
  
 
 
 
 

	
	
	
	
	function rtg_regen_viewSCImg(sc,i)
	{
			// on genere les SCFull
			rtg_regen_viewSCFullImg(sc,i,function (){
					rtg_setPrgBar('#savPrgBar',30,'Génération de l\'images d\'un secteur ('+(i+1)+'/'+sc.length+')','progress-bar-info');	
					rtg_SCTopo(sc[i],'img-gen');
					window.setTimeout (
					function(){ 
						rtg_regen_genSCImg(sc,i);
					},2000);
			});

	}
	
	function rtg_regen_genSCImg(sc,i,nextsi)
	{
		rtg_setImg("sc",sc[i],betaCreator.getImage(1, 'jpg', 0));
		////console.log("Img SC "+i+" : "+sc[i])
		if (sc.length > (i+1))
		{
			rtg_regen_viewSCImg(sc,(i+1));
		}
		else
		{
			rtg_setPrgBar('#savPrgBar',100,'Génération des l\'images finie','progress-bar-success');	
		}
	}	
	
	function rtg_regen_viewSCFullImg(sc,i)
	{
		// on genere les SP
		var dataSC  = rtg_getSCdatas(sc[i]);
			rtg_regen_viewSPImg(dataSC['sp'],0,function (){
					rtg_setPrgBar('#savPrgBar',30,'Génération de l\'images d\'un secteur ('+(i+1)+'/'+sc.length+')','progress-bar-info');	
					rtg_SCTopo(sc[i],'img-gen');
					window.setTimeout (
					function(){ 
						rtg_regen_genSCFullImg(sc,i);
					},2000);
			});

	}
	
	function rtg_regen_genSCFullImg(sc,i,nextsc)
	{
		rtg_setImg("scfull",sc[i],betaCreator.getImage(1, 'jpg', 0));
		////console.log("Img SC "+i+" : "+sc[i])
		rtg_regen_genSCImg(sc,i)
	}	
	
	function rtg_regen_viewSPImg(sp,i,nextsc)
	{
		rtg_setPrgBar('#savPrgBar',50,'Génération de l\'image d\'un depart ('+(i+1)+'/'+sp.length+')','progress-bar-info');	
		// on genere les voies
		////console.log("i "+i);
		////console.log(sp);
		////console.log("sp[i] "+sp[i]);
		var dataSP  = rtg_getSPdatas(sp[i]);
		////console.log("w: "+dataSP['w']);
		if (dataSP)
		{
			rtg_regen_viewWImg(dataSP['w'],0,function (){
					// on genere l'images du sp
					rtg_SPTopo(sp[i],'img-gen');
					window.setTimeout (
					function(){ 
						rtg_regen_genSPImg(sp,i,nextsc);
					},2000);
			});
		}
		else
		{
			rtg_regen_genSPImg(sp,i,nextsc);
		}
	}
	
	function rtg_regen_genSPImg(sp,i,nextsc)
	{
		rtg_setImg("sp",sp[i],betaCreator.getImage(1, 'jpg', 0));
		if (sp.length > (i+1))
		{
			rtg_regen_viewSPImg(sp,(i+1),nextsc);
		}
		else
		{
			////console.log("nextsc");
			nextsc();
		}
	}
		
	function rtg_regen_viewWImg(w,i,nextsp)
	{
		rtg_setPrgBar('#savPrgBar',80,'Génération de l\'image d\'une voie ('+(i+1)+'/'+w.length+')','progress-bar-info');	
		rtg_WTopo(w[i],'img-gen');
		window.setTimeout (
		function(){ 
			rtg_regen_genWImg(w,i,nextsp);
		},2000);
	}
	
	function rtg_regen_genWImg(w,i,nextsp)
	{
		
		rtg_setImg("w",w[i],betaCreator.getImage(1, 'jpg', 0));
		if (w.length > (i+1))
		{
			rtg_regen_viewWImg(w,(i+1),nextsp);
		}
		else
		{
			////console.log("nextsp");
			nextsp();
		}
	}
 
 
 
function rtg_isAllow(action,id)
{
	if (action == "")
		return true;
	
        if (action == "true")
		return true;

	if (action == "SIRead")
	{
		var dataSI = rtg_getSIdatas(id);
		if (dataSI['public'] == 1)
			return true;
	}	
	
	var r = rtg_getUserRight();
	if (rtg_isAdmin())
			return true;
	
	if( Object.prototype.toString.call( r[action] ) === '[object Array]' )
	{
		if (r[action].indexOf(id) >= 0)
			return true;
	}
	
	if (action == "SIRead")
	{
		return rtg_isAllow("SIWrite",id);
	}

	return false;
}

 


var rtg_right=null;
function rtg_getUserRight()
{
	if (rtg_right != null)
		return rtg_right;
	rtg_right = rtg_getFromBdd("rpc/rtg_getdatas.php",{type:"right"});
	return rtg_right;
}
function rtg_cleanUserRight()
{
	rtg_right=null;
}

function rtg_isAdmin()
{
	return rtg_hasRight('admin');
}

function rtg_hasRight(right)
{
	var r = rtg_getUserRight();
	if (r[right])
		return true;
	return false;
}


	function rtg_AddCompFunc() {
	    var selectBox = document.getElementById("AddCompFunc");
	    selectedValue = selectBox.options[selectBox.selectedIndex].value;
	    selectBox.selectedIndex=0;
	    rtg_AddCompForm(selectedValue,'')
	    }
	function rtg_RegenForm(f) {
	    if (f.length > 0)
	    {
		    for (var i=0;i<f.length;i++)
		    {
		    
			 rtg_AddCompForm(f[i].name,f[i].value);
		    }
	    }
	}	    

	function rtg_AddCompForm(k,valeur) {
	    var d = rtg_complements[k]
	    var idt = new Date().getTime()
	    //console.log(d);
	    if (d)
	    {
	    ht = '<div class="form-group" id="'+idt+'"><label class="col-sm-3" for="'+d[0]+'">'+d.nom+'</label><div class="col-sm-8">'
	    switch(d.mode) 
	    {
	    	default:
    			ht += '<input name="'+k+'" class="form-control" id="'+k+'" value="'+valeur+'">';
    			
    		break;
    		case 'image':
    		case 'logo':
	    		var uid = rtg_uid();
	    		if (!valeur)
	    		{
	    		   valeur=uid;

	    		}
	    		else
	    		{
	    		  //alert('REuse '+valeur)
	    		}
	    		   
	    		ht += '<tr><td colspan=2><input name="'+k+'" id="'+k+'" value="'+valeur+'" style="display:none;">'+
	    		     '<img id="'+ valeur+'" class="img-thumbnail" src="bddimg/comp/T.'+valeur+'.jpg"/>'+
	    		     '<a class="btn btn-primary glyphicon glyphicon-plus" onclick="return rtg_UploadImgDialog(\''+ valeur+'\',\'comp\',\''+ valeur+'\');">Charger une image</a>'+
'<a class="btn btn-default glyphicon glyphicon-pencil" onclick="return rtg_modifyImgDialog(\''+ valeur+'\',\'comp\');"> </a>'+
 '</td></tr>';
	    		
			    		
	    		
	    		break;
    		case 'gpx':
	    		var uid = rtg_uid();
	    		if (!valeur)
	    		{
	    		   valeur=uid;

	    		}
	    		else
	    		{
	    		  //alert('REuse '+valeur)
	    		}
	    		   
	    		ht += '<tr><td colspan=2><input name="'+k+'" id="'+k+'" value="'+valeur+'" style="display:none;">'+
	    		     '<a class="btn btn-primary glyphicon glyphicon-plus" onclick="return rtg_UploadGpxDialog(\''+ valeur+'\');">Charger un fichier gpx</a><a class="btn btn-primary glyphicon glyphicon-download-alt" href="'+fullBaseUrl+'gpx/'+valeur+'.gpx" target="_blank">Télécharger</a><a class="btn btn-primary glyphicon glyphicon-eye-open" href="http://www.gpx-view.com/gpx.php?f='+fullBaseUrl+'gpx/'+valeur+'.gpx" target="_blank">Voir</a>'+
 '</td></tr>';
	    		
			    		
	    		
	    		break;
    		case 'select':
    		case 'qualite':
    		    	ht += '<select name="'+k+'" class="form-control" id="'+k+'">';
    		    	ht +=  '<option></option>';
    		    	var vs = d.valeurs;
    		    	//console.log(vs);
    		    	for (var i=0;i<vs.length;i++)
		    	{
		    		if (vs[i])
		    		{
		    			select =""
		    			if (vs[i]['val'] == valeur)
		    				select=' selected'
	    		    	 	ht +=  '<option value="'+vs[i]['val']+'"'+select+'>'+vs[i]['aff']+'</option>';
	    		    	}
    		    	}
    		    	ht +=  '</select>';
    		break;
    	     }
    	     ht +=  '</div><div><a class="btn btn-default glyphicon glyphicon-trash" onClick="$(\'#'+idt+'\').remove()">Suppr.</a></div></div>';
    	     $('#complementForm').append(ht);
    	    }
	 }
	

var rtg_uid= (function() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
               .toString(16)
               .substring(1);
  }
  return function() {
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
           s4() + '-' + s4() + s4() + s4();
  };
})();
