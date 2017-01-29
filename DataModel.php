<?php

/**
 * @file
 * Used to create the model for the data processing application
 */

/**
 * Verb Noun.
 */

class DateModel {
	public $contents;
	public $variables;
	public $input;

	public function __construct() {
		$this->contents = array();
		$this->variables = array();
		$this->input = array();
	}

	// loads in values used in page generation
	public function page_basics() {
		$variables = $this->variables; // first arg is consdered to be the variables
		$input = $this->input; // second arg is consdered to be the input from the user

		$this->contents['page__content'] = '';
		foreach ($variables as $key => $value) {
			$this->contents[$key] = $value;
		}

		ksort($this->contents); // make sure $this->contents['page__content'] comes in first

		// fill in the empty ones
		if (!isset($this->contents['page_home'])) {
			$this->contents['page_home'] = 'Welcome to the Date Calculator';	
		} 
		if (!isset($this->contents['page_navigation'])) {
			$this->contents['page_navigation'] = ''; // nothing for now	
		} 
		if (!isset($this->contents['page_title'])) {
			$this->contents['page_title'] = 'Date Calculator';	
		}
		if (!isset($this->contents['page_path'])) {
			$this->contents['page_path'] = $_SERVER['REQUEST_URI']; // get the current working path... if not set elsewhere and otherwise 	
		} 
		if (!isset($this->contents['document_path'])) {
			$this->contents['document_path'] = $this->get_path($this->contents['page_path']).'/template/'; // get the current document path 	
		}
	}

	// models the form used in this application
	public function page_form() {
		$variables = $this->variables; // first arg is consdered to be the variables
		$input = $this->input; // second arg is consdered to be the input from the user
		$output = array();

		// the namings for the date fields in case this needs to the swapped to be something dynamic or different later
		$first = 'date_1';
		$second = 'date_2';

		// the lines of html output as we're working with function assumptions that this is a page and a form
		$output[] = '<form action="'.$this->contents['page_path'].'" method="GET">';
		$output[] = '<input type="hidden" name="action" value="'.htmlspecialchars($input['action']).'" />';

		$output[] = $this->check_input($input[$first], $input[$second]);
		$output[] = sprintf('<span class="date_diffs"><input type="datetime" class="date_diffs" name="%s" value="%s" /></span>', $first, htmlspecialchars($input[$first]));				
		$output[] = $this->calculate_difference($input[$first], $input[$second]);
		$output[] = sprintf('<span class="date_diffs"><input type="datetime" class="date_diffs" name="%s" value="%s" /></span>', $second, htmlspecialchars($input[$second]));

		$output[] = '<input type="submit" value="Calculate" />';
		$output[] = '</form>';

		$this->contents['page__content'] = implode("\n", $output); // imploded with line breaks for client side easy reading
	}


	private function get_path($path) {
		// work from the assumption that the current page path ends in a uri / script call. toss that last element.
		$paths = explode("/", $path);
		array_pop($paths);
		return implode("/", $paths);
	}

	// a server side looksee at the inputs
	private function check_input() {
		$args = func_get_args();
		$output = "&nbsp;";

		// if there were multiples this could become a while loop
		if (sizeof($args) > 1) {
			// we have enough to work with
			$first = array_shift($args); // first arg for the date_1 value
			$second = array_shift($args); // second arg for the date_2 value

			// taking the lengths as an indicator that the user made some attempt to input information
			if ((strlen($first) > 2) && (strlen($second) > 2)) {
				$first_date = $this->parse_date($first);
				$second_date = $this->parse_date($second);

				if ((array_key_exists('error', $first_date)) || (array_key_exists('error', $second_date))) { 
					$output = sprintf("try again. \n %s %s", $first_date['error'], $second_date['error']);		
				}
			}
		}

		return '<span class="date_notice">'.$output.'</span>';
	}

