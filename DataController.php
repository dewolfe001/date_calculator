<?php

/**
 * @file
 * Used to create the controller for the data processing application
 */

/**
 * Build the controller for the date application.
 */


class DateController {
	public $model;

    public function __construct($model) {
        $this->model = $model;
    }

	public function form() {
		$args = func_get_args();
		$this->model->variables = array_shift($args); // first arg is consdered to be the variables
		$this->model->input = array_shift($args); // second arg is consdered to be the input from the user

		// this method sets up page basic values
		$this->model->page_basics();

		// this method builds a form into the page content
		$this->model->page_form();
	}
}


?>