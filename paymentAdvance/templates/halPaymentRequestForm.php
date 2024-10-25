<?php 
    require_once("../configPaymentAdvance.php");

    $aksi = $_GET['aksi'];

    $idEdit = "";
    $batchno = date('Ymd');
    $entryDate = date('d/m/Y');
    $reqName = "";
    // $senderVendor = "";
    $idCmp = "";
    $idDiv = "";
    $invDate = "";
    $dueDay = "";
    $dueDate = "";
    $invNo = "";
    $amount = "";
    $currNya = "";
    $remark = "";
    $displayMore = "";
    $voyageNo = "";
    $vesselCode = "";
    $thnVoyage = "";
    $typeDoc = "";
    $labelForm = "ADD FORM PAYMENT & ADVANCE";

    $barcodeNo = $CPaymentAdv->getNewBarcode();
    $barcode = "P".$CPaymentAdv->getFormatNo($barcodeNo,7);

    if($aksi == "edit")
    {
        $idEdit = $_GET['idEdit'];
        $displayMore = "none";
        $labelForm = "EDIT FORM PAYMENT & ADVANCE";

        $query = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM datapayment WHERE id = '".$idEdit."' AND st_delete = '0';", $CkoneksiPaymentAdv->bukaKoneksi());
        while($row = $CkoneksiPaymentAdv->mysqlFetch($query))
        {
            $batchno = $row['batchno'];
            $entryDate = $CPublic->convTglNonDB($row['entry_date']);
            $reqName = $row['request_name'];
            //$senderVendor = $row['sendervendor'];
            $idCmp = $row['init_company'];
            $idDiv = $row['divisi'];
            $barcode = $row['barcode'];
            $barcodeNo = $row['barcode_no'];
            $invDate = $CPublic->convTglNonDB($row['invoice_date']);
            $dueDay = $row['due_day'];
            $dueDate = $CPublic->convTglNonDB($row['invoice_due_date']);
            $invNo = $row['mailinvno'];
            $amount = number_format($row['amount'],2);
            $currNya = $row['currency'];
            $remark = $row['remark'];
            $voyageNo = $row['voyage_no'];
            $vesselCode = $row['vessel_code'];

            if($row['doc_type'] == "advance")
            {
                $typeDoc = "checked=\"checked\"";
            }

            $dt = explode("/", $entryDate);
            $thnVoyage = $dt[2];
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

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css"
    media="screen">
</LINK>
<link rel="stylesheet" type="text/css" href="../css/paymentAdvance.css">
<link rel="stylesheet" type="text/css" href="../css/button.css" />
<link rel="stylesheet" type="text/css" href="../css/table.css" />

<script type="text/javascript">
function saveData() {
    var formData = new FormData();
    var chkAdvance = $('#chkAdvance').is(":checked");

    if ($("#txtReqName").val() == "") {
        pesanError('Request Name Empty..!!', 'txtReqName');
        return false;
    }

    if ($("#slcCompany").val() == "") {
        pesanError('Company Empty..!!', 'slcCompany');
        return false;
    }

    if ($("#slcUnit").val() == "") {
        pesanError('Divisi Empty..!!', 'slcUnit');
        return false;
    }

    if ($("#txtAmount").val() == "") {
        pesanError('Amount Empty..!!', 'txtAmount');
        return false;
    }

    if ($("#txtCurrency").val() == "") {
        pesanError('Currency Empty..!!', 'txtCurrency');
        return false;
    }

    var fileDataNya = $("#fileUploadNya").prop('files')[0];
    var cekFile = $("#fileUploadNya").val();

    formData.append('aksi', "saveDataPaymentRequest");
    formData.append('idEdit', $("#txtIdEdit").val());
    formData.append('batchno', $("#txtBatchno").val());
    formData.append('txtEntryDate', $("#txtEntryDate").val());
    formData.append('txtReqName', $("#txtReqName").val());
    //formData.append('lblSenderVendorAccount', $("#lblSenderVendorAccount").text());
    //formData.append('txtSenderVendor', $("#txtSenderVendor").val());
    formData.append('slcInitCompany', $("#slcCompany").val());
    formData.append('slcNameCompany', $("#slcCompany option:selected").text());
    formData.append('slcUnit', $("#slcUnit").val());
    formData.append('slcUnitName', $("#slcUnit option:selected").text());
    formData.append('txtBarcode', $("#txtBarcode").val());
    formData.append('txtBarcodeNo', $("#txtBarcodeNo").val());
    formData.append('txtInvoiceDate', $("#txtInvoiceDate").val());
    formData.append('txtDueDay', $("#txtDueDay").val());
    formData.append('txtDueDate', $("#txtDueDate").val());
    formData.append('txtNoInvoice', $("#txtNoInvoice").val());
    formData.append('txtAmount', $("#txtAmount").val());
    formData.append('txtCurrency', $("#txtCurrency").val());
    formData.append('txtRemark', $("#txtRemark").val());
    formData.append('fileData', fileDataNya);
    formData.append('cekFile', cekFile);
    formData.append('vslCode', $("#slcVessel").val());
    formData.append('vslName', $("#slcVessel option:selected").text());
    formData.append('voyageNo', $("#slcVoyage").val());
    formData.append('chkAdvance', chkAdvance);

    $('#loaderImg').show();

    $.ajax({
        url: '../halPostPaymentRequest.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        type: 'post',
        success: function(dataNya) {
            alert(dataNya);
            if ($('#moreData').is(":checked")) {
                var now = new Date();
                var day = ("0" + now.getDate()).slice(-2);
                var month = ("0" + (now.getMonth() + 1)).slice(-2);
                var dateNow = (day) + "/" + (month) + "/" + now.getFullYear();

                $("#txtIdEdit").val("");
                $("#txtEntryDate").val(dateNow);
                $("#txtReqName").val("");
                //$("#txtSenderVendor").val("");
                $("#slcCompany").val("");
                $("#slcUnit").val("");
                $("#txtBarcode").val("");
                $("#txtBarcodeNo").val("");
                $("#txtInvoiceDate").val("");
                $("#txtDueDay").val("");
                $("#txtDueDate").val("");
                $("#txtNoInvoice").val("");
                $("#txtAmount").val("");
                $("#txtCurrency").val("");
                $("#txtRemark").val("");
                $("#fileUploadNya").val("");
                $("#slcVoyage").val("");
                $("#slcVessel").val("");

                getBarcode();

                document.getElementById("moreData").checked = false;
                $('#loaderImg').hide();
            } else {
                parent.tb_remove(false);
                parent.reloadPage();
            }
        },
        dataType: "json"
    });
}

function tutupForm(sure) {
    var answer = confirm('Are you sure want to Close?');
    if (answer) {
        parent.tb_remove(false);
        $('#loaderImg').show();
        parent.document.onmousedown = parent.disableLeftClick;
        parent.doneWait();
        parent.reloadPage();
    }
}

function getVoyage() {
    var thn = $("#txtEntryDate").val();
    var vsl = $("#slcVessel").val();

    $.post('../halPostPaymentRequest.php', {
            aksi: "getVoyageNo",
            thn: thn,
            vsl: vsl
        },
        function(data) {
            $("#slcVoyage").empty();
            $("#slcVoyage").append(data);
        },
        "json"
    );
}

function hanyaAngkaAmount() {
    amountMask = new Mask("#,###.##", "number");
    amountMask.attach(document.getElementById('txtAmount'));
}

function textCounter(field, countfield, maxlimit) {
    if (field.value.length > maxlimit)
        field.value = field.value.substring(0, maxlimit);
    else
        countfield.value = maxlimit - field.value.length;
}

function getBarcode() {
    $.post('../halPostPaymentRequest.php', {
            aksi: "getBarcodeNo"
        },
        function(data) {
            $("#txtBarcode").val(data['txtBarcode']);
            $("#txtBarcodeNo").val(data['txtBarcodeNo']);
        },
        "json"
    );
}

// function autoComplete()
// {
//     $("#lblSenderVendorAccount").text("");
//     $.post('../halPostPaymentRequest.php',
//     { aksi:"ketikAutoComplSender", param:$('#txtSenderVendor').val() },
//         function(data) 
//         {
//             $('#autoCompSender').show();
//             $('#autoCompSender').empty( '' );
//             $('#autoCompSender').append(data);
//         },
//         "json"
//     );
// }

// function dataSelectAutoComplete(val,account)
// {
//     $("#txtSenderVendor").val(val);
//     $("#lblSenderVendorAccount").text(account);
//     $("#autoCompSender").hide();
// }

function getDueDate() {
    var invDate = $("#txtInvoiceDate").val();
    var dueDay = $("#txtDueDay").val();

    $.post('../halPostPaymentRequest.php', {
            aksi: "getDueDate",
            invDate: invDate,
            dueDay: dueDay
        },
        function(data) {
            $("#txtDueDate").val(data);
        },
        "json"
    );
}
</script>
<style>
body {
    background-color: #f9f9f9;
}
</style>

<div id="loaderImg" style="display:none;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;&nbsp;Please Wait...&nbsp;<img
            src="../picture/loading (115).gif" height="12" />&nbsp;</div>
</div>

<div id="idHalTambahMailInv" onclick="$('#autoCompSender').css('display','none');">
    <table cellpadding="0" cellspacing="0" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;width:100%;"
        border="0" align="center">
        <tr>
            <td align="center" height="30" colspan="2"><span style="font-size:16px;"><?php echo $labelForm; ?></span>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Batchno</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="text" id="txtBatchno" class="elementInput" style="width:100px;"
                    value="<?php echo $batchno; ?>" disabled="disabled" />
                <input type="checkbox" id="chkAdvance" style="margin-left:30px;cursor:pointer;"
                    <?php echo $typeDoc; ?>>&nbsp;<label
                    style="font:12px sans-serif;font-weight:bold;color:#485a88;">Advance</label>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Entry Date</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <span id="spanInvoiceDate"><input type="text" id="txtEntryDate" class="elementInput" style="width:60px;"
                        value="<?php echo $entryDate; ?>" /></span>
                <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date"
                    onclick="displayCalendar(document.getElementById('txtEntryDate'),'dd/mm/yyyy',this, '', '', '193', '183');" />&nbsp;<span
                    class="spanKalender">(DD/MM/YYYY)</span>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Request Name <span style="color:red;">*</span></td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="text" id="txtReqName" class="elementInput" value="<?php echo $reqName; ?>"
                    style="width:188px;" />
            </td>
        </tr>
        <!-- <tr valign="top">
            <td style="width:25%;padding:2px;">Sender/Vendor <span style="color:red;">*</span></td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="text" class="elementInput" id="txtSenderVendor" value="<?php echo $senderVendor; ?>" style="width:307px;" oninput="autoComplete();">
                <div id="autoCompSender" class="overout" style="position:absolute;display:none;z-index:10;width:335px;height:300px;overflow:auto;border-color:#333;"></div>
                <span id="lblSenderVendorAccount"></span>
            </td>
        </tr> -->
        <tr>
            <td style="width:25%;padding:2px;">Vessel</td>
            <td style="width:75%;padding:2px;">
                <select id="slcVessel" class="elementMenu" style="width:320px;" onchange="getVoyage();">
                    <option value="">-- SELECT VESSEL --</option>
                    <?php echo $CPaymentAdv->menuVessel($vesselCode); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Voyage No</td>
            <td style="width:75%;padding:2px;">
                <select id="slcVoyage" class="elementMenu" style="width:320px;">
                    <?php echo $CPaymentAdv->getVoyageNo($voyageNo,$thnVoyage,$vesselCode); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Company <span style="color:red;">*</span></td>
            <td style="width:75%;padding:2px;">
                <select id="slcCompany" class="elementMenu" style="width:320px;">
                    <option value="">-- SELECT COMPANY --</option>
                    <?php echo $CPaymentAdv->menuCmp($idCmp); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Divisi <span style="color:red;">*</span></td>
            <td style="width:75%;padding:2px;">
                <select id="slcUnit" class="elementMenu" style="width:320px;">
                    <option value="">-- SELECT DIVISI --</option>
                    <?php echo $CPaymentAdv->menuUnit($idDiv); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Barcode</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="text" id="txtBarcode" class="elementInput" value="<?php echo $barcode; ?>" maxlength="8"
                    style="width:60px;" disabled>
                <input type="hidden" id="txtBarcodeNo" value="<?php echo $barcodeNo;?>">
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Invoice / Letter Date</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <span id="spanInvoiceDate">
                    <input type="text" id="txtInvoiceDate" class="elementInput" value="<?php echo $invDate; ?>"
                        style="width:60px;" />
                </span>
                <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date"
                    onclick="displayCalendar(document.getElementById('txtInvoiceDate'),'dd/mm/yyyy',this, '', '', '193', '183');" />&nbsp;<span
                    class="spanKalender">(DD/MM/YYYY)</span>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Due Date</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <div id="divDueDay" style="display:none;">&nbsp;</div>
                <div id="divDueDate" style="display:none;">&nbsp;</div>
                <input type="text" id="txtDueDay" class="elementInput" value="<?php echo $dueDay; ?>"
                    style="width:20px;" oninput="getDueDate();" />&nbsp;<span class="spanKalender">(Day)</span>
                <input type="text" id="txtDueDate" class="elementInput" value="<?php echo $dueDate; ?>"
                    style="width:60px;" />
                <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date"
                    onclick="displayCalendar(document.getElementById('txtDueDate'),'dd/mm/yyyy',this, '', '', '193', '183');"
                    id="imgCalDueDate" />&nbsp;<span class="spanKalender">(DD/MM/YYYY)</span>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Mail/Invoice No</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="text" id="txtNoInvoice" class="elementInput" value="<?php echo $invNo; ?>"
                    style="width:188px;">
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Amount <span style="color:red;">*</span></td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="text" id="txtAmount" class="elementInput" value="<?php echo $amount; ?>"
                    style="width:188px;text-align:right;" oninput="hanyaAngkaAmount();">
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Currency <span style="color:red;">*</span></td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <select id="txtCurrency" class="elementMenu" style="width:200px;">
                    <option value="">-- PLEASE SELECT --</option>
                    <?php echo $CPaymentAdv->menuCurrency($currNya); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">Remark</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <textarea id="txtRemark" class="elementInput" rows="5" cols="51" style="height:70px;"
                    oninput="textCounter(this, sisaRemarks, 200);"><?php echo $remark; ?></textarea>
                <input disabled="disabled" readonly type="text" id="sisaRemarks" value="200" style="width:23px">
            </td>
        </tr>
        <tr>
            <td style="width:25%;padding:2px;">File</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="file" name="fileUploadNya" id="fileUploadNya" class="btnStandar" style="width:250px"
                    title="File Upload">
                &nbsp <a style="cursor:pointer;" onclick="$('#fileUploadNya').val('');"> Clear </a>
            </td>
        </tr>
        <tr id="idTrMoreData" style="display:<?php echo $displayMore;?>">
            <td style="width:25%;padding:2px;">&nbsp;</td>
            <td class="elementTeks" style="width:75%;padding:2px;">
                <input type="checkbox" id="moreData" style="cursor:pointer;">&nbsp;More Data
            </td>
        </tr>
        <tr valign="top">
            <td height="10" valign="bottom" colspan="2">
                <div id="idErrorMsg" class="errorMsgInv" style="visibility:hidden;"><img
                        src="../picture/exclamation-red.png" />&nbsp;<span>&nbsp;</span>&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center" style="padding-top: 10px;">
                <input type="hidden" id="txtIdEdit" value="<?php echo $idEdit; ?>">
                <button class="btnStandar" onclick="tutupForm('Y');">
                    <table border="0" width="63" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/door-open-out.png" /></td>
                            <td align="left">CLOSE</td>
                        </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="saveData();return false;">
                    <table width="53" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/disk-black.png" /></td>
                            <td align="left">SAVE</td>
                        </tr>
                    </table>
                </button>
            </td>
        </tr>
    </table>
</div>

</HTML>