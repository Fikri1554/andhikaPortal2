<html>
<head>	
	<script type="text/javascript" src="../../js/main.js"></script>
	<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>

	<!-- <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
	<link rel="stylesheet" href="http://leaflet.github.io/Leaflet.label/leaflet.label.css"/>
  	<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
  	<script src="http://leaflet.github.io/Leaflet.label/leaflet.label.js"></script> -->

	<link rel="stylesheet" href="../asset/leaflet.css" />
	<link rel="stylesheet" href="../asset/leafletLabel.css" />
  	<script src="../asset/leaflet.js"></script>
  	<script src="../asset/leafletLabel.js"></script>
  	
  	<style>
	  #mapid{
	    width: 100%;
	    height: 440px;
	  }  
	  .leaflet-control-attribution {
	    display: none;
	  }
	  .leaflet-control-layers-selector {
	    display: none;
	  }
	  @keyframes fade { 
	    from { opacity: 0; } 
	  }
	  .blinking1 { animation: fade 1.0s infinite alternate; }
	  .blinking2 { animation: fade 0.5s infinite alternate; }
	  .blinking3 { animation: fade 0.7s infinite alternate; }
	  .blinking4 { animation: fade 0.8s infinite alternate; }
	  .blinking5 { animation: fade 0.9s infinite alternate; }
	  .blinking6 { animation: fade 0.6s infinite alternate; }
	  .blinking7 { animation: fade 0.4s infinite alternate; }
	  .blinking8 { animation: fade 0.95s infinite alternate; }
	</style>
</head>
<body>
	<div class="container">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr valign="top">
			<td align="right" style="padding-bottom: 5px;">
				<div style="width: 95%;float: left;">
				<img id="idLoading" src="../../picture/ajax-loader12.gif" style="width:25px;height:25px;float:left;display:none;">
				<label id="dateAls" style="font-size: 12px;color:#9A25CB;font-weight:bold;"></label> ||
				<label id="dateAth" style="font-size: 12px;color:#C8BE00;font-weight:bold;"></label> ||
				<label id="dateBuBat" style="font-size: 12px;color:#000;font-weight:bold;"></label> ||
				<label id="dateBuHal" style="font-size: 12px;color:#4294C8;font-weight:bold;"></label> ||
				<label id="dateBuNus" style="font-size: 12px;color:#777D7F;font-weight:bold;"></label> <br>
				<label id="dateKan" style="font-size: 12px;color:#30A747;font-weight:bold;"></label> ||
				<!-- <label id="dateNar" style="font-size: 12px;color:#4294C8;font-weight:bold;"></label> || -->
				<label id="datePar" style="font-size: 12px;color:#F2941A;font-weight:bold;"></label> ||
				<label id="dateVid" style="font-size: 12px;color:#F00;font-weight:bold;"></label>
				</div>
				<div style="width: 5%;">
		    	<button id="btnCloseMap">X</button>
		    	</div>
			</td>
		</tr>
		<tr>
		    <td>
		        <div id="mapid"></div>
		    </td>
		</tr>
		</table>
	</div>
