--TEST--
Test basic calculation test of the data model function, parse_date
--FILE--
<?php

require_once('DataModel.php');
$model = new DateModel();
echo "testing \$model->parse_date()\n";
var_dump($model->parse_date('April 26, 2017'));
var_dump($model->parse_date('today'));
var_dump($model->parse_date('2009-22-12'));
var_dump($model->parse_date('April 26, 2017 12:00'));
var_dump($model->parse_date('2009-22-12 12:00'));
var_dump($model->parse_date('r2-d2'));
var_dump($model->parse_date(''));
var_dump($model->parse_date(array()));
echo "Done\n";
?>
--EXPECTF-- 
testing  model->parse_date()
array(1) {
  ["date"]=>
  string(19) "2017-04-26 00:00:00"
}
array(1) {
  ["date"]=>
  string(19) "2017-01-29 00:00:00"
}
array(1) {
  ["date"]=>
  string(19) "2009-12-22 00:00:00"
}
array(1) {
  ["date"]=>
  string(19) "2017-04-26 12:00:00"
}
array(1) {
  ["date"]=>
  string(19) "2009-12-22 12:00:00"
}
array(1) {
  ["error"]=>
  string(82) "<ul><li>Missing the year</li><li>Missing a month</li><li>Missing the day</li></ul>"
}
array(1) {
  ["error"]=>
  string(82) "<ul><li>Missing the year</li><li>Missing a month</li><li>Missing the day</li></ul>"
}
array(1) {
  ["error"]=>
  string(82) "<ul><li>Missing the year</li><li>Missing a month</li><li>Missing the day</li></ul>"
}
Done
