<?php
require_once("../../config.php");

$pathFolder="../data/document/";

$ideGet = $_GET['ide'];
$foldSubGet = $_GET['foldSub'];

$ideFold = $CFolder->detilFold($ideGet, "ide");
$name = $CFolder->detilFold($ideGet, "namefold");
$desc = $CFolder->detilFold($ideGet, "descfold");
$fileFold = $CFolder->detilFold($ideGet, "filefold");

$levelFolder="LEVEL".$foldSubGet;
if(is_dir($pathFolder.$levelFolder."/".$fileFold))
{
	$size = $CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold);
}
else
{
	$size = "Not a Folder";
}

$tipeKonten = $CFolder->detilFold($ideGet, "tipekonten");
$idFold = $CFolder->detilFold($ideGet, "idfold");

if($tipeKonten == "folder") // jika tipe konten folder maka ambil jumlah folder yang berada di level setelahnya
{
	$isi = $CFolder->jmlFolder($idFold);
}
else if($tipeKonten == "file") // jika tipe konten file maka ambil jumlah file yang berada di level setelahnya
{
	$isi = $CFile->jmlFile($idFold);
}
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<body bgcolor="#FFFFFF">
<center>
<table cellpadding="0" cellspacing="0" width="70%" height="100%" border="0" class="">
<!--<tr>
	<td align="center" height="40">
    	<table cellpadding="0" cellspacing="0" width="100%">
        <tr>
        	<td align="center"><span class="teksMyFolder">:: DETAIL FOLDER ::</span></td>
        </tr>
        </table>	
    </td>
</tr>-->

<tr><td height="20px;">&nbsp;</td></tr>

<tr valign="top">
	<td align="center">
    	<table cellpadding="0" cellspacing="5" width="95%" class="formInput">
        <tr>
            <td height="20" width="22%">Name</td>
            <td width="78%" style="color:#000080;">&nbsp;<?php echo $name; ?></td>
        </tr>
        <tr>
            <td height="20">Description</td>
            <td style="color:#000080;">&nbsp;<?php echo $desc; ?></td></td>
        </tr>
        <tr>
            <td height="20">Size</td>
            <td style="color:#000080;">&nbsp;<?php echo $CFolder->display_size($size)." (".$size." Bytes)"; ?></td>
        </tr>
        <tr>
            <td height="20">Contents</td>
            <td style="color:#000080;">&nbsp;<?php echo $tipeKonten; ?></td>
        </tr>
        <tr>
            <td height="20">Number of contents</td>
            <td style="color:#000080;">&nbsp;<?php echo $isi; ?></td>
        </tr>
        <tr>
            <td height="20">Employee can access</td>
            <td>
            <p style="color:#000080;font-size:12px;font-family:Tahoma;margin-left:4px;" align="justify">
<?php
			$i = 0;
			$tabel = "";
			$query = $CKoneksi->mysqlQuery("SELECT * FROM tblauthorfold WHERE idefold='".$ideFold."' AND deletests=0");
			while($row = $CKoneksi->mysqlFetch($query))
			{
				$i++;
				if($i == 1)
				{
					$tabel.= $row['nama'];
				}
				else
				{
					$tabel.= ", ".$row['nama'];
				}
			}
			echo $tabel;
?>
			</p>
            </td>
        </tr>
        </table> 
    </td>
</tr>
</table>
</center>
</body>