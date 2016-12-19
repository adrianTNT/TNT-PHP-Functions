<?php

// timestamp to "x days/hours ago" converter
if(!function_exists("ago")){
	function ago($timestamp){
		if(isset($timestamp) and $timestamp !=''){  
			$difference = time() - $timestamp;
			$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
			$lengths = array("60","60","24","7","4.35","12","10");
			for($j = 0; isset($lengths[$j]) && $difference >= $lengths[$j]; $j++){
				$difference /= $lengths[$j]; // <<< line with problem
			}
			$difference = round($difference);
			if($difference != 1) $periods[$j].= "s";
			$text = "$difference $periods[$j] ago";
			return $text;
		}
	}
}
// echo ago(1482124507);

?>
