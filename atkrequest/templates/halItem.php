<!DOCTYPE HTML>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../css/atkRequest.css" rel="stylesheet" type="text/css" />
<link href="../css/paging.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<?php
require_once('../../config.php');
require_once('../configAtk.php');

$page = $_GET['page'];

$tglServer = $CPublic->tglServer();
$bln =  substr($tglServer,4,2);
$thn =  substr($tglServer,0,4);

//$paging = $_GET['paging'];
$paramTextGet = $_GET['paramText'];
$sqlCari = "";
if($paramTextGet != "")
{
	$sqlCari = "AND itemname LIKE '%".$paramTextGet."%'";
}

if($aksiPost == "addCart")
{
	$itemIdPost = $_POST['itemId'];
	$itemName = $CReqAtk->detilAtkItem("itemname", $itemIdPost);
	//echo $itemIdPost." add Cart ".$itemName." | ".$userIdSession;
	
	$qty = $CReqAtk->cekItem("cartqty", $userIdLogin, $itemIdPost);
	if($qty != "")
	{
		$qtyUpd = $qty +1;
		$query = $CKoneksiAtk->mysqlQuery("UPDATE cart SET cartqty=".$qtyUpd." WHERE ownerid=".$userIdLogin." AND itemid=".$itemIdPost.";");
	}
	if($qty == "")
	{
		$query = $CKoneksiAtk->mysqlQuery("INSERT INTO cart (ownerid,itemid,itemname) VALUES (".$userIdLogin.",".$itemIdPost.",'".$itemName."')");
	}
	
}
?>

<script>
function addCart(id)
{
	addToCart.itemId.value = id;
	addToCart.submit();
}
</script>

