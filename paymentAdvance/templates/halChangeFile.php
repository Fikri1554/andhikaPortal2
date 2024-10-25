<?php 
    require_once("../configPaymentAdvance.php");

    $aksi = $_GET['aksi'];

    $idEdit = "";
    $reqName = "";
    $idCmp = "";

    $idEdit = $_GET['idEdit'];

    $query = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM datapayment WHERE id = '".$idEdit."' AND st_delete = '0';", $CkoneksiPaymentAdv->bukaKoneksi());
    while($row = $CkoneksiPaymentAdv->mysqlFetch($query))
    {
        $idCmp = $row['init_company'];
        $reqName = $row['request_name'];
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
function saveUpload() {
    var formData = new FormData();

    var slcTypeFile = $("#slcTypeFile").val();
    var cekFile = $("#fileUploadChange").val();
    var fileDataNya = $("#fileUploadChange").prop('files')[0];

    if (cekFile == "") {
        alert("File Upload Empty..!!");
        return false;
    }

    formData.append('aksi', "saveUploadChangeFile");
    formData.append('idPayment', $("#txtIdPaymentAdv").val());
    formData.append('fileData', fileDataNya);
    formData.append('cekFile', cekFile);
    formData.append('slcTypeFile', slcTypeFile);

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

            parent.tb_remove(false);
            parent.reloadPage();

        },
        dataType: "json"
    });
}


function searchData() {
    var txtBarcode = document.getElementById('txtSearch').value;

    if (txtBarcode == "") {
        alert("Text Search Empty..!!");
        return false;
    } else {
        $('#loaderImg').show();

        $.post('../halPostPaymentRequest.php', {
                aksi: "searchDataChangeFile",
                txtBarcode: txtBarcode
            },
            function(data) {
                $("#idBody").empty();
                $("#idBody").append(data);
                backPage()
                $('#loaderImg').hide();
            },
            "json"
        );
    }
}

function showFormUploadChange(id, cmp, dvs, brcd, amnt) {
    $("#txtIdPaymentAdv").val(id);

    $("#lblCompany").text(cmp);
    $("#lblDivisi").text(dvs);
    $("#lblBarcode").text(brcd);
    $("#lblAmount").text(amnt);

    $("#idTblFormSearch").hide();
    $("#idTblDataSearch").hide();
    $("#idTblFormUpload").show(100);
}

function showFormUpdate(id, cmp, dvs, brcd, amnt, settlementAmount, docType, transNo, settlementTransNo) {
    $("#txtIdPaymentAdv").val(id);
    $("#txtAmount").val('');

    $("#lblCompanyUpdate").text(cmp);
    $("#lblDivisiUpdate").text(dvs);
    $("#lblBarcodeUpdate").text(brcd);
    $("#lblAmountUpdate").text(amnt);
    $("#lblSettlementAmountUpdate").text(settlementAmount);
    $("#lblDocTypeUpdate").text(docType);
    $("#lblTransNoUpdate").text(transNo);
    $("#lblTransNoSettlementUpdate").text(settlementTransNo);

    $("#slcTypeData").val(docType).change();

    var slcCompany = document.getElementById("slcCompany");
    for (var i = 0; i < slcCompany.options.length; i++) {
        if (slcCompany.options[i].text === cmp.toUpperCase()) {
            slcCompany.selectedIndex = i;
            break;
        }
    }

    handleTypeUpdateChange();

    $("#idTblFormSearch").hide();
    $("#idTblDataSearch").hide();
    $("#idTblFormUpdate").show(100);
}


