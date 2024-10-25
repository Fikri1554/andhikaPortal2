<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/main.js"></script>
<?php
require_once('../../config.php');
require_once('../configAtk.php');

if($aksiPost == "newReq")
{
	$date = str_replace("-","/",$CPublic->waktuSek());
	$tgl =  substr($date,8,2);
	$bln =  substr($date,5,2);
	$thn =  substr($date,0,4);
	$waktu =  substr($date,11,8);
	
	$tmpImg = $_FILES['fileUpload']['tmp_name'];
	$expNamaFile = explode(".", $_FILES["fileUpload"]["name"]);
	$imgExt = end($expNamaFile);
	
	$requestor = $userIdLogin;
	$nmItem = mysql_real_escape_string($_POST['nmItem']);
	$cat = $_POST['cat'];
	$qty = mysql_real_escape_string($_POST['qty']);
	$satuanQty = $_POST['satuanQty'];
	$note = mysql_real_escape_string($_POST['note']);
	
	$imgName = $requestor."-".$thn.$bln.$tgl."-".str_replace(":","",substr($date,11,8)).".".$imgExt;

	$CKoneksiAtk->mysqlQuery("INSERT INTO reqnew (ownerid, tgl, bln, thn, waktu, category, status, reqimg, reqname, reqqty, qtytype, reqnote) VALUES (".$requestor.", '".$tgl."', '".$bln."', ".$thn.", '".$waktu."', '".$cat."', 'Unread', '".$imgName."', '".$nmItem."', ".$qty.", '".$satuanQty."', '".$note."');");
	$lastInsertId = mysql_insert_id();
	
	$MyThumb = new Thumbnail("".$tmpImg."", 255, 221, "../requestPict/".$imgName."");
	$MyThumb->Output();
	
	$CHistory->updateLogReqAtk($userIdLogin, "Request item baru(requestid = <b>".$lastInsertId."</b>, nama item = <b>".$nmItem."</b>)");

// ========= NOTIFIKASI ===================================================================================================	
	// Notif Email Ke Admin
	$emailKe = $CReqAtk->emailKe($db);
	if($emailKe != "")
	{
		$CNotif->emailNewReq($CReqAtk, $lastInsertId, $emailKe, $db, $link);
	}
	
	//variabel notif desktop ke admin
	$user =$CReqAtk->detilLoginAtk($userIdLogin,"userfullnm",$db);
	$jmlItem = $CReqAtk->jmlTrans($lastInsertId);
	$s = "";
	if($jmlItem > 1)
	{
		$s = "s";
	}
	$notes = $user." has Request New Item on ATK Request. Please, check Andhika Portal.";
	//Notif Desktop ke admin
	$CReqAtk->varNotifDesktop("", $koneksiOdbc, $koneksiOdbcId, $CPublic, $CLogin, $CNotif, $notes, $db);
}
?>
<script>
function submit()
{
	var fileUpload = document.getElementById('fileUpload').value;
	var nmItem = document.getElementById('nmItem').value;
	var cat = document.getElementById('cat').value;
	var qty = document.getElementById('qty').value;
	var satuanQty = document.getElementById('satuanQty').value;
	var note = document.getElementById('note').value;
	var aksi = document.getElementById('aksi').value;
	
	var tipeFile = fileUpload.split('.').pop();
	var imgExt = "jpg,JPG,jpeg,JPEG,png,PNG,x-png,X-PNG";
	var adaFileExt = imgExt.indexOf(tipeFile+",");
	
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
	
	if(nmItem.replace(/ /g,"") == "") // nmItem tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = "Item Name still empty";
		document.getElementById('nmItem').focus();
		return false;
	}
	
	if(cat.replace(/ /g,"") == "") // category tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = "Category still not choosen";
		document.getElementById('cat').focus();
		return false;
	}
	
	if(qty.replace(/ /g,"") == "") // qty tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = "Qty still empty";
		document.getElementById('qty').focus();
		return false;
	}
	
	if(isNaN(qty)) // qty tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = "Qty must be number";
		document.getElementById('qty').focus();
		return false;
	}
	
	if(satuanQty.replace(/ /g,"") == "0") // qty Type tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = "Stock tpye still not choosen";
		document.getElementById('satuanQty').focus();
		return false;
	}
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		formReq.submit();
	}
	else
	{	return false;	}
		
}

function pilihUpload()
{
	var fileUpload = document.getElementById('fileUpload').value;
	
	var namaFile = fileUpload.split('\\').pop();
	document.getElementById('nmItem').value = namaFileCustom(namaFile);
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
	document.getElementById('spanImg').innerHTML = '<img id="imgTmp" src="'+input+'" width="150" height="130" alt="a"/>';
}
</script>
<body bgcolor="#F8F8F8">
<center>
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="99%" align="center">
<tr valign="top" style="width:100%;">
	<td align="left">
    	<span class="teksLvlFolder" style="color:#666;font-size:14px;"><b>&nbsp;</b></span>
    </td>
	<td align="right">
    	<span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: Request New Item ::</b></span>
    </td>
