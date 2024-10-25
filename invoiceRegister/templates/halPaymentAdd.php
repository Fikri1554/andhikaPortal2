<script type="text/javascript" src="./../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="js/payment.js"></script>
<!-- <script type="text/javascript" src="js/masks.js"></script> -->

<link rel="stylesheet" type="text/css" href="./../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>

<script type="text/javascript">
var arrTransNo = [];
var arrIdVoucher = [];
var arrIdPaymentAdv = [];
var arrIdMailInv = [];
window.onload = function()
{   
    windowDisplayAddPayment();
}
function searchDataTemp()
{
    pleaseWait();
    var txtSearch = $("#txtSearchTemp").val();
    var slcCompany = $("#slcCompany").val();

    loadIframe('iframeList', '');
    loadIframe('iframeList', 'templates/halAddPaymentList.php?aksi=ketikSearchTemp&&paramCari='+txtSearch+'&&paramCompany='+slcCompany);    
}
function sendToPaymentList()
{
    var txtDateBatch = $("#txtDateBatch").val();

    if(txtDateBatch == "")
    {
        alert('Batchno Empty..!!');
        return false;
    }
    if(arrTransNo.length > 0 || arrIdVoucher.length > 0 || arrIdPaymentAdv.length > 0 || arrIdMailInv.length > 0)
    {
        var cfm = confirm("Send Data..??");
        if(cfm)
        {
            pleaseWait();

            var ttlTransNo = "";
            var valTransNo = $("input[name^='txtTransNo']").map(function(){return $(this).val();}).get();
            for (var l = 0; l < valTransNo.length; l++)
            {
                if(ttlTransNo == ""){ ttlTransNo = valTransNo[l]; }else{ ttlTransNo += "*"+valTransNo[l]; }
            }

            var remark = "";
            var valRemark = $("input[name^='txtRemark']").map(function(){return $(this).val();}).get();
            for (var l = 0; l < valRemark.length; l++)
            {
                if(valRemark[l] == "")
                {
                    valRemark[l] = "-";
                }
                if(remark == ""){ remark = valRemark[l]; }else{ remark += "*"+valRemark[l]; }
            }

            var ttlIdMailInv = "";
            var valIdMailInv = $("input[name^='txtIdMailInv']").map(function(){return $(this).val();}).get();
            for (var l = 0; l < valIdMailInv.length; l++)
            {
                if(ttlIdMailInv == ""){ ttlIdMailInv = valIdMailInv[l]; }else{ ttlIdMailInv += "*"+valIdMailInv[l]; }
            }

            var remarkByPass = "";
            var valRemarkByPass = $("input[name^='txtIdMailRemark']").map(function(){return $(this).val();}).get();
            for (var l = 0; l < valRemarkByPass.length; l++)
            {
                if(valRemarkByPass[l] == "")
                {
                    valRemarkByPass[l] = "-";
                }
                if(remarkByPass == ""){ remarkByPass = valRemarkByPass[l]; }else{ remarkByPass += "*"+valRemarkByPass[l]; }
            }

            var ttlVoucher = "";
            var valVoucher = $("input[name^='txtIdVoucher']").map(function(){return $(this).val();}).get();
            for (var l = 0; l < valVoucher.length; l++)
            {
                if(ttlVoucher == ""){ ttlVoucher = valVoucher[l]; }else{ ttlVoucher += "*"+valVoucher[l]; }
            }

            var remarkVoucher = "";
            var valRemarkVoucher = $("input[name^='txtRemarkVoucher']").map(function(){return $(this).val();}).get();
            for (var l = 0; l < valRemarkVoucher.length; l++)
            {
                if(valRemarkVoucher[l] == "")
                {
                    valRemarkVoucher[l] = "-";
                }
                if(remarkVoucher == ""){ remarkVoucher = valRemarkVoucher[l]; }else{ remarkVoucher += "*"+valRemarkVoucher[l]; }
            }

            var ttlPaymentAdv = "";
            var valPaymentAdv = $("input[name^='txtTransNoPA']").map(function(){return $(this).val();}).get();
            for (var l = 0; l < valPaymentAdv.length; l++)
            {
                if(ttlPaymentAdv == ""){ ttlPaymentAdv = valPaymentAdv[l]; }else{ ttlPaymentAdv += "*"+valPaymentAdv[l]; }
            }

            var remarkPaymentAdv = "";
            var valRemarkPaymentAdv = $("input[name^='txtRemarkPA']").map(function(){return $(this).val();}).get();
            for (var l = 0; l < valRemarkPaymentAdv.length; l++)
            {
                if(valRemarkPaymentAdv[l] == "")
                {
                    valRemarkPaymentAdv[l] = "-";
                }
                if(remarkPaymentAdv == ""){ remarkPaymentAdv = valRemarkPaymentAdv[l]; }else{ remarkPaymentAdv += "*"+valRemarkPaymentAdv[l]; }
            }

            $.post( "../invoiceRegister/halPostMailInv.php",
            { aksi : "sendToPaymentList",arrTransNos : ttlTransNo,arrRemark : remark,arrVoucher : ttlVoucher,arrRemarkVoucher : remarkVoucher,ttlPaymentAdv : ttlPaymentAdv,remarkPaymentAdv : remarkPaymentAdv,txtDateBatch : txtDateBatch,arrMailInvId : ttlIdMailInv,remarkByPass : remarkByPass }, 
            function(data){
                $('#formPaymentAddList').submit();
            });
        }
    }
}
function addDataListPayment(id,typelist)
{
    if(typelist == "invreg")
    {
        let indexNya = arrTransNo.indexOf(id);
        if(indexNya == -1)
        {
            arrTransNo.push(id);
            getDataTemp();
        }else{
            alert("Sudah ada");
        }
    }
    else if(typelist == "invregByPass")
    {
        let indexMI = arrIdMailInv.indexOf(id);
        if(indexMI == -1)
        {
            arrIdMailInv.push(id);
            getDataTemp();
        }else{
            alert("Sudah ada");
        }
    }
    else if(typelist == "paymentAdvance")
    {
        let indexPA = arrIdPaymentAdv.indexOf(id);
        if(indexPA == -1)
        {
            arrIdPaymentAdv.push(id);
            getDataTemp();
        }else{
            alert("Sudah ada");
        }
    }else{
        let indexVoucher = arrIdVoucher.indexOf(id);
        if(indexVoucher == -1)
        {
            arrIdVoucher.push(id);
            getDataTemp();
        }else{
            alert("Sudah ada");
        }
    }    
}
function getDataTemp()
{
    if(arrTransNo.length > 0 || arrIdVoucher.length > 0 || arrIdPaymentAdv.length > 0 || arrIdMailInv.length > 0)
    {
        pleaseWait();
        $.post( "../invoiceRegister/halPostMailInv.php",
        { aksi : "getTempPaymentList",arrTransNos : arrTransNo,idVoucher : arrIdVoucher,arrIdPaymentAdv : arrIdPaymentAdv,arrIdMailInv : arrIdMailInv }, 
        function(data){
            $("#idBodyFrameNya").empty();
            $("#idBodyFrameNya").append(data);
            doneWait();
        });
    }else{
        $("#idBodyFrameNya").empty();
    }
}
function showFormUploadInvReg(transNo,typeData)
{
   document.getElementById('hrefThickbox').href = "templates/halAddPaymentUploadFile.php?typeVoucher=display&idVoucher="+transNo+"&typeData="+typeData+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=550&width=800&modal=true";

   document.getElementById('hrefThickbox').click();
}
function delTempGetData(id,typelist)
{
    var cfm = confirm("Yakin Di hapus..??");
    if(cfm)
    {
        if(typelist == "invreg")
        {
            const indexNya = arrTransNo.indexOf(id);
            if (indexNya > -1)
            {
              arrTransNo.splice(indexNya, 1);
              getDataTemp();
            }
        }
        else if(typelist == "invregByPass")
        {
            const indexMI = arrIdMailInv.indexOf(id);
            if (indexMI > -1)
            {
              arrIdMailInv.splice(indexMI, 1);
              getDataTemp();
            }
        }
        else if(typelist == "paymentAdvance")
        {
            const indexPA = arrIdPaymentAdv.indexOf(id);
            if (indexPA > -1)
            {
              arrIdPaymentAdv.splice(indexPA, 1);
              getDataTemp();
            }
        }else{
            const indexVoucher = arrIdVoucher.indexOf(id);
            if (indexVoucher > -1)
            {
              arrIdVoucher.splice(indexVoucher, 1);
              getDataTemp();
            }
        }
    }
}
</script>

