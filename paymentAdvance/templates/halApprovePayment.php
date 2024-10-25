<script type="text/javascript" src="js/paymentAdvance.js"></script>
<script type="text/javascript" src="../js/jquery-1.4.3.js"></script>

<link href="css/paymentAdvance.css" rel="stylesheet" type="text/css" />
<link href="css/button.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
window.onload = function() {
    var userJenis = "{userJenis}";
    if (userJenis != "admin") {
        document.oncontextmenu = function() {
            return false;
        };
    }

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
});

function approveData() {
    var dtChecked = [];
    var typeAprvArr = [];

    $(':checkbox:checked').each(function(i) {
        dtChecked[i] = $(this).val();
        typeAprvArr[i] = $('#txtTypeDataApprove_' + dtChecked[i]).val();
    });

    $("#loaderImg").css('visibility', '');
    setTimeout(function() {
        var idChecked = dtChecked;

        $.post('halPostPaymentRequest.php', {
                aksi: "approveData",
                idChecked: idChecked,
                typeAprvArr: typeAprvArr
            },
            function(data) {
                alert(data);
                reloadPage();
            },
            "json"
        );
    }, 300);
}

function rejectData() {
    var dtChecked = [];
    var dtCheckedApprove = [];

    $('.chkApprove:checked').each(function(i) {
        dtChecked[i] = $(this).val();
    });

    $('.chkApproveSettle:checked').each(function(i) {
        dtCheckedApprove[i] = $(this).val();
    });

    // $(':checkbox:checked').each(function(i){
    //     dtChecked[i] = $(this).val();
    // });

    $("#loaderImg").css('visibility', '');
    setTimeout(function() {
        document.getElementById('hrefThickbox').href = "templates/halRejectData.php?aksi=display&idEdit=" +
            dtChecked + "&idEditSettle=" + dtCheckedApprove +
            "&rejectName=approve&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=200&width=900&modal=true";

        $("#loaderImg").css('visibility', 'hidden');
        document.getElementById('hrefThickbox').click();
    }, 300);
}

function gantiBatchnoThnBln() {
    $("#loaderImg").css('visibility', '');
    setTimeout(function() {
        var thnBln = $("#menuBatchnoThnBln").val();

        $.post('halPostPaymentRequest.php', {
                aksi: "batchnoChangeThnBln",
                thnBln: thnBln
            },
            function(data) {
                $("#menuBatchnoTgl").empty();
                $("#menuBatchnoTgl").append(data);

                var dayNya = $("#menuBatchnoTgl").val();
                reloadPage();
            },
            "json"
        );
    }, 300);
}

function gantiBatchnoTgl() {
    $("#loaderImg").css('visibility', '');
    setTimeout(function() {
        reloadPage();
    }, 300);
}

function onlickNya(idTr, id, checkNya) {
    if (checkNya == "") {
        $("#txtIdEdit").val("");
        $('[id^=idTr_]').css('background-color', '#FFFFFF');
        disabledBtn("btnApprove");
        disabledBtn("btnReject");
    } else {
        $('[id^=idTr_]').css('background-color', '#FFFFFF');
        document.getElementById('idTr_' + idTr).onmouseout = '';
        document.getElementById('idTr_' + idTr).onmouseover = '';
        document.getElementById('idTr_' + idTr).style.backgroundColor = '#B0DAFF';

        document.getElementById('txtIdEdit').value = id;

        enabledBtn("btnApprove");
        enabledBtn("btnReject");

        var dtChecked = [];
        $(':checkbox:checked').each(function(i) {
            dtChecked[i] = $(this).val();
        });
        if (dtChecked.length != "0") {
            enabledBtn("btnApprove");
            enabledBtn("btnReject");
        } else {
            disabledBtn("btnApprove");
            disabledBtn("btnReject");
        }
    }
}

