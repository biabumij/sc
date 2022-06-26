<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Index Waktu Class

1. Hari()
2. Tanggal()
3. Bulan()
4. Tahun()
5. Tgl($waktu)
6. Bln($waktu)
7. Thn($waktu)
8. WaktuOri()
9. HariIndo($hr)
10. HariOri($hr)
11. BulanIndo($bln)
12. BulanOri($bln)
13. Konversi($waktu)
14. WestConvertion2($waktu)
15. WestConvertion($date)
16. WaktuIndo()
17. GetMKTIME($date)
18. GetMKTIME2($date)
19. GetMKTIME3($datetime)
20. StandarTime($mktime)

*/

class Waktu
{
	var $hari;
	var $tanggal;
	var $bulan;
	var $tahun;
	var $waktu_ori;
	
	function Hari()
	{
	$this->hari=date("l");
	return $this->hari;
	}
	
	function Tanggal()
	{
	$this->tanggal=date("d");
	return $this->tanggal;
	}
	
	function Bulan()
	{
	$this->bulan=date("m");
	return $this->bulan;
	}
	
	function Tahun()
	{
	$this->tahun=date("Y");
	return $this->tahun;
	}
	
	function Tgl($waktu)
	{
	$tgl=substr($waktu,8,2);
	return $tgl;
	}
	
	function Bln($waktu)
	{
	$bln=substr($waktu,5,2);
	return $bln;
	}
	
	function Thn($waktu)
	{
	$thn=substr($waktu,0,4);
	return $thn;
	}
	
	function WaktuOri()
	{
	$this->waktu_ori=$this->tahun()."-".$this->bulan().-$this->tanggal();
	return $this->waktu_ori;
	}
	
	function HariIndo($hr)
	{
	switch($hr) {
	case "Sunday" :
	$hari="Minggu";
	break;
	case "Monday" :
	$hari="Senin";
	break;
	case "Tuesday";
	$hari="Selasa";
	break;
	case "Wednesday";
	$hari="Rabu";
	break;
	case "Thursday";
	$hari="Kamis";
	break;
	case "Friday";
	$hari="Jumat";
	break;
	case "Saturday";
	$hari="Sabtu";
	break;
	}
	return $hari;
	}
	
	function HariOri($hr)
	{
	switch($hr) {
	case "Sunday" :
	$hari="Sun";
	break;
	case "Monday" :
	$hari="Mon";
	break;
	case "Tuesday";
	$hari="Tue";
	break;
	case "Wednesday";
	$hari="Wed";
	break;
	case "Thursday";
	$hari="Thu";
	break;
	case "Friday";
	$hari="Fri";
	break;
	case "Saturday";
	$hari="Sat";
	break;
	}
	return $hari;
	}
	
	function BulanIndo($bln)
	{
	switch($bln) {
	case "01" :
	$bulan="Januari";
	break;
	case "02" :
	$bulan="Februari";
	break;
	case "03" :
	$bulan="Maret";
	break;
	case "04" :
	$bulan="April";
	break;
	case "05" :
	$bulan="Mei";
	break;
	case "06" :
	$bulan="Juni";
	break;
	case "07" :
	$bulan="Juli";
	break;
	case "08" :
	$bulan="Agustus";
	break;
	case "09" :
	$bulan="September";
	break;
	case "10" :
	$bulan="Oktober";
	break;
	case "11" :
	$bulan="Nopember";
	break;
	case "12" :
	$bulan="Desember";
	break;
	}
	return $bulan;
	}

	function BulanWorld($bln)
	{
	switch($bln) {
	case "01" :
	$bulan="January";
	break;
	case "02" :
	$bulan="February";
	break;
	case "03" :
	$bulan="March";
	break;
	case "04" :
	$bulan="April";
	break;
	case "05" :
	$bulan="May";
	break;
	case "06" :
	$bulan="June";
	break;
	case "07" :
	$bulan="July";
	break;
	case "08" :
	$bulan="August";
	break;
	case "09" :
	$bulan="September";
	break;
	case "10" :
	$bulan="October";
	break;
	case "11" :
	$bulan="November";
	break;
	case "12" :
	$bulan="December";
	break;
	}
	return $bulan;
	}
	
