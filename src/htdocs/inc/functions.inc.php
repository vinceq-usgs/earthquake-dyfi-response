<?php

	print("Now in functions.inc.php");

	// Is this file necessary? response.php is looking for a copy
	// in the template directory

	function romanMmi($decimalMmi) {
		$romans = array('I', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII',
				'IX', 'X');
		$idx = round($decimalMmi);
		return $romans[$idx];
	}

	function prettyLat($numericLat) {
		if($numericLat > 0) {
			return sprintf("%4.2f&deg;N", $numericLat);
		} else {
			return sprintf("%4.2f&deg;S", -1 * $numericLat);
		}
	}

	function prettyLon($numericLon) {
		if($numericLon > 0) {
			return sprintf("%5.2f&deg;E", $numericLon);
		} else {
			return sprintf("%5.2f&deg;W", $numericLon);
		}
	}
	
	function prettyDepth($numericDepth) {
		$pd = $numericDepth . "Km Deep";
		return $pd;
	}
?>