function reloadPage() {
    disabledBtn("btnApprove");
    disabledBtn("btnReject");
    $("#loaderImg").css('visibility', '');
    var thnBln = $("#menuBatchnoThnBln").val();
    var tgl = $("#menuBatchnoTgl").val();
    $("#txtIdEdit").val("");

    setTimeout(function() {
        $.post('halPostPaymentRequest.php', {
                aksi: "getDataApprove",
                thnBln: thnBln,
                tgl: tgl
            },
            function(data) {
                $("#idBody").empty();
                $("#idBody").append(data);

                $("#loaderImg").css('visibility', 'hidden');
            },
            "json"
        );
    }, 300);
}
</script>

<a class="thickbox" id="hrefThickbox"></a>

<div class="wrap">
    <div id="loaderImg" style="visibility:hidden;" class="pleaseWait">
        <div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="picture/loading (115).gif"
                height="12" />&nbsp;</div>
    </div>
    <div class="namaAplikasi"> APPROVE PAYMENT </div>
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
        <button class="btnStandarTabPilih" id="" title="APPROVE" onclick="return false;">
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
    </div>

    <div class="kotakBtnTengah">
        <span class="fontSpanBatch">BATCHNO</span>&nbsp;
        <select class="elementMenu" id="menuBatchnoThnBln" name="menuBatchnoThnBln"
            style="width:75px;height:26px;font-size:12px;background-color: #f5f5f5;" title="CHOOSE YEAR & MONTH"
            onchange="gantiBatchnoThnBln();return false;">
            {searchBatchNoThnBln}
        </select>
        <div id="idTdMenuTgl" style="position:absolute;top:0px;left:145px; width:60px;">
            <select class="elementMenu" id="menuBatchnoTgl" name="menuBatchnoTgl"
                style="width:45px;height:26px;font-size:12px;background-color: #f5f5f5;" title="CHOOSE DATE"
                onchange="gantiBatchnoTgl();return false;">
                {searchBatchNoDay}
            </select>
        </div>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |
        <button class="btnStandar" id="btnRefresh" title="Refresh" onclick="reloadPage();return false;">
            <table width="76" height="24">
                <tr>
                    <td align="center" width="20"><img src="picture/arrow-circle-315.png" /></td>
                    <td align="left">REFRESH</td>
                </tr>
            </table>
        </button>
        <button class="btnStandarDis" id="btnApprove" title="Approve Data" onclick="approveData();return false;"
            disabled="disabled">
            <table width="100" height="24">
                <tr>
                    <td align="center" width="20"><img src="picture/document-task.png" /></td>
                    <td align="left">APPROVE</td>
                </tr>
            </table>
        </button>
        <button class="btnStandarDis" id="btnReject" title="Approve Data" onclick="rejectData();return false;"
            disabled="disabled">
            <table width="100" height="24">
                <tr>
                    <td align="center" width="20"><img src="picture/cross.png" /></td>
                    <td align="left">REJECT</td>
                </tr>
            </table>
        </button>
        <input type="hidden" id="txtIdEdit" value="">
    </div>
    <div class="kotakIframe" id="divKotakIframe">
        <div style="width:100%;overflow-x:auto;white-space:nowrap;height:100%;">
            <table id="judul" width="1900" cellpadding="0" cellspacing="0" style="">
                <thead>
                    <tr
                        style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;">
                        <td width="40" style="height:30px;vertical-align:middle;text-align:center;" colspan="2">NO</td>
                        <td width="200" style="height:30px;vertical-align:middle;text-align:center;">COMPANY</td>
                        <td width="150" style="height:30px;vertical-align:middle;text-align:center;">REQ. NAME</td>
                        <td width="130" style="height:30px;vertical-align:middle;text-align:center;">AMOUNT</td>
                        <td width="100" style="height:30px;vertical-align:middle;text-align:center;">BARCODE</td>
                        <td width="200" style="height:30px;vertical-align:middle;text-align:center;">DIVISI</td>
                        <td width="100" style="height:30px;vertical-align:middle;text-align:center;">INV. DATE</td>
                        <td width="150" style="height:30px;vertical-align:middle;text-align:center;">INV. NUMBER</td>
                        <td width="335" style="height:30px;vertical-align:middle;text-align:center;">REMARK</td>
                    </tr>
                </thead>
                <tbody id="idBody">
                    {getDataApprove}
                </tbody>
            </table>
        </div>
    </div>

</div>