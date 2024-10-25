<?php 
    require_once("../configPaymentAdvance.php");

    $idEdit = $_GET['idEdit'];
    $typeDoc = $_GET['typeDoc'];
    $lblPage = "DETAIL PAYMENT";

    $batchno = "";
    $reqName = "";
    $company = "";
    $divisi = "";
    $barcode = "";
    $invDate = "";
    $dueDate = "";
    $invNo = "";
    $amount = "";
    $remark = "";
    $amountNya = "";
    $initVsl = "";
    $voyageNya = "";
    $vslNya = "";
    $tdAmountExpense = "";
    $amountExpense = "";

    $query = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM datapayment WHERE id = '".$idEdit."' AND st_delete = '0';", $CkoneksiPaymentAdv->bukaKoneksi());
    while($row = $CkoneksiPaymentAdv->mysqlFetch($query))
    {
        $batchno = $row['batchno'];
        $reqName = $row['request_name'];
        $company = $row['company_name'];
        $divisi = $row['divisi'];
        $barcode = $row['barcode'];
        $invDate = $CPublic->convTglNonDB($row['invoice_date']);
        $dueDate = $CPublic->convTglNonDB($row['invoice_due_date']);
        $invNo = $row['mailinvno'];
        $amount = "(".$row['currency'].") ".number_format($row['amount'],2);
        $remark = $row['remark'];
        $amountNya = $row['amount'];
        $initVsl = $row['vessel_code'];
        $voyageNya = $row['voyage_no'];
        $vslNya = $row['vessel_name'];
        $amountExpense = $row['settlement_amount'];

        if($typeDoc == "giveSettlement")
        {
            if($row['amount'] > $row['settlement_amount'])
            {
                $lblPage = "DETAIL REFUND";
            }

            $tdAmountExpense .= "<td style=\"width:150px;\" height=\"20\">";
                $tdAmountExpense .= "<label>Amount Expense</label>";
            $tdAmountExpense .= "</td>";
            $tdAmountExpense .= "<td class=\"elementTeks\" style=\"width:300px;\">";
                $tdAmountExpense .= "(".$row['currency'].") ".number_format($row['settlement_amount'],2);
            $tdAmountExpense .= "</td>";
        }else{
            $tdAmountExpense = "";
        }
    }

?>

<!DOCTYPE HTML>
<script type="text/javascript" src="../js/jquery-1.8.0.js"></script>
<script type="text/javascript" src="../js/masks.js"></script>
<script type="text/javascript" src="../js/paymentAdvance.js"></script>

<link rel="stylesheet" type="text/css" href="../css/paymentAdvance.css"> 
<link rel="stylesheet" type="text/css" href="../css/button.css"/>
<link rel="stylesheet" type="text/css" href="../css/table.css"/>

