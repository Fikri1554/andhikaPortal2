<script type="text/javascript" src="js/paymentAdvance.js"></script>
<script type="text/javascript" src="../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../js/masks.js"></script>
<script type="text/javascript" src="../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>

<link href="css/paymentAdvance.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
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

function saveData(type)
{
    $("#loaderImg").css('visibility','');
    setTimeout(function()
    {
        var typeDoc = $("#txtTypeDocHide").val();
        var txtTransNo = $("#txtTransNo").val();
        var slcPayMethod = $("#slcPayMethod").val();
        var slcBankCode = $("#slcBankCode").val();
        var txtVoucher = $("#txtVoucher").val();
        var txtPaidToFrom = $("#txtPaidToFrom").val();
        var txtRef = $("#txtRef").val();
        var txtChequeNumber = $("#txtChequeNumber").val();
        var txtDatePaid = $("#txtDatePaid").val();
        var txtAmountPaid = $("#txtAmountPaid").val();
        var slcCurrency = $("#slcCurrency").val();

        if(slcPayMethod == "")
        {
            $("#loaderImg").css('visibility','hidden');
            alert("Payment Method Empty..!!");
            return false;
        }

        if(txtPaidToFrom == "")
        {
            $("#loaderImg").css('visibility','hidden');
            alert("Paid To / From Empty..!!");
            document.getElementById("txtPaidToFrom").focus();
            return false;
        }

        if(slcBankCode == "")
        {
            $("#loaderImg").css('visibility','hidden');
            alert("Bank Empty..!!");
            return false;
        }

        if(txtVoucher == "")
        {
            $("#loaderImg").css('visibility','hidden');
            alert("Voucher no Empty..!!");
            document.getElementById("txtVoucher").focus();
            return false;
        }

        if(txtRef == "")
        {
            $("#loaderImg").css('visibility','hidden');
            alert("Reference no Empty..!!");
            document.getElementById("txtRef").focus();
            return false;
        }

        if(txtDatePaid == "")
        {
            $("#loaderImg").css('visibility','hidden');
            alert("Date Paid Empty..!!");
            document.getElementById("txtDatePaid").focus();
            return false;
        }

        if(txtAmountPaid == "")
        {
            $("#loaderImg").css('visibility','hidden');
            alert("Amount Empty..!!");
            document.getElementById("txtAmountPaid").focus();
            return false;
        }

        if(slcCurrency == "")
        {
            $("#loaderImg").css('visibility','hidden');
            alert("Currency Empty..!!");
            document.getElementById("slcCurrency").focus();
            return false;
        }

        $.post('halPostPaymentRequest.php',
        { aksi:"saveDataVoucher", typeDoc:typeDoc, txtTransNo:txtTransNo, slcPayMethod:slcPayMethod, slcBankCode:slcBankCode, txtVoucher:txtVoucher, txtPaidToFrom:txtPaidToFrom, txtRef:txtRef, txtChequeNumber:txtChequeNumber, txtDatePaid:txtDatePaid, txtAmountPaid:txtAmountPaid, slcCurrency:slcCurrency },
            function(data) 
            {
                if(type == '')
                {
                    alert(data);
                    reloadPage();
                }
            },
        "json"
        );

    },300);
}

function transToAcct()
{
    $("#loaderImg").css('visibility','');
    saveData('transToAcct');
    setTimeout(function()
    {
        var typeDoc = $("#txtTypeDocHide").val();
        var txtTransNo = $("#txtTransNo").val();
        var slcPayMethod = $("#slcPayMethod").val();
        var slcBankCode = $("#slcBankCode").val();
        var txtVoucher = $("#txtVoucher").val();
        var txtPaidToFrom = $("#txtPaidToFrom").val();
        var txtRef = $("#txtRef").val();
        var txtChequeNumber = $("#txtChequeNumber").val();
        var txtDatePaid = $("#txtDatePaid").val();
        var txtAmountPaid = $("#txtAmountPaid").val();
        var slcCurrency = $("#slcCurrency").val();

        $.post('halPostPaymentRequest.php',
        { aksi:"transToAcct", typeDoc:typeDoc, txtTransNo:txtTransNo, slcPayMethod:slcPayMethod, slcBankCode:slcBankCode, txtVoucher:txtVoucher, txtPaidToFrom:txtPaidToFrom, txtRef:txtRef, txtChequeNumber:txtChequeNumber, txtDatePaid:txtDatePaid, txtAmountPaid:txtAmountPaid, slcCurrency:slcCurrency},
            function(data) 
            {
                alert(data);
                reloadPage();
            },
        "json"
        );

    },300);
}

