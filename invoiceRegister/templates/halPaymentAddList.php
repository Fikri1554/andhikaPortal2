<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="js/payment.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>

<script type="text/javascript">

window.onload = function()
{
	windowDisplayAddPaymentListDetail();
    getSearchThnBlnBatch();
}

function getSearchThnBlnBatch()
{
    $.post( "../invoiceRegister/halPostMailInv.php",
    { aksi : "getSearchThnBlnBatch" }, 
    function(data){
        $('#batchNoThnBlnPaymentList').empty();
        $('#batchNoThnBlnPaymentList').append(data);
    });
}

function getTglNya()
{
    var thnBln = $("#batchNoThnBlnPaymentList").val();

    if(thnBln == "all")
    {
        $('#formPaymentList').submit();
    }else{
        pleaseWait();
        $.post( "../invoiceRegister/halPostMailInv.php",
            { aksi : "getBatchNoTglPaymentList",thnBln : thnBln }, 
            function(data){
                $('#batchNoTglPaymentList').empty();
                $('#batchNoTglPaymentList').append(data);
                doneWait();
                getDataByBatch();
        });
        
    }
}

function getDataByBatch()
{
    var thnBln = $("#batchNoThnBlnPaymentList").val();
    var tgl = $("#batchNoTglPaymentList").val();

    if(tgl == "")
    {

    }else{
        pleaseWait();

        loadIframe('iframeList', '');
        loadIframe('iframeList', 'templates/halPaymentAddListDetail.php?aksi=ketikSearchByBatchNo&thnbln='+thnBln+'&tgl='+tgl);
    }

}

function printPaymentAddList()
{
    var thnBln = $("#batchNoThnBlnPaymentList").val();
    var tgl = $("#batchNoTglPaymentList").val();
    setTimeout(function()
    {
        $('#formPrintPaymentListAdd').attr('action', 'templates/halPaymentPrintList.php?thnBln='+thnBln+'&tgl='+tgl);
        formPrintPaymentListAdd.submit();
    }, 250);
}

function exportExcelPaymentList()
{
    var thnBln = $("#batchNoThnBlnPaymentList").val();
    var tglNya = $("#batchNoTglPaymentList").val();

    if (thnBln != "all")
    {
        var thn = thnBln.substring(0,4);
        var bln = thnBln.substring(4,6);

        thnBln = thn+"-"+bln+"-"+tglNya;
    }
    
    $("#txtBatchNoExport").val(thnBln);
    formExportPaymentList.submit();
}

function exportPrintByBank(type)
{
    var thnBln = $("#batchNoThnBlnPaymentList").val();
    var tgl = $("#batchNoTglPaymentList").val();
    if(type == "")
    {
        alert("Select Export or Print By Bank");
    }
    else if(type == "export")
    {
        if (thnBln != "all")
        {
            var thn = thnBln.substring(0,4);
            var bln = thnBln.substring(4,6);

            thnBln = thn+"-"+bln+"-"+tglNya;
        }
        $("#txtBatchNoExportByBank").val(thnBln);
        formExportPaymentListByBank.submit();
    }
    else if(type == "print")
    {
        $('#formPrintPaymentListAdd').attr('action', 'templates/halPaymentPrintListByBank.php?thnBln='+thnBln+'&tgl='+tgl);
        formPrintPaymentListAdd.submit();
    }
    $("#slcCetakByBank").val("");
}

function paidPaymentList(id,type,typeAdv)
{
    var answer  = confirm('Are you sure have Paid..??');

    if(answer)
    {
        pleaseWait();
        $.post( "../invoiceRegister/halPostMailInv.php",
        { aksi : "paidByPass",id : id,type : type,typeAdv : typeAdv }, 
            function(data){
                $('#formPaymentList').submit();return false;
        });
    }
}

function delPaymentList(transNoDel,type)
{
    var answer  = confirm('Are you sure want to Delete?');

    if(answer)
    {
        pleaseWait();
        $.post( "../invoiceRegister/halPostMailInv.php",
        { aksi : "delPaymentList",type : type,transNoDel : transNoDel }, 
            function(data){
                alert(data);
                $('#formPaymentList').submit();return false;
        });
    }
}

</script>


<div class="wrap">
	<div id="loaderImg" style="visibility:hidden;" class="pleaseWait">
    	<div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="picture/loading (115).gif" height="12"/>&nbsp;</div>
    </div>
  	<div class="namaAplikasi"> PAYMENT LIST </div>
</div>

