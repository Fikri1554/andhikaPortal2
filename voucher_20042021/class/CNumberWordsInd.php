<?php

/**
 * Class NumberWords
 * - This class convert number to words in Indonesian language
 * 
 * How to use:
 * - construct the class and give number to be converted as parameter $number, if "Rupiah"
 *   want to be added then set parameter $include_currency true
 * - call function get() to get the conversion result
 * 
 * 
 * @version 1.0
 * @author Herry Ramli
 * @since 2009
 * @todo this class can be developed more to support more langguages
 */
class CNumberWordsInd
{
	var $number = 0; // number to be converted
	var $include_currency = false; // flag "Rupiah" is added or not

	/**
	 * Constuctor
	 * @param $number number to be converted
	 * @param $include_currency true if "Rupiah" is added in the result of false if not
	 * @return String number in words
	 */
	function CNumberWordsInd($number, $include_currency=false)
	{
		$this->number = $number;
		$this->include_currency = $include_currency;
	}

	/**
	 * Return the conversion result
	 * 
	 * @param String conversion result
	 */
	function get()
	{
		if ($this->include_currency)
			return CNumberWordsInd::convert($this->number) . " Rupiah";
		else
			return CNumberWordsInd::convert($this->number);
	}
	
	/**
	 * Additional static function
	 */
	static function convert($number)
	{
		if (($number < 0) || ($number > 9999999999991)) 
	    { 
	    throw new Exception("Number is out of range");
	    } 
	
		$Mn = floor( $number / 1000000000 ); /* Billions (giga) */ 
        $number -= $Mn * 1000000000;
	    $Gn = floor($number / 1000000);  /* Millions (giga) */ 
	    $number -= $Gn * 1000000; 
	    $kn = floor($number / 1000);     /* Thousands (kilo) */ 
	    $number -= $kn * 1000; 
	    $Hn = floor($number / 100);      /* Hundreds (hecto) */ 
	    $number -= $Hn * 100; 
	    $Dn = floor($number / 10);       /* Tens (deca) */ 
	    $n = $number % 10;               /* Ones */ 
	
	    $res = ""; 
			
		if ($Mn) 
	    { 
	        $res .= CNumberWordsInd::convert($Mn) . " Milyar"; 
	    } 
		
	    if ($Gn) 
	    { 
			$res .= (empty($res) ? "" : " ") . CNumberWordsInd::convert($Gn) . " Juta"; 
	    } 
	
	    if ($kn) 
	    { 
	        $res .= (empty($res) ? "" : " ") . CNumberWordsInd::convert($kn) . " Ribu"; 
	    } 
	
	    if ($Hn) 
	    { 
	        $res .= (empty($res) ? "" : " ") . CNumberWordsInd::convert($Hn) . " Ratus"; 
	    } 
	
	    $ones = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", 
	        "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas", "Dua Belas", "Tiga Belas", 
	        "Empat Belas", "Lima Belas", "Enam Belas", "Tujuh Belas", "Delapan Belas", 
	        "Sembilan Belas"); 
	    $tens = array("", "Sepuluh", "Dua Puluh", "Tiga Puluh", "Empat Puluh", "Lima Puluh", "Enam Puluh", 
	        "Tujuh Puluh", "Delapan Puluh", "Sembilan Puluh");
	    $thousands = array("", "Seribu", "Dua Ribu", "Tiga Ribu", "Empat Ribu", "Lima Ribu", "Enam Ribu", 
	        "Tujuh Ribu", "Delapan Ribu", "Sembilan Ribu");
	    if ($Dn || $n) 
	    { 
	        if (!empty($res)) 
	        { 
	            $res .= " "; 
	        } 
	
	        if ($Dn < 2) 
	        { 
	            $res .= $ones[$Dn * 10 + $n]; 
	        } 
	        else 
	        { 
	            $res .= $tens[$Dn]; 
	
	            if ($n) 
	            { 
	                $res .= " " . $ones[$n]; 
	            } 
	        } 
	    } 
	
	    if (empty($res)) 
	    { 
	        $res = "nol"; 
	    } 
	
	    if($Hn < 2)
		{
	    	$res = str_ireplace("Satu Ratus", "Seratus", $res);
		}
		if($kn < 2)
		{
	    	$res = str_ireplace("Satu Ribu", "Seribu", $res);
		}
	    
	    return $res; 
	}
}

?>