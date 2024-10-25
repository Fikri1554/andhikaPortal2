<?php
require_once("../../config.php");

$pathFolder="../data/document/";

$ideGet = $_GET['ide'];
$foldSubGet = $_GET['foldSub'];

$name = $CFile->detilFile($ideGet, "namedoc");
$desc = $CFile->detilFile($ideGet, "descdoc");
$fileDoc = $CFile->detilFile($ideGet, "filedoc");

$idFold = $CFile->detilFile($ideGet, "idfold");
$fileFold = $CFolder->detilFoldByIdFold($idFold, "filefold");
$foldSub = $CFolder->detilFoldByIdFold($idFold, "foldsub");
$levelFolder="LEVEL".$foldSub;

$pathDoc = $CFile->detilFile($ideGet, "pathdoc");
$convFold = $CFile->detilFile($ideGet, "convfold");
if($convFold == "N")
{
	$pathFile = $pathFolder.$levelFolder."/".$fileFold."/".$fileDoc;
}
else if($convFold == "Y")
{
	$pathFile = str_replace("C:/wamp/www/andhikaPortal/archives/", "../", $pathDoc).$fileDoc;
}

$fileSize = filesize($pathFile);

$tipeFile = $CFile->detilFile($ideGet, "extdoc");
$fileOwner = $CLogin->detilLogin($CFile->detilFile($ideGet, "fileowner"), "userfullnm");

$lastUploadByName = $CFile->detilFile($ideGet, "lastuploadbyname");
$lastUploadByName2 = $fileOwner;
if(str_replace(" ", "", $lastUploadByName)  != "")
{
	$lastUploadByName2 = $lastUploadByName;
}

$expWktUpload = explode(" ", $CFile->detilFile($ideGet, "wktupload"));
$expWktUpload1 = explode("-", $expWktUpload[0]);
$expWktUpload2 = $expWktUpload[1];

$tglUpload = $expWktUpload1[2];
$blnUpload = ucfirst( strtolower( $CPublic->bulanSetengah( $expWktUpload1[1], "ind") ) );
$thnUpload = $expWktUpload1[0];

$lastUploadTime = $tglUpload." ".$blnUpload." ".$thnUpload." ".$expWktUpload2;
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
        	<td align="center"><span class="teksMyFolder">:: DETAIL FILE ::</span></td>
        </tr>
        </table>	
    </td>
</tr>-->

<tr><td height="20">&nbsp;</td></tr>

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
            <td style="color:#000080;">&nbsp;<?php echo $CFolder->display_size($fileSize)." (".$fileSize." Bytes)"; ?></td>
        </tr>
        <tr>
            <td height="20">Type file</td>
            <td style="color:#000080;">&nbsp;<?php echo $tipeFile; ?></td>
        </tr>
        <tr>
            <td height="20">File owner</td>
            <td style="color:#000080;">&nbsp;<?php echo $fileOwner; ?></td>
        </tr>
        <tr>
            <td height="20">Last Upload By</td>
            <td style="color:#000080;">&nbsp;<?php echo $lastUploadByName2; ?></td>
        </tr>
        <tr>
            <td height="20">Last Upload Time</td>
            <td style="color:#000080;">&nbsp;<?php echo $lastUploadTime; ?></td>
        </tr>
        <tr>
            <td height="20">Employee can access</td>
            <td>
            <p style="color:#000080;font-size:12px;font-family:Tahoma;margin-left:4px;" align="justify">
<?php
			$ideFold = $CFolder->detilFoldByIdFold($idFold, "ide");
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