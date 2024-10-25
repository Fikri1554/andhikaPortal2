<!DOCTYPE HTML>
<?php
require_once('../../config.php');
require_once('../configAtk.php');
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/main.js"></script>
<script>
function submitItem()
{
	var aksi = formItem.aksi.value;
	var fileUpload = formItem.fileUpload.value;
	var nmUnit = formItem.nmUnit.value;
	var category = formItem.category.value;
	var qtyMin = formItem.qtyMin.value;
	var qtyType = formItem.qtyType.value;
	
	var tipeFile = fileUpload.split('.').pop();
	var imgExt = "jpg,JPG,jpeg,JPEG,png,PNG,x-png,X-PNG";
	var adaFileExt = imgExt.indexOf(tipeFile+",");

	//alert(aksi+', '+fileUpload+', '+nmUnit+', '+category+', '+qty+', '+qtyType);
	
	if(aksi != "edit")
	{
		if(fileUpload.replace(/ /g,"") == "") // file upload tidak boleh kosong
		{
			document.getElementById('errorMsg').innerHTML = "File upload still empty";
			document.getElementById('fileUpload').focus();
			return false;
		}
		
		if(adaFileExt == -1) // Ext file upload harus jpg,JPG,jpeg,JPEG,png,PNG,x-png,X-PNG
		{
			document.getElementById('errorMsg').innerHTML = "File type not allowed!";
			document.getElementById('fileUpload').focus();
			return false;
		}
	}

	if(nmUnit.replace(/ /g,"") == "") // nmUnit tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = "Unit Name still empty";
		document.getElementById('nmUnit').focus();
		return false;
	}
	
	if(category.replace(/ /g,"") == "0") // category tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = "Category still not choosen";
		document.getElementById('category').focus();
		return false;
	}
	
	if(qtyMin.replace(/ /g,"") == "") // qty tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = "Minimal Stock still empty";
		document.getElementById('qtyMin').focus();
		return false;
	}
	
	if(isNaN(qtyMin)) // qty tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = "Minimal Stock must be number";
		document.getElementById('qtyMin').focus();
		return false;
	}
	
	if(qtyType.replace(/ /g,"") == "0") // qty Type tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = "Stock tpye still not choosen";
		document.getElementById('qtyType').focus();
		return false;
	}
	
	var fileAdaTidak = document.getElementById('fileAdaTidak').value;
	if(fileAdaTidak == "ada") //jika file ada di database
	{
		document.getElementById('errorMsg').innerHTML = "Item name already exists!";
		document.getElementById('nmUnit').focus();
		return false;
	}
	
	var editTxt = "";
	if(aksi == "edit")
	{
		var editTxt = "edit ";
	}
	var answer  = confirm("Are you sure want to save "+editTxt+"?");
	if(answer)
	{
		formItem.submit();
	}
	else
	{	return false;	}
}

function report(text)
{
	document.getElementById('report').innerHTML = "New Item succesfully "+text;
	
	setTimeout(function()
	{
		document.getElementById('report').innerHTML = "";
	},10000);
}

function ajaxGetFile(namaFile, aksi, idHalaman)
{
	var mypostrequest=new ajaxRequest()
	mypostrequest.onreadystatechange=function()
	{
		if (mypostrequest.readyState==4)
		{
			if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1)
			{
				document.getElementById(idHalaman).innerHTML=mypostrequest.responseText;
			}
		}
	}
	
	if(aksi == "cekFileAdaTidak")
	{
		var itemId = document.getElementById('itemId').value;
		var nmUnit = document.getElementById('nmUnit').value.replace(/&/g,"%26");
		var parameters="halaman="+aksi+"&nmUnit="+nmUnit+"&itemId="+itemId;
	}
	
	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}

function pilihUpload()
{
	var fileUpload = document.getElementById('fileUpload').value;
	
	var namaFile = fileUpload.split('\\').pop();
	document.getElementById('nmUnit').value = namaFileCustom(namaFile);
}

