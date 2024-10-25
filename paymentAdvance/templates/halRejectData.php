<?php 
    require_once("../configPaymentAdvance.php");
    $idEdit = $_GET['idEdit'];
    $idEditSettle = $_GET['idEditSettle'];
    $rejectName = $_GET['rejectName'];
?>

<!DOCTYPE HTML>
<script type="text/javascript" src="../js/jquery-1.8.0.js"></script>
<script type="text/javascript" src="../js/masks.js"></script>
<script type="text/javascript" src="../js/paymentAdvance.js"></script>

<link rel="stylesheet" type="text/css" href="../css/paymentAdvance.css"> 
<link rel="stylesheet" type="text/css" href="../css/button.css"/>
<link rel="stylesheet" type="text/css" href="../css/table.css"/>

<script type="text/javascript">
    function saveRejectData()
    {
        var formData = new FormData();

        var txtReason = $("#txtRemarkReject").val();

        if(txtReason == "")
        {
            alert("Reason Reject Empty..!!");
            return false;
        }

        formData.append('aksi', "saveDataReject");
        formData.append('txtRemarkReject', $("#txtRemarkReject").val());
        formData.append('idReject', $("#txtIdReject").val());
        formData.append('idRejectSettle', $("#txtIdRejectSettle").val());
        formData.append('rejectName', $("#txtRejectName").val());

        $('#loaderImg').show();

        $.ajax({
            url: '../halPostPaymentRequest.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: 'post',
            success: function(dataNya){
                alert(dataNya);
                $("#txtIdReject").val('');
                $("#txtRejectName").val('');
                parent.tb_remove(false);
                $('#loaderImg').show();
                parent.doneWait();
                parent.reloadPage();
            }
            ,dataType:"json"
        });
    }

    function tutupForm(sure)
    {
        var answer  = confirm('Are you sure want to Close?');
        if(answer)
        {
            $("#txtIdReject").val('');
            $("#txtRejectName").val('');
            parent.tb_remove(false);
            $('#loaderImg').show();
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

<div id="idHalRejectData" >
    <table cellpadding="0" cellspacing="0" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;width:100%;" border="0" align="center">
        <tr>
            <td align="center" height="30" colspan="6"><span style="font-size:16px;"><u>REASON REJECT</u></span></td>
        </tr>
        <tr style="padding-top:20px;">
            <td style="width:15%;vertical-align:top;">Reason Reject :</td>
            <td style="width:50%;">
                <textarea id="txtRemarkReject" class="elementInput" rows="5" cols="51" style="height:70px;"></textarea>
            </td>
            <td style="width:35%;text-align:left;">
                <input type="hidden" id="txtIdReject" value="<?php echo $idEdit; ?>">
                <input type="hidden" id="txtIdRejectSettle" value="<?php echo $idEditSettle; ?>">
                <input type="hidden" id="txtRejectName" value="<?php echo $rejectName; ?>">
                <button class="btnStandar" onclick="saveRejectData();return false;">
                    <table width="53" height="30">
                    <tr>
                        <td align="center" width="20"><img src="../picture/disk-black.png"/></td>
                        <td align="left">SAVE</td>
                    </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="tutupForm('Y');">
                    <table border="0" width="63" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                            <td align="left">CLOSE</td>
                        </tr>
                    </table>
                </button>
            </td>
        </tr>
    </table>

    
</div> 

</HTML>