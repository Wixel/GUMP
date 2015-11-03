#!/usr/bin/php -q
<?php
require "../gump.class.php";

$gump = new GUMP();

$data = array(
    'streets' => array('test', '12323'),
    'name' => 'ron '
);

foreach($data as $key => $value){
	
	if(is_array($value)){
						
		foreach($value as $k => $v){
			$val[$k] = 'regex,/^[a-zA-Z]+$/';
		}
		
		$validated_array = GUMP::is_valid($value, $val);		
		
	}else{
		$validated = GUMP::is_valid($data, array(
			$key => 'regex,/^[a-zA-Z]+$/',  
		));
	}
	
}

if($validated_array === true) {
    echo "Good array";
} else {
	echo '<pre>';
    print_r($validated_array );
    echo '</pre>';
}

if($validated === true) {
    echo "Good No array";
} else {
	echo '<pre>';
    print_r($validated);
   echo '</pre>';
}