	function BulanOri($bln)
	{
	switch($bln) {
	case "01" :
	$bulan="Jan";
	break;
	case "02" :
	$bulan="Feb";
	break;
	case "03" :
	$bulan="Mar";
	break;
	case "04" :
	$bulan="Apr";
	break;
	case "05" :
	$bulan="May";
	break;
	case "06" :
	$bulan="Jun";
	break;
	case "07" :
	$bulan="Jul";
	break;
	case "08" :
	$bulan="Aug";
	break;
	case "09" :
	$bulan="Sept";
	break;
	case "10" :
	$bulan="Oct";
	break;
	case "11" :
	$bulan="Nov";
	break;
	case "12" :
	$bulan="Dec";
	break;
	}
	return $bulan;
	}
	
	function BulanStrToInt($bln)
	{
	switch($bln) {
	case "Jan" :
	$bulan="01";
	break;
	case "Feb" :
	$bulan="02";
	break;
	case "Mar" :
	$bulan="03";
	break;
	case "Apr" :
	$bulan="04";
	break;
	case "May" :
	$bulan="05";
	break;
	case "Jun" :
	$bulan="06";
	break;
	case "Jul" :
	$bulan="07";
	break;
	case "Aug" :
	$bulan="08";
	break;
	case "Sep" :
	$bulan="09";
	break;
	case "Oct" :
	$bulan="10";
	break;
	case "Nov" :
	$bulan="11";
	break;
	case "Dec" :
	$bulan="12";
	break;
	}
	return $bulan;
	}	
	
	function BulanRomawi($bln_string)
	{
		$output=false;
		
		switch($bln_string) {
			case 'Jan' :
			$output='I';
			break;
			
			case 'Feb' :
			$output='II';
			break;		
			
			case 'Mar' :
			$output='III';
			break;			
			
			case 'Apr' :
			$output='IV';
			break;			
			
			case 'May' :
			$output='V';
			break;		
			
			case 'Jun' :
			$output='VI';
			break;		
			
			case 'Jul' :
			$output='VII';
			break;		
			
			case 'Aug' :
			$output='VIII';
			break;			
			
			case 'Sep' :
			$output='IX';
			break;			
			
			case 'Oct' :
			$output='X';
			break;		
			
			case 'Nov' :
			$output='XI';
			break;			
			
			case 'Dec' :
			$output='XII';
			break;								
		}
		
		return $output;
	}
	
	
	function Konversi($waktu)
	{
	$tgl=$this->Tgl($waktu);
	$bln=$this->Bln($waktu);
	$thn=$this->Thn($waktu);
	
	$bulan=$this->BulanIndo($bln);
	$runquery=mysql_query("select dayname('$waktu')hari");
	$data=mysql_fetch_array($runquery);
	$hari=$this->HariIndo($data[hari]);
	
	$konversi="$hari, $tgl $bulan $thn";
	return $konversi;
	}
	
	function WestConvertion2($waktu)
	{
	$tgl=$this->Tgl($waktu);
	$bln=$this->Bln($waktu);
	$thn=$this->Thn($waktu);
	
	$bulan=$this->BulanOri($bln);
	$runquery=mysql_query("select dayname('$waktu')hari");
	$data=mysql_fetch_array($runquery);
	$hari=$this->HariOri($data[hari]);
	
	$konversi="$hari, $thn $bulan $tgl";
	return $konversi;
	}
	
	function WestConvertion($date)
	{
		$time=$this->GetMKTIME2($date);
		$time=date('l, j F Y', $time);
		return $time;
	}
	
	function WaktuIndo()
	{
	$hr=$this->Hari();
	$bln=$this->Bulan();
	
	$hari=$this->HariIndo($hr);
	$bulan=$this->BulanIndo($bln);
	
	$waktu_indo=$hari.", ".$this->tanggal()." ".$bulan." ".$this->tahun();
	return $waktu_indo;
	}

	function ProjectDate($date)
	{
		$tgl=$this->Tgl($date);
		$bln=$this->Bln($date);
		$thn=$this->Thn($date);
		return $tgl.'-'.$bln.'-'.$thn;
	}

	function InvoiceDate($date)
	{
		$tgl=$this->Tgl($date);
		$bln=$this->Bln($date);
		$thn=$this->Thn($date);
		return $tgl.'/'.$bln.'/'.$thn;
	}
	

	function ConvertAfterDay($day)
	{	
		$convert = false;
		if($day == 1){
			$convert = 'st';
		}else if($day == 2){
			$convert = 'nd';
		}else if($day == 3){
			$convert = 'rd';
		}else {
			$convert = 'th';
		}

		return $day.$convert;
	}

	function ProposalDate($date)
	{
		$output = false;

		$date_new = explode("/", $date);
		$output = $date_new[2].'-'.$date_new[1].'-'.$date_new[0];

		return $output;
	}

