<?php 
require_once("../configSafir.php"); 

$idDataGet = $_GET['idData'];
$namaKapalGet = $_GET['namaKapal'];
$hdsnGet = $_GET['hdsn'];

$typeReport = $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "typereport");
$refreshDataList = "";

if($aksiGet == "giveAck")
{
	$CKoneksi->mysqlQuery("UPDATE datainfo SET signsafcom='".$userFullnm."' WHERE iddata='".$idDataGet."' AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0 LIMIT 1;");
	$CKoneksi->mysqlQuery("UPDATE datalaporan SET ack='Y', waktuack='".$CPublic->tglServer()."/".$CPublic->jamServer()."', ackby='".$userFullnm."' WHERE iddata = '".$idDataGet."' AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0 LIMIT 1;");
	$CHistory->updateLog2($userIdLogin, "GIVE ACKNOWLEDGE (iddata = <b>".$idDataGet."</b>, namakapal=<b>".$namaKapalGet."</b>, hdsn=<b>".$hdsnGet."</b>)");
	
	$refreshDataList = "parent.refreshDataList();";
}
?>

<script type="text/javascript" src="../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../js/main.js"></script>

<link href="../css/main.css" rel="stylesheet" type="text/css">
<link href="../css/table.css" rel="stylesheet" type="text/css">
<link href="../css/button.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
this.window.onload = function() {
    parent.doneWait();
    parent.document.onmousedown = parent.enableLeftClick;

    document.getElementById('loaderImg').style.visibility = "hidden";
}

window.onscroll =
    function() {
        document.getElementById('loaderImg').style.top = (document.pageYOffset ? document.pageYOffset : document.body
            .scrollTop);
    }

<?php
echo $refreshDataList;
?>
</script>

<style>
.loader {
    position: absolute;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 1;
    background: url('../picture/loading (124).gif') 50% 50% no-repeat rgb(249, 249, 249);
}
</style>
<div class="loader" id="loaderImg" style="visibility:hidden;"></div>

<body onLoad="loadScroll('dataInfo');" onUnload="saveScroll('dataInfo');">
    <?php
function gantiNilai($terganti, $mengganti, $source)
{
	// gantiNilai("", "N", $source);
	// CONTOH JIKA NILAI SOURCE KOSONG ATAU "" MAKA NILAI KEMBALI ADALAH N SEBALIKNYA JIKA NILAI SOURCE ADA MAKA NILAI KEMBALI ADALAH Y
	$nilai = $source;
	if($source == $terganti)
	{
		$nilai = $mengganti;
	}
	
	return $nilai;
}

function detilCentang($CKoneksi, $idData, $namaKapal, $hdsn, $fields)
{
	$nilai = "";
	$row = $CKoneksi->mysqlFetch( $CKoneksi->mysqlQuery("SELECT ".$fields." FROM datainfo WHERE iddata='".$idData."' AND namakapal='".$namaKapal."' AND hdsn='".$hdsn."' AND deletests=0;") );
	if( $row[$fields] == "Y" )
	{
		$nilai = "checked";
	}
	
	return $nilai;
}

function detilInfoByIdData($CKoneksi, $idData, $namaKapal, $hdsn, $fields)
{
	$row = $CKoneksi->mysqlFetch( $CKoneksi->mysqlQuery("SELECT ".$fields." FROM datainfo WHERE iddata='".$idData."' AND namakapal='".$namaKapal."' AND hdsn='".$hdsn."' AND deletests=0;") );
	
	return konversiQuotes1($row[$fields]);
}

function konversiQuotes1($string) 
{ 
	$search = array('"', "'"); 
	$replace = array("&#34;", "&#39;"); 

	return str_replace($search, $replace, $string); 
}

$noReport = konversiQuotes1( $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "noreport") );
$nmVessel = konversiQuotes1( $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "nmvessel") );
$noVoyage = konversiQuotes1( $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "novoyage") );
$dateEvent = gantiNilai("00/00/0000", "", $CPublic->convTglNonDB( $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "dateevent") ));
$nmWriter = konversiQuotes1( $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "nmwriter") );
$dateReport = gantiNilai("00/00/0000", "", $CPublic->convTglNonDB( $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "datereport") ));
$typeReport = $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "typereport");