function namaFileCustom(namaFileParam)
{
	var nilai = '';
	var namaFile = namaFileParam.split('.');
	
	
	for(i = 0; i<=(namaFile.length-2); i++ )
	{
		if(i == 0)
		{
			nilai += namaFile[i];
		}
		else
		{
			nilai += "."+namaFile[i];
		}
	}
	
	return nilai;
}

function readURL(input) 
{
	document.getElementById('spanImg').innerHTML = '<img id="imgTmp" src="'+input+'" width="120" height="104" alt="a"/>';
}
</script>
<?php
$dateTime = $CPublic->dateTimeGabung();

$disCancel = "disabled";
$displayItem = "checked";
$btnCancel = "<button type=\"button\" id=\"btnCancel\" class=\"btnStandarDisabled\"  onClick=\"parent.refreshPage('detail');\" title=\"Cancel Edit Item\" disabled=\"disabled\">
				<table id=\"tblAdd\" height=\"24\">
					<tr>
						<td align=\"center\" width=\"20\"><img src=\"../picture/arrow-return-180-left.png\"></td>
						<td align=\"left\">Cancel&nbsp;</td>
					</tr>
				</table>
			</button>";


if($aksiGet == "")
{
	$aksiGet = "new";
	$imgTmp = "readURL(this.value)";
}

if($aksiGet == "edit")
{
	$imgTmp = "readURL(this.value);";
	$disCancel = "";
	$btnCancel = "<button type=\"button\" id=\"btnCancel\" class=\"btnStandar\" onClick=\"parent.refreshPage('detail');\" title=\"Cancel Edit Item\">
					<table id=\"tblAdd\" height=\"24\">
						<tr>
							<td align=\"center\" width=\"20\"><img src=\"../picture/arrow-return-180-left.png\"></td>
							<td align=\"left\">Cancel&nbsp</td>
						</tr>
					</table>
				</button>";
	
	$itemIdGet = $_GET['itemId'];
	
	$sqlItem = $CKoneksiAtk->mysqlQuery("SELECT * FROM item WHERE itemid=".$itemIdGet." AND deletests=0 ORDER BY itemname ASC");
   	$rItem = $CKoneksiAtk->mysqlFetch($sqlItem);
	
	$img = $rItem['itemimg'];
	$name = $rItem['itemname'];
	$qtyMinGet = $rItem['qtymin'];
	$qtyTypeGet = $rItem['qtytype'];
	$cDisplayGet = $rItem['itemdisplay'];
	if($cDisplayGet != "on")
	{
		$displayItem = "";
	}
	
	$dis = "";
	$cekTransItem = $CReqAtk->cekTransItem($itemIdGet);
	$cekCartItem = $CReqAtk->cekCartItem($itemIdGet);
	//echo $cekCartItem;
	if($cekTransItem > 0 || $cekCartItem > 0)
	{
		$dis = "disabled";
	}
}

if($aksiPost == "new")
{
    $tmpImg = $_FILES['fileUpload']['tmp_name'];
	$expNamaFile = explode(".", $_FILES["fileUpload"]["name"]);
	$imgExt = end($expNamaFile);
	
	$nmUnit = mysql_real_escape_string($_POST['nmUnit']);
	$category = $_POST['category'];
	$qtyMin = mysql_real_escape_string($_POST['qtyMin']);
	$qtyType = $_POST['qtyType'];
	$cDisplay = $_POST['cDisplay'];
	
	$nameImg = $dateTime."-".$nmUnit.".".$imgExt ;
	
	//echo $nameImg." | ".$tmpImg." | ".$nmUnit." | ".$category." | ".$qtyMin." | ".$qtyType." | ".$cDisplay." | ".$imgExt;
	
	$CKoneksiAtk->mysqlQuery("INSERT INTO item (itemimg, itemname, category, qtymin, qtytype, itemdisplay, addusrdt) VALUES ('".$nameImg."', '".$nmUnit."', '".$category."', '".$qtyMin."', '".$qtyType."', '".$cDisplay."', '".$CPublic->userWhoAct()."');");
	$lastInsertId = mysql_insert_id();
	
	$MyThumb = new Thumbnail("".$tmpImg."", 255, 221, "../picture/".$nameImg."");
	$MyThumb->Output();
	
	$CHistory->updateLogReqAtk($userIdLogin, "Buat Master Item baru (itemid = <b>".$lastInsertId."</b>, nama item = <b>".$nmUnit."</b>)");
}

