<?php
require_once("../../config.php");
require_once('../configAtk.php');

$yearMonthGet = $_GET['yearMonth'];
$bln =  substr($yearMonthGet,4,2);
$thn =  substr($yearMonthGet,0,4);
$blnSeb = "";
$thnSeb = "";

if($bln == 01)
{
	$blnSeb = 12;
	$thnSeb = $thn -1;
}
if($bln != 01)
{
	$blnSeb = $CPublic->zerofill($bln - 1,2);
	$thnSeb = $thn;
}

if($halamanGet == "copyLastStock") // copy last stock ke bulan baru
{
	$stockBlnSeb = stockBlnSeb($CKoneksiAtk, $blnSeb, $thnSeb);
	
	if($stockBlnSeb > 0)
	{
		$query = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT itemid FROM stock WHERE stockmonth=".$blnSeb." AND stockyear=".$thnSeb." AND active='Y' ORDER BY itemid;");
		while($row = $CKoneksiAtk->mysqlFetch($query))
		{
			$itemId = $row['itemid'];
			
			$CKoneksiAtk->mysqlQuery("INSERT INTO stock (itemid, urutanid, stockdate, stockmonth, stockyear, stockname, stocktype, stockmin, stockprice, stockall, lastmonthstock, addusrdt) SELECT stock.itemid, 1, '00', '".$bln."', ".$thn.", stock.stockname, stock.stocktype, stock.stockmin, stock.stockprice, stock.stockall, stock.stockall, '".$CPublic->userWhoAct()."' FROM stock WHERE itemid = ".$itemId." AND stockmonth=".$blnSeb." AND stockyear=".$thnSeb." ORDER BY stockid DESC LIMIT 1;");
		}
	}
}

function stockBlnSeb($CKoneksiAtk, $bln, $thn)
{
	$query = $CKoneksiAtk->mysqlQuery("SELECT stockid FROM stock WHERE stockmonth = ".$bln." AND stockyear = ".$thn."");
	$jml = $CKoneksiAtk->mysqlNRows($query);
	
	return $jml;
}
?>
<script language="javascript">
/*function stock()
{
	var jmlData = document.getElementById('jmlData').value;
	var i;
	for(i=1;i<=jmlData;i++)
	{
		if(document.getElementById('tdCurrentStock'+i) !== null)
		{
			var stockMin = document.getElementById('stockMin'+i).innerHTML;
			var currentStock = document.getElementById('currentStock'+i).innerHTML;
			if(parseInt(currentStock) > parseInt(stockMin))
			{
				document.getElementById('tdCurrentStock'+i).style.backgroundColor = "#5EFF46";
			}
			if(parseInt(currentStock) == parseInt(stockMin))
			{
				document.getElementById('tdCurrentStock'+i).style.backgroundColor = "#F5FF46";
			}
			if(parseInt(currentStock) < parseInt(stockMin))
			{
				document.getElementById('tdCurrentStock'+i).style.backgroundColor = "#FF464A";
			}
		}
	}
}*/
</script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../css/atkRequest.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css" />
<body>

