<?php
class CHistory
{	
	function CHistory($koneksiMysql, $cpublic)
	{
		$this->koneksi = $koneksiMysql;
		$this->cpublic = $cpublic;
	}
	
	function updateLog($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../../data/history/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLog2($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../data/history/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateConvFoldGagal($CPublic, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../archives/data/documentConvFoldGagal/";
		$file = $CPublic->dateTimeGabung().".txt";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm, $text." \r\n \r\n");
		fclose($cnm);
	}
	
	function updateLogQhse($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../../data/history/qhse/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLog2Qhse($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../data/history/qhse/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLogInvReg($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../../data/history/InvoiceRegister/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLog2InvReg($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../data/history/InvoiceRegister/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLogSurStat($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../../data/history/surveystatus/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLog2SurStat($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../data/history/surveystatus/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLogEmpl($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../../data/history/empl/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLog2Empl($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../data/history/empl/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLogReqAtk($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../../data/history/reqAtk/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLog2ReqAtk($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../data/history/reqAtk/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLogSpj($userId, $text) // turun satu folder keatas root ($dir = "../../data/history/")
	{
		$dir = "../../data/history/spj/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLog2Spj($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../data/history/spj/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLogVoucher($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../../data/history/voucher/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLog2Voucher($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../data/history/voucher/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLogJobRole($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../../data/history/jobRole/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function updateLog2JobRole($userId, $text) // turun satu folder keatas root ($dir = "../data/history/")
	{
		$dir = "../data/history/jobRole/";
		$file = $userId."_".date('Y').".tmp";
		
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function cekFile($userId, $text) 
	{
		$dir = "../data/history/";
		$file = $userId."_".date('Y').".tmp";

		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
	}
	
	function lastDateLog()
	{
		return $this->cpublic->tglServer()." ".$this->cpublic->jamServer();
	}
	
	function logHistory($tahun, $userIdPilih)
	{
		$tabel = "";
		
		$dir = "../../data/history/";
		$file = $userIdPilih."_".$tahun.".tmp";
		if(file_exists($dir.$file))
		{		
			$bukaFile = fopen($dir.$file, "r");
			$isiFile = fread($bukaFile, filesize($dir.$file));
			fclose($bukaFile);
			$tabel.= $isiFile;
		}
		else
		{
			$tabel.= "";
		}

		return $tabel;
	}
	
	function logHistoryInv($tahun, $userIdPilih)
	{
		$tabel = "";
		
		$dir = "../../data/history/InvoiceRegister/";
		$file = $userIdPilih."_".$tahun.".tmp";
		if(file_exists($dir.$file))
		{		
			$bukaFile = fopen($dir.$file, "r");
			$isiFile = fread($bukaFile, filesize($dir.$file));
			fclose($bukaFile);
			$tabel.= $isiFile;
		}
		else
		{
			$tabel.= "";
		}

		return $tabel;
	}
	
	function logHistoryQhse($tahun, $userIdPilih)
	{
		$tabel = "";
		
		$dir = "../../data/history/qhse/";
		$file = $userIdPilih."_".$tahun.".tmp";
		if(file_exists($dir.$file))
		{		
			$bukaFile = fopen($dir.$file, "r");
			$isiFile = fread($bukaFile, filesize($dir.$file));
			fclose($bukaFile);
			$tabel.= $isiFile;
		}
		else
		{
			$tabel.= "";
		}

		return $tabel;
	}
	
	function logHistoryEmpl($tahun, $userIdPilih)
	{
		$tabel = "";
		
		$dir = "../../data/history/empl/";
		$file = $userIdPilih."_".$tahun.".tmp";
		if(file_exists($dir.$file))
		{		
			$bukaFile = fopen($dir.$file, "r");
			$isiFile = fread($bukaFile, filesize($dir.$file));
			fclose($bukaFile);
			$tabel.= $isiFile;
		}
		else
		{
			$tabel.= "";
		}

		return $tabel;
	}
	
	function logHistoryReqAtk($tahun, $userIdPilih)
	{
		$tabel = "";
		
		$dir = "../../data/history/reqAtk/";
		$file = $userIdPilih."_".$tahun.".tmp";
		if(file_exists($dir.$file))
		{		
			$bukaFile = fopen($dir.$file, "r");
			$isiFile = fread($bukaFile, filesize($dir.$file));
			fclose($bukaFile);
			$tabel.= $isiFile;
		}
		else
		{
			$tabel.= "";
		}

		return $tabel;
	}
	
	function logHistorySafir($tahun, $userIdPilih)
	{
		$tabel = "";
		
		$dir = "../../safir/data/history/";
		$file = $userIdPilih."_".$tahun.".tmp";
		if(file_exists($dir.$file))
		{		
			$bukaFile = fopen($dir.$file, "r");
			$isiFile = fread($bukaFile, filesize($dir.$file));
			fclose($bukaFile);
			$tabel.= $isiFile;
		}
		else
		{
			$tabel.= "";
		}

		return $tabel;
	}
	
	function logHistorySpj($tahun, $userIdPilih)
	{
		$tabel = "";
		
		$dir = "../../data/history/spj/";
		$file = $userIdPilih."_".$tahun.".tmp";
		if(file_exists($dir.$file))
		{		
			$bukaFile = fopen($dir.$file, "r");
			$isiFile = fread($bukaFile, filesize($dir.$file));
			fclose($bukaFile);
			$tabel.= $isiFile;
		}
		else
		{
			$tabel.= "";
		}

		return $tabel;
	}
	
	function logHistoryVoucher($tahun, $userIdPilih)
	{
		$tabel = "";
		
		$dir = "../../data/history/voucher/";
		$file = $userIdPilih."_".$tahun.".tmp";
		if(file_exists($dir.$file))
		{		
			$bukaFile = fopen($dir.$file, "r");
			$isiFile = fread($bukaFile, filesize($dir.$file));
			fclose($bukaFile);
			$tabel.= $isiFile;
		}
		else
		{
			$tabel.= "";
		}

		return $tabel;
	}
	
	function logHistorySurStat($tahun, $userIdPilih)
	{
		$tabel = "";
		
		$dir = "../../data/history/surveystatus/";
		$file = $userIdPilih."_".$tahun.".tmp";
		if(file_exists($dir.$file))
		{		
			$bukaFile = fopen($dir.$file, "r");
			$isiFile = fread($bukaFile, filesize($dir.$file));
			fclose($bukaFile);
			$tabel.= $isiFile;
		}
		else
		{
			$tabel.= "";
		}

		return $tabel;
	}
	
	function logHistoryJobRole($tahun, $userIdPilih)
	{
		$tabel = "";
		
		$dir = "../../data/history/jobRole/";
		$file = $userIdPilih."_".$tahun.".tmp";
		if(file_exists($dir.$file))
		{		
			$bukaFile = fopen($dir.$file, "r");
			$isiFile = fread($bukaFile, filesize($dir.$file));
			fclose($bukaFile);
			$tabel.= $isiFile;
		}
		else
		{
			$tabel.= "";
		}

		return $tabel;
	}
	
// #########################################################################
// function2 yang dibawah ini belum dipakai
// #########################################################################
	function logHistoryy($tahun, $userIdPilih)
	{
		$tabel = "";
		
		$dir = "../data/history/";
		$file = $userIdPilih."_".$tahun.".tmp";
		if(file_exists($dir.$file))
		{		
			$bukaFile = fopen($dir.$file, "r");
			$isiFile = fread($bukaFile, filesize($dir.$file));
			fclose($bukaFile);
			$tabel.= $isiFile;
		}
		else
		{
			$tabel.= "";
		}

		return $tabel;
	}	
	
	function updateLogHalPostt($userId, $text)
	{
		$dir = "data/log/";
		$file = "history_".$userId."_".date('Y').".tmp";
		$cnmsize = $dir.$file;
		$cnm = fopen($cnmsize, "a+");
		fwrite($cnm,"<b>[".$this->lastDateLog()."]</b>    ".$text."<br>");
		fclose($cnm);
		
	}	
	
	
}
?>