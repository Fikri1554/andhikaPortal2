<?php
class CLaporanAging
{	
	function CLaporanAging($koneksiMysql, $CAging, $CPublic, $CInvReg)
	{
		$this->koneksi = $koneksiMysql;
		$this->CAging = $CAging;
		$this->CPublic = $CPublic;
		$this->CInvReg = $CInvReg;
	}

	function printAging($get)
	{
		$userId = $get['userId'];
		$account = $get['account'];

		$frmDateConv = $this->CPublic->convTglDB($_GET['fromDate']);
    	$endDateConv = $this->CPublic->convTglDB($_GET['endDate']);
    	$company = $_GET['company'];

		$currOther1 = "";
		$totalOther1Current = 0;
		$totalOther1RangeSatu = 0;
		$totalOther1RangeDua = 0;
		$totalOther1RangeTiga = 0;
		$totalOther1RangeEmpat = 0;
		$totalOther1RangeLima = 0;
		$currOther2 = "";
		$totalOther2Current = 0;
		$totalOther2RangeSatu = 0;
		$totalOther2RangeDua = 0;
		$totalOther2RangeTiga = 0;
		$totalOther2RangeEmpat = 0;
		$totalOther2RangeLima = 0;

		$totalCurrentIdr = 0;
	    $totalRange1Idr = 0;
	    $totalRange2Idr = 0;
	    $totalRange3Idr = 0;
	    $totalRange4Idr = 0;
	    $totalRange5Idr = 0;
	    $totalCurrentUsd = 0;
	    $totalRange1Usd = 0;
	    $totalRange2Usd = 0;
	    $totalRange3Usd = 0;
	    $totalRange4Usd = 0;
	    $totalRange5Usd = 0;
		
		$limitHeight = 500;

		$tempDataUnion = array();
		$noTemp = 1;

		$sql = "SELECT * FROM
				(
					SELECT kreditacc,vendor,currency,urutan,urutangrup,idmailinv,company,datedisp,mailinvno,barcode,receivedate,tglinvoice,dueday,tglexp,subaccount,amount,rangesatu,rangedua,rangetiga,rangeempat,rangelima FROM summaryaging 
					WHERE userid = '".$userId."'
					UNION ALL
					SELECT kreditacc,vendor,currency,urutan,urutangrup,idmailinv,company,datedisp,mailinvno,barcode,receivedate,tglinvoice,dueday,tglexp,subaccount,amount,rangesatu,rangedua,rangetiga,rangeempat,rangelima FROM tempsummaryaging
					WHERE paid = 'N' AND company = '".$company."' AND receivedate BETWEEN '".$frmDateConv."' AND '".$endDateConv."'
				) A ORDER BY kreditacc,subaccount,urutangrup ASC ";
		$query = $this->koneksi->mysqlQuery($sql);

		while($row = $this->koneksi->mysqlFetch($query))
		{
			$txtSubAcct = "";

			if($row['subaccount'] != "")
			{
				$ds = $this->CInvReg->getSubAccount($row['company'],$row['kreditacc'],$row['subaccount']);

				if($ds != "")
				{
					$txtSubAcct = $row['subaccount']." - ".$ds;
				}
			}

			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['urutangrup'] = $row['urutangrup'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['idmailinv'] = $row['idmailinv'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['company'] = $row['company'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['datedisp'] = $row['datedisp'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['kreditacc'] = $row['kreditacc'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['vendor'] = $row['vendor'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['currency'] = $row['currency'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['mailinvno'] = $row['mailinvno'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['barcode'] = $row['barcode'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['receivedate'] = $row['receivedate'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['tglinvoice'] = $row['tglinvoice'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['dueday'] = $row['dueday'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['tglexp'] = $row['tglexp'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['subaccount'] = $row['subaccount'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['amount'] = $row['amount'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangesatu'] = $row['rangesatu'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangedua'] = $row['rangedua'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangetiga'] = $row['rangetiga'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangeempat'] = $row['rangeempat'];
			$tempDataUnion[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangelima'] = $row['rangelima'];
			$noTemp++;
		}

		$jmlAllHeight = $this->jmlAllHeightWithUnion($tempDataUnion);
		$maxPage = ceil($jmlAllHeight/$limitHeight);
		$html = "";
		
		for($a=1;$a<=$maxPage;$a++)
		{
			$sql1 = "";
			$pagebreak = "";
			if($a == 1)
			{
				$persenHeight = 0;
				$batasAwal = 0;
				$batasAkhir = ($a * $limitHeight);
				
				$offset = "LIMIT 0, ".$this->cariMaksRowPerHalamanWithUnion($userId, $a, $batasAwal, $batasAkhir, "end",$tempDataUnion);
			}
			if($a > 1)
			{
				$persenHeight = ($a-1) * 100;
				$pagebreak = "<tr style=\"page-break-after: always;\"><td></td></tr>";
				
				$batasAwal = (($a-1) * $limitHeight);
				$batasAkhir = ($a * $limitHeight);
				
				$offset =  "LIMIT ".$this->cariMaksRowPerHalamanWithUnion($userId, $a, $batasAwal, $batasAkhir, "start",$tempDataUnion).", ".$this->cariMaksRowPerHalamanWithUnion($userId, $a, $batasAwal, $batasAkhir, "end",$tempDataUnion);
			}
			$html.= $pagebreak;
			$html.= $this->headerAging($get);

			$html.= '
			<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%" style="font:1em Arial Narrow;font-weight:normal;">';
			
			if($offset != "LIMIT , ")
			{
				$noTemp = 1;
				$tempData = array();

				$sql1 = $sql." ".$offset;
			    $query = $this->koneksi->mysqlQuery($sql1);
			    while($row = $this->koneksi->mysqlFetch($query))
			    {
			        $txtSubAcct = "";

			        if($row['subaccount'] != "")
			        {
			            $ds = $this->CInvReg->getSubAccount($row['company'],$row['kreditacc'],$row['subaccount']);

			            if($ds != "")
			            {
			                $txtSubAcct = $row['subaccount']." - ".$ds;
			            }
			        }

			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['urutangrup'] = $row['urutangrup'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['idmailinv'] = $row['idmailinv'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['company'] = $row['company'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['datedisp'] = $row['datedisp'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['kreditacc'] = $row['kreditacc'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['vendor'] = $row['vendor'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['currency'] = $row['currency'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['mailinvno'] = $row['mailinvno'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['barcode'] = $row['barcode'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['receivedate'] = $row['receivedate'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['tglinvoice'] = $row['tglinvoice'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['dueday'] = $row['dueday'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['tglexp'] = $row['tglexp'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['subaccount'] = $row['subaccount'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['amount'] = $row['amount'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangesatu'] = $row['rangesatu'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangedua'] = $row['rangedua'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangetiga'] = $row['rangetiga'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangeempat'] = $row['rangeempat'];
			        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangelima'] = $row['rangelima'];
			        $noTemp++;
			    }
			    
			    foreach ($tempData as $key => $val)
			    {
			        $html .= "<tr>";
			            $html .= "<td class=\"tabelBorderBottomJust\" colspan=\"15\" height=\"25\" style=\"font:1em Arial Narrow;font-weight:bold;\">";
			                $html .= $key;
			            $html .= "</td>";
			        $html .= "</tr>";

			        foreach ($val as $keys => $vals)
			        {
			            $no=1;
			            $subTotalCurrent = 0;
			            $subTotalRangeSatu = 0;
			            $subTotalRangeDua = 0;
			            $subTotalRangeTiga = 0;
			            $subTotalRangeEmpat = 0;
			            $subTotalRangeLima = 0;

			            if($keys == "")
			            {
			                $keys = "-";
			            }
			            $html .= "<tr>";
			                $html .= "<td class=\"tabelBorderBottomJust\" colspan=\"15\" height=\"22\" style=\"padding-left:30px;font:0.8em Arial Narrow;font-weight:bold;\">";
			                    $html .= $keys;
			                $html .= "</td>";
			            $html .= "</tr>";
			            
			            foreach ($vals as $keyd => $value)
			            {
			            	$currentNya = 0;
			                $rangeSatu = 0;
			                $rangeDua = 0;
			                $rangeTiga = 0;
			                $rangeEmpat = 0;
			                $rangeLima = 0;
			                $currentNyaView = "";
			                $rangeSatuView = "";
			                $rangeDuaView = "";
			                $rangeTigaView = "";
			                $rangeEmpatView = "";
			                $rangeLimaView = "";

			                $current = $value['currency'];
			                if($account == "pay")
							{
								$widthInv = "13%";
								$barcode = '<td width="6%" align="center">'.$value['barcode'].'</td>';
								$colspanCompany = '13';
								$colspan = '7';
							}
							if($account == "rec")
							{
								$widthInv = "20%";
								$barcode = '';
								$colspanCompany = '11';
								$colspan = '5';
							}

							$interval = $this->CPublic->perbedaanHari(str_replace("-","",$endDateConv),str_replace("-","",$value['tglexp']));

							if($interval < 1)
			                {
			                    $currentNya = $value['amount'];
			                    $currentNyaView = number_format($currentNya, 2, ",", ".");

			                    if(strtoupper($value['currency']) == "IDR")
			                    {
			                        $totalCurrentIdr = $totalCurrentIdr + $currentNya;
			                    }
			                    if(strtoupper($value['currency']) == "USD")
			                    {
			                        $totalCurrentUsd = $totalCurrentUsd + $currentNya;
			                    }

			                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
			                    {
			                        if($currOther1 == "")
			                        {
			                            $currOther1 = $value['currency'];
			                            $totalOther1Current = $totalOther1Current + $currentNya;
			                        }else{
			                            if($currOther1 == $value['currency'])
			                            {
			                                $totalOther1Current = $totalOther1Current + $currentNya;
			                            }else{
			                                if($currOther2 == "")
			                                {
			                                    $currOther2 = $value['currency'];
			                                }
			                                $totalOther2Current = $totalOther2Current + $currentNya;
			                            }
			                        }
			                    }
			                }
			                if($interval >= 1 && $interval <= 30)
			                {
			                    $rangeSatu = $value['amount'];
			                    $rangeSatuView = number_format($rangeSatu, 2, ",", ".");

			                    if(strtoupper($value['currency']) == "IDR")
			                    {
			                        $totalRange1Idr = $totalRange1Idr + $rangeSatu;
			                    }
			                    if(strtoupper($value['currency']) == "USD")
			                    {
			                        $totalRange1Usd = $totalRange1Usd + $rangeSatu;
			                    }

			                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
			                    {
			                        if($currOther1 == "")
			                        {
			                            $currOther1 = $value['currency'];
			                            $totalOther1RangeSatu = $totalOther1RangeSatu + $rangeSatu;
			                        }else{
			                            if($currOther1 == $value['currency'])
			                            {
			                                $totalOther1RangeSatu = $totalOther1RangeSatu + $rangeSatu;
			                            }else{
			                                if($currOther2 == "")
			                                {
			                                    $currOther2 = $value['currency'];
			                                }
			                                $totalOther2RangeSatu = $totalOther2RangeSatu + $rangeSatu;
			                            }
			                        }
			                    }
			                }
			                if($interval >= 31 && $interval <= 60)
			                {
			                    $rangeDua = $value['amount'];
			                    $rangeDuaView = number_format($rangeDua, 2, ",", ".");

			                    if(strtoupper($value['currency']) == "IDR")
			                    {
			                        $totalRange2Idr = $totalRange2Idr + $rangeDua;
			                    }
			                    if(strtoupper($value['currency']) == "USD")
			                    {
			                        $totalRange2Usd = $totalRange2Usd + $rangeDua;
			                    }

			                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
			                    {
			                        if($currOther1 == "")
			                        {
			                            $currOther1 = $value['currency'];
			                            $totalOther1RangeDua = $totalOther1RangeDua + $rangeDua;
			                        }else{
			                            if($currOther1 == $value['currency'])
			                            {
			                                $totalOther1RangeDua = $totalOther1RangeDua + $rangeDua;
			                            }else{
			                                if($currOther2 == "")
			                                {
			                                    $currOther2 = $value['currency'];
			                                }
			                                $totalOther2RangeDua = $totalOther2RangeDua + $rangeDua;
			                            }
			                        }
			                    }
			                }
			                if($interval >= 61 && $interval <= 90)
			                {
			                    $rangeTiga = $value['amount'];
			                    $rangeTigaView = number_format($rangeTiga, 2, ",", ".");

			                    if(strtoupper($value['currency']) == "IDR")
			                    {
			                        $totalRange3Idr = $totalRange3Idr + $rangeTiga;
			                    }
			                    if(strtoupper($value['currency']) == "USD")
			                    {
			                        $totalRange3Usd = $totalRange3Usd + $rangeTiga;
			                    }

			                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
			                    {
			                        if($currOther1 == "")
			                        {
			                            $currOther1 = $value['currency'];
			                            $totalOther1RangeTiga = $totalOther1RangeTiga + $rangeTiga;
			                        }else{
			                            if($currOther1 == $value['currency'])
			                            {
			                                $totalOther1RangeTiga = $totalOther1RangeTiga + $rangeTiga;
			                            }else{
			                                if($currOther2 == "")
			                                {
			                                    $currOther2 = $value['currency'];
			                                }
			                                $totalOther2RangeTiga = $totalOther2RangeTiga + $rangeTiga;
			                            }
			                        }
			                    }
			                }
			                 if($interval >= 91 && $interval <= 360)
			                {
			                    $rangeEmpat = $value['amount'];
			                    $rangeEmpatView = number_format($rangeEmpat, 2, ",", ".");

			                    if(strtoupper($value['currency']) == "IDR")
			                    {
			                        $totalRange4Idr = $totalRange4Idr + $rangeEmpat;
			                    }
			                    if(strtoupper($value['currency']) == "USD")
			                    {
			                        $totalRange4Usd = $totalRange4Usd + $rangeEmpat;
			                    }

			                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
			                    {
			                        if($currOther1 == "")
			                        {
			                            $currOther1 = $value['currency'];
			                            $totalOther1RangeEmpat = $totalOther1RangeEmpat + $rangeEmpat;
			                        }else{
			                            if($currOther1 == $value['currency'])
			                            {
			                                $totalOther1RangeEmpat = $totalOther1RangeEmpat + $rangeEmpat;
			                            }else{
			                                if($currOther2 == "")
			                                {
			                                    $currOther2 = $value['currency'];
			                                }
			                                $totalOther2RangeEmpat = $totalOther2RangeEmpat + $rangeEmpat;
			                            }
			                        }
			                    }
			                }
			                if($interval >= 361)
			                {
			                    $rangeLima = $value['amount'];
			                    $rangeLimaView = number_format($rangeLima, 2, ",", ".");

			                    if(strtoupper($value['currency']) == "IDR")
			                    {
			                        $totalRange5Idr = $totalRange5Idr + $rangeLima;
			                    }
			                    if(strtoupper($value['currency']) == "USD")
			                    {
			                        $totalRange5Usd = $totalRange5Usd + $rangeLima;
			                    }

			                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
			                    {
			                        if($currOther1 == "")
			                        {
			                            $currOther1 = $value['currency'];
			                            $totalOther1RangeLima = $totalOther1RangeLima + $rangeLima;
			                        }else{
			                            if($currOther1 == $value['currency'])
			                            {
			                                $totalOther1RangeLima = $totalOther1RangeLima + $rangeLima;
			                            }else{
			                                if($currOther2 == "")
			                                {
			                                    $currOther2 = $value['currency'];
			                                }
			                                $totalOther2RangeLima = $totalOther2RangeLima + $rangeLima;
			                            }
			                        }
			                    }
			                }

			                $rowTotal = $currentNya + $rangeSatu + $rangeDua + $rangeTiga + $rangeEmpat + $rangeLima;
			                
			                $html .= "<tr>";
			          			$html .= "<td height=\"12\" width=\"2%\" align=\"center\">".$no."</td>";
			                    $html .= "<td width=\"".$widthInv."\">".$value['mailinvno']."</td>";
			                    $html .= $barcode;
			                    $html .= "<td width=\"7%\" align=\"center\">".$this->CPublic->convTglNonDB($value['receivedate'])."</td>";
			                    $html .= "<td width=\"7%\" align=\"center\">".$this->CPublic->convTglNonDB($value['tglinvoice'])."</td>";
			                    $html .= "<td width=\"5%\" align=\"center\">".$value['dueday']."</td>";
			                    $html .= "<td width=\"7%\" align=\"center\">".$this->CPublic->isWeekendNya($value['tglexp'])."</td>";
			                    $html .= "<td width=\"5%\" align=\"right\">".$current."</td>";
			                    $html .= "<td width=\"5%\" align=\"right\">".$currentNyaView."</td>";
								$html .= "<td width=\"8%\" align=\"right\">".$rangeSatuView."</td>";
								$html .= "<td width=\"8%\" align=\"right\">".$rangeDuaView."</td>";
								$html .= "<td width=\"8%\" align=\"right\">".$rangeTigaView."</td>";
								$html .= "<td width=\"8%\" align=\"right\">".$rangeEmpatView."</td>";
								$html .= "<td width=\"8%\" align=\"right\">".$rangeLimaView."</td>";
								$html .= "<td width=\"5%\" align=\"right\">".number_format($rowTotal, 2, ",", ".")."</td>";
							$html .= "</tr>";

			                $subTotalCurrent = $subTotalCurrent + $currentNya;
			                $subTotalRangeSatu = $subTotalRangeSatu + $rangeSatu;
			                $subTotalRangeDua = $subTotalRangeDua + $rangeDua;
			                $subTotalRangeTiga = $subTotalRangeTiga + $rangeTiga;
			                $subTotalRangeEmpat = $subTotalRangeEmpat + $rangeEmpat;
			                $subTotalRangeLima = $subTotalRangeLima + $rangeLima;

			                $no++;
			            }

			            $subRowTotal = 0;
			            if($subTotalCurrent > 0)
			            {
			            	$subRowTotal = $subRowTotal + $subTotalCurrent;
			                $subTotalCurrent = number_format($subTotalCurrent, 2, ",", ".");
			            }else{
			                $subTotalCurrent = "";
			            }

			            if($subTotalRangeSatu > 0)
			            {
			            	$subRowTotal = $subRowTotal + $subTotalRangeSatu;
			                $subTotalRangeSatu = number_format($subTotalRangeSatu, 2, ",", ".");
			            }else{
			                $subTotalRangeSatu = "";
			            }

			            if($subTotalRangeDua > 0)
			            {
			            	$subRowTotal = $subRowTotal + $subTotalRangeDua;
			                $subTotalRangeDua = number_format($subTotalRangeDua, 2, ",", ".");
			            }else{
			                $subTotalRangeDua = "";
			            }

			            if($subTotalRangeTiga > 0)
			            {
			            	$subRowTotal = $subRowTotal + $subTotalRangeTiga;
			                $subTotalRangeTiga = number_format($subTotalRangeTiga, 2, ",", ".");
			            }else{
			                $subTotalRangeTiga = "";
			            }

			            if($subTotalRangeEmpat > 0)
			            {
			            	$subRowTotal = $subRowTotal + $subTotalRangeEmpat;
			                $subTotalRangeEmpat = number_format($subTotalRangeEmpat, 2, ",", ".");
			            }else{
			                $subTotalRangeEmpat = "";
			            }

			            if($subTotalRangeLima > 0)
			            {
			            	$subRowTotal = $subRowTotal + $subTotalRangeLima;
			                $subTotalRangeLima = number_format($subTotalRangeLima, 2, ",", ".");
			            }else{
			                $subTotalRangeLima = "";
			            }

			            $html .= "<tr height=\"20\" style=\"font-style:bold;\">";
			                $html .= "<td class=\"tabelBorderBottomJust\" style=\"border-bottom-color:#999;\" colspan=\"".$colspan."\" > </td>";
			                $html .= "<td class=\"tabelBorderBotAndTop\" style=\"font-weight:bold;\" align=\"right\">Sub Total</td>";
			                $html .= "<td class=\"tabelBorderBotAndTop\" style=\"font-weight:bold;\" align=\"right\">".$subTotalCurrent."</td>";
			                $html .= "<td class=\"tabelBorderBotAndTop\" style=\"font-weight:bold;\" align=\"right\">".$subTotalRangeSatu."</td>";
			                $html .= "<td class=\"tabelBorderBotAndTop\" style=\"font-weight:bold;\" align=\"right\">".$subTotalRangeDua."</td>";
			                $html .= "<td class=\"tabelBorderBotAndTop\" style=\"font-weight:bold;\" align=\"right\">".$subTotalRangeTiga."</td>";
			                $html .= "<td class=\"tabelBorderBotAndTop\" style=\"font-weight:bold;\" align=\"right\">".$subTotalRangeEmpat."</td>";
			                $html .= "<td class=\"tabelBorderBotAndTop\" style=\"font-weight:bold;\" align=\"right\">".$subTotalRangeLima."</td>";
			                $html .= "<td class=\"tabelBorderBotAndTop\" style=\"font-weight:bold;\" align=\"right\">".number_format($subRowTotal, 2, ",", ".")."</td>";
			            $html .= "</tr>";
			        }
			    }
			}			

			$html.= '
			</table>
			</td>
			</tr>
			';
			
			// Total IDR
			if($a == $maxPage)
			{
				$totalRowIdr = $totalCurrentIdr + $totalRange1Idr + $totalRange2Idr + $totalRange3Idr + $totalRange4Idr + $totalRange5Idr;
    			$totalRowUsd = $totalCurrentUsd + $totalRange1Usd + $totalRange2Usd + $totalRange3Usd + $totalRange4Usd + $totalRange5Usd;

				if($totalCurrentIdr > 0) { $totalCurrentIdr = number_format($totalCurrentIdr, 2, ",", "."); } else { $totalCurrentIdr = ""; }
			    if($totalRange1Idr > 0) { $totalRange1Idr = number_format($totalRange1Idr, 2, ",", "."); } else { $totalRange1Idr = ""; }
			    if($totalRange2Idr > 0) { $totalRange2Idr = number_format($totalRange2Idr, 2, ",", "."); } else { $totalRange2Idr = ""; }
			    if($totalRange3Idr > 0) { $totalRange3Idr = number_format($totalRange3Idr, 2, ",", "."); } else { $totalRange3Idr = ""; }
			    if($totalRange4Idr > 0) { $totalRange4Idr = number_format($totalRange4Idr, 2, ",", "."); } else { $totalRange4Idr = ""; }
			    if($totalRange5Idr > 0) { $totalRange5Idr = number_format($totalRange5Idr, 2, ",", "."); } else { $totalRange5Idr = ""; }
			    if($totalCurrentUsd > 0) { $totalCurrentUsd = number_format($totalCurrentUsd, 2, ",", "."); } else { $totalCurrentUsd = ""; }
			    if($totalRange1Usd > 0) { $totalRange1Usd = number_format($totalRange1Usd, 2, ",", "."); } else { $totalRange1Usd = ""; }
			    if($totalRange2Usd > 0) { $totalRange2Usd = number_format($totalRange2Usd, 2, ",", "."); } else { $totalRange2Usd = ""; }
			    if($totalRange3Usd > 0) { $totalRange3Usd = number_format($totalRange3Usd, 2, ",", "."); } else { $totalRange3Usd = ""; }
			    if($totalRange4Usd > 0) { $totalRange4Usd = number_format($totalRange4Usd, 2, ",", "."); } else { $totalRange4Usd = ""; }
			    if($totalRange5Usd > 0) { $totalRange5Usd = number_format($totalRange5Usd, 2, ",", "."); } else { $totalRange5Usd = ""; }

				$html.= '
					<tr>
					<td height="22">
					<table class="tabelBorderBottomJust" height="100%" cellpadding="0" cellspacing="0" width="100%" style="font:1.1em Arial Narrow;font-weight:bold;">
						<tr>
							<td width="2%">&nbsp;</td>
							<td width="45%" align="right">Total - IDR</td>
							<td width="8%" align="right">'.$totalCurrentIdr.'</td>
							<td width="8%" align="right">'.$totalRange1Idr.'</td>
							<td width="8%" align="right">'.$totalRange2Idr.'</td>
							<td width="8%" align="right">'.$totalRange3Idr.'</td>
							<td width="8%" align="right">'.$totalRange4Idr.'&nbsp;</td>
							<td width="8%" align="right">'.$totalRange5Idr.'&nbsp;</td>
							<td width="8%" align="right">'.number_format($totalRowIdr, 2, ",", ".").'&nbsp;</td>
						</tr>
					</table>
					</td>
					</tr>
					';
		
				// Total USD		
				$html.= '
					<tr>
					<td height="22">
					<table class="tabelBorderBottomJust" height="100%" cellpadding="0" cellspacing="0" width="100%" style="font:1.1em Arial Narrow;font-weight:bold;">
						<tr>
							<td width="2%">&nbsp;</td>
							<td width="45%" align="right">Total - USD</td>
							<td width="8%" align="right">'.$totalCurrentUsd.'</td>
							<td width="8%" align="right">'.$totalRange1Usd.'</td>
							<td width="8%" align="right">'.$totalRange2Usd.'</td>
							<td width="8%" align="right">'.$totalRange3Usd.'</td>
							<td width="8%" align="right">'.$totalRange4Usd.'&nbsp;</td>
							<td width="8%" align="right">'.$totalRange5Usd.'&nbsp;</td>
							<td width="8%" align="right">'.number_format($totalRowUsd, 2, ",", ".").'&nbsp;</td>
						</tr>
					</table>
					</td>
					</tr>
					';
				if($currOther1 != "")
				{
					$totalRowOther1 = $totalOther1Current + $totalOther1RangeSatu + $totalOther1RangeDua + $totalOther1RangeTiga + $totalOther1RangeEmpat + $totalOther1RangeLima;

					if($totalOther1Current > 0) { $totalOther1Current = number_format($totalOther1Current, 2, ",", "."); } else { $totalOther1Current = ""; }
			        if($totalOther1RangeSatu > 0) { $totalOther1RangeSatu = number_format($totalOther1RangeSatu, 2, ",", "."); } else { $totalOther1RangeSatu = ""; }
			        if($totalOther1RangeDua > 0) { $totalOther1RangeDua = number_format($totalOther1RangeDua, 2, ",", "."); } else { $totalOther1RangeDua = ""; }
			        if($totalOther1RangeTiga > 0) { $totalOther1RangeTiga = number_format($totalOther1RangeTiga, 2, ",", "."); } else { $totalOther1RangeTiga = ""; }
			        if($totalOther1RangeEmpat > 0){ $totalOther1RangeEmpat= number_format($totalOther1RangeEmpat, 2, ",", "."); }else{ $totalOther1RangeEmpat = ""; }
			        if($totalOther1RangeLima > 0) { $totalOther1RangeLima = number_format($totalOther1RangeLima, 2, ",", "."); } else { $totalOther1RangeLima = ""; }

					$html.= '
						<tr>
						<td height="22">
						<table class="tabelBorderBottomJust" height="100%" cellpadding="0" cellspacing="0" width="100%" style="font:1.1em Arial Narrow;font-weight:bold;">
							<tr>
								<td width="2%">&nbsp;</td>
								<td width="45%" align="right">Total - '.$currOther1.'</td>
								<td width="8%" align="right">'.$totalOther1Current.'</td>
								<td width="8%" align="right">'.$totalOther1RangeSatu.'</td>
								<td width="8%" align="right">'.$totalOther1RangeDua.'</td>
								<td width="8%" align="right">'.$totalOther1RangeTiga.'</td>
								<td width="8%" align="right">'.$totalOther1RangeEmpat.'&nbsp;</td>
								<td width="8%" align="right">'.$totalOther1RangeLima.'&nbsp;</td>
								<td width="8%" align="right">'.number_format($totalRowOther1, 2, ",", ".").'&nbsp;</td>
							</tr>
						</table>
						</td>
						</tr>
						';
				}

				if($currOther2 != "")
				{
					$totalRowOther2 = $totalOther2Current + $totalOther2RangeSatu + $totalOther2RangeDua + $totalOther2RangeTiga + $totalOther2RangeEmpat + $totalOther2RangeLima;

					if($totalOther2Current > 0) { $totalOther2Current = number_format($totalOther2Current, 2, ",", "."); } else { $totalOther2Current = ""; }
			        if($totalOther2RangeSatu > 0) { $totalOther2RangeSatu = number_format($totalOther2RangeSatu, 2, ",", "."); } else { $totalOther2RangeSatu = ""; }
			        if($totalOther2RangeDua > 0) { $totalOther2RangeDua = number_format($totalOther2RangeDua, 2, ",", "."); } else { $totalOther2RangeDua = ""; }
			        if($totalOther2RangeTiga > 0) { $totalOther2RangeTiga = number_format($totalOther2RangeTiga, 2, ",", "."); } else { $totalOther2RangeTiga = ""; }
			        if($totalOther2RangeEmpat > 0){ $totalOther2RangeEmpat= number_format($totalOther2RangeEmpat, 2, ",", "."); }else{ $totalOther2RangeEmpat = ""; }
			        if($totalOther2RangeLima > 0) { $totalOther2RangeLima = number_format($totalOther2RangeLima, 2, ",", "."); } else { $totalOther2RangeLima = ""; }

					$html.= '
						<tr>
						<td height="22">
						<table class="tabelBorderBottomJust" height="100%" cellpadding="0" cellspacing="0" width="100%" style="font:1.1em Arial Narrow;font-weight:bold;">
							<tr>
								<td width="2%">&nbsp;</td>
								<td width="45%" align="right">Total - '.$currOther2.'</td>
								<td width="8%" align="right">'.$totalOther2Current.'</td>
								<td width="8%" align="right">'.$totalOther2RangeSatu.'</td>
								<td width="8%" align="right">'.$totalOther2RangeDua.'</td>
								<td width="8%" align="right">'.$totalOther2RangeTiga.'</td>
								<td width="8%" align="right">'.$totalOther2RangeEmpat.'&nbsp;</td>
								<td width="8%" align="right">'.$totalOther2RangeLima.'&nbsp;</td>
								<td width="8%" align="right">'.number_format($totalRowOther2, 2, ",", ".").'&nbsp;</td>
							</tr>
						</table>
						</td>
						</tr>
						';
				}
			}
			$html.= "<tr><td style=\"position:absolute;bottom:-".$persenHeight."%;width:100%;right:0%;font:0.7em sans-serif;color:#333;\" class=\"tabelBorderAllNull\"> Page ".$a." of ".$maxPage."</td></tr>";

		}
		
		return $html;
	}
	
	function printAging_03082022($get)
	{
		$userId = $get['userId'];
		$account = $get['account'];

		$currOther1 = "";
		$totalOther1RangeSatu = "";
		$totalOther1RangeDua = "";
		$totalOther1RangeTiga = "";
		$totalOther1RangeEmpat = "";
		$totalOther1RangeLima = "";
		$currOther2 = "";
		$totalOther2RangeSatu = "";
		$totalOther2RangeDua = "";
		$totalOther2RangeTiga = "";
		$totalOther2RangeEmpat = "";
		$totalOther2RangeLima = "";
		
		$limitHeight = 500;
		$jmlAllHeight = $this->jmlAllHeight($userId);
		
		$maxPage = ceil($jmlAllHeight/$limitHeight); // jumlah semua data dibagi limit maka didapatlah jumlah halaman
		$html = "";
		for($a=1;$a<=$maxPage;$a++)
		{
			//echo $maxPage."<br>";
			if($a == 1)//jika halaman satu maka nomor mulai dari 1
			{
				$persenHeight = 0;
				$pagebreak = "";
				
				$batasAwal = 0;
				$batasAkhir = ($a * $limitHeight);
				
				$offset = "LIMIT 0, ".$this->cariMaksRowPerHalaman($userId, $a, $batasAwal, $batasAkhir, "end");
			}
			if($a > 1)//jika halaman satu maka nomor mulai dari 1
			{
				$persenHeight = ($a-1) * 100;
				$pagebreak = "<tr style=\"page-break-after: left;\"></tr>";
				
				$batasAwal = (($a-1) * $limitHeight);
				$batasAkhir = ($a * $limitHeight);
				
				$offset =  "LIMIT ".$this->cariMaksRowPerHalaman($userId, $a, $batasAwal, $batasAkhir, "start").", ".$this->cariMaksRowPerHalaman($userId, $a, $batasAwal, $batasAkhir, "end");
			}
			$offsetDisp.= $offset;
			$html.= $pagebreak;
			// HEADER AGING Tabel		
			$html.= $this->headerAging($get);			
			
			// START --  Tabel Nama Vendor s/d 	Sub Total	
			$html.= '
			<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%" style="font:1em Arial Narrow;font-weight:normal;">';
	
			if($offset != "LIMIT , ")
			{
			// While Query
			$query = $this->koneksi->mysqlQuery("SELECT * FROM summaryaging WHERE userid = ".$userId." ORDER BY urutan ASC ".$offset.";");
			while($row = $this->koneksi->mysqlFetch($query))
			{
			// kondisi amount
				// $current = "&nbsp;";
				// if($row['amount'] != 0.00)
				// 	$current = number_format($row['amount'], 2, ",", ".");
				$current = $row['currency'];
				$rangeSatu = "&nbsp;";
				if($row['rangesatu'] != 0.00)
					$rangeSatu = number_format($row['rangesatu'], 2, ",", ".");
				$rangeDua = "&nbsp;";
				if($row['rangedua'] != 0.00)
					$rangeDua = number_format($row['rangedua'], 2, ",", ".");
				$rangeTiga = "&nbsp;";
				if($row['rangetiga'] != 0.00)
					$rangeTiga = number_format($row['rangetiga'], 2, ",", ".");
				$rangeEmpat = "&nbsp;";
				if($row['rangeempat'] != 0.00)
					$rangeEmpat = number_format($row['rangeempat'], 2, ",", ".");
				$rangeLima = "&nbsp;";
				if($row['rangelima'] != 0.00)
					$rangeLima = number_format($row['rangelima'], 2, ",", ".");
					
				//kondisi dan function Sub Total
				$subRangeSatu = "&nbsp;";
				if($this->CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "satu") != 0.00)
					$subRangeSatu = number_format($this->CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "satu"), 2, ",", ".");
				$subRangeDua = "&nbsp;";
				if($this->CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "dua") != 0.00)
					$subRangeDua = number_format($this->CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "dua"), 2, ",", ".");
				$subRangeTiga = "&nbsp;";
				if($this->CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "tiga") != 0.00)
					$subRangeTiga = number_format($this->CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "tiga"), 2, ",", ".");
				$subRangeEmpat = "&nbsp;";
				if($this->CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "empat") != 0.00)
					$subRangeEmpat = number_format($this->CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "empat"), 2, ",", ".");
				$subRangeLima = "&nbsp;";
				if($this->CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "lima") != 0.00)
					$subRangeLima = number_format($this->CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "lima"), 2, ",", ".");
				
				//kondisi dan function Total IDR	
				$totalIdr = "&nbsp;";
				if($this->CAging->hitungTotal($userId, "IDR", "") != 0.00)
					$totalIdr = number_format($this->CAging->hitungTotal($userId, "IDR", "satu"), 2, ",", ".");
				$totalRangeSatuIdr = "&nbsp;";
				if($this->CAging->hitungTotal($userId, "IDR", "satu") != 0.00)
					$totalRangeSatuIdr = number_format($this->CAging->hitungTotal($userId, "IDR", "satu"), 2, ",", ".");
				$totalRangeDuaIdr = "&nbsp;";
				if($this->CAging->hitungTotal($userId, "IDR", "dua") != 0.00)
					$totalRangeDuaIdr = number_format($this->CAging->hitungTotal($userId, "IDR", "dua"), 2, ",", ".");
				$totalRangeTigaIdr = "&nbsp;";
				if($this->CAging->hitungTotal($userId, "IDR", "tiga") != 0.00)
					$totalRangeTigaIdr = number_format($this->CAging->hitungTotal($userId, "IDR", "tiga"), 2, ",", ".");
				$totalRangeEmpatIdr = "&nbsp;";
				if($this->CAging->hitungTotal($userId, "IDR", "empat") != 0.00)
					$totalRangeEmpatIdr = number_format($this->CAging->hitungTotal($userId, "IDR", "empat"), 2, ",", ".");
				$totalRangeLimaIdr = "&nbsp;";
				if($this->CAging->hitungTotal($userId, "IDR", "lima") != 0.00)
					$totalRangeLimaIdr = number_format($this->CAging->hitungTotal($userId, "IDR", "lima"), 2, ",", ".");
				
				//kondisi dan function Total USD	
				$totalUsd = "&nbsp;";
				if($this->CAging->hitungTotal($userId, "USD", "") != 0.00)
					$totalUsd = number_format($this->CAging->hitungTotal($userId, "USD", ""), 2, ",", ".");
				$totalRangeSatuUsd = "&nbsp;";
				if($this->CAging->hitungTotal($userId, "USD", "satu") != 0.00)
					$totalRangeSatuUsd = number_format($this->CAging->hitungTotal($userId, "USD", "satu"), 2, ",", ".");
				$totalRangeDuaUsd = "&nbsp;";
				if($this->CAging->hitungTotal($userId, "USD", "dua") != 0.00)
					$totalRangeDuaUsd = number_format($this->CAging->hitungTotal($userId, "USD", "dua"), 2, ",", ".");
				$totalRangeTigaUsd = "&nbsp;";
				if($this->CAging->hitungTotal($userId, "USD", "tiga") != 0.00)
					$totalRangeTigaUsd = number_format($this->CAging->hitungTotal($userId, "USD", "tiga"), 2, ",", ".");
				$totalRangeEmpatUsd = "&nbsp;";
				if($this->CAging->hitungTotal($userId, "USD", "empat") != 0.00)
					$totalRangeEmpatUsd = number_format($this->CAging->hitungTotal($userId, "USD", "empat"), 2, ",", ".");
				$totalRangeLimaUsd = "&nbsp;";
				if($this->CAging->hitungTotal($userId, "USD", "lima") != 0.00)
					$totalRangeLimaUsd = number_format($this->CAging->hitungTotal($userId, "USD", "lima"), 2, ",", ".");

				if($current != "IDR" AND $current != "USD")
			    {
			        if($currOther1 == "")
			        {
			            $currOther1 = $current;

			            if($this->CAging->hitungTotal($userId, $current, "satu") != 0.00)
			            $totalOther1RangeSatu = number_format($this->CAging->hitungTotal($userId, $current, "satu"), 2, ",", ".");
			            if($this->CAging->hitungTotal($userId, $current, "dua") != 0.00)
			            $totalOther1RangeDua = number_format($this->CAging->hitungTotal($userId, $current, "dua"), 2, ",", ".");
			            if($this->CAging->hitungTotal($userId, $current, "tiga") != 0.00)
			            $totalOther1RangeTiga = number_format($this->CAging->hitungTotal($userId, $current, "tiga"), 2, ",", ".");
			            if($this->CAging->hitungTotal($userId, $current, "empat") != 0.00)
			            $totalOther1RangeEmpat = number_format($this->CAging->hitungTotal($userId, $current, "empat"), 2, ",", ".");
			            if($this->CAging->hitungTotal($userId, $current, "lima") != 0.00)
			            $totalOther1RangeLima = number_format($this->CAging->hitungTotal($userId, $current, "lima"), 2, ",", ".");
			        }else{
			            if($currOther1 == $current)
			            {
			                if($this->CAging->hitungTotal($userId, $current, "satu") != 0.00)
			                $totalOther1RangeSatu = number_format($this->CAging->hitungTotal($userId, $current, "satu"), 2, ",", ".");
			                if($this->CAging->hitungTotal($userId, $current, "dua") != 0.00)
			                $totalOther1RangeDua = number_format($this->CAging->hitungTotal($userId, $current, "dua"), 2, ",", ".");
			                if($this->CAging->hitungTotal($userId, $current, "tiga") != 0.00)
			                $totalOther1RangeTiga = number_format($this->CAging->hitungTotal($userId, $current, "tiga"), 2, ",", ".");
			                if($this->CAging->hitungTotal($userId, $current, "empat") != 0.00)
			                $totalOther1RangeEmpat = number_format($this->CAging->hitungTotal($userId, $current, "empat"), 2, ",", ".");
			                if($this->CAging->hitungTotal($userId, $current, "lima") != 0.00)
			                $totalOther1RangeLima = number_format($this->CAging->hitungTotal($userId, $current, "lima"), 2, ",", ".");
			            }else{
			                if($currOther2 == "")
			                {
			                    $currOther2 = $current;                    
			                }
			                if($this->CAging->hitungTotal($userId, $current, "satu") != 0.00)
			                $totalOther2RangeSatu = number_format($this->CAging->hitungTotal($userId, $current, "satu"), 2, ",", ".");
			                if($this->CAging->hitungTotal($userId, $current, "dua") != 0.00)
			                $totalOther2RangeDua = number_format($this->CAging->hitungTotal($userId, $current, "dua"), 2, ",", ".");
			                if($this->CAging->hitungTotal($userId, $current, "tiga") != 0.00)
			                $totalOther2RangeTiga = number_format($this->CAging->hitungTotal($userId, $current, "tiga"), 2, ",", ".");
			                if($this->CAging->hitungTotal($userId, $current, "empat") != 0.00)
			                $totalOther2RangeEmpat = number_format($this->CAging->hitungTotal($userId, $current, "empat"), 2, ",", ".");
			                if($this->CAging->hitungTotal($userId, $current, "lima") != 0.00)
			                $totalOther2RangeLima = number_format($this->CAging->hitungTotal($userId, $current, "lima"), 2, ",", ".");
			            }
			        }			        
			    }
				
				if($account == "pay")
				{
					$widthInv = "13%";
					$barcode = '<td width="6%" align="center">'.$row['barcode'].'</td>';
					$colspanCompany = '13';
					$colspan = '6';
				}
				if($account == "rec")
				{
					$widthInv = "20%";
					$barcode = '';
					$colspanCompany = '11';
					$colspan = '5';
				}
				
				
				// Jika urutan grup = 1, maka tampilkan nama vendor
				if($row['urutangrup'] == 1)	{
			// Nama Vendor - Currency		
					$html.= '
					<tr><td colspan="'.$colspanCompany.'" height="22" style="font:1.2em Arial Narrow;font-weight:bold;">'.$row['kreditacc'].' - '.$row['vendor'].' - '.$row['currency'].'</td></tr>
					';
				}
				
			// LIST DATA		
				$html.= '
					<tr>
						<td height="12" width="2%" align="center">'.$row['urutangrup'].'</td>
						<td width="'.$widthInv.'">'.$row['mailinvno'].'</td>
						<!--<td width="9%" align="center">'.$row['barcode'].'</td>-->
						'.$barcode.'
						<td width="7%" align="center">'.$this->CPublic->convTglNonDB($row['receivedate']).'</td>
						<td width="7%" align="center">'.$this->CPublic->convTglNonDB($row['tglinvoice']).'</td>
						<td width="5%" align="center">'.$row['dueday'].'</td>
						<td width="7%" align="center">'.$this->CPublic->convTglNonDB($row['tglexp']).'</td>
						<td width="5%" align="right">'.$current.'</td>
						<td width="8%" align="right">'.$rangeSatu.'</td>
						<td width="8%" align="right">'.$rangeDua.'</td>
						<td width="8%" align="right">'.$rangeTiga.'</td>
						<td width="8%" align="right">'.$rangeEmpat.'&nbsp;</td>
						<td width="8%" align="right">'.$rangeLima.'&nbsp;</td>
					</tr>';
	
				if($row['urutangrup'] == $this->CAging->jmlInvoice($userId, $row['kreditacc'], $row['currency']))
				{
			// Sub Total		
				$html.= '
				<tr height="20" style="font-style:bold;">
						<td class="tabelBorderBottomJust" style="border-bottom-color:#999;" colspan="'.$colspan.'" >&nbsp;</td>
						<td class="tabelBorderBotAndTop" style="font-weight:bold;" align="right">Sub Total</td>
						<td class="tabelBorderBotAndTop" style="font-weight:bold;" align="right"></td>
						<td class="tabelBorderBotAndTop" style="font-weight:bold;" align="right">'.$subRangeSatu.'</td>
						<td class="tabelBorderBotAndTop" style="font-weight:bold;" align="right">'.$subRangeDua.'</td>
						<td class="tabelBorderBotAndTop" style="font-weight:bold;" align="right">'.$subRangeTiga.'</td>
						<td class="tabelBorderBotAndTop" style="font-weight:bold;" align="right">'.$subRangeEmpat.'</td>
						<td class="tabelBorderBotAndTop" style="font-weight:bold;" align="right">'.$subRangeLima.'&nbsp;</td>
					</tr>';
				}
		// END -- while Query			
			} 
			}
		// END -- while Query		
			$html.= '
			</table>
			</td>
			</tr>
			';
		// END --  Tabel Nama Vendor s/d Sub Total
		
		// Total IDR
			if($a == $maxPage)
			{		
				$html.= '
					<tr>
					<td height="22">
					<table class="tabelBorderBottomJust" height="100%" cellpadding="0" cellspacing="0" width="100%" style="font:1.1em Arial Narrow;font-weight:bold;">
						<tr>
							<td width="2%">&nbsp;</td>
							<td width="45%" align="right">Total - IDR</td>
							<td width="5%" align="right"></td>
							<td width="8%" align="right">'.$totalRangeSatuIdr.'</td>
							<td width="8%" align="right">'.$totalRangeDuaIdr.'</td>
							<td width="8%" align="right">'.$totalRangeTigaIdr.'</td>
							<td width="8%" align="right">'.$totalRangeEmpatIdr.'&nbsp;</td>
							<td width="8%" align="right">'.$totalRangeLimaIdr.'&nbsp;</td>
						</tr>
					</table>
					</td>
					</tr>
					';
		
		// Total USD		
				$html.= '
					<tr>
					<td height="22">
					<table class="tabelBorderBottomJust" height="100%" cellpadding="0" cellspacing="0" width="100%" style="font:1.1em Arial Narrow;font-weight:bold;">
						<tr>
							<td width="2%">&nbsp;</td>
							<td width="45%" align="right">Total - USD</td>
							<td width="5%" align="right"></td>
							<td width="8%" align="right">'.$totalRangeSatuUsd.'</td>
							<td width="8%" align="right">'.$totalRangeDuaUsd.'</td>
							<td width="8%" align="right">'.$totalRangeTigaUsd.'</td>
							<td width="8%" align="right">'.$totalRangeEmpatUsd.'&nbsp;</td>
							<td width="8%" align="right">'.$totalRangeLimaUsd.'&nbsp;</td>
						</tr>
					</table>
					</td>
					</tr>
					';
				if($currOther1 != "")
				{
					$html.= '
						<tr>
						<td height="22">
						<table class="tabelBorderBottomJust" height="100%" cellpadding="0" cellspacing="0" width="100%" style="font:1.1em Arial Narrow;font-weight:bold;">
							<tr>
								<td width="2%">&nbsp;</td>
								<td width="45%" align="right">Total - '.$currOther1.'</td>
								<td width="5%" align="right"></td>
								<td width="8%" align="right">'.$totalOther1RangeSatu.'</td>
								<td width="8%" align="right">'.$totalOther1RangeDua.'</td>
								<td width="8%" align="right">'.$totalOther1RangeTiga.'</td>
								<td width="8%" align="right">'.$totalOther1RangeEmpat.'&nbsp;</td>
								<td width="8%" align="right">'.$totalOther1RangeLima.'&nbsp;</td>
							</tr>
						</table>
						</td>
						</tr>
						';
				}

				if($currOther2 != "")
				{
					$html.= '
						<tr>
						<td height="22">
						<table class="tabelBorderBottomJust" height="100%" cellpadding="0" cellspacing="0" width="100%" style="font:1.1em Arial Narrow;font-weight:bold;">
							<tr>
								<td width="2%">&nbsp;</td>
								<td width="45%" align="right">Total - '.$currOther2.'</td>
								<td width="5%" align="right"></td>
								<td width="8%" align="right">'.$totalOther2RangeSatu.'</td>
								<td width="8%" align="right">'.$totalOther2RangeDua.'</td>
								<td width="8%" align="right">'.$totalOther2RangeTiga.'</td>
								<td width="8%" align="right">'.$totalOther2RangeEmpat.'&nbsp;</td>
								<td width="8%" align="right">'.$totalOther2RangeLima.'&nbsp;</td>
							</tr>
						</table>
						</td>
						</tr>
						';
				}

			}
		//FOOTER
			$html.= "<tr><td style=\"position:absolute;bottom:-".$persenHeight."%;width:100%;right:1%;font:0.7em sans-serif;color:#333;\" class=\"tabelBorderAllNull\">Page ".$a." of ".$maxPage."</td></tr>";
		}
		
		return $html;
	}
	
	function jmlAllHeightWithUnion($tempData)
	{
		$urutIsi = 0;			
		$totalHeight = 0;
		$heightJudul = 22;
		$heightSubJudul = 16;
		$heightRow = 12;
		$heightSub = 20;
		$heightTotal = 22;
		$krAcc = "";
		
		if(count($tempData))
		{
			foreach ($tempData as $key => $val)
			{
				$totalHeight += $heightJudul+$heightRow+$heightSub;
				foreach ($val as $keys => $vals)
				{
					$totalHeight += $heightSubJudul;								            
					foreach ($vals as $keyd => $value)
					{
						$totalHeight += $heightRow;
			     	}
				}
			}
		}
		
		return $totalHeight;
	}

	function cariMaksRowPerHalamanWithUnion($userId, $halamanKe, $batasAwal, $batasAkhir, $aksi,$tempData)
	{
		$totalHeight = 0;
		$heightJudul = 22;
		$heightRow = 12;
		$heightSub = 20;
		$heightTotal = 22;
		$krAcc = "";
		$heightSubJudul = 16;
		
		if($halamanKe == 1)
		{
			$urutData = 0;

			if(count($tempData))
			{
				foreach ($tempData as $key => $val)
				{
					$totalHeight += $heightJudul+$heightRow+$heightSub;
					foreach ($val as $keys => $vals)
					{
						$totalHeight += $heightSubJudul;								            
						foreach ($vals as $keyd => $value)
						{
							$totalHeight += $heightRow;

							if($totalHeight >= $batasAwal && $totalHeight < $batasAkhir) 
							{
								$urutData++;
								if($aksi == "end")
								{
									$nilai = $urutData;
								}
							}

				     	}
					}
				}
			}

			if($aksi == "start")
			{
				$nilai = 0;
			}
		}

		if($halamanKe > 1)
		{
			$urutIsi = 0;
			$urutData = 0;

			if(count($tempData))
			{
				foreach ($tempData as $key => $val)
				{
					$totalHeight += $heightJudul+$heightRow+$heightSub;
					foreach ($val as $keys => $vals)
					{
						$totalHeight += $heightSubJudul;								            
						foreach ($vals as $keyd => $value)
						{
							$totalHeight += $heightRow;
							$urutIsi++;

							if($totalHeight >= $batasAwal && $totalHeight <= $batasAkhir)
							{
								$urutData++;
								if($aksi == "start")
								{
									if($urutData == 1)
									{
										$nilai = ($urutIsi-1);
									}
								}
								if($aksi == "end")
								{
									$nilai = $urutData;
								}
							}

				     	}
					}
				}
			}
		}

		return $nilai;
	}

	function jmlAllHeight($userId)
	{
		$urutIsi = 0;	 //0.325				
		$totalHeight = 0;
		$heightJudul = 22;
		$heightRow = 12;
		$heightSub = 20;
		$heightTotal = 44;
		
		$query = $this->koneksi->mysqlQuery("SELECT * FROM summaryaging WHERE userid = ".$userId.";");			
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if($row['urutangrup'] == 1)//Jika urutan grup1, maka tambahkan dengan tinggiJudul, tinggiRow, dan tinggi subTotal
			{	
				$jmlHeight = $heightJudul+$heightRow+$heightSub ;
				$totalHeight += $jmlHeight;	
			}
			else//jika tidak, tambahkan tinggi row saja
			{	$totalHeight += $heightRow;		}
			
		}
		//$jml = $totalHeight+$heightTotal;
		return $totalHeight;
	}
	
	function cariMaksRowPerHalaman($userId, $halamanKe, $batasAwal, $batasAkhir, $aksi)
	{
		$totalHeight = 0;
		$heightJudul = 22;
		$heightRow = 12;
		$heightSub = 20;
		$heightTotal = 22;
		
		if($halamanKe == 1)
		{
			$urutData = 0;	// URUTAN DATA SECARA KESELURUHAN YANG SEHARUSNYA DITAMPILKAN	
			$query = $this->koneksi->mysqlQuery("SELECT * FROM summaryaging WHERE userid = ".$userId." ORDER BY urutan ASC;");			
			while($row = $this->koneksi->mysqlFetch($query))
			{
				if($row['urutangrup'] == 1)//Jika urutan grup == 1, maka tambahkan dengan tinggiJudul, dan tinggiRow
				{	
					$jmlHeight = $heightJudul+$heightRow ;
					$totalHeight += $jmlHeight;	
				}
				if($row['urutangrup'] == $this->CAging->jmlInvoice($userId, $row['kreditacc'], $row['currency']))//Jika urutan grup == maks urutan grup, maka tambahkan dengan tinggi subTotal
				{
					$totalHeight += $heightSub;	
				}
				if($row['urutangrup'] != 1 && $row['urutangrup'] != $this->CAging->jmlInvoice($userId, $row['kreditacc'], $row['currency']))// selain itu, tambahkan dengan tinggi row
				{	$totalHeight += $heightRow;		}
				
				if($totalHeight >= $batasAwal && $totalHeight < $batasAkhir) 
				{ // TAMMPILKAN DATA YANG BERADA DI ANTARA BATAS AWAL DAN AKHIR BERDASARKAN HEIGHT DATA TSB MASUK APA TIDAK DALAM JANGKAUAN TERSEBUT
					$urutData++;
					if($aksi == "end") // JIKA AKSI SAMA DENGAN END MAKA TAMPILA DATA TERAKHIR SAJA
						$nilai = $urutData;
				}
			}
			if($aksi == "start") // KHUSUS HALAMAN 1 NILAI AWAL ADALAH 0
			{
				$nilai = 0;
			}
		}
		if($halamanKe > 1)
		{
			$urutIsi = 0;
			$urutData = 0;	// URUTAN DARI DATA YANG TAMPIL SAJA BERDASARKAN RANGE HEIGHT
			$query = $this->koneksi->mysqlQuery("SELECT * FROM summaryaging WHERE userid = ".$userId." ORDER BY urutan ASC;");			
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$urutIsi++;	
				$maxUrutGrup = $this->CAging->jmlInvoice($userId, $row['kreditacc'], $row['currency']);
				
				if($row['urutangrup'] == 1)//Jika urutan grup == 1, maka tambahkan dengan tinggiJudul, dan tinggiRow
				{	
					$jmlHeight = $heightJudul+$heightRow ;
					$totalHeight += $jmlHeight;	
				}
				if($row['urutangrup'] == $maxUrutGrup)//Jika urutan grup == maks urutan grup, maka tambahkan dengan tinggi subTotal
				{	$totalHeight += $heightSub;		}
				if($row['urutangrup'] != 1 && $row['urutangrup'] != $maxUrutGrup)// selain itu, tambahkan dengan tinggi row
				{	$totalHeight += $heightRow;		}
				
				if($totalHeight >= $batasAwal && $totalHeight <= $batasAkhir)
				{ // TAMPILKAN DATA YANG BERADA DI ANTARA BARTAS AWAL DAN AKHIR BERDASARKAN HEIGHT DATA TSB MASUK APA TIDAK DALAM JANGKAUAN TERSEBUT
					$urutData++;
					if($aksi == "start") // JIKA AKSI START MAKA TAMPILKAN DATA YANG BERADA DALAM AWAL BARIS YANG BERADA DALAM JANGKAUAN BATAS
					{
						if($urutData == 1)
						{	$nilai = ($urutIsi-1);	}
					}
					if($aksi == "end")
					{	$nilai = $urutData;		}
				}
			}
		}
		
		return $nilai;
	}
	
	function cariLastNumber($userId, $ofset, $limitBts, $limit)
	{
		$i = 1;
		$urutanBatas = "";
		$query = $this->koneksi->mysqlQuery("SELECT * FROM summaryaging WHERE userid = ".$userId." ORDER BY urutan ASC LIMIT ".$ofset.", ".$limitBts.";");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if($row['urutangrup'] == 1)	{
				$i++;
			}
			$i++;
			if($row['urutangrup'] == $this->CAging->jmlInvoice($userId, $row['kreditacc'], $row['currency']))
			{
				$i++;
			}
			
			if($urutanBatas == "" && $i >= $limit+1)
			{
				$urutanBatas = $row['urutan'];
			}
			else
			{
				$urutanBatas = $urutanBatas;
			}
		}
		
		return $urutanBatas;
	}
	
	function headerAging($get)
	{
		$endDate = $get['endDate'];
		$tgl = substr($endDate,0,2);
		$bln = substr($endDate,3,2);
		$thn = substr($endDate,6,4);
		$dateDisp = ucfirst(strtolower($this->CPublic->detilBulanNamaAngka($bln, "eng")))." ".$tgl.", ".$thn;
		
		$account = $get['account'];
		
		if($account == "pay")
		{
			$title = "Payable";
			$widthInv = "13%";
			$barcode = '<td rowspan="2" width="6%" align="center">Barcode</td>';
		}
		if($account == "rec")
		{
			$title = "Receivable";
			$widthInv = "20%";
			$barcode = '';
		}
		
		$html.= '
		<tr>
                <td height="20" style="font:1.5em Arial Narrow;font-weight:bold;">'.$get['companyName'].'</td>
            </tr>
            <tr>
                <td height="20" style="font:1.3em Arial Narrow;font-weight:normal;">Account '.$title.' Aging Report</td>
            </tr>
            <tr>
                <td height="20" style="font:1.3em Arial Narrow;font-weight:normal;">as of '.$dateDisp.'</td>
            </tr>
            
            <!-- TABEL -->
            <tr>
            <td width="100%">
            <table class="tabelBorderBottomJust" width="100%" style="font:1.4em Arial Narrow;font-weight:bold;border-color:#333;border-bottom-width:2px">
            	<tr>
                	<td rowspan="2" width="2%" align="center">No</td>
                    <td rowspan="2" width="'.$widthInv.'" align="center">Invoice No</td>
                    '.$barcode.'
                    <td width="7%" align="center">Inv. Received</td>
                    <td rowspan="2" width="7%" align="center">Inv. Date</td>
                    <td rowspan="2" width="5%" align="center">Term</td>
                    <td rowspan="2" width="7%" align="center">Inv. Due Date</td>
                    <td rowspan="2" width="5%" align="center">Currency</td>
                    <td rowspan="2" width="5%" align="center">Current</td>
                    <td colspan="5" align="center" class="tabelBorderBottomJust" style="border-color:#333;" width="40%">Overdue</td>
                    <td rowspan="2" width="5%" align="center">Total</td>
                </tr>
                <tr>
					<td align="center">Date</td>
                	<td width="8%" align="center">1 - 30 Days</td>
                    <td width="8%" align="center">31 - 60 Days</td>
                    <td width="8%" align="center">61 - 90 Days</td>
                    <td width="8%" align="center">91 - 360 Days</td>
                    <td width="8%" align="center">> 360 Days</td>
                </tr>
            </table>
            </td>
            </tr>
		';
		
		return $html;
	}
	
	function jmlRowIsiDispo($userId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT * FROM summaryaging WHERE userid = ".$userId.";");
		return $this->koneksi->mysqlNRows($query);
	}
	
	function jmlAllJudulDispo($userId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT * FROM summaryaging WHERE userid = 00011 GROUP BY kreditacc;");
		return $this->koneksi->mysqlNRows($query);
	}
	
	function jmlJudulDispo($offset, $limit, $userId)
	{
		$jmlJudul = 0;
		
		$query = $this->koneksi->mysqlQuery("SELECT * FROM summaryaging WHERE userid = ".$userId." ORDER BY urutan ASC LIMIT ".$offset.", ".$limit);					
		while($row = $this->koneksi->mysqlFetch($query))
		{
			//$cekUrutByUnit = $this->cekUrutByUnit($this->CPublic->zerofill($row['unit'],5), $paramPrintBy);
			if($row['urutangrup'] == 1)// jika urutangrup == 1, maka ++ judul
			{
				$jmlJudul++;
			}
		}
		
		return $jmlJudul;
	}
	
	function jmlSubTotDispo($offset, $limit, $userId)
	{
		$jmlSub = 0;
		
		$query = $this->koneksi->mysqlQuery("SELECT * FROM summaryaging WHERE userid = ".$userId." ORDER BY urutan ASC LIMIT ".$offset.", ".$limit);					
		while($row = $this->koneksi->mysqlFetch($query))
		{
			//$cekUrutByUnit = $this->cekUrutByUnit($this->CPublic->zerofill($row['unit'],5), $paramPrintBy);
			if($row['urutangrup'] == $this->CAging->jmlInvoice($userId, $row['kreditacc'], $row['currency']))// jika urutangrup == jmlMaks Invoice/Vendor, maka ++subtotal
			{
				$jmlSub++;
			}
		}
		
		return $jmlSub;
	}
}
?>