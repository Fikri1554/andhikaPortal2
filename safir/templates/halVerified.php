<?php 
require_once("../configSafir.php"); 
require_once("../class/clsencrypt.php");

$aksiGet = $_GET['aksi'];
$idDataGet = $_GET['idData'];
$namaKapalGet = $_GET['namaKapal'];
$hdsnGet = $_GET['hdsn'];

$tutupWindow = "N";
$btnAccDipilihPost = $_POST['btnAccDipilih'];
$btnClosedDipilihPost = $_POST['btnClosedDipilih'];
$errorMsg = "";

if($aksiGet == "pilihAccButton")
{
	if($btnAccDipilihPost == "yes")
	{
		$CKoneksi->mysqlQuery("UPDATE datalaporan SET accept='Y', waktuaccept='".$CPublic->tglServer()."/".$CPublic->jamServer()."', acceptby='".$userFullnm."' WHERE iddata = '".$idDataGet."' AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0 LIMIT 1;");
		$CHistory->updateLog2($userIdLogin, "GIVING STATUS ACCEPT (iddata=<b>".$idDataGet."</b>, namakapal=<b>".$namaKapalGet."</b>, hdsn=<b>".$hdsnGet."</b>)");
		
		$errorMsg = "&nbsp;SUCCESSFULLY GIVING STATUS ACCEPT";
	}
	if($btnAccDipilihPost == "no")
	{
		$CKoneksi->mysqlQuery("UPDATE datalaporan SET accept='N', waktunotaccept='".$CPublic->tglServer()."/".$CPublic->jamServer()."', acceptby='".$userFullnm."' WHERE iddata = '".$idDataGet."' AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0 LIMIT 1;");
		$CHistory->updateLog2($userIdLogin, "GIVING STATUS NOT ACCEPT (iddata = <b>".$idDataGet."</b>, namakapal=<b>".$namaKapalGet."</b>, hdsn=<b>".$hdsnGet."</b>)");
		
		$errorMsg = "&nbsp;SUCCESSFULLY GIVING STATUS NOT ACCEPT";
	}
}
if($aksiGet == "pilihClosedButton")
{
	$explanationPost = mysql_real_escape_string($_POST['explanation']);
	if($btnClosedDipilihPost == "yes")
	{
		$CKoneksi->mysqlQuery("UPDATE datalaporan SET closed='Y', explanation='".$explanationPost."', waktuclosed='".$CPublic->tglServer()."/".$CPublic->jamServer()."', closedby='".$userFullnm."' WHERE iddata = '".$idDataGet."' AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0 LIMIT 1;");
		$CHistory->updateLog2($userIdLogin, "GIVING STATUS CLOSED (iddata = <b>".$idDataGet."</b>, namakapal=<b>".$namaKapalGet."</b>, hdsn=<b>".$hdsnGet."</b>)");
		
		$errorMsg = "&nbsp;SUCCESSFULLY GIVING STATUS CLOSED";
	}
	if($btnClosedDipilihPost == "no")
	{
		$CKoneksi->mysqlQuery("UPDATE datalaporan SET closed='N', explanation='".$explanationPost."', waktunotclosed='".$CPublic->tglServer()."/".$CPublic->jamServer()."', closedby='".$userFullnm."' WHERE iddata = '".$idDataGet."' AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0 LIMIT 1;");
		$CHistory->updateLog2($userIdLogin, "GIVING STATUS NOT CLOSED (iddata = <b>".$idDataGet."</b>, namakapal=<b>".$namaKapalGet."</b>, hdsn=<b>".$hdsnGet."</b>)");
		
		$errorMsg = "&nbsp;SUCCESSFULLY GIVING STATUS NOT CLOSED";
	}
}

$acceptNow = "NO";
if($CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "accept") == "Y")
{
	$acceptNow = "YES";
}
$closedNow = "NO";
if($CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "closed") == "Y")
{
	$closedNow = "YES";
}
$explanation = $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "explanation");
?>

<script type="text/javascript" src="../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../js/main.js"></script>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css">
<link href="../css/button.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
this.window.onload = 
function() 
{
	doneWait();
}

function tutup()
{
	parent.tb_remove(false);
	parent.setelahTutup();
	
}

function pilihBtnAccept(nilaiAccept)
{
	if(nilaiAccept == "Y")
	{
		var answer  = confirm("Are you sure want to Give Accept Status?");
		if(answer)
		{	
			pleaseWait();
			document.getElementById('btnAccDipilih').value = "yes";
			
			formVer.submit();
		}
		else
		{	return false;	}
	}
	if(nilaiAccept == "N")
	{
		var answer  = confirm("Are you sure want to Give Not Accept Status?");
		if(answer)
		{	
			pleaseWait();
			document.getElementById('btnAccDipilih').value = "no";
			
			formVer.submit();
		}
		else
		{	return false;	}
	}
}

