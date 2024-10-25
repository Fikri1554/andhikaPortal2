<?php
require_once("../../config.php");
$ideGet = $_GET['ide'];

$query = $CKoneksi->mysqlQuery("SELECT * FROM empldoc WHERE ide=".$ideGet." AND deletests=0");
$row = $CKoneksi->mysqlFetch($query);
$filePdf = "data/document/".$row['filedoc'];
//echo $filePdf;
/*//$filePdfGet = $_GET['filePdf'];
$filePdfGet = 

// headers to send your file
header("Content-Type: pdf");
header('Content-Disposition: attachment; filename='.ucwords(strtolower($filePdfGet)).'.pdf');

// upload the file to the user and quit
readfile("../data/Andhika Portal Manual Book Rev 1.1.pdf");
exit;*/
?>
<script>
function zoomDoc()
{
	var width = screen.width - 300;
	var height = screen.height - 150;
	 var leftPosition, topPosition;
    //Allow for borders.
    leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
    //Allow for title and status bars.
    topPosition = (window.screen.height / 2) - ((height / 2) + 50);
    //Open the window.
    window.open("<?php echo $filePdf;?>", "Window2",
    "status=no,height=" + height + ",width=" + width + ",resizable=yes,left="
    + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY="
    + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
}
</script>
<iframe src="<?php echo "../".$filePdf;?>" height="100%" width="100%" frameborder="0" scrolling="no"></iframe>