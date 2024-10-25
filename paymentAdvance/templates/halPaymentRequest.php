
<script type="text/javascript" src="js/paymentAdvance.js"></script>
<script type="text/javascript" src="../js/jquery-1.4.3.js"></script>

<link href="css/paymentAdvance.css" rel="stylesheet" type="text/css" />
<link href="css/button.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">

window.onload = function()
{   
    var userJenis = "{userJenis}";
    if(userJenis != "admin")
    {   document.oncontextmenu = function(){    return false;   }; }

    {disBtnPaymentReq}
    {disBtnPaymentConfirm}
    {disBtnPaymentCheck}
    {disBtnPaymentApprove}
    {disBtnPaymentRelease}
    {disBtnPaymentPrepare}
    {disBtnPaymentVoucher}
    {disBtnChangeFile}
}

function newData()
{
    $("#loaderImg").css('visibility','');
    setTimeout(function()
    {
        document.getElementById('hrefThickbox').href = "templates/halPaymentRequestForm.php?aksi=add&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=600&width=550&modal=true";

        $("#loaderImg").css('visibility','hidden');
        document.getElementById('hrefThickbox').click();
    },300);
}

function gantiBatchnoThnBln()
{
    disabledBtn('btnPayReqEdit');
    disabledBtn('btnPayReqDelete');
    disabledBtn('btnSubmitReq');
    disabledBtn('btnUploadFile');
    disabledBtn('btnSettlement');
    $("#loaderImg").css('visibility','');
    setTimeout(function()
    {
        var thnBln = $("#menuBatchnoThnBln").val();
        
        $.post('halPostPaymentRequest.php',
        { aksi:"batchnoChangeThnBln", thnBln : thnBln },
            function(data) 
            {
                $("#menuBatchnoTgl").empty();
                $("#menuBatchnoTgl").append(data);
                $("#loaderImg").css('visibility','hidden');

                var dayNya = $("#menuBatchnoTgl").val();
                $("#divKotakIframe").empty();

                var html = '<iframe width="100%" height="100%" src="templates/halPaymentRequestList.php?aksi=search&thnBln='+thnBln+'&dayNya='+dayNya+'" target="iframeList" name="iframeList" id="iframeList" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes"></iframe>';
                $("#divKotakIframe").append(html);
            },
        "json"
        );
    },300);
}

function gantiBatchnoTgl()
{
    disabledBtn('btnPayReqEdit');
    disabledBtn('btnPayReqDelete');
    disabledBtn('btnSubmitReq');
    disabledBtn('btnUploadFile');
    disabledBtn('btnSettlement');
    $("#loaderImg").css('visibility','');
    setTimeout(function()
    {
        var thnBln = $("#menuBatchnoThnBln").val();
        var dayNya = $("#menuBatchnoTgl").val();
        
        $("#loaderImg").css('visibility','hidden');
        $("#divKotakIframe").empty();

        var html = '<iframe width="100%" height="100%" src="templates/halPaymentRequestList.php?aksi=search&thnBln='+thnBln+'&dayNya='+dayNya+'" target="iframeList" name="iframeList" id="iframeList" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes"></iframe>';
        $("#divKotakIframe").append(html);
    },300);
}

function submitRequestNya()
{
    var cfm = confirm('Submit data..??');

    if(cfm)
    {
        $("#loaderImg").css('visibility','');
        $.post('halPostPaymentRequest.php',
        { aksi:"submitDataRequest", idSubmit:$('#txtIdEdit').val() },
            function(data) 
            {
                alert(data);
                $("#loaderImg").css('visibility','hidden');                
            },
        "json"
        );
        setTimeout(function()
        {
            reloadPage();
        },10000);
    }
}

function showBtnNya()
{
    enabledBtn('btnPayReqEdit');   
    enabledBtn('btnPayReqDelete');
    enabledBtn('btnSubmitReq');
    
    document.getElementById('btnPayReqEdit').disabled = false;
    document.getElementById('btnPayReqDelete').disabled = false;
    document.getElementById('btnSubmitReq').disabled = false;
    
    disabledBtn('btnUploadFile');
    disabledBtn('btnSettlement');
}

function uploadFile()
{
    var idEdit = $('#txtIdEdit').val();
    $("#loaderImg").css('visibility','');

    setTimeout(function()
    {
        document.getElementById('hrefThickbox').href = "templates/halPaymentRequestUploadFile.php?aksi=edit&idEdit="+idEdit+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=550&width=550&modal=true";

        $("#loaderImg").css('visibility','hidden');
        document.getElementById('hrefThickbox').click();
    },300);
}

