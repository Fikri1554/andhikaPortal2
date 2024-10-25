<?php
$html.= '<tr>
				<td align="center">
				<table class="fontMyFolderList" cellpadding="0" cellspacing="0" border="0" width="96%">
					<tr>
						<td width="24%" align="center">Menyetujui,</td>
						<td width="52%">&nbsp;</td>
						<td width="24%" align="center">Mengetahui,</td>
					</tr>
					<tr>
						<td colspan="3" height="50px"></td>
					</tr>
					<tr>
						<td width="24%" align="center" style="border-bottom:#000 solid thin;font-weight:bold;">';
						//script 1
							/*if($row['aprvbyadm'] == "N")// Jika sudah Approved
							{
								$html = $CSpj->detilLoginSpjByEmpno($row['aprvempno'], "userfullnm", $db);
							}
							if($row['aprvbyadm'] == "Y")// Jika Approved dari Administrator
							{
								$html = $CSpj->detilLoginSpjByEmpno($row['kadivempno'], "userfullnm", $db);
							}*/
	$html.='					nama'.ucwords(strtolower($html)).'
						</td>
						<td width="52%">&nbsp;</td>
						<td width="24%" align="center" style="border-bottom:#000 solid thin;font-weight:bold;">';
						//script 2
							/*if($row['knowbyadm'] == "N")//jika Sudah Acknowledge
							{
								$know = $CSpj->detilLoginSpjByEmpno($CSpj->detilForm($formIdGet, "knowempno"), "userfullnm", $db);
							}
							if($row['knowbyadm'] == "Y")
							{
								$know = $CSpj->detilLoginSpjByEmpno($CEmployee->detilDiv("050", "divhead"), "userfullnm", $db);
							}*/
	$html.= '					nama'.ucwords(strtolower($know)).'
						</td>
					</tr>
					<tr>
						<td width="24%" align="center">';
						//script3 
							/*$jabatan = "";
							if($row['kadivempno'] == "00625")
							{
								$jabatan = "CEO";
							}
							if($row['kadivempno'] != "00625")
							{
								$jabatan = "Kadiv ".$CSpj->detilDivByDivhead($row['kadivempno'], "nmdiv");
							}*/
	$html.= '					jabatan'.$jabatan.'
						</td>
						<td width="52%">&nbsp;</td>
						<td width="24%" align="center">Kadiv. HR & SUPPORT DIV.</td>
					</tr>
				</table>
				</td>
			</tr>';
?>