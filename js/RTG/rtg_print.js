
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
		color="#F2FF00"
	break;
	default:
		color="#F2FF00"
	break;		
	
	}
	return color
 }

  function rtg_addSC(map,data)
  {
  	   	  var color = rtg_getColor(data[4]);
		  var pPath = []

		  
		  var dataSC = rtg_getSCdatas(data[1]);
		  if (dataSC["sp"])
		  {
			  for (var i = 0; i < dataSC["sp"].length ; i++)
			  {
				var dataSP = rtg_getSPdatas(dataSC["sp"][i])
				var p = new google.maps.LatLng(dataSP["lat"], dataSP["lon"])
			    	pPath.push(p)
			  }
			  for (var i = dataSC["sp"].length-1 ; i >= 0  ; i--)
			  {
				var dataSP = rtg_getSPdatas(dataSC["sp"][i])
				var p = new google.maps.LatLng(dataSP["lat"], dataSP["lon"])
			    	pPath.push(p)
			  }		  
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
		  
		  
		  
		  zone.setMap(map);
  }
  
  function rtg_addSP(map,data)
  {
                var dataSP = rtg_getSPdatas(data[1]);
                isDraggable=false
               

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
		
               
  }



 
 
  function rtg_addPI(map,data)
  {
                var nfo = rtg_pi[data[3]]
                 var dataPI = rtg_getPIdatas(data[1]);
                isDraggable=false

		var marker = new google.maps.Marker({
		position: new google.maps.LatLng(data[2][0], data[2][1])
		,map: map
		,icon: 'img/'+nfo.icon
		,draggable:isDraggable
		});
		pis.push(marker)
		
  }
  

var iconsMaps = {}
  iconsMaps["si-Defaut"] =  "M 0,0 m0,0.0c-12,-13.06003 -5.661,-7.64633 -26.907,-12.64633l-137.5034,0l0,0l50.382,0l94.46439,0c6.123,0 11.99701,2.4913 16.32901,6.9243c4.32999,4.43602 6.763,10.44902 6.763,16.72202l0,59.114l0,0l0,35.4686l0,0c0,13.057 1.66199,21.645 -23.09201,23.645l-23.23199,0l-23.23201,0l-25.81899,32.12601l-19.2817,-33.063l-36.2817,-0.063l37.37575,0l17.37576,33l24.7515,-32c-12.7541,0 43.90739,-0.588 58.90739,-0.64499l13,-9l0,-52.46861l-1,-17l0.125,-4.88931l0.125,-4.88919l0.125,-4.88921l0.06201,-2.4447l0.06299,-2.4446z m-20,65.05545c-2.74695,-4.18672 -6.12448,-7.58061 -9.4743,-9.95508c-3.95551,2.43814 -8.7348,3.91502 -13.97955,3.91502l-13.31708,-3.07671c-4.01248,-0.69937 -21.63602,-19.10165 -24.38278,-14.91496c-6.39882,9.72943 12.10242,29.4881 17.62196,32.07664c2.47147,1.16422 0.21088,3.3902 1.90857,7.91931c1.69774,4.5291 0.79297,3.45247 -3.50301,7.96294c-4.29596,4.51044 -14.27826,5.61351 -9.39322,13.83423c4.88503,8.2207 28.5451,-2.54167 29.45158,-7.56828c0.90585,5.02661 4.41484,17.4688 8.20464,17.4688c6.31647,0 11.45267,-9.01131 11.45267,-20.14206c0,-2.5016 -0.2753,-4.89937 -0.7692,-7.10565c2.69153,2.17743 5.30038,3.0463 7.77274,1.88208c5.51968,-2.58855 4.80566,-12.56688 -1.59302,-22.29628zm-23.45386,-9.83321c10.62857,0 19.25122,-7.2739 19.25122,-16.23871s-8.62265,-16.24432 -19.25122,-16.24432c-10.62859,0 -19.252,7.27385 -19.252,16.24432s8.62341,16.23871 19.252,16.23871z m-150,-45.00l0,0c0,-13.06001 10.3382,-23.6463 23.09219,-23.6463l10.49699,0l0,0c16.79402,0 81.58801,1 98.38202,1c-4.461,47.29129 -76.922,93.58229 -81.38202,140.8743l-27.49699,0c-12.754,0 -23.09219,-10.588 -23.09219,-23.645l0,0l0,-35.46898l0,0l0,-59.11401l0,-0.00001z"  
 iconsMaps["si-Multi"] =  "M 65,-170 m0,0c-12,-13.06 -5.661,-7.64633 -26.907,-12.6463l-137.503,0l0,0l50.382,0l94.464,0c6.123,0 11.997,2.4913 16.329,6.92427c4.32999,4.43602 6.763,10.44902 6.763,16.72203l0,59.114l0,0l0,35.469l0,0c0,13.057 1.66199,21.645 -23.092,23.645l-23.232,0l-23.232,0l-25.819,32.12601l-19.282,-33.063l-36.28101,-0.063l37.375,0l17.37611,33l24.7515,-32c-12.7541,0 43.9074,-0.588 58.9074,-0.645l13,-8.99999l0,-52.469l-1,-17l0.125,-4.8893l0.125,-4.8892l0.125,-4.8892l0.06201,-2.4447l0.06299,-2.4446l-3.5,-30.557z m-199,11l0,0c0,-13.06001 10.3382,-23.6463 23.09219,-23.6463l10.49698,0l0,0c16.79404,0 81.58801,1 98.38203,1c65.539,5.29129 124.078,113.58229 -29.38203,139.8743l-79.49698,1c-12.754,0 -23.09219,-10.588 -23.09219,-23.645l0,0l0,-35.46898l0,0l0,-59.11401l0,-0.00001z"  
  iconsMaps["si-Falaise"] =  "M 0,0 m0,0.0c-12,-13.06003 -5.661,-7.64633 -26.907,-12.64633l-137.5034,0l0,0l50.382,0l94.46439,0c6.123,0 11.99701,2.4913 16.32901,6.9243c4.32999,4.43602 6.763,10.44902 6.763,16.72202l0,59.114l0,0l0,35.4686l0,0c0,13.057 1.66199,21.645 -23.09201,23.645l-23.23199,0l-23.23201,0l-25.81899,32.12601l-19.2817,-33.063l-36.2817,-0.063l37.37575,0l17.37576,33l24.7515,-32c-12.7541,0 43.90739,-0.588 58.90739,-0.64499l13,-9l0,-52.46861l-1,-17l0.125,-4.88931l0.125,-4.88919l0.125,-4.88921l0.06201,-2.4447l0.06299,-2.4446z m-20,65.05545c-2.74695,-4.18672 -6.12448,-7.58061 -9.4743,-9.95508c-3.95551,2.43814 -8.7348,3.91502 -13.97955,3.91502l-13.31708,-3.07671c-4.01248,-0.69937 -21.63602,-19.10165 -24.38278,-14.91496c-6.39882,9.72943 12.10242,29.4881 17.62196,32.07664c2.47147,1.16422 0.21088,3.3902 1.90857,7.91931c1.69774,4.5291 0.79297,3.45247 -3.50301,7.96294c-4.29596,4.51044 -14.27826,5.61351 -9.39322,13.83423c4.88503,8.2207 28.5451,-2.54167 29.45158,-7.56828c0.90585,5.02661 4.41484,17.4688 8.20464,17.4688c6.31647,0 11.45267,-9.01131 11.45267,-20.14206c0,-2.5016 -0.2753,-4.89937 -0.7692,-7.10565c2.69153,2.17743 5.30038,3.0463 7.77274,1.88208c5.51968,-2.58855 4.80566,-12.56688 -1.59302,-22.29628zm-23.45386,-9.83321c10.62857,0 19.25122,-7.2739 19.25122,-16.23871s-8.62265,-16.24432 -19.25122,-16.24432c-10.62859,0 -19.252,7.27385 -19.252,16.24432s8.62341,16.23871 19.252,16.23871z m-150,-45.00l0,0c0,-13.06001 10.3382,-23.6463 23.09219,-23.6463l10.49699,0l0,0c16.79402,0 81.58801,1 98.38202,1c-4.461,47.29129 -76.922,93.58229 -81.38202,140.8743l-27.49699,0c-12.754,0 -23.09219,-10.588 -23.09219,-23.645l0,0l0,-35.46898l0,0l0,-59.11401l0,-0.00001z"  
  iconsMaps["si-Bloc"] =  "M 0,0 m0,0m0,0c-12,-13.06 -5.661,-7.64633 -26.907,-12.6463l-137.503,0l0,0l50.382,0l94.464,0c6.123,0 11.997,2.4913 16.329,6.92427c4.32999,4.43602 6.763,10.44902 6.763,16.72203l0,59.114l0,0l0,35.469l0,0c0,13.057 1.66199,21.645 -23.092,23.645l-23.232,0l-23.232,0l-25.819,32.12601l-19.282,-33.063l-36.28101,-0.063l37.375,0l17.37611,33l24.7515,-32c-12.7541,0 43.9074,-0.588 58.9074,-0.645l13,-8.99999l0,-52.469l-1,-17l0.125,-4.8893l0.125,-4.8892l0.125,-4.8892l0.06201,-2.4447l0.06299,-2.4446l-3.5,-30.557zm-20,65.0555c-2.747,-4.1868 -6.1245,-7.5807 -9.4743,-9.9551c-3.9555,2.4381 -8.7348,3.915 -13.9795,3.915l-13.3171,-3.0767c-4.0125,-0.6994 -21.6361,-19.1017 -24.3828,-14.915c-6.3988,9.7295 12.1024,29.4881 17.622,32.0767c2.4714,1.1642 0.2108,3.3902 1.9085,7.9193c1.6978,4.5291 0.793,3.4524 -3.503,7.9629c-4.2959,4.5104 -14.27821,5.6135 -9.3932,13.8344c4.885,8.221 28.5451,-2.542 29.4516,-7.56841c0.9058,5.02641 4.4148,17.46841 8.2046,17.46841c6.3165,0 11.4527,-9.011 11.4527,-20.1417c0,-2.5016 -0.2753,-4.8994 -0.7692,-7.1056c2.6915,2.1774 5.3004,3.0463 7.7727,1.882c5.5197,-2.5885 4.8057,-12.5668 -1.593,-22.2962zm-23.4539,-9.8333c10.6286,0 19.2513,-7.2739 19.2513,-16.2387s-8.6227,-16.2443 -19.2513,-16.2443c-10.6286,0 -19.252,7.2739 -19.252,16.2443s8.6234,16.2387 19.252,16.2387zm-150.0001,-45l0,0c0,-13.05997 10.338,-23.6463 23.092,-23.6463l10.49699,0l0,0c-26.20599,112 50.843,-19.2913 73.382,21c22.539,40.2913 -38.922,37.5823 -26.382,120.8741l-57.497,0c-12.754,0 -23.092,-10.588 -23.092,-23.645l0,0l0,-35.4687l0,0l0,-59.1141l0,0z"  
  iconsMaps["si-SAE"] =    "M 0,0 m0,-0.84375m17.40235,-9.28125c-0.60938,-1.2475 -86.3446,-48.35727 -110.12185,-62.84943l-107.128,57.90234l-3.16406,1.89844l109.66716,-61.73828l80.84681,46.33984c14.34956,8.85938 30.91497,17.21005 31.72744,18.94771c0.81247,1.73766 -17.49481,0.21855 -18.76044,2.69469l3.05859,76.19994l0,0l0,35.469l0,0c0,13.057 1.66199,21.645 -23.092,23.645l-23.232,0l-23.232,0l-25.819,32.12601l-19.282,-33.063l-36.28101,-0.063l37.375,0l17.37611,33l24.7515,-32c-12.7541,0 43.9074,-0.588 58.9074,-0.645l13,-8.99999l0,-52.469l-1,-17l0.125,-4.8893l-0.50781,-4.8892l-0.19141,-5.2056l0.06201,-1.81189l-1.62451,-40.83522c5.9349,-0.16613 17.14844,-0.51653 16.53906,-1.76403zm-37.40235,74.33675c-2.747,-4.1868 -6.1245,-7.5807 -9.4743,-9.9551c-3.9555,2.4381 -8.7348,3.915 -13.9795,3.915l-13.3171,-3.0767c-4.0125,-0.6994 -21.6361,-19.1017 -24.3828,-14.915c-6.3988,9.7295 12.1024,29.4881 17.622,32.0767c2.4714,1.1642 0.2108,3.3902 1.9085,7.9193c1.6978,4.5291 0.793,3.4524 -3.503,7.9629c-4.2959,4.5104 -14.27821,5.6135 -9.3932,13.8344c4.885,8.221 28.5451,-2.542 29.4516,-7.56841c0.9058,5.02641 4.4148,17.46841 8.2046,17.46841c6.3165,0 11.4527,-9.011 11.4527,-20.1417c0,-2.5016 -0.2753,-4.8994 -0.7692,-7.1056c2.6915,2.1774 5.3004,3.0463 7.7727,1.882c5.5197,-2.5885 4.8057,-12.5668 -1.593,-22.2962zm-23.4539,-9.8333c10.6286,0 19.2513,-7.2739 19.2513,-16.2387s-8.6227,-16.2443 -19.2513,-16.2443c-10.6286,0 -19.252,7.2739 -19.252,16.2443s8.6234,16.2387 19.252,16.2387zm-150.0001,-45l0,0c0,-13.05997 10.338,-23.6463 23.092,-23.6463l-30.95222,0.31641l30.05859,-17.71875c51.71979,-1.25 41.87425,37.05635 67.53435,50.54297c25.6601,13.48662 18.47253,48.78543 9.41879,106.6241l-76.0595,2.10938c-12.754,0 -23.092,-10.588 -23.092,-23.645l0,0l0,-35.4687l0,0l0,-59.1141l0,0z"  
  iconsMaps["si-SAE"] =    "M 0,0 m0,-0.28125m17.4023,-9.28125c-0.6093,-1.2475 -72.0008,-41.6073 -110.1218,-62.8494l-107.12851,57.9023l-3.16399,1.8985l109.66759,-61.7383l80.8468,46.3398c14.34957,8.8594 30.915,17.2101 31.7274,18.94773c0.8125,1.73765 -17.49477,0.21855 -18.76039,2.69468l3.05859,76.19989l0,0l0,35.4693l0,0c0,13.057 1.66199,21.645 -23.092,23.645l-23.232,0l-23.232,0l-25.819,32.12599l-19.282,-33.063l-58.21901,-0.063l59.313,0l17.37611,33.00001l24.7515,-32c-12.7541,0 43.9074,-0.58801 58.9074,-0.645l13,-9l0,-52.4693l-1,-17l0.125,-4.8892l-0.50781,-4.8893l-0.19141,-5.2056l0.06201,-1.8118l-2.46826,-40.41339c5.9349,-0.16614 17.99217,-0.93841 17.38277,-2.18591zm-37.4023,74.3367c-2.747,-4.1868 -6.1245,-7.5806 -9.4743,-9.9551c-3.9555,2.4382 -8.7348,3.915 -13.9795,3.915l-13.3171,-3.0766c-4.0125,-0.6995 -21.6361,-19.1017 -24.3828,-14.915c-6.3988,9.7295 12.1024,29.4881 17.622,32.0767c2.4714,1.1642 0.2108,3.3902 1.9085,7.9192c1.6978,4.5291 0.793,3.4524 -3.503,7.9629c-4.2959,4.5104 -14.27821,5.61349 -9.3932,13.8347c4.885,8.221 28.5451,-2.54221 29.4516,-7.5687c0.9058,5.0265 4.4148,17.4687 8.2046,17.4687c6.3165,0 11.4527,-9.01099 11.4527,-20.1419c0,-2.5017 -0.2753,-4.8994 -0.7692,-7.1056c2.6915,2.1774 5.3004,3.0462 7.7727,1.8819c5.5197,-2.58849 4.8057,-12.5668 -1.593,-22.2962zm-23.4539,-9.8333c10.6286,0 19.2513,-7.2739 19.2513,-16.2386s-8.6227,-16.2443 -19.2513,-16.2443c-10.6286,0 -19.252,7.2738 -19.252,16.2443s8.6234,16.2386 19.252,16.2386zm-150.0001,-44.99995l0,0c0,-13.05997 10.338,-23.64625 23.092,-23.64625l-30.952,0.3164l103.46459,-57.375c43.7042,18.5781 92.92115,44.2282 112.2531,58.1367c19.332,13.90847 -59.5743,-16.6052 -183.7997,-0.5322l-0.966,140.7654c-12.754,0 -23.092,-10.025 -23.092,-23.082l0,0l0,-35.4689l0,0l0,-59.11415l0,0z"


  function rtg_addSI(map,data)
  {

      if (data[4] == '-')
	return;


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
                strokeColor: '#000000',

                fillColor:  rtg_getColor(data[4]),
                fillOpacity: .8
                }				

		});
		sis.push(marker)
		
		
  }
  
 function rtg_addGPX(map,data)
  { 

          if ($.inArray(data[2], gpxsId) == -1)
          {
      
	  $.ajax({
	  type: "GET",
	  url: data[2],
	  dataType: "xml",
	  success: function(xml) {
		var points = [];
		var bounds = new google.maps.LatLngBounds ();
		$(xml).find("trkpt").each(function() {
		  var lat = $(this).attr("lat");
		  var lon = $(this).attr("lon");
		  var p = new google.maps.LatLng(lat, lon);
		  points.push(p);
		  bounds.extend(p);
		});
	
		var poly = new google.maps.Polyline({
		  // use your own style here
		  path: points,
		  strokeColor: "#AA00FF",
		  strokeOpacity: .7,
		  strokeWeight: 4
		});
		
		poly.setMap(map);
	  }
	});
        }
  }

