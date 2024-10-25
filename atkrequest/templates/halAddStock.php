<?php
require_once('../../config.php');
require_once('../configAtk.php');
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css"
    media="screen">
</LINK>
<script src="../../js/JavaScriptUtil.js"></script>
<script src="../../js/Parsers.js"></script>
<script src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<SCRIPT type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<?php
if($aksiPost == "add")
{
	$paramDate = $_POST['paramDate'];	
	$tgl =  substr($paramDate,0,2);
	$bln =  substr($paramDate,3,2);
	$thn =  substr($paramDate,6,4);
	
	$itemId = $_POST['itemId'];
	$itemName = $CReqAtk->detilAtkItem("itemname", $itemId);
	$minStock = $CReqAtk->detilAtkItem("qtymin", $itemId);
	$qty = $_POST['qty'];
	$qtyType = mysql_real_escape_string($_POST['satuanQty']);
	$price = str_replace(",","",$_POST['price']);
	$anotherSupply = $_POST['anotherSupply'];
	
	//echo $price;
	$sqlOut = $CKoneksiAtk->mysqlQuery("SELECT stockout FROM stock WHERE stockmonth = '".$bln."' AND stockyear = ".$thn." AND itemid=".$itemId." AND active = 'Y' LIMIT 1;");
	$rOut = $CKoneksiAtk->mysqlFetch($sqlOut);
	$stockOut = $rOut['stockout']; // nilai stockout (item keluar)
	if($stockOut == "")
	{
		$stockOut = "0";
	}
	
	$stock = $CReqAtk->lastStock("stockall", $itemId, $bln, $thn);
	$stockNow = $stock+$qty;// stock akhir + stock baru masuk
	
	$lastMonthStock = $CReqAtk->lastStock("lastmonthstock", $itemId, $bln, $thn);
	if($lastMonthStock == "")
	{
		$lastMonthStock = "0";
	}
	// Menentukan nilai stock terakhir + stock baru masuk
	
	$sqlStockId = $CKoneksiAtk->mysqlQuery("SELECT stockid FROM stock WHERE itemid =".$itemId." AND stockdate=00 AND stockmonth=".$bln." AND stockyear=".$thn." AND active='Y';");
	$rSqlStockId = $CKoneksiAtk->mysqlFetch($sqlStockId);
	$stockId = $rSqlStockId['stockid'];
	// ambil stockid yg merupakan stock last month
	if($stockId != "")
	{
		$query = $CKoneksiAtk->mysqlQuery("UPDATE stock SET urutanid=0, active='N' WHERE stockid=".$stockId.";");
		// update urutanid=0 dan active=N pada stockid lastmonth
	}
	
	$sqlUrutan = $CKoneksiAtk->mysqlQuery("SELECT(MAX(urutanid)) FROM stock WHERE itemid=".$itemId." AND stockmonth=".$bln." AND stockyear=".$thn." AND active='Y'");
	$rUrutan = $CKoneksiAtk->mysqlFetch($sqlUrutan);
	$urutanIdLast = $rUrutan['(MAX(urutanid))'];
	$urutanId = $urutanIdLast + 1;
	// menentukan urutanid terakhir dari itemid tsb
	
	$CKoneksiAtk->mysqlQuery("INSERT INTO stock (itemid, urutanid, stockdate, stockmonth, stockyear, stockname, stockqty, stocktype, stockmin, stockprice, stockout, stockall, lastmonthstock, addusrdt) VALUES (".$itemId.", ".$urutanId.", '".$tgl."', '".$bln."', '".$thn."', '".$itemName."', '".$qty."', '".$qtyType."', '".$minStock."', ".$price.", '".$stockOut."', '".$stockNow."', ".$lastMonthStock.", '".$CPublic->userWhoAct()."');");
	$lastInsertId = mysql_insert_id();
	
	$query = $CKoneksiAtk->mysqlQuery("UPDATE stock SET stockall='".$stockNow."' WHERE itemid=".$itemId." AND stockmonth=".$bln." AND stockyear=".$thn."");
	// update stock terhadap item yg sama pada bulan & tahun yg sama jika ada
	
	$CHistory->updateLogReqAtk($userIdLogin, "Tambah Stock (itemid = <b>".$lastInsertId."</b>, nama item = <b>".$itemName."</b>)");
	
	if($anotherSupply == "on")
	{
		$tutupWindow = "tidak";
	}
	if($anotherSupply == "")
	{
		$tutupWindow = "ya";
	}
}
?>
<script>
function qtyType() {
    var type = document.getElementById('satuanQty').value;
    document.getElementById('spanQtyType').innerHTML = "...";
    if (type != "0") {
        document.getElementById('spanQtyType').innerHTML = type;
    }
}