function saveUpdate() {
    const selectedType = $('#slcTypeUpdate').val();
    const amount = $('#txtAmount').val();
    const settlementAmount = $('#SettlementAmount').val();
    const idPayment = $('#txtIdPaymentAdv').val();
    const transNo = $('#transNo').val();
    const settlementTransNo = $('#settlementTransNo').val();
    const docType = $('#slcTypeData').val();
    const initCompany = $('#slcCompany').val();
    const companyName =
        const formData = new FormData();

    if (idPayment === "") {
        alert("ID Payment kosong..!!");
        return false;
    }

    formData.append('idPayment', idPayment);
    formData.append('slcTypeEdit', selectedType);
    formData.append('txtAmount', amount);
    formData.append('SettlementAmount', settlementAmount);

    if (selectedType === 'amount') {
        formData.append('idEdit', idPayment);
        formData.append('idPaymentSplit1', $('#txtIdPaymentSplit1_hidden').val());
        formData.append('idPaymentSplit2', $('#txtIdPaymentSplit2_hidden').val());
        formData.append('type_dbcr1_input', $('#txtIdPaymentSplit1').val());
        formData.append('type_dbcr2_input', $('#txtIdPaymentSplit2').val());
        formData.append('aksi', "saveUpdateAmount");
    } else if (selectedType === 'settlementamount') {
        formData.append('idEdit', idPayment);
        $('input[name^="txtIdPaymentSplitSettlement"]').each(function() {
            const id = $(this).attr('id').replace('_hidden', '');
            formData.append(id, $(this).val());
        });
        $('input[name^="txtAmountSettle"]').each(function() {
            formData.append($(this).attr('id'), $(this).val());
        });
        formData.append('aksi', 'saveUpdateSettlementAmount');
    } else if (selectedType === 'updategroup') {
        formData.append('idEdit', idPayment);
        formData.append('transNo', transNo);
        formData.append('aksi', 'updategroup');
    } else if (selectedType === 'updategroupsettlement') {
        formData.append('idEdit', idPayment);
        formData.append('settlementTransNo', settlementTransNo);
        formData.append('aksi', 'updategroupsettlement');
    } else if (selectedType === 'tipedata') {
        formData.append('idEdit', idPayment);
        formData.append('docType', docType);
        formData.append('aksi', 'saveUpdateTypeData');
    } else if (selectedType === 'updatecompany') {
        formData.append('idEdit', idPayment);
        formData.append('initCompany', initCompany);
        formData.append('companyName', companyName);
        formData.append('aksi', 'saveUpdateCompany');
    }

    $.ajax({
        url: '../halPostPaymentRequest.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            try {
                response = typeof response === "string" ? response : JSON.stringify(response);
                response = response.trim();
                const data = JSON.parse(response);

                if (data.status === "success") {
                    alert(data.message);
                    searchData();
                    if (selectedType === 'amount') {
                        $("#lblAmountUpdate").text(data.formattedAmount);
                    } else if (selectedType === 'settlementamount') {
                        $("#lblSettlementUpdateAmount").text(data.formattedSettlementAmount);
                    } else if (selectedType === 'updategroup') {
                        $("#lblTransNoUpdate").text(data.transNo);
                    } else if (selectedType === 'updategroupsettlement') {
                        $("#lblTransNoSettlementUpdate").text(data.settlementtransno);
                    } else if (selectedType === 'tipedata') {
                        $("#lblDocTypeUpdate").text(data.docType);
                    } else if (selectedType === 'updatecompany') {
                        $("#lblCompanyUpdate").text(data.companyname);
                    }
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error parsing JSON response:', error, response);
                alert('An error occurred while processing the request.');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error(textStatus, errorThrown);
        }
    });
}

