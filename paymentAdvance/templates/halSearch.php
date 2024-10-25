<script type="text/javascript" src="js/paymentAdvance.js"></script>
<script type="text/javascript" src="../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../js/masks.js"></script>
<script type="text/javascript" src="../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>

<link href="css/paymentAdvance.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css"
    media="screen">
</LINK>
<link href="css/button.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
window.onload = function() {
    {
        disBtnPaymentReq
    } {
        disBtnPaymentConfirm
    } {
        disBtnPaymentCheck
    } {
        disBtnPaymentApprove
    } {
        disBtnPaymentRelease
    } {
        disBtnPaymentPrepare
    } {
        disBtnPaymentVoucher
    }
}

$(window).scroll(function() {
    $('#judul').css('left', '-' + $(window).scrollLeft() + 'px');
    $('#judul1').css('left', '-' + $(window).scrollLeft() + 'px');
    $('#judul2').css('left', '-' + $(window).scrollLeft() + 'px');
});

function searchData() {
    var typeSearch = $("#slcSearchMethod").val();
    var txtSearch = $("#txtSearch").val();
    var sDate = $("#startDate").val();
    var eDate = $("#endDate").val();

    $("#loaderImg").css('visibility', '');

    $.post('halPostPaymentRequest.php', {
            aksi: "getSearchData",
            typeSearch: typeSearch,
            txtSearch: txtSearch,
            sDate: sDate,
            eDate: eDate
        },
        function(data) {
            $("#idBody").empty();
            $("#idBody").append(data);
            $("#loaderImg").css('visibility', 'hidden');
        },
        "json"
    );
}

function onClickNya(idTr, idPaymentAdv, color) {
    var idTrSblm = document.getElementById('txtIdTrHid').value;

    if (idTrSblm != "") {
        document.getElementById(idTrSblm).style.backgroundColor = '';
        document.getElementById(idTrSblm).style.fontSize = '10px';
    }

    document.getElementById('tr_' + idTr).style.backgroundColor = '#B0DAFF';
    document.getElementById('tr_' + idTr).style.fontSize = '11px';
    document.getElementById('txtIdTrHid').value = 'tr_' + idTr;

    $("#loaderImg").css('visibility', '');

    $.post('halPostPaymentRequest.php', {
            aksi: "getSearchDataDetail",
            idPaymentAdv: idPaymentAdv
        },
        function(data) {
            $("#idBodyDetail").empty();
            $("#idBodyDetail").append(data);
            $("#loaderImg").css('visibility', 'hidden');
        },
        "json"
    );

}

function reloadPage() {
    $('#formVoucher').submit();
}
</script>

<a class="thickbox" id="hrefThickbox"></a>

<div class="wrap">
    <div id="loaderImg" style="visibility:hidden;" class="pleaseWait">
        <div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="picture/loading (115).gif"
                height="12" />&nbsp;</div>
    </div>
    <div class="namaAplikasi"> SEARCH </div>
</div>