<a class="thickbox" id="hrefThickbox"></a>

<div class="wrap">
    <div id="loaderImg" style="visibility:hidden;" class="pleaseWait">
        <div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="picture/loading (115).gif" height="12"/>&nbsp;</div>
    </div>
    <div class="namaAplikasi"> INVOICE REGISTER </div>
</div>

<div class="kotakInvReg" style="height: 600px;">    
    <div class="kotakBtnAtas">
        <div style="width:100px;">
        <form method="post" action="index.php" id="formIncoming" style="display:none;">
        <input type="hidden" id="halaman" name="halaman" value="halIncoming" />
        </form>
        <form method="post" action="index.php" id="formProcess" style="display:none;">
        <input type="hidden" id="halaman" name="halaman" value="halProcessAck" />
        </form>
        <form method="post" action="index.php" id="formPayment" style="display:none;">
        <input type="hidden" id="halaman" name="halaman" value="halPaymentOutstanding"/>
        </form>
        <form method="post" action="index.php" id="formPaymentPrepare" style="display:none;">
        <input type="hidden" id="halaman" name="halaman" value="halPaymentPrepare"/>
        </form>
        <form method="post" action="index.php" id="formPaymentBatch" style="display:none;">
        <input type="hidden" id="halaman" name="halaman" value="halPaymentBatch"/>
        </form>
        <form method="post" action="index.php" id="formOutgoing">
        <input type="hidden" id="halaman" name="halaman" value="halOutgoing" />
        </form>
        <form method="post" action="index.php" id="formCari" style="display:none;">
        <input type="hidden" id="halaman" name="halaman" value="halCari"/>
        </form>
        <form method="post" action="index.php" id="formPrintDist" style="display:none;">
        <input type="hidden" id="halaman" name="halaman" value="halPrintDistribution"/>
        </form>
        <form method="post" name="formPrintVoucher" id="formPrintVoucher" target="Report" onSubmit="window.open('this.form.action', this.target, 'fullscreen=no, titlebar=no, toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no');"></form>
        <form method="post" action="index.php" id="formPaymentAddList" style="display:none;">
        <input type="hidden" id="halaman" name="halaman" value="halPaymentAddList"/>
        </form>
        <form method="post" action="index.php" id="formPaymentList">
        <input type="hidden" id="halaman" name="halaman" value="halPaymentList"/>
        </form>
        </div>
        
        <button class="btnStandar" id="btnIncoming" title="INCOMING MAIL / DATA INVOICE" onClick="$('#formIncoming').submit();return false;">
            <table width="79" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/documents-stack.png"/></td>
                <td align="left">INCOMING</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnInvProcess" title="MAIL / DATA PROCESS" onClick="$('#formProcess').submit();return false;">
            <table width="77" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/wand.png"/></td>
                <td align="left">INVOICE PROCESS</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandarTabPilih" id="" title="PAYMENT TRANSACTION" onClick="return false;">
            <table width="77" height="29">
            <tr>
                <td align="center" width="20"><img src="picture/credit-cards.png"/></td>
                <td align="left">PAYMENT</td> 
            </tr>
            </table>
        </button>
        <button id="btnPaymentListAdd" class="btnStandar" title="PAYMENT LIST" onclick="$('#formPaymentList').submit();return false;">
            <table width="100" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/document-sticky-note.png"/> </td>
                <td align="left">PAYMENT LIST</td>
              </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnOutgoing" title="OUTGOING MAIL / DATA INVOICE" onClick="$('#formOutgoing').submit();return false;">
            <table width="81" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/document-export.png"/></td>
                <td align="left">OUTGOING</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPrint" title="PRINT MAIL / DATA INVOICE" onClick="$('#formPrintDist').submit();return false;">
            <table width="58" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/printer.png"/> </td>
                <td align="left">PRINT</td>
              </tr>
            </table>
        </button>
        <button id="btnSearch" class="btnStandar" title="SEARCHING DATA" onClick="$('#formCari').submit();return false;">
            <table width="70" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/magnifier.png"/> </td>
                <td align="left">SEARCH</td>
              </tr>
            </table>
        </button>
    </div>
    
    <div class="kotakBtnTengah" style="width:750px;">
        <input type="hidden" id="barisTransno" name="barisTransno"/> <!-- // ID DARI TR YANG DIPILIH DI IFRAME TRANSNO DATA -->
        <input type="hidden" id="idMailInv" name="idMailInv"/>
        <input type="hidden" id="transNo" name="transNo"/>
        <input type="hidden" id="lastSelBank" name="lastSelBank"/>
        <input type="hidden" id="statusPaid" name="statusPaid"/>
        <input type="hidden" id="statusCancelPaid" name="statusCancelPaid"/>
        <input type="hidden" id="reasonStatusCancelHid" name="reasonStatusCancelHid"/>
        <input type="hidden" id="totalAmount"/>
        
        <input type="hidden" id="payTypeHid" name="payTypeHid"/>
        <input type="hidden" id="bankCodeHid" name="bankCodeHid"/>
        <input type="hidden" id="voucherHid" name="voucherHid"/>
        <input type="hidden" id="refHid" name="refHid"/>
        <input type="hidden" id="chequeNumberHid" name="chequeNumberHid"/>
        <input type="hidden" id="datePaidHid" name="datePaidHid"/>
        <input type="hidden" id="currHid" name="currHid"/>
        <input type="hidden" id="amtConvHid" name="amtConvHid"/>
        <input type="hidden" id="currConvHid" name="currConvHid"/>
        <input type="hidden" id="adjAccHid" name="adjAccHid"/>
        <input type="hidden" id="adjAmtHid" name="adjAmtHid"/>
        <input type="hidden" id="paidToFromHid" name="paidToFromHid"/>
        
        <input type="hidden" id="aksesBtnPayTransAcct" value="{aksesBtnPayTransAcct}"/>
        <input type="hidden" id="aksesBtnPayPrintVoucher" value="{aksesBtnPayPrintVoucher}"/>
        <input type="hidden" id="aksesBtnCancelled" value="{aksesBtnCancelled}"/>
        <input type="hidden" id="dateToday" value="{datePaid}"/>       
        
        <input type="hidden" id="paidYesNo" value="no" size="10">
        <input type="hidden" id="paidOptYesNo" value="no" size="10">
        <input type="hidden" id="idViewFileNya" value=""/>
  
        <button id="btnPayOutstanding" class="btnStandar" title="OUTSTANDING INVOICE" onClick="$('#formPayment').submit();return false;">
            <table width="144" height="24" border="0">
            <tr>
                <td align="center" width="20"><img src="picture/document-task.png"/></td>
                <td align="left">OUTSTANDING INVOICE</td> 
            </tr>
            </table>
        </button>
        <button id="btnPayPreppay" class="btnStandar" title="PREPARE FOR PAYMENT" onClick="$('#formPaymentPrepare').submit();return false;">
            <table width="153" height="25" border="0">
              <tr>
                <td align="center" width="20"><img src="picture/document--arrow.png"/> </td>
                <td align="left">PREPARE FOR PAYMENT</td>
              </tr>
            </table>
        </button>
        <button id="btnPayBybatch" class="btnStandar" title="PAYMENT BY BATCH" onclick="$('#formPaymentBatch').submit();return false;">
            <table width="130" height="25" border="0">
              <tr>
                <td align="center" width="20"><img src="picture/money.png"/> </td>
                <td align="left">PAYMENT BY BATCH</td>
              </tr>
            </table>
        </button>
        <button id="btnPayBybatch" class="btnStandarTabPilih" title="ADD PAYMENT" onClick="return false;">
            <table width="70" height="24" border="0">
              <tr>
                <td align="left" style="font-size:9px;">ADD PAYMENT</td>
              </tr>
            </table>
        </button>
        |
        <button class="btnStandar" id="btnRefresh" title="" onClick="$('#formPaymentAddList').submit();return false;">
            <table width="76" height="24">
            <tr>
                <td align="center" width="20"><img src="picture/arrow-circle-315.png"/></td>
                <td align="left">REFRESH</td> 
            </tr>
            </table>
        </button>
    </div>
    
    <div id="idTeksMap" class="kotakTeksMap">{teksMap}</div>
    
    <div class="kotakIframe" style="border-style:dotted; height: 650px;">
        <div style="width:100%;padding:5px;">
            <input type="text" id="txtSearchTemp" placeholder="Trans no" value="" style="width:150px;height:23px;">
            <select style="width:150px;height:25px;" id="slcCompany">
                <option value="all">ALL</option>
                {menuCompany}
            </select>
            <button class="btnStandar" id="btnSearchPaymentAddList" title="Search" onClick="searchDataTemp();">
                <table width="76" height="20">
                <tr>
                    <td align="center" width="20"><img src="picture/magnifier.png"/></td>
                    <td align="left">SEARCH</td> 
                </tr>
                </table>
            </button>
            <button class="btnStandar" id="btnSubmit" title="Send To Payment" onClick="sendToPaymentList();" style="float:right;margin-right:10px;">
                <table width="76" height="20">
                <tr>
                    <td align="center" width="20"><img src="picture/tick.png"/></td>
                    <td align="left">SUBMIT</td> 
                </tr>
                </table>
            </button>
            <input type="text" id="txtDateBatch" placeholder="Batch no" value="" style="width:100px;height:23px;float:right;margin-right:10px;" onclick="displayCalendar(document.getElementById('txtDateBatch'),'yyyy-mm-dd',this, '', '', '193', '183');">
            <label style="float:right;margin-right:20px;color:#369;">Payment List</label>
        </div>

        <div id="divIframeList" style="border: solid 1px #CCC; top:30px; left:5px;text-align:left;width:480px;height:470px;float: left;">        
            <iframe style="overflow:scroll;height:470px;width:100%;" src="" target="iframeList" name="iframeList" id="iframeList" frameborder="0" marginwidth="0" marginheight="0">
            </iframe>
        </div>

        <div id="divIframeListShow" style="border: solid 1px #CCC; top:30px; left:5px;text-align:left;width:480px;height:470px;float:left;margin-left:20px;">
            <div style="overflow:scroll;width:100%;height:470px;">
                <table cellpadding="0" cellspacing="0" border="0" style="width:600px;">
                    <thead>
                        <tr align="center">
                            <td width="40" class="tabelBorderRightJust" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;left:0px;top:0px;z-index:10;" height="30">#</td>
                            <td width="70" class="tabelBorderRightJust" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;left:0px;top:0px;z-index:10;">TRANS / BATCH NO</td>
                            <td width="120" class="tabelBorderRightJust" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;left:0px;top:0px;z-index:10;">INV. NUMBER</td>
                            <td width="150" class="tabelBorderRightJust" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;left:0px;top:0px;z-index:10;">COMPANY</td>
                            <td width="100" class="tabelBorderRightJust" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;left:0px;top:0px;z-index:10;">AMOUNT</td>
                            <td width="120" class="tabelBorderRightJust" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;left:0px;top:0px;z-index:10;">REMARK</td>
                        </tr>
                    </thead>
                    <tbody id="idBodyFrameNya">      
                    </tbody>
                </table>
            </div>
        </div>
       
    </div>
    
</div>