
<script type="text/javascript" src="js/paymentAdvance.js"></script>
<script type="text/javascript" src="../js/jquery-1.4.3.js"></script>

<link href="css/paymentAdvance.css" rel="stylesheet" type="text/css" />
<link href="css/button.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
var idPaymenAll = [];

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
}

$(window).scroll(function(){
    $('#judul').css('left','-'+$(window).scrollLeft()+'px');
    $('#judul1').css('left','-'+$(window).scrollLeft()+'px');
    $('#judul2').css('left','-'+$(window).scrollLeft()+'px');
});

function onclickNya(idTr,id,typeNya)
{
    $('[id^=idTr_]').css('background-color','#FFFFFF');
    $('[id^=idTrSet_]').css('background-color','#E5CDFC');
    document.getElementById('idTr_'+idTr).onmouseout = '';
    document.getElementById('idTr_'+idTr).onmouseover ='';
    document.getElementById('idTr_'+idTr).style.backgroundColor='#B0DAFF';

    document.getElementById('txtIdPayment').value = id;
    $("#lblRemarkRelease").text("");

    enabledBtn("btnPayPrepAddGroup");    
    viewKeterangan(id,'');
}

function onclickSettlement(idTr,id,typeNya)
{
    $('[id^=idTr_]').css('background-color','#FFFFFF');
    $('[id^=idTrSet_]').css('background-color','#E5CDFC');
    document.getElementById('idTrSet_'+idTr).onmouseout = '';
    document.getElementById('idTrSet_'+idTr).onmouseover ='';
    document.getElementById('idTrSet_'+idTr).style.backgroundColor='#B0DAFF';

    document.getElementById('txtIdPayment').value = id;
    document.getElementById('txtTypeDocTemp').value = typeNya;

    enabledBtn("btnPayPrepAddGroup");    
    viewKeterangan(id,typeNya);
}

function klikBtnAddGroup()
{
    var txtIdPayment = $("#txtIdPayment").val();
    var txtTypeDoc = $("#txtTypeDocTemp").val();

    if(idPaymenAll.length > 0)
    {
        var cekId = "";
        for (var lan=0; lan < idPaymenAll.length; lan++)
        {
            if (idPaymenAll[lan] === txtIdPayment)
            {
                alert("Data Already exist..!!");
                cekId = idPaymenAll[lan];
            }
        }
        if(cekId != "")
        {
            return false;
        }
    }

    $("#loaderImg").css('visibility','');

    setTimeout(function()
    {
        var txtCompanyTemp = $("#txtCompanyTemp").val();
        var jmlhTemp = $("#txtAmountTemp").val();

        if(txtCompanyTemp == "")
        {
            $.post('halPostPaymentRequest.php',
            { aksi:"getDisplayKet", idPayment : txtIdPayment, typeDoc : txtTypeDoc },
                function(data) 
                {
                    $("#lblTblCompany").text(data.company);
                    $("#lblTblAmount").text(data.amountCRDisplay);

                    $("#txtCompanyTemp").val(data.company);
                    $("#txtCurrTemp").val(data.currency);
                    $("#txtSenderVendorTemp").val(data.senderVendor);

                    $("#txtAmountTemp").val(data.amountAllCR);

                    $("#idBodyPaymentGroupItems").append(data.trNya);
                    idPaymenAll.push(txtIdPayment);
                    $("#txtIdPaymentAll").val(idPaymenAll);
                    $("#lblLastTransNo").text(data.transNo);
                    $("#lblRemarkRelease").append(data.release_remark);
                },
            "json"
            );
        }else{
            $.post('halPostPaymentRequest.php',
            { aksi:"getDisplayKet", idPayment : txtIdPayment, typeDoc : txtTypeDoc },
                function(data) 
                {
                    if(data.company == $("#txtCompanyTemp").val() && data.currency == $("#txtCurrTemp").val())
                    {
                        var ttlTemp = parseFloat(jmlhTemp) + parseFloat(data.amount);

                        $("#lblTblAmount").text(ttlTemp.toLocaleString());

                        $("#idBodyPaymentGroupItems").append(data.trNya);
                        idPaymenAll.push(txtIdPayment);
                        $("#txtIdPaymentAll").val(idPaymenAll);
                    }else{
                        alert("BILLING COMPANY and CURRENCY must be same with the Group Items");
                    }
                },
            "json"
            );
        }
        enabledBtn("btnPayPrepAssign");
        $("#loaderImg").css('visibility','hidden');
    },300);
}

function klikBtnAssignTransNo()
{
    $("#loaderImg").css('visibility','');
    var allIdPayment = $("#txtIdPaymentAll").val();
    var txtTypeDoc = $("#txtTypeDocTemp").val();

    setTimeout(function()
    {
        $.post('halPostPaymentRequest.php',
        { aksi:"assignTransNo", idPaymenAll : allIdPayment, typeDoc : txtTypeDoc },
            function(data) 
            {
                alert(data);
                reloadPage();
            },
        "json"
        );
    },300);
}

