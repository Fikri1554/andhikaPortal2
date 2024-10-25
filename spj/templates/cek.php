<?php
require_once('../../config.php');
require_once('../configSpj.php');

$kadivEmpNo = $CEmployee->detilDiv($CEmployee->detilTblEmpGen($userEmpNo, "kddiv"), "divhead");// empNo Kadiv
if($kadivEmpNo == $userEmpNo)// jika user berjabatan kadiv, maka kadivempno diisi dengan empno CEO
{
	$kadivEmpNo = "00625";
}
$kdDept = $CEmployee->detilTblEmpGen($userEmpNo, "kddept");
$nmDept = $CEmployee->detilDept($kdDept, "nmdept");
echo  "Divisi : ".$CEmployee->detilDiv($CEmployee->detilTblEmpGen($userEmpNo, "kddiv"), "nmdiv")."<br/>
		Dept. : ".$nmDept."<br/>
		Kadiv Empno - Name : ".$kadivEmpNo." - ".$CSpj->detilLoginSpjByEmpno($kadivEmpNo, "userfullnm", $db);
?>