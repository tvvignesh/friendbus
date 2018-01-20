<?php
/**
 *
 * CONTAINS ALL DATE OPERATION FUNCTIONS
 * @author T.V.VIGNESH
 *
 */
class ta_dateoperations
{
	/**
	 *
	 * GET CURRENT DATE IN THE GIVEN FORMAT
	 * @param unknown_type $format FORMAT OF DATE AND TIME TO BE RETURNED
	 * @return string GIVES CURRENT TIMESTAMP IF format="" ELSE gives the date in the given format
	 */
	public function getcurdate($format="d-m-Y h:i:sA")
	{
		$dateobj=new ta_dateoperations();
		$dateobj->setusertimezone();
		if($format=="")return time();
		$res=$dateobj->timestamptodate(time(),$format);
		return $res;
	}

	/**
	 *
	 * Get the user's time zone as an object
	 * @return DateTimeZone
	 */
	public function gettimezone()
	{
		if(!isset($_COOKIE["usrtimezone"]))
		{
			$timezone = new DateTimeZone("Asia/Calcutta");
			return $timezone;
		}
		$offset = $_COOKIE["usrtimezone"];
		$validobj=new ta_filtervalue();
		$offset=$validobj->sanitize_removetags($offset)*3600;
		$abbrarray = timezone_abbreviations_list();
		foreach ($abbrarray as $abbr)
		{
			foreach ($abbr as $city)
			{
				if ($city['offset'] == $offset)
				{
					return $city['timezone_id'];
				}
			}
		}

		return BOOL_FAILURE;
	}

	/**
	 *
	 * Set time passed as parameter according to timezone
	 * @param unknown_type $date Date to be formatted(Format is yyyy-mm-dd hh:mm:ss)
	 * @return DateTime Returns object of datetime
	 */
	public function settime($date)
	{//'2012-04-21 01:13:30'
		$dateobj=new ta_dateoperations();
		$zone=$dateobj->gettimezone();
		if($zone)
		{
			$datetime = new DateTime($date,new DateTimeZone($zone));
			$datetime->setTimezone(new DateTimeZone($zone));
			return $datetime;
		}
		else
			return FAILURE;
	}

	/**
	 *
	 * Converts timestamp to date in the format given
	 * @param unknown_type $timestamp Timestamp passed by the user
	 * @param unknown_type $format Format of date required by the user
	 * @return string Gives the date in the format asked
	 */
	public function timestamptodate($timestamp,$format="d-m-Y h:i:sA")
	{
		$dateobj=new ta_dateoperations();
		$datetime=$dateobj->settime(date("Y-m-d h:i:sA", $timestamp));
		return date($format, $datetime->format('U'));
	}

	/**
	 *
	 * Converts date of any format to Unix timestamp
	 * @param unknown_type $date Date of any format being passed
	 * @return number The timestamp
	 */
	public function datetotimestamp($date)
	{
		return strtotime($date);
	}

	/**
	 *
	 * Gets the difference between 2 dates being passed (end-start)
	 * @param unknown_type $start Start date in any format
	 * @param unknown_type $end End date in any format
	 * @param unknown_type $diffin Type of value required (1-seconds,2-minutes,3-hours,4-days,5-years)
	 * @return Returns the difference as array with y m d hour minute second as keys
	 */
	public function datediff($start,$end)
	{
		$from = new DateTime($start);
		$to   = new DateTime($end);
		$from->diff($to)->y;
		$diff=array("y"=>$from->diff($to)->y,"m"=>$from->diff($to)->m,"d"=>$from->diff($to)->d,"hour"=>$from->diff($to)->h,"minute"=>$from->diff($to)->i,"second"=>$from->diff($to)->s);
		return $diff;
		
// 		if($diffin=="1")
// 		{
// 			return (strtotime($end) - strtotime($start));
// 		}
// 		else
// 			if($diffin=="2")
// 		{
// 			return floor((strtotime($end) - strtotime($start))/60);
// 		}
// 		else
// 			if($diffin=="3")
// 		{
// 			return floor((strtotime($end) - strtotime($start))/3600);
// 		}
// 		else
// 			if($diffin=="4")
// 		{
// 			return floor((strtotime($end) - strtotime($start))/86400);
// 		}
// 		else
// 			if($diffin=="5")
// 		{
// 				return floor((strtotime($end) - strtotime($start))/31536000);
// 		}
	}

	/**
	 *
	 * Get time zone from gmt offset
	 * @param unknown_type $gmtoffset The GMT offset being passed
	 * @param unknown_type $abbr The abbreviation of the Timezone (Defaults to "")
	 * @param unknown_type $daylight Daylight saving (Defaults to 0)
	 * @return string The timezone returned as string
	 */
	public function timezonefromgmt($gmtoffset,$abbr="",$daylight=0)
	{
		$timezone_abbreviations = DateTimeZone::listAbbreviations();
		$gmtoff=$gmtoffset*3600;
		return timezone_name_from_abbr($abbr, $gmtoff, $daylight);
	}

	/**
	 * sets default timezone for the user according to GMT offset
	 */
	public function setusertimezone()
	{
		$dateobj=new ta_dateoperations();
		if(isset($_COOKIE["usrtimezone"]))
		{
			$offset = $_COOKIE["usrtimezone"];
			$validobj=new ta_filtervalue();
			$offset=$validobj->sanitize_removetags($offset);
			$timezone=$dateobj->timezonefromgmt($offset);
			date_default_timezone_set($timezone);
		}
		else
		{
			throw new Exception('#ta@0000000_0000035');
		}
	}
	
	public function usrtimezone_get()
	{
		echo '
		<script type="text/javascript">
		var localTime = new Date();
		var gmtOffset,days;
		days=1;
		gmtOffset = localTime.getTimezoneOffset()/60 * (-1);
		
		var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
		document.cookie = "usrtimezone="+gmtOffset+expires+"; path=/";
		</script>
	';
	}
}
?>