<?php 
    require_once("../configPaymentAdvance.php");

    $txtIdRelease = $_GET['idRelease'];
    $txtIdReleaseSettle = $_GET['idReleaseSettle'];
?>

<!DOCTYPE HTML>
<script type="text/javascript" src="../js/jquery-1.8.0.js"></script>
<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../../js/Parsers.js"></script>
<script type="text/javascript" src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../js/masks.js"></script>
<script type="text/javascript" src="../js/paymentAdvance.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
<link rel="stylesheet" type="text/css" href="../css/paymentAdvance.css"> 
<link rel="stylesheet" type="text/css" href="../css/button.css"/>
<link rel="stylesheet" type="text/css" href="../css/table.css"/>

<script type="text/javascript">

    function saveRelease()
    {        
        var txtIdRelease = $("#txtIdReleaseHide").val();
        var txtIdReleaseSettle = $("#txtIdReleaseSettleHide").val();
        var remarkNya = $("#txtRemarkRelease").val();
        
        $("#loaderImg").show();

        setTimeout(function()
        {            
            $.post('../halPostPaymentRequest.php',
            { aksi:"releaseData",idChecked : txtIdRelease, idCheckedSettle : txtIdReleaseSettle,remarkNya : remarkNya },
                function(data) 
                {
                    alert(data);
                    parent.tb_remove(false);
                    parent.reloadPage();
                },
            "json"
            );
        },300);
    }
    function tutupForm(sure)
    {
        var answer  = confirm('Are you sure want to Close?');
        if(answer)
        {   
            parent.tb_remove(false);
            $('#loaderImg').show();
            parent.document.onmousedown=parent.disableLeftClick;
            parent.doneWait();
            parent.reloadPage();
        }
    }
</script>
<style>
    body {background-color: #f9f9f9;}
</style>

<div id="loaderImg" style="display:none;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;</div>
</div>

<div id="idHalTambahMailInv" onclick="$('#autoCompSender').css('display','none');">
    <table cellpadding="0" cellspacing="0" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;width:100%;" border="0" align="center">
        <tr>
            <td align="center" height="30" colspan="2"><span style="font-size:16px;">Release</span></td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Remark</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <textarea id="txtRemarkRelease" class="elementInput" rows="5" cols="51" style="height:70px;" oninput="textCounter(this, sisaRemarks, 200);"></textarea>
                <input disabled="disabled" readonly type="text" id="sisaRemarks" value="200" style="width:23px">
            </td>
        </tr>
        <tr valign="top">
            <td height="10" valign="bottom" colspan="2">
                <div id="idErrorMsg" class="errorMsgInv" style="visibility:hidden;"><img src="../picture/exclamation-red.png"/>&nbsp;<span>&nbsp;</span>&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center" style="padding-top: 10px;">
                <input type="hidden" id="txtIdReleaseHide" value="<?php echo $txtIdRelease; ?>">
                <input type="hidden" id="txtIdReleaseSettleHide" value="<?php echo $txtIdReleaseSettle; ?>">
                <button class="btnStandar" onclick="tutupForm('Y');">
                    <table border="0" width="63" height="30">
                    <tr>
                        <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                        <td align="left">CLOSE</td>
                    </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="saveRelease();return false;">
                    <table width="53" height="30">
                    <tr>
                        <td align="center" width="20"><img src="../picture/disk-black.png"/></td>
                        <td align="left">SAVE</td>
                    </tr>
                    </table>
                </button>
            </td>
        </tr>
    </table>
</div> 

</HTML>