<div class="kotakInvReg">

    <div class="kotakBtnAtas">
    	<form method="post" action="index.php" id="formIncoming">
        <input type="hidden" id="halaman" name="halaman" value="halIncoming" />
        </form>
        <form method="post" action="index.php" id="formProcess">
        <input type="hidden" id="halaman" name="halaman" value="halProcessAck" />
        </form>
        <form method="post" action="index.php" id="formPayment">
        <input type="hidden" id="halaman" name="halaman" value="halPaymentOutstanding"/>
        </form>
        <form method="post" action="index.php" id="formOutgoing">
        <input type="hidden" id="halaman" name="halaman" value="halOutgoing" />
        </form>
        <form method="post" action="index.php" id="formCari">
        <input type="hidden" id="halaman" name="halaman" value="halCari"/>
        </form>
        <form method="post" action="index.php" id="formPrintDist">
        <input type="hidden" id="halaman" name="halaman" value="halPrintDistribution"/>
        </form>
        <form method="post" action="index.php" id="formPaymentList">
        <input type="hidden" id="halaman" name="halaman" value="halPaymentList"/>
        </form>
        <form method="post" action="" id="formPrintPaymentListAdd" target="_blank">
        </form>
        <button class="btnStandar" id="btnIncoming" title="INCOMING MAIL / DATA INVOICE" onclick="$('#formIncoming').submit();return false;">
            <table width="79" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/documents-stack.png"/></td>
                <td align="left">INCOMING</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnInvProcess" title="MAIL / DATA PROCESS" onclick="$('#formProcess').submit();return false;">
            <table width="77" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/wand.png"/></td>
                <td align="left">INVOICE PROCESS</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPayment" title="PAYMENT TRANSACTION" onclick="$('#formPayment').submit();return false;">
            <table width="77" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/credit-cards.png"/></td>
                <td align="left">PAYMENT</td> 
            </tr>
            </table>
        </button>
        <button id="btnPaymentListAdd" class="btnStandarTabPilih" title="PAYMENT LIST" onclick="return false;">
            <table width="100" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/document-sticky-note.png"/> </td>
                <td align="left">PAYMENT LIST</td>
              </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnOutgoing" title="OUTGOING MAIL / DATA INVOICE" onclick="$('#formOutgoing').submit();return false;">
            <table width="81" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/document-export.png"/></td>
                <td align="left">OUTGOING</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPrint" title="PRINT MAIL / DATA INVOICE" onclick="$('#formPrintDist').submit();return false;">
            <table width="58" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/printer.png"/> </td>
                <td align="left">PRINT</td>
              </tr>
            </table>
        </button>
        <button id="btnSearch" class="btnStandar" title="SEARCHING DATA" onclick="$('#formCari').submit();return false;">
            <table width="70" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/magnifier.png"/> </td>
                <td align="left">SEARCH</td>
              </tr>
            </table>
        </button>        
    </div>
    
    <div class="kotakBtnTengah" style="margin-top:5px;">
    	<span class="fontSpanBatch">BATCHNO</span>&nbsp;
  		<select class="elementMenu" id="batchNoThnBlnPaymentList" name="batchNoThnBlnPaymentList" style="width:75px;height:26px;font-size:12px;background-color: #f5f5f5;" title="CHOOSE YEAR & MONTH" onchange="getTglNya();">
        </select>
        <div id="idTdMenuTgl" style="position:absolute;top:0px;left:145px; width:60px;">
        <select class="elementMenu" id="batchNoTglPaymentList" name="batchNoTglPaymentList" style="width:45px;height:26px;font-size:12px;background-color: #f5f5f5;" title="CHOOSE DATE" onchange="getDataByBatch();">
        </select>
        </div>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        |
        <button class="btnStandar" id="btnRefresh" title="" onclick="$('#formPaymentList').submit();return false;">
            <table width="76" height="24">
            <tr>
                <td align="center" width="20"><img src="picture/arrow-circle-315.png"/></td>
                <td align="left">REFRESH</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPrintPaymentAddList" title="" onclick="printPaymentAddList();">
            <table width="150" height="24">
            <tr>
                <td align="center" width="20"><img src="picture/printer.png"/></td>
                <td align="left">PRINT PAYMENT LIST</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnExportExcel" title="Export EXCEL" onclick="exportExcelPaymentList();">
            <table width="60" height="24">
            <tr>
                <td align="center" width="20"><img style="width: 16px;vertical-align: top;" src="picture/fileXls.png"/></td>
                <td align="left">Export</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandarDis" id="btnPrintPaymentDelList" title="Delete List" disabled="disabled">
            <table width="70" height="24">
            <tr>
                <td align="center" width="20"><img src="picture/document--minus.png"/></td>
                <td align="left">DELETE</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandarDis" id="btnPaymentListPaid" title="PAID" disabled="disabled">
            <table width="60" height="24">
            <tr>
                <td align="center" width="20"><img style="width: 16px;vertical-align: top;" src="picture/money.png"/></td>
                <td align="left">PAID</td> 
            </tr>
            </table>
        </button>
        <select class="elementMenu" id="slcCetakByBank" style="width:150px;height:26px;font-size:12px;background-color: #f5f5f5;" title="PRINT / EXPORT BY BANK" onchange="exportPrintByBank($(this).val())">
            <option value="">Select By Bank</option>
            <option value="export">Export By Bank</option>
            <option value="print">Print By Bank</option>
        </select>
    </div>
    
    <div class="kotakIframe">        
        <iframe width="100%" height="100%" src="" target="iframeList" name="iframeList" id="iframeList" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes"></iframe>
    </div>

</div>
<form name="formExportPaymentList" method="post" action="halPostMailInv.php" target="_blank">
    <input type="hidden" id="aksi" name="aksi" value="actionExportPaymentList"/>
    <input type="hidden" id="txtBatchNoExport" name="txtBatchNoExport" value=""/>
</form>
<form name="formExportPaymentListByBank" method="post" action="halPostMailInv.php" target="_blank">
    <input type="hidden" id="aksi" name="aksi" value="actionExportPaymentListByBank"/>
    <input type="hidden" id="txtBatchNoExportByBank" name="txtBatchNoExportByBank" value=""/>
</form>