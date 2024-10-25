<?php

ini_set("smtp","mail.andhika.com");
// ini_set("smtp_port","587");
// ini_set("smtp_server","smtp.gmail.com");
ini_set("smtp_port","25");
// ini_set("auth_username","lana.sajalah@gmail.com");
// ini_set("auth_password","****");
// ini_set("force_sender","lana.sajalah@gmail.com");

$msg = "First line of text\nSecond line of text";

// //use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

$headers = "MIME-Version: 1.0\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\n";
$headers .= "X-Priority: 3\n";
$headers .= "X-MSMail-Priority: Normal\n";
$headers .= "X-Mailer: php\n";
$headers .= "From: noreply@andhika.com\n";
		
$emailKe = "<ahmad.maulana@andhika.com>";
$subject = "MY TEST";
$isiMessage = $msg;

mail($emailKe, $subject, $isiMessage, $headers);


?>