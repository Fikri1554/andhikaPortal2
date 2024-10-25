<?php
require_once("../../config.php");

$allFolderGet = $_GET['allFolder'];
$allFileGet = $_GET['allFile'];

$userIdSelect = $_GET['userIdSelect'];
$idFoldRefGet = $_GET['idFoldRef'];

?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/main.js"></script>

<style>

</style>

<script>
function cekFold(urutan) {
    document.getElementById('cekFold' + urutan).click();

    var idBykAllFolder = document.getElementById('idBykAllFolder').value;
    var isiAllCekFolder = "";
    var isiAllCekIdFoldSource = "";

    for (var i = 0; i <= (idBykAllFolder - 2); i++) {
        if (document.getElementById('cekFold' + i).checked == true) {
            isiAllCekFolder += document.getElementById('idTdNamaFold' + i).innerHTML + "*****";
            isiAllCekIdFoldSource += document.getElementById('cekIdFold' + i).value + "-";
        } else {
            isiAllCekFolder += "";
            isiAllCekIdFoldSource += "";
        }
    }

    isiAllCekFolder = isiAllCekFolder.replace(/%/g, '%25').replace(/'/g, '%27').replace(/#/g, '%23').replace(/&amp;/g,
        '%26').replace(/\?/g, '%3F');
    parent.document.getElementById('allCekFolder').value = isiAllCekFolder;
    parent.document.getElementById('allCekIdFoldSource').value = isiAllCekIdFoldSource;
}

function cekFile(urutan) {
    document.getElementById('cekFile' + urutan).click();
}

//str.replace(/[#_]/g,'')
//search(/\\|\/|:|\*|\?|"|<|>|\|/i)

this.window.onload =
    function() {
        setTimeout(function() {
            parent.document.getElementById('errorMsg').innerHTML = "&nbsp;";
        }, 1000);

    }
</script>

<body onLoad="loadScroll('foldSourceList');" onUnload="saveScroll('foldSourceList');">

    <table width="500%">
        <?php

$folderPisah = explode("*****", $allFolderGet);
$bykAllFolder = sizeof($folderPisah);

$filePisah = explode("*****", $allFileGet);
$bykAllFile = sizeof($filePisah);

if($aksiGet == "ambilFolder")
{
	$urutanIdFold = 0;
	$idFold = "";
	$a = 1;
	for($i = 0; $i < ($bykAllFolder-1); $i++)
	{
		$urutanIdFold++;
		$idFold = $idFoldRefGet.".".$urutanIdFold;
		
		$pathNamaFolder = str_replace("-----", "#" ,$folderPisah[$i]);
	?>


        <tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';"
            onClick="cekFold('<?php echo $i; ?>');">
            <td class="tdMyFolder">
                <table width="100%">
                    <tr class="fontMyFolderList">
                        <td width="20" align="center"><b><?php echo $a++; ?></b></td>
                        <td width="10" align="center">
                            <input type="checkbox" id="cekFold<?php echo $i; ?>">
                            <input type="hidden" id="cekIdFold<?php echo $i; ?>" value="<?php echo $idFold; ?>">
                        </td>
                        <td width="10" align="center">
                            <img src="../../../picture/folder-horizontal.png" height="17" />
                        </td>
                        <td id="idTdNamaFold<?php echo $i; ?>"><?php echo $pathNamaFolder; ?></td>
                    </tr>
                </table>
            </td>
        </tr>

        <?php
	}
	
	
	for($a = 0; $a < ($bykAllFile-1); $a++)
	{
		$namaFile = str_replace("-----", "#" ,$filePisah[$a]);
	?>

        <tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';"
            onClick="cekFile('<?php echo $a; ?>');">
            <td class="tdMyFolder">
                <table width="100%">
                    <tr class="fontMyFolderList">
                        <td width="10" align="center">
                            <input type="checkbox" id="cekFile<?php echo $a; ?>">
                        </td>
                        <td width="10" align="center">
                            <img src="../../../picture/document.png" height="17" />
                        </td>
                        <td id="idTdNamaFile<?php echo $a; ?>"><?php echo $namaFile; ?></td>
                    </tr>
                </table>
            </td>
        </tr>

        <?php
	}
}

?>
        <input type="hidden" id="idBykAllFolder" value="<?php echo $bykAllFolder; ?>">
    </table>
</body>