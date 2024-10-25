<?php
require_once("../configInvReg.php");

if ( !isset($_REQUEST['term']) )
    exit;

$dblink = mysql_connect('10.0.2.7', 'root', '4ndh1k4') or die( mysql_error() );
mysql_select_db('invoiceregistertes');

$rs = mysql_query("SELECT * FROM mailinvoice WHERE sendervendor2name LIKE '%".mysql_real_escape_string($_REQUEST['term'])."%' AND deletests=0;", $dblink);
$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array('label' => $row['sendervendor2'] .', '. $row['sendervendor2name'], 'value' => $row['sendervendor2']);
    }
}

echo json_encode($data);
flush();

/*$i = 1;
$tabel = "";
$term = trim(strip_tags($_GET['term']));

$json = "[";
$first = true;
//$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT Acctcode, AcctIndo, Acctname FROM AccountCode WHERE Acctname LIKE '%".$term."%' AND SUBSTRING(Acctcode ,1,2) = '12' AND LEN(RTRIM(Acctcode))=5 ORDER BY Acctname ASC;");
//while($row = $koneksiOdbcAcc->odbcFetch($query))
$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM mailinvoice WHERE sendervendor2name LIKE '%".$term."%' AND deletests=0;");
while($row = $CKoneksiInvReg->mysqlFetch($query))
{
	//$sel = "";
	//if($acctCode == $row['Acctcode'])
	//	$sel = "selected";
		
	//$tabel.="<option value=\"".$row['Acctcode']."\" ".$sel.">".$row['Acctname']."</option>";
	//$row['Acctname']=htmlentities(stripslashes($row['Acctname']));
	//$row['Acctcode']=(int)$row['Acctcode'];
	//$row_set[] = $row;//build an array
	if (!$first) { $json .=  ","; } else { $first = false; }
    $json .= "{'value':'".$row['sendervendor2name']."'}";
}
$json.= "]";
echo $json;*/


/*$term = trim(strip_tags($_GET['term']));//retrieve the search term that autocomplete sends

$qstring = "SELECT description as value,id FROM test WHERE description LIKE '%".$term."%'";
$result = mysql_query($qstring);//query the database for entries containing the term

while ($row = mysql_fetch_array($result,MYSQL_ASSOC))//loop through the retrieved values
{
		$row['value']=htmlentities(stripslashes($row['value']));
		$row['id']=(int)$row['id'];
		$row_set[] = $row;//build an array
}
echo json_encode($row_set);//format the array into json data*/

?>