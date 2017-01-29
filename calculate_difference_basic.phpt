--TEST--
Test basic calculation test of the data model function, calculate_difference
--FILE--
<?php

require_once('DataModel.php');
$model = new DateModel();
echo "testing \$model->calculate_difference()\n";
var_dump($model->calculate_difference('April 26, 2017', 'April 26, 2016'));
var_dump($model->calculate_difference('today', 'August 6th, 2015'));
var_dump($model->calculate_difference('2009-22-12', 'Feb. 26, 2017 11:00'));
var_dump($model->calculate_difference('April 26, 2017 12:00'));
var_dump($model->calculate_difference('2009-22-12 12:00'));
var_dump($model->calculate_difference('r2-d2','2009-22-12 12:00'));
var_dump($model->calculate_difference(''));
var_dump($model->calculate_difference(array()));

echo "Done\n";
?>
--EXPECTF-- 
testing  model->calculate_difference()
string(50) "<span class="date_diffs"> is 365 days from </span>"
string(50) "<span class="date_diffs"> is 542 days from </span>"
string(51) "<span class="date_diffs"> is 2623 days from </span>"
string(38) "<span class="date_diffs">&nbsp;</span>"
string(38) "<span class="date_diffs">&nbsp;</span>"
string(38) "<span class="date_diffs">&nbsp;</span>"
string(38) "<span class="date_diffs">&nbsp;</span>"
string(38) "<span class="date_diffs">&nbsp;</span>"

----------------
Done
