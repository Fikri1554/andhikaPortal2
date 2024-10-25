
<link href="css/paymentAdvance.css" rel="stylesheet" type="text/css" />
<link href="css/button.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
</script>

<a class="thickbox" id="hrefThickbox"></a>

<div class="wrap">
  	<div class="namaAplikasi"> PAYMENT & ADVANCE </div>
</div>

<div class="kotakPaymentAdv">

    <div class="kotakBtnAtas">
    	<form method="post" action="index.php" id="formPaymentReq">
            <input type="hidden" id="halaman" name="halaman" value="halPaymentRequest" />
        </form>

        <button class="btnStandar" id="btnPaymentReq" title="PAYMENT REQUEST" onclick="$('#formPaymentReq').submit();return false;">
            <table width="110" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/documents-stack.png"/></td>
                <td align="left">PAYMENT REQ.</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentCheck" title="CHECK" onclick="">
            <table width="77" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/tick.png"/></td>
                <td align="left">CHECK</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentApprove" title="APPROVE" onclick="">
            <table width="77" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/hourglass--pencil.png"/></td>
                <td align="left">APPROVE</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentRelease" title="RELEASE" onclick="">
            <table width="81" height="28">
            <tr>
                <td align="center" width="20"><img src="picture/thumb-up.png"/></td>
                <td align="left">RELEASE</td> 
            </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentPrepareForPayment" title="PREPARE FOR PAYMENT" onclick="">
            <table width="150" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/document--arrow.png"/> </td>
                <td align="left">PREPARE FOR PAYMENT</td>
              </tr>
            </table>
        </button>
        <button class="btnStandar" id="btnPaymentVoucher" title="VOUCHER" onclick="">
            <table width="58" height="28">
              <tr>
                <td align="center" width="20"><img src="picture/document-sticky-note.png"/> </td>
                <td align="left">VOUCHER</td>
              </tr>
            </table>
        </button>
    </div>
    
    <div class="kotakIframe" style="top:41px;height:495px;" id="idKotakFrame"> 
        <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center">
            
                <table cellpadding="0" cellspacing="0" width="99%">
                <tr align="center">
                    <td height="423" style="font-family:sans-serif;font-weight:bold;font-size:30px;color:#CCC;">PLEASE SELECT BUTTON ABOVE</td>
                </tr>
                </table>
            </td>
        </tr>
        </table>         	
    </div>

</div>