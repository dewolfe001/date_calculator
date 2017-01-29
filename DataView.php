<?php

/**
 * @file
 * Used to create the view for the data processing application
 */

/**
 * Begin the Date View class
 */

define('FORMAT_HTML', 'html');
// other formats could be added

class DateView {
	private $model;
    private $controller;

    public function __construct($controller,$model) {
        $this->controller = $controller;
        $this->model = $model;
    }

    public function output($format = FORMAT_HTML) {		
		// do theming exercises to the content
		$formatter = 'format_'.$format;
        $output = $this->$formatter();
        return $output;
    }

	public function format_html() {
		$contents = $this->model->contents; // expect an array of values for templating

		/* the elements we will always substitute
			{!page__content} -- have this stitched in first so that following find-replaces could insert into patterns in the content
			{!page_home}
			{!page_navigation}
			{!page_title}
		*/

		// get template file

		$output = $this->load_file('template/basic.html'); // hardwired template call as there is no alterative template in the scope of this project
		foreach ($contents as $key => $value) {
			// allows for the process to elaborated on rather than pushing in an array => array find-replace
			$output = str_replace("{!$key}", $value, $output);
		}
	
		return $output;
	}

	private function load_file($path) {
		if ($output = file_get_contents($path)) {
			return $output;
		}
		else {
			return "load file error";
		}
	}

}

?>