function rtg_plotMapInfos(map,datas,filter)
{
		var filterP = filter.split("-");
		var zoomLevel = map.getZoom();		
		for (var i = 0; i < datas.length ; i++) 
		{ 


			switch(datas[i][0]) {
						case "sp":
							if (zoomLevel > 18)
							{
								rtg_addSP(map,datas[i])
							}
							break;
						case "sc":
							if (zoomLevel > 15)
							{

								switch(filterP[0])
								{
									case "grp":
										var dataSC = rtg_getSCdatas(datas[i][1]);
										console.log(dataSC["groupe"]);
										
										if (filter == "grp-"+dataSC["groupe"])
											rtg_addSC(map,datas[i])
									break;
									default:
										rtg_addSC(map,datas[i])									
									break;
								}
							}
							break;
						case "si-SAE":
						case "si-Bloc":
						case "si-Falaise":
						case "si":

							if (zoomLevel <= 15)
							{
								if (filter == "si-"+datas[i][1])
									rtg_addSI(map,datas[i])
							}							
						 	break;
						case "pi":
							if (rtg_pi[datas[i][3]])
      							{
                                                             var nfo = rtg_pi[datas[i][3]]
	      			                            
									if (zoomLevel > 15 || zoomLevel > nfo.zoomlevel)
									{							
										rtg_addPI(map,datas[i]);
									}
                                                        }
							break;
						case "gpx":
								rtg_addGPX(map,datas[i]);
							break;
						
						} 						
			
		}
} 




