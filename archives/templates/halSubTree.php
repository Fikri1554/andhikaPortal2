<?php
require_once("../../config.php");

$empNoDidet_1 = "00425";
$empNoDidet_2 = "00625";

$userEmpNoLvl1 = $userEmpNo;
if($userEmpNo == "00625")
{
	$queryBossEmpNo = "AND (bossempno = '".$empNoDidet_1."' OR bossempno = '".$empNoDidet_2."')";
}
else
{
	$queryBossEmpNo = "AND (bossempno = '".$userEmpNoLvl1."')";
}
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
<!-- START LEVEL 1 ###################  -->
<tr><td height="20">&nbsp;</td></tr>
<tr>
	<td>&nbsp;</td>
	<td align="center" valign="top" colspan="2">
    	<table cellpadding="0" cellspacing="0" align="center" style="text-align:center;" border="0">
        <tr>
        	<td colspan="2">
                &nbsp;&nbsp;
                <?php
				$namaLvl1 = $CLogin->detilLogin($userIdLogin, "userfullnm");
				echo tombolTree($CLogin, "", $namaLvl1, "ada", "kosong");
				?>
                &nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td><td>&nbsp;</td>
        </tr>
        </table>
    </td>
    <td>&nbsp;</td>
</tr>

<tr>
	<td colspan="10" valign="top">
   		<table cellpadding="0" cellspacing="0" align="center" border="0">
        <tr>
        <?php
		$urutan = 0;
		$html = "";
		$query = $koneksiOdbc->odbcExec($koneksiOdbcId, "SELECT empno, nama, tglkeluar, stsresign, YEAR(tglkeluar) AS tahunkeluar FROM dbo.tblMstEmp WHERE (stsresign = 0) AND (kdcmp = 01 OR kdcmp = 02) AND (YEAR(tglkeluar) = '1900') AND (deletests = 0) ".$queryBossEmpNo." ORDER BY nama");
		$jmlRow = $koneksiOdbc->odbcNRows($query);
		while($row = $koneksiOdbc->odbcFetch($query))
		{
			$urutan++;
			
			$border1 = "<tr><td height=\"10\" width=\"50%\" class=\"tabelBorderTopJust\">&nbsp;</td><td class=\"tabelBorderBottomRightNull\">&nbsp;</td></tr>";
			if($urutan == 1)
			{
				$border1 = "<tr><td height=\"10\" width=\"50%\">&nbsp;</td><td class=\"tabelBorderBottomRightNull\">&nbsp;</td></tr>";
			}
			if($urutan == $jmlRow)
			{
				$border1 = "<tr><td height=\"10\" width=\"50%\" class=\"tabelBorderTopJust\">&nbsp;</td><td class=\"tabelBorderLeftJust\">&nbsp;</td></tr>";
			}
			if($jmlRow == 1)
			{
				$border1 = "<tr><td height=\"10\" width=\"50%\" class=\"\">&nbsp;</td><td class=\"tabelBorderLeftJust\">&nbsp;</td></tr>";
			}

			$cekPunyaLogin = $CLogin->cekPunyaLogin($CKoneksi, $row['empno']);			
			$cekSubordinate = $CEmployee->cekSubordinate($koneksiOdbc, $koneksiOdbcId, $row['empno']);
			if($cekSubordinate == "kosong")
			{
				$html.= "
						<td valign=\"top\">
							<table cellpadding=\"0\" cellspacing=\"0\" align=\"center\" style=\"text-align:center;\">
							".$border1."
							<tr>
								<td valign=\"top\" colspan=\"2\">
									 &nbsp;&nbsp;
									 	".tombolTree($CLogin, $row['empno'], $row['nama'], $cekPunyaLogin, "kosong")."
									&nbsp;&nbsp;
								</td>
							</tr>
							</table>
						</td>";
			}
			if($cekSubordinate == "ada") // cek jika punya anak buah maka muncul border kebawah
			{
				$html.= "
						<td valign=\"top\">
							<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
							".$border1."
							<tr>
								<td colspan=\"2\">
									<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
									<tr>
										<td width=\"10\" height=\"50%\" class=\"tabelBorderBottomJust\">&nbsp;</td>
										<td width=\"110\" align=\"center\" valign=\"middle\" rowspan=\"2\">
											".tombolTree($CLogin, $row['empno'], $row['nama'], $cekPunyaLogin, "kosong")."
										</td>
										<td>&nbsp;</td>
									</tr>
									<tr><td class=\"tabelBorderLeftJust\">&nbsp;</td></tr>
									</table>
								</td>
							</tr>";
				
				$html.= "	<tr><td height=\"10\" colspan=\"2\" class=\"tabelBorderLeftJust\">&nbsp;</td></tr>
							<tr>
								<td align=\"center\" valign=\"top\" colspan=\"2\">
									<table cellpadding=\"0\" cellspacing=\"0\" align=\"left\">";
				$urutan2 = 0;				
				$query2 = $koneksiOdbc->odbcExec($koneksiOdbcId, "SELECT empno, nama, tglkeluar, stsresign, YEAR(tglkeluar) AS tahunkeluar FROM dbo.tblMstEmp WHERE (stsresign = 0) AND (kdcmp = 01 OR kdcmp = 02) AND (YEAR(tglkeluar) = '1900') AND (deletests = 0) AND (bossempno = '".$row['empno']."') ORDER BY nama");
				$jmlRow2 = $koneksiOdbc->odbcNRows($query2);
				while($row2 = $koneksiOdbc->odbcFetch($query2))
				{
					$urutan2++;
					if($urutan2 == $jmlRow2)
					{
						$border2 = "<tr><td>&nbsp;</td></tr>";
					}
					else
					{
						$border2 = "<tr><td class=\"tabelBorderLeftJust\">&nbsp;</td></tr>
									<tr><td class=\"tabelBorderLeftJust\" height=\"10\">&nbsp;</td></tr>";
					}
					
					$cekPunyaLogin2 = $CLogin->cekPunyaLogin($CKoneksi, $row2['empno']);
					$cekSubordinate2 = $CEmployee->cekSubordinate($koneksiOdbc, $koneksiOdbcId, $row2['empno']);
					$html.= "<tr>
								<td width=\"10\" height=\"50%\" class=\"tabelBorderTopRightNull\">&nbsp;</td>
								<td width=\"110\" align=\"center\" valign=\"middle\" rowspan=\"2\">
									".tombolTree($CLogin, $row2['empno'], $row2['nama'], $cekPunyaLogin2, $cekSubordinate2)."
								</td>
								<td>&nbsp;</td>
							</tr>
							".$border2."
							";
				}
				$html.= "			</table>";					
				$html.= "    	</td>
                			</tr>
                			</table>";			
				$html.= "</td>";
			}
		}
		
		if($userSubCustom == "Y")
		{
			$querySubCustom = $CKoneksi->mysqlQuery("SELECT * FROM tblsubcustom WHERE userid='".$userIdLogin."' AND deletests=0 ORDER BY userfullnm ASC");
			while($row = $CKoneksi->mysqlFetch($querySubCustom))
			{
				$empNoSubCustom = $CLogin->detilLogin($row['sub_userid'], "empno");
				$html.= "<td valign=\"top\">
							<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
							<tr><td height=\"20\" class=\"fontBtnTree\">&nbsp;</td></tr>
							<tr>
								<td valign=\"top\" colspan=\"2\">";
				$html.= tombolTreeSubCustom($row['sub_userfullnm'], $empNoSubCustom);				
				$html.= "   	</td>
                			</tr>
                			</table>";	
				$html.= "</td>";
			}
		}
		
		echo $html;