	function ProposalDateReturn($date)
	{
		$output = false;

		$date_new = explode("-", $date);
		$output = $date_new[2].'/'.$date_new[1].'/'.$date_new[0];

		return $output;
	}

	function ProposalDateShow($date)
	{
		$output = false;

		$date_new = explode("-", $date);
		$output = $date_new[2].'.'.$date_new[1].'.'.$date_new[0];

		return $output;
	}

	function BlogDate($date)
	{
		$tgl=$this->Tgl($date);
		$bln=$this->Bln($date);
		$thn=substr($this->Thn($date), 0,4);
		return $tgl.'/'.$bln.'/'.$thn;
	}


	function BlogDate2($date)
	{
		$tgl=$this->Tgl($date);
		$bln=$this->Bln($date);
		$thn=$this->Thn($date);
		return $this->ConvertAfterDay($tgl).', '.$this->BulanWorld($bln).', '.$thn;
	}

	function BlogDate3($date)
	{
		$tgl=$this->Tgl($date);
		$bln=$this->BulanOri($this->Bln($date));
		// $bln=;
		$thn=substr($this->Thn($date), 0,4);

		return $tgl.' '.$bln.' '.$thn;
	}

	function BlogDate4($date)
	{
		$tgl=$this->Tgl($date);
		$bln=$this->BulanIndo($this->Bln($date));
		// $bln=;
		$thn=substr($this->Thn($date), 0,4);

		return $tgl.' '.$bln.' '.$thn;
	}
	
	function GetMKTIME($date)
	{
		$mktime=false;
		$explode=explode('-',$date);
		$date=$explode[2];
		$month=$explode[1];
		$year=$explode[0];
		
		$mktime=mktime(0,0,0,$month,$date,$year);
		
		return $mktime;
	}
	
	function GetMKTIME2($date)
	{
		$mktime=false;
		
		if($date != '0000-00-00 00:00:00') {
			// 2012-12-22 12:22:12
			$hour=substr($date,11,2);
			$minute=substr($date,14,2);
			$second=substr($date,17,2);
			
			$month=substr($date,5,2);
			$day=substr($date,8,2);
			$year=substr($date,0,4);	
			
			$mktime=mktime($hour,$minute,$second,$month,$day,$year);
		}
		
		return $mktime;
	}
	
	function GetMKTIME3($datetime)
	{
		$mktime=false;
		
		if($datetime != '0000-00-00 00:00:00') {
			$arr=explode(' ',$datetime);
			$date=$arr[0];
			$time=$arr[1];
			
			// arrdate
			$arrdate=str_replace('&#45;','-',$date);
			$arrdate=explode('-',$arrdate);
			$year=$arrdate[0];
			$month=$arrdate[1];
			$day=$arrdate[2];
			
			// arrtime
			$arrtime=explode(':',$time);
			$hour=$arrtime[0];
			$minute=$arrtime[1];
			$second=$arrtime[2];		
			
			$mktime=mktime($hour,$minute,$second,$month,$day,$year);
		}
		
		return $mktime;
	}
	
	function StandarTime($mktime)
	{
		$output=false;
		
		if(!empty($mktime)) {
			$output=date('d F Y, H:i:s',$mktime);
			$output=str_replace('January','Jan',$output);
			$output=str_replace('February','Feb',$output);
			$output=str_replace('March','Mar',$output);
			$output=str_replace('April','Apr',$output);
			$output=str_replace('May','Mei',$output);
			$output=str_replace('June','Jun',$output);
			$output=str_replace('July','Jul',$output);
			$output=str_replace('August','Agu',$output);
			$output=str_replace('September','Sep',$output);
			$output=str_replace('October','Okt',$output);
			$output=str_replace('November','Nov',$output);
			$output=str_replace('December','Des',$output);
		}
		
		return $output;
	}

	function StandarTime2($mktime)
	{
		$output=false;
		
		if(!empty($mktime)) {
			$output=date('d F Y',$mktime);
			$output=str_replace('January','Jan',$output);
			$output=str_replace('February','Feb',$output);
			$output=str_replace('March','Mar',$output);
			$output=str_replace('April','Apr',$output);
			$output=str_replace('May','Mei',$output);
			$output=str_replace('June','Jun',$output);
			$output=str_replace('July','Jul',$output);
			$output=str_replace('August','Agu',$output);
			$output=str_replace('September','Sep',$output);
			$output=str_replace('October','Okt',$output);
			$output=str_replace('November','Nov',$output);
			$output=str_replace('December','Des',$output);
		}
		
		return $output;
	}
	