if(($aksiGet == "pilihRow" && $typeReport == "A") || ($halamanPost == "simpanData" && $typeReport == "A"))
{
	$centangCb100 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb100");
	$centangCb110 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb110");
	$centangCb120 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb120");
	$centangCb130 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb130");
	$centangCb140 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb140");
	$centangCb150 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb150");
	$centangCb160 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb160");
	$centangCb170 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb170");
	$centangCb180 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb180");
	
	$centangCb200 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb200");
	$centangCb210 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb210");
	$centangCb220 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb220");
	$centangCb230 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb230");
	$centangCb240 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb240");
	$centangCb250 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb250");
	$centangCb260 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb260");
	$centangCb270 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb270");
	$centangCb280 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb280");
	
	$centangCb300 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb300");
	$centangCb310 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb310");
	$centangCb320 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb320");
	$isiText330 = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "text330");
	$isiText340 = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "text340");
	$centangCb350 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb350");
	$centangCb360 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb360");
	$centangCb370 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb370");
	
	$centangCb400 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb400");
	$centangCb410 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb410");
	$centangCb420 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb420");
	$centangCb430 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb430");
	$centangCb440 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb440");
	$centangCb450 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb450");
	$centangCb460 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb460");
	$centangCb470 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb470");
	$centangCb480 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb480");
	
	$centangCb700 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb700");
	$centangCb710 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb710");
	$centangCb720 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb720");
	$centangCb730 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb730");
	$centangCb740 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb740");
	$isiNoticeGiven = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "noticegiven");
	
	$isiDescribeHappen = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "describehappen");
	$isiProbableCaused = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "probablecaused");
	$isiImmediateCorr = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "immediatecorr");
	$isiCorrAction = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "corraction");
	$isiPreventive = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "preventive");
	$proposalDate = gantiNilai("00/00/0000", "", $CPublic->convTglNonDB( detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "proposaldate") ));
	$isiPic = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "pic");
	
	$isiSignPlace = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signplace");
	$isiSignDate = gantiNilai("00/00/0000", "", $CPublic->convTglNonDB( detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signdate") ));
	$isiSignMaster = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signmaster");
	$isiSignChef = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signchef");
	$isiSignSafCom = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signsafcom");
?>
    <table cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
            <td height="5"></td>
        </tr>

        <tr>
            <td align="center">
                <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                    <tr>
                        <td height="200">
                            <fieldset>
                                <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                    &nbsp;&nbsp;&nbsp;GENERAL INFORMATION&nbsp;&nbsp;&nbsp;</legend>
                                <table width="100%" height="100%" bgcolor="#F0FFF0" border="0" class="">

                                    <tr valign="top">
                                        <td width="18" class="boldPersonal"><b>000</b></td>
                                        <td width="114" class="boldPersonal">Report no.</td>
                                        <td width="310">
                                            <input class="styeInputText" type="text" size="21" maxlength="20"
                                                name="noReport" id="noReport" title="Report number (Max 20 character)"
                                                value="<?php echo $noReport; ?>" readonly />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>001</b></td>
                                        <td class="boldPersonal">Name of Vessel</td>
                                        <td><input class="styeInputText" type="text" size="45" maxlength="40"
                                                name="nmVessel" id="nmVessel" title="Name of Vessel (Max 40 character)"
                                                value="<?php echo $nmVessel; ?>" readonly /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>002</b></td>
                                        <td class="boldPersonal">Voyage No.</td>
                                        <td><input class="styeInputText" type="text" size="21" maxlength="20"
                                                name="noVoyage" id="noVoyage" title="Voyage number (Max 20 character)"
                                                value="<?php echo $noVoyage; ?>" readonly /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>003</b></td>
                                        <td class="boldPersonal">Date of event</td>
                                        <td><input class="styeInputText" type="text" style="width:70px;" maxlength="10"
                                                name="dateEvent" id="dateEvent" value="<?php echo $dateEvent; ?>"
                                                readonly /> &nbsp;<span style="font:0.7em sans-serif;color:#333333;"
                                                readonly>(dd/mm/yyyy)</span></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>004</b></td>
                                        <td class="boldPersonal">Writer's name and position</td>
                                        <td><input class="styeInputText" type="text" size="45" maxlength="40"
                                                name="nmWriter" id="nmWriter" / title="Writer's name (Max 40 character)"
                                                value="<?php echo $nmWriter; ?>" readonly></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>005</b></td>
                                        <td class="boldPersonal">Date of report</td>
                                        <td><input class="styeInputText" type="text" style="width:70px;" maxlength="10"
                                                name="dateReport" id="dateReport" value="<?php echo $dateReport; ?>"
                                                readonly /> &nbsp;<span
                                                style="font:0.7em sans-serif;color:#333333;">(dd/mm/yyyy)</span></td>
                                    </tr>
                                    <tr valign="top">
                                        <td>&nbsp;</td>
                                        <td class="boldPersonal">Type of Report</td>
                                        <td height="20" valign="top"><img src="../picture/arrow-turn-270.png"></td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td height="10"></td>
        </tr>

        <tr>
            <td align="center" height="25">
                <table cellpadding="0" cellspacing="0" width="190" height="100%"
                    style="background-color:#F9F9F9;font-family:Arial;font-size:14px;font-weight:bold;color:#333;">
                    <tr align="center">
                        <td class="tabelBorderAll"> A - VERSION ACCIDENT </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="5"></td>
        </tr>
        <tr>
            <td height="35">
                <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                    <tr>
                        <td align="center"
                            style="color:#555;font-family:Arial;font-size:12px;text-decoration:underline;">
                            <b>DESCRIPTION 1/2 : </b><i>KEY INFORMATION DEPENDING ON TYPE OF REPORT (DETAILED
                                DESCRIPTION OVERLEAF)</i>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="5"></td>
        </tr>
        <form method="post" action="halDataInfo.php?idData=<?php echo $idDataGet; ?>" enctype="multipart/form-data"
            id="formDataInfo" name="formDataInfo">
            <tr>
                <td align="center" height="95">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsg100"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; EVENT &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20" class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb100" name="cb100"
                                                    value="Y" <?php echo $centangCb100; ?>>
                                            </td>
                                            <td width="425" class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb100');"><span><b>100</b> Collision</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb110" name="cb110"
                                                    value="Y" <?php echo $centangCb110; ?>>
                                            </td>
                                            <td class=" tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb110');"><span><b>110</b> Grounding</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb120" name="cb120"
                                                    value="Y" <?php echo $centangCb120; ?>>
                                            </td>
                                            <td class=" tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb120');"><span><b>120</b> Fire / Explosion</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb130" name="cb130"
                                                    value="Y" <?php echo $centangCb130; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb130');"><span><b>130</b> Weather change</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust ">
                                                <input type="checkbox" class="styleCekBox" id="cb140" name="cb140"
                                                    value="Y" <?php echo $centangCb140; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb140');"><span><b>140</b> Machinery
                                                    break-down</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb150" name="cb150"
                                                    value="Y" <?php echo $centangCb150; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb150');"><span><b>150</b> Loss of stability /
                                                    flooding</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb160" name="cb160"
                                                    value="Y" <?php echo $centangCb160; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb160');"><span><b>160</b> Contact damages</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb170" name="cb170"
                                                    value="Y" <?php echo $centangCb170; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb170');"><span><b>170</b> Harm to people</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb180" name="cb180"
                                                    value="Y" <?php echo $centangCb180; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb180');"><span><b>180</b> Other</span> </td>
                                        </tr>
                                        <!--<tr><td height="20"></td></tr>-->
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsg200"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; PROBABLE CAUSE &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20" class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb200" name="cb200"
                                                    value="Y" <?php echo $centangCb200; ?>>
                                            </td>
                                            <td width="425" class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb200');"><span><b>200</b> Weather</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb210" name="cb210"
                                                    value="Y" <?php echo $centangCb210; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb210');"><span><b>210</b> Tech. / ops. conditions
                                                    override the control of the ship</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb220" name="cb220"
                                                    value="Y" <?php echo $centangCb220; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb220');"><span><b>220</b> Construction / equipment
                                                    / materials</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb230" name="cb230"
                                                    value="Y" <?php echo $centangCb230; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb230');"><span><b>230</b> Design / arrangement /
                                                    function</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb240" name="cb240"
                                                    value="Y" <?php echo $centangCb240; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb240');"><span><b>240</b> Cargo / bunkers</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb250" name="cb250"
                                                    value="Y" <?php echo $centangCb250; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb250');"><span><b>250</b> Producers and
                                                    routine</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb260" name="cb260"
                                                    value="Y" <?php echo $centangCb260; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb260');"><span><b>260</b> Working schedules</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb270" name="cb270"
                                                    value="Y" <?php echo $centangCb270; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb270');"><span><b>270</b> Education / training /
                                                    motivation</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb280" name="cb280"
                                                    value="Y" <?php echo $centangCb280; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb280');"><span><b>280</b> Other</span> </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsg300"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; CONSEQUENCES IN TERMS OF HARM TO PEOPLE. IF ANY &nbsp;&nbsp;
                                    </legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20" class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb300" name="cb300"
                                                    value="Y" <?php echo $centangCb300; ?>>
                                            </td>
                                            <td width="425" class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb300');"><span><b>300</b> Death casualty</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb310" name="cb310"
                                                    value="Y" <?php echo $centangCb310; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb310');"><span><b>310</b> Personal Injury</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb320" name="cb320"
                                                    value="Y" <?php echo $centangCb320; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb320');"><span><b>320</b> Occupational disease /
                                                    illness</span> </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="boldPersonal"><b>330</b> Name of harmed person</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" maxlength="100" name="text330"
                                                    id="text330" title="Masters signature (Max 100 character)"
                                                    style="width:372px;" value="<?php echo $isiText330; ?>"
                                                    readonly />&nbsp;
                                                <!--<input style="width:35px;height:20px;" type="text" name="sisaText330" value="100" readonly disabled>-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="boldPersonal"><b>340</b> Job position</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" maxlength="100" name="text340"
                                                    id="text340" title="Masters signature (Max 100 character)"
                                                    style="width:372px;" value="<?php echo $isiText340; ?>"
                                                    readonly />&nbsp;
                                                <!--<input style="width:35px;height:20px;" type="text" name="sisaText340" value="100" readonly disabled>-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb350" name="cb350"
                                                    value="Y" <?php echo $centangCb350; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb350');"><span><b>350</b> Doctor's report filled
                                                    in</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb360" name="cb360"
                                                    value="Y" <?php echo $centangCb360; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb360');"><span><b>360</b> RTV report filled
                                                    in</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb370" name="cb370"
                                                    value="Y" <?php echo $centangCb370; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb370');"><span><b>370</b> Other reports, if
                                                    any</span> </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsg400"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend class="" style="height:30px;">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                            style="position:absolute;left:26px; height:30px;width:410px; font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;text-align:justify;">
                                            CONSEQUENCES IN TERMS OF DAMAGE TO PROPERTY, ENVIRONMENT OR LOSS TO
                                            OPERATION </span></legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20" class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb400" name="cb400"
                                                    value="Y" <?php echo $centangCb400; ?>>
                                            </td>
                                            <td width="425" class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb400');"><span><b>400</b> Hull</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb410" name="cb410"
                                                    value="Y" <?php echo $centangCb410; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb410');"><span><b>410</b> Machinery</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb420" name="cb420"
                                                    value="Y" <?php echo $centangCb420; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb420');"><span><b>420</b> Equipment</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb430" name="cb430"
                                                    value="Y" <?php echo $centangCb430; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb430');"><span><b>430</b> Cargo</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb440" name="cb440"
                                                    value="Y" <?php echo $centangCb440; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb440');"><span><b>440</b> Third party</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb450" name="cb450"
                                                    value="Y" <?php echo $centangCb450; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb450');"><span><b>450</b> Marine
                                                    environment</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb460" name="cb460"
                                                    value="Y" <?php echo $centangCb460; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb460');"><span><b>460</b> Air</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb470" name="cb470"
                                                    value="Y" <?php echo $centangCb470; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb470');"><span><b>470</b> Loss of time</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb480" name="cb480"
                                                    value="Y" <?php echo $centangCb480; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb480');"><span><b>480</b> Other</span> </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsg700"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; NOTICE GIVEN, IF ANY &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20" class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb700" name="cb700"
                                                    value="Y" <?php echo $centangCb700; ?>>
                                            </td>
                                            <td width="425" class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb700');"><span><b>700</b> Flag state</span> </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="styleCekBox" id="cb710" name="cb710"
                                                    value="Y" <?php echo $centangCb710; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb710');"><span><b>710</b> Port state</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb720" name="cb720"
                                                    value="Y" <?php echo $centangCb720; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb720');"><span><b>720</b> Class</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb730" name="cb730"
                                                    value="Y" <?php echo $centangCb730; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb730');"><span><b>730</b> Charterer</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb740" name="cb740"
                                                    value="Y" <?php echo $centangCb740; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb740');"><span><b>740</b> Others</span> </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="boldPersonal">NOTICE GIVEN, IF ANY</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" maxlength="50"
                                                    name="noticeGiven" id="noticeGiven" title="Notice given, If any"
                                                    style="width:372px;" value="<?php echo $isiNoticeGiven; ?>"
                                                    readonly />&nbsp;
                                                <!--<input style="width:35px;height:20px;" type="text" name="sisaNoticeGiven" value="50" readonly disabled>-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>

            <tr>
                <td height="35">
                    <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                        <tr>
                            <td align="center"
                                style="color:#555;font-family:Arial;font-size:12px;text-decoration:underline;">
                                <b>DESCRIPTION 2/2 : </b><i>DETAILED INFORMATION</i>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; GENERAL GUIDELINES &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" height="50" style="text-align:justify;">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This page is to be filled in on board for
                                                all reports versions. The Description should be as short and specific as
                                                possible. If you need more space for your description , please continue
                                                on separate paper.</td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsgDescribeHappen"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; DESCRIBE WHAT HAPPEN &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425"><textarea class="styeInputText" id="describeHappen"
                                                    name="describeHappen" rows="5" cols="70"
                                                    readonly><?php echo $isiDescribeHappen; ?></textarea>&nbsp;
                                                <!--<input style="width:35px;height:20px;" type="text" name="sisaDescribeHappen" value="1300" readonly disabled>-->
                                            </td>
                                        </tr>
                                        <?php
						$htmlPaper = "";
						$jmlPaperAktif = $CData->jmlPaperAktif($idDataGet, $namaKapalGet, $hdsnGet);
						$htmlPaper.= "" 
						?>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" style="width:30px;"
                                                    maxlength="1" name="jmlMorePaper" id="jmlMorePaper"
                                                    value="<?php echo $jmlPaperAktif ; ?>" />&nbsp;<span
                                                    class="boldPersonalAbu"> More Paper </span>
                                                <input type="hidden" id="jmlPaperLama"
                                                    value="<?php echo $jmlPaperAktif ; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td id="idHalMorePaper">
                                                <?php
							$jmlPaperAktif = $CData->jmlPaperAktif($idDataGet, $namaKapalGet, $hdsnGet);
							for($a=1; $a<=$jmlPaperAktif; $a++)
							{
						?>
                                                <span class="boldPersonal" style="color:#A60000;"> Paper
                                                    <?php echo $a; ?> </span><br />
                                                <textarea class="styeInputText" rows="5" cols="70"
                                                    id="paper<?php echo $a; ?>" name="paper<?php echo $a; ?>"
                                                    readonly><?php echo $CData->detilPaper($idDataGet, $namaKapalGet, $hdsnGet, $a, "isipaper"); ?></textarea>&nbsp;<br>

                                                <?php
							}
						?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <?php "";
						echo $htmlPaper;
						?>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsgDescribeProbable"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; DESCRIBE PROBABLE CAUSED &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425"> <textarea class="styeInputText" rows="5" cols="70"
                                                    id="probableCaused" name="probableCaused"
                                                    readonly><?php echo $isiProbableCaused; ?></textarea>&nbsp;
                                                <!--<input style="width:35px;height:20px;" type="text" name="sisaProbableCaused" value="1300" readonly disabled></td>-->
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend class="" style="height:30px;">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                            style="position:absolute;left:26px; height:30px;width:410px; font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;text-align:justify;">
                                            DESCRIBE IMMEDIATE EXECUTED CORRECTIVE ACTION, (IF ANY) AND FURTHER
                                            RECOMENDED CORRECTIVE ACTION AND PREVENTIVE ACTION </span></legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <!--<tr><td height="5"></td></tr>
                        <tr>
                        	<td width="20">&nbsp;</td>
                            <td width="425">
                            	<textarea class="styeInputText" rows="5" cols="70" id="describeImmediate" name="paper1" onKeyUp="textCounter(this, sisaDescribeImmediate, 3000);"></textarea>&nbsp;<input style="width:35px;" type="text" name="sisaDescribeImmediate" value="3000" readonly disabled></td>
                        </tr>
                        <tr><td height="5"></td></tr>
                        -->
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Immediate corrective action </td>
                                        </tr>
                                        <tr valign="top">
                                            <td>&nbsp;</td>
                                            <td>
                                                <textarea class="styeInputText" rows="5" cols="70" id="immediateCorr"
                                                    name="immediateCorr"
                                                    readonly><?php echo $isiImmediateCorr; ?></textarea>&nbsp;
                                                <!--<input style="width:35px;height:20px;" type="text" name="sisaImmediateCorr" value="250" readonly disabled>-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Corrective action </td>
                                        </tr>
                                        <tr valign="top">
                                            <td>&nbsp;</td>
                                            <td>
                                                <textarea class="styeInputText" rows="5" cols="70" id="corrAction"
                                                    name="corrAction"
                                                    readonly><?php echo $isiCorrAction; ?></textarea>&nbsp;
                                                <!--<input style="width:35px;height:20px;" type="text" name="sisaCorrAction" value="250" readonly disabled>-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Preventive action </td>
                                        </tr>
                                        <tr valign="top">
                                            <td>&nbsp;</td>
                                            <td>
                                                <textarea class="styeInputText" rows="5" cols="70" id="preventive"
                                                    name="preventive"
                                                    readonly><?php echo $isiPreventive; ?></textarea>&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Corrective & Preventive Action
                                                Proposal Completion Date </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" style="width:70px;"
                                                    maxlength="10" name="proposalDate" id="proposalDate" /
                                                    title="Corrective & Preventive Action Proposal Completion Date"
                                                    value="<?php echo $proposalDate; ?>" readonly> &nbsp;
                                                <!--<img src="../picture/calendar.gif" style="cursor: pointer; border: 1px solid red;" title="Select Date" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="displayCalendar(document.getElementById('proposalDate'),'dd/mm/yyyy',this, '', '', '-16', '1')"/>-->
                                                <span style="font:0.7em sans-serif;color:#333333;">(dd/mm/yyyy)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> PIC </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" size="33" maxlength="30"
                                                    title="PIC (Max 30 Character)" name="pic" id="pic"
                                                    value="<?php echo $isiPic; ?>" readonly />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; SIGNATURE INFORMATION &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="130" class="boldPersonal"> Place and date </td>
                                            <td width="315"><input class="styeInputText" type="text" size="20"
                                                    name="signPlace" id="signPlace"
                                                    title="Place signature (Max 20 Character)"
                                                    value="<?php echo $isiSignPlace; ?>" readonly />
                                                <input class="styeInputText" type="text" size="8" maxlength="10"
                                                    name="signDate" id="signDate" title="Data signature"
                                                    value="<?php echo $isiSignDate; ?>" readonly />&nbsp;
                                                <span style="font:0.7em sans-serif;color:#333333;">(dd/mm/yyyy)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" title="Masters signature"> Masters </td>
                                            <td><input class="styeInputText" type="text" size="35" name="signMaster"
                                                    id="signMaster" title="Masters signature (Max 25 character)"
                                                    value="<?php echo $isiSignMaster; ?>" readonly /></td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" title="Ch.off / Ch.eng's signature"> Ch.off /
                                                Ch.eng's </td>
                                            <td><input class="styeInputText" type="text" size="35" name="signChef"
                                                    id="signChef" title="Ch.off / Ch.eng's signature (Max 25 character)"
                                                    value="<?php echo $isiSignChef; ?>" readonly /></td>
                                        </tr>
                                        <tr>
                                            <!--<td class="boldPersonal" title="Safety Com. rep's signature"> Safety Com. rep's </td>
                            <td><input class="styeInputText" type="text" size="35" name="signSafCom" id="signSafCom" title="Safety Com. rep's signature (Max 25 character)" style="font-weight:bold;" value="<?php echo $isiSignSafCom; ?>"/></td>
                            <td class="boldPersonal" height="20" title="Safety Com. rep's signature"> Safety Com. rep's </td>
                            <td class="styeInputText">&nbsp;<?php echo $isiSignSafCom; ?></td>-->
                                            <td class="boldPersonal" height="20" title="Safety Com. rep's signature">
                                                Safety Com. rep's </td>
                                            <td class="styeInputText"><b><?php echo $isiSignSafCom; ?></b>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <input type="hidden" id="halaman" name="halaman" value="simpanData">
        </form>
    </table>
    <?php
}
else if(($aksiGet == "pilihRow" && $typeReport == "B") || ($halamanPost == "simpanData" && $typeReport == "B"))
{
	$centangCb100 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb100");
	$centangCb110 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb110");
	$centangCb120 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb120");
	$centangCb130 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb130");
	$centangCb140 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb140");
	$centangCb150 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb150");
	$centangCb160 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb160");
	$centangCb170 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb170");
	$centangCb180 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb180");
	
	$centangCb200 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb200");
	$centangCb210 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb210");
	$centangCb220 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb220");
	$centangCb230 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb230");
	$centangCb240 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb240");
	$centangCb250 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb250");
	$centangCb260 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb260");
	$centangCb270 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb270");
	$centangCb280 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb280");
	
	$centangCb700 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb700");
	$centangCb710 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb710");
	$centangCb720 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb720");
	$centangCb730 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb730");
	$centangCb740 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb740");
	$isiNoticeGiven = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "noticegiven");
	
	$isiDescribeHappen = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "describehappen");
	$isiProbableCaused = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "probablecaused");
	$isiImmediateCorr = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "immediatecorr");
	$isiCorrAction = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "corraction");
	$isiPreventive = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "preventive");
	$proposalDate = gantiNilai("00/00/0000", "", $CPublic->convTglNonDB( detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "proposaldate") ));
	$isiPic = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "pic");
	
	$isiSignPlace = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signplace");
	$isiSignDate = gantiNilai("00/00/0000", "", $CPublic->convTglNonDB( detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signdate") ));
	$isiSignMaster = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signmaster");
	$isiSignChef = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signchef");
	$isiSignSafCom = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signsafcom");
?>
    <table cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
            <td height="5"></td>
        </tr>

        <tr>
            <td align="center">
                <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                    <tr>
                        <td height="200">
                            <fieldset>
                                <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                    &nbsp;&nbsp;&nbsp;GENERAL INFORMATION&nbsp;&nbsp;&nbsp;</legend>
                                <table width="100%" height="100%" bgcolor="#F0FFF0" border="0" class="">

                                    <tr valign="top">
                                        <td width="18" class="boldPersonal"><b>000</b></td>
                                        <td width="114" class="boldPersonal">Report no.</td>
                                        <td width="310">
                                            <input class="styeInputText" type="text" size="21" maxlength="20"
                                                name="noReport" id="noReport" title="Report number (Max 20 character)"
                                                value="<?php echo $noReport; ?>" readonly />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>001</b></td>
                                        <td class="boldPersonal">Name of Vessel</td>
                                        <td><input class="styeInputText" type="text" size="45" maxlength="40"
                                                name="nmVessel" id="nmVessel" title="Name of Vessel (Max 40 character)"
                                                value="<?php echo $nmVessel; ?>" readonly /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>002</b></td>
                                        <td class="boldPersonal">Voyage No.</td>
                                        <td><input class="styeInputText" type="text" size="21" maxlength="20"
                                                name="noVoyage" id="noVoyage" title="Voyage number (Max 20 character)"
                                                value="<?php echo $noVoyage; ?>" readonly /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>003</b></td>
                                        <td class="boldPersonal">Date of event</td>
                                        <td><input class="styeInputText" type="text" style="width:70px;" maxlength="10"
                                                name="dateEvent" id="dateEvent" value="<?php echo $dateEvent; ?>" />
                                            &nbsp;<span style="font:0.7em sans-serif;color:#333333;"
                                                readonly>(dd/mm/yyyy)</span></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>004</b></td>
                                        <td class="boldPersonal">Writer's name and position</td>
                                        <td><input class="styeInputText" type="text" size="45" maxlength="40"
                                                name="nmWriter" id="nmWriter" / title="Writer's name (Max 40 character)"
                                                value="<?php echo $nmWriter; ?>" readonly></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>005</b></td>
                                        <td class="boldPersonal">Date of report</td>
                                        <td><input class="styeInputText" type="text" style="width:70px;" maxlength="10"
                                                name="dateReport" id="dateReport" value="<?php echo $dateReport; ?>"
                                                readonly /> &nbsp;<span
                                                style="font:0.7em sans-serif;color:#333333;">(dd/mm/yyyy)</span></td>
                                    </tr>
                                    <tr valign="top">
                                        <td>&nbsp;</td>
                                        <td class="boldPersonal">Type of Report</td>
                                        <td height="20" valign="top"><img src="../picture/arrow-turn-270.png"></td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td height="10"></td>
        </tr>
        <tr>
            <td align="center" height="25">
                <table cellpadding="0" cellspacing="0" width="230" height="100%"
                    style="background-color:#F8F8F8;font-family:Arial;font-size:14px;font-weight:bold;color:#333;">
                    <tr align="center">
                        <td class="tabelBorderAll">B - VERSION NEAR ACCIDENT </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="5"></td>
        </tr>
        <tr>
            <td align="center" height="35">
                <table cellpadding="0" cellspacing="0" width="98%" height="100%">
                    <tr>
                        <td align="left" style="color:#555;font-family:Arial;font-size:12px;text-decoration:underline;">
                            <b>DESCRIPTION 1/2 : </b><i>KEY INFORMATION DEPENDING ON TYPE OF REPORT (DETAILED
                                DESCRIPTION OVERLEAF)</i>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="5"></td>
        </tr>
        <form method="post" action="halDataInfo.php?idData=<?php echo $idDataGet; ?>" enctype="multipart/form-data"
            id="formDataInfo" name="formDataInfo">
            <tr>
                <td align="center" height="95">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsg100"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; EVENT &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20" class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb100" name="cb100"
                                                    value="Y" <?php echo $centangCb100; ?>>
                                            </td>
                                            <td width="425" class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb100');"><span><b>100</b> Collision</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb110" name="cb110"
                                                    value="Y" <?php echo $centangCb110; ?>>
                                            </td>
                                            <td class=" tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb110');"><span><b>110</b> Grounding</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb120" name="cb120"
                                                    value="Y" <?php echo $centangCb120; ?>>
                                            </td>
                                            <td class=" tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb120');"><span><b>120</b> Fire / Explosion</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb130" name="cb130"
                                                    value="Y" <?php echo $centangCb130; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb130');"><span><b>130</b> Weather change</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust ">
                                                <input type="checkbox" class="styleCekBox" id="cb140" name="cb140"
                                                    value="Y" <?php echo $centangCb140; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb140');"><span><b>140</b> Machinery
                                                    break-down</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb150" name="cb150"
                                                    value="Y" <?php echo $centangCb150; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb150');"><span><b>150</b> Loss of stability /
                                                    flooding</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb160" name="cb160"
                                                    value="Y" <?php echo $centangCb160; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb160');"><span><b>160</b> Contact damages</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb170" name="cb170"
                                                    value="Y" <?php echo $centangCb170; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb170');"><span><b>170</b> Harm to people</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb180" name="cb180"
                                                    value="Y" <?php echo $centangCb180; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb180');"><span><b>180</b> Other</span> </td>
                                        </tr>
                                        <!--<tr><td height="20"></td></tr>-->
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsg200"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; PROBABLE CAUSE &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20" class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb200" name="cb200"
                                                    value="Y" <?php echo $centangCb200; ?>>
                                            </td>
                                            <td width="425" class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb200');"><span><b>200</b> Weather</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb210" name="cb210"
                                                    value="Y" <?php echo $centangCb210; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb210');"><span><b>210</b> Tech. / ops. conditions
                                                    override the control of the ship</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb220" name="cb220"
                                                    value="Y" <?php echo $centangCb220; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb220');"><span><b>220</b> Construction / equipment
                                                    / materials</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb230" name="cb230"
                                                    value="Y" <?php echo $centangCb230; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb230');"><span><b>230</b> Design / arrangement /
                                                    function</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb240" name="cb240"
                                                    value="Y" <?php echo $centangCb240; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb240');"><span><b>240</b> Cargo / bunkers</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb250" name="cb250"
                                                    value="Y" <?php echo $centangCb250; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb250');"><span><b>250</b> Producers and
                                                    routine</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb260" name="cb260"
                                                    value="Y" <?php echo $centangCb260; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb260');"><span><b>260</b> Working schedules</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb270" name="cb270"
                                                    value="Y" <?php echo $centangCb270; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb270');"><span><b>270</b> Education / training /
                                                    motivation</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb280" name="cb280"
                                                    value="Y" <?php echo $centangCb280; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb280');"><span><b>280</b> Other</span> </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsg700"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; NOTICE GIVEN, IF ANY &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20" class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb700" name="cb700"
                                                    value="Y" <?php echo $centangCb700; ?>>
                                            </td>
                                            <td width="425" class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb700');"><span><b>700</b> Flag state</span> </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="styleCekBox" id="cb710" name="cb710"
                                                    value="Y" <?php echo $centangCb710; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb710');"><span><b>710</b> Port state</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb720" name="cb720"
                                                    value="Y" <?php echo $centangCb720; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb720');"><span><b>720</b> Class</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb730" name="cb730"
                                                    value="Y" <?php echo $centangCb730; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb730');"><span><b>730</b> Charterer</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb740" name="cb740"
                                                    value="Y" <?php echo $centangCb740; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb740');"><span><b>740</b> Others</span> </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="boldPersonal">NOTICE GIVEN, IF ANY</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" maxlength="50"
                                                    name="noticeGiven" id="noticeGiven" title="Notice given, If any"
                                                    style="width:372px;" value="<?php echo $isiNoticeGiven; ?>"
                                                    readonly />&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>

            <tr>
                <td height="35">
                    <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                        <tr>
                            <td align="center"
                                style="color:#555;font-family:Arial;font-size:12px;text-decoration:underline;">
                                <b>DESCRIPTION 2/2 : </b><i>DETAILED INFORMATION</i>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; GENERAL GUIDELINES &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" height="50" style="text-align:justify;">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This page is to be filled in on board for
                                                all reports versions. The Description should be as short and specific as
                                                possible. If you need more space for your description , please continue
                                                on separate paper.</td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsgDescribeHappen"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; DESCRIBE WHAT HAPPEN &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425"><textarea class="styeInputText" id="describeHappen"
                                                    name="describeHappen" rows="5" cols="70"
                                                    readonly><?php echo $isiDescribeHappen; ?></textarea>&nbsp;</td>
                                        </tr>
                                        <?php
						$htmlPaper = "";
						
						$jmlPaperAktif = $CData->jmlPaperAktif($idDataGet, $namaKapalGet, $hdsnGet);

						$htmlPaper.= "" 
						?>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" style="width:30px;"
                                                    maxlength="1" name="jmlMorePaper" id="jmlMorePaper"
                                                    value="<?php echo $jmlPaperAktif ; ?>" />&nbsp;<span
                                                    class="boldPersonalAbu"> More Paper </span>
                                                <input type="hidden" id="jmlPaperLama"
                                                    value="<?php echo $jmlPaperAktif ; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td id="idHalMorePaper">
                                                <?php
							for($a=1; $a<=$jmlPaperAktif; $a++)
							{
						?>
                                                <span class="boldPersonal" style="color:#A60000;"> Paper
                                                    <?php echo $a; ?> </span><br />
                                                <textarea class="styeInputText" rows="5" cols="70"
                                                    id="paper<?php echo $a; ?>" name="paper<?php echo $a; ?>"
                                                    readonly><?php echo $CData->detilPaper($idDataGet, $namaKapalGet, $hdsnGet, $a, "isipaper"); ?></textarea>&nbsp;<br>

                                                <?php
							}
						?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <?php "";
						echo $htmlPaper;
						?>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsgDescribeProbable"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; DESCRIBE PROBABLE CAUSED &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425"> <textarea class="styeInputText" rows="5" cols="70"
                                                    id="probableCaused" name="probableCaused"
                                                    readonly><?php echo $isiProbableCaused; ?></textarea>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend class="" style="height:30px;">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                            style="position:absolute;left:26px; height:30px;width:410px; font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;text-align:justify;">
                                            DESCRIBE IMMEDIATE EXECUTED CORRECTIVE ACTION, (IF ANY) AND FURTHER
                                            RECOMENDED CORRECTIVE ACTION AND PREVENTIVE ACTION </span></legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <!--<tr><td height="5"></td></tr>
                        <tr>
                        	<td width="20">&nbsp;</td>
                            <td width="425">
                            	<textarea class="styeInputText" rows="5" cols="70" id="describeImmediate" name="paper1" onKeyUp="textCounter(this, sisaDescribeImmediate, 3000);"></textarea>&nbsp;<input style="width:35px;" type="text" name="sisaDescribeImmediate" value="3000" readonly disabled></td>
                        </tr>
                        <tr><td height="5"></td></tr>
                        -->
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Immediate corrective action </td>
                                        </tr>
                                        <tr valign="top">
                                            <td>&nbsp;</td>
                                            <td>
                                                <textarea class="styeInputText" rows="5" cols="70" id="immediateCorr"
                                                    name="immediateCorr"
                                                    readonly><?php echo $isiImmediateCorr; ?></textarea>&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Corrective action </td>
                                        </tr>
                                        <tr valign="top">
                                            <td>&nbsp;</td>
                                            <td>
                                                <textarea class="styeInputText" rows="5" cols="70" id="corrAction"
                                                    name="corrAction"
                                                    readonly><?php echo $isiCorrAction; ?></textarea>&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Preventive action </td>
                                        </tr>
                                        <tr valign="top">
                                            <td>&nbsp;</td>
                                            <td>
                                                <textarea class="styeInputText" rows="5" cols="70" id="preventive"
                                                    name="preventive"
                                                    readonly><?php echo $isiPreventive; ?></textarea>&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Corrective & Preventive Action
                                                Proposal Completion Date </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" style="width:70px;"
                                                    maxlength="10" name="proposalDate" id="proposalDate" /
                                                    title="Corrective & Preventive Action Proposal Completion Date"
                                                    value="<?php echo $proposalDate; ?>"> &nbsp;
                                                <span style="font:0.7em sans-serif;color:#333333;">(dd/mm/yyyy)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> PIC </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" size="33" maxlength="30"
                                                    title="PIC (Max 30 Character)" name="pic" id="pic"
                                                    value="<?php echo $isiPic; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; SIGNATURE INFORMATION &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="130" class="boldPersonal"> Place and date </td>
                                            <td width="315"><input class="styeInputText" type="text" size="20"
                                                    name="signPlace" id="signPlace"
                                                    title="Place signature (Max 20 Character)"
                                                    value="<?php echo $isiSignPlace; ?>" readonly />
                                                <input class="styeInputText" type="text" size="8" maxlength="10"
                                                    name="signDate" id="signDate" title="Data signature"
                                                    value="<?php echo $isiSignDate; ?>" readonly />&nbsp;
                                                <span style="font:0.7em sans-serif;color:#333333;">(dd/mm/yyyy)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" title="Masters signature"> Masters </td>
                                            <td><input class="styeInputText" type="text" size="35" name="signMaster"
                                                    id="signMaster" title="Masters signature (Max 25 character)"
                                                    value="<?php echo $isiSignMaster; ?>" readonly /></td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" title="Ch.off / Ch.eng's signature"> Ch.off /
                                                Ch.eng's </td>
                                            <td><input class="styeInputText" type="text" size="35" name="signChef"
                                                    id="signChef" title="Ch.off / Ch.eng's signature (Max 25 character)"
                                                    value="<?php echo $isiSignChef; ?>" readonly /></td>
                                        </tr>
                                        <tr>
                                            <!--<td class="boldPersonal" title="Safety Com. rep's signature"> Safety Com. rep's </td>
                            <td><input class="styeInputText" type="text" size="35" name="signSafCom" id="signSafCom" title="Safety Com. rep's signature (Max 25 character)" style="font-weight:bold;" value="<?php echo $isiSignSafCom; ?>"/>
                            </td>-->
                                            <td class="boldPersonal" height="20" title="Safety Com. rep's signature">
                                                Safety Com. rep's </td>
                                            <td class="styeInputText"><b><?php echo $isiSignSafCom; ?></b>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <input type="hidden" id="halaman" name="halaman" value="simpanData">
        </form>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <?php	
}
else if(($aksiGet == "pilihRow" && $typeReport == "C") || ($halamanPost == "simpanData" && $typeReport == "C"))
{
	$centangCb500 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb500");
	$centangCb510 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb510");
	$centangCb520 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb520");
	$centangCb530 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb530");
	$centangCb540 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb540");
	$centangCb550 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb550");
	
	$centangCb700 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb700");
	$centangCb710 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb710");
	$centangCb720 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb720");
	$centangCb730 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb730");
	$centangCb740 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb740");
	$isiNoticeGiven = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "noticegiven");
	
	$isiDescribeHappen = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "describehappen");
	$isiProbableCaused = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "probablecaused");
	$isiImmediateCorr = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "immediatecorr");
	$isiCorrAction = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "corraction");
	$isiPreventive = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "preventive");
	$proposalDate = gantiNilai("00/00/0000", "", $CPublic->convTglNonDB( detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "proposaldate") ));
	$isiPic = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "pic");
	
	$isiSignPlace = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signplace");
	$isiSignDate = gantiNilai("00/00/0000", "", $CPublic->convTglNonDB( detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signdate") ));
	$isiSignMaster = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signmaster");
	$isiSignChef = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signchef");
	$isiSignSafCom = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signsafcom");
?>
    <table cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
            <td height="5"></td>
        </tr>

        <tr>
            <td align="center">
                <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                    <tr>
                        <td height="200">
                            <fieldset>
                                <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                    &nbsp;&nbsp;&nbsp;GENERAL INFORMATION&nbsp;&nbsp;&nbsp;</legend>
                                <table width="100%" height="100%" bgcolor="#F0FFF0" border="0" class="">

                                    <tr valign="top">
                                        <td width="18" class="boldPersonal"><b>000</b></td>
                                        <td width="114" class="boldPersonal">Report no.</td>
                                        <td width="310">
                                            <input class="styeInputText" type="text" size="21" maxlength="20"
                                                name="noReport" id="noReport" title="Report number (Max 20 character)"
                                                value="<?php echo $noReport; ?>" readonly />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>001</b></td>
                                        <td class="boldPersonal">Name of Vessel</td>
                                        <td><input class="styeInputText" type="text" size="45" maxlength="40"
                                                name="nmVessel" id="nmVessel" title="Name of Vessel (Max 40 character)"
                                                value="<?php echo $nmVessel; ?>" readonly /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>002</b></td>
                                        <td class="boldPersonal">Voyage No.</td>
                                        <td><input class="styeInputText" type="text" size="21" maxlength="20"
                                                name="noVoyage" id="noVoyage" title="Voyage number (Max 20 character)"
                                                value="<?php echo $noVoyage; ?>" readonly /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>003</b></td>
                                        <td class="boldPersonal">Date of event</td>
                                        <td><input class="styeInputText" type="text" style="width:70px;" maxlength="10"
                                                name="dateEvent" id="dateEvent" value="<?php echo $dateEvent; ?>" />
                                            &nbsp;<span style="font:0.7em sans-serif;color:#333333;"
                                                readonly>(dd/mm/yyyy)</span></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>004</b></td>
                                        <td class="boldPersonal">Writer's name and position</td>
                                        <td><input class="styeInputText" type="text" size="45" maxlength="40"
                                                name="nmWriter" id="nmWriter" / title="Writer's name (Max 40 character)"
                                                value="<?php echo $nmWriter; ?>" readonly></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>005</b></td>
                                        <td class="boldPersonal">Date of report</td>
                                        <td><input class="styeInputText" type="text" style="width:70px;" maxlength="10"
                                                name="dateReport" id="dateReport" value="<?php echo $dateReport; ?>"
                                                readonly /> &nbsp;<span
                                                style="font:0.7em sans-serif;color:#333333;">(dd/mm/yyyy)</span></td>
                                    </tr>
                                    <tr valign="top">
                                        <td>&nbsp;</td>
                                        <td class="boldPersonal">Type of Report</td>
                                        <td height="20" valign="top"><img src="../picture/arrow-turn-270.png"></td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td height="10"></td>
        </tr>
        <tr>
            <td align="center" height="25">
                <table cellpadding="0" cellspacing="0" width="250" height="100%"
                    style="background-color:#F8F8F8;font-family:Arial;font-size:14px;font-weight:bold;color:#333;">
                    <tr align="center">
                        <td class="tabelBorderAll">C - VERSION NON-COMFORMITY</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="5"></td>
        </tr>
        <tr>
            <td align="center" height="35">
                <table cellpadding="0" cellspacing="0" width="98%" height="100%">
                    <tr>
                        <td align="left" style="color:#555;font-family:Arial;font-size:12px;text-decoration:underline;">
                            <b>DESCRIPTION 1/2 : </b><i>KEY INFORMATION DEPENDING ON TYPE OF REPORT (DETAILED
                                DESCRIPTION OVERLEAF)</i>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="5"></td>
        </tr>
        <form method="post" action="halDataInfo.php?idData=<?php echo $idDataGet; ?>" enctype="multipart/form-data"
            id="formDataInfo" name="formDataInfo">
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsg500"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend class="" style="height:30px;">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                            style="position:absolute;left:26px; height:30px;width:410px; font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;text-align:justify;">
                                            CONSEQUENCES IN TERMS OF DAMAGE TO PROPERTY, ENVIRONMENT OR LOSS TO
                                            OPERATION </span></legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20" class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb500" name="cb500"
                                                    value="Y" <?php echo $centangCb500; ?>>
                                            </td>
                                            <td width="425" class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb500');"><span><b>500</b> Flag state</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb510" name="cb510"
                                                    value="Y" <?php echo $centangCb510; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb510');"><span><b>510</b> Port state</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb520" name="cb520"
                                                    value="Y" <?php echo $centangCb520; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb520');"><span><b>520</b> Classification</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb530" name="cb530"
                                                    value="Y" <?php echo $centangCb530; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb530');"><span><b>530</b> Company
                                                    Management</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb540" name="cb540"
                                                    value="Y" <?php echo $centangCb540; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb540');"><span><b>540</b> Charterer</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb550" name="cb550"
                                                    value="Y" <?php echo $centangCb550; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb550');"><span><b>550</b> Other</span> </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsg700"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; NOTICE GIVEN, IF ANY &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20" class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb700" name="cb700"
                                                    value="Y" <?php echo $centangCb700; ?>>
                                            </td>
                                            <td width="425" class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb700');"><span><b>700</b> Flag state</span> </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="styleCekBox" id="cb710" name="cb710"
                                                    value="Y" <?php echo $centangCb710; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb710');"><span><b>710</b> Port state</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb720" name="cb720"
                                                    value="Y" <?php echo $centangCb720; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb720');"><span><b>720</b> Class</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb730" name="cb730"
                                                    value="Y" <?php echo $centangCb730; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb730');"><span><b>730</b> Charterer</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb740" name="cb740"
                                                    value="Y" <?php echo $centangCb740; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb740');"><span><b>740</b> Others</span> </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="boldPersonal">NOTICE GIVEN, IF ANY</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" maxlength="50"
                                                    name="noticeGiven" id="noticeGiven" title="Notice given, If any"
                                                    style="width:372px;" value="<?php echo $isiNoticeGiven; ?>"
                                                    readonly />&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td height="35">
                    <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                        <tr>
                            <td align="center"
                                style="color:#555;font-family:Arial;font-size:12px;text-decoration:underline;">
                                <b>DESCRIPTION 2/2 : </b><i>DETAILED INFORMATION</i>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; GENERAL GUIDELINES &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" height="50" style="text-align:justify;">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This page is to be filled in on board for
                                                all reports versions. The Description should be as short and specific as
                                                possible. If you need more space for your description , please continue
                                                on separate paper.</td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsgDescribeHappen"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; DESCRIBE WHAT HAPPEN &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425"><textarea class="styeInputText" id="describeHappen"
                                                    name="describeHappen" rows="5" cols="70"
                                                    readonly><?php echo $isiDescribeHappen; ?></textarea>&nbsp;</td>
                                        </tr>
                                        <?php
						$htmlPaper = "";
						
						$jmlPaperAktif = $CData->jmlPaperAktif($idDataGet, $namaKapalGet, $hdsnGet);

						$htmlPaper.= "" 
						?>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" style="width:30px;"
                                                    maxlength="1" name="jmlMorePaper" id="jmlMorePaper"
                                                    value="<?php echo $jmlPaperAktif ; ?>" />&nbsp;<span
                                                    class="boldPersonalAbu"> More Paper </span>
                                                <input type="hidden" id="jmlPaperLama"
                                                    value="<?php echo $jmlPaperAktif ; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td id="idHalMorePaper">
                                                <?php
							for($a=1; $a<=$jmlPaperAktif; $a++)
							{
						?>
                                                <span class="boldPersonal" style="color:#A60000;"> Paper
                                                    <?php echo $a; ?> </span><br />
                                                <textarea class="styeInputText" rows="5" cols="70"
                                                    id="paper<?php echo $a; ?>" name="paper<?php echo $a; ?>"
                                                    readonly><?php echo $CData->detilPaper($idDataGet, $namaKapalGet, $hdsnGet, $a, "isipaper"); ?></textarea>&nbsp;<br>

                                                <?php
							}
						?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <?php "";
						echo $htmlPaper;
						?>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsgDescribeProbable"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; DESCRIBE PROBABLE CAUSED &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425"> <textarea class="styeInputText" rows="5" cols="70"
                                                    id="probableCaused" name="probableCaused"
                                                    readonly><?php echo $isiProbableCaused; ?></textarea>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend class="" style="height:30px;">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                            style="position:absolute;left:26px; height:30px;width:410px; font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;text-align:justify;">
                                            DESCRIBE IMMEDIATE EXECUTED CORRECTIVE ACTION, (IF ANY) AND FURTHER
                                            RECOMENDED CORRECTIVE ACTION AND PREVENTIVE ACTION </span></legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <!--<tr><td height="5"></td></tr>
                        <tr>
                        	<td width="20">&nbsp;</td>
                            <td width="425">
                            	<textarea class="styeInputText" rows="5" cols="70" id="describeImmediate" name="paper1" onKeyUp="textCounter(this, sisaDescribeImmediate, 3000);"></textarea>&nbsp;<input style="width:35px;" type="text" name="sisaDescribeImmediate" value="3000" readonly disabled></td>
                        </tr>
                        <tr><td height="5"></td></tr>
                        -->
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Immediate corrective action </td>
                                        </tr>
                                        <tr valign="top">
                                            <td>&nbsp;</td>
                                            <td>
                                                <textarea class="styeInputText" rows="5" cols="70" id="immediateCorr"
                                                    name="immediateCorr"
                                                    readonly><?php echo $isiImmediateCorr; ?></textarea>&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Corrective action </td>
                                        </tr>
                                        <tr valign="top">
                                            <td>&nbsp;</td>
                                            <td>
                                                <textarea class="styeInputText" rows="5" cols="70" id="corrAction"
                                                    name="corrAction"
                                                    readonly><?php echo $isiCorrAction; ?></textarea>&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Preventive action </td>
                                        </tr>
                                        <tr valign="top">
                                            <td>&nbsp;</td>
                                            <td>
                                                <textarea class="styeInputText" rows="5" cols="70" id="preventive"
                                                    name="preventive"
                                                    readonly><?php echo $isiPreventive; ?></textarea>&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Corrective & Preventive Action
                                                Proposal Completion Date </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" style="width:70px;"
                                                    maxlength="10" name="proposalDate" id="proposalDate" /
                                                    title="Corrective & Preventive Action Proposal Completion Date"
                                                    value="<?php echo $proposalDate; ?>"> &nbsp;
                                                <span style="font:0.7em sans-serif;color:#333333;">(dd/mm/yyyy)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> PIC </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" size="33" maxlength="30"
                                                    title="PIC (Max 30 Character)" name="pic" id="pic"
                                                    value="<?php echo $isiPic; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; SIGNATURE INFORMATION &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="130" class="boldPersonal"> Place and date </td>
                                            <td width="315"><input class="styeInputText" type="text" size="20"
                                                    name="signPlace" id="signPlace"
                                                    title="Place signature (Max 20 Character)"
                                                    value="<?php echo $isiSignPlace; ?>" readonly />
                                                <input class="styeInputText" type="text" size="8" maxlength="10"
                                                    name="signDate" id="signDate" title="Data signature"
                                                    value="<?php echo $isiSignDate; ?>" readonly />&nbsp;
                                                <span style="font:0.7em sans-serif;color:#333333;">(dd/mm/yyyy)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" title="Masters signature"> Masters </td>
                                            <td><input class="styeInputText" type="text" size="35" name="signMaster"
                                                    id="signMaster" title="Masters signature (Max 25 character)"
                                                    value="<?php echo $isiSignMaster; ?>" readonly /></td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" title="Ch.off / Ch.eng's signature"> Ch.off /
                                                Ch.eng's </td>
                                            <td><input class="styeInputText" type="text" size="35" name="signChef"
                                                    id="signChef" title="Ch.off / Ch.eng's signature (Max 25 character)"
                                                    value="<?php echo $isiSignChef; ?>" readonly /></td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" height="20" title="Safety Com. rep's signature">
                                                Safety Com. rep's </td>
                                            <td class="styeInputText"><b><?php echo $isiSignSafCom; ?></b>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <input type="hidden" id="halaman" name="halaman" value="simpanData">
        </form>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <?php
}
else if(($aksiGet == "pilihRow" && $typeReport == "D") || ($halamanPost == "simpanData" && $typeReport == "D"))
{
	$centangCb600 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb600");
	$centangCb610 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb610");
	$centangCb620 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb620");
	$centangCb630 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb630");
	$centangCb640 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb640");
	$centangCb650 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb650");
	$centangCb660 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb660");
	$centangCb670 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb670");
	$centangCb680 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb680");
	
	$centangCb700 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb700");
	$centangCb710 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb710");
	$centangCb720 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb720");
	$centangCb730 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb730");
	$centangCb740 = detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb740");
	$isiNoticeGiven = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "noticegiven");
	
	$isiDescribeHappen = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "describehappen");
	$isiProbableCaused = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "probablecaused");
	$isiImmediateCorr = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "immediatecorr");
	$isiCorrAction = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "corraction");
	$isiPreventive = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "preventive");
	$proposalDate = gantiNilai("00/00/0000", "", $CPublic->convTglNonDB( detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "proposaldate") ));
	$isiPic = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "pic");
	
	$isiSignPlace = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signplace");
	$isiSignDate = gantiNilai("00/00/0000", "", $CPublic->convTglNonDB( detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signdate") ));
	$isiSignMaster = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signmaster");
	$isiSignChef = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signchef");
	$isiSignSafCom = detilInfoByIdData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signsafcom");
?>
    <table cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
            <td height="5"></td>
        </tr>

        <tr>
            <td align="center">
                <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                    <tr>
                        <td height="200">
                            <fieldset>
                                <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                    &nbsp;&nbsp;&nbsp;GENERAL INFORMATION&nbsp;&nbsp;&nbsp;</legend>
                                <table width="100%" height="100%" bgcolor="#F0FFF0" border="0" class="">

                                    <tr valign="top">
                                        <td width="18" class="boldPersonal"><b>000</b></td>
                                        <td width="114" class="boldPersonal">Report no.</td>
                                        <td width="310">
                                            <input class="styeInputText" type="text" size="21" maxlength="20"
                                                name="noReport" id="noReport" title="Report number (Max 20 character)"
                                                value="<?php echo $noReport; ?>" readonly />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>001</b></td>
                                        <td class="boldPersonal">Name of Vessel</td>
                                        <td><input class="styeInputText" type="text" size="45" maxlength="40"
                                                name="nmVessel" id="nmVessel" title="Name of Vessel (Max 40 character)"
                                                value="<?php echo $nmVessel; ?>" readonly /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>002</b></td>
                                        <td class="boldPersonal">Voyage No.</td>
                                        <td><input class="styeInputText" type="text" size="21" maxlength="20"
                                                name="noVoyage" id="noVoyage" title="Voyage number (Max 20 character)"
                                                value="<?php echo $noVoyage; ?>" readonly /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>003</b></td>
                                        <td class="boldPersonal">Date of event</td>
                                        <td><input class="styeInputText" type="text" style="width:70px;" maxlength="10"
                                                name="dateEvent" id="dateEvent" value="<?php echo $dateEvent; ?>" />
                                            &nbsp;<span style="font:0.7em sans-serif;color:#333333;"
                                                readonly>(dd/mm/yyyy)</span></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>004</b></td>
                                        <td class="boldPersonal">Writer's name and position</td>
                                        <td><input class="styeInputText" type="text" size="45" maxlength="40"
                                                name="nmWriter" id="nmWriter" / title="Writer's name (Max 40 character)"
                                                value="<?php echo $nmWriter; ?>" readonly></td>
                                    </tr>
                                    <tr valign="top">
                                        <td class="boldPersonal"><b>005</b></td>
                                        <td class="boldPersonal">Date of report</td>
                                        <td><input class="styeInputText" type="text" style="width:70px;" maxlength="10"
                                                name="dateReport" id="dateReport" value="<?php echo $dateReport; ?>"
                                                readonly /> &nbsp;<span
                                                style="font:0.7em sans-serif;color:#333333;">(dd/mm/yyyy)</span></td>
                                    </tr>
                                    <tr valign="top">
                                        <td>&nbsp;</td>
                                        <td class="boldPersonal">Type of Report</td>
                                        <td height="20" valign="top"><img src="../picture/arrow-turn-270.png"></td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td height="10"></td>
        </tr>
        <tr>
            <td align="center" height="25">
                <table cellpadding="0" cellspacing="0" width="250" height="100%"
                    style="background-color:#F8F8F8;font-family:Arial;font-size:14px;font-weight:bold;color:#333;">
                    <tr align="center">
                        <td class="tabelBorderAll">D - VERSION SUGGESTION FOR IMPROVEMENT</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="5"></td>
        </tr>
        <tr>
            <td align="center" height="35">
                <table cellpadding="0" cellspacing="0" width="98%" height="100%">
                    <tr>
                        <td align="left" style="color:#555;font-family:Arial;font-size:12px;text-decoration:underline;">
                            <b>DESCRIPTION 1/2 : </b><i>KEY INFORMATION DEPENDING ON TYPE OF REPORT (DETAILED
                                DESCRIPTION OVERLEAF)</i>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="5"></td>
        </tr>
        <form method="post" action="halDataInfo.php?idData=<?php echo $idDataGet; ?>" enctype="multipart/form-data"
            id="formDataInfo" name="formDataInfo">
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsg600"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend class="" style="height:30px;">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                            style="position:absolute;left:26px; height:30px;width:410px; font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;text-align:justify;">
                                            CONSEQUENCES IN TERMS OF DAMAGE TO PROPERTY, ENVIRONMENT OR LOSS TO
                                            OPERATION </span></legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20" class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb600" name="cb600"
                                                    value="Y" <?php echo $centangCb600; ?>>
                                            </td>
                                            <td width="425" class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb600');"><span><b>600</b> Working Methods</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb610" name="cb610"
                                                    value="Y" <?php echo $centangCb610; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb610');"><span><b>610</b> Procedures &
                                                    routines</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb620" name="cb620"
                                                    value="Y" <?php echo $centangCb620; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb620');"><span><b>620</b> Constructions /
                                                    equipment / materials</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb630" name="cb630"
                                                    value="Y" <?php echo $centangCb630; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb630');"><span><b>630</b> Education and
                                                    training</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb640" name="cb640"
                                                    value="Y" <?php echo $centangCb640; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb640');"><span><b>640</b> Information /
                                                    instruction</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb650" name="cb650"
                                                    value="Y" <?php echo $centangCb650; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb650');"><span><b>650</b> Social</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb660" name="cb660"
                                                    value="Y" <?php echo $centangCb660; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb660');"><span><b>660</b> Other ship
                                                    related</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb670" name="cb670"
                                                    value="Y" <?php echo $centangCb670; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb670');"><span><b>670</b> Other shore
                                                    related</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb680" name="cb680"
                                                    value="Y" <?php echo $centangCb680; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb680');"><span><b>680</b> Other</span> </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsg700"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; NOTICE GIVEN, IF ANY &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20" class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb700" name="cb700"
                                                    value="Y" <?php echo $centangCb700; ?>>
                                            </td>
                                            <td width="425" class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb700');"><span><b>700</b> Flag state</span> </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="styleCekBox" id="cb710" name="cb710"
                                                    value="Y" <?php echo $centangCb710; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb710');"><span><b>710</b> Port state</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb720" name="cb720"
                                                    value="Y" <?php echo $centangCb720; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb720');"><span><b>720</b> Class</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb730" name="cb730"
                                                    value="Y" <?php echo $centangCb730; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb730');"><span><b>730</b> Charterer</span> </td>
                                        </tr>
                                        <tr>
                                            <td class="tabelBorderBottomJust">
                                                <input type="checkbox" class="styleCekBox" id="cb740" name="cb740"
                                                    value="Y" <?php echo $centangCb740; ?>>
                                            </td>
                                            <td class="tabelBorderBottomJust boldPersonal"
                                                onClick="klikCekBox('cb740');"><span><b>740</b> Others</span> </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="boldPersonal">NOTICE GIVEN, IF ANY</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" maxlength="50"
                                                    name="noticeGiven" id="noticeGiven" title="Notice given, If any"
                                                    style="width:372px;" value="<?php echo $isiNoticeGiven; ?>"
                                                    readonly />&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td height="35">
                    <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                        <tr>
                            <td align="center"
                                style="color:#555;font-family:Arial;font-size:12px;text-decoration:underline;">
                                <b>DESCRIPTION 2/2 : </b><i>DETAILED INFORMATION</i>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; GENERAL GUIDELINES &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" height="50" style="text-align:justify;">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This page is to be filled in on board for
                                                all reports versions. The Description should be as short and specific as
                                                possible. If you need more space for your description , please continue
                                                on separate paper.</td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsgDescribeHappen"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; DESCRIBE WHAT HAPPEN &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425"><textarea class="styeInputText" id="describeHappen"
                                                    name="describeHappen" rows="5" cols="70"
                                                    readonly><?php echo $isiDescribeHappen; ?></textarea>&nbsp;</td>
                                        </tr>
                                        <?php
						$htmlPaper = "";
						
						$jmlPaperAktif = $CData->jmlPaperAktif($idDataGet, $namaKapalGet, $hdsnGet);

						$htmlPaper.= "" 
						?>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" style="width:30px;"
                                                    maxlength="1" name="jmlMorePaper" id="jmlMorePaper"
                                                    value="<?php echo $jmlPaperAktif ; ?>" />&nbsp;<span
                                                    class="boldPersonalAbu"> More Paper </span>
                                                <input type="hidden" id="jmlPaperLama"
                                                    value="<?php echo $jmlPaperAktif ; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td id="idHalMorePaper">
                                                <?php
							for($a=1; $a<=$jmlPaperAktif; $a++)
							{
						?>
                                                <span class="boldPersonal" style="color:#A60000;"> Paper
                                                    <?php echo $a; ?> </span><br />
                                                <textarea class="styeInputText" rows="5" cols="70"
                                                    id="paper<?php echo $a; ?>" name="paper<?php echo $a; ?>"
                                                    readonly><?php echo $CData->detilPaper($idDataGet, $namaKapalGet, $hdsnGet, $a, "isipaper"); ?></textarea>&nbsp;<br>

                                                <?php
							}
						?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <?php "";
						echo $htmlPaper;
						?>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="middle" class="tabelBorderAll errorMsg"
                                style="border-style:dashed;border-color:#FF9B9B;" id="tdErrorMsgDescribeProbable"></td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; DESCRIBE PROBABLE CAUSED &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425"> <textarea class="styeInputText" rows="5" cols="70"
                                                    id="probableCaused" name="probableCaused"
                                                    readonly><?php echo $isiProbableCaused; ?></textarea>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend class="" style="height:30px;">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                            style="position:absolute;left:26px; height:30px;width:410px; font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;text-align:justify;">
                                            DESCRIBE IMMEDIATE EXECUTED CORRECTIVE ACTION, (IF ANY) AND FURTHER
                                            RECOMENDED CORRECTIVE ACTION AND PREVENTIVE ACTION </span></legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <!--<tr><td height="5"></td></tr>
                        <tr>
                        	<td width="20">&nbsp;</td>
                            <td width="425">
                            	<textarea class="styeInputText" rows="5" cols="70" id="describeImmediate" name="paper1" onKeyUp="textCounter(this, sisaDescribeImmediate, 3000);"></textarea>&nbsp;<input style="width:35px;" type="text" name="sisaDescribeImmediate" value="3000" readonly disabled></td>
                        </tr>
                        <tr><td height="5"></td></tr>
                        -->
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Immediate corrective action </td>
                                        </tr>
                                        <tr valign="top">
                                            <td>&nbsp;</td>
                                            <td>
                                                <textarea class="styeInputText" rows="5" cols="70" id="immediateCorr"
                                                    name="immediateCorr"
                                                    readonly><?php echo $isiImmediateCorr; ?></textarea>&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Corrective action </td>
                                        </tr>
                                        <tr valign="top">
                                            <td>&nbsp;</td>
                                            <td>
                                                <textarea class="styeInputText" rows="5" cols="70" id="corrAction"
                                                    name="corrAction"
                                                    readonly><?php echo $isiCorrAction; ?></textarea>&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Preventive action </td>
                                        </tr>
                                        <tr valign="top">
                                            <td>&nbsp;</td>
                                            <td>
                                                <textarea class="styeInputText" rows="5" cols="70" id="preventive"
                                                    name="preventive"
                                                    readonly><?php echo $isiPreventive; ?></textarea>&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> Corrective & Preventive Action
                                                Proposal Completion Date </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" style="width:70px;"
                                                    maxlength="10" name="proposalDate" id="proposalDate" /
                                                    title="Corrective & Preventive Action Proposal Completion Date"
                                                    value="<?php echo $proposalDate; ?>"> &nbsp;
                                                <span style="font:0.7em sans-serif;color:#333333;">(dd/mm/yyyy)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20">&nbsp;</td>
                                            <td width="425" class="boldPersonal"> PIC </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input class="styeInputText" type="text" size="33" maxlength="30"
                                                    title="PIC (Max 30 Character)" name="pic" id="pic"
                                                    value="<?php echo $isiPic; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" class="" width="460" height="100%">
                        <tr>
                            <td valign="top" align="center">
                                <fieldset>
                                    <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">
                                        &nbsp;&nbsp; SIGNATURE INFORMATION &nbsp;&nbsp;</legend>
                                    <table cellpadding="0" cellspacing="0" width="445" height="100%" bgcolor="#F0FFF0"
                                        border="0" class="">
                                        <tr>
                                            <td width="130" class="boldPersonal"> Place and date </td>
                                            <td width="315"><input class="styeInputText" type="text" size="20"
                                                    name="signPlace" id="signPlace"
                                                    title="Place signature (Max 20 Character)"
                                                    value="<?php echo $isiSignPlace; ?>" readonly />
                                                <input class="styeInputText" type="text" size="8" maxlength="10"
                                                    name="signDate" id="signDate" title="Data signature"
                                                    value="<?php echo $isiSignDate; ?>" readonly />&nbsp;
                                                <span style="font:0.7em sans-serif;color:#333333;">(dd/mm/yyyy)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" title="Masters signature"> Masters </td>
                                            <td><input class="styeInputText" type="text" size="35" name="signMaster"
                                                    id="signMaster" title="Masters signature (Max 25 character)"
                                                    value="<?php echo $isiSignMaster; ?>" readonly /></td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" title="Ch.off / Ch.eng's signature"> Ch.off /
                                                Ch.eng's </td>
                                            <td><input class="styeInputText" type="text" size="35" name="signChef"
                                                    id="signChef" title="Ch.off / Ch.eng's signature (Max 25 character)"
                                                    value="<?php echo $isiSignChef; ?>" readonly /></td>
                                        </tr>
                                        <tr>
                                            <td class="boldPersonal" height="20" title="Safety Com. rep's signature">
                                                Safety Com. rep's </td>
                                            <td class="styeInputText"><b><?php echo $isiSignSafCom; ?></b>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <input type="hidden" id="halaman" name="halaman" value="simpanData">
        </form>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <?php
}
else
{
?>
    <table width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#F2FFF5"
        style="font:0.7em sans-serif;color:#333;">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td align="center">
                <?php //echo $idDataGet." / ".$namaKapalGet." / ".$hdsnGet; ?>
                <table cellpadding="0" cellspacing="0" width="99%">
                    <tr align="center">
                        <td height="423" style="font-family:sans-serif;font-weight:bold;font-size:26px;color:#CCC;">
                            PLEASE SELECT DATA ROW</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <?php	
}
?>
</body>