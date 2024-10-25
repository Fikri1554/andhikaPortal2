<script type="text/javascript" src="./../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="./js/payment.js"></script>

<link rel="stylesheet" type="text/css" href="./../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>

<script type="text/javascript">

window.onload = function()
{
	getDataByBatch();
    disabledBtn('btnVerifyAction');
}

function viewData(id,type)
{
    $('#hrefThickbox').attr('href', 'templates/halVerifyView.php?id='+id+'&type='+type+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=575&width=950&modal=true');
    $('#hrefThickbox').click();
}

function getDataByBatch()
{
    pleaseWait();
    loadIframe('iframeList', '');
    loadIframe('iframeList', 'templates/halVerifyListDetail.php');
}

function closePage(sure)
{
    $('#formVerify').submit();
}

</script>

<a class="thickbox" id="hrefThickbox"></a>
<div class="wrap">
	<div id="loaderImg" style="visibility:hidden;" class="pleaseWait">
    	<div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="picture/loading (115).gif" height="12"/>&nbsp;</div>
    </div>
  	<div class="namaAplikasi"> VERIFY </div>
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
        <form method="post" action="index.php" id="formVerify">
        <input type="hidden" id="halaman" name="halaman" value="halVerify"/>        
        </form>
        <input type="hidden" id="txtIdVerify" value="">
        <input type="hidden" id="txtTypeVerify" value="">
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
        <button id="btnVerify" class="btnStandarTabPilih" title="VERIFY DATA" onClick="">
            <table width="90" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/hourglass--pencil.png"/> </td>
                <td align="left">VERIFY LIST</td>
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
        <button class="btnStandar" id="btnRefresh" title="" onclick="$('#formVerify').submit();return false;">
            <table width="76" height="24">
            <tr>
                <td align="center" width="20"><img src="picture/arrow-circle-315.png"/></td>
                <td align="left">REFRESH</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnVerifyAction" title="Verify Data" onclick="verifyData('','');">
            <table width="76" height="24">
            <tr>
                <td align="center" width="20"><img src="picture/tick.png"/></td>
                <td align="left">VERIFY</td> 
            </tr>
            </table>
        </button>
    </div>
    
    <div class="kotakIframe">        
        <iframe width="100%" height="100%" src="" target="iframeList" name="iframeList" id="iframeList" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes"></iframe>
    </div>

</div>
<script type="text/javascript">
    function verifyData(idNya,type)
    {
        if(idNya == "")
        {
            var cfm = confirm("Verify Data..??");

            if(cfm)
            {
                pleaseWait();
                var id = $("#txtIdVerify").val();
                var typeNya = $("#txtTypeVerify").val();

                $.post( "halPostMailInv.php",
                { aksi:'verifyData', id:id, type:typeNya }, 
                function( data )
                {
                    alert(data);
                    $('#formVerify').submit();
                });
            }
        }else{
            $.post( "halPostMailInv.php",
            { aksi:'verifyData', id:idNya, type:type }, 
            function( data )
            {
                alert(data);
                $('#formVerify').submit();
            });
        }
    }
</script>