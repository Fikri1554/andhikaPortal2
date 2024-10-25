<!DOCTYPE HTML>
<?php
require_once('../../config.php');
require_once('../configSpj.php');
// $htmlTbl = "";
$idForm = $_GET['formId'];
if($aksiPost == "add")
{
	$idUsr = $_POST['slcFollow'];
	$idFormNya = $_POST['idForm'];
	$CKoneksiSpj->mysqlQuery("INSERT INTO follower (formid, followerid) VALUES ('".$idFormNya."', '".$idUsr."');");
}
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../css/loading.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>

<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script src="../../js/JavaScriptUtil.js"></script>
<script src="../../js/Parsers.js"></script>
<script src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<SCRIPT type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../js/loading.js"></script>

<script>
$(document).ready(function() {
	doneWait();
	getDataNya('<?php echo $idForm; ?>');
});

function submitCopy()
{	
	var idUsr = $('#slcFollow').val();
	var chkFollower = "";
	if(idUsr == "0")
	{
		alert("Select Follower Name");
		return false;
	}else{
		$.post('../../spj/halPost.php',
		{ halaman : 'checkFollowerNya',idForm : '<?php echo $idForm;?>', idUsr : idUsr},
			function(data) 
			{	
				if(data == 'kosong')
				{
					var answer  = confirm("Are you sure want to save?");
					if(answer)
					{
						pleaseWait();
						formCopy.submit();
					}
					else
					{	return false;	}
				}else{
					alert("Follower Ready..!!");
					return false;
				}
			},
		"json"
		);
	}
	
}

function getDataNya(idForm)
{
	$('#idBodyNya').empty();
	$.post('../../spj/halPost.php',
	{ halaman : "followByTable",id : idForm},
		function(data) 
		{	
			var html = data;
			$('#idBodyNya').append(html);
		},
	"json"
	);
}

function checkFollowerNya(idForm,idUsr)
{	
	var stCheck = "";
	$('#idBodyNya').empty();
	$.post('../../spj/halPost.php',
	{ halaman : "checkFollowerNya",idForm : idForm, idUsr : idUsr},
		function(data) 
		{	
			alert("==>"+data);
			stCheck = data;
		},
	"json"
	);
	return stCheck;
}

function removeFollowNya(idForm,folid)
{
	var answer  = confirm("Are you sure want to REMOVE FOLLOWER..??");
	if(answer)
	{
		$.post('../../spj/halPost.php',
		{ halaman : "removeFollowerNya",folid : folid},
			function(data) 
			{
				if(data == "sukses")
				{
					getDataNya(idForm);
				}
			},
		"json"
		);
	}
	else
	{	return false;	}
}

</script>
<body bgcolor="#F8F8F8">
<div id="loaderImg" style="visibility:visible;width:500px;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">
    	&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;
    </div>
</div>
<center>
<form action="" name="formCopy" id="formCopy" method="post" enctype="multipart/form-data">
<table cellpadding="0" cellspacing="0" border="0" width="99%" height="99%" align="center">
<tr valign="top" style="width:100%;">
	<td align="left">
    	<!--<span class="teksLvlFolder" style="color:#666;font-size:14px;"><b></b></span>-->
    </td>
	<td align="right">
    	<span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;" title="Tembusan SPJ"><b>:: Add Follow ::</b></span>
    </td>
</tr>
<tr valign="top">
	<td colspan="2" class="spjTdMyFolder" bgcolor="#FFFFFF" valign="top" align="center">
    <div style="width:99%;overflow:scroll;overflow-x: hidden;top: expression(offsetParent.scrollTop);">  
		<table cellpadding="0" cellspacing="5" width="98%" class="spjFormInput" border="0">
			<tr><td height="5"></td></tr>
			<tr valign="top">
				<td width="20%" height="28px" align="left" title="Rekan yang mendampingi dinas">Follower Name</td>
				<td width="90%" align="left">
					<select id="slcFollow" name="slcFollow" class="elementMenu" style="width:102%;">
						<option value="0">- Select -</option>
						<?php echo $CSpj->menuUser($db); ?>
					</select>
				</td>     
			</tr>        
		</table>
    </div>
    </td>
</tr>
<tr valign="top">
	<td colspan = "2">
		<div style="width:99%;height:150px;overflow:scroll;overflow-x: hidden;top: expression(offsetParent.scrollTop);">  
			<table cellpadding="0" cellspacing="5" width="98%" class="spjFormInput" border="0">
				<thead>
				  <tr>
					<td style="width:8%;" align="center">No</td>
					<td align="center">Follower</td>
					<td style="width:15%;" align="center">Action</td>
				  </tr>
				</thead>
				<tbody id="idBodyNya">
					
				</tbody>
			</table>
		</div>
	</td>
</tr>
<tr valign="top">
	<td colspan="2" bgcolor="#FFFFFF" height="35" valign="middle" align="center">
		<input type="hidden" id="aksi" name="aksi" value="add"/>
		<input type="hidden" id="idForm" name="idForm" value="<?php echo $idForm; ?>"/>
       &nbsp;<button type="button" class="spjBtnStandar" id="btnNewFolder" onclick="parent.close();" style="width:65px;height:25px;" title="Close Window">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                    <td align="left"><img src="../picture/control-power.png" height="15"/> </td>
                    <td align="right">CLOSE</td>
                </tr>
                </table>
            </button>
       &nbsp;<button type="submit" class="spjBtnStandar" id="btnSaveFollow" onclick="submitCopy(); return false;" style="width:60px;height:25px;" title="Save Follow">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                    <td align="left"><img src="../picture/disk.png" height="15"/> </td>
                    <td align="right">SAVE</td>
                </tr>
                </table>
            </button>
       &nbsp;
    </td>
</tr>
<tr valign="top">
	<td colspan="2" valign="middle" align="center">
    <span id="errorMsg" class="errorMsg"></span>
    </td>
</tr>
</table>
</form>
</center>
</body>
<script language="javascript">
<?php
if($aksiPost == "add")
{
	echo "parent.exit();
		  parent.reportTembusan();
		  parent.klikTr(parent.$('#trActive').val());";
}
?>
</script>
</HTML>	