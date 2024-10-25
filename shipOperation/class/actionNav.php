<?php
	require_once("../../config.php");
	
	if(isset($_POST['actionSearchVessel']) || !empty($_POST['actionSearchVessel']))
	{
	    $dataComp = $CGetDataSO->getDataSearchVessel();
	    print json_encode($dataComp);
	    exit;
	}
	if(isset($_POST['actionOptVessel']) || !empty($_POST['actionOptVessel']))
	{
	    $dataVessel = $CGetDataSO->getOptVessel();
	    print json_encode($dataVessel);
	    exit;
	}
	
	
?>