var swLatSAV = 0
var swLngSAV = 0
var neLatSAV = 0
var neLngSAV = 0
var zoomLevelSAV = 10;
 function rtg_refreshMap(map,filter)
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
	
		swLatSAV = swLat
		swLngSAV = swLng
		neLatSAV = neLat
		neLngSAV = neLng
		zoomLevelSAV = zoomLevel

		rtg_getMapInfos({
		    "swLat":swLat-0.005,
		    "swLng":swLng-0.005,
		    "neLat":neLat+0.005,
		    "neLng":neLng+0.005,
		    "zoomLevel":zoomLevel,
		    "printmode":"1"
		  },
		function(data,status){
		    rtg_plotMapInfos(map,data,filter);
		});

  }





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






 function rtg_newMap(divId,lat,lon,zoom,type,filter)
 {

  // initialise la carte google maps
  var map = new google.maps.Map(document.getElementById(divId), {
      zoom: zoom,
      center: new google.maps.LatLng(lat,lon),
      mapTypeId: type,
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

 
  
  google.maps.event.addListener(map, 'domready',  function() {  rtg_refreshMap(map,filter);  });
	setTimeout( function() {rtg_refreshMap(map,filter)},2000)
}




 var infowindow = new google.maps.InfoWindow();
 var sps = [];
 var scs = [];
 var sis = [];
 var pis = [];
 var gpxsId = [];
  

// http://gpp3-wxs.ign.fr/'+rtg_GEOPORTAIL_KEY+'/wmts?service=WMTS&request=GetCapabilities
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

