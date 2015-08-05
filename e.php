<?php

error_reporting(-1);

ini_set('display_errors', 1);

require "gump.class.php";

$data = array(
  "val" => 0
);


echo GUMP::field('test', $data, "sadasd");


//$is_valid = GUMP::is_valid($data, array(
//  'val' => 'required|min_numeric,0.01'
//));

//if($is_valid === true) {
    //echo "valid";
//} else {
  //  print_r($is_valid);
//}
