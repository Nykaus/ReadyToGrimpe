
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
  
  function rtg_addSI(map,data)
  {
      if (data[4] == '-')
	return;

		var marker = new google.maps.Marker({
		position: new google.maps.LatLng(data[2][0], data[2][1])
		,map: map
		
		,icon: {
                path: 'M 0,0 '
  +'m0,0.0c-12,-13.06003 -5.661,-7.64633 -26.907,-12.64633l-137.5034,0l0,0l50.382,0l94.46439,0c6.123,0 11.99701,2.4913 16.32901,6.9243c4.32999,4.43602 6.763,10.44902 6.763,16.72202l0,59.114l0,0l0,35.4686l0,0c0,13.057 1.66199,21.645 -23.09201,23.645l-23.23199,0l-23.23201,0l-25.81899,32.12601l-19.2817,-33.063l-36.2817,-0.063l37.37575,0l17.37576,33l24.7515,-32c-12.7541,0 43.90739,-0.588 58.90739,-0.64499l13,-9l0,-52.46861l-1,-17l0.125,-4.88931l0.125,-4.88919l0.125,-4.88921l0.06201,-2.4447l0.06299,-2.4446z'
  +'m-20,65.05545c-2.74695,-4.18672 -6.12448,-7.58061 -9.4743,-9.95508c-3.95551,2.43814 -8.7348,3.91502 -13.97955,3.91502l-13.31708,-3.07671c-4.01248,-0.69937 -21.63602,-19.10165 -24.38278,-14.91496c-6.39882,9.72943 12.10242,29.4881 17.62196,32.07664c2.47147,1.16422 0.21088,3.3902 1.90857,7.91931c1.69774,4.5291 0.79297,3.45247 -3.50301,7.96294c-4.29596,4.51044 -14.27826,5.61351 -9.39322,13.83423c4.88503,8.2207 28.5451,-2.54167 29.45158,-7.56828c0.90585,5.02661 4.41484,17.4688 8.20464,17.4688c6.31647,0 11.45267,-9.01131 11.45267,-20.14206c0,-2.5016 -0.2753,-4.89937 -0.7692,-7.10565c2.69153,2.17743 5.30038,3.0463 7.77274,1.88208c5.51968,-2.58855 4.80566,-12.56688 -1.59302,-22.29628zm-23.45386,-9.83321c10.62857,0 19.25122,-7.2739 19.25122,-16.23871s-8.62265,-16.24432 -19.25122,-16.24432c-10.62859,0 -19.252,7.27385 -19.252,16.24432s8.62341,16.23871 19.252,16.23871z'
  +'m-150,-45.00l0,0c0,-13.06001 10.3382,-23.6463 23.09219,-23.6463l10.49699,0l0,0c16.79402,0 81.58801,1 98.38202,1c-4.461,47.29129 -76.922,93.58229 -81.38202,140.8743l-27.49699,0c-12.754,0 -23.09219,-10.588 -23.09219,-23.645l0,0l0,-35.46898l0,0l0,-59.11401l0,-0.00001z'
 

                ,scale: .15,
                strokeWeight: 1.5,
                strokeColor: '#000000',

                fillColor:  rtg_getColor(data[4]),
                fillOpacity: .8
                }				

		});
		sis.push(marker)
		
		
  }
  
function rtg_plotMapInfos(map,datas)
{
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
								rtg_addSC(map,datas[i])
							}
							break;
						case "si-SAE":
						case "si-Block":
						case "si-Falaise":
						case "si":
							if (zoomLevel <= 15)
							{
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
						} 						
			
		}
} 




var swLatSAV = 0
var swLngSAV = 0
var neLatSAV = 0
var neLngSAV = 0
var zoomLevelSAV = 10;
 function rtg_refreshMap(map)
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
		    "swLat":swLat,
		    "swLng":swLng,
		    "neLat":neLat,
		    "neLng":neLng,
		    "zoomLevel":zoomLevel,
		    "printmode":"1"
		  },
		function(data,status){
		    rtg_plotMapInfos(map,data);
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






 function rtg_newMap(divId,lat,lon,zoom,type)
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

 
  
  google.maps.event.addListener(map, 'domready',  function() {  rtg_refreshMap(map);  });
	setTimeout( function() {rtg_refreshMap(map)},2000)
}




 var infowindow = new google.maps.InfoWindow();
 var sps = [];
 var scs = [];
 var sis = [];
 var pis = [];
  

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

