<html>
<script type="text/javascript" src="js/invReg.js"></script>
<script language="javascript">
function printpr()
{
	if((navigator.userAgent.indexOf("MSIE") != -1 ) || (!!document.documentMode == true )) //IF IE > 10
    {
    	var OLECMDID = 7;
		/* OLECMDID values:
		* 6 - print
		* 7 - print preview
		* 1 - open window
		* 4 - Save As
		*/
		
		var PROMPT = 1; // 2 DONTPROMPTUSER
		var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
		document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
		
		WebBrowser1.ExecWB(OLECMDID, PROMPT); 
		WebBrowser1.outerHTML = "";
    }  
	else
	{
		window.print();
	}
} 
</script>


<style>
</style>

<link href="css/laporanInv.css" rel="stylesheet" type="text/css">
<link href="../css/laporanInv.css" rel="stylesheet" type="text/css">
<link href="css/tableReport.css" rel="stylesheet" type="text/css">
<link href="../css/tableReport.css" rel="stylesheet" type="text/css">

<body onLoad="printpr();" onBlur="open('', '_self').close();">
<center>

<table width="100%" cellpadding="0" cellspacing="0" style="font-family:Arial;">
    
<tr>
    <td valign="middle" align="center" height="40" style="font-size:25px;font-family:Arial Black;"><b>{compName}</b></td>
</tr>
<tr>
    <td align="center" style="font-size:20px;text-decoration:underline;font-family:Arial Black;"><b>{lblPageVoucher}</b></td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<tr>
	<td align="center">
    	<table width="940" cellpadding="0" cellspacing="0" style="font-size:16px;">
        <tr>
        	<td height="30">&nbsp;</td>
            <td align="right"><b>No.</b></td>
            <td width="254">&nbsp;&nbsp;&nbsp;<i>{voucher}&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;{reference}</i></td>
        </tr>
        <tr>
            <td height="30" width="630">&nbsp;&nbsp;&nbsp;Trans No : {transNo} / {barcodeNya}</td>
            <td width="54" align="right"><b>Date :</b></td>
            <td>&nbsp;&nbsp;&nbsp;{datePaid}</td>
        </tr>
        </table>
    </td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td align="center">
    	<table width="940" height="220" cellpadding="5" cellspacing="0">
        <tr align="center" style="font-weight:bold;font-size:16px;font-family:Arial Black;">
        	<td width="513" height="40" class="tabelBorderRightNull">Particulars</td>
            <td width="62" class="tabelBorderRightNull">Vsl</td>
            <td width="142" class="tabelBorderRightNull">A/C Code</td>
            <td width="241" class="tabelBorderAll">Amount</td>
        </tr>
        <tr style="font-size:14px;">
        	<td class="tabelBorderLeftJust" height="20"><b><i>{credAccName}&nbsp;</i></b></td>
            <td class="tabelBorderLeftJust">&nbsp;</td>
            <td class="tabelBorderLeftJust" align="center" style="letter-spacing:10px;">{credAcc}&nbsp;</td>
            <td class="tabelBorderTopBottomNull">&nbsp;</td>
        </tr>
        {grupItem}
        <tr>
            <td class="tabelBorderAll" align="center" height="20" style="font-weight:bold;font-size:15px;font-family:Arial Black;font-weight: bold;">Total</td>
            <td class="tabelBorderLeftRightNull" align="center" colspan="2" style="font-size:15px;font-weight: bold;"><span style="letter-spacing:5px;">{initBank}&nbsp;{bankCode}&nbsp;</span> {typeDrCr}</td>
            <td class="tabelBorderAll">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:15px;font-weight: bold;">
                    <tr>
                        <td align="right" width="25%">{totalCurr}</td>
                        <td align="right" width="75%">{totalAmount}&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        </table>
    </td>
</tr>

<tr><td height="10"></td></tr>

<tr>
	<td align="center">
    	<table width="940" cellpadding="0" cellspacing="0">
        <tr>
        	<td height="40" class="" align="left" colspan="2" style="font-size:15px;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Method</i>
            &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{paymentMethod}</td>
        </tr>
        <tr valign="top" style="font-size:15px;">
            <td width="138" height="40" class="">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Amount</i>&nbsp;:</td>
            <td width="800" style="font-family:Arial Narrow;">
            == {totalAmountWords} ==</td>
        </tr>
        </table>
    </td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr>
	<td align="center">
    	<table width="940" cellpadding="0" cellspacing="0">
        <tr>
        	<td width="246" height="130" align="center" valign="top">
            	<table width="80%" cellpadding="0" cellspacing="0" class="">
                <tr style="font-size:15px;">
                	<td height="40" align="center"><i>Received By</i></td></tr>
                <tr><td height="75" class="tabelBorderBottomJust">&nbsp;</td></tr>
                <tr valign="bottom" style="font-size:10px;"><td height="40">&nbsp;</td></tr>
            	</table>
            </td>
            <td width="314" valign="top" align="center">
            	<table width="99%" cellpadding="0" cellspacing="0">
                <tr style="font-size:13px;font-family:Arial Black;" align="center">
                	<td height="30" class="tabelBorderRightNull">Descriptions</td>
                    <td class="tabelBorderAll">Checked By</td>
                </tr>
                <tr style="font-size:13px;">
                	<td height="25" class="tabelBorderTopRightNull">&nbsp;{lblPageVoucher}</td><td class="tabelBorderTopNull">&nbsp;</td></tr>
                <tr style="font-size:13px;">
                	<td height="25" class="tabelBorderTopRightNull">&nbsp;Cheque</td><td class="tabelBorderTopNull">&nbsp;</td></tr>
                <tr style="font-size:13px;">
                	<td height="25" class="tabelBorderTopRightNull">&nbsp;Cheque Signed</td><td class="tabelBorderTopNull">&nbsp;</td></tr>
                <tr style="font-size:13px;">
                	<td height="25" class="tabelBorderTopRightNull">&nbsp;Mailed By</td><td class="tabelBorderTopNull">&nbsp;</td></tr>
                <tr valign="bottom">
                    <td height="28" align="right" style="font-style:italic;font-family:Arial Narrow;font-size:12px;color:#666;" colspan="2">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{ctkLabel}
                        </td>
                    </tr>
            	</table>
            </td>
            <td width="378" align="right" valign="top">
            	<table width="99%" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td>
                        <table width="100%" cellpadding="0" cellspacing="0" class="tabelBorderAll">
                        	<tr align="center" style="font-size:13px;">
                                <td height="30">Additional Entries</td>
                            </tr>
                            <tr style="font-size:13px;">
                                <td height="100" valign="top" style="padding:2px;">&nbsp;</td></tr>
                        </table>
                        </td>
                    </tr>
                    <tr valign="bottom">
                        <td height="28" align="right" style="font-style:italic;font-family:Arial Narrow;font-size:12px;color:#666;">
                            {printBy}&nbsp;
                        </td>
                    </tr>
            	</table>
            </td>
        </tr>
        </table>
    </td>
</tr>

<tr>
	<td align="center">
    	<table width="952" cellpadding="0" cellspacing="0">
        <tr>
        	<td></td>
        </tr>
        </table>
    </td>
</tr>


</table>
  
</center>
</body>
</html>