function ajaxDetail(itemId, aksi, idHalaman) {
    var mypostrequest = new ajaxRequest()
    mypostrequest.onreadystatechange = function() {
        if (mypostrequest.readyState == 4) {
            if (mypostrequest.status == 200 || window.location.href.indexOf("http") == -1) {
                document.getElementById(idHalaman).innerHTML = mypostrequest.responseText;
            }
        }
    }

    var parameters = "halaman=" + aksi + "&itemId=" + itemId;

    mypostrequest.open("POST", "../halPostFold.php", true);
    mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    mypostrequest.send(parameters);

    setTimeout(function() {
        qtyType();
    }, 500);
}

function submitStock() {
    var aksi = formStock.aksi.value;
    var paramDate = formStock.paramDate.value;
    var itemId = formStock.itemId.value;
    var qty = formStock.qty.value;
    var price = formStock.price.value;

    //alert(paramDateAct+', '+itemName+', '+qty+', '+satuanQty+', '+price);

    if (paramDate.replace(/ /g, "") == "") // paramDateAct tidak boleh kosong
    {
        document.getElementById('errorMsg').innerHTML = "Date still empty";
        document.getElementById('paramDate').focus();
        return false;
    }

    if (itemId.replace(/ /g, "") == "0") // itemName tidak boleh kosong
    {
        document.getElementById('errorMsg').innerHTML = "Item Name still not choosen";
        document.getElementById('itemId').focus();
        return false;
    }

    if (qty.replace(/ /g, "") == "") // qty tidak boleh kosong
    {
        document.getElementById('errorMsg').innerHTML = "Qty still empty";
        document.getElementById('qty').focus();
        return false;
    }

    if (isNaN(qty)) // qty harus angka
    {
        document.getElementById('errorMsg').innerHTML = "Minimal Stock must be number";
        document.getElementById('qty').focus();
        return false;
    }

    if (price.replace(/ /g, "") == "") // price tidak boleh kosong
    {
        document.getElementById('errorMsg').innerHTML = "Price still empty!";
        document.getElementById('price').focus();
        return false;
    }

    /*if(isNaN(price)) // price harus angka
    {
    	document.getElementById('errorMsg').innerHTML = "Price must be number";
    	document.getElementById('price').focus();
    	return false;
    }*/

    var answer = confirm("Are you sure want to save?");
    if (answer) {
        formStock.submit();
    } else {
        return false;
    }
}

function setup() {
    var decimalSeparator = ".";
    var groupSeparator = ",";

    var numParser1 = new NumberParser(0, decimalSeparator, groupSeparator, true);
    var numMask1 = new NumberMask(numParser1, "price");
}
</script>

