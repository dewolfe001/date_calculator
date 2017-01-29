<?php

/**
 * @file
 * Used to create the implementation for the data processing application
 */

/**
 * Kickoff the MVC process for the process.
 */

error_reporting(-1);

require_once('DataModel.php');
require_once('DataController.php');
require_once('DataView.php');

$model = new DateModel();
$controller = new DateController($model);
$view = new DateView($controller, $model);

// the default will be to present an empty form

$action = htmlspecialchars(($_GET['action']) ? $_GET['action'] : 'form');
$controller->$action(array(), $_GET); // the first array is for the consistency of passing in preset variables

echo $view->output();