function setSettlement()
{
    var idEdit = $('#txtIdEdit').val();
    $("#loaderImg").css('visibility','');

    setTimeout(function()
    {
        document.getElementById('hrefThickbox').href = "templates/halPaymentRequestSettlement.php?aksi=edit&idEdit="+idEdit+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=550&width=550&modal=true";

        $("#loaderImg").css('visibility','hidden');
        document.getElementById('hrefThickbox').click();
    },300);
}

function editData()
{
    var idEdit = $('#txtIdEdit').val();
    $("#loaderImg").css('visibility','');

    setTimeout(function()
    {
        document.getElementById('hrefThickbox').href = "templates/halPaymentRequestForm.php?aksi=edit&idEdit="+idEdit+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=600&width=550&modal=true";

        $("#loaderImg").css('visibility','hidden');
        document.getElementById('hrefThickbox').click();
    },300);
}

function showFormChangeFile()
{
    $("#loaderImg").css('visibility','');
    setTimeout(function()
    {
        document.getElementById('hrefThickbox').href = "templates/halChangeFile.php?aksi=changeFile&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=400&width=700&modal=true";

        $("#loaderImg").css('visibility','hidden');
        document.getElementById('hrefThickbox').click();
    },300);
}

function delData()
{
    var cfm = confirm('Yakin di Hapus..??');

    if(cfm)
    {
        $("#loaderImg").css('visibility','');
        setTimeout(function()
        {
            $.post('halPostPaymentRequest.php',
            { aksi:"delDataPaymentReq", idDel:$('#txtIdEdit').val() },
                function(data) 
                {
                    alert(data);
                    $("#loaderImg").css('visibility','hidden');
                    reloadPage();
                },
            "json"
            );
        },300);
    }
}

function reloadPage()
{
    $("#loaderImg").css('visibility','');
    setTimeout(function()
    {
        disabledBtn('btnPayReqEdit');
        disabledBtn('btnPayReqDelete');
        disabledBtn('btnSubmitReq');
        disabledBtn('btnUploadFile');
        disabledBtn('btnSettlement');
    
        var thnBln = $("#menuBatchnoThnBln").val();
        var dayNya = $("#menuBatchnoTgl").val();
        
        $("#loaderImg").css('visibility','hidden');
        $("#divKotakIframe").empty();
        
        var html = '<iframe width="100%" height="100%" src="templates/halPaymentRequestList.php?aksi=search&thnBln='+thnBln+'&dayNya='+dayNya+'" target="iframeList" name="iframeList" id="iframeList" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes"></iframe>';
        $("#divKotakIframe").append(html);
    },300);
}

</script>

<a class="thickbox" id="hrefThickbox"></a>

