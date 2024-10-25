<?php
	require_once("../../config.php");
		
	if (isset($_POST['actionGetLatLongMap']) && !empty($_POST['actionGetLatLongMap'])) {
	    $getLatLongViewMap = $CPublic->getLatLongViewMap();
	    print json_encode($getLatLongViewMap);
	    exit;
	}

	
?>