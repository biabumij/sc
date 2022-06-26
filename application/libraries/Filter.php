<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Index Filter Class

1. FilterHTML($str)
2. FilterSQL($str)
3. FilterSpecialChar($str)
4. FilterEncode($str)
5. FilterURL($str)
6. FilterInput($str)
7. FilterTextarea($str)

*/

class Filter
{

	function FilterHTML($str)
	{
		$str=strip_tags($str);
		return $str;
	}

	function FilterSQL($str)
	{
		$str=str_replace("'","`",$str);
		$str=mysql_real_escape_string($str);
		return $str;
	}
	
	function FilterSpecialChar($str)
	{
		$str=str_replace("'","`",$str);
		return $str;
	}
	
	function FilterEncode($str)
	{
		$str=utf8_decode($str);
		return $str;
	}
	
	function FilterURL($str)
	{
		$str=str_replace(' ','_',$str);
		$str=str_replace('/','\\',$str);
		$str=$this->FilterInput($str);
		return $str;
	}	
	
	function FilterInput($str)
	{
		$str=$this->FilterEncode($str);		
		$str=$this->FilterHTML($str);
		// $str=$this->FilterSQL($str);
		$str=trim($str);
		return $str;
	}
	
	function FilterTextarea($str)
	{
		$str=$this->filterHTML($str);
		$str=$this->filterSpecialChar($str);
		$str=nl2br($str);
		return $str;
	}
	
	function FilterEmptyArray($array, $remove_null_number = true)
	{
		$new_array = array();
		$null_exceptions = array();
	 
		foreach ($array as $key => $value)
		{
			$value = trim($value);
	 
			if($remove_null_number) {
				$null_exceptions[] = '0';
			}
	 
			if(!in_array($value, $null_exceptions) && $value != "") {
				$new_array[] = $value;
			}
		}
		
	return $new_array;
	
	}	
	
	function FormatCurrency($currency)
	{
		$output=false;
		// Rp. 10.000,-
		
		$output=number_format($currency);
		$output=str_replace(",",".",$output);
		$output='Rp '.$output.',-';
				
		return $output;
	}
	
	function FormatDollar($currency)
	{
		$output=false;
		
		$output='$'.$currency;
				
		return $output;
	}	
	
	function ExcapeXmlSpecialCharacter($str)
	{
		$output=false;

		// &amp;, &lt;, &gt;, &apos; and &quot;
		$output=str_replace('&','&amp;',$str);
		$output=str_replace('<','&lt;',$output);
		$output=str_replace('>','&gt;',$output);
		$output=str_replace("'","&apos;",$output);
		$output=str_replace('"','&quot;',$output);
		
		return $output;
	}
	
	function FilterAjaxRequest()
	{
		$CI =& get_instance();
		if($CI->input->is_ajax_request()) {
			return true;
		}else{
			echo 'Invalid user access...';
			exit();
		}		
	}
	
	function FilterDomain($domain)
	{
		$output=false;
		
		if(!empty($domain)) {
			$valid_domain=strtolower($domain);
			$valid_domain=str_replace('_','-',$valid_domain);
		}
		
		$output=$valid_domain;
		
		return $output;
	}

	function Rupiah($total)
	{

		return number_format($total,2,',','.');
	}

	function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = $this->penyebut($nilai - 10). " belas";
        } else if ($nilai < 100) {
            $temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
        }     
        return $temp;
    }
 
    function terbilang($nilai) {
        if($nilai<0) {
            $hasil = "minus ". trim(strtoupper($this->penyebut($nilai))).' RUPIAH';
        } else {
            $hasil = trim(strtoupper($this->penyebut($nilai))).' RUPIAH';
        }     		
        return $hasil;
    }


    function RupiahToDB($output)
    {

    	$output = str_replace('.', '', $output);
        $output = str_replace(',', '.', $output);
    	return $output;
    }
}

?>