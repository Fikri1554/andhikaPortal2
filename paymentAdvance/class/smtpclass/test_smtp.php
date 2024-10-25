<?php
	require("smtp.php");
	require("sasl.php");

	$from="admin@andhika.com";
	$to="lana.sajalah@gmail.com";
	
	if(strlen($from)==0)
		die("Please set the messages sender address in line ".$sender_line." of the script ".basename(__FILE__)."\n");
	if(strlen($to)==0)
		die("Please set the messages recipient address in line ".$recipient_line." of the script ".basename(__FILE__)."\n");

	$smtp=new smtp_class;

	$smtp->host_name="mail.andhika.com";
	$smtp->host_port=25;
	$smtp->ssl=0;                                                  /* Change this variable if the SMTP server requires an secure connection using SSL */

	$smtp->start_tls=0;                                            /* Change this variable if the SMTP server requires security by starting TLS during the connection */
	$smtp->cryptographic_method = STREAM_CRYPTO_METHOD_TLS_CLIENT; /* Change this variable if the SMTP server requires the use of a specific version of SSL or TLS when establishing secure connections */
	$smtp->localhost="localhost";                                  /* Your computer address */
	$smtp->direct_delivery=0;                                      /* Set to 1 to deliver directly to the recepient SMTP server */
	$smtp->timeout=500;                                             /* Set to the number of seconds wait for a successful connection to the SMTP server */
	$smtp->data_timeout=0;                                         /* Set to the number seconds wait for sending or retrieving data from the SMTP server.
	                                                                  Set to 0 to use the same defined in the timeout variable */
	$smtp->debug=0;                                                /* Set to 1 to output the communication with the SMTP server */
	$smtp->html_debug=0;                                           /* Set to 1 to format the debug output as HTML */
	$smtp->pop3_auth_host="";                                      /* Set to the POP3 authentication host if your SMTP server requires prior POP3 authentication */
	$smtp->user="vis@andhika.com";                          /* Set to the user name if the server requires authetication */
	$smtp->realm="";                                               /* Set to the authetication realm, usually the authentication user e-mail domain */
	$smtp->password="4ndh1k4";                                /* Set to the authetication password */
	$smtp->workstation="";                                         /* Workstation name for NTLM authentication */
	$smtp->authentication_mechanism="";                            /* Specify a SASL authentication method like LOGIN, PLAIN, CRAM-MD5, NTLM, etc..
                                                                      Leave it empty to make the class negotiate if necessary */

	if($smtp->direct_delivery)
	{
		if(!function_exists("GetMXRR"))
		{
			$_NAMESERVERS=array();
			include("getmxrr.php");
		}
	}

	if($smtp->SendMessage(
		$from,
		array(
			$to
		),
		array(
			"From: $from",
			"To: $to",
			"Subject: Testing Manuel Lemos' SMTP class",
			"Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z")
		),
		"Hello $to,\n\nIt is just to let you know that your SMTP class is working just fine.\n\nBye.\n"))
		echo "Message sent to $to OK.\n";
	else
		echo "Could not send the message to $to.\nError: ".$smtp->error."\n";
?>