function viewKeterangan(id,typeDoc)
{
    $("#loaderImg").css('visibility','');

    setTimeout(function()
    {
        $.post('halPostPaymentRequest.php',
        { aksi:"getDisplayKet", idPayment : id, typeDoc : typeDoc },
            function(data) 
            {
                $("#lblBillComp").text(data.company);
                $("#lblAmount").text(data.amountAllCR);

                $("#loaderImg").css('visibility','hidden');
            },
        "json"
        );
    },300);
}

function btnCari(type)
{
    $("#txtCariPrep").val("");
    if(type == "show")
    {
        $("#txtCariPrep").css("display","");        
        $('#btnCariPrepare')[0].setAttribute('onclick',"btnCari('hide')");
    }else{
        $("#txtCariPrep").css("display","none");
        $('#btnCariPrepare')[0].setAttribute('onclick',"btnCari('show')");
    }
}

function searchDataNya()
{
    $("#loaderImg").css('visibility','');
    var txtSearch = $("#txtCariPrep").val();
    var slcSearch = $("#prepareBy").val();

    setTimeout(function()
    {
        if(slcSearch == "barcode")
        {
            $("#lblNmTbl").text("BARCODE");
        }
        if(slcSearch == "company")
        {
            $("#lblNmTbl").text("COMPANY");
        }
        if(slcSearch == "invoice")
        {
            $("#lblNmTbl").text("INVOICE/MAIL");
        }
        if(slcSearch == "requestname")
        {
            $("#lblNmTbl").text("REQUEST NAME");
        }

        $.post('halPostPaymentRequest.php',
        { aksi:"searchDataNya", txtSearch:txtSearch, slcSearch:slcSearch },
            function(data) 
            {
                $("#idBody").empty();
                $("#idBody").append(data);

                $("#loaderImg").css('visibility','hidden');
            },
        "json"
        );
    },200);
}

function reloadPage()
{
    $('#formPrepareForPayment').submit();
}

</script>

<a class="thickbox" id="hrefThickbox"></a>

