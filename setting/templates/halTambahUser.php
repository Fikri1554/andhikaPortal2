<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$halamanGet = $_GET['halaman'];
$halamanPost = $_POST['halaman'];

if($halamanGet == "newUser")
{
	$aksi = "simpanNewUser";
	$judul = "Create new User";
	$disGiveAccess = "disabled";
	$dis = "disabled";
}
else if($halamanGet == "editUser")
{
	$userIdGet = $_GET['userId'];
	$paramText = $_GET['paramText'];
	$aksi = "simpanEditUser";
	$judul = "Edit User";
	$disGiveAccess = "";
	$dis = "";
	
	$empNo = $CLogin->detilLogin($userIdGet, "empno");
	$username = $CLogin->detilLogin($userIdGet, "username");
	$active = $CLogin->detilLogin($userIdGet, "active");
	$kdDiv = $CLogin->detilLogin($userIdGet, "kddiv");
	$useremail = $CLogin->detilLogin($userIdGet, "useremail");
	$userInitialHr = $CLogin->detilLogin($userIdGet, "userinithr");

	$cekMenuSetting = "";
	$cekBtnUser = "";
	$cekBtnLogHistory = "";
	$cekBtnCustomSub = "";
	$cekBtnOtherApp = "";
	$cekKpiSetting = "";
	$cekMenuApplication = "";
	$cekBtnExportPrint = "";

	if($CLogin->detilLogin($userIdGet, "menusetting") == "Y")
	{
		$cekMenuSetting = "checked";
	}
	if($CLogin->detilLogin($userIdGet, "btnuser") == "Y")
	{
		$cekBtnUser = "checked";
	}
	if($CLogin->detilLogin($userIdGet, "loghistory") == "Y")
	{
		$cekBtnLogHistory = "checked";
	}
	if($CLogin->detilLogin($userIdGet, "subcustom") == "Y")
	{
		$cekBtnCustomSub = "checked";
	}
	if($CLogin->detilLogin($userIdGet, "otherapp") == "Y")
	{
		$cekBtnOtherApp = "checked";
	}
	if($CLogin->detilLogin($userIdGet, "kpisetting") == "Y")
	{
		$cekBtnKpiSetting = "checked";
	}
	if($CLogin->detilLogin($userIdGet, "menuapplication") == "Y")
	{
		$cekMenuApplication = "checked";
	}
	// if($CLogin->detilLogin($userIdGet, "btnexportprint") == "Y")
	// {
	// 	$cekBtnExportPrint = "checked";
	// }
}