function pilihBtnClosed(nilaiClosed)
{
	if(nilaiClosed == "Y")
	{
		var answer  = confirm("Are you sure want to Give Closed Status?");
		if(answer)
		{	
			pleaseWait();
			document.getElementById('btnClosedDipilih').value = "yes";
			
			formClosed.submit();
		}
		else
		{	return false;	}
	}
	if(nilaiClosed == "N")
	{
		var answer  = confirm("Are you sure want to Give Not Closed Status?");
		if(answer)
		{	
			pleaseWait();
			document.getElementById('btnClosedDipilih').value = "no";
			
			formClosed.submit();
		}
		else
		{	return false;	}
	}
}

function acceptNo()
{
	var answer  = confirm("Are you sure want to Give Not Accept Status?");
	if(answer)
	{	
		pleaseWait();
		document.getElementById('btnDipilih').value = "acceptNo";
	}
	else
	{	return false;	}
}

function pleaseWait()
{
	document.getElementById('loaderImg').style.visibility = "visible";
}

function doneWait()
{
	document.getElementById('loaderImg').style.visibility = "hidden";
}

function blinker() {
    $('.errorMsg').fadeOut(250);
    $('.errorMsg').fadeIn(500);
}
setInterval(blinker, 1500);
</script>

<style>
.pleaseWait
{
	position:absolute;
	width:556px;
	margin:0 auto;
}

.isiPleaseWait
{
	
	width:200px;
	padding: 4px;

	color:#333;
	font-family:Arial;
	font-weight:bold;
	font-size:12px;
	height:25px;
	background-color:#F4FBF4;
	
	-webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}

.wrapVer
{
	position:relative;
	width: 556px;
	height: 400px;

	margin:0 auto;
}

.judul
{
	position: absolute;
	width: 554px;
	height:30px;
	
	left: 50%;
  	margin-left: -277px;
	
	text-align:center;
	padding:6px;
	
	background-color:#666;
	color:#F9F9F9;
	font-family:Arial;
	font-weight:bold;
	font-size:14px;
}

.kontenVer1
{
	position: absolute;
	width: 554px;
	
	left: 50%;
  	margin-left: -277px;
	
	top:40px;
}

.kontenVer2
{
	position: absolute;
	width: 554px;
	height:30px;
	
	left: 50%;
  	margin-left: -277px;
	
	top:155px;
}

.kontenErr
{
	position: absolute;
	width: 554px;
	height:25px;
	
	left: 50%;
  	margin-left: -277px;
	
	top:340px;
}

.btnClose
{
	position: absolute;
	width: 554px;
	height:40px;
	
	left: 50%;
  	margin-left: -277px;
	
	top:380px;
}
</style>

<form id="formVer" name="formVer" action="halVerified.php?aksi=pilihAccButton&idData=<?php echo $idDataGet; ?>&namaKapal=<?php echo $namaKapalGet; ?>&hdsn=<?php echo $hdsnGet; ?>" method="post" enctype="multipart/form-data">
<input type="hidden" id="btnAccDipilih" name="btnAccDipilih">
</form>




<div class="" style="position:absolute;width:556px;margin:0 auto;z-index:1;">

	<div id="loaderImg" style="visibility:visible;" class="pleaseWait">
    	<center>
    	<div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;</div>
        </center>
    </div>
   
</div>

