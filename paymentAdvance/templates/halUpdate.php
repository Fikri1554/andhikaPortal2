<!DOCTYPE HTML>
<script type="text/javascript" src="../js/jquery-1.8.0.js"></script>
<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../../js/Parsers.js"></script>
<script type="text/javascript" src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../js/masks.js"></script>
<script type="text/javascript" src="../js/paymentAdvance.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css"
    media="screen">
</LINK>
<link rel="stylesheet" type="text/css" href="../css/paymentAdvance.css">
<link rel="stylesheet" type="text/css" href="../css/button.css" />
<link rel="stylesheet" type="text/css" href="../css/table.css" />

<script type="text/javascript">

    function showFormUpdate

</script>
<style>
body {
    background-color: #f9f9f9;
}

#TB_window {
    margin-top: -200px;
}
</style>

<div id="loaderImg" style="display:none;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;&nbsp;Please Wait...&nbsp;<img
            src="../picture/loading (115).gif" height="12" />&nbsp;</div>
</div>

<div id="idHalTambahMailInv" onclick="$('#autoCompSender').css('display','none');">
    <table id="idTblFormSearch" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;width:100%;">
        <tr>
            <td align="right" height="30" colspan="3"><span style="font-size:16px;"><i><u>:: CHANGE ::</u></i></span></td>
        </tr>
        <tr>
            <td style="width:15%;padding:2px;">SEARCH DATA</td>
            <td style="width:50%;padding:2px;">
                <input type="text" id="txtSearch" class="elementInput" value="" placeholder="Barcode No"
                    style="width:100%;height:20px;" />
            </td>
            <td style="width:35%;padding:2px;padding-left: 10px;">
                <button class="btnStandar" onclick="searchData();return false;">
                    <table width="53" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/magnifier.png" /></td>
                            <td align="left">SEARCH</td>
                        </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="reloadPageChangeFile();">
                    <table border="0" width="63" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/door-open-out.png" /></td>
                            <td align="left">CLOSE</td>
                        </tr>
                    </table>
                </button>
            </td>
        </tr>
    </table>

    <table id="idTblDataSearch" width="1000" cellpadding="3" cellspacing="0">
        <thead>
            <tr align="center"
                style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;left:0px;top:0px;">
                <td width="30" style="height:30px;vertical-align:middle;text-align:center;">#</td>
                <td width="100" style="height:30px;vertical-align:middle;text-align:center;">REQ. NAME</td>
                <td width="70" style="height:30px;vertical-align:middle;text-align:center;">BARCODE</td>
                <td width="150" style="height:30px;vertical-align:middle;text-align:center;">COMPANY</td>
                <td width="150" style="height:30px;vertical-align:middle;text-align:center;">DIVISI</td>
                <td width="80" style="height:30px;vertical-align:middle;text-align:center;">AMOUNT</td>
                <td width="100" style="height:30px;vertical-align:middle;text-align:center;">INV. NUMBER</td>
            </tr>
        </thead>
        <tbody id="idBody" style="font-family:Arial;font-size:10px;"></tbody>
    </table>

    <table id="idTblFormUpload" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;width:700px;display:none;">
        <tr>
            <td align="right" height="30" colspan="4"><span style="font-size:16px;"><i><u>:: FORM FILE ::</u></i></span>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <table style="width:100%;font-size:12px;">
                    <tr>
                        <td height="20" style="width:70%;vertical-align:top;">
                            Company : <label style="color:#000;font-size:11px;" id="lblCompany"></label>
                        </td>
                        <td height="20" colspan="2" style="width:30%;vertical-align:top;">
                            Barcode : <label style="color:#000;font-size:11px;" id="lblBarcode"></label>
                        </td>
                    </tr>
                    <tr>
                        <td height="20" style="width:70%;vertical-align:top;">
                            Divisi : <label style="color:#000;font-size:11px;" id="lblDivisi"></label>
                        </td>
                        <td height="20" colspan="2" style="width:30%;vertical-align:top;">
                            Amount : <label style="color:#000;font-size:11px;" id="lblAmount"></label>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="width:15%;padding:2px;">UPLOAD FILE</td>
            <td style="width:15%;padding:2px;">
                <select id="slcTypeFile" class="elementMenu" style="width:100px;">
                    <option value="pengajuan">Pengajuan</option>
                    <option value="buktitransfer">Bukti Transfer</option>
                    <option value="settlement">Settlement</option>
                    <option value="uploadSettlement">Upload Settlement FInance</option>
                </select>
            </td>
            <td style="width:45%;padding:2px;">
                <input type="file" name="fileUploadChange" id="fileUploadChange" class="btnStandar" style="width:250px"
                    title="File Upload">
                &nbsp <a style="cursor:pointer;" onclick="$('#fileUploadChange').val('');"> Clear </a>
            </td>
            <td style="width:35%;padding:2px;padding-left: 10px;">
                <input type="hidden" id="txtIdPaymentAdv" value="">
                <button class="btnStandar" onclick="saveUpload();return false;" title="UPLOAD FILE">
                    <table width="53" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/btnUp.png" / style="width: 20px;"></td>
                            <td align="left">UPLOAD</td>
                        </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="reloadPageChangeFile();" title="CLOSE">
                    <table border="0" width="63" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/door-open-out.png" /></td>
                            <td align="left">CLOSE</td>
                        </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="backPage();" title="BACK">
                    <table border="0" width="147" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/arrow-return-180-left.png" /></td>
                            <td align="left">BACK</td>
                        </tr>
                    </table>
                </button>
            </td>
        </tr>
    </table>
</div>

</HTML>