</body>
</html>
<script>
	var map = L.map('mapid').setView([-1.663586, 118.087937], 5);
	//L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: ''}).addTo(map);
	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibGFuaGFsIiwiYSI6ImNsaWp6M2swejBiOXczbm9rOHgwMDJ2Z2MifQ.HffJtVEwUZWajJ50X-gKeg', {
		maxZoom: 18,
		attribution: '',
		id: 'mapbox/streets-v11',
		tileSize: 512,
		zoomOffset: -1
	}).addTo(map);
	
	var layer1 = new L.LayerGroup();
	var layer2 = new L.LayerGroup();
  	var layer3 = new L.LayerGroup();
  	var layer4 = new L.LayerGroup();
  	var layer5 = new L.LayerGroup();
  	var layer6 = new L.LayerGroup();
  	var layer7 = new L.LayerGroup();
  	var layer8 = new L.LayerGroup();
  	var layer9 = new L.LayerGroup();
  	var baseLayer = {};
  	var overlays = {
    	'Keterangan :': layer1,
    	'<img width="10%;" src="icon/marker-icon-violet.png"> ALISHA</img>': layer8,
    	'<img width="10%;" src="icon/marker-icon-yellow.png"> ATHALIA</img>': layer7,
    	'<img width="10%;" src="icon/marker-icon-black.png"> BULK BATAVIA</img>': layer5,
    	'<img width="10%;" src="icon/marker-icon-blue.png"> BULK HALMAHERA</img>': layer3,
    	'<img width="10%;" src="icon/marker-icon-grey.png"> BULK NUSANTARA</img>': layer9,
    	'<img width="10%;" src="icon/marker-icon-green.png"> KANISHKA</img>': layer2,
    	// '<img width="10%;" src="icon/marker-icon-blue.png"> NARESWARI</img>': layer3,
    	'<img width="10%;" src="icon/marker-icon-orange.png"> PARAMESTI</img>': layer4,    	
    	'<img width="10%;" src="icon/marker-icon-red.png"> VIDYANATA</img>': layer6    	
  	};
  	L.control.layers(baseLayer, overlays, {collapsed: false}).addTo(map);

  	var kanishka = new L.Icon({
	    iconUrl: 'icon/marker-icon-2x-green.png',
	    shadowUrl: 'icon/marker-shadow.png',
	    className: 'blinking1',
	    iconSize: [25, 41],
	    iconAnchor: [12, 41],
	    popupAnchor: [1, -34],
	    shadowSize: [41, 41]
	  });
  	var halmahera = new L.Icon({
	    iconUrl: 'icon/marker-icon-2x-blue.png',
	    shadowUrl: 'icon/marker-shadow.png',
	    className: 'blinking2',
	    iconSize: [25, 41],
	    iconAnchor: [12, 41],
	    popupAnchor: [1, -34],
	    shadowSize: [41, 41]
	  });
  	// var nareswari = new L.Icon({
	  //   iconUrl: 'icon/marker-icon-2x-blue.png',
	  //   shadowUrl: 'icon/marker-shadow.png',
	  //   className: 'blinking2',
	  //   iconSize: [25, 41],
	  //   iconAnchor: [12, 41],
	  //   popupAnchor: [1, -34],
	  //   shadowSize: [41, 41]
	  // });
	var paramesti = new L.Icon({
	    iconUrl: 'icon/marker-icon-2x-orange.png',
	    shadowUrl: 'icon/marker-shadow.png',
	    className: 'blinking3',
	    iconSize: [25, 41],
	    iconAnchor: [12, 41],
	    popupAnchor: [1, -34],
	    shadowSize: [41, 41]
	  });
	var vidyanata = new L.Icon({
	    iconUrl: 'icon/marker-icon-2x-red.png',
	    shadowUrl: 'icon/marker-shadow.png',
	    className: 'blinking5',
	    iconSize: [25, 41],
	    iconAnchor: [12, 41],
	    popupAnchor: [1, -34],
	    shadowSize: [41, 41]
	  });
	var athalia = new L.Icon({
	    iconUrl: 'icon/marker-icon-2x-yellow.png',
	    shadowUrl: 'icon/marker-shadow.png',
	    className: 'blinking6',
	    iconSize: [25, 41],
	    iconAnchor: [12, 41],
	    popupAnchor: [1, -34],
	    shadowSize: [41, 41]
	  });
	var bulkBatavia = new L.Icon({
	    iconUrl: 'icon/marker-icon-2x-black.png',
	    shadowUrl: 'icon/marker-shadow.png',
	    className: 'blinking4',
	    iconSize: [25, 41],
	    iconAnchor: [12, 41],
	    popupAnchor: [1, -34],
	    shadowSize: [41, 41]
	  });

	var alisha = new L.Icon({
	    iconUrl: 'icon/marker-icon-2x-violet.png',
	    shadowUrl: 'icon/marker-shadow.png',
	    className: 'blinking7',
	    iconSize: [25, 41],
	    iconAnchor: [12, 41],
	    popupAnchor: [1, -34],
	    shadowSize: [41, 41]
	  });
	var bulkNusantara = new L.Icon({
	    iconUrl: 'icon/marker-icon-2x-grey.png',
	    shadowUrl: 'icon/marker-shadow.png',
	    className: 'blinking8',
	    iconSize: [25, 41],
	    iconAnchor: [12, 41],
	    popupAnchor: [1, -34],
	    shadowSize: [41, 41]
	  });

	$(document).ready(function(){
		document.getElementById("idLoading").style.display="";
	    $.post('../class/actionNav.php',
	    {   
	    	actionGetLatLongMap : "actionGetLatLongMap",
	    	vslName : "kanishka,nareswari,paramesti,vidyanata,athalia,batavia,alisha,nusantara,halmahera"
	    },
	    	function(data) 
	       	{
	       		if (data.kanishka == 'undefined' || data.kanishka == undefined)
	       		{
	       			$("#dateKan").append("Kanishka : -");
	       		}else{
	       			L.marker([data.kanishka.lat,data.kanishka.longs], {icon: kanishka}).addTo(map);
	       			$("#dateKan").append("Kanishka : "+data.kanishka.datePosition);
	       		}

	       		if (data.halmahera == 'undefined' || data.halmahera == undefined)
	        	{
	        		$("#dateBuHal").text("Nareswari : -");
	        	}else{
	        		L.marker([data.halmahera.lat,data.halmahera.longs], {icon: halmahera}).addTo(map);
	        		$("#dateBuHal").text("Halmahera : "+data.halmahera.datePosition);
	        	}

	        	// if (data.nareswari == 'undefined' || data.nareswari == undefined)
	        	// {
	        	// 	$("#dateNar").text("Nareswari : -");
	        	// }else{
	        	// 	L.marker([data.nareswari.lat,data.nareswari.longs], {icon: nareswari}).addTo(map);
	        	// 	$("#dateNar").text("Nareswari : "+data.nareswari.datePosition);
	        	// }

	         	if (data.paramesti == 'undefined' || data.paramesti == undefined) 
	         	{
	         		$("#datePar").text("Paramesti : -");
	         	}else{
	         		L.marker([data.paramesti.lat,data.paramesti.longs], {icon: paramesti}).addTo(map);
	         		$("#datePar").text("Paramesti : "+data.paramesti.datePosition);
	         	}

	         	if (data.vidyanata == 'undefined' || data.vidyanata == undefined)
	         	{
	         		$("#dateVid").text("Vidyanata : -");
	         	}else{
	         		L.marker([data.vidyanata.lat,data.vidyanata.longs], {icon: vidyanata}).addTo(map);
	         		$("#dateVid").text("Vidyanata : "+data.vidyanata.datePosition);
	         	}
	         	
	         	if (data.athalia == 'undefined' || data.athalia == undefined)
	         	{
	         		$("#dateAth").text("Athalia : -");
	         	}else{
	         		L.marker([data.athalia.lat,data.athalia.longs], {icon: athalia}).addTo(map);
	         		$("#dateAth").text("Athalia : "+data.athalia.datePosition);
	         	}

	         	if (data.batavia == 'undefined' || data.batavia == undefined)
	         	{
	         		$("#dateBuBat").text("Bulk Batavia : -");
	         	}else{
	         		L.marker([data.batavia.lat,data.batavia.longs], {icon: bulkBatavia}).addTo(map);
	         		$("#dateBuBat").text("Bulk Batavia : "+data.batavia.datePosition);
	         	}

	         	if (data.alisha == 'undefined' || data.alisha == undefined)
	         	{
	         		$("#dateAls").text("Alisha : -");
	         	}else{
	         		L.marker([data.alisha.lat,data.alisha.longs], {icon: alisha}).addTo(map);
	         		$("#dateAls").text("Alisha : "+data.alisha.datePosition);
	         	}

	         	if (data.nusantara == 'undefined' || data.nusantara == undefined)
	         	{
	         		$("#dateBuNus").text("Bulk Nusantara : -");
	         	}else{
	         		L.marker([data.nusantara.lat,data.nusantara.longs], {icon: bulkNusantara}).addTo(map);
	         		$("#dateBuNus").text("Bulk Nusantara : "+data.nusantara.datePosition);
	         	}
	         	document.getElementById("idLoading").style.display="none";
	       	},
	     	"json"
	  	);

	  	$("#btnCloseMap").click(function(){
	  		parent.tb_remove(false);
	  	});
	  });  
</script>