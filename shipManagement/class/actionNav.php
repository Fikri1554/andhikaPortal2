<?php
	require_once("../../config.php");
	
	if(isset($_POST['actionEquip']) && !empty($_POST['actionEquip']) || $_POST['actionEquip'] == "0") {
	    $actionEquip = $_POST['actionEquip'];
	    $dataComp = $CGetData->getDataComponent($actionEquip,"","exportCompJob");
	    print json_encode($dataComp);
	    exit;
	}
	if (isset($_POST['actionCompDesc']) && !empty($_POST['actionCompDesc'])) {
		$actionCompDesc = $_POST['actionCompDesc'];
	    $dataDescComp = $CGetData->getDataDescComp($actionCompDesc);
	    print json_encode($dataDescComp);
	    exit;
	}
	if (isset($_POST['compCode1']) && !empty($_POST['compCode1']) || $_POST['actionEquip'] != "") {
		$data = $_POST['compCode1'];
	    $stAction = $CGetData->insertComponentJob($data);
	    print json_encode($stAction);
	    exit;
	}
	if (isset($_POST['idEdit']) && !empty($_POST['idEdit'])) {
		$idEdit = $_POST['idEdit'];
	    $dataEdit = $CGetData->getDataComponent("0",$idEdit);
	    print json_encode($dataEdit);
	    exit;
	}
	if (isset($_POST['idDel']) && !empty($_POST['idDel'])) {
		$idDel = $_POST['idDel'];
	    $dataDel = $CGetData->delData($idDel);
	    print json_encode($dataDel);
	    exit;
	}
	if (isset($_POST['actionDoneJob']) && !empty($_POST['actionDoneJob'])) {
	    $dataDoneJob = $CGetData->updateDoneJob();
	    print json_encode($dataDoneJob);
	    exit;
	}
	if (isset($_POST['actionOpenUnscheduled']) && !empty($_POST['actionOpenUnscheduled'])) {
	    $actionOpenUnscheduled = $CGetData->getDataUnschedule();
	    print json_encode($actionOpenUnscheduled);
	    exit;
	}
	if (isset($_POST['actionCodeUnsch']) && !empty($_POST['actionCodeUnsch'])) {
	    $actionCodeUnsch = $CGetData->saveUnSchedule();
	    print json_encode($actionCodeUnsch);
	    exit;
	}
	if (isset($_POST['actionCom']) && !empty($_POST['actionCom'])) {
	    $actionCom = $CGetData->getDataCompHours();
	    print json_encode($actionCom);
	    exit;
	}
	if (isset($_POST['actionEditComp']) && !empty($_POST['actionEditComp'])) {
	    $actionEditComp = $CGetData->editDataCompHours();
	    print json_encode($actionEditComp);
	    exit;
	}
	if (isset($_POST['actionOverhaul']) && !empty($_POST['actionOverhaul'])) {
	    $actionOverhaul = $CGetData->updateDataCompHours();
	    print json_encode($actionOverhaul);
	    exit;
	}
	if (isset($_POST['actionDateSearch']) && !empty($_POST['actionDateSearch'])) {
	    $actionDateSearch = $CGetData->getDataEquip($_POST['actionDateSearch']);
	    print json_encode($actionDateSearch);
	    exit;
	}
	if (isset($_POST['actionDelEquip']) && !empty($_POST['actionDelEquip'])) {
	    $actionDelEquip = $CGetData->delDataEquip();
	    print json_encode($actionDelEquip);
	    exit;
	}
	if (isset($_POST['actionMstCompJob']) && !empty($_POST['actionMstCompJob'])) {
	    $actionMstCompJob = $CGetData->getMstCompJob();
	    print json_encode($actionMstCompJob);
	    exit;
	}
	if (isset($_POST['actionMstCompDesc']) && !empty($_POST['actionMstCompDesc'])) {
	    $actionMstCompDesc = $CGetData->getMstDescComp();
	    print json_encode($actionMstCompDesc);
	    exit;
	}
	if (isset($_POST['actionMstJobDesc']) && !empty($_POST['actionMstJobDesc'])) {
	    $actionMstJobDesc = $CGetData->getMstJobDesc();
	    print json_encode($actionMstJobDesc);
	    exit;
	}
	if (isset($_POST['actionMstgetNewCode']) && !empty($_POST['actionMstgetNewCode'])) {
	    $actionMstgetNewCode = $CGetData->getMstNewCode();
	    print json_encode($actionMstgetNewCode);
	    exit;
	}
	if (isset($_POST['actionMstSlcCode']) && !empty($_POST['actionMstSlcCode'])) {
	    $actionMstSlcCode = $CGetData->saveJobDesc();
	    print json_encode($actionMstSlcCode);
	    exit;
	}
	if (isset($_POST['actionEditMstDescJob']) && !empty($_POST['actionEditMstDescJob'])) {
	    $actionEditMstDescJob = $CGetData->editMstJobDesc();
	    print json_encode($actionEditMstDescJob);
	    exit;
	}
	if (isset($_POST['actionDelMstJobDesc']) && !empty($_POST['actionDelMstJobDesc'])) {
	    $actionDelMstJobDesc = $CGetData->delMstJobDesc();
	    print json_encode($actionDelMstJobDesc);
	    exit;
	}
	if (isset($_POST['actionEditMstWorkClass']) && !empty($_POST['actionEditMstWorkClass'])) {
	    $actionEditMstWorkClass = $CGetData->editMstWorkClass();
	    print json_encode($actionEditMstWorkClass);
	    exit;
	}
	if (isset($_POST['actionMstIdWorkClass']) && !empty($_POST['actionMstIdWorkClass'])) {
	    $actionMstIdWorkClass = $CGetData->saveMstWorkClass();
	    print json_encode($actionMstIdWorkClass);
	    exit;
	}
	if (isset($_POST['actionMaintenanceReport']) && !empty($_POST['actionMaintenanceReport'])) {
	    $actionMaintenanceReport = $CGetData->getDataMaintenance();
	    print json_encode($actionMaintenanceReport);
	    exit;
	}
	if (isset($_POST['actionDateWorkList']) && !empty($_POST['actionDateWorkList'])) {
	    $actionDateWorkList = $CGetData->getDataWorkList();
	    print json_encode($actionDateWorkList);
	    exit;
	}
	if (isset($_POST['actionDateSearchWorkList']) && !empty($_POST['actionDateSearchWorkList'])) {
	    $actionDateSearchWorkList = $CGetData->getDataWorkList("dataSearch");
	    print json_encode($actionDateSearchWorkList);
	    exit;
	}
	if (isset($_POST['actionGetReportPDF']) && !empty($_POST['actionGetReportPDF'])) {
	    $actionGetReportPDF = $CGetData->getReportPDF();
	    print json_encode($actionGetReportPDF);
	    exit;
	}
	if (isset($_POST['actionExportCompJob']) && !empty($_POST['actionExportCompJob'])) {
	    $actionExportCompJob = $CGetData->getExportCompJob();
	    print json_encode($actionExportCompJob);
	    exit;
	}
	if (isset($_POST['actionExportRoutinJob']) && !empty($_POST['actionExportRoutinJob'])) {
	    $CGetData->getExportRoutinJob();
	    exit;
	}
	if (isset($_POST['actionSetSessionVessel']) && !empty($_POST['actionSetSessionVessel'])) {
		$_SESSION["vesselCode"] = $_POST["actionSetSessionVessel"];
		$_SESSION["vesselName"] = $_POST["vesselName"];
	    exit;
	}
	if (isset($_POST['actionDeficiency']) && !empty($_POST['actionDeficiency'])) {
		$actionDeficiency = $CGetData->addEditDeficiency();
		print json_encode($actionDeficiency);
	    exit;
	}
	if (isset($_POST['actionUpdateDefMst']) && !empty($_POST['actionUpdateDefMst'])) {
		$actionUpdateDefMst = $CGetData->getDataEditDeficiencyMst();
		print json_encode($actionUpdateDefMst);
	    exit;
	}
	if (isset($_POST['idDelDeficiencyMst']) && !empty($_POST['idDelDeficiencyMst'])) {
		$idDelDeficiencyMst = $CGetData->delDeficiencyMst();
		print json_encode($idDelDeficiencyMst);
	    exit;
	}
	if (isset($_POST['actionSetDefDetail']) && !empty($_POST['actionSetDefDetail'])) {
		$actionSetDefDetail = $CGetData->getDataDeficiencyDetail();
		print json_encode($actionSetDefDetail);
	    exit;
	}
	if (isset($_POST['actionDeficiencyDetail']) && !empty($_POST['actionDeficiencyDetail'])) {
		$actionDeficiencyDetail = $CGetData->addEditDefDetail();
		print json_encode($actionDeficiencyDetail);
	    exit;
	}
	if (isset($_POST['actionUpdateDefDetail']) && !empty($_POST['actionUpdateDefDetail'])) {
		$actionUpdateDefDetail = $CGetData->getDataEditDeficiencyDetail();
		print json_encode($actionUpdateDefDetail);
	    exit;
	}
	if (isset($_POST['idDelDeficiencyDetail']) && !empty($_POST['idDelDeficiencyDetail'])) {
		$idDelDeficiencyDetail = $CGetData->delDeficiencyDetail();
		print json_encode($idDelDeficiencyDetail);
	    exit;
	}
	if (isset($_POST['actionExportExcelAllCompJob']) && !empty($_POST['actionExportExcelAllCompJob'])) {
		$actionEquip = $_POST['actionEquipNya'];
	    $CGetData->getExportCompAllJob($actionEquip);
	    exit;
	}
	if (isset($_POST['actionSaveMasterVisselSurveyStatus']) && !empty($_POST['actionSaveMasterVisselSurveyStatus'])) {
	    $actionSaveMasterVisselSurveyStatus = $CGetDataSurveyStatus->saveMstVesselSurveyStatus();
	    print json_encode($actionSaveMasterVisselSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionEditMstSurveyStatus']) && !empty($_POST['actionEditMstSurveyStatus'])) {
		$idEdit = $_POST['actionEditMstSurveyStatus'];
		$actionEditMstSurveyStatus = $CGetDataSurveyStatus->getDataMstVesselSurveyStatus($idEdit);
		print json_encode($actionEditMstSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionIdDelMstSurveyStatus']) && !empty($_POST['actionIdDelMstSurveyStatus'])) {
		$actionIdDelMstSurveyStatus = $CGetDataSurveyStatus->delDataMstVesselSurveyStatus();
		print json_encode($actionIdDelMstSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionSaveMasterPicSurveyStatus']) && !empty($_POST['actionSaveMasterPicSurveyStatus'])) {
	    $actionSaveMasterPicSurveyStatus = $CGetDataSurveyStatus->saveMstPicSurveyStatus();
	    print json_encode($actionSaveMasterPicSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionEditMstPicSurveyStatus']) && !empty($_POST['actionEditMstPicSurveyStatus'])) {
		$idEdit = $_POST['actionEditMstPicSurveyStatus'];
		$actionEditMstPicSurveyStatus = $CGetDataSurveyStatus->getDataMstPicSurveyStatus($idEdit);
		print json_encode($actionEditMstPicSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionIdDelMstPicSurveyStatus']) && !empty($_POST['actionIdDelMstPicSurveyStatus'])) {
		$actionIdDelMstPicSurveyStatus = $CGetDataSurveyStatus->delDataMstPicSurveyStatus();
		print json_encode($actionIdDelMstPicSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionSaveMasterCertSurveyStatus']) && !empty($_POST['actionSaveMasterCertSurveyStatus'])) {
	    $actionSaveMasterCertSurveyStatus = $CGetDataSurveyStatus->saveMstCertSurveyStatus();
	    print json_encode($actionSaveMasterCertSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionEditMstCertSurveyStatus']) && !empty($_POST['actionEditMstCertSurveyStatus'])) {
		$idEdit = $_POST['actionEditMstCertSurveyStatus'];
		$actionEditMstCertSurveyStatus = $CGetDataSurveyStatus->getDataMstCertSurveyStatus($idEdit);
		print json_encode($actionEditMstCertSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionIdDelMstCertSurveyStatus']) && !empty($_POST['actionIdDelMstCertSurveyStatus'])) {
		$actionIdDelMstCertSurveyStatus = $CGetDataSurveyStatus->delDataMstCertSurveyStatus();
		print json_encode($actionIdDelMstCertSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionCertSurveyStatus']) && !empty($_POST['actionCertSurveyStatus'])) {
	    $actionCertSurveyStatus = $CGetDataSurveyStatus->getDataCertSurveyStatus();
	    print json_encode($actionCertSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionSaveCertSurveyItem']) && !empty($_POST['actionSaveCertSurveyItem'])) {
	    $actionSaveCertSurveyItem = $CGetDataSurveyStatus->saveCertSurveyStatus();
	    print json_encode($actionSaveCertSurveyItem);
	    exit;
	}
	if (isset($_POST['actionIdEditCertSurveyItem']) && !empty($_POST['actionIdEditCertSurveyItem'])) {
		$idEdit = $_POST['actionIdEditCertSurveyItem'];
		$actionIdEditCertSurveyItem = $CGetDataSurveyStatus->getDataEditCertSurveyStatus($idEdit);
		print json_encode($actionIdEditCertSurveyItem);
	    exit;
	}
	if (isset($_POST['actionIdDelCertSurveyItem']) && !empty($_POST['actionIdDelCertSurveyItem'])) {
		$actionIdDelCertSurveyItem = $CGetDataSurveyStatus->delDataCertSurveyStatus();
		print json_encode($actionIdDelCertSurveyItem);
	    exit;
	}
	if (isset($_POST['actionGetTransSurveyStatus']) && !empty($_POST['actionGetTransSurveyStatus'])) {
		$actionGetTransSurveyStatus = $CGetDataSurveyStatus->getDataTrans();
		print json_encode($actionGetTransSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionSaveTransactionSurveyStatus']) && !empty($_POST['actionSaveTransactionSurveyStatus'])) {
	    $actionSaveTransactionSurveyStatus = $CGetDataSurveyStatus->saveDataTrans();
	    print json_encode($actionSaveTransactionSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionIdEditTransSurveyItem']) && !empty($_POST['actionIdEditTransSurveyItem'])) {
		$idEdit = $_POST['actionIdEditTransSurveyItem'];
		$actionIdEditTransSurveyItem = $CGetDataSurveyStatus->getDataEditTransSurveyStatus($idEdit);
		print json_encode($actionIdEditTransSurveyItem);
	    exit;
	}
	if (isset($_POST['actionIdDelTransSurveyStatus']) && !empty($_POST['actionIdDelTransSurveyStatus'])) {
		$actionIdDelTransSurveyStatus = $CGetDataSurveyStatus->delDataTransSurveyStatus();
		print json_encode($actionIdDelTransSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionUploadPdf']) && !empty($_POST['actionUploadPdf'])) { 
		$actionUploadPdf = $CGetDataSurveyStatus->uploadPdfSurveyStatus($path);
		print json_encode($actionUploadPdf);
	    exit;
	}
	if (isset($_POST['actionReportCertSurveyStatus']) && !empty($_POST['actionReportCertSurveyStatus'])) {
		$actionReportCertSurveyStatus = $CGetDataSurveyStatus->getDataReportCertificate();
		print json_encode($actionReportCertSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionGenerateReportSurveyStatus']) && !empty($_POST['actionGenerateReportSurveyStatus'])) {
	    $actionGenerateReportSurveyStatus = $CGetDataSurveyStatus->generateReportSurveyStatus();
	    print json_encode($actionGenerateReportSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionReportVesselSurveyStatus']) && !empty($_POST['actionReportVesselSurveyStatus'])) {
		$actionReportVesselSurveyStatus = $CGetDataSurveyStatus->getDataReportVessel();
		print json_encode($actionReportVesselSurveyStatus);
	    exit;
	}
	if (isset($_POST['actionUploadExcelNoon']) && !empty($_POST['actionUploadExcelNoon'])) { 
		$actionUploadExcelNoon = $CUploadFile->uploadFileNoon($path);
		print json_encode($actionUploadExcelNoon);
	    exit;
	}
	if (isset($_POST['actionInsExcelNoon']) && !empty($_POST['actionInsExcelNoon'])) { 
		$typeData = $_POST['actionInsExcelNoon'];
		$actionInsExcelNoon = $CUploadFile->uploadFileNoon($path,$typeData);
		print json_encode($actionInsExcelNoon);
	    exit;
	}
	if (isset($_POST['actionGetDataUploadNoonById']) && !empty($_POST['actionGetDataUploadNoonById'])) { 
		$idGet = $_POST['actionGetDataUploadNoonById'];
		$actionGetDataUploadNoonById = $CUploadFile->getDataUploadNoon($idGet);
		print json_encode($actionGetDataUploadNoonById);
	    exit;
	}
	if (isset($_POST['actionGetSearchUploadNoon']) && !empty($_POST['actionGetSearchUploadNoon'])) { 
		$whereNya = " AND voyage_date >= '".$_POST['sDate']."' AND voyage_date <= '".$_POST['eDate']."' ";
		$actionGetSearchUploadNoon = $CUploadFile->getDataUploadNoon("",$whereNya);
		print json_encode($actionGetSearchUploadNoon);
	    exit;
	}
	if (isset($_POST['actionUploadExcelOil']) && !empty($_POST['actionUploadExcelOil'])) { 
		$actionUploadExcelOil = $CUploadFile->uploadFileOil();
		print json_encode($actionUploadExcelOil);
	    exit;
	}
	if (isset($_POST['actionInsExcelOil']) && !empty($_POST['actionInsExcelOil'])) { 
		$typeData = $_POST['actionInsExcelOil'];
		$actionInsExcelOil = $CUploadFile->uploadFileOil($typeData);
		print json_encode($actionInsExcelOil);
	    exit;
	}
	if (isset($_POST['actionGetDataUploadOilById']) && !empty($_POST['actionGetDataUploadOilById'])) { 
		$idGet = $_POST['actionGetDataUploadOilById'];
		$actionGetDataUploadOilById = $CUploadFile->getDataUploadOil($idGet);
		print json_encode($actionGetDataUploadOilById);
	    exit;
	}
	if (isset($_POST['actionGetSearchUploadOil']) && !empty($_POST['actionGetSearchUploadOil'])) { 
		$whereNya = "";
		if($_POST['vNo'] != "")
		{
			$whereNya = " AND  voyage_no  LIKE '%".$_POST['vNo']."%' ";
		}
		if($_POST['sDate'] != "" && $_POST['eDate'] != "")
		{
			$whereNya .= " AND date_upload >= '".$_POST['sDate']."' AND date_upload <= '".$_POST['eDate']."' ";
		}
		$actionGetSearchUploadOil = $CUploadFile->getDataUploadOil("",$whereNya);
		print json_encode($actionGetSearchUploadOil);
	    exit;
	}
	if (isset($_POST['actionSearchSummaryCons']) && !empty($_POST['actionSearchSummaryCons'])) { 
		$voyNo = $_POST['voyNo'];
		$actionSearchSummaryCons = $CUploadFile->getDataSummaryBUnkerConsSpeed($voyNo);
		print json_encode($actionSearchSummaryCons);
	    exit;
	}
	if (isset($_POST['actionExportBunkerConSpeed']) && !empty($_POST['actionExportBunkerConSpeed'])) { 
		$CUploadFile->getExportBunkerConsSpeed();
	    exit;
	}
	if (isset($_POST['actionPagingBunkerConsSpeed']) && !empty($_POST['actionPagingBunkerConsSpeed'])) { 
		$actionPagingBunkerConsSpeed = $CUploadFile->getDataSummaryBUnkerConsSpeed("","dataPaging");
		print json_encode($actionPagingBunkerConsSpeed);
	    exit;
	}
	if (isset($_POST['actionSearchBunkerCargoTrace']) && !empty($_POST['actionSearchBunkerCargoTrace'])) { 
		$voyNo = $_POST['voyNo'];
		$actionSearchBunkerCargoTrace = $CUploadFile->getDataSummaryCargoTrace($voyNo);
		print json_encode($actionSearchBunkerCargoTrace);
	    exit;
	}
	if (isset($_POST['actionExportBunkerCargoTrace']) && !empty($_POST['actionExportBunkerCargoTrace'])) { 
		$CUploadFile->getExportBunkerCargoTrace();
	    exit;
	}
	if (isset($_POST['actionSearchUpdJobDone']) && !empty($_POST['actionSearchUpdJobDone'])) {
		$searchNya = $_POST['searchNya'];
	    $actionSearchUpdJobDone = $CGetData->getDataJobDone($searchNya);
	    print json_encode($actionSearchUpdJobDone);
	    exit;
	}

	
?>