<div class="wrap">
	<div id="loaderImg" style="visibility:hidden;" class="pleaseWait">
    	<div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="picture/loading (115).gif" height="12"/>&nbsp;</div>
    </div>
  	<div class="namaAplikasi"> PAYMENT & ADVANCE </div>
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

        <button class="btnStandarTabPilih" id="" title="PAYMENT REQUEST" onclick="return false;">
            <table width="110" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/documents-stack.png"/></td>
                <td align="left">PAYMENT REQ.</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentConfirm" title="CONFIRM" onclick="$('#formConfirmPayment').submit();return false;">
            <table width="77" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/hourglass--pencil.png"/></td>
                <td align="left">CONFIRM</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentCheck" title="CHECK" onclick="$('#formCheckPayment').submit();return false;">
            <table width="77" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/tick.png"/></td>
                <td align="left">CHECK</td> 
            </tr>
            </table>
        </button>        
        <button class="btnStandar" id="btnPaymentApprove" title="APPROVE" onclick="$('#formApprovePayment').submit();return false;">
            <table width="77" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/document-task.png"/></td>
                <td align="left">APPROVE</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentRelease" title="RELEASE" onclick="$('#formReleasePayment').submit();return false;">
            <table width="81" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/thumb-up.png"/></td>
                <td align="left">RELEASE</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentPrepareForPayment" title="PREPARE VOUCHER" onclick="$('#formPrepareForPayment').submit();return false;">
            <table width="130" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/document--arrow.png"/> </td>
                <td align="left">PREPARE VOUCHER</td>
              </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentVoucher" title="VOUCHER" onclick="$('#formVoucher').submit();return false;">
            <table width="58" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/document-sticky-note.png"/> </td>
                <td align="left">VOUCHER</td>
              </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentVoucher" title="VOUCHER" onclick="$('#formSearchPayment').submit();return false;">
            <table width="58" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/Search-blue-32.png" style="width:20px;" /> </td>
                <td align="left">SEARCH</td>
              </tr>
            </table>
        </button>
    </div>
    
    <div class="kotakBtnTengah">
    	<input type="hidden" id="batchno" name="batchno" value="{batchno}"/>
        <input type="hidden" id="idMailInv" name="idMailInv"/>
    	<span class="fontSpanBatch">BATCHNO</span>&nbsp;
  		<select class="elementMenu" id="menuBatchnoThnBln" name="menuBatchnoThnBln" style="width:75px;height:26px;font-size:12px;background-color: #f5f5f5;" title="CHOOSE YEAR & MONTH" onchange="gantiBatchnoThnBln();return false;">
        {searchBatchNoThnBln}
        </select>
        <div id="idTdMenuTgl" style="position:absolute;top:0px;left:145px; width:60px;">
        <select class="elementMenu" id="menuBatchnoTgl" name="menuBatchnoTgl" style="width:45px;height:26px;font-size:12px;background-color: #f5f5f5;" title="CHOOSE DATE" onchange="gantiBatchnoTgl();return false;">
        {searchBatchNoDay}
        </select>
        </div>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |
        <button class="btnStandar" id="btnRefresh" title="Refresh" onclick="reloadPage();return false;">
            <table width="76" height="24">
            <tr>
                <td align="center" width="20"><img src="picture/arrow-circle-315.png"/></td>
                <td align="left">REFRESH</td> 
            </tr>
            </table>
        </button>
        <button id="btnPayReqNew" class="btnStandar" title="NEW DATA" onclick="newData();">
            <table width="51" height="24">
            <tr>
                <td align="center" width="20"><img src="picture/document--plus.png"/></td>
                <td align="left">NEW</td> 
            </tr>
            </table>
        </button>
        <button id="btnPayReqEdit" class="btnStandarDis" title="EDIT DATA" onclick="editData();" disabled="disabled">
            <table width="50" height="24" border="0">
              <tr>
                <td align="center" width="20"><img src="picture/document--pencil.png"/></td>
                <td align="left">EDIT</td>
              </tr>
            </table>
        </button>
        <button id="btnPayReqDelete" class="btnStandarDis" title="DELETE DATA" onclick="delData();" disabled="disabled">
            <table width="66" height="24">
              <tr>
                <td align="center" width="20"><img src="picture/document--minus.png"/></td>
                <td align="left">DELETE</td>
              </tr>
            </table>
        </button>
        <button id="btnSubmitReq" class="btnStandarDis" title="SUBMIT DATA" onclick="submitRequestNya();" disabled="disabled">
            <table width="51" height="24">
            <tr>
                <td align="center" width="20"><img style="width:15px;" src="picture/Button-Check-blue-48.png"/></td>
                <td align="left">SUBMIT</td> 
            </tr>
            </table>
        </button>
        <button id="btnUploadFile" class="btnStandarDis" title="UPLOAD FILE" onclick="uploadFile();" disabled="disabled">
            <table width="51" height="24">
            <tr>
                <td align="center" width="20"><img style="width:15px;" src="picture/Outbox-blue-32.png"/></td>
                <td align="left">UPLOAD</td> 
            </tr>
            </table>
        </button>
        <button id="btnSettlement" class="btnStandarDis" title="SETTLEMENT" onclick="setSettlement();" disabled="disabled">
            <table width="51" height="24">
            <tr>
                <td align="center" width="20"><img style="width:15px;" src="picture/Outbox-blue-32.png"/></td>
                <td align="left">SETTLEMENT</td> 
            </tr>
            </table>
        </button>
        <button id="btnChangeFile" class="btnStandarDis" title="CHANGE FILE" onclick="showFormChangeFile();" disabled="disabled">
            <table width="51" height="24">
            <tr>
                <td align="center" width="20"><img style="width:15px;" src="picture/Button-Synchronize-blue-32.png"/></td>
                <td align="left">CHANGE</td> 
            </tr>
            </table>
        </button>
        <input type="hidden" id="txtIdEdit" value="">
    </div>
    <div class="kotakIframe" id="divKotakIframe">        
        <iframe width="100%" height="100%" src="templates/halPaymentRequestList.php?aksi=display" target="iframeList" name="iframeList" id="iframeList" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes"></iframe>
    </div>

</div>