<html>
<head>	
	<script type="text/javascript" src="../../js/main.js"></script>
	<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
	<script src="../../js/jquery-ui.js"></script>
	<link rel="stylesheet" href="../../css/jquery-ui.css">

	<link rel="stylesheet" href="../asset/leaflet.css" />
	<link rel="stylesheet" href="../asset/leafletLabel.css" />
  	<script src="../asset/leaflet.js"></script>
  	<script src="../asset/leafletLabel.js"></script>
	<!-- <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
	<link rel="stylesheet" href="http://leaflet.github.io/Leaflet.label/leaflet.label.css"/>
  	<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
  	<script src="http://leaflet.github.io/Leaflet.label/leaflet.label.js"></script> -->

  	<link href="../../css/main.css" rel="stylesheet" type="text/css" />
	<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
	<title>Vessel Tracking</title>	
  	<script type="text/javascript">
  		$("#idBtnHistoryVessel").hide();
  		var markers;
  		$(document).ready(function(){
  			var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formBackHome.submit();" style="width:70px;height:40px;float:left;margin-left:10px;" title="Back"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/arrow-return-180-left.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">BACK</td></tr></table></button>');
      		$("#idNavHome").append(html);

      		var color,image1,image2 = "";

      		$.post('../class/actionNav.php',
			{
				actionOptVessel : "actionOptVessel"
			},
				function(data) 
				{
					$("#slcVessel").append(data);
				},
			"json"
			);

      		$( "#txtSDate" ).datepicker({
				dateFormat: 'yy-mm-dd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
		    });
		    $( "#txtEdate" ).datepicker({
				dateFormat: 'yy-mm-dd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
		    });
      		$("#btnSearch").click(function(){
      			var sDate = "";
      			var eDate = "";
      			var slcVessel = "";
      			var colorLine = "";
      			var cekData = 0;
      			removeLine();      			

      			slcVessel = $("#slcVessel").val();
      			sDate = $("#txtSDate").val();
      			eDate = $("#txtEdate").val();

      			if(slcVessel == "")
      			{
      				alert("Vessel Empty..!!");
      				return false;
      			}

      			$.post('../class/actionNav.php',
			    {
			    	actionSearchVessel : "actionSearchVessel",
			    	slcVessel : slcVessel,sDate : sDate,eDate : eDate
			    },
				    function(data) 
				    {
				    	var jmlData = data.dataLoc.length;
				      	var arr = [];
				       	$.each(data.dataLoc, function (key, val) {
				       		cekData++;
				       		arr.push(new L.LatLng(val.lat, val.longs));
				       		if(cekData == jmlData)
				       		{				       			
				       			viewPoint(data.image,data.image_2,val.lat,val.longs);
				       			map.panTo(new L.LatLng(val.lat, val.longs));
				       		}
						});
				        var options ={color: data.color, weight: 3,opacity: 0.5, smoothFactor: 1};
				        var polyline = new L.Polyline(arr, options);
				        polyline.addTo(map);
				    },
			    "json"
			  	);
      		});
  		});
  		function removeLine()
  		{
  			for(i in map._layers)
  			{
		        if(map._layers[i]._path != undefined)
		        {
		        	try {
		                map.removeLayer(map._layers[i]);
		            }
		            catch(e) {
		                console.log("problem with " + e + map._layers[i]);
		            }
		        }
	   		}
	   		if (markers)
	   		{
				map.removeLayer(markers);
			}
  		}
  		function viewPoint(icon1,icon2,lat,longs)
  		{
  			var icon_1,icon_2 = '';
      		icon_1 = 'icon/'+icon1;
  			icon_2 = 'icon/'+icon2;
      		
  			var iconNya = new L.Icon({
			    iconUrl: icon_1,
			    shadowUrl: icon_2,
			    className: 'blinking',
			    iconSize: [25, 41],
			    iconAnchor: [12, 41],
			    popupAnchor: [1, -34],
			    shadowSize: [41, 41]
			  });
  			markers = new L.marker([lat,longs], {icon: iconNya}).addTo(map);  			
  		}
  	</script>
  	<style>
	  #mapid{
	    width: 100%%;
	    height: 510px;
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
	  .ui-widget{ font-size: 12px; }
	  .ui-datepicker{ width: 232px; }
	  .blinking { animation: fade 0.9s infinite alternate; }
	</style>
