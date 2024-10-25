<!DOCTYPE HTML>
<?php
require_once("../configVoucher.php");

$aksiGet = $_GET['aksi'];
?>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../js/voucher.js"></script>
<script type="text/javascript" src="../js/popup.js"></script>

<script type="text/javascript" src="../../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../../js/Parsers.js"></script>
<script type="text/javascript" src="../../js/InputMask.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
<link href="../css/voucher.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css" />

<body>
<center>
<style>
body {background-color: #f9f9f9;}
</style>
<?php
if($aksiGet == "suksesSimpanBaruVoucher")
{
	$idVoucherGet = $_GET['idVoucher'];
	$batchno = $CVoucher->detilVoucher($idVoucherGet, "batchno");
	$yearProcess = $CVoucher->detilVoucher($idVoucherGet, "YEAR(datepaid)");
?>
	 <div style="position:inherit; border: solid 1px #CCC; width:100%; height:204px; text-align:center; background-color:#FFF;">
     	<div style="position:relative; top:10px;"><img src="../picture/Button-Check-blue-48.png"></div>
        <div style="position:relative; top:5px; font:0.8em sans-serif; line-height:30px; font-weight:bold;">Congratulations, You have successfully Save New Voucher</div>
        <div style="position:relative; top:5px; font:1.2em sans-serif; line-height:30px; color:#096;">
        	<span style=" font:0.8em sans-serif;">BATCHNO</span>&nbsp;<b><?php echo $CPublic->zerofill($batchno, 6); ?></b>
        </div>
        <div style="position:relative; top:5px; font:0.8em sans-serif; line-height:30px; font-weight:bold;">
        	<input type="checkbox" id="moreVoucher" name="moreVoucher" style="cursor:pointer;"><span style="cursor:pointer;" onclick="$('#moreVoucher').click();">&nbsp;Add New Voucher</span>
        </div>
        <div style="position:relative; top:10px;">
            <button class="btnStandar" id="" title="CLOSED" onclick="parent.closePopupNewVoucher('<?php echo $idVoucherGet; ?>', '<?php echo $yearProcess; ?>', $('#moreVoucher:checked').val());return false;">
                <table width="75" height="40">
                <tr>
                    <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                    <td align="left">CLOSED</td> 
                </tr>
                </table>
            </button>
        </div>
     </div>
<?php	
}
if($aksiGet == "suksesSimpanUbahVoucher")
{
	$idVoucherGet = $_GET['idVoucher'];
	$batchno = $CVoucher->detilVoucher($idVoucherGet, "batchno");
?>
	 <div style="position:inherit; border: solid 1px #CCC; width:100%; height:174px; text-align:center; background-color:#FFF;">
     	<div style="position:relative; top:10px;"><img src="../picture/Button-Check-blue-48.png"></div>
        <div style="position:relative; top:5px; font:0.8em sans-serif; line-height:30px; font-weight:bold;"><!--CONGRATULATIONS, EDIT VOUCHER SUCCEEDED-->Congratulations, You Have Successfully Edit Voucher</div>
        <div style="position:relative; top:5px; font:1.2em sans-serif; line-height:30px; color:#096;">
        	<span style=" font:0.8em sans-serif;">BATCHNO</span>&nbsp;<b><?php echo $CPublic->zerofill($batchno, 6); ?></b></div>
        <div style="position:relative; top:10px;">
            <button class="btnStandar" id="" title="CLOSED" onclick="parent.closePopup('<?php echo $idVoucherGet; ?>');return false;">
                <table width="75" height="40">
                <tr>
                    <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                    <td align="left">CLOSED</td> 
                </tr>
                </table>
            </button>
        </div>
     </div>
<?php
}
if($aksiGet == "suksesTransferAcct")
{
	$idVoucherGet = $_GET['idVoucher'];
	$batchno = $CVoucher->detilVoucher($idVoucherGet, "batchno");
?>
	 <div style="position:inherit; border: solid 1px #CCC; width:100%; height:174px; text-align:center; background-color:#FFF;">
     	<div style="position:relative; top:10px;"><img src="../picture/Button-Check-blue-48.png"></div>
        <div style="position:relative; top:5px; font:0.8em sans-serif; line-height:30px; font-weight:bold;">Congrulations, You have successfully Transfer to Accounting</div>
        <div style="position:relative; top:5px; font:1.2em sans-serif; line-height:30px; color:#096;">
        	<span style=" font:0.8em sans-serif;">BATCHNO</span>&nbsp;<b><?php echo $CPublic->zerofill($batchno, 6); ?></b></div>
        <div style="position:relative; top:10px;">
            <button class="btnStandar" id="" title="CLOSED" onclick="parent.closePopup('<?php echo $idVoucherGet; ?>');return false;">
                <table width="75" height="40">
                <tr>
                    <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                    <td align="left">CLOSED</td> 
                </tr>
                </table>
            </button>
        </div>
     </div>
<?php	
}
if($aksiGet == "descLargeView")
{
	$idVoucherGet = $_GET['idVoucher'];
	$batchnoGet = $_GET['batchno'];
	$transferAcct = $CVoucher->detilVoucher($idVoucherGet, "trfacct");
	$dis = "";
	$classBtn = "btnStandar";
	if($transferAcct == "Y")
	{
		$dis = "disabled";
		$classBtn = "btnStandarDis";
	}
	$company = strtoupper( $CVoucher->detilComp($CVoucher->detilVoucher($idVoucherGet, "company"), "compname") );
	$bookSts = $CVoucher->detilVoucher($idVoucherGet, "booksts");
	$amount = "( ".$CVoucher->detilVoucher($idVoucherGet, "currency")." )&nbsp;&nbsp;".number_format($CVoucher->detilVoucher($idVoucherGet, "amount"), 2, ".", ",");
	//echo $idVoucherGet." / ".$batchnoGet;
?>

    <div id="loaderImg" class="pleaseWait" style="visibility:hidden;top:0px;width:99%;">
    	<div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;</div>
    </div>


<div style="position:inherit; border: solid 1px #CCC; width:100%; height:524px;background-color:#FFF;">
	<input type="hidden" id="idVoucherHid" name="idVoucherHid" value="<?php echo $idVoucherGet; ?>"/>
    <input type="hidden" id="batchnoHid" name="batchnoHid" value="<?php echo $batchnoGet; ?>"/>
    <input type="hidden" id="bookStsHid" name="bookStsHid" value="<?php echo strtoupper($bookSts); ?>"/>
    <input type="hidden" id="amountHid" name="amountHid" value="<?php echo $CVoucher->detilVoucher($idVoucherGet, "amount"); ?>"/>
	<div style="position:relative; border-right: solid 1px #CCC;top:5px;width:350px;height:30px;text-align:left;display:table-cell;font:0.7em sans-serif;color:#333;padding-left:5px;">
    BATCHNO : <span style="font-size:11px;color:#096;font-weight:bold;"><?php echo $CPublic->zerofill($batchnoGet, 6); ?></span><br>
    COMPANY : <b><?php echo $company; ?></b>
    </div>
    <div style="position:relative; border: solid 0px #CCC;top:5px;width:200px;height:30px;text-align:left;display:table-cell;font:0.7em sans-serif;color:#333;">
    &nbsp;BOOK STATUS : <span style="text-decoration:underline;"><?php echo strtoupper($bookSts); ?></span><br>
    &nbsp;AMOUNT : <b><?php echo $amount; ?></b>
    </div>
	<div style="position:relative; border: solid 0px #CCC;top:5px;width:495px;height:30px;text-align:right;display:table-cell; vertical-align:bottom;padding-right:5px;">
        <span style="font:bold 0.7em sans-serif;color:#333;vertical-align: bottom;" id="spanJmlDesc">&nbsp;</span>&nbsp;
        <button class="<?php echo $classBtn; ?>" id="btnAddDesc" title="ADD NEW DESCRIPTION" onclick="pilihBtnAddDesc();return false;" <?php echo $dis; ?>>
            <table width="92" height="29">
            <tr>
                <td align="center" width="20"><img src="../picture/blue-document--plus.png"/></td>
                <td align="left" style="font-size:9px;">ADD DESCRIPTION</td>
            </tr>
            </table>
        </button>
        <button class="<?php echo $classBtn; ?>" id="btnEditDesc" title="EDIT DESCRIPTION" onclick="pilihBtnEditDesc();return false;" <?php echo $dis; ?>>
              <table width="50" height="29">
              <tr>
                  <td align="center" width="20"><img src="../picture/blue-document--pencil.png"/></td>
                  <td align="left">EDIT</td>
              </tr>
              </table>
        </button>
        <button class="btnStandar" id="btnSaveDesc" title="SAVE ALL DESCRIPTION" onclick="pilihBtnSaveDesc();return false;" style="display:none;">
              <table width="92" height="29">
              <tr>
                  <td align="center" width="20"><img src="../picture/disk-black.png"/></td>
                  <td align="left">SAVE DESC.</td>
              </tr>
              </table>
        </button>
        <button class="btnStandar" id="btnCancelDesc" title="CANCEL SAVE DESCRIPTION" onclick="pilihBtnCancelSaveDesc();return false;" style="display:none;">
              <table width="60" height="29">
              <tr>
                  <td align="center" width="20"><img src="../picture/arrow-return-180-left.png"/></td>
                <td align="left">CANCEL</td>
              </tr>
              </table>
        </button>
        <button class="btnStandar" id="btnClose" title="ADD NEW DESCRIPTION" onclick="parent.closePopup('<?php echo $idVoucherGet; ?>');return false;">
            <table width="73" height="29">
            <tr>
                <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                <td align="left">CLOSED</td>
            </tr>
            </table>
        </button>
        
    </div>
	 <div style="position:relative; border: solid 1px #CCC;top:10px;width:960px;height:476px;">
        <iframe style="vertical-align:central;" width="100%" height="100%" src="../templates/halPopupDescList.php?aksi=display&idVoucher=<?php echo $idVoucherGet; ?>&batchno=<?php echo $batchnoGet; ?>" target="iframeDescList" name="iframeDescList" id="iframeDescList" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes"></iframe>
     </div> 
</div>
<?php
	//$transNo = $CInvReg->detilMailInv($idMailInvGet, "transno");
}
if($aksiGet == "cari")
{
	$yearProcessGet = $_GET['yearProcess'];
?>
	<script type="text/javascript">
	window.onload = function()
	{	
		setupCari();
	}
	</script>

	<div style="position:absolute; top:5px; left:5px; vertical-align:top; text-align:left;border: solid 1px #CCC;">
        <table width="462px" cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;font-weight:bold;color:#485a88;background-color:#FFF;">
        <tr><td height="3"></td></tr>
        <tr>
            <td height="23" class="">&nbsp;SEARCH BY </td>
            <td class="elementTeks">
            	<select id="cariBerdasarkan" name="cariBerdasarkan" class="elementMenu" style="width:100px;" onchange="return false;">
                    <option value="paidName">PAID NAME</option>
                    <option value="company">COMPANY</option>
                    <option value="bank">BANK NAME</option>
                    <option value="voucherNo">VOUCHER NO</option>
                    <option value="invNo">INVOICE NO</option>
                    <option value="datePaid">DATE PAID</option>
                </select>
            </td>
        </tr>
        <tr valign="middle">
            <td width="90" height="41" class="">&nbsp;</td>
            <td class="elementTeks" id="">
                <textarea id="teksCari" name="teksCari" class="elementInput" style="height:35px;width:250px;"></textarea>
            </td>
        </tr>
        <tr valign="middle">
            <td height="23" class="">&nbsp;START</td>
            <td class="elementTeks">
                <input type="text" name="startDate" id="startDate" class="elementInput" style="width:60px;"/>&nbsp;<img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date" onclick="displayCalendar(document.getElementById('startDate'),'dd/mm/yyyy',this, '', '', '193', '183');" id="imgStartDate"/>
            </td>
        </tr>
        <tr valign="middle">
            <td height="23" class="">&nbsp;END</td>
            <td class="elementTeks" id="">
                <input type="text" name="endDate" id="endDate" class="elementInput" style="width:60px;"/>&nbsp;<img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date" onclick="displayCalendar(document.getElementById('endDate'),'dd/mm/yyyy',this, '', '', '193', '183');" id="imgEndDate"/>
            </td>
        </tr>
		<tr><td height="3"></td></tr>
        <tr valign="middle">
            <td height="20" valign="bottom" colspan="2" align="center">
                <div id="idErrorMsg" class="errorMsgInv" style="visibility:hidden;width:450px;"><img src="../picture/exclamation-red.png"/>&nbsp;<span>&nbsp;</span>&nbsp;</div>
            </td>
        </tr>
        <tr><td height="3"></td></tr>
        </table>
    </div>
    <div style="position:absolute; top:153px; left:5px; vertical-align:top; text-align:left;border: solid 0px #CCC;	">
    	<input type="hidden" id="idVoucher" name="idVoucher">
        <input type="hidden" id="menuPageBatchno" name="menuPageBatchno">
        <button class="btnStandar" id="btnDoSearch" title="START SEARCHING" onclick="klikBtnCari('<?php echo $yearProcessGet; ?>');return false;">
            <table width="90" height="24">
            <tr>
              <td align="center" width="20"><img src="../picture/magnifier.png"/></td>
              <td align="left">DO SEARCH</td> 
            </tr>
            </table>
        </button>
        |
        <button class="btnStandar" id="btnRefresh" title="" onclick="klikBtnRefresh();return false;">
            <table width="60" height="24">
            <tr>
                <td align="center" width="20"><img src="../picture/arrow-return-180-left.png"/></td>
                <td align="left">RESET</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandarDis" id="btnGetResult" title="" onclick="klikBtnResult('<?php echo $yearProcessGet; ?>');return false;" disabled>
            <table width="92" height="24">
            <tr>
                <td align="center" width="20"><img src="../picture/property.png"/></td>
                <td align="left">GET RESULT</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnClosed" title="" onclick="klikBtnClosed();return false;">
            <table width="73" height="24">
            <tr>
                <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                <td align="left">CLOSED</td> 
            </tr>
            </table>
        </button>
    </div>
    <div style="position:absolute; border: solid 1px #CCC; top:184px; left:5px; width:462px; height:303px; text-align:left;">        
        <iframe width="100%" height="100%" src="../templates/halCariList.php?yearProcess=<?php echo $yearProcessGet; ?>" target="iframeList" name="iframeList" id="iframeList" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes"></iframe>
    </div>
    
    <span style="position:absolute;top:5px;left:475px; font:0.7em sans-serif;font-weight:bold;color:#485a88;">RESULT DETIL</span>
        <img style="position:absolute;top:4px;left:548px;" src="../picture/arrow-315-medium.png"/>
        
    <div style="position:absolute; border: solid 1px #CCC; width:332px; height:467px; top:20px; left:475px; text-align:center; background-color:#FFF;">
        <div id="divDetailCari" style="text-align:left;margin:5px;border: solid 0px #CCC; height:457px;overflow-y: scroll;">&nbsp;</div>
    </div>
<?php
}
if($aksiGet == "gantiTahun")
{
	$statusGet = $_GET['status'];
	
	$disBtnCancel = "";
	$classBtnCancel = "btnStandar";
	if($statusGet == "awal")
	{
		$disBtnCancel = "disabled";
		$classBtnCancel = "btnStandarDis";
	}
?>
	<script language="javascript">
	function onClickTrYear(trId, year, bgColor)
	{
		var idTrSeb = document.getElementById('idTrYearSeb').value;
		var bgColorSeb = document.getElementById('bgColorYearSeb').value;
		
		if(idTrSeb != "")
		{
			document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
			document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor=bgColorSeb;	}
			document.getElementById(idTrSeb).style.fontWeight='';
			document.getElementById(idTrSeb).style.backgroundColor=bgColorSeb;
			document.getElementById(idTrSeb).style.cursor = 'pointer';	
			//document.getElementById(idTrSeb).style.height = "22";
			document.getElementById(idTrSeb).style.fontWeight=''; // FONT TIDAK BOLD UNTUK TD YANG DIPILIH
		}
		
		document.getElementById('tr'+year).onmouseout = '';
		document.getElementById('tr'+year).onmouseover ='';
		document.getElementById('tr'+year).style.fontWeight='bold';
		document.getElementById('tr'+year).style.backgroundColor='#B0DAFF';
		document.getElementById('tr'+year).style.cursor = 'default';
		//document.getElementById('tr'+year).style.fontSize='11px';
		document.getElementById('idTrYearSeb').value = 'tr'+year;
		//document.getElementById('tr'+trId).style.height = "";
	
		document.getElementById('bgColorYearSeb').value = bgColor;
		document.getElementById('yearProcess').value = year;
		document.getElementById('btnChoose').disabled = false;
		document.getElementById('btnChoose').className = 'btnStandar';
	}
	</script>
    
    <input type="hidden" id="idTrYearSeb" name="idTrYearSeb">
	<input type="hidden" id="bgColorYearSeb" name="bgColorYearSeb">

   <div style="position:inherit; border: solid 1px #CCC; width:100%; height:274px; text-align:center; background-color:#FFF;">
      <div style="position:relative; top:5px; font:1.2em sans-serif; line-height:30px; font-weight:bold;">Year to Process</div>
      <div style="position:relative; top:5px; left:5px; width:185px; height:185px;overflow:scroll;overflow-x: hidden;" class="tabelBorderAll">
          <table cellpadding="0" cellspacing="0" width="170" style="color:#096;font:bold 1.2em sans-serif;">
          <!--<tr bgcolor="" onMouseOver="this.style.backgroundColor='#D9EDFF';" onmouseout="this.style.backgroundColor='#FFFFFF';" style="cursor:pointer;" id="tr" onClick="">
              <td height="40" class="tabelBorderBottomJust"><?php echo $CPublic->tahunServer();?> </td>
          </tr>-->
		<?php
        $i = 0;
        $tabel = "";
        $tahunSekarang = "ADA";
        $query = $CKoneksiVoucher->mysqlQuery("SELECT DISTINCT(YEAR(datepaid)) AS tahun FROM tblvoucher WHERE deletests=0 ORDER BY datepaid DESC;", $CKoneksiVoucher->bukaKoneksi());
        while($row = $CKoneksiVoucher->mysqlFetch($query))
		{
			$i++;
			if($i == 1)
			{
				if($row['tahun'] != $CPublic->tahunServer())
				{
					$tabel.="<tr bgcolor=\"#FFFFFF\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onmouseout=\"this.style.backgroundColor='#FFFFFF';\" style=\"cursor:pointer;\" id=\"tr".$CPublic->tahunServer()."\" onClick=\"onClickTrYear('".$i."', '".$CPublic->tahunServer()."', '".$rowColor."')\">
			<td height=\"40\" class=\"tabelBorderBottomJust\">".$CPublic->tahunServer()."</td>
		</tr>";
					$tahunSekarang = "KOSONG"; 
				}
			}
			
			if($tahunSekarang == "KOSONG")
			{	$rowColor = $CPublic->rowColorCustom($i, "#F0F1FF", "#FFFFFF");	}
			else
			{	$rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");	}

             $tabel.="<tr bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onmouseout=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;\" id=\"tr".$row['tahun']."\" onClick=\"onClickTrYear('".$i."', '".$row['tahun']."', '".$rowColor."')\">
              <td height=\"40\" class=\"tabelBorderBottomJust\">".$row['tahun']."</td>
          </tr>";
          }
          
          echo $tabel;
          ?>
          </table>	
      </div>
      <div style="position:relative; top:10px;">
          <input type="hidden" id="yearProcess" name="yearProcess"/>
          <button class="<?php echo $classBtnCancel; ?>" id="" title="CANCEL" onclick="parent.cancelChooseYear();return false;" <?php echo $disBtnCancel; ?>>
              <table width="75" height="40">
              <tr>
                  <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                  <td align="left">CANCEL</td> 
              </tr>
              </table>
          </button>
          <button class="btnStandarDis" id="btnChoose" title="CHOOSE" onclick="parent.chooseYear($('#yearProcess').val())" disabled>
              <table width="77" height="40">
              <tr>
                  <td align="center" width="20"><img src="../picture/thumb-up.png"/></td>
                  <td align="left">CHOOSE</td> 
              </tr>
              </table>
          </button>
      </div>
   </div>
<?php	
}
?>

</center>
</body>
</HTML>