<!DOCTYPE HTML>
<?php
require_once('../../config.php');

$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../css/otherReport.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../../js/animatedcollapse.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script>
$(document).ready(function() {
    parent.tampilLoad('none');
});
// === start == Animated Collapsible DIV
//animatedcollapse.addDiv('act', 'fade=1,height=auto,overflow-y=scroll')
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
	//$: Access to jQuery
	//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
	//state: "block" or "none", depending on state
}
animatedcollapse.init()
// === end of == Animated Collapsible DIV

function ajaxJam(cek, id, ownerId, aksi, halaman)
{
	var from = $('#from').val();
	var to = $('#to').val();
	$.post( 
		"../halPostFold.php",
		{	halaman: aksi, idActivity: id, ownerId: ownerId, cek: cek, from: from, to: to	},
		function(data){
			$('#'+halaman+'').html(data);	
		}
	);
	setTimeout(function()
	{
		ajaxTotal('tdTotal');
	},100);
}

function ajaxUser(i, id, aksi, halaman)
{
	var userIdLogin = $('#userIdLogin').val();
	var from = $('#from').val();
	var to = $('#to').val();
	$.post( 
		"../halPostFold.php",
		{	halaman: aksi, i: i, userId: id, from: from, to: to, userIdLogin: userIdLogin	},
		function(data){
			$('#'+halaman+'').html(data);	
		}
	);
	
	setTimeout(function()
	{
		ajaxJam('true','', id, 'updateTime', 'data'+i);
	},500);
}

function ajaxTotal(halaman)
{
	var from = $('#from').val();
	var to = $('#to').val();
	$.post( 
		"../halPostFold.php",
		{	halaman: "updateTotal", from: from, to: to	},
		function(data){
			$('#'+halaman+'').html(data);	
		}
	);
}


function ubahBg(id, cek)
{
	if(cek == true)
	{
		setTimeout(function()
		{
			$('#'+id+'').css({backgroundColor: '#DDFFDD'});
		},500);
	}
	if(cek == false)
	{
		setTimeout(function()
		{
			$('#'+id+'').css({backgroundColor: '#FFDDDD'});
		},500);
	}
}

function cekboxChange(status)
{
	var baris = $('#cekboxAktif').val();
	if(status == "aktif")
	{
		document.getElementById("cekbox"+baris).checked = false;
		document.getElementById("cekbox"+baris).disabled = false;
	}
	else if(status == "tidak")
	{
		document.getElementById("cekbox"+baris).checked = true;
		document.getElementById("cekbox"+baris).disabled = true;
	}
}
</script>
<input type="hidden" id="from" value="<?php echo $fromDate;?>"/>
<input type="hidden" id="to" value="<?php echo $toDate;?>"/>
<input type="hidden" id="cekboxAktif"/>
<input type="hidden" id="userIdLogin" value="<?php echo $userIdLogin;?>"/>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td height="5px"></td></tr>
<tr>
	<td align="center">
    <table cellpadding="1" cellspacing="0" border="0" width="99.8%">
    <tr style="background-color:#95C8FF;color:#000;font-family:Arial;font-size:13px;font-weight:bold;" height="28px;">
    	<td colspan="2" align="center" width="5%">
        	No.
        </td>
        <td align="center" width="20%">
        	&nbsp;Name
        </td>
        <td align="center" width="61%">
        	Information
        </td>
        <td align="center" width="10%">
        	Term(hour)
        </td>
        <td align="right" width="4%">
        	Lock&nbsp;
        </td>
    </tr>
    <tr>
    	<td colspan="6" height="1px"></td>
    </tr>
<?php
	$i=1;
	$jamKerja = 0;
	
	$query = $CKoneksi->mysqlQuery("SELECT userfullnm,userid FROM login WHERE deletests = 0 AND active = 'Y' ORDER BY userfullnm;");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$jam = $COtherReport->pheonwj($row['userid'], $fromDate, $toDate);
		$jamKerja = $jamKerja + $jam;
		
		$expandDown = "";
		$onClick = "";
		$jmlAct = $COtherReport->jmlAct($row['userid'], $fromDate, $toDate);
		if($jmlAct>0)
		{
			$expandDown = "<img src=\"../../picture/s_desc.png\"/>";
			$onClick = " onClick=\"animatedcollapse.toggle('act".$i."');\"";
		}
		// CHECKBOX option ================================================
		$display = "style=\"display:block\"";
		$jmlPheonwj = $COtherReport->jmlAct($row['userid'], $fromDate, $toDate);
		$belumLock = $COtherReport->belumLock($row['userid'], $fromDate, $toDate);
		if($jmlPheonwj < 1) // tidak display checkbox jika tidak ada PHE ONWJ
		{
			$display = "style=\"display:none\"";
		}
		
		if($belumLock > 0 && $jmlPheonwj >0) // jika ada PHE ONWJ yg belum lock maka cekbox masih aktif
		{
			$checked1 = "";
			$dis1 = "";
		}
		if($belumLock < 1 && $jmlPheonwj >0) // jika PHE ONWJ sudah lock semua maka cekbox non-aktif
		{
			$checked1 = "checked";
			$dis1 = "disabled";
		}
		
		// warna baris ==============================================
		$class = "class=\"ganjil\"";
		if($i%2 == 0)
		{
			$class = "class=\"genap\"";
		}