</head>
<body style="background-color:#dbdbdb;">
	<div class="container" align="center">
		<div id="idHead" style="background-color:#1d4e69;height:50px;margin:-8px;margin-bottom:2px;" align="center">
			<div style="width: 80%;padding-top: 10px;" align="left">
				<label style="font-size: 24px;font-family: sans-serif;color: #FFF;padding-left: 10px;" >Vessel Tracking</label>
			</div>			
		</div>
		<div id="idTable">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
			    <td align="center">
			        <div style="width: 80%;" id="mapid"></div>
			    </td>
			</tr>
			</table>
		</div>		
		<div style="width:80%;margin-top:2px;min-height:150px;background-color:#EBECEC;" align="left">
			<div id="idForm" style="padding: 10px;">
				<label style="margin-top: 10px;">Search Data :&nbsp&nbsp</label>
				<select class="elementSearch" id="slcVessel" style="width: 200px; margin: 0px 10px 0px 10px;">
					<option value="">-Select-</option>
					{dataSlcOpt}
					<!-- <option value="kanishka">ANDHIKA KANISHKA</option>
					<option value="nareswari">ANDHIKA NARESWARI</option>
					<option value="paramesti">ANDHIKA PARAMESTI</option>
					<option value="ventura">ANDHIKA VENTURA</option>
					<option value="vidyanata">ANDHIKA VIDYANATA</option> -->
				</select>
				<input type="text" name="txtSDate" placeholder="Start Date" id="txtSDate" class="elementSearch" style="width:10%;margin: 0px 10px 0px 0px;">
				<input type="text" name="txtEdate" placeholder="End Date" id="txtEdate" class="elementSearch" style="width:10%;margin: 0px 10px 0px 0px;">
				<button class="btnStandar" id="btnSearch" style="width:100px;height:29px;" title="Search">
		        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
		            	<tr>
		                	<td align="right" width="25"><img src="../../picture/Search-blue-32.png" height="20"/> </td>
		                    <td align="center" style="font-size: 14px;">&nbsp Search</td>
		              	</tr>
		         	</table>
		     	</button>
	     	</div>
	     	<div id="idKet" style="padding: 10px;" align="center">
	     		<img style="width:15px;margin: 0px 10px 0px 10px;" src="icon/marker-icon-green.png">&nbsp KANISHKA</img>
	     		<img style="width:15px;margin: 0px 10px 0px 10px;" src="icon/marker-icon-blue.png">&nbsp NARESWARI</img>
	     		<img style="width:15px;" src="icon/marker-icon-orange.png">&nbsp PARAMESTI</img>
	     		<img style="width:15px;" src="icon/marker-icon-red.png">&nbsp VIDYANATA</img>
	     		<img style="width:15px;" src="icon/marker-icon-yellow.png">&nbsp ATHALIA</img>
	     		<img style="width:15px;" src="icon/marker-icon-black.png">&nbsp BULK BATAVIA</img>
	     		<img style="width:15px;" src="icon/marker-icon-violet.png">&nbsp ALISHA</img>
	     		<img style="width:15px;" src="icon/marker-icon-grey.png">&nbsp BULK NUSANTARA</img>
	     	</div>
		</div>
	</div>
</body>
</html>
<script>
	var map = L.map('mapid').setView([-1.0463888888889,117.2075], 5);
	// L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: ''}).addTo(map);
	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibGFuaGFsIiwiYSI6ImNsaWp6M2swejBiOXczbm9rOHgwMDJ2Z2MifQ.HffJtVEwUZWajJ50X-gKeg', {
		maxZoom: 18,
		attribution: '',
		id: 'mapbox/streets-v11',
		tileSize: 512,
		zoomOffset: -1
	}).addTo(map);

	// var pointA = new L.LatLng(-5.837258, 105.987716);
	// var pointB = new L.LatLng(-5.353431, 107.179732);
	// var pointC = new L.LatLng(-5.200274, 114.617476);
	// var pointList = [pointA, pointB,pointC];

	// var firstpolyline = new L.Polyline(pointList, {
	//     color: '#ea1010',
	//     weight: 3,
	//     opacity: 0.5,
	//     smoothFactor: 1
	// });
	// firstpolyline.addTo(map);

		// var arr = [];
  //       arr.push(new L.LatLng(-5.837258, 105.987716));
  //       arr.push(new L.LatLng(-5.353431, 107.179732));
  //       arr.push(new L.LatLng(-5.200274, 114.617476));

  //       var options ={color: '#ea1010', weight: 3,opacity: 0.5, smoothFactor: 1 };
  //       var polyline = new L.Polyline(arr, options);
  //       polyline.addTo(map);

</script>