function onclickNya(idTr,transno)
{
    $('[id^=idTr_]').css('background-color','#FFFFFF');
    $('[id^=idTrSet_]').css('background-color','#E5CDFC');
    document.getElementById('idTr_'+idTr).onmouseout = '';
    document.getElementById('idTr_'+idTr).onmouseover ='';
    document.getElementById('idTr_'+idTr).style.backgroundColor='#B0DAFF';

    document.getElementById('txtTransNo').value = transno;
    document.getElementById('txtTypeDocHide').value = "";

    enabledBtn("btnRetrieve");
}

function onclickSettlementNya(idTr,transno,typeDoc)
{
    $('[id^=idTr_]').css('background-color','#FFFFFF');
    $('[id^=idTrSet_]').css('background-color','#E5CDFC');
    document.getElementById('idTrSet_'+idTr).onmouseout = '';
    document.getElementById('idTrSet_'+idTr).onmouseover ='';
    document.getElementById('idTrSet_'+idTr).style.backgroundColor='#B0DAFF';

    document.getElementById('txtTransNo').value = transno;
    document.getElementById('txtTypeDocHide').value = typeDoc;

    enabledBtn("btnRetrieve");
}

function klikBtnRetrieve()
{
    $("#loaderImg").css('visibility','');
    setTimeout(function()
    {
        var transno = $("#txtTransNo").val();
        var typeDoc = $("#txtTypeDocHide").val();

        $("#idLblStatus").text("");
        $("#txtChequeNumber").attr('disabled',true);

        $.post('halPostPaymentRequest.php',
        { aksi:"getDisplayKetByTransNo", transno : transno, typeDoc : typeDoc },
            function(data) 
            {
                $("#lblTblCompany").text(data['paymentGrouping']['company']);
                $("#lblTblAmount").text("("+data['paymentGrouping']['curr']+") "+data['paymentGrouping']['totalNya']);

                $("#idBodyPaymentGroupItems").empty();
                $("#idBodyPaymentGroupItems").append(data['trNya']);
                $("#tdTransNo").text(data['transNoFormat']);
                $("#loaderImg").css('visibility','hidden');

                enabledBtn("btnSaveVoucher");
                enabledBtn("btnEditDetail");
                if(data['paymentGrouping']['stVoucher'] == "N")
                {
                    $("#idLblStatus").text("CREATE");
                    $("#slcPayMethod").val("");
                    $("#slcBankCode").val("");
                    $("#txtVoucher").val("");
                    $("#txtPaidToFrom").val("");
                    $("#txtRef").val("");
                    $("#txtChequeNumber").val("");
                    $("#txtAmountPaid").val("");
                    $("#slcCurrency").val("");
                    $("#txtDatePaid").val("");
                    $("#txtBankCharge").val("");
                    disabledBtn("btnPrint");
                    disabledBtn("btnTranferToAcct");
                }else{
                    enabledBtn("btnPrint");
                    if(data['paymentGrouping']['txtStTransToAcct'] == "Y")
                    {
                        $("#idLblStatus").text("PAID");
                        disabledBtn("btnTranferToAcct");
                        disabledBtn("btnSaveVoucher");
                    }else{
                        $("#idLblStatus").text("UPDATE");
                        enabledBtn("btnTranferToAcct");
                    }
                    
                    if(data['paymentGrouping']['slcMethod'] == "cheque")
                    {
                        $("#txtChequeNumber").attr('disabled',false);
                    }
                    $("#slcPayMethod").val(data['paymentGrouping']['slcMethod']);
                    $("#slcBankCode").val(data['paymentGrouping']['slcBank']);
                    $("#txtVoucher").val(data['paymentGrouping']['txtVoucherNo']);
                    $("#txtPaidToFrom").val(data['paymentGrouping']['txtPaidToFrom']);
                    $("#txtRef").val(data['paymentGrouping']['txtRef']);
                    $("#txtChequeNumber").val(data['paymentGrouping']['txtCheqNo']);
                    $("#txtAmountPaid").val(data['paymentGrouping']['txtAmount']);
                    $("#slcCurrency").val(data['paymentGrouping']['slcCurr']);
                    $("#txtDatePaid").val(data['paymentGrouping']['txtDatePaid']);
                    $("#txtBankCharge").val(data['paymentGrouping']['txtBankCharges']);
                }
            },
        "json"
        );
    },300);
}