	function StandarTime3($mktime)
	{
		$output=false;
		
		if(!empty($mktime)) {
			$output=date('d F Y, H:i:s',$mktime);
			$output=str_replace('January','Januari',$output);
			$output=str_replace('February','Februari',$output);
			$output=str_replace('March','Maret',$output);
			$output=str_replace('April','April',$output);
			$output=str_replace('May','Mei',$output);
			$output=str_replace('June','Juni',$output);
			$output=str_replace('July','Juli',$output);
			$output=str_replace('August','Agustus',$output);
			$output=str_replace('September','September',$output);
			$output=str_replace('October','Oktober',$output);
			$output=str_replace('November','November',$output);
			$output=str_replace('December','Desember',$output);
		}
		
		return $output;
	}
	
	function SelectHour($fieldname,$active=false)
	{
		$selected=false;
		?>
		<select name="<?php echo $fieldname;?>" class="input-style">
			<?php
			for($i=0;$i<=23;$i++) {
				if(strlen($i) == 1) {
					$i='0'.$i;
				}
				
				if(!empty($active)) {
					if($i == $active) {
						$selected='selected';
					}else{
						$selected=false;
					}				
				}				
				?>
				<option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $i;?></option>
				<?php
			}
			?>
		</select>
		<?php
	}
	
	function SelectMinute($fieldname,$active=false)
	{
		$selected=false;
		?>
		<select name="<?php echo $fieldname;?>" class="input-style">
			<?php
			for($i=0;$i<=59;$i++) {
				if(strlen($i) == 1) {
					$i='0'.$i;
				}
				
				if(!empty($active)) {
					if($i == $active) {
						$selected='selected';
					}else{
						$selected=false;
					}				
				}
				?>
				<option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $i;?></option>
				<?php
			}
			?>
		</select>
		<?php
	}	
	
	function SelectSecond($fieldname,$active=false)
	{
		$selected=false;
		?>
		<select name="<?php echo $fieldname;?>" class="input-style">
			<?php
			for($i=0;$i<=59;$i++) {
				if(strlen($i) == 1) {
					$i='0'.$i;
				}
				
				if(!empty($active)) {
					if($i == $active) {
						$selected='selected';
					}else{
						$selected=false;
					}
				}
				?>
				<option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $i;?></option>
				<?php
			}
			?>
		</select>
		<?php
	}	
	
	function ExplodeDateTime($datetime)
	{
		$output=false;
		
		$mktime=$this->GetMKTIME2($datetime);
		
		$year=date('Y',$mktime);
		$month=date('m',$mktime);
		$day=date('d',$mktime);
		
		$hour=date('H',$mktime);
		$minute=date('i',$mktime);
		$second=date('s',$mktime);
		
		$output['year']=$year;
		$output['month']=$month;
		$output['day']=$day;
		
		$output['hour']=$hour;
		$output['minute']=$minute;
		$output['second']=$second;
		
		return $output;
	}
	
	function GetRemain($end_date)
	{
		$output=false;
		
		$thisdate=time();
		if($end_date != '0000-00-00 00:00:00') {
			$end_second=$this->GetMKTIME2($end_date);					
			$remain=$end_second - $thisdate;
			$remain=floor($remain/86400);		
			
			$output=$remain;
		}
		
		return $output;
	}

	function SelectTgl($id=false)
	{
      	for ($i=1; $i <= 31; $i++) { 

      		if($id == $i){
      			$active = 'selected';
      		}else {
      			$active = false;
      		}
      		?>
      		<option value="<?php echo $i;?>" <?php echo $active;?>><?php echo $i;?></option>
      		<?php
      	}
	}


	function SelectBulan($id=false)
	{
		for ($i=1; $i <= 12; $i++) { 

      		if($id == $i){
      			$active = 'selected';
      		}else {
      			$active = false;
      		}
      		?>
      		<option value="<?php echo $i;?>" <?php echo $active;?>><?php echo $i;?></option>
      		<?php
      	}
	}

	function SelectTahun($id=false)
	{
		$first_year = (int)date("Y") - 40;
		$last_year = $first_year + 40;
		for ($i=$first_year; $i <= $last_year; $i++) { 

      		if($id == $i){
      			$active = 'selected';
      		}else {
      			$active = false;
      		}
      		?>
      		<option value="<?php echo $i;?>" <?php echo $active;?>><?php echo $i;?></option>
      		<?php
      	}
	}

}
?>
