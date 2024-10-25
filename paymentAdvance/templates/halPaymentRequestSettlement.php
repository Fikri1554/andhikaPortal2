<?php 
    require_once("../configPaymentAdvance.php");

    $aksi = $_GET['aksi'];
    $labelForm = "FORM SETTLEMENT";
    $userId = $userIdSession;
    $cekUserAkses = $CPaymentAdv->cekAksesBtn($userId,"btn_payment_voucher");
    $displayFormUpload = "display:none;";

    if($cekUserAkses == "Y")
    {
        $displayFormUpload = "";
    }

    $idEdit = $_GET['idEdit'];
    $batchno = "";
    $reqName = "";
    $voyageNo = "";
    $vesselName = "";    
    $cmpName = "";
    $divisi = "";
    $barcode = "";    
    $invDate = "";
    $dueDay = "";
    $dueDate = "";
    $invNo = "";
    $amount = "";
    $currAmount = "";
    $ttlAxpense = "";
    $remarkExpense = "";
    $viewFileSettlement = "";
    $stFormDis = "";
    $stDisplayBtn = "";
    $viewFileSettlementUpload = "";
    $stDisplayBtnUploadSett = "";
    $stFormDisUpldSett = "";
    $stFormDisInput = "btnStandar";

    $query = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM datapayment WHERE id = '".$idEdit."' AND st_delete = '0';", $CkoneksiPaymentAdv->bukaKoneksi());
    while($row = $CkoneksiPaymentAdv->mysqlFetch($query))
    {
        $batchno = $row['batchno'];
        $reqName = $row['request_name'];
        $voyageNo = $row['voyage_no'];
        $vesselName = $row['vessel_name'];
        $cmpName = $row['company_name'];
        $divisi = $row['divisi'];
        $barcode = $row['barcode'];
        $invDate = $CPublic->convTglNonDB($row['invoice_date']);
        $dueDay = $row['due_day'];
        $dueDate = $CPublic->convTglNonDB($row['invoice_due_date']);
        $invNo = $row['mailinvno'];
        $amount = number_format($row['amount'],2);
        $currAmount = "(".$row['currency'].") ".$amount;        

        // if($row['st_settlement'] == "Y" AND $row['st_settlementCheck'] == "Y" AND $row['settlement_voucher_status'] == "Y")
        if($row['st_settlement'] == "Y" AND $row['st_settlementCheck'] == "Y")
        {
            $labelForm = "VIEW SETTLEMENT";
            $ttlAxpense = number_format($row['settlement_amount'],2);
            $remarkExpense = $row['settlement_remark'];
            $stFormDis = "disabled=\"disabled\"";
            $stDisplayBtn = "display:none;";

            if($row['settlement_file'] != "")
            {
                $viewFileSettlement ="<a href=\"./fileUploadBukti"."/".$row['settlement_file']."\" target=\"_blank\"> View File </a>";
            }

            if($row['settlement_upload_file'] != "")
            {
                $stFormDisInput = "btnStandarDis";
                $stFormDisUpldSett = "disabled=\"disabled\"";
                $stDisplayBtnUploadSett = "display:none;";
                $viewFileSettlementUpload ="<a href=\"./fileUploadBukti"."/".$row['settlement_upload_file']."\" target=\"_blank\"> View File </a>";
            }
        }
    }
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

    function saveData()
    {
        var formData = new FormData();

        var fileDataNya = $("#fileUpload").prop('files')[0];
        var cekFile = $("#fileUpload").val();

        if(cekFile == "")
        {
            pesanError('File Upload Empty..!!', 'fileUpload');
            return false;
        }
        
        formData.append('aksi', "saveDataPaymentRequestSettlement");
        formData.append('idEdit', $("#txtIdEdit").val());        
        formData.append('fileData', fileDataNya);
        formData.append('cekFile', cekFile);
        formData.append('txtExpense', $("#txtExpense").val());
        formData.append('txtRemark', $("#txtRemark").val());

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

                parent.tb_remove(false);
                parent.reloadPage();
                
            }
            ,dataType:"json"
        });
    }

    function saveDataUpload()
    {
        var formData = new FormData();
        var idPaymentAdv = $("#txtIdEdit").val();

        var fileDataNya = $("#filePenyelesaian").prop('files')[0];
        var cekFile = $("#filePenyelesaian").val();

        if(cekFile == "")
        {
            pesanError('File Upload Empty..!!', 'filePenyelesaian');
            return false;
        }
        
        formData.append('aksi', "saveDataPaymentRequestSettlementUpload");
        formData.append('idPaymentAdv', idPaymentAdv);        
        formData.append('fileData', fileDataNya);
        formData.append('cekFile', cekFile);

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

                parent.tb_remove(false);
                parent.document.onmousedown=parent.disableLeftClick;
                parent.doneWait();
                parent.reloadPage();
                
            }
            ,dataType:"json"
        });
    }

    function hanyaAngkaAmount()
    {
        amountMask = new Mask("#,###.##", "number");
        amountMask.attach(document.getElementById('txtExpense'));
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
            <td align="center" height="30" colspan="2"><span style="font-size:16px;"><?php echo $labelForm; ?></span></td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Batchno</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="text" id="txtBatchno" class="elementInput" style="width:100px;" value="<?php echo $batchno; ?>" disabled="disabled"/>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Request Name</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="text" id="txtReqName" class="elementInput" value="<?php echo $reqName; ?>" style="width:250px;" disabled="disabled"/>                
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Vessel</td>
            <td style="width:75%;padding:2px;">
                <input type="text" id="txtVessel" class="elementInput" value="<?php echo $vesselName; ?>" style="width:250px;" disabled="disabled"/> 
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Voyage No</td>
            <td style="width:75%;padding:2px;">
                <input type="text" id="txtVoyage" class="elementInput" value="<?php echo $voyageNo; ?>" style="width:250px;" disabled="disabled"/>
            </td>
        </tr>        
        <tr>
            <td style="width:25%;padding:2px;">Company</td>
            <td style="width:75%;padding:2px;">
                <input type="text" id="txtCompany" class="elementInput" value="<?php echo $cmpName; ?>" style="width:250px;" disabled="disabled"/>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Divisi</td>
            <td style="width:75%;padding:2px;">
                <input type="text" id="txtDivisi" class="elementInput" value="<?php echo $divisi; ?>" style="width:250px;" disabled="disabled"/>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Barcode</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="text" id="txtBarcode" class="elementInput" value="<?php echo $barcode; ?>" style="width:250px;" disabled="disabled"/>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Invoice / Letter Date</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <span id="spanInvoiceDate">
                    <input type="text" id="txtInvoiceDate" class="elementInput" value="<?php echo $invDate; ?>" style="width:60px;" disabled="disabled"/>
                </span>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Due Date</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <div id="divDueDay" style="display:none;">&nbsp;</div><div id="divDueDate" style="display:none;">&nbsp;</div>
                <input type="text" id="txtDueDay" class="elementInput" value="<?php echo $dueDay; ?>" style="width:20px;" oninput="getDueDate();" disabled="disabled"/>&nbsp;<span class="spanKalender">(Day)</span>
                <input type="text" id="txtDueDate" class="elementInput" value="<?php echo $dueDate; ?>" style="width:60px;" disabled="disabled"/>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Mail/Invoice No</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="text" id="txtNoInvoice" class="elementInput" value="<?php echo $invNo; ?>" style="width:188px;" disabled="disabled">
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Amount</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="text" id="txtAmount" class="elementInput" value="<?php echo $currAmount; ?>" style="width:188px;text-align:right;" disabled="disabled">
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Total Expenses</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="text" id="txtExpense" class="elementInput" value="<?php echo $ttlAxpense ?>" style="width:188px;text-align:right;" oninput="hanyaAngkaAmount();" <?php echo $stFormDis; ?>>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">File</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="file" name="fileUpload" id="fileUpload" class="btnStandar" style="width:250px" title="File Upload" <?php echo $stFormDis; ?>>
                &nbsp <a style="cursor:pointer;" onclick="$('#fileUpload').val('');"> Clear </a> &nbsp <?php echo $viewFileSettlement; ?>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Remark</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <textarea id="txtRemark" class="elementInput" rows="5" cols="51" style="height:70px;" oninput="textCounter(this, sisaRemarks, 200);" <?php echo $stFormDis; ?>><?php echo $remarkExpense; ?></textarea>
                <input disabled="disabled" readonly type="text" id="sisaRemarks" value="200" style="width:23px">
            </td>
        </tr>
        <tr style="<?php echo $displayFormUpload; ?>">
            <td style="width:25%;padding:2px;">Upload</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="file" name="filePenyelesaian" id="filePenyelesaian" class="<?php echo $stFormDisInput; ?>" style="width:250px" title="File Upload" <?php echo $stFormDisUpldSett; ?>>
                &nbsp <a style="cursor:pointer;" onclick="$('#filePenyelesaian').val('');"> Clear </a>&nbsp &nbsp 
                <button class="btnStandar" id="btnSettlementUpload" onclick="saveDataUpload();return false;" style="padding:4px;<?php echo $stDisplayBtnUploadSett; ?>">Upload</button>
                <?php echo $viewFileSettlementUpload; ?>
            </td>
        </tr>
        <tr valign="top">
            <td height="10" valign="bottom" colspan="2">
                <div id="idErrorMsg" class="errorMsgInv" style="visibility:hidden;"><img src="../picture/exclamation-red.png"/>&nbsp;<span>&nbsp;</span>&nbsp;</div>
            </td>
        </tr>        
        <tr>
            <td colspan="2" align="center" style="padding-top: 10px;">
                <input type="hidden" id="txtIdEdit" value="<?php echo $idEdit; ?>">
                <button class="btnStandar" onclick="tutupForm('Y');">
                    <table border="0" width="63" height="30">
                    <tr>
                        <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                        <td align="left">CLOSE</td>
                    </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="saveData();return false;" style="<?php echo $stDisplayBtn; ?>">
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