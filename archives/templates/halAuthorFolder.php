<?php
require_once("../../config.php");

$foldSubGet = $_GET['foldSub'];
$ideGet = $_GET['ide'];

$aksi = "simpanNewEmp";

$aksiPost = $_POST['aksi'];
if($aksiPost == "simpanNewEmp")
{
	$empNo = $_POST['empno'];
	$nmEmp = $CLogin->detilLoginByEmpno($empNo, "userfullnm");	
	if($empNo == "99999")
	{
		$nmEmp = "ALL";
	}
	$nmDiv = $CLogin->detilLoginByEmpno($empNo, "nmdiv");
	$nmDept = $CLogin->detilLoginByEmpno($empNo, "nmdept");
	$nmJab = $CLogin->detilLoginByEmpno($empNo, "nmjabatan");
	$folderOwner = $CFolder->detilFold($ideGet, "folderowner");
	$nameFold = $CFolder->detilFold($ideGet, "namefold");

	$CKoneksi->mysqlQuery("INSERT INTO tblauthorfold (empno, nama, nmdiv, nmdept, nmjabatan, idefold, namefold, folderowner, addusrdt)VALUES ('".$empNo."', '".$nmEmp."', '".$nmDiv."', '".$nmDept."', '".$nmJab."', '".$ideGet."', '".$nameFold."', '".$folderOwner."', '".$CPublic->userWhoAct()."');");
	$lastInsertId = mysql_insert_id();
	$CHistory->updateLog($userIdLogin, "Tambah Employee pada tblauthorfold (idauthorfold = <b>".$lastInsertId."</b>, nama employee = <b>".$nmEmp."</b>)");
}
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function exit()
{
	var foldSub = document.getElementById('foldSub').value;
	parent.exit("no", "", foldSub, "", "", "", "closeNewFolder");
}

function pilihBtnAdd()
{
	var empno = formAddAuthor.empno.value;
	if(empno == "00000")
	{
		document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" />&nbsp;Please select employee name";
	}
	else
	{
		document.getElementById('errorMsg').innerHTML = "&nbsp;";
		formAddAuthor.submit();
	}
}

function deleteEmpAuthorFold(idAuthorFold, ideFold)
{
	var answer  = confirm("Are you sure want to delete?");
	if(answer)
	{
		document.getElementById('iframeAuthorFolderList').src = "";
		document.getElementById('iframeAuthorFolderList').src = "halAuthorFolderList.php?aksi=deleteEmpAuthor&ideFold="+ideFold+"&idAuthorFold="+idAuthorFold;
	}
	else
	{	return false;	}
}

function refreshHalAuthorization()
{
	parent.formAuthor.submit();
}

function pilihAuthorEmp(idAuthorFold, ideFold)
{
	document.getElementById('iframeAuthor').src = "";
	document.getElementById('iframeAuthor').src = "halAuthor.php?aksi=pilihAuthorEmp&ideFold="+ideFold+"&idAuthorFold="+idAuthorFold;
}
</script>



<input type="hidden" id="foldSub" name="foldSub" value="<?php echo $foldSubGet; ?>" />
<input type="hidden" id="ide" name="ide" value="<?php echo $ideGet; ?>" />


<body bgcolor="#FFFFFF">
<center>
<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
<!--<tr>
	<td align="center" height="20">
    	<table cellpadding="0" cellspacing="0" width="100%">
        <tr>
        	<td align="center"><span class="teksMyFolder">:: GIVE ACCESS / PERMISSIONS TO FOLDER ::</span></td>
        </tr>
        </table>	
    </td>
</tr>-->

<tr><td height="10"></td></tr>

<tr>
	<td align="center" valign="bottom" height="30">
    	<form action="halAuthorFolder.php?ide=<?php echo $ideGet; ?>&foldSub=<?php echo $foldSubGet; ?>" method="post" enctype="multipart/form-data" id="formAddAuthor" name="formAddAuthor">
    	<table cellpadding="0" cellspacing="0" width="100%" border="0">
        <tr align="left" valign="bottom">
        	<td width="15%" class="teksForm1">&nbsp;Employee name&nbsp;&nbsp;</td>
        	<td width="9%" height="30">&nbsp;
            	<button class="btnStandar" onMouseOver="this.className='btnStandarHover'" onMouseOut="this.className='btnStandar'" onClick="pilihBtnAdd();" style="width:70px;height:29px;" title="Add the Choosen name to Have Access">
                    <table border="0" width="100%" cellpadding="0" cellspacing="0" class="fontBtnStandar" onMouseOver="this.className='fontBtnStandarHover'" onMouseOut="this.className='fontBtnStandar'">
                      <tr>
                        <td align="center" width="25"><img src="../../picture/Button-Add-blue-32.png" height="20"/> </td>
                        <td align="center">Add</td>
                      </tr>
                    </table>
                </button>           
             </td>
            <td>
<?php
		$empNoSendiri = $CLogin->detilLogin($userIdLogin, "empno");
		echo $CEmployee->menuAuthorEmployee($CPublic, $empNoSendiri, $ideGet);
?> 
            </td>
        </tr>
        </table>	
    	<input type="hidden" name="aksi" id="aksi" value="<?php echo $aksi; ?>"> 
    	</form>
    </td>
</tr>

<tr><td height="25"><span id="errorMsg" class="errorMsg">&nbsp;</span></td></tr>

<tr>
	<td valign="top">
    	<table cellpadding="0" cellspacing="0" width="100%" border="0">
        <tr>
        	<td width="40%">
            	<table cellpadding="0" cellspacing="0" width="100%" border="0" class="fontMyFolderList">
                <tr align="center" style="background-color:#666;color:#EFEFEF;font-family:sans-serif;font-size:18px;font-weight:bold;">
                    <td width="25%" height="40">Employee name</td>
                </tr>
                <tr>
                	<td align="center" id="tdFrameSeaExp" class="">
                    	<iframe width="100%" height="310" src="halAuthorFolderList.php?ideFold=<?php echo $ideGet; ?>" target="iframeAuthorFolderList" name="iframeAuthorFolderList" id="iframeAuthorFolderList" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes"></iframe></td>
                </tr>
                </table>
            </td>
            <td>&nbsp;</td>
            <td width="58%" valign="top">
            <!-- ############ TABEL AKSES -->
            	<table cellpadding="0" cellspacing="0" width="100%" border="0" class="fontMyFolderList">
                <tr align="center" style="background-color:#666;color:#EFEFEF;font-family:sans-serif;font-size:18px;font-weight:bold;">
                    <td width="25%" height="40">Permissions</td>
                </tr>
                <tr>
                	<td align="center" id="tdFrameSeaExp" class="">
                    	<iframe width="100%" height="310" src="halAuthor.php?ideFold=<?php echo $ideGet; ?>" target="iframeAuthor" name="iframeAuthor" id="iframeAuthor" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes"></iframe></td>
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

<?php
if($aksiGet == "simpanNewEmp")
{
	?>
	<script language="javascript">
		document.getElementById('iframeAuthorFolderList').src = "";
		document.getElementById('iframeAuthorFolderList').src = "templates/halAuthorFolderList.php?ideFold=<?php echo $ideGet; ?>";
	</script>	
<?php
}
?>