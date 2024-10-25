<?php
    require_once "src/imapxPHP.php";
	
	set_time_limit(3600);

    $imap = new Imapx();

	$sortBy = array("desc","date");
	
    $inbox = $imap->getInbox(1, 20, $sortBy);
	$totalEmail = $imap->totalEmail();
	
	echo $totalEmail."<br>";
//var_dump($inbox);
	$i = 0;
    foreach ($inbox as $key => $mail) 
	{
        if (isset($mail->subject)) 
		{
			if($i++ == 20)break;  
            echo $i." ".$mail->subject."<br/>";
			echo "<br/>";
        }
    }
?>