function getDataPaymentSplit(idPayment) {
    console.log("Fetching data for idPayment:", idPayment);

    $.ajax({
        url: '../halPostPaymentRequest.php',
        type: 'POST',
        data: {
            aksi: 'getDataPaymentSplitById',
            idPayment: idPayment
        },
        success: function(response) {
            console.log("Server response:", response);
            try {
                const data = JSON.parse(response);
                if (data.status === "success") {
                    if (data.data.length > 0) {
                        // Display first record
                        $("#lblAccountName1").text(data.data[0].account_name).show();
                        $("#type_dbcr1").text(data.data[0].type_dbcr).show();
                        $("#txtIdPaymentSplit1").val(data.data[0].amount).show();
                        $("#txtIdPaymentSplit1_hidden").val(data.data[0]
                            .id_payment_split); // Update hidden input

                        // If there's a second record, display it
                        if (data.data.length > 1) {
                            $("#lblAccountName2").text(data.data[1].account_name).show();
                            $("#type_dbcr2").text(data.data[1].type_dbcr).show();
                            $("#txtIdPaymentSplit2").val(data.data[1].amount).show();
                            $("#txtIdPaymentSplit2_hidden").val(data.data[1]
                                .id_payment_split); // Update hidden input
                        } else {
                            $("#lblAccountName2").hide();
                            $("#type_dbcr2").hide();
                            $("#txtIdPaymentSplit2").hide();
                            $("#txtIdPaymentSplit2_hidden").val(""); // Clear hidden input
                        }
                    } else {
                        console.error('No data found.');
                    }
                } else {
                    alert(data.message);
                }
            } catch (e) {
                console.error("Error parsing JSON:", e);
                console.error("Response:", response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error(textStatus, errorThrown);
        }
    });
}

function getDataPaymentSplitSettlementById(idPayment) {
    $.ajax({
        url: '../halPostPaymentRequest.php',
        type: 'POST',
        data: {
            idPayment: idPayment,
            aksi: 'getDataPaymentSplitSettlementById'
        },
        success: function(response) {
            console.log('Raw response:', response);
            try {
                if (typeof response !== 'string') {
                    response = JSON.stringify(response);
                }
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    var html = '';
                    $.each(data.data, function(index, item) {
                        html += '<tr>' +
                            '<td colspan="2">' +
                            '<label id="lblAccNameSettle' + (index + 1) +
                            '" style="margin-right: 10px;">' + item.account_name + '</label>' +
                            '<input type="hidden" id="txtIdPaymentSplitSettlement' + (index +
                                1) +
                            '_hidden" name="idPaymentSplitSettlement' + (index + 1) +
                            '" value="' + item.id_payment_split_settlement + '">' +
                            '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<td>' +
                            '<label id="type_dbcrSettle' + (index + 1) +
                            '" style="margin-right: 10px;">' + item.type_dbcr + '</label>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" id="txtAmountSettle' + (index + 1) +
                            '" name="txtAmountSettle' + (index + 1) +
                            '" style="width: 150px; margin-right: 10px;" value="' + item
                            .amount +
                            '">' +
                            '</td>' +
                            '</tr>';
                    });
                    $('#paymentSplitSettlement').html(html);
                } else {
                    console.error('Error fetching data:', data.message);
                }
            } catch (error) {
                console.error('Error parsing JSON response:', error);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}

function handleTypeUpdateChange() {
    var typeUpdate = document.getElementById("slcTypeUpdate").value;
    var txtAmount = document.getElementById("txtAmount");
    var SettlementAmount = document.getElementById("SettlementAmount");
    var slcTypeData = document.getElementById("slcTypeData");
    var lblAccountName1 = document.getElementById("lblAccountName1");
    var lblAccountName2 = document.getElementById("lblAccountName2");
    var type_dbcr1 = document.getElementById("type_dbcr1");
    var type_dbcr2 = document.getElementById("type_dbcr2");
    var paymentSplitSettlement = document.getElementById("paymentSplitSettlement");
    var settlementtransno = document.getElementById("settlementTransNo");
    var transNo = document.getElementById("transNo");
    var typeCompany = document.getElementById("slcCompany");


    // Reset the display of all elements
    txtAmount.style.display = "none";
    SettlementAmount.style.display = "none";
    settlementtransno.style.display = "none";
    slcTypeData.style.display = "none";
    lblAccountName1.style.display = "none";
    lblAccountName2.style.display = "none";
    type_dbcr1.style.display = "none";
    type_dbcr2.style.display = "none";
    transNo.style.display = "none";
    typeCompany.style.display = "none";
    $("#txtIdPaymentSplit1").hide();
    $("#txtIdPaymentSplit2").hide();
    paymentSplitSettlement.innerHTML = '';

    var idPayment = document.getElementById("txtIdPaymentAdv").value;

    // Show fields based on the selected type
    if (typeUpdate === "amount") {
        txtAmount.style.display = "block";
        lblAccountName1.style.display = "inline";
        lblAccountName2.style.display = "inline";
        type_dbcr1.style.display = "inline";
        type_dbcr2.style.display = "inline";
        getDataPaymentSplit(idPayment);
    } else if (typeUpdate === "settlementamount") {
        SettlementAmount.style.display = "inline";
        getDataPaymentSplitSettlementById(idPayment);
    } else if (typeUpdate === "tipedata") {
        slcTypeData.style.display = "inline";
    } else if (typeUpdate === "updategroupsettlement") {
        settlementtransno.style.display = "inline";
    } else if (typeUpdate === "updategroup") {
        transNo.style.display = "inline";
    } else if (typeUpdate === "updatecompany") {
        typeCompany.style.display = "inline";
    }
}

function reloadPageChangeFile() {
    var answer = confirm('Are you sure want to Close?');
    if (answer) {
        parent.tb_remove(false);
        $('#loaderImg').show();
        parent.document.onmousedown = parent.disableLeftClick;
        parent.doneWait();
        parent.reloadPage();
    }
}

function reloadPageUpdate() {
    var answer = confirm('Are you sure want to Close?');
    if (answer) {
        parent.tb_remove(false);
        $('#loaderImg').show();
        parent.document.onmousedown = parent.disableLeftClick;
        parent.doneWait();
        parent.reloadPage();
    }
}

function backPage() {
    $("#txtIdPaymentAdv").val("");

    $("#lblCompany").text("");
    $("#lblDivisi").text("");
    $("#lblBarcode").text("");
    $("#lblAmount").text("");
    $("#lblTransNoUpdate").text("");
    $("#lblSettlementAmountUpdate").text("");
    $("#lblAccountName").text("").hide();
    $("#lblTypeDbcr").text("").hide();


    $("#txtAmount").hide();
    $("#SettlementAmount").hide();
    $("#slcTypeData").hide();
    $("#lblAccountName1").hide();
    $("#type_dbcr1").hide();
    $("#lblAccountName2").hide();
    $("#type_dbcr2").hide();
    $("#txtIdPaymentSplit1").hide();
    $("#txtIdPaymentSplit2").hide();

    $("#slcTypeUpdate").val("");

    $("#idTblFormUpload").hide();
    $("#idTblFormUpdate").hide();
    $("#idTblFormSearch").show(100);
    $("#idTblDataSearch").show(100);
}
</script>
<style>
body {
    background-color: #f9f9f9;
}

#TB_window {
    margin-top: -200px;
}
</style>

<div id="loaderImg" style="display:none;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;&nbsp;Please Wait...&nbsp;<img
            src="../picture/loading (115).gif" height="12" />&nbsp;</div>
</div>

<div id="idHalTambahMailInv" onclick="$('#autoCompSender').css('display','none');">
    <table id="idTblFormSearch" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;width:100%;">
        <tr>
            <td align="right" height="30" colspan="3"><span style="font-size:16px;"><i><u>:: FILE CHANGE
                            ::</u></i></span></td>
        </tr>
        <tr>
            <td style="width:15%;padding:2px;">SEARCH DATA</td>
            <td style="width:50%;padding:2px;">
                <input type="text" id="txtSearch" class="elementInput" value="" placeholder="Barcode No"
                    style="width:100%;height:20px;" />
            </td>
            <td style="width:35%;padding:2px;padding-left: 10px;">
                <button class="btnStandar" onclick="searchData();return false;">
                    <table width="53" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/magnifier.png" /></td>
                            <td align="left">SEARCH</td>
                        </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="reloadPageChangeFile();">
                    <table border="0" width="63" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/door-open-out.png" /></td>
                            <td align="left">CLOSE</td>
                        </tr>
                    </table>
                </button>
            </td>
        </tr>
    </table>

    <table id="idTblDataSearch" width="1000" cellpadding="3" cellspacing="0">
        <thead>
            <tr align="center"
                style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;left:0px;top:0px;">
                <td width="30" style="height:30px;vertical-align:middle;text-align:center;">#</td>
                <td width="100" style="height:30px;vertical-align:middle;text-align:center;">REQ. NAME</td>
                <td width="70" style="height:30px;vertical-align:middle;text-align:center;">BARCODE</td>
                <td width="150" style="height:30px;vertical-align:middle;text-align:center;">COMPANY</td>
                <td width="150" style="height:30px;vertical-align:middle;text-align:center;">DIVISI</td>
                <td width="80" style="height:30px;vertical-align:middle;text-align:center;">AMOUNT</td>
                <td width="100" style="height:30px;vertical-align:middle;text-align:center;">INV. NUMBER</td>
            </tr>
        </thead>
        <tbody id="idBody" style="font-family:Arial;font-size:10px;"></tbody>
    </table>

    <table id="idTblFormUpload" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;width:700px;display:none;">
        <tr>
            <td align="right" height="30" colspan="4"><span style="font-size:16px;"><i><u>:: FORM FILE ::</u></i></span>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <table style="width:100%;font-size:12px;">
                    <tr>
                        <td height="20" style="width:70%;vertical-align:top;">
                            Company : <label style="color:#000;font-size:11px;" id="lblCompany"></label>
                        </td>
                        <td height="20" colspan="2" style="width:30%;vertical-align:top;">
                            Barcode : <label style="color:#000;font-size:11px;" id="lblBarcode"></label>
                        </td>
                    </tr>
                    <tr>
                        <td height="20" style="width:70%;vertical-align:top;">
                            Divisi : <label style="color:#000;font-size:11px;" id="lblDivisi"></label>
                        </td>
                        <td height="20" colspan="2" style="width:30%;vertical-align:top;">
                            Amount : <label style="color:#000;font-size:11px;" id="lblAmount"></label>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="width:15%;padding:2px;">UPLOAD FILE</td>
            <td style="width:15%;padding:2px;">
                <select id="slcTypeFile" class="elementMenu" style="width:100px;">
                    <option value="pengajuan">Pengajuan</option>
                    <option value="buktitransfer">Bukti Transfer</option>
                    <option value="settlement">Settlement</option>
                    <option value="uploadSettlement">Upload Settlement FInance</option>
                </select>
            </td>
            <td style="width:45%;padding:2px;">
                <input type="file" name="fileUploadChange" id="fileUploadChange" class="btnStandar" style="width:250px"
                    title="File Upload">
                &nbsp <a style="cursor:pointer;" onclick="$('#fileUploadChange').val('');"> Clear </a>
            </td>
            <td style="width:35%;padding:2px;padding-left: 10px;">
                <input type="hidden" id="txtIdPaymentAdv" value="">
                <button class="btnStandar" onclick="saveUpload();return false;" title="UPLOAD FILE">
                    <table width="53" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/btnUp.png" / style="width: 20px;"></td>
                            <td align="left">UPLOAD</td>
                        </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="reloadPageChangeFile();" title="CLOSE">
                    <table border="0" width="63" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/door-open-out.png" /></td>
                            <td align="left">CLOSE</td>
                        </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="backPage();" title="BACK">
                    <table border="0" width="147" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/arrow-return-180-left.png" /></td>
                            <td align="left">BACK</td>
                        </tr>
                    </table>
                </button>
            </td>
        </tr>
    </table>

    <table id="idTblFormUpdate" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;width:700px;display:none;">
        <tr>
            <td align="right" height="30" colspan="4"><span style="font-size:16px;"><i><u>:: FORM
                            UPDATE::</u></i></span>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <table style="width:100%;font-size:12px;">
                    <tr>
                        <td height="20" style="width:60%;vertical-align:top;">
                            Company : <label style="color:#000;font-size:11px;" id="lblCompanyUpdate"></label>
                        </td>
                        <td height="20" style="width:30%;vertical-align:top;">
                            Barcode : <label style="color:#000;font-size:11px;" id="lblBarcodeUpdate"></label>
                        </td>
                    </tr>
                    <tr>
                        <td height="20" style="width:60%;vertical-align:top;">
                            Division : <label style="color:#000;font-size:11px;" id="lblDivisiUpdate"></label>
                        </td>
                        <td height="20" style="width:30%;vertical-align:top;">
                            Amount : <label style="color:#000;font-size:11px;" id="lblAmountUpdate"></label>
                        </td>
                    </tr>
                    <tr>
                        <td height="20" style="width:60%;vertical-align:top;">
                            Doc Type: <label style="color:#000;font-size:11px;" id="lblDocTypeUpdate"></label>
                        </td>
                        <td height="20" style="width:30%;vertical-align:top;">
                            Settlement Amount: <label style="color:#000;font-size:11px;"
                                id="lblSettlementAmountUpdate"></label>
                        </td>
                    </tr>
                    <tr>
                        <td height="20" style="width:60%;vertical-align:top;">
                            TransNo: <label style="color:#000;font-size:11px;" id="lblTransNoUpdate"></label>
                        </td>
                        <td height="20" style="width:30%;vertical-align:top;">
                            TransNo Settlement: <label style="color:#000;font-size:11px;"
                                id="lblTransNoSettlementUpdate"></label>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="display: flex; align-items: center; gap: 5px;">
                <label for="Update Type" style="margin-right: 10px;">UPDATE TYPE:</label>
                <input type="hidden" id="transNoHidden" name="transNoHidden" value="">
                <select id="slcTypeUpdate" class="elementMenu" style="width: 120px; margin-right: 10px;"
                    onchange="handleTypeUpdateChange()">
                    <option value="">- Select -</option>
                    <option value="amount">Amount</option>
                    <option value="settlementamount">Settlement Amount</option>
                    <option value="tipedata">Tipe Document</option>
                    <option value="updategroup">Update Group</option>
                    <option value="updategroupsettlement">Update Group Settlement</option>
                    <option value="updatecompany">Update Company Name</option>
                </select>
                <input type="text" name="txtAmount" id="txtAmount"
                    style="width: 150px; margin-right: 10px; display: none;">
                <input type="text" name="SettlementAmount" id="SettlementAmount"
                    style="width: 150px; margin-right: 10px; display: none;">
                <input type="text" name="transNo" id="transNo" style="width: 150px; margin-right: 10px; display: none;">
                <input type="text" name="settlementTransNo" id="settlementTransNo"
                    style="width: 150px; margin-right: 10px; display: none;">
                <select id="slcTypeData" class="elementMenu" style="width: 150px; display: none; margin-right: 10px;">
                    <option value="general">Reimburse</option>
                    <option value="advance">Advance</option>
                </select>
                <select id="slcCompany" class="elementMenu" style="width: 150px; display: none; margin-right: 10px;">
                    <option value="">-- SELECT COMPANY --</option>
                    <?php echo $CPaymentAdv->menuCmp($idCmp); ?>
                </select>
            </td>
            <td style="width:35%;padding:2px;padding-left: 10px;">
                <input type="hidden" id="txtIdPaymentAdv" value="">
                <button class="btnStandar" onclick="saveUpdate();return false;" title="SIMPAN">
                    <table width="53" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/btnUp.png" / style="width: 20px;"></td>
                            <td align="left">SIMPAN</td>
                        </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="reloadPageUpdate();" title="CLOSE">
                    <table border="0" width="63" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/door-open-out.png" /></td>
                            <td align="left">CLOSE</td>
                        </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="backPage();" title="BACK">
                    <table border="0" width="147" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/arrow-return-180-left.png" /></td>
                            <td align="left">BACK</td>
                        </tr>
                    </table>
                </button>
            </td>
        </tr>
        <tr style="display: block; align-items: center; gap: 10px;">
            <td style="display: block;">
                <label id="lblAccountName1" style="margin-right: 10px;"></label>
            </td>
            <td style="display: block;">
                <label id="type_dbcr1" style="margin-right: 10px;"></label>
                <input type="text" id="txtIdPaymentSplit1" name="type_dbcr1" style="width: 150px; margin-right: 10px;">
                <input type="hidden" id="txtIdPaymentSplit1_hidden" value="">
            </td>
            <td style="display: block;">
                <label id="lblAccountName2" style="margin-right: 10px;"></label>
            </td>
            <td style="display: block;">
                <label id="type_dbcr2" style="margin-right: 10px;"></label>
                <input type="text" id="txtIdPaymentSplit2" name="type_dbcr2"
                    style="width: 150px; margin-right: 10px; display: none;">
                <input type="hidden" id="txtIdPaymentSplit2_hidden" value="">
            </td>
        </tr>

        <tr id="paymentSplitSettlement">

        </tr>
    </table>

</div>

</HTML>