<body bgcolor="#F8F8F8">
    <center>
        <table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%" align="center">
            <tr valign="top" style="width:100%;">
                <td align="left">
                    <span class="teksLvlFolder" style="color:#666;font-size:14px;"><b></b></span>
                </td>
                <td align="right">
                    <span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: Add Item
                            Stock ::</b></span>
                </td>
            </tr>
            <tr>
                <td height="5" colspan="2"></td>
            </tr>
            <tr valign="top">
                <td colspan="2" class="tdMyFolder" bgcolor="#FFFFFF" valign="top" align="center">
                    <form action="" name="formStock" id="formStock" method="post" enctype="multipart/form-data">
                        <table cellpadding="0" cellspacing="5" width="98%" height="100%" class="formInput" border="0">
                            <tr>
                                <td height="10" colspan="3"></td>
                            </tr>
                            <tr width="3%" valign="top">
                                <td width="23%" rowspan="4" align="center" id="tdImg">&nbsp;</td>
                                <td height="28" width="18%">Supply Date</td>
                                <td width="59%">
                                    <input type="text" class="elementSearch" id="paramDate" name="paramDate" size="8"
                                        style="height:28px;color:#333;font-weight:bold;font-size:14px;background-color:#FFF;text-align:center;border:0;"
                                        readonly>
                                    <button class="btnStandar" id="btnNewFolder"
                                        onMouseOver="this.className='btnStandarHover';"
                                        onMouseOut="this.className='btnStandar';"
                                        onClick="displayCalendar(document.getElementById('paramDate'),'dd/mm/yyyy',this, '', '', '193', '191');"
                                        style="width:28px;height:28px;" title="Calendar Activity Quick Action">
                                        <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                                            <tr>
                                                <td align="center"><img src="../../picture/calendar.gif" width="23" />
                                                </td>
                                            </tr>
                                        </table>
                                    </button>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td height="28" width="18%">Item Name</td>
                                <td width="59%">
                                    <select id="itemId" name="itemId" class="elementMenu" style="width:99%;"
                                        onChange="ajaxDetail(this.value, 'bukaImg', 'tdImg');ajaxDetail(this.value, 'bukaQty', 'spanQty');ajaxDetail(this.value, 'bukaPrice', 'tdPrice');">
                                        <option value="0">-- PLEASE SELECT -- </option>
                                        <?php
					$sqlName = $CKoneksiAtk->mysqlQuery("SELECT * FROM item WHERE deletests=0 ORDER BY itemname ASC");
					while($rName = $CKoneksiAtk->mysqlFetch($sqlName))
					{
						echo "<option value=\"".$rName['itemid']."\">".$rName['itemname']."</option>";
					}
					?>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td height="28" width="18%">Qty</td>
                                <td width="59%">
                                    <input type="text" class="elementDefault" id="qty" name="qty"
                                        style="width:30%;height:28px;" />
                                    <span id="spanQty"><input type="text" class="elementDefault" id="satuanQty"
                                            name="satuanQty" style="width:68%;height:28px;" readonly /></span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td height="28">Price / <span id="spanQtyType">...</span> (Rp.)</td>
                                <td id="tdPrice">
                                    <input type="text" class="elementDefault" id="price" name="price"
                                        style="width:99%;height:28px;" onFocus="setup();" onKeyUp="setup();">
                                </td>
                            </tr>
                            <tr valign="top">
                                <td></td>
                                <td><input type="hidden" id="aksi" name="aksi" value="add" /></td>
                                <td>
                                    <input type="checkbox" class="elementSearch" id="anotherSupply"
                                        name="anotherSupply">&nbsp;Add another Supply
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" height="20" align="center" valign="middle">&nbsp; <span id="errorMsg"
                                        class="errorMsg">&nbsp;</span></td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr valign="top">
                <td colspan="2" class="tdMyFolder" bgcolor="#FFFFFF" height="65" valign="middle">
                    &nbsp;<button onClick="parent.tutup();" class="btnStandarKecil"
                        onMouseOver="this.className='btnStandarKecilHover'"
                        onMouseOut="this.className='btnStandarKecil'" style="width:80px;height:55px;"
                        title="Close Window">
                        <table width="100%" height="100%" class="fontBtnKecil"
                            onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
                            <tr>
                                <td align="center"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="25" />
                                </td>
                            </tr>
                            <tr>
                                <td align="center">CLOSE</td>
                            </tr>
                        </table>
                    </button>
                    &nbsp;<button onClick="submitStock(); return false;" class="btnStandarKecil"
                        onMouseOver="this.className='btnStandarKecilHover'"
                        onMouseOut="this.className='btnStandarKecil'" style="width:80px;height:55px;" title="Save Data">
                        <table width="100%" height="100%" class="fontBtnKecil"
                            onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
                            <tr>
                                <td align="center"><img src="../../picture/Floppy-Disk-blue-32.png" height="25" /> </td>
                            </tr>
                            <tr>
                                <td align="center">SUBMIT</td>
                            </tr>
                        </table>
                    </button>
                </td>
            </tr>
        </table>

    </center>
</body>
<script language="javascript">
<?php
if($tutupWindow == "tidak")
{
}
if($tutupWindow == "ya")
{
	echo "parent.exit();
		  parent.report();";
}
?>
</script>