?>		
    <tr <?php echo $class;?> height="20px;">
        <td align="center" width="2%" <?php echo $onClick;?>><?php echo $expandDown;?></td>
        <td align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;" width="3%" <?php echo $onClick;?>>
            <?php echo $i;?>
        </td>
        <td colspan="2" align="left" <?php echo $onClick;?>>
            <?php echo $row['userfullnm'];?>
        </td>
        <td id="data<?php echo $i;?>" align="center" <?php echo $onClick;?>>
            <?php echo $COtherReport->pheonwj($row['userid'], $fromDate, $toDate);?></td>
        <td align="center">
            <input type="checkbox" id="cekbox<?php echo $i;?>" <?php echo $checked1." ".$dis1." ".$display;?>/ onClick="ajaxUser('<?php echo $i;?>','<?php echo $row['userid'];?>', 'userTime', 'act<?php echo $i;?>');$('#cekboxAktif').val(<?php echo $i;?>);"/>        
        </td>   
    </tr>
	<tr>
    	<td colspan="6">
        	<div id="act<?php echo $i;?>" style="display:none;width:auto;">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
    <?php
	$j = 1;
	$queryAct = $CKoneksi->mysqlQuery("SELECT * FROM tblactivity 
WHERE ownerid = ".$row['userid']." AND DATE(CONCAT(tahun,'/',bulan,'/', tanggal)) BETWEEN '".$fromDate."' AND '".$toDate."' 
AND project='pheonwj' AND deletests=0 ORDER BY idactivity ASC;");
	while($rowAct = $CKoneksi->mysqlFetch($queryAct))
	{
		$aprv = $rowAct['bosapprove'];
		$lock = $rowAct['lockedit'];
		$checked = "";
		$dis = "";
		$bg = "bgcolor=\"#FFDDDD\"";
		if($lock == "Y")
		{
			$checked = "checked";
		}
		if($aprv == "Y")
		{
			$checked = "checked";
			$dis = "disabled";
		}
		if($checked == "checked")
		{
			$bg = "bgcolor=\"#DDFFDD\"";
		}
		
		// ------- BORDER ------
		if($j == 1)
		{
			$type1 = "class=\"borderAll\"";
			$type2 = "class=\"borderLeftNull\"";
			$type3 = "class=\"borderLeftRightNull\"";
		}
		if($j != 1)
		{
			$type1 = "class=\"borderTopNull\"";
			$type2 = "class=\"borderTopLeftNull\"";
			$type3 = "class=\"borderBottom\"";
		}
	?>
                    <tr>
                    	<td width="5%"></td>
                        <td>
                        <table id="tbl<?php echo $i.$j;?>" cellpadding="0" cellspacing="0" border="0" width="100%" <?php echo $bg;?>>
                        	<tr valign="top">
                            	<td <?php echo $type1;?> align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;font-size:13px;" width="3%">
                                	<?php echo $j;?>
                                </td>
                                <td <?php echo $type3;?> width="1%"></td>
                                <td <?php echo $type2;?> align="left" width="82%"><?php echo $rowAct['relatedinfo'];?>
                                </td>
                                <td <?php echo $type2;?> align="center" width="10%"><?php echo $COtherReport->selisihJam($rowAct['fromtime'], $rowAct['totime']);?></td>
                                <td <?php echo $type2;?> align="center" width="4%">
                                	<input type="checkbox" <?php echo $checked." ".$dis?> onclick="ajaxJam(this.checked,'<?php echo $rowAct['idactivity'];?>', '<?php echo $rowAct['ownerid'];?>', 'updateTime', 'data<?php echo $i;?>');ubahBg('tbl<?php echo $i.$j;?>',this.checked);$('#cekboxAktif').val(<?php echo $i;?>);"/>
                                </td>
                            </tr>
                        </table>
                        </td>
                    </tr>
	<?php $j++;}?>                    
                </table>
            </div>
        </td>
    </tr>
    
<?php $i++;} ?>
	<!--<tr>
    	<td colspan="6">
        	<div id="act" style="display:none;width:auto;">
                <table width="100%">
                    <tr><td>a</td></tr>
                </table>
            </div>
        </td>
    </tr>-->  
    <tr><td colspan="6" height="5px"></td></tr>
    </table>
    
    </td>
</tr>
<tr style="font-family:Arial;font-size:13px;" height="20px;">
    <td id="tdTotal" align="left" style="color:#000080;font-weight:bold;font-family:Tahoma;">
         &nbsp;&nbsp;&nbsp;All Total : 
		 <?php
			echo $jamKerja;
		 ?> hours&nbsp;&nbsp;
    </td>
</tr>
</table>
<?php
$setAllCollapse = "";
$jml = $i-1;
for($ii = 1; $ii <= $jml; $ii++)
{
	$setAllCollapse.= "animatedcollapse.addDiv('act".$ii."', 'fade=1,speed=500,height=auto,overflow-y=scroll');";
}

echo "<script>".$setAllCollapse."</script>";
?>
<button id="ubahAktif" onclick="cekboxChange('aktif');" style="display:none;"/>
<button id="ubahTdkAktif" onclick="cekboxChange('tidak');" style="display:none;"/>
</HTML>