function setMethod()
{
    var slcMethod = $("#slcPayMethod").val();

    if(slcMethod == "cheque")
    {
        $("#txtChequeNumber").attr('disabled',false);
    }else{
        $("#txtChequeNumber").val("");
        $("#txtChequeNumber").attr('disabled',true);
    }
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

function hanyaAngkaAmount(idDiv)
{
    amountMask = new Mask("#,###.##", "number");
    amountMask.attach(document.getElementById(idDiv));
}

function searchDataNya()
{
    $("#loaderImg").css('visibility','');
    var txtSearch = $("#txtCariPrep").val();

    setTimeout(function()
    {
        $.post('halPostPaymentRequest.php',
        { aksi:"searchDataVoucher", txtSearch:txtSearch },
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

function printVoucher()
{
    $("#loaderImg").css('visibility','');
    var transno = $("#txtTransNo").val();
    var typeDoc = $("#txtTypeDocHide").val();

    setTimeout(function()
    {
        $("#loaderImg").css('visibility','hidden');

        $('#formPrintVoucher').attr('action', 'halPrint.php?aksi=printVoucher&transno='+transno+'&typeDoc='+typeDoc);
        formPrintVoucher.submit();
    }, 250);
}

function saveDetail()
{
    var typeDoc = $("#txtTypeDocHide").val();

    var idPayDet = "";
    var valIdPayDet = $("input[id^='txtIdPaySplit_']").map(function(){return $(this).val();}).get();
    for (var l = 0; l < valIdPayDet.length; l++)
    {
        if(valIdPayDet[l] == "")
        {
            valIdPayDet[l] = "-";
        }
        if(idPayDet == ""){ idPayDet = valIdPayDet[l]; }else{ idPayDet += "^"+valIdPayDet[l]; }
    }

    var remark = "";
    var valRemark = $("input[id^='txtDesc_']").map(function(){return $(this).val();}).get();
    for (var l = 0; l < valRemark.length; l++)
    {
        if(valRemark[l] == "")
        {
            valRemark[l] = "-";
        }
        if(remark == ""){ remark = valRemark[l]; }else{ remark += "^"+valRemark[l]; }
    }

    $("#loaderImg").css('visibility','');

    setTimeout(function()
    {
        $.post('halPostPaymentRequest.php',
        { aksi:"saveDetailSplit", typeDoc:typeDoc, idPayDetSplit:idPayDet, remark:remark },
            function(data) 
            {
                alert(data);
                $("[id^=txtDesc_]").attr('disabled',true);
                disabledBtn("btnSaveDetail");
                $("#loaderImg").css('visibility','hidden');
            },
        "json"
        );
    },200);
}

function editFormDesc()
{
    $("[id^=txtDesc_]").attr('disabled',false);
    enabledBtn("btnSaveDetail");
}

function reloadPage()
{
    $('#formVoucher').submit();
}

</script>

<a class="thickbox" id="hrefThickbox"></a>

<div class="wrap">
    <div id="loaderImg" style="visibility:hidden;" class="pleaseWait">
        <div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="picture/loading (115).gif" height="12"/>&nbsp;</div>
    </div>
    <div class="namaAplikasi"> VOUCHER </div>
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
        <form method="post" name="formPrintVoucher" id="formPrintVoucher" target="Report" onSubmit="window.open('this.form.action', this.target, 'fullscreen=no, titlebar=no, toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no');"></form>

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
        <button class="btnStandar" id="btnPaymentPrepareForPayment" title="PREPARE FOR PAYMENT" onclick="$('#formPrepareForPayment').submit();return false;">
            <table width="130" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/document--arrow.png"/> </td>
                <td align="left">PREPARE VOUCHER</td>
              </tr>
            </table>
        </button>
        <button class="btnStandarTabPilih" id="" title="VOUCHER" onclick="return false;">
            <table width="58" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/document-sticky-note.png"/> </td>
                <td align="left">VOUCHER</td>
              </tr>
            </table>
        </button>
    </div>

    <div class="kotakIframe" id="divKotakIframe" style="border-style:dotted;margin-top:-30px;height:487px;">
        <div id="divIframeList" style="position:inherit;border:solid 1px #CCC;left:5px;text-align:left;width:24%;">
            <button class="btnStandar" id="btnCariPrepare" title="Cari Prepare" onclick="btnCari('show');" style="margin:2px 2px 0px 2px;">
                <table cellpadding="0" cellspacing="0" width="24" height="19">
                    <tr>
                        <td align="center" valign="bottom"><img src="picture/magnifier.png" height="14" /></td>
                    </tr>
                </table>
            </button>
            <input type="text" id="txtCariPrep" class="elementInput" style="vertical-align:bottom;width:100px;display:none;" oninput="searchDataNya();" placeholder="Trans No">
            <div style="width:100%;overflow-x:auto;white-space:nowrap;height:450px;margin-top:5px;">
                <table id="judul" cellpadding="0" cellspacing="0" style="width: 350px;">
                    <thead>
                        <tr style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;">
                            <td width="50" style="height:30px;vertical-align:middle;text-align:center;" id="lblNmTbl">STATUS</td>
                            <td width="100" style="height:30px;vertical-align:middle;text-align:center;">TRANS NO</td>
                            <td width="200" style="height:30px;vertical-align:middle;text-align:center;">COMPANY</td>
                        </tr>
                    </thead>
                    <tbody id="idBody">
                        {getDataVoucher}
                    </tbody>
                </table>
            </div>
        </div>
        <div style="position:inherit;border:solid 0px #CCC;left:25.5%;text-align:left;width:75%;">            
            <table width="600" cellpadding="0" cellspacing="0" style="padding-top:5px;">
                <tr>
                    <td>
                        <input type="hidden" id="txtTransNo" value="">
                        <input type="hidden" id="txtTypeDocHide" value="">
                        <button class="btnStandarDis" id="btnRetrieve" title="Retrieve" onclick="klikBtnRetrieve();" disabled="disabled">
                            <table width="80" height="24">
                            <tr>
                                <td align="center" width="20"><img src="picture/table--plus.png"/></td>
                                <td align="left">RETRIEVE</td> 
                            </tr>
                            </table>
                        </button>
                        <button class="btnStandarDis" id="btnSaveVoucher" title="" onclick="saveData('');" disabled="disabled">
                            <table width="70" height="24">
                            <tr>
                                <td align="center" width="20"><img src="picture/disk-black.png"/></td>
                                <td align="left">SAVE</td> 
                            </tr>
                            </table>
                        </button>
                        <button class="btnStandarDis" id="btnPrint" title="" onclick="printVoucher();" disabled="disabled">
                            <table width="70" height="24">
                            <tr>
                                <td align="center" width="20"><img src="picture/printer.png"/></td>
                                <td align="left">PRINT</td> 
                            </tr>
                            </table>
                        </button>
                        <button class="btnStandarDis" id="btnTranferToAcct" title="" onclick="transToAcct();" disabled="disabled">
                            <table width="100" height="24">
                            <tr>
                                <td align="center" width="20"><img src="picture/document-task.png"/></td>
                                <td align="left">DONE</td> 
                            </tr>
                            </table>
                        </button>
                        <button class="btnStandar" id="btnPayPrepResetGroup" title="" onclick="reloadPage();return false;">
                            <table width="101" height="24">
                            <tr>
                                <td align="center" width="20"><img src="picture/arrow-circle-315.png"/></td>
                                <td align="left">REFRESH</td> 
                            </tr>
                            </table>
                        </button>
                        <label style="padding-left:10px;color:red;" id="idLblStatus"></label>
                    </td>
                </tr>
            </table>
            <div style="position:inherit; border: solid 1px #CCC; top:35px; background-color:#FFF;">
              <table width="600" height="180" cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;font-weight:bold;color:#485a88;">
                <tr>
                    <td height="22" class="">&nbsp;TRANS. NO</td>
                    <td class="elementTeks" id="tdTransNo" style="font-size:12px;text-decoration:underline;">&nbsp;</td>
                </tr>
                <tr valign="middle">
                    <td width="152" height="22" class="">&nbsp;PAYMENT METHOD</td>
                    <td width="356" class="elementTeks" id="">
                        <select id="slcPayMethod" class="elementMenu" style="width:135px;" onchange="setMethod();">
                            <option value="">-- PLEASE SELECT  --</option>
                            <option value="cash">CASH</option>
                            <option value="cheque">CHEQUE</option>
                            <option value="transfer">TRANSFER</option>
                        </select>
                        <span style="font:1em sans-serif;font-weight:bold;color:#485a88;padding-left:85px;">CHEQUE NO</span>&nbsp;
                        <input type="text" id="txtChequeNumber" class="elementInput" style="width:50px;" maxlength="12" disabled="disabled">
                    </td>
                </tr>
                <tr valign="middle">
                    <td width="152" height="22" class="">&nbsp;PAID TO / FROM</td>
                    <td width="356" class="elementTeks" id="">
                        <input type="text" id="txtPaidToFrom" class="elementInput" style="width:340px;">
                    </td>
                </tr>
                <tr valign="middle">
                    <td height="22" class="">&nbsp;BANK</td>
                    <td class="elementTeks" id="tdBankCode">
                        <select id="slcBankCode" class="elementMenu" style="width:350px;" onChange="">
                        <option value="">-- SELECT BANK  --</option>
                            {getCodeBank}
                        </select>
                    </td>
                </tr>
                <tr valign="middle">
                    <td height="22" class="">&nbsp;VOUCHER NO</td>
                    <td class="elementTeks" id="">
                        <input type="text" id="txtVoucher" class="elementInput" style="width:60px;" maxlength="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span style="font:1em sans-serif;font-weight:bold;color:#485a88;">REFERENCE NO</span>&nbsp;
                        <input type="text" id="txtRef" class="elementInput" style="width:60px;" maxlength="5">
                    </td>
                </tr>
                <tr valign="middle">
                    <td height="22" class="">&nbsp;DATE PAID</td>
                    <td class="">
                         <input type="text" id="txtDatePaid" class="elementInput" value="{dateNow}" style="width:60px;"/>
                         <img src="../picture/calendar.gif" class="gayaKalender" title="Select Date" onclick="displayCalendar(document.getElementById('txtDatePaid'),'dd/mm/yyyy',this, '', '', '193', '183');"/>&nbsp;<span class="spanKalender">(DD/MM/YYYY)</span>
                    </td>
                </tr>
                <tr valign="middle">
                    <td height="22" class="">&nbsp;AMOUNT</td>
                    <td class="elementTeks" id="">
                        <input type="text" id="txtAmountPaid" class="elementInput" style="width:120px;text-align:right;vertical-align:middle;" oninput="hanyaAngkaAmount('txtAmountPaid');">
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font:1em sans-serif;font-weight:bold;color:#485a88;">CURR</span>&nbsp;
                        <select id="slcCurrency" class="elementMenu" style="width:165px;vertical-align:middle;">
                            <option value="">-- PLEASE SELECT  --</option>
                            {getCurr}
                        </select>
                    </td>
                </tr>
                </table>
            </div>

            <span style="position:inherit;top:240px;font:0.7em sans-serif;font-weight:bold;color:#485a88;">PAYMENT GROUPINGS</span>
            <img style="position:inherit;top:240px;left:120px;" src="picture/arrow-315-medium.png"/>            
            <div style="position:inherit; border: solid 1px #CCC;top:260px; width:700px;height:52px;">
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

            <span style="position:inherit;top:320px;font:0.7em sans-serif;font-weight:bold;color:#485a88;">GROUPED ITEMS</span>
            <img style="position:inherit;top:320px;left:90px;" src="picture/arrow-315-medium.png"/>       
            
            <div style="position:inherit;top:320px;left:550px;">
                <button class="btnStandarDis" id="btnEditDetail" title="EDIT" onclick="editFormDesc();">
                    <table width="60" height="20">
                        <tr>
                            <td align="center" width="20"><img src="picture/pencil.png"/></td>
                            <td align="left">EDIT</td> 
                        </tr>
                    </table>
                </button>
                <button class="btnStandarDis" id="btnSaveDetail" title="SAVE DETAIL" onclick="saveDetail();">
                    <table width="100" height="20">
                        <tr>
                            <td align="center" width="20"><img src="picture/disk-black.png"/></td>
                            <td align="left">SAVE DETAIL</td> 
                        </tr>
                    </table>
                </button>
            </div>
            <div style="position:inherit;border: solid 1px #CCC;top:350px;bottom:6px; width:730px;height:140px;overflow-x:auto;">
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