<body onLoad="loadScroll('folderList');" onUnload="saveScroll('folderList');">
<form id="addToCart" name="addToCart" action="" method="post" enctype="multipart/form-data">
<table width="100%"cellspacing="0" cellpadding="0" >
<tr>
	<td>
    <input type="hidden" name="itemId" id="itemId"/>
    <?php
	// How many adjacent pages should be shown on each side?
	$adjacents = 1;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	$query = $CKoneksi->mysqlQuery("SELECT COUNT(*) as num FROM item WHERE itemdisplay='on' ".$sqlCari."");
	$total_pages = $CKoneksiAtk->mysqlFetch($query);
	$total_pages = $total_pages['num'];
	
	/* Setup vars for query. */
	if($page == "all")
	{
		$pagingOn = "";
		$pagination = "<span style=\"font-family:Tahoma;font-size:11px;\"><strong>All Item : ".$total_pages." </strong></span>";
	}
	if($page != "all")
	{
		$limit = 8; 								//how many items to show per page
		if($page) 
			$start = ($page - 1) * $limit; 			//first item to display on this page
		else
			$start = 0;								//if no page var is given, set start to 0
		
		$pagingOn = "LIMIT ".$start.", ".$limit;
		$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	}
	
	/* Get data. */
	$query1 = $CKoneksi->mysqlQuery("SELECT * FROM item WHERE itemdisplay='on' ".$sqlCari." ORDER BY itemname ASC ".$pagingOn."");
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	
	if($page != "all")
	{
		if($lastpage > 1)
		{	
			$pagination .= "<div class=\"pagination\">";
			$pagination .= "<span style=\"font-size:11px;\"><strong>All Item : ".$total_pages." </strong></span>";
			//previous button
			if ($page > 1) 
				$pagination.= "<a href=\"halItem.php?page=".$prev."&paramText=".$paramTextGet."\">&laquo; prev</a>";
			else
				$pagination.= "<span class=\"disabled\">&laquo; prev</span>";	
			
			//pages	
			if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
			{	
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">".$counter."</span>";
					else
						$pagination.= "<a href=\"halItem.php?page=".$counter."&paramText=".$paramTextGet."\">".$counter."</a>";					
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">".$counter."</span>";
						else
							$pagination.= "<a href=\"halItem.php?page=".$counter."&paramText=".$paramTextGet."\">$counter</a>";					
					}
					$pagination.= "...";
					$pagination.= "<a href=\"halItem.php?page=".$lpm1."&paramText=".$paramTextGet."\">".$lpm1."</a>";
					$pagination.= "<a href=\"halItem.php?page=".$lastpage."&paramText=".$paramTextGet."\">".$lastpage."</a>";		
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<a href=\"halItem.php?page=1&paramText=".$paramTextGet."\">1</a>";
					$pagination.= "<a href=\"halItem.php?page=2&paramText=".$paramTextGet."\">2</a>";
					$pagination.= "...";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">".$counter."</span>";
						else
							$pagination.= "<a href=\"halItem.php?page=".$counter."&paramText=".$paramTextGet."\">$counter</a>";					
					}
					$pagination.= "...";
					$pagination.= "<a href=\"halItem.php?page=".$lpm1."&paramText=".$paramTextGet."\">".$lpm1."</a>";
					$pagination.= "<a href=\"halItem.php?page=".$lastpage."&paramText=".$paramTextGet."\">".$lastpage."</a>";		
				}
				//close to end; only hide early pages
				else
				{
					$pagination.= "<a href=\"halItem.php?page=1\">1</a>";
					$pagination.= "<a href=\"halItem.php?page=2\">2</a>";
					$pagination.= "...";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">".$counter."</span>";
						else
							$pagination.= "<a href=\"halItem.php?page=".$counter."&paramText=".$paramTextGet."\">".$counter."</a>";					
					}
				}
			}
			
			//next button
			if ($page < $counter - 1) 
				$pagination.= "<a href=\"halItem.php?page=".$next."&paramText=".$paramTextGet."\">next &raquo;</a>";
			else
				$pagination.= "<span class=\"disabled\">next &raquo;</span>";
			$pagination.= "</div>\n";		
		}
	}
	$i = 1;
	while($row = $CKoneksiAtk->mysqlFetch($query1))
	{
		$itemId = $row['itemid'];
		//$stock = $row['stockall'];
		$stock = $CReqAtk->stockSaatIni($itemId);
		if($stock == "")
		{
			$stock = "0";
		}
	
		/*$i = 1;
		$batas = 8; //limit data yg ditampilkan per page
		
		if(empty($paging))
		{
			$posisi = 0;
			$paging = 1;
			$pagingOn = "LIMIT ".$posisi.", ".$batas."";
		}
		else if($paging == "all")
		{
			$pagingOn = "";
		}
		else
		{
			$posisi = ($paging-1)*$batas;
			$pagingOn = "LIMIT ".$posisi.", ".$batas."";
		}
		$query = $CKoneksiAtk->mysqlQuery("SELECT * FROM item WHERE itemdisplay='on' ".$sqlCari." ORDER BY itemname ASC ".$pagingOn."");
		while($row= $CKoneksiAtk->mysqlFetch($query))
		{
			$itemId = $row['itemid'];
			$sql = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT stockall FROM stock WHERE itemid=".$itemId." AND stockyear=".$thn." AND stockmonth=".$bln."");
			$r= $CKoneksiAtk->mysqlFetch($sql);
			$stock = $r['stockall'];
			if($stock == "")
			{
				$stock = "0";
			}*/
			/*$dis = "";
			if($stock == "0")
			{
				$dis = "disabled";
			}*/
	?>
    	<div class="boxItem" onMouseOver="this.className='boxItemHover';document.getElementById('boxButton<?php echo $i;?>').style.display='block';" onMouseOut="this.className='boxItem';document.getElementById('boxButton<?php echo $i;?>').style.display='none';">
        	<table width="100%"cellspacing="0" cellpadding="0">
           	<tr>
            	<td colspan="3" class="borderImg" align="center">
                	<img src="../picture/<?php echo $row['itemimg'];?>"/ width="150" height="130">
                </td>
            </tr>
            <tr>
            	<td colspan="3" height="3"></td>
            </tr>
            <tr>
            	<td width="5px">&nbsp;</td>
            	<td width="121px" class="fontMyFolderList">
                	<?php echo $CPublic->potongKarakter($row['itemname'],"38");?>
                </td>
                <td width="28px" height="28px" class="qtyTd" title="<?php echo $stock." ".$row['qtytype'];?>"><?php echo $stock;?></td>
            </tr>
            </table>
            <div id="boxButton<?php echo $i;?>" class="boxButton" style="display:none">
            <table width="100%%"cellspacing="0" cellpadding="0">
            <tr><td height="40"></td></tr>
           	<tr>
            	<td align="center">
                	<button type="button" style="width:130px;height:35px;" class="btnAtk" title="View Item Image" onClick="parent.imgThickbox('<?php echo $itemId; ?>', '<?php echo $stock." ".$row['qtytype']; ?>');">
                        <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                            <tr valign="middle">
                                <td align="center" height="30"><img src="../../picture/Zoom-In-blue-32.png"/ height="20px"></td>
                                <td align="center">View Image</td>
                            </tr>
                        </table>
                    </button>
                </td>
            </tr>
            <tr><td height="5"></td></tr>
            <tr>
            	<td align="center">
                <button type="submit" style="width:130px;height:35px;" class="btnAtk" onClick="addCart('<?php echo $itemId;?>'); return false;" title="Submit Item to Cart">
                    <table cellpadding="0" cellspacing="0" width="100%" height="100%" class="fontBtnAtk">
                        <tr <?php echo $valign;?>>
                            <td align="center"><img src="../../picture/Shopping-Basket-blue-32.png"/ height="20px" style="vertical-align:middle;"></td>
                            <td align="center">Order Item</td>
                        </tr>
                    </table>
                </button>
                <input type="hidden" name="aksi" value="addCart"/>
                </td>
            </tr>
            </table>
            
            </div>
        </div>
	<?php $i++;}?>
    