if($aksiPost == "edit")
{
	$itemIdPost = $_POST['itemId'];
	$nmUnit = mysql_real_escape_string($_POST['nmUnit']);
	$category = $_POST['category'];
	$qtyType = $_POST['qtyType'];
	
	$qtyMin = mysql_real_escape_string($_POST['qtyMin']);
	$cDisplay = $_POST['cDisplay'];
	
	$tmpImg = $_FILES['fileUpload']['tmp_name'];
	if($tmpImg != "")
	{
		$expNamaFile = explode(".", $_FILES["fileUpload"]["name"]);
		$imgExt = end($expNamaFile);
		$nameImg = $dateTime."-".$nmUnit.".".$imgExt ;
		
		$pathBefore = "../picture/".$CReqAtk->detilAtkItem("itemimg",$itemIdPost);
		$pathUpdate = "../picture/update//Up-".$CReqAtk->detilAtkItem("itemimg", $itemIdPost);
		
		copy($pathBefore, $pathUpdate); //kopikan file ke folder documentDel/
		unlink($pathBefore); //delete file yang akan digantikan
		$MyThumb = new Thumbnail("".$tmpImg."", 255, 221, "../picture/".$nameImg."");
		$MyThumb->Output();
		
		$CKoneksiAtk->mysqlQuery("UPDATE item SET itemimg='".$nameImg."', itemname='".$nmUnit."', category='".$category."', qtymin='".$qtyMin."', qtytype='".$qtyType."', itemdisplay='".$cDisplay."', updusrdt='".$CPublic->userWhoAct()."' WHERE itemid=".$itemIdPost."");
		$CHistory->updateLogReqAtk($userIdLogin, "Edit Master Item (termasuk gambar)(itemid = <b>".$itemIdPost."</b>, nama item = <b>".$nmUnit."</b>)");
	}
	//echo $itemIdPost." | ".$nameImg." | ".$tmpImg." | ".$nmUnit." | ".$category." | ".$qtyMin." | ".$qtyType." | ".$cDisplay." | ".$imgExt;
	
	if($tmpImg == "")
	{
		if($nmUnit == "" && $category == "" && $qtyType == "")
		{
			$nmUnit = $CReqAtk->detilAtkItem("itemname", $itemIdPost);
			
			$CKoneksiAtk->mysqlQuery("UPDATE item SET qtymin='".$qtyMin."', itemdisplay='".$cDisplay."', updusrdt='".$CPublic->userWhoAct()."' WHERE itemid=".$itemIdPost."");
			$CHistory->updateLogReqAtk($userIdLogin, "Edit Master Item (itemid = <b>".$itemIdPost."</b>, nama item = <b>".$nmUnit."</b>)");
		}
		
		if($nmUnit != "" && $category != "" && $qtyType != "")
		{
			$CKoneksiAtk->mysqlQuery("UPDATE item SET itemname='".$nmUnit."', category='".$category."', qtymin='".$qtyMin."', qtytype='".$qtyType."', itemdisplay='".$cDisplay."', updusrdt='".$CPublic->userWhoAct()."' WHERE itemid=".$itemIdPost."");
			$CHistory->updateLogReqAtk($userIdLogin, "Edit Master Item (itemid = <b>".$itemIdPost."</b>, nama item = <b>".$nmUnit."</b>)");
		}
	}	
}
?>