	// a server side calculation of the differences
	public function calculate_difference() {
		define ('DAY', 86400);

		$args = func_get_args();
		$output = "&nbsp;";

		// if there were multiples this could become a while loop
		if (sizeof($args) > 1) {
			// we have enough to work with
			$first = array_shift($args); // first arg for the date_1 value
			$second = array_shift($args); // second arg for the date_2 value

			$first_date = $this->parse_date($first);
			$second_date = $this->parse_date($second);

			if ((array_key_exists('date', $first_date)) && (array_key_exists('date', $second_date))) { 
				$diff = abs(strtotime($first_date['date']) - strtotime($second_date['date']));
				$day_diff = floor($diff / DAY);

				$output = sprintf(" is %d days from ", $day_diff);
			}
		} 

		return '<span class="date_diffs">'.$output.'</span>';
	}

	public function parse_date($date) {
		/*
		The form input type is datetime.
		One Firefox, IE and pre-HTML5 capable browsers, the "now" and "today" can be used as input
		*/
		if ($date == "now") {
			$date = date('Y-m-d H:i:s');
		}
		if ($date == "today") {
			$date = date('Y-m-d');
		}

		// the original function to fulfill this role
		// $date_array = date_parse($date);
		
		// look for patterns

		// year-month-day
		$year = false;
		$month = false;
		$day = false;

		if (preg_match('/(\d{4})[ |-](\d{2})[ |-](\d{2})/', $date, $matches)) {
			$year = $matches[1];
			$second = $matches[2];
			$third = $matches[3];

			if (intval($second) > 12) {
				// good guess this is a month then day
				$day = $second;
				$month = $third;
			}
			else {
				// good guess this is a day then month
				$month = $second;
				$day = $third;
			}
		}

		// still don't have something
		if ($month == false) {
			$month_array = array(1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr',
							5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'aug',
							9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dec');
			
			$day_tidy = array(0 => array(1,2,3,4,5,6,7,8,9,0),
							  1 => array("1st","2nd","3rd","4th","5th","6th","7th","8th","9th", "0th"));
			

			if (preg_match('/([\w]+)/', $date, $matches)) {
				$raw_month = $matches[1]." ";
				$short_month = strtolower(substr($raw_month, 0, 3));
				if ($month = array_search($short_month, $month_array)) {
					$date = str_replace($raw_month, "", $date); // remove the month name
					$date = str_replace($day_tidy[1], $day_tidy[0], $date); // remove the suffixes to days
				}
			}

			if (preg_match('/([\d]+)[ |,]+([\d]+)/', $date, $matches)) {
				$first = $matches[1];
				$second = $matches[2];

				if (intval($second) > 31) {
					// good guess this is a day then year
					$day = $first;
					$year = $second;
				}
				else {
					// good guess this is a year then day
					$year = $first;
					$day = $second;
				}
			}
		}

		// get the hours
		if (preg_match('/(\d{2}):(\d{2})/', $date, $matches)) {
			$hour = $matches[1];
			$minute = $matches[2];
		}
		
		// final checks
		if ($day > 31) {
			$day = false;
		}
		if (strlen($year) != 4) {
			$year = false;
		}

		$error = array();
		if (!$year) {
			$error[] = "Missing the year";
		}
		if (!$month) {
			$error[] = "Missing a month";
		}
		if (!$day) {
			$error[] = "Missing the day";
		}
		$error_count = sizeof($error);

		$date_array = array(
							'year' => $year,
							'month' => $month,
							'day' => $day,
							'hour' => $hour,
							'minute' => $minute,
							'second' => 0
							);

		if ($error_count == 0) {
			// print print_r($date_array, TRUE);
			// print print_r(sprintf("%4d-%02d-%02d %02d:%02d:%02d", $date_array['year'], $date_array['month'], $date_array['day'], $date_array['hour'], $date_array['minute'], $date_array['second']), TRUE);

			return array('date' => sprintf("%4d-%02d-%02d %02d:%02d:%02d", $date_array['year'], $date_array['month'], $date_array['day'], $date_array['hour'], $date_array['minute'], $date_array['second']));
		}
		else {
			return array('error' => "<ul><li>".implode("</li><li>", $error)."</li></ul>");
		}
	}

}


?>