<div style="clear:both;" align="center">
    <?php 
	echo $pagination;
		/*$tampil2 = $CKoneksiAtk->mysqlQuery("SELECT * FROM item WHERE itemdisplay='on' ".$sqlCari." ORDER BY itemname ASC");
		$jmldata = $CKoneksiAtk->mysqlNRows($tampil2);
		$jmlhal = ceil($jmldata/$batas);
		
		if($paging == "all")
		{
		}
		else
		{
			echo "<div class=posPaging><div class=paging>
				  <span style=\"font-size:11px;\"><strong>All Item : ".$jmldata." </strong></span>";
			
			if($paging > 1){
				// jika bukan di halaman pertama, maka tampilkan tombol previous untuk ke halaman sebelumnya
				$prev = $paging-1;
				echo "<span class=prevnext><a href=$_SERVER[PHP_SELF]?halaman=$prev>&laquo; Prev</a></span>";
			}	
			else
			{
				// jika di halaman pertama, maka tombol previous di-disable
				echo "<span class=disabled>&laquo; Prev</span>";
			}
			
			for($i=1;$i<=$jmlhal;$i++){
				if($i!=$paging){
					// menampilkan berapa halaman
					echo "<a href=$_SERVER[PHP_SELF]?paging=$i>$i</a>";
				}
				else{
					echo "<span class=current>$i</span>";
				}
			}
			
			if($paging<$jmlhal){
				// jika bukan di halaman terakhir, maka tampilkan tombol next untuk ke halaman berikutnya
				$next = $paging + 1;
				echo "<span class=prevnext><a href=$_SERVER[PHP_SELF]?paging=$next>Next &raquo;</a></span>";
			}
			else{
				// jika di halaman terakhir, tombol next di-disable
				echo "<span class=disabled>Next &raquo;</span>";
			}
			echo "</div></div>";
		}*/
		//echo "<div style=\"font-size:12px;\"><strong>All Item : ".$jmldata."</strong></div>"; 
	?>
</div>
    </td>
</tr>
</table>
</form>
</body>
<script language="javascript">
<?php
if($aksiPost == "addCart")
{
?>
	setTimeout(function()
	{
		parent.refreshPage();
	},500)
<?php
}
?>
</script>
</HTML>