<div class="kotakPaymentAdv">

    <div class="kotakBtnAtas">
        <form method="post" action="index.php" id="formPaymentReq">
            <input type="hidden" id="halaman" name="halaman" value="halPaymentRequest" />
        </form>
        <form method="post" action="index.php" id="formConfirmPayment">
            <input type="hidden" id="halaman" name="halaman" value="halConfirmPayment" />
        </form>
        <form method="post" action="index.php" id="formCheckPayment">
            <input type="hidden" id="halaman" name="halaman" value="halCheckPayment" />
        </form>
        <form method="post" action="index.php" id="formApprovePayment">
            <input type="hidden" id="halaman" name="halaman" value="halApprovePayment" />
        </form>
        <form method="post" action="index.php" id="formReleasePayment">
            <input type="hidden" id="halaman" name="halaman" value="halReleasePayment" />
        </form>
        <form method="post" action="index.php" id="formPrepareForPayment">
            <input type="hidden" id="halaman" name="halaman" value="halPrepareForPayment" />
        </form>
        <form method="post" action="index.php" id="formVoucher">
            <input type="hidden" id="halaman" name="halaman" value="halVoucher" />
        </form>
        <form method="post" action="index.php" id="formSearchPayment">
            <input type="hidden" id="halaman" name="halaman" value="halSearch" />
        </form>

        <button class="btnStandar" id="btnPaymentReq" title="PAYMENT REQUEST"
            onclick="$('#formPaymentReq').submit();return false;">
            <table width="110" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/documents-stack.png" /></td>
                    <td align="left">PAYMENT REQ.</td>
                </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentConfirm" title="CONFIRM"
            onclick="$('#formConfirmPayment').submit();return false;">
            <table width="77" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/hourglass--pencil.png" /></td>
                    <td align="left">CONFIRM</td>
                </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentCheck" title="CHECK"
            onclick="$('#formCheckPayment').submit();return false;">
            <table width="77" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/tick.png" /></td>
                    <td align="left">CHECK</td>
                </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentApprove" title="APPROVE"
            onclick="$('#formApprovePayment').submit();return false;">
            <table width="77" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/document-task.png" /></td>
                    <td align="left">APPROVE</td>
                </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentRelease" title="RELEASE"
            onclick="$('#formReleasePayment').submit();return false;">
            <table width="81" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/thumb-up.png" /></td>
                    <td align="left">RELEASE</td>
                </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentPrepareForPayment" title="PREPARE VOUCHER"
            onclick="$('#formPrepareForPayment').submit();return false;">
            <table width="130" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/document--arrow.png" /> </td>
                    <td align="left">PREPARE VOUCHER</td>
                </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentVoucher" title="VOUCHER"
            onclick="$('#formVoucher').submit();return false;">
            <table width="58" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/document-sticky-note.png" /> </td>
                    <td align="left">VOUCHER</td>
                </tr>
            </table>
        </button>
        <button class="btnStandarTabPilih" id="btnPaymentVoucher" title="VOUCHER"
            onclick="$('#formSearchPayment').submit();return false;">
            <table width="58" height="28">
                <tr>
                    <td align="center" width="20"><img src="picture/Search-blue-32.png" style="width:20px;" /> </td>
                    <td align="left">SEARCH</td>
                </tr>
            </table>
        </button>
    </div>

    <div class="kotakIframe" id="divKotakIframe" style="border-style:dotted;margin-top:-30px;height:483px;">
        <div id="divIframeList" style="position:inherit;border:solid 1px #CCC;text-align:left;width:35%;">
            <input type="hidden" id="txtIdTrHid" value="">
            <table style="width: 100%;font-size: 13px;">
                <tr>
                    <td rowspan="2">&nbsp;SEARCH BY :</td>
                    <td>
                        <select id="slcSearchMethod" class="elementMenu" style="width:100%;">
                            <option value="barcode">BARCODE</option>
                            <option value="batchno">BATCHNO</option>
                            <option value="company">COMPANY / BILIING</option>
                            <option value="invDate">INV. DATE</option>
                            <option value="invNo">INV. NO</option>
                            <option value="remark">REMARK</option>
                            <option value="reqName">REQUEST NAME</option>
                            <option value="unit">UNIT / DIVISION</option>
                            <option value="complete">COMPLETED</option>
                            <option value="notComplete">NOT COMPLETED</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;">
                        <input type="text" id="txtSearch" class="elementInput" style="width:95%;height:20px;" value=""
                            placeholder="Text search">
                    </td>
                </tr>
                <tr>
                    <td height="23" class="">&nbsp;START</td>
                    <td class="elementTeks">
                        <input type="text" name="startDate" id="startDate" class="elementInput" style="width:80px;"
                            onfocus="disableBtnPrint();" />&nbsp;<img src="./../picture/calendar.gif"
                            class="gayaKalender" title="Select Date"
                            onclick="displayCalendar(document.getElementById('startDate'),'yyyy-mm-dd',this, '', '', '193', '183');"
                            id="imgStartDate" />&nbsp;<span class="spanKalender">( YYYY-MM-DD )</span>
                    </td>
                </tr>
                <tr valign="middle">
                    <td height="23" class="">&nbsp;END</td>
                    <td class="elementTeks" id="">
                        <input type="text" name="endDate" id="endDate" class="elementInput" style="width:80px;"
                            onfocus="disableBtnPrint();" />&nbsp;<img src="./../picture/calendar.gif"
                            class="gayaKalender" title="Select Date"
                            onclick="displayCalendar(document.getElementById('endDate'),'yyyy-mm-dd',this, '', '', '193', '183');"
                            id="imgEndDate" />&nbsp;<span class="spanKalender">( YYYY-MM-DD )</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top:5px;" align="right">
                        <button class="btnStandar" id="btnSearch" title="START SEARCHING" onclick="searchData();">
                            <table width="70" height="23">
                                <tr>
                                    <td align="center" width="20"><img src="picture/magnifier.png" /></td>
                                    <td align="right">SEARCH</td>
                                </tr>
                            </table>
                        </button>
                        <button class="btnStandar" id="btnRefresh" title=""
                            onclick="$('#formSearchPayment').submit();return false;">
                            <table width="70" height="23">
                                <tr>
                                    <td align="center" width="20"><img src="picture/arrow-circle-315.png" /></td>
                                    <td align="left">REFRESH</td>
                                </tr>
                            </table>
                        </button>
                    </td>
                </tr>
            </table>
            <input type="text" id="txtCariPrep" class="elementInput"
                style="vertical-align:bottom;width:100px;display:none;" oninput="searchDataNya();"
                placeholder="Trans No">
            <div style="width:100%;overflow-x:auto;white-space:nowrap;height:335px;margin-top:5px;">
                <table id="judul" cellpadding="0" cellspacing="0" style="width:450px;">
                    <thead>
                        <tr
                            style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;">
                            <td width="150" style="height:30px;vertical-align:middle;text-align:center;">REQ.NAME</td>
                            <td width="200" style="height:30px;vertical-align:middle;text-align:center;">COMPANY</td>
                            <td width="100" style="height:30px;vertical-align:middle;text-align:center;">BARCODE</td>
                        </tr>
                    </thead>
                    <tbody id="idBody">
                    </tbody>
                </table>
            </div>
        </div>
        <div style="position:inherit;border:solid 0px #CCC;left:36%;text-align:left;width:64%;">
            <span style="position:inherit;left:5px; font:0.7em sans-serif;font-weight:bold;color:#485a88;">RESULT
                DETIL</span>
            <img style="position:inherit;left:80px;" src="picture/arrow-315-medium.png" />
            <div style="overflow-x:auto; border: solid 1px #CCC; background-color:#FFF;margin-top: 20px;height:461px;">
                <table style="width:610px;" class="tabelDetailCari" id="idBodyDetail">
                </table>
            </div>

        </div>
    </div>

</div>