<script type="text/javascript">
    function saveData()
    {
        var formData = new FormData();

        var amountDebit = 0;
        var amountCredit = 0;
        var amountCreditSettlement = $("#txtAmountExpenseTemp").val();
        var blnce = 0;
        var ttlAdvance = $("#txtAmountTemp").val();
        var ttlExpense = 0;

        formData.append('aksi', "giveCheckData");
        formData.append('txtIdEdit', $("#txtIdEdit").val());
        formData.append('txtTypeDoc', $("#txtHideTypeDoc").val());

        for (var lan = 1;lan <= 5;lan++)
        {
            formData.append('accNo_'+lan, $("#txtAccId_"+lan).val());
            formData.append('accName_'+lan, $("#slcAccName_"+lan+" option:selected").text());
            formData.append('amount_'+lan, $("#txtAmount_"+lan).val());
            formData.append('vslCode_'+lan, $("#txtVslCode_"+lan).val());
            formData.append('vslName_'+lan, $("#slcVessel_"+lan+" option:selected").text());
            formData.append('desc_'+lan, $("#txtDesc_"+lan).val());
            formData.append('voy_'+lan, $("#slcVoyage_"+lan).val());
            formData.append('dbCr_'+lan, $("#slcDbCr_"+lan).val());

            var ttlNya = $("#txtAmount_"+lan).val();
            ttlNya = ttlNya.replace(/,/g, '');
            if(ttlNya == "")
            {
                ttlNya = 0;
            }

            if($("#slcDbCr_"+lan).val() == "DB")
            {
                amountDebit = parseFloat(amountDebit) + parseFloat(ttlNya);
            }else{
                amountCredit = parseFloat(amountCredit) + parseFloat(ttlNya);
            }
            
        }

        if($("#txtHideTypeDoc").val() == "giveSettlement")
        {
            ttlExpense = $("#txtAmountExpenseTemp").val();

            if(parseFloat(ttlAdvance) > parseFloat(ttlExpense))// posisi refund (total expense jadi DB) 
            {
                blnce = 0;
            }else{  // posisi payment (total expense jadi CR)
                blnce = 0;
            }            
        }else{
            blnce = (parseFloat(ttlAdvance)+parseFloat(amountCredit)) - parseFloat(amountDebit);
        }

        if(blnce != "0")
        {
            alert("ACCOUNT NOT BALANCE..!!");
            return false;
        }

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
    function hanyaAngkaAmount(id)
    {
        amountMask = new Mask("#,###.##", "number");
        amountMask.attach(document.getElementById('txtAmount_'+id));
    }
    function setAccNo(id)
    {
        var accNo = $("#slcAccName_"+id).val();

        $("#txtAccId_"+id).val(accNo);

        $("#txtVslCode_"+id).val($("#txtInitVesselTemp").val());
        $("#slcVessel_"+id).val($("#txtInitVesselTemp").val());
        $("#txtDesc_"+id).val($("#txtRemarkTemp").val());

        getVoyage(id);
    }
    function setVslCode(id)
    {
        var accNo = $("#slcVessel_"+id).val();

        $("#txtVslCode_"+id).val(accNo);
        getVoyage(id);
    }
    function autoComplete(id)
    {
        $.post('../halPostPaymentRequest.php',
        { aksi:"ketikAutoComplSenderByCode", param:$('#txtAccId_'+id).val(), idRow:id },
            function(data) 
            {
                $('#autoCompSender_'+id).show();
                $('#autoCompSender_'+id).empty( '' );
                $('#autoCompSender_'+id).append(data);
            },
            "json"
        );
    }
    function dataSelectAutoComplete(account,id)
    {
        $("#txtAccId_"+id).val(account);
        $("#slcAccName_"+id).val(account);
        $("#autoCompSender_"+id).hide();

        $("#txtVslCode_"+id).val($("#txtInitVesselTemp").val());
        $("#slcVessel_"+id).val($("#txtInitVesselTemp").val());
        $("#txtDesc_"+id).val($("#txtRemarkTemp").val());
        getVoyage(id);
    }
    function getVoyage(id)
    {
        var thn = $("#idInvDate").text();
        var vslCode = $("#txtVslCode_"+id).val();
        var vygNo = $("#txtVoyageTemp").val();

        $.post('../halPostPaymentRequest.php',
        { aksi:"getVoyageNo", thn:thn, vsl:vslCode },
            function(data) 
            {
                $('#slcVoyage_'+id).empty( '' );
                $('#slcVoyage_'+id).append(data);

                $('#slcVoyage_'+id).val(vygNo);
            },
            "json"
        );
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

<div id="idHalTambahMailInv" onclick="$('[id^=autoCompSender_]').css('display','none');">
    <table cellpadding="0" cellspacing="0" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;width:100%;" border="0" align="center">
        <tr>
            <td align="center" height="30" colspan="6"><span style="font-size:16px;"><u><?php echo $lblPage; ?></u></span></td>
        </tr>
        <tr style="">
            <td style="width:150px;padding-top:20px;" height="20">
                <label>Batchno </label>                
            </td>
            <td class="elementTeks" style="width:300px;padding-top:20px;">
                <?php echo $batchno; ?>
            </td>
            <td style="width:150px;padding-top:20px;" height="20">
                <label>Request Name </label>                
            </td>
            <td class="elementTeks" style="width:300px;padding-top:20px;">
                <?php echo $reqName; ?>
            </td>
        </tr>
        <tr>
            <td style="width:150px;" height="20">
                <label>Company </label>                
            </td>
            <td class="elementTeks" style="width:300px;">
                <?php echo $company; ?>
            </td>            
            <td style="width:150px;" height="20">
                <label>Barcode </label>                
            </td>
            <td class="elementTeks" style="width:300px;">
                <?php echo $barcode; ?>
            </td>
        </tr>
        <tr>
            <td style="width:150px;" height="20">
                <label>Divisi </label>                
            </td>
            <td class="elementTeks" style="width:300px;">
                <?php echo $divisi; ?>
            </td>
            <td style="width:150px;" height="20">
                <label>Invoice / Letter Date </label>                
            </td>
            <td class="elementTeks" style="width:300px;" id="idInvDate">
                <?php echo $invDate; ?>
            </td>
        </tr>
        <tr>
            <td style="width:150px;" height="20">
                <label>Vessel / Voyage </label>                
            </td>
            <td class="elementTeks" style="width:300px;">
                <?php echo $vslNya." / ".$voyageNya; ?>
            </td>
            <td style="width:150px;" height="20">
                <label>Due Date </label>                
            </td>
            <td class="elementTeks" style="width:300px;">
                <?php echo $dueDate; ?>
            </td>
        </tr>
        <tr>
            <td style="width:150px;" height="20">
                <label>Mail/Invoice No </label>                
            </td>
            <td class="elementTeks" style="width:300px;">
                <?php echo $invNo; ?>
            </td>
            <td style="width:150px;" height="20">
                <label>Amount </label>                
            </td>
            <td class="elementTeks" style="width:300px;">
                <?php echo $amount; ?>
            </td>
        </tr>
        <tr>
            <td style="width:150px;" height="20">
                <label>Remark </label>                
            </td>
            <td class="elementTeks" style="width:550px;min-height:100px;">
                <?php echo $remark; ?>
            </td>
            <?php echo $tdAmountExpense; ?>
        </tr>
        <tr>
            <td colspan="6" style="padding:5px;vertical-align:middle;"></td>
        </tr>
        <tr>
            <td colspan="6">
                <table cellpadding="0" cellspacing="0" style="border:solid 0px #666;font-size:12px;width:100%;" border="0">
                <tr align="center" style="background-color:#F2FAFF;height:30px;">
                    <td style="width:30%;" class="tabelBorderBottomRightNull" colspan="2">Account Name</td>
                    <td style="width:10%;" class="tabelBorderTopJust">Amount</td>
                    <td style="width:30%;" class="tabelBorderTopJust" colspan="2">Vessel</td>
                    <td style="width:10%;" class="tabelBorderTopJust">Voyage</td>
                    <td style="width:20%;" class="tabelBorderBottomLeftNull">Description</td>
                </tr>
                <?php
                    $getOptSenderVender = $CPaymentAdv->getOptSenderVender();
                    $getOptVessel = $CPaymentAdv->menuVessel("");
                    for ($a=1; $a <= 5; $a++){
                ?>
                <tr align="center">
                    <td style="padding-top:5px;">                        
                        <!-- <input type="text" id="txtAccId_<?php echo $a;?>" class="elementInput" value="" style="width:50px;"> -->
                        <input autocomplete="off" type="text" id="txtAccId_<?php echo $a;?>" class="elementInput" value="" style="width:50px;" oninput="autoComplete(<?php echo $a;?>);">
                        <div id="autoCompSender_<?php echo $a;?>" class="overout" align="left" style="position:absolute;display:none;z-index:10;width:335px;height:300px;overflow:auto;border-color:#333;"></div>
                    </td>
                    <td style="padding-top:5px;">
                        <select class="elementMenu" id="slcAccName_<?php echo $a;?>" style="width:200px;" onchange="setAccNo('<?php echo $a; ?>');">
                            <option value=""></option>
                            <?php echo $getOptSenderVender; ?>
                        </select>
                    </td>
                    <td style="padding-top:5px;">
                        <input type="text" id="txtAmount_<?php echo $a;?>" class="elementInput" value="" style="width:95%;text-align:right;" oninput="hanyaAngkaAmount('<?php echo $a; ?>');">
                    </td>
                    <td style="padding-top:5px;">                        
                        <input type="hidden" id="txtVslCode_<?php echo $a;?>" class="elementInput" value="" disabled="disabled">
                        <select class="elementMenu" id="slcDbCr_<?php echo $a;?>" style="width:70px;margin-left:10px;">
                            <option value="DB">DB</option>
                            <option value="CR">CR</option>                            
                        </select>
                    </td>
                    <td style="padding-top:5px;">
                        <select class="elementMenu" id="slcVessel_<?php echo $a;?>" style="width:170px;" onchange="setVslCode('<?php echo $a; ?>');">
                            <option value=""></option>
                            <?php echo $getOptVessel; ?>
                        </select>
                    </td>
                    <td style="padding-top:5px;">
                        <select class="elementMenu" id="slcVoyage_<?php echo $a;?>" style="width:130px;">
                        </select>
                    </td>
                    <td style="padding-top:5px;">
                        <input type="text" id="txtDesc_<?php echo $a;?>" class="elementInput" value="" style="width:95%;">
                    </td>
                </tr>
                <?php } ?>
            </table>
            </td>
        </tr>

        <tr>
            <td colspan="4" align="center" style="padding-top: 10px;" align="center">
                <input type="hidden" id="txtIdEdit" value="<?php echo $idEdit; ?>">
                <input type="hidden" id="txtAmountTemp" value="<?php echo $amountNya; ?>">
                <input type="hidden" id="txtInitVesselTemp" value="<?php echo $initVsl; ?>">
                <input type="hidden" id="txtRemarkTemp" value="<?php echo $remark; ?>">
                <input type="hidden" id="txtVoyageTemp" value="<?php echo $voyageNya; ?>">
                <input type="hidden" id="txtHideTypeDoc" value="<?php echo $typeDoc; ?>">
                <input type="hidden" id="txtAmountExpenseTemp" value="<?php echo $amountExpense; ?>">
                <button class="btnStandar" onclick="tutupForm('Y');">
                    <table border="0" width="63" height="30">
                    <tr>
                        <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                        <td align="left">CLOSE</td>
                    </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="saveData();return false;">
                    <table width="100" height="30">
                    <tr>
                        <td align="center" width="20"><img src="../picture/tick.png"/></td>
                        <td align="left">GIVE CHECK</td>
                    </tr>
                    </table>
                </button>
            </td>
        </tr>
    </table>
</div> 

</HTML>