if($halamanPost == "simpanNewUser")
{
	$empNo = $_POST['empno'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$userfullnm = $CEmployee->detilEmp($empNo, "nama");
	$userInit = strtolower(str_replace(" ", "", $userfullnm));
	$userInitialHr = strtoupper($_POST['initialHr']);
	$email = $_POST['email'];
	$active = $_POST['active'];
	
	$kdDiv = $CEmployee->detilTblEmpGen($empNo, "kddiv");
	$kdDept = $CEmployee->detilTblEmpGen($empNo, "kddept");
	$kdJab = $CEmployee->detilTblEmpGen($empNo, "kdjabatan");
	
	$nmDiv = $CEmployee->detilDiv($kdDiv, "nmdiv");
	$nmDept = $CEmployee->detilDept($kdDept, "nmdept");
	$nmJab = $CEmployee->detilJabatan($kdJab, "nmjabatan");
	
	$CKoneksi->mysqlQuery("INSERT INTO login (empno, username, userpass, userfullnm, userinit, userinithr, active, nmdiv, nmdept, nmjabatan, useremail, addusrdt)VALUES ('".$empNo."', '".$username."', md5('".$password."'), '".$userfullnm."', '".$userInit."', '".$userInitialHr."', '".$active."', '".$nmDiv."', '".$nmDept."', '".$nmJab."', '".$email."', '".$CPublic->userWhoAct()."');");
	$lastUserId = mysql_insert_id();
	$CHistory->updateLog($userIdLogin, "Buat User baru (userId = '".$CPublic->zerofill($lastUserId, 5)."', Username = <b>".$username."</b>, Userinit Hr = <b>".$userInitialHr."</b>, Fullname = <b>".$userfullnm."</b>)");
	$CHistory->updateLog($lastUserId, "<b> ***** USER INI BARU DIBUAT OLEH ADMIN *****</b>");
}
	
if($halamanPost == "simpanEditUser")
{
	$userId = $_POST['userId'];
	$empNo = $_POST['empNo'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	$userInitialHr = strtoupper($_POST['initialHr']);
	$active = $_POST['active'];
	$divisi = $_POST['divisi'];
	
	$kdDiv = $CEmployee->detilTblEmpGen($empNo, "kddiv");
	$kdDept = $CEmployee->detilTblEmpGen($empNo, "kddept");
	$kdJab = $CEmployee->detilTblEmpGen($empNo, "kdjabatan");
	
	$nmDiv = $CEmployee->detilDiv($kdDiv, "nmdiv");
	$nmDept = $CEmployee->detilDept($kdDept, "nmdept");
	$nmJab = $CEmployee->detilJabatan($kdJab, "nmjabatan");
	
	
	if($password == "")
	{
		$CKoneksi->mysqlQuery("UPDATE login SET username = '".$username."', userinithr='".$userInitialHr."', useremail = '".$email."', active = '".$active."', kddiv = '".$divisi."', nmdiv='".$nmDiv."', nmdept='".$nmDept ."', nmjabatan='".$nmJab."', updusrdt = '".$CPublic->userWhoAct()."' WHERE userid = '".$userId."' AND deletests=0");
		$CHistory->updateLog($userIdLogin, "Rubah user (Nama = <b>".$CLogin->detilLogin($userId, "userfullnm")."</b>, Username = <b>".$username."</b>, Userinit Hr = <b>".$userInitialHr."</b>, active = <b>".$active."</b>, kddiv = <b>".$divisi."</b>)");
	}
	elseif($password != "")
	{
		$CKoneksi->mysqlQuery("UPDATE login SET username = '".$username."', userinithr='".$userInitialHr."', useremail = '".$email."', userpass = md5('".$password."'), active = '".$active."', kddiv = '".$divisi."', nmdiv='".$nmDiv."', nmdept='".$nmDept ."', nmjabatan='".$nmJab."', updusrdt = '".$CPublic->userWhoAct()."' WHERE userid = '".$userId."' AND deletests=0");
		$CHistory->updateLog($userIdLogin, "Rubah user (Nama = <b>".$CLogin->detilLogin($userId, "userfullnm")."</b>, Username = <b>".$username."</b>, Userinit Hr = <b>".$userInitialHr."</b>, Password = <b>#####</b>, active = <b>".$active."</b>, kddiv = <b>".$divisi."</b>)");
	}
}
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript">
function exit()
{
	parent.exit("no");
	
	var paramText = parent.document.getElementById('paramText').value;
	
	if(paramText != "")
	{
		parent.document.getElementById('iframeHalUser').src = "";
		parent.document.getElementById('iframeHalUser').src = "templates/halUserList.php?halaman=cari&paramCari="+paramText;
	}
}

function pilihBtnSave()
{
	var empno = formUser.empno.value;
	var username = formUser.username.value;
	var password = formUser.password.value;
	var email = formUser.email.value;
	var initialHr = formUser.initialHr.value;
	var halaman = formUser.halaman.value;
	
	if(empno == "00000")
	{
		document.getElementById('errorMsg').innerHTML = "Please choose Employe name!";
		return false;
	}
	if(username.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = "Username still empty!";
		return false;
	}
	if(email.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = "Email still empty!";
		return false;
	}
	
	if(initialHr.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = "Initial still empty!";
		return false;
	}
	if(halaman == "simpanNewUser")
	{
		if(password.replace(/ /g,"") == "")
		{
			document.getElementById('errorMsg').innerHTML = "password still empty!";
			return false;
		}
	}
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		formUser.submit();
	}
	else
	{	return false;	}
		
}

function ajaxGetGiveAccess(statusCentang, aksi)
{
	var mypostrequest=new ajaxRequest()
	mypostrequest.onreadystatechange=function()
	{
		if (mypostrequest.readyState==4)
		{
			if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1)
			{
				document.getElementById('idHalCentangSubCustom').innerHTML=mypostrequest.responseText;
			}
		}
	}
	
	if(aksi == "cekMenuSetting"  || aksi == "cekMenuApplication" || aksi == "cekBtnUser" || aksi == "cekLogHistory" || aksi == "cekCustomSub" || aksi == "cekOtherApp" || aksi == "cekKpiSetting" || aksi == "cekBtnExportPrint")
	{
		var parameters="halaman="+aksi+"&statusCentang="+statusCentang+"&userIdChoose=<?php echo $userIdGet; ?>";
	}
	
	mypostrequest.open("POST", "../halPostSetting.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}
</script>

<style>
#initialHr{
    text-transform:uppercase;
}
</style>


<body bgcolor="#F8F8F8">
<center>
<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
<tr valign="top">

	<td height="25" width="50%">&nbsp;</td>
    <td align="right"><span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: <?php echo $judul; ?> ::</b></span>&nbsp;</td>
</tr>

<tr valign="top">
    <td colspan="2" class="tdMyFolder" bgcolor="#FFFFFF" height="437" valign="top">
       
        <table cellpadding="0" cellspacing="0" height="100%" width="100%" border="0">
        
        <tr>
            <td align="center" height="130" width="50%" valign="top">
            <form action="halTambahUser.php" method="post" enctype="multipart/form-data" id="formUser" name="formUser">
                <table cellpadding="0" cellspacing="5" width="100%" height="100%" class="formInput" border="0">
                <tr><td height="10" width="23%"></td></tr>
                <!--<input type="text" id="paramText" name="paramText" value="<?php echo $paramText; ?>"/>-->
                <tr valign="middle" align="left">
                    <td height="28" width="23%">EMPLOYEE NAME</td>
                    <td width="77%">
        			<?php
                    if($halamanGet == "newUser")
                    {
                        echo $CEmployee->menuEmployee($empNo, $CLogin);
                    }
                    else if($halamanGet == "editUser")
                    {
                        echo $CEmployee->menuEmployeeEditUser($empNo);
                    }  
        			?> 
                    </td>
                </tr>
                <tr valign="middle" align="left">
                    <td height="28">USERNAME</td>
                    <td>
                        <input type="text" class="elementSearch" id="username" name="username" style="width:95%;" value="<?php echo $username; ?>">
                    </td>
                </tr>
                <tr valign="middle" align="left">
                    <td height="28">PASSWORD</td>
                    <td>
                        <input type="password" class="elementSearch" id="password" name="password" style="width:95%;" value="<?php echo $password; ?>">
                    </td>
                </tr>
                <tr valign="middle" align="left">
                    <td height="28">EMAIL</td>
                    <td>
                        <input type="text" class="elementSearch" id="email" name="email" style="width:70%;" value="<?php echo $useremail; ?>"> @andhika.com
                    </td>
                </tr>
                <tr valign="middle" align="left">
                    <td height="28">INITIAL</td>
                    <td>
                        <input type="text" class="elementSearch" id="initialHr" name="initialHr" style="width:10%;" maxlength="3" value="<?php echo $userInitialHr; ?>" onkeypress="return;">
                    </td>
                </tr>
                <tr valign="middle" align="left">
                    <td height="28">ACTIVE</td>
                    <td>
        			<?php
                        echo $CEmployee->menuActive($active);
        			?>
                    </td>
                </tr>
                <tr valign="middle" align="left">
                    <td height="28">DIVISION</td>
                    <td>
        			<?php echo $CEmployee->menuDivision($kdDiv, $dis); ?>
                    </td>
                </tr>
                <tr>
                	<td colspan="2" height="20" align="center" valign="middle">&nbsp; <span id="errorMsg" class="errorMsg">&nbsp;</span></td>
                </tr>
                <tr>
                    <td>&nbsp;
                    <input type="hidden" id="userId" name="userId" value="<?php echo $userIdGet; ?>" />
            		<input type="hidden" id="empNo" name="empNo" value="<?php echo $empNo; ?>" />
            		<input type="hidden" name="halaman" id="halaman" value="<?php echo $aksi; ?>">  
                    </td>
                </tr>
                </table> 
                       
            </form>
            </td>
            <td rowspan="4" valign="top">
            
                <table cellpadding="0" cellspacing="0" width="99%">
                <tr><td height="10"></td></tr>
                <tr align="center" style="background-color:#666;">
                    <td>
                    
                        <table cellpadding="0" cellspacing="0" width="100%" style="color:#EFEFEF;font-family:sans-serif;font-size:18px;font-weight:bold;">
                        <tr>
                            <td width="25%">&nbsp;</td>
                            <td width="50%" height="30" align="center">Give Access</td>
                            <td width="25%">&nbsp;</td>
                        </tr>
                        </table>
                    
                    </td>
                </tr>
                <tr>
                    <td class="tabelBorderTopNull" style="border-width:thin;">
                        <div style="width:100%;height:387px;overflow:auto;overflow-x: hidden;top: expression(offsetParent.scrollTop);">
                        
                        <table width="100%" cellspacing="0" style="font-family:Tahoma;font-size:12px;color:#369;font-weight:bold;">
                        <tr>
                            <td align="left">&nbsp;<input <?php echo $disGiveAccess; ?> <?php echo $cekMenuSetting; ?> type="checkbox" onClick="ajaxGetGiveAccess(this.checked, 'cekMenuSetting');" >&nbsp;Menu Setting</td>
                        </tr>
                        <tr>
                        	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input <?php echo $disGiveAccess; ?> <?php echo $cekBtnUser; ?> onClick="ajaxGetGiveAccess(this.checked, 'cekBtnUser');" type="checkbox" >&nbsp;Button User</td>
                        </tr>
                        <tr>
                        	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input <?php echo $disGiveAccess; ?> <?php echo $cekBtnLogHistory; ?> onClick="ajaxGetGiveAccess(this.checked, 'cekLogHistory');" type="checkbox" >&nbsp;Button Log History</td>
                        </tr>
                        <tr>
                        	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input <?php echo $disGiveAccess; ?> <?php echo $cekBtnCustomSub; ?> onClick="ajaxGetGiveAccess(this.checked, 'cekCustomSub');" type="checkbox" >&nbsp;Button Custom Sub</td>
                        </tr>
                        <tr>
                        	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input <?php echo $disGiveAccess; ?> <?php echo $cekBtnOtherApp; ?> onClick="ajaxGetGiveAccess(this.checked, 'cekOtherApp');" type="checkbox" >&nbsp;Button Other App</td>
                        </tr>
                        <tr>
                        	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input <?php echo $disKpiSetting; ?> <?php echo $cekBtnKpiSetting; ?> onClick="ajaxGetGiveAccess(this.checked, 'cekKpiSetting');" type="checkbox" >&nbsp;Button KPI Setting</td>
                        </tr>
                        <tr>
                            <td align="left">&nbsp;<input <?php echo $disGiveAccess; ?> <?php echo $cekMenuApplication; ?> type="checkbox" onClick="ajaxGetGiveAccess(this.checked, 'cekMenuApplication');" >&nbsp;Menu Application</td>
                        </tr>
                        <tr>
                            <td align="left">&nbsp;<input <?php echo $disGiveAccess; ?> <?php echo $cekBtnExportPrint; ?> type="checkbox" onClick="ajaxGetGiveAccess(this.checked, 'cekBtnExportPrint');" >&nbsp;Button Export/Print</td>
                        </tr>
                        </table>
                       
                        </div>
                    </td>
                </tr>
                </table>
            
            </td>
        </tr>
        </table>
        
    </td>
</tr>

<tr><td height="5"></td></tr>

<tr valign="top">
	<td colspan="2" class="tdMyFolder" bgcolor="#FFFFFF" height="65" valign="middle" align="left">
        &nbsp;
        <button onClick="exit();" class="btnStandarKecil" style="width:90px;height:55px;" title="Close User Window">
            <table width="100%" height="100%">
            <tr>
                <td align="center"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="25"/> </td> 
            </tr>
            <tr>
                <td align="center">CLOSE</td>
            </tr>
            </table>
        </button>
        &nbsp;
        <button onClick="pilihBtnSave();" class="btnStandarKecil" style="width:90px;height:55px;" title="Save New User">
            <table width="100%" height="100%">
            <tr>
                <td align="center"><img src="../../picture/Floppy-Disk-blue-32.png" height="25"/> </td> 
            </tr>
            <tr>
                <td align="center">SAVE</td>
            </tr>
            </table>
        </button>
    </td>
</tr>
<div id="idHalCentangSubCustom" style="visibility:hidden;"></div>
</table>
</center>
</body>

<?php
if($halamanPost == "simpanNewUser" || $halamanPost == "simpanEditUser")
{
?>
	<script language="javascript">
		exit();
	</script>	
<?php
}

/*if($statusEmp == "ada")
{
?>
	<script language="javascript">
		document.getElementById('errorMsg').innerHTML = "This Employee is already exists";
	</script>	
<?php
}*/
?>
</HTML>