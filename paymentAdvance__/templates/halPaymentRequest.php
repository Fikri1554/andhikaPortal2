<link href="css/paymentAdvance.css" rel="stylesheet" type="text/css" />
<link href="css/button.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function newData() {
    var batchno = $('#batchno').val();
    document.getElementById('hrefThickbox').href =
        "templates/halPaymentRequestAdd.php?aksi=tambahMailInv&batchno=&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=550&width=550&modal=true";

    document.getElementById('hrefThickbox').click();
}
</script>

<a class="thickbox" id="hrefThickbox"></a>

<div class="wrap">
    <div id="loaderImg" style="visibility:hidden;" class="pleaseWait">
        <div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="picture/loading (115).gif"
                height="12" />&nbsp;</div>
    </div>
    <div class="namaAplikasi"> PAYMENT & ADVANCE </div>
</div>

<div class="kotakPaymentAdv">

    <div class="kotakBtnAtas">
        <form method="post" action="index.php" id="formPaymentReq">
            <input type="hidden" id="halaman" name="halaman" value="halPaymentRequest" />
        </form>

        <button class="btnStandarTabPilih" id="btnPaymentReq" title="PAYMENT REQUEST"
            onclick="$('#formPaymentReq').submit();return false;">
            <table width="110" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/documents-stack.png" /></td>
                    <td align="left">PAYMENT REQ.</td>
                </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentCheck" title="CHECK" onclick="">
            <table width="77" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/tick.png" /></td>
                    <td align="left">CHECK</td>
                </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentApprove" title="APPROVE" onclick="">
            <table width="77" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/hourglass--pencil.png" /></td>
                    <td align="left">APPROVE</td>
                </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentRelease" title="RELEASE" onclick="">
            <table width="81" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/thumb-up.png" /></td>
                    <td align="left">RELEASE</td>
                </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentPrepareForPayment" title="PREPARE FOR PAYMENT" onclick="">
            <table width="150" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/document--arrow.png" /> </td>
                    <td align="left">PREPARE FOR PAYMENT</td>
                </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentVoucher" title="VOUCHER" onclick="">
            <table width="58" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/document-sticky-note.png" /> </td>
                    <td align="left">VOUCHER</td>
                </tr>
            </table>
        </button>
    </div>

    <div class="kotakBtnTengah">
        <input type="hidden" id="batchno" name="batchno" value="{batchno}" />
        <input type="hidden" id="idMailInv" name="idMailInv" />
        <span class="fontSpanBatch">BATCHNO</span>&nbsp;
        <select class="elementMenu" id="menuBatchnoThnBln" name="menuBatchnoThnBln"
            style="width:75px;height:26px;font-size:12px;background-color: #f5f5f5;" title="CHOOSE YEAR & MONTH"
            onchange="rubahMenuBatchnoThnBln();return false;">

        </select>
        <div id="idTdMenuTgl" style="position:absolute;top:0px;left:145px; width:60px;">
            <select class="elementMenu" id="menuBatchnoTgl" name="menuBatchnoTgl"
                style="width:45px;height:26px;font-size:12px;background-color: #f5f5f5;" title="CHOOSE DATE"
                onchange="rubahMenuBatchnoTgl();return false;">

            </select>
        </div>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |
        <button class="btnStandar" id="btnRefresh" title="" onclick="klikBtnRefresh();return false;">
            <table width="76" height="24">
                <tr>
                    <td align="center" width="20"><img src="picture/arrow-circle-315.png" /></td>
                    <td align="left">REFRESH</td>
                </tr>
            </table>
        </button>
        <button id="btnPayReqNew" class="btnStandar" title="NEW INCOMING MAIL / INVOICE"
            onclick="newData();return false;">
            <table width="51" height="24">
                <tr>
                    <td align="center" width="20"><img src="picture/document--plus.png" /></td>
                    <td align="left">NEW</td>
                </tr>
            </table>
        </button>
        <button id="btnPayReqEdit" class="btnStandarDis" title="EDIT INCOMING MAIL / INVOICE"
            onclick="openThickboxWindow('klikBtnEdit');return false;" disabled>
            <table width="50" height="24" border="0">
                <tr>
                    <td align="center" width="20"><img src="picture/document--pencil.png" /> </td>
                    <td align="left">EDIT</td>
                </tr>
            </table>
        </button>
        <button id="btnPayReqDelete" class="btnStandarDis" title="DELETE INCOMING MAIL / INVOICE"
            onclick="klikBtnDelete(); return false;" disabled>
            <table width="66" height="24">
                <tr>
                    <td align="center" width="20"><img src="picture/document--minus.png" /> </td>
                    <td align="left">DELETE</td>
                </tr>
            </table>
        </button>
    </div>

    <div class="kotakSortBy">
        <table cellpadding="0" cellspacing="0" style="font-family:sans-serif;font-weight:bold;color:#485a88;">
            <tr>
                <td align="right"><span style="font-size:12px;vertical-align:bottom;">SORT BY : </span>
                    <select id="sortBy" name="sortBy" class="elementMenu"
                        style="width:130px;position:relative;color:#485a88;" onchange="klikBtnDisplay();">
                        <option value="entryDate">SNO</option>
                        <option value="barcode">BARCODE</option>
                        <option value="company">COMPANY</option>
                        <option value="senVenName">SENDER/VENDOR NAME</option>
                        <option value="mailDate">MAIL/INV. DATE</option>
                    </select>
                    <select id="ascBy" name="ascBy" class="elementMenu"
                        style="width:55px;position:relative;color:#485a88;" onchange="klikBtnDisplay();">
                        <option value="asc">ASC</option>
                        <option value="desc">DESC</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>


    <div class="kotakIframe">
        <!-- <iframe width="100%" height="100%" src="templates/halIncomingList.php" target="iframeList" name="iframeList" id="iframeList" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes"></iframe> -->
    </div>

</div>