<!DOCTYPE HTML>
<?php
require_once("../configInvReg.php");

?>

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../../js/Parsers.js"></script>
<script type="text/javascript" src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../js/cariMail.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css"
    media="screen">
</LINK>
<link rel="stylesheet" type="text/css" href="../../css/archives.css">
<link href="../css/invReg.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/button.css" />
<link rel="stylesheet" type="text/css" href="../css/table.css" />

<style>
body {
    background-color: #f9f9f9;
}
</style>

<?php
?>
<style>
body {
    background-color: #f9f9f9;
}
</style>

<script>
window.onload = function() {
    setup();
    doneWait();
}
</script>

<div id="loaderImg" style="visibility:hidden;" class="pleaseWait3">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif"
            height="12" />&nbsp;</div>
</div>

<div style="position:absolute;right:10px;">
    <button class="btnStandar" onclick="tutup();">
        <table border="0" width="35" height="30">
            <tr>
                <td align="center"><img src="../picture/door-open-out.png" /></td>
            </tr>
        </table>
    </button>
</div>

<div id="idHalCariMailInv">
    <table cellpadding="0" cellspacing="0" width="850" height="489"
        style="font-family:sans-serif;font-weight:bold;color:#485a88;" border="0" align="center">
        <tr>
            <td colspan="2" height="30" align="center" valign="top" class=""><span style="font-size:16px;">SEARCH MAIL /
                    INVOICE</span></td>
        </tr>
        <tr>
            <td width="482" height="80" class="">
                <table cellpadding="0" cellspacing="0" width="476" height="80" class="tabelBorderAll"
                    style="font-size:11px;background-color:#FFF;">
                    <tr>
                        <td width="141" height="25" class="tabelBorderRightJust" style="border-style:dotted;">
                            &nbsp;<input type="radio" checked="checked" name="cariBerdasarkan"
                                id="cariBerdasarkan_senderVendor" value="senderVendor" />&nbsp;<span
                                onclick="$('#cariBerdasarkan_senderVendor').click();" style="cursor:pointer;">SENDER /
                                VENDOR</span>
                        </td>
                        <td width="95" class="tabelBorderRightJust" style="border-style:dotted;">
                            &nbsp;<input type="radio" name="cariBerdasarkan" id="cariBerdasarkan_company"
                                value="company" />&nbsp;<span onclick="$('#cariBerdasarkan_company').click();"
                                style="cursor:pointer;">COMPANY</span>
                        </td>
                        <td width="129" class="tabelBorderRightJust" style="border-style:dotted;">
                            &nbsp;<input type="radio" name="cariBerdasarkan" id="cariBerdasarkan_invDate"
                                value="invDate" />&nbsp;<span onclick="$('#cariBerdasarkan_invDate').click();"
                                style="cursor:pointer;">MAIL / INV. DATE</span>
                        </td>
                        <td width="109" height="22" class="tabelBorderAllNull" style="border-style:dotted;">
                            &nbsp;<input type="radio" name="cariBerdasarkan" id="cariBerdasarkan_entryDate"
                                value="entryDate" />&nbsp;<span onclick="$('#cariBerdasarkan_entryDate').click();"
                                style="cursor:pointer;">ENTRY DATE</span>
                        </td>
                    </tr>
                    <tr align="left">
                        <td colspan="2" class="tabelBorderBottomLeftNull" style="border-style:dotted;">
                            &nbsp;&nbsp;<textarea id="teksCari" class="elementInput" cols="39"
                                style="height:35px;"></textarea>
                        </td>
                        <td colspan="2" class="tabelBorderTopJust" style="border-style:dotted;">
                            &nbsp;&nbsp;&nbsp;<span
                                class="spanKalender">START</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="spanKalender">END</span><br>&nbsp;&nbsp;
                            <input type="text" name="startDate" id="startDate" class="elementInput"
                                style="width:60px;" />
                            <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date"
                                onclick="displayCalendar(document.getElementById('startDate'),'dd/mm/yyyy',this, '', '', '193', '183');"
                                id="imgStartDate" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="text" name="endDate" id="endDate" class="elementInput" style="width:60px;" />
                            <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date"
                                onclick="displayCalendar(document.getElementById('endDate'),'dd/mm/yyyy',this, '', '', '193', '183');"
                                id="imgEndDate" />
                        </td>
                    </tr>
                </table>
            </td>
            <td width="368" valign="bottom" class="">
                <div id="idErrorMsg" class="errorMsg" style="margin-left:0px;visibility:hidden;"><img
                        src="../picture/exclamation-red.png" />&nbsp;<span>&nbsp;aaa</span>&nbsp;</div>
                <button class="btnStandar" id="btnDoSearch" title="START SEARCHING" onclick="klikBtnDoSearch();">
                    <table width="92" height="28">
                        <tr>
                            <td align="center" width="20"><img src="../picture/magnifier.png" /></td>
                            <td align="left">DO SEARCH</td>
                        </tr>
                    </table>
                </button>
                <button class="btnStandarDis" id="btnView" title="START SEARCHING" onclick="klikBtnView();" disabled>
                    <table width="58" height="28">
                        <tr>
                            <td align="center" width="20"><img src="../picture/blue-document-list.png" /></td>
                            <td align="left">VIEW</td>
                        </tr>
                    </table>
                </button>
                <button class="btnStandarDis" id="btnBatchnoGroup" title="START SEARCHING"
                    onclick="klikBtnBatchnoGroup();" disabled>
                    <table width="120" height="28">
                        <tr>
                            <td align="center" width="20"><img src="../picture/document-view-thumbnail.png" /></td>
                            <td align="left">BATCHNO GROUP</td>
                        </tr>
                    </table>
                </button>
                <input type="hidden" id="batchno" name="batchno" />
                <input type="hidden" id="idMailInv" name="idMailInv" />
            </td>
        </tr>
        <tr>
            <td height="30"><span style="font-size:12px;vertical-align:bottom;">BATCHNO : <span
                        id="teksBatchno">&nbsp;</span></span></td>
            <td align="right"><span style="font-size:12px;vertical-align:bottom;">SORT BY : </span>
                <select id="sortBy" name="sortBy" class="elementMenu" style="width:150px;position:relative;"
                    onchange="klikBtnDoSearch();">
                    <option value="senVenAsc">SENDER/VENDOR (ASC)</option>
                    <option value="senVenDesc">SENDER/VENDOR (DESC)</option>
                    <option value="companyAsc">COMPANY (ASC)</option>
                    <option value="companyDesc">COMPANY (DESC)</option>
                    <option value="mailDateAsc">MAIL/INV. DATE (ASC)</option>
                    <option value="mailDateDesc">MAIL/INV. DATE (DESC)</option>
                    <option value="entryAsc">ENTRY (ASC)</option>
                    <option value="entryDesc">ENTRY (DESC)</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="tabelBorderAll" valign="top" colspan="2">
                <div class="kotakIframeCariIncoming">
                    <iframe width="100%" height="100%" src="../templates/halIncomingList.php" id="iframeList"
                        frameborder="0" marginwidth="0" marginheight="0" scrolling="yes"></iframe>
                </div>
                <!--
                <div class="divIframeCariInc">
                <table cellpadding="0" cellspacing="0" width="1550" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-size:11px;">
                <tr align="center">
                    <td width="42" rowspan="2" height="50" class="">NO</td>
                    <td width="258" rowspan="2" class="">SENDER / VENDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td colspan="2" class="tabelBorderBottomJust">ADRESSEE</td>
                    <td width="82" rowspan="2" class="">BARCODE</td>
                    <td colspan="2" class="tabelBorderBottomJust">MAIL / INVOICE</td>
                    <td width="144" rowspan="2" class="">NUMBER</td>
                    <td width="133" rowspan="2" class="">AMOUNT</td>
                    <td width="355" rowspan="2" class="">REMARK</td>
                </tr>
                <tr align="center">
                    <td width="160" class="">COMPANY</td>
                    <td width="215" class="">UNIT</td>
                    <td width="82" class="">DATE</td>
                    <td width="79" class="">DUE DATE</td>
                </tr>
                <tr>
                    <td height="300" colspan="10" bgcolor="#FFFFFF" style="color:#FFF">
                        
                    </td>
                </tr>
                </table>
                </div>
                -->

            </td>
        </tr>
    </table>
</div>

<script>
<?php
if($tutupWindow == "tidak")
{
	//echo "parent.openThickboxWindow('', 'newAct')";
}
if($tutupWindow == "ya")
{
	echo "tutup();";
}
?>
</script>

</HTML>