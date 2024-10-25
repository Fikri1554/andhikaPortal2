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

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family:Arial;">
    <tr>
        <td class="tabelBorderBottomNull" valign="middle" align="center" height="40" style="font-size:25px;text-decoration:underline;font-family:Arial Black;">
            <b>Form Request Payment & Advance</b>
        </td>
    </tr>
    <tr>
        <td class="tabelBorderTopBottomNull" align="center" style="font-size:20px;font-family:Arial Black;"></td>
    </tr>
    <tr>
        <td class="tabelBorderTopBottomNull">&nbsp;</td>
    </tr>
    <tr>
    	<td align="center" class="tabelBorderTopBottomNull">
        	<table width="940" cellpadding="0" cellspacing="0" style="font-size:16px;">
                <tr>
                    <td height="30" width="470">Request Name : {reqName}</td>
                    <td height="30" width="470">Company : {compName}</td>
                </tr>
                <tr>
                    <td height="30" width="470">Request Date : {entryDate}</td>
                    <td height="30" width="470">Divisi : {divisi}</td>
                </tr>
                <tr>
                    <td height="30" width="470">Barcode : {barcode}</td>
                    <td height="30" width="470">Batch No : {batchNo}</td>
                </tr>
                <tr>
                    <td height="30" align="center" colspan="2" style="font-size:18px;"><b>Total : {curr} {amount}</b></td>
                </tr>
                <tr>
                    <td height="30" colspan="2">Terbilang : <i>{terbilang}</i></td>
                </tr>
                <tr>
                    <td height="30" colspan="2">Remark : {remark}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td class="tabelBorderTopBottomNull">&nbsp;</td>
    </tr>
    <tr><td height="10" class="tabelBorderTopBottomNull"></td></tr>
    <tr><td class="tabelBorderTopBottomNull">&nbsp;</td></tr>
    <tr>
    	<td align="center" class="tabelBorderTopNull">
        	<table width="940" cellpadding="0" cellspacing="0">
            <tr>
            	<td width="246" height="130" align="center" valign="top">
                	<table width="80%" cellpadding="0" cellspacing="0" class="">
                        <tr style="font-size:15px;">
                        	<td height="40" align="center"><i>Confirm By</i></td></tr>
                        <tr>
                            <td height="75" align="center" class="tabelBorderBottomJust" style="vertical-align:bottom;">
                                <img src="./picture/sign.jpg" style="width:160px;"><br>
                                {fullNameConfirm}
                            </td>
                        </tr>
                        <tr valign="top" style="font-size:10px;text-align:center;">
                            <td height="40">&nbsp;{dateConfirm}</td>
                        </tr>
                	</table>
                </td>
                <td width="314" valign="top" align="center">
                </td>
                <td width="246" height="130" align="center" valign="top">
                </td>
                <td width="246" align="right" valign="top">
                    <table width="80%" cellpadding="0" cellspacing="0" class="">
                        <tr style="font-size:15px;">
                            <td height="40" align="center"><i>Pemohon</i></td>
                        </tr>
                        <tr>
                            <td height="75" align="center" class="tabelBorderBottomJust" style="vertical-align:bottom;">
                                <img src="./picture/sign.jpg" style="width:170px;"><br>
                                {reqName}
                            </td>
                        </tr>
                        <tr valign="bottom">
                            <td height="28" align="center" style="font-style:italic;font-family:Arial Narrow;font-size:12px;color:#666;">
                                {entryDate}<br>{printBy}&nbsp;
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </table>
        </td>
    </tr>
</table>
  
</center>
</body>
</html>