<div class="wrap">
	<div id="loaderImg" style="visibility:hidden;" class="pleaseWait">
    	<div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="picture/loading (115).gif" height="12"/>&nbsp;</div>
    </div>
  	<div class="namaAplikasi"> PREPARE FOR PAYMENT </div>
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

        <button class="btnStandar" id="btnPaymentReq" title="PAYMENT REQUEST" onclick="$('#formPaymentReq').submit();return false;">
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
        <button class="btnStandarTabPilih" id="" title="PREPARE FOR PAYMENT" onclick="return false;">
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
    </div>

    <div class="kotakIframe" id="divKotakIframe" style="border-style:dotted;">
        <div style="position:inherit;border:solid 0px #CCC;top:5px;left:5px;text-align:left;width:24%;">
            <table cellpadding="0" cellspacing="0" style="font-family:sans-serif;font-weight:bold;color:#485a88;">
            <tr align="left">
                <td width="" valign="bottom"><span style="font-size:11px;vertical-align:bottom;">PREPARED BY&nbsp;</span>
                    <select id="prepareBy" name="prepareBy" class="elementMenu" style="width:135px;position:relative;color:#485a88;" onchange="searchDataNya();">
                        <option value="barcode">BARCODE</option>
                        <option value="company">COMPANY</option>
                        <option value="invoice">INVOICE NUMBER</option>
                        <option value="requestname" selected>REQUEST NAME</option>
                    </select>
                </td>
            </tr>
            </table>        
            <div style="width:100%;overflow-x:auto;white-space:nowrap;height:400px;margin-top:5px;">
                <table id="judul" width="220" cellpadding="0" cellspacing="0" style="">
                    <thead>
                        <tr style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;">
                            <td width="150" style="height:30px;vertical-align:middle;text-align:center;" id="lblNmTbl">REQUEST NAME</td>
                            <td width="150" style="height:30px;vertical-align:middle;text-align:center;">AMOUNT</td>
                        </tr>
                    </thead>
                    <tbody id="idBody">
                        {getDataPrepare}
                    </tbody>
                </table>
            </div>
        </div>
        <div style="position:inherit;border:solid 0px #CCC;top:5px;left:25%;text-align:left;width:75%;">
            <button class="btnStandar" id="btnCariPrepare" title="Cari Prepare" onclick="btnCari('show');">
                <table cellpadding="0" cellspacing="0" width="24" height="19">
                    <tr>
                        <td align="center" valign="bottom"><img src="picture/magnifier.png" height="14" /></td>
                    </tr>
                </table>
            </button>
            <input type="text" id="txtCariPrep" class="elementInput" style="vertical-align:bottom;width:100px;display:none;" oninput="searchDataNya();">
            <table width="500" cellpadding="0" cellspacing="0" style="padding-top:5px;">
                <tr>
                    <td>
                        <input type="hidden" id="txtIdPayment" value="">
                        <input type="hidden" id="txtCompanyTemp" value="">
                        <input type="hidden" id="txtCurrTemp" value="">
                        <input type="hidden" id="txtSenderVendorTemp" value="">
                        <input type="hidden" id="txtAmountTemp" value="0">
                        <input type="hidden" id="txtIdPaymentAll" value="">
                        <input type="hidden" id="txtTypeDocTemp" value="">
                        <button class="btnStandarDis" id="btnPayPrepAddGroup" title="Add To Group" onclick="klikBtnAddGroup();" disabled="disabled">
                            <table width="110" height="24">
                            <tr>
                                <td align="center" width="20"><img src="picture/table--plus.png"/></td>
                                <td align="left">ADD TO GROUP</td> 
                            </tr>
                            </table>
                        </button>
                        <button class="btnStandarDis" id="btnPayPrepAssign" title="" onclick="klikBtnAssignTransNo();" disabled="disabled">
                            <table width="140" height="24">
                            <tr>
                                <td align="center" width="20"><img src="picture/document-task.png"/></td>
                                <td align="left">ASSIGN TO TRANS. NO</td> 
                            </tr>
                            </table>
                        </button>
                        <button class="btnStandar" id="btnPayPrepResetGroup" title="" onclick="reloadPage();return false;">
                            <table width="101" height="24">
                            <tr>
                                <td align="center" width="20"><img src="picture/arrow-return-180-left.png"/></td>
                                <td align="left">RESET GROUP</td> 
                            </tr>
                            </table>
                        </button>
                    </td>
                </tr>
            </table>
            <div style="position:inherit; border: solid 1px #CCC; top:61px;background-color:#F9F9F9;">
                <table height="80" width="650" cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;font-weight:bold;color:#485a88;">
                    <tr>
                        <td height="20" style="width: 150px;">&nbsp;BILLING COMPANY</td>
                        <td class="elementTeks" id="lblBillComp"></td>
                    </tr>
                    <tr>
                        <td height="20" style="width: 150px;">&nbsp;AMOUNT</td>
                        <td class="elementTeks" id="lblAmount"></td>
                    </tr>
                    <tr>
                        <td height="20" style="width: 150px;">&nbsp;LAST TRANS NO</td>
                        <td class="elementTeks" id="lblLastTransNo" style="color: red;">[ {getLastTransNo} ]</td>
                    </tr>
                    <tr>
                        <td height="20" style="width: 150px;">&nbsp;REMARK</td>
                        <td class="elementTeks" id="lblRemarkRelease"></td>
                    </tr>
                </table>
            </div>

            <span style="position:inherit;top:160px;font:0.7em sans-serif;font-weight:bold;color:#485a88;">PAYMENT GROUPINGS</span>
            <img style="position:inherit;top:160px;left:120px;" src="picture/arrow-315-medium.png"/>            
            <div style="position:inherit; border: solid 1px #CCC;top:180px; width:700px;height:52px;">
                <table id="judul1" cellpadding="0" cellspacing="0" width="700" border="0">
                    <thead style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;">
                        <tr align="center">
                            <td width="220" height="30" class="">BILLING COMPANY</td>
                            <td width="150" class="">TOTAL AMOUNT</td>
                        </tr>
                    </thead>
                    <tbody id="idBodyPaymentGroup" style="font-family:Arial;font-size:10px;">
                        <tr align="center">
                            <td width="220" id="lblTblCompany"></td>
                            <td width="150" id="lblTblAmount"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <span style="position:inherit;top:240px;font:0.7em sans-serif;font-weight:bold;color:#485a88;">GROUPED ITEMS</span>
            <img style="position:inherit;top:240px;left:90px;" src="picture/arrow-315-medium.png"/>            
            <div style="position:inherit; border: solid 1px #CCC;top:260px;bottom:6px; width:730px;height:190px;">
                <table id="judul2" cellpadding="0" cellspacing="0" width="730" border="0">
                    <thead style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;">
                        <tr align="center">
                            <td width="85" height="30">BARCODE</td>
                            <td width="100">INV.DATE</td>
                            <td width="100">DUE DATE</td>
                            <td width="120">INV.NUMBER</td>
                            <td width="20">TYPE</td>
                            <td width="130">AMOUNT</td>
                            <td width="120">VESSEL</td>
                            <td width="120">VOYAGE</td>
                            <td width="335">DESCRIPTION</td>
                        </tr>
                    </thead>
                    <tbody id="idBodyPaymentGroupItems" style="font-size:10px;">
                        
                    </tbody>
                </table>
            </div> 
        </div>
    </div>

</div>