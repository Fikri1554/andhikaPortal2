<?php
/*
 * xoauth2_sasl_client.php
 *
 * @(#) $Id: xoauth2_sasl_client.php,v 1.3 2022/10/03 08:50:00 mlemos Exp $
 *
 */

define('SASL_XOAUTH2_STATE_START',             0);
define('SASL_XOAUTH2_STATE_AUTHORIZATION',     1);
define('SASL_XOAUTH2_STATE_DONE',              2);

class xoauth2_sasl_client_class
{
	var $credentials=array();
	var $state=SASL_XOAUTH2_STATE_START;

	Function Initialize(&$client)
	{
		return(1);
	}

	Function Start(&$client, &$message, &$interactions)
	{
		if($this->state!=SASL_XOAUTH2_STATE_START)
		{
			$client->error='XOAUTH2 authentication state is not at the start';
			return(SASL_FAIL);
		}
		$this->credentials=array(
			'user'=>'',
			'token'=>'',
		);
		$defaults=array();
		$status=$client->GetCredentials($this->credentials,$defaults,$interactions);
		if($status==SASL_CONTINUE)
		{
			$this->state=SASL_XOAUTH2_STATE_AUTHORIZATION;
		}
		$message = 'user='.$this->credentials['user'].chr(1).'auth=Bearer '.$this->credentials['token'].chr(1).chr(1);
		return($status);
	}

	Function Step(&$client, $response, &$message, &$interactions)
	{
		switch($this->state)
		{
			case SASL_XOAUTH2_STATE_AUTHORIZATION:
				if($response === '')
					return SASL_OK;
				$oauth_response = @json_decode($response);
				if(GetType($oauth_response) !== 'object')
					$client->error = 'the OAuth token authorization response is not valid: '.strlen($response);
				else
					$client->error = 'OAuth token authorization failed with status code '.$oauth_response->status;
				return SASL_FAIL;
			case SASL_XOAUTH2_STATE_DONE:
				if($response !== '')
				{
					$client->error='XOAUTH2 authentication was finished without success with response: '.$response;
					return(SASL_FAIL);
				}
			default:
				$client->error='invalid XOAUTH2 authentication step state';
				return(SASL_FAIL);
		}
		return(SASL_CONTINUE);
	}
};

?>