</tr>
<tr><td height="5" colspan="2"></td></tr>
<tr valign="top">
	<td colspan="2" class="tdMyFolder" bgcolor="#FFFFFF" height="100%" valign="top" align="left">
    <form action="" name="formReq" id="formReq" method="post" enctype="multipart/form-data">
    <table cellpadding="0" cellspacing="5" width="95%" height="100%" class="formInput" border="0">
                <tr><td height="10"></td></tr>
                <tr valign="top">
                	<td width="28%" rowspan="5" align="right">
                    	<!--<img id="imgTmp" src="#" width="150" height="130" alt="a"/>--><span id="spanImg">&nbsp;&nbsp;</span>
                    </td>
                    <td height="28" width="17%">Item Image</td>
                    <td width="55%">
                        <input type="file" class="elementDefault" id="fileUpload" name="fileUpload" style="width:99%;height:28px;" title="Choose File from LocalDisk" onChange="pilihUpload();readURL(this.value);">
                    </td>
                </tr>
                <tr>
                	<td height="18">&nbsp;</td>
                    <td>* Image automatically resize to 150 X 130 px resolution</td>
                </tr>
                <tr valign="top">
                    <td height="28">Item Name</td>
                    <td>
        			<input type="text" class="elementDefault" id="nmItem" name="nmItem" style="width:99%;height:28px;" value="<?php echo $nameDoc;?>" onFocus="ajaxGetFile(this.value, 'cekFileAdaTidak', 'tdFileAdaTidak');" onKeyUp="ajaxGetFile(this.value, 'cekFileAdaTidak', 'tdFileAdaTidak');">
                    </td>
                </tr>
                <tr valign="top">
                    <td height="28">Item Category</td>
                    <td>
                    <select id="cat" name="cat" class="elementMenu" style="width:99%;height:29px;">
                        <option value="">-- PLEASE SELECT -- </option>  
                        <option value="ATK" selected>ATK</option>
                    </select>
                    </td>
                </tr>
                <tr valign="top">
                    <td height="28">Qty</td>
                    <td>
                    <input type="text" class="elementDefault" id="qty" name="qty" style="width:30%;height:28px;" value="<?php echo $nameDoc;?>" >
                    
                    <select id="satuanQty" name="satuanQty" class="elementMenu" style="width:68%;" onChange="">
                        <option value="0">-- PLEASE SELECT -- </option>
                    <?php
					$sql = $CKoneksiAtk->mysqlQuery("SELECT * FROM mstunit ORDER BY unitid ASC");
					while($r = $CKoneksiAtk->mysqlFetch($sql))
					{
						echo "<option value=\"".$r['unitname']."\">".$r['unitname']."</option>";
					}
					?>  
                    </select>
                    </td>
                </tr>
                <tr valign="top">
                    <td height="28"><input type="hidden" id="aksi" name="aksi" value="newReq"/></td>
                    <td height="28">Note</td>
                    <td>
                    	<textarea class="elementDefault" id="note" name="note" style="width:99%;height:70px;"></textarea>
                    </td>
                </tr>
                <tr>
                	<td id="tdFileAdaTidak">&nbsp;<input type="hidden" id="fileAdaTidak" name="fileAdaTidak" value="kosong"></td>
                    <td colspan="2" height="20" align="center" valign="middle">&nbsp; <span id="errorMsg" class="errorMsg">&nbsp;</span></td>
                </tr>
		</table>
        </form>
    </td>
</tr>
<tr><td height="5"></td></tr>
<tr valign="top">
	<td colspan="2" class="tdMyFolder" bgcolor="#FFFFFF" height="65" valign="middle">
       &nbsp;<button onClick="parent.closeWarn();" class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" style="width:80px;height:55px;" title="Close Window">
       	<table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
            <tr>
                <td align="center"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="25"/> </td> 
            </tr>
            <tr>
                <td align="center">CLOSE</td>
            </tr>
            </table>
       </button>
       &nbsp;<button onClick="submit(); return false;" class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" style="width:80px;height:55px;" title="Save Data">
       	<table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
            <tr>
                <td align="center"><img src="../../picture/Floppy-Disk-blue-32.png" height="25"/> </td> 
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
if($aksiPost == "newReq")
{
	echo "parent.exit();
		  parent.refreshPage();
		  parent.report('Request New Item succesfully submit');";
}
?>
</script>