<div class="wrapVer">

	<div class="judul"> &nbsp;:: GIVE VERIFICATION  ::&nbsp </div>
    
    <div class="kontenVer1">
    	<fieldset>
            <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">&nbsp;&nbsp;&nbsp;VERIFICATION CORRECTIVE & PREVENTIVE ACTION PROPOSAL&nbsp;&nbsp;&nbsp;</legend>
            <table width="100%" height="100%" bgcolor="#F0FFF0" border="0" class="">
            <tr><td height="15"></td></tr> 
            <tr>
            	<td width="21%" class="boldPersonal">&nbsp;Status Now</td>
            	<td width="79%" class="boldPersonalAbu"><?php echo $acceptNow; ?></td>
            </tr>
            <tr>
            	<td class="boldPersonal">&nbsp;Accept Status</td>
            	<td>
                	<button class="btnStandar" onMouseOver="this.className='btnStandarHover'" onMouseOut="this.className='btnStandar'" style="width:52px;height:30px;" title="GIVING YES STATUS" onclick="pilihBtnAccept('Y');return false;">
                      <table class="fontBtn" onMouseOver="this.className='fontBtnHover'" onMouseOut="this.className='fontBtn'" cellpadding="0" cellspacing="0" width="100%" height="26">
                      <tr valign="middle">
                          <td align="center" width="22"><img src="../picture/thumb-up.png"/> </td>
                          <td align="left">YES</td> 
                      </tr>
                      </table>
                  </button>&nbsp;&nbsp;
                  <button class="btnStandar" onMouseOver="this.className='btnStandarHover'" onMouseOut="this.className='btnStandar'" style="width:46px;height:30px;" title="GIVING NO STATUS" onclick="pilihBtnAccept('N');return false;">
                      <table class="fontBtn" onMouseOver="this.className='fontBtnHover'" onMouseOut="this.className='fontBtn'" cellpadding="0" cellspacing="0" width="100%" height="26">
                      <tr valign="middle">
                          <td align="center" width="22"><img src="../picture/thumb.png"/> </td>
                          <td align="left">NO</td> 
                      </tr>
                      </table>
                  </button>
                </td>
            </tr>
            <tr><td height="15"></td></tr> 
            </table>
        </fieldset>
    </div>
    
    <div class="kontenVer2">
    	<form id="formClosed" name="formClosed" action="halVerified.php?aksi=pilihClosedButton&idData=<?php echo $idDataGet; ?>&namaKapal=<?php echo $namaKapalGet; ?>&hdsn=<?php echo $hdsnGet; ?>" method="post" enctype="multipart/form-data">
    	<fieldset>
            <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">&nbsp;&nbsp;&nbsp;VERIFICATION RESULT&nbsp;&nbsp;&nbsp;</legend>
            <table width="100%" height="100%" bgcolor="#F0FFF0" border="0" class="">
            <tr><td height="15"></td></tr> 
            <tr valign="top">
            	<td width="21%" class="boldPersonal">&nbsp;Status Now</td>
                <td width="79%" class="boldPersonalAbu"><?php echo $closedNow; ?></td>
            </tr>
            <tr valign="top">
            	<td class="boldPersonal">&nbsp;Explanation</td>
                <td>
                <textarea class="styeInputText" id="explanation" name="explanation" rows="4" cols="65" onKeyUp="textCounter(this, sisaTextExp, 200);"><?php echo $explanation; ?></textarea>&nbsp;<input style="width:35px;height:20px;" type="text" name="sisaTextExp" value="200" readonly disabled>
                </td>
            </tr>
            <tr>
            	<td class="boldPersonal">&nbsp;Closed Status</td>
                <td>
                	<button class="btnStandar" onMouseOver="this.className='btnStandarHover'" onMouseOut="this.className='btnStandar'" style="width:52px;height:30px;" title="GIVING YES STATUS" onclick="pilihBtnClosed('Y');return false;">
                      <table class="fontBtn" onMouseOver="this.className='fontBtnHover'" onMouseOut="this.className='fontBtn'" cellpadding="0" cellspacing="0" width="100%" height="26">
                      <tr valign="middle">
                          <td align="center" width="22"><img src="../picture/lock.png"/> </td>
                          <td align="left">YES</td> 
                      </tr>
                      </table>
                  </button>&nbsp;&nbsp;
                  <button class="btnStandar" onMouseOver="this.className='btnStandarHover'" onMouseOut="this.className='btnStandar'" style="width:46px;height:30px;" title="GIVING NO STATUS" onclick="pilihBtnClosed('N');return false;">
                      <table class="fontBtn" onMouseOver="this.className='fontBtnHover'" onMouseOut="this.className='fontBtn'" cellpadding="0" cellspacing="0" width="100%" height="26">
                      <tr valign="middle">
                          <td align="center" width="22"><img src="../picture/lock-unlock.png"/> </td>
                          <td align="left">NO</td> 
                      </tr>
                      </table>
                  </button>
                </td>
            </tr>
            <tr><td height="15"></td></tr> 
            </table>
        </fieldset>
        <input type="hidden" id="btnClosedDipilih" name="btnClosedDipilih">
		</form>
    </div>
    
    <div class="kontenErr">
    	<table width="100%" height="100%" bgcolor="#F0FFF0" border="0" class="">
        <tr>
            <td height="25" valign="bottom" class="tabelBorderAll errorMsg" id="errorMsg" style="border-style:dashed;border-color:#FF9B9B;font-size:14px;"><?php echo $errorMsg; ?></td>
        </tr>
        </table>
    </div>
    
    <div class="btnClose">
    	<button class="btnStandar" onMouseOver="this.className='btnStandarHover'" onMouseOut="this.className='btnStandar'" style="width:64px;height:30px;" title="CLOSE" onclick="tutup();return false;">
              <table class="fontBtn" onMouseOver="this.className='fontBtnHover'" onMouseOut="this.className='fontBtn'" cellpadding="0" cellspacing="0" width="100%" height="26">
              <tr valign="middle">
                  <td align="center" width="22"><img src="../picture/door-open-out.png"/> </td>
                  <td align="left">CLOSE</td> 
              </tr>
              </table>
          </button>
    </div>
</div>

<script>
<?php
if($tutupWindow == "Y")
{
	echo "tutup();";
}
?>
</script>