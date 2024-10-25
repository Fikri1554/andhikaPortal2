<?php
	require_once("../../config.php");
	
	// if(isset($_POST['actionSearchVessel']) || !empty($_POST['actionSearchVessel']))
	// {
	//     $dataComp = $CGetDataSO->getDataSearchVessel();
	//     print json_encode($dataComp);
	//     exit;
	// }
	if(isset($_POST['actionCekDateLock']) || !empty($_POST['actionCekDateLock']))
	{
	    // $dataVessel = $CGetDataSO->getOptVessel();
	    print json_encode('lana');
	    // exit;
	}
	
?>