<table width="100%" cellspacing="0" cellpadding="0">
<?php
$i=1;
	$sql = $CKoneksiAtk->mysqlQuery("SELECT * FROM stock WHERE stockyear=".$thn." AND stockmonth=".$bln." AND active = 'Y' ORDER BY stockname ASC;");
	$jmlRow = $CKoneksiAtk->mysqlNRows($sql);
	while($r = $CKoneksiAtk->mysqlFetch($sql))
	{
		$jmlTotalItem = cariJumlahItem($CKoneksiAtk, $r['itemid'], $thn, $bln);
		$date = $r['stockdate']."/".$r['stockmonth']."/".$r['stockyear'];
		if($r['stockdate'] == "00")
		{
			$date = "&nbsp;";
		}
		$supply = $r['stockqty'];
		if($supply == "")
		{
			$supply = "&nbsp;";
		}
		
		//warna stock
		$stock = $r['stockall'];
		$minStock = $r['stockmin'];
		if($stock < $minStock)
		{
			$bgColor = "background-color:#FF464A;";
		}
		if($stock == $minStock)
		{
			$bgColor = "background-color:#F5FF46;";
		}
		if($stock > $minStock)
		{
			$bgColor = "background-color:#5EFF46;";
		}
?>
            <tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" class="fontMyFolderList" height="20">
                <!--<td class="borderTopNull" width="4%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $i; ?></td>-->
                <?php
				if($r['urutanid'] == "1" && $r['active'] == "Y")
				{
				?>
                	<td class="borderTopNull" width="4%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;" rowspan="<?php echo $jmlTotalItem;?>"><?php echo $i; ?></td>
                    <?php $i++;?>
                    <td width="40%" class="borderTopLeftNull" rowspan="<?php echo $jmlTotalItem;?>">
                        &nbsp;<?php echo $CPublic->potongKarakter($r['stockname'],"65"); ?>
                    </td>
                <?php } ?>
                <td width="7%" align="center" class="borderTopLeftNull"><?php echo $r['stocktype']; ?></td>
                <!--<td width="8%" align="center" class="borderTopLeftNull"><?php echo number_format((float)$r['stockprice'], 0, ',', '.'); ?></td>-->
                <?php
				if($r['urutanid'] == "1")
				{
				?>
                    <td width="8%" align="center" class="borderTopLeftNull" rowspan="<?php echo $jmlTotalItem;?>">
                        <span id="stockMin<?php echo $i; ?>"><?php echo $r['stockmin']; ?></span>
                    </td>
                    <td width="7%" align="center" class="borderTopLeftNull" rowspan="<?php echo $jmlTotalItem;?>">
                        <span id="prevMonth<?php echo $i; ?>"><?php echo $r['lastmonthstock']; ?></span>
                    </td>
                <?php
				}
				?>
                <td width="7%" align="center" class="borderTopLeftNull">
                	<span id="stockQty<?php echo $i; ?>"><?php echo $supply; ?></span>
                </td>
                <td width="9%" align="center" class="borderTopLeftNull" title="Rp. <?php echo number_format((float)$r['stockprice'], 0, ',', '.'); ?>">
					<?php echo $date; ?>
                </td>
                <?php
				if($r['urutanid'] == "1")
				{
				?>
                    <td width="9%" align="center" class="borderTopLeftNull" rowspan="<?php echo $jmlTotalItem;?>">
                        <span id="itemOut<?php echo $i; ?>"><?php echo $r['stockout']; ?></span>
                    </td>
                    <td width="9%" id="tdCurrentStock<?php echo $i; ?>" rowspan="<?php echo $jmlTotalItem;?>" align="center" class="borderTopLeftNull" style="font-weight:bold;<?php echo $bgColor;?>">
                        <span id="currentStock<?php echo $i; ?>"><?php echo $r['stockall']; ?></span>
                    </td>
                <?php
				}
				?>
            
        </td>
    </tr>
<?php
}
 
 function cariJumlahItem($CKoneksiAtk, $itemId, $thn, $bln)
 {
	 $nilai = "";
	 $query = $CKoneksiAtk->mysqlQuery("SELECT * FROM stock WHERE stockyear=".$thn." AND stockmonth=".$bln." AND itemid=".$itemId." AND active='Y'");
	 $jmlRow = $CKoneksiAtk->mysqlNRows($query);
	 
	 return $jmlRow;
 }
 ?>    
<input type="hidden" id="jmlData" value="<?php echo $jmlRow; ?>"/>    
    <!--<tr>
        <td onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
            <table width="100%" cellspacing="0" cellpadding="0">
            <tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" class="fontMyFolderList" height="20">
                <td class="borderTopNull" width="4%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;">2</td>
                <td width="39%" class="borderTopLeftNull">
                	&nbsp;Kertas A3
                </td>
                <td width="6%" align="center" class="borderTopLeftNull">Rim</td>
                <td width="8%" align="center" class="borderTopLeftNull">35000</td>
                <td width="7%" align="center" class="borderTopLeftNull">100</td>
                <td width="6%" align="center" class="borderTopLeftNull">50</td>
                <td width="6%" align="center" class="borderTopLeftNull">
                	100
                </td>
                <td width="8%" align="center" class="borderTopLeftNull">
                	2014/12/04
                </td>
                <td width="7%" align="center" class="borderTopLeftNull">50</td>
                <td width="9%" align="center" class="borderTopLeftNull" style="background-color=#F5FF46;">100</td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
            <table width="100%" cellspacing="0" cellpadding="0">
            <tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" class="fontMyFolderList" height="20">
                <td class="borderTopNull" width="4%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;">3</td>
                <td width="39%" class="borderTopLeftNull">
                	&nbsp;Spidol
                </td>
                <td width="6%" align="center" class="borderTopLeftNull">Pcs</td>
                <td width="8%" align="center" class="borderTopLeftNull">8000</td>
                <td width="7%" align="center" class="borderTopLeftNull">100</td>
                <td width="6%" align="center" class="borderTopLeftNull">50</td>
                <td width="6%" align="center" class="borderTopLeftNull">100</td>
                <td width="8%" align="center" class="borderTopLeftNull">
                	2014/12/04
                </td>
                <td width="7%" align="center" class="borderTopLeftNull">100</td>
                <td width="9%" align="center" class="borderTopLeftNull" style="background-color=#FF464A;">50</td>
            </tr>
            </table>
        </td>
    </tr>-->
</table>
</body>
<script language="javascript">
<?php
if($halamanGet == "copyLastStock")
{
	echo "parent.loadUrl('../index.php?aksi=stockReport');";
}
?>
</script>	