?>	
		</tr>
        </table>
    	
    </td>
</tr>
 <tr><td height="100%">&nbsp;</td></tr>
</table>

<!-- END LEVEL 1 ###################  -->

<?php
function tombolTree($CLogin, $empNo, $nama, $cekPunyaLogin, $cekSubordinate)
{
	$html = "";
	if($cekPunyaLogin == "kosong")
	{
		$html = "<button type=\"button\" class=\"btnTreeDisabled\" style=\"width:110;\" disabled>
					<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" class=\"fontBtnTreeDisabled\" title=\"Go to Subordinate Folder / Daily Activity\">
					  <tr>
						<td align=\"center\">
						<p class=\"kotakTreeDisabled\"  align=\"justify\">".$nama."</p></td>
					  </tr>
					</table>
				</button>";
	}
	if($cekPunyaLogin == "ada")
	{
		$bgPlus = "";
		$onClick = "onclick=\"parent.loadUrl('../index.php?aksi=pilihBtnSubordinate&empNo=".$empNo."'); return false;\"";
		if($cekSubordinate == "ada")
		{
			$bgPlus = "style=\"background-image:url(../../picture/toggle-expand.png);background-repeat:no-repeat;background-position:right top;\"";
			$onClick = "onclick=\"parent.openThickboxWindow('".$empNo."','detailSubordinate');\"";
		}
		
		if($empNo == "")
		{
			$onClick = "";
		}
		
		$html = "<button type=\"button\" class=\"btnTree\" onMouseOver=\"this.className='btnTreeHover'\" onMouseOut=\"this.className='btnTree'\" style=\"width:110;\" ".$onClick." title=\"Go to Folder / Daily Activity\">
					<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" class=\"fontBtnTree\" onMouseOver=\"this.className='fontBtnTreeHover'\" onMouseOut=\"this.className='fontBtnTree'\" ".$bgPlus.">
					  <tr>
						<td align=\"center\">
						<p class=\"kotakTree\" align=\"justify\">".$nama."</p></td>
					  </tr>
					</table>
				</button>";
	}
	
	return $html;
}

function tombolTreeSubCustom($nama, $empNo)
{
	$onClick = "onclick=\"parent.openThickboxWindow('".$empNo."','pilihHalaman');\"";
	$html = "";
	
	$html = "&nbsp;&nbsp;
				<button type=\"button\" class=\"btnTree\" onMouseOver=\"this.className='btnTreeHover'\" onMouseOut=\"this.className='btnTree'\" style=\"width:110;\" ".$onClick." title=\"Go to Subordinate Folder / Daily Activity\">
					<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" class=\"fontBtnTree\" onMouseOver=\"this.className='fontBtnTreeHover'\" onMouseOut=\"this.className='fontBtnTree'\">
					  <tr>
						<td align=\"center\">
						<p class=\"kotakTree\" align=\"justify\">".$nama."</p></td>
					  </tr>
					</table>
				</button>
				&nbsp;&nbsp;";
	return $html;
}
?>