<body>
<form action="halMasterItemDetail.php" name="formItem" id="formItem" method="post" enctype="multipart/form-data">
<table width="99%">
<tr valign="top" class="formInput">
	<td width="22%" rowspan="5" align="center">
    <?php
		if($aksiGet == "edit")
		{
        	echo "<img src=\"../picture/".$img."\" width=\"120\" height=\"104\"/>";
		}
		if($aksiGet == "new")
		{
        	echo "<span id=\"spanImg\"></span>";
		}
	?>
    </td>
    <td height="28" width="15%">Item Image</td>
    <td width="58%">
        <input type="file" class="elementDefault" id="fileUpload" name="fileUpload" style="width:99%;height:21px;" title="Choose File from LocalDisk" <?php echo $dis;?> onChange="pilihUpload();<?php echo $imgTmp;?>">
    </td>
    <td width="5%" id="tdFileAdaTidak"><input type="hidden" id="fileAdaTidak" name="fileAdaTidak" value="kosong"></td>
</tr>
<tr class="formInput">
    <td height="10">&nbsp;</td>
    <td>* Image automatically resize to 150 X 130 px resolution</td>
    <td></td>
</tr>
<tr valign="top" class="formInput">
    <td>Unit Name</td>
    <td>
    <input type="text" class="elementDefault" id="nmUnit" name="nmUnit" style="width:96%;height:15px;" value="<?php echo $name;?>" onFocus="ajaxGetFile(this.value, 'cekFileAdaTidak', 'tdFileAdaTidak');" onKeyUp="ajaxGetFile(this.value, 'cekFileAdaTidak', 'tdFileAdaTidak');" <?php echo $dis;?>>
    </td>
    <td>
    </td>
</tr>
<tr valign="top" class="formInput">
    <td height="20">Item Category</td>
    <td>
        <select id="category" name="category" class="elementMenu" style="width:99%;height:25x;" <?php echo $dis;?>>
            <option value="0">-- PLEASE SELECT -- </option>  
            <option value="ATK" selected>ATK</option>
        </select>
    </td>
    <td></td>
</tr>
<tr valign="top" class="formInput">
    <td height="20">Min. Stock</td>
    <td>
        <input type="text" class="elementDefault" id="qtyMin" name="qtyMin" style="width:30%;height:15px;" value="<?php echo $qtyMinGet; ?>"/>
                    
        <select id="qtyType" name="qtyType" class="elementMenu" style="width:65%;height:27px;" <?php echo $dis;?>>
            <option value="0">-- PLEASE SELECT -- </option>
        <?php
        $sql = $CKoneksiAtk->mysqlQuery("SELECT * FROM mstunit ORDER BY unitid ASC");
        while($r = $CKoneksiAtk->mysqlFetch($sql))
        {
			$rName = $r['unitname'];
			$sel = "";
			if($rName == $qtyTypeGet)
			{
				$sel = "selected";
			}
            echo "<option value=\"".$rName."\" ".$sel.">".$rName."</option>";
        }
        ?>  
        </select>
    </td>
    <td></td>
</tr>
<tr valign="top" class="formInput">
	<td>
    	<input type="hidden" id="aksi" name="aksi" value="<?php echo $aksiGet; ?>"/>
    	<input type="hidden" id="itemId" name="itemId" value="<?php echo $itemIdGet; ?>"/>
    </td>
    <td height="20">Display Item</td>
    <td>
        <input type="checkbox" id="cDisplay" name="cDisplay" <?php echo $displayItem;?>/>
    </td>
    <td></td>
</tr>
<tr class="formInput">
    <td colspan="4" align="center" valign="middle">
    	
    	<span id="errorMsg" class="errorMsg"></span><span id="report" class="report"></span>
    </td>
</tr>
<tr>
	<td colspan="4" align="center">
    	<button type="submit" class="btnStandar" id="btnAdd" onClick="submitItem(); return false;" title="Save Item">
            <table height="24">
              <tr>
                <td align="center" width="20"><img src="../picture/disk-black.png"/></td>
                <td align="left">Save&nbsp;</td>
              </tr>
            </table>
        </button>
        <?php echo $btnCancel;?>
    </td>
</tr>
</table>
</form>
</body>
<?php
if($aksiPost == "new" || $aksiPost == "edit")
{
	if($aksiPost == "new")
	{
		$report = "added";
	}
	if($aksiPost == "edit")
	{
		$report = "edit";
	}
?>
	<script language="javascript">
		parent.refreshPage('');
		report('<?php echo $report; ?>');
	</script>	
<?php
}
?>
</HTML>