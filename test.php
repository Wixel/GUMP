<?php
require "gump.class.php";

$input = array(
	'name'     => 'Sean Nieuwoudt',
	'email'    => 'sean@isean.co.za',
	'password' => 'deldialer'
);

GUMP::register('required', function($value, $params = null) { 
	$value = trim($value);

	if(!empty($value)) {
		return $value;
	} else {
		throw new Exception("Value is required");
	}
});

// $c = GUMP::call('required', 'input value', 'params');

// print_r($c);
// );

// GUMP::r('asda', $x);

// $r = GUMP::tokenize('required|exact_len:1|contains:m,f');

$result = GUMP::run($input, array('name' => 'required'));

print_r($result);

// if($result->valid()) {

// } else {
// 	$result->errors('en', array('username'));
// 	$result->errors_for(array(
// 		'username', 'password'
// 	));
// }

// print_r($result);


// $validator = new GUMP();
// $_POST = array(
// 	'persian_name' 	  	        => 'عبدالرحمن شیرزاد',
// 	'valid_eng_per_pas_name' 	=> 'Abdul Rahman Sherzad عبدالرحمن شیرزاد',
// 	'persian_digit' 	  	    => '۰۱۲۳۴۵۶۷۸۹',
// 	'persian_text' 	  	        => 'یک فیلم فوق العاده زیبا و الهام بخش برای همه معلمان و استادان عزیز که زندگی های زیادی را لمس میکنند. سعی کنیم برای دیگران عامل زندگی و امید باشیم',
// 	'pashtu_text' 	  	        => 'د چاه بهار بندر له لارې له هنده افغانستان ته د مرستو لومړنۍ کښتۍ وخوځېده. په دغه کشتۍ کې، له هنده افغانستان ته، ۱۱۰ زره مټرېک ټنه غنم وړل کېږي.',
	
// );

// $rules = array(
// 	'persian_name'    		    => 'valid_persian_name',
// 	'valid_eng_per_pas_name'    => 'valid_eng_per_pas_name',
// 	'persian_digit'    		    => 'valid_persian_digit',
// 	'persian_text'    	        => 'valid_persian_text',
// 	'pashtu_text'    	        => 'valid_pashtu_text',
// );

// $filters = array(
// 	'persian_name' 	  			=> 'trim|sanitize_string',
// 	'valid_eng_per_pas_name' 	=> 'trim|sanitize_string',
// 	'persian_digit' 			=> 'trim|sanitize_string',
// 	'persian_text' 	  	        => 'trim|sanitize_string',
// 	'pashtu_text' 	  	        => 'trim|sanitize_string',
// );

// $validated = $validator->is_valid($_POST, $rules);

// $_POST = $validator->sanitize($_POST);
// $_POST = $validator->filter($_POST, $filters);

// if($validated === TRUE) {
// 	echo "Successful Validation\n\n";
// 	echo "<pre>";
// 	print_r($_POST);
// 	echo "</pre>";
// 	exit;
// } else {
// 	echo "<pre>";
// 	print_r($validated);
// 	echo "</pre>";
// }
