<?php
error_reporting(-1);
ini_set('display_errors', 1);

require "gump.class.php";

$validator = new GUMP();

$rules = array(
    'missing'				=> 'required',
    'email'					=> 'valid_email',
    'max_len'				=> 'max_len,1',
    'min_len'				=> 'min_len,4',
    'exact_len'				=> 'exact_len,10',
    'alpha'					=> 'alpha',
    'alpha_numeric'			=> 'alpha_numeric',
    'alpha_dash'			=> 'alpha_dash',
    'alpha_numeric_space'	=> 'alpha_numeric',
    'alpha_space'			=> 'alpha_space',
    'numeric'				=> 'numeric',
    'integer'				=> 'integer',
    'boolean'				=> 'boolean',
    'float'					=> 'float',
    'valid_url'				=> 'valid_url',
    'url_exists'			=> 'url_exists',
    'valid_ip'				=> 'valid_ip',
    'valid_ipv4'			=> 'valid_ipv4',
    'valid_ipv6'			=> 'valid_ipv6',
    'valid_name'			=> 'valid_name',
    'contains'				=> 'contains,free pro basic',
    'array_size_equal'		=> 'valid_array_size_equal,2',
    'array_size_greater'	=> 'valid_array_size_greater,2',
    'array_size_lesser'		=> 'valid_array_size_lesser,2'
);

$invalid_data = array(
    'missing'               => '',
    'email'                 => "not a valid email\r\n",
    'max_len'               => "1234567890",
    'min_len'               => "1",
    'exact_len'             => "123456",
    'alpha'                 => "*(^*^*&",
    'alpha_numeric'         => "abcdefg12345+\r\n\r\n\r\n",
    'alpha_dash'            => "ab<script>alert(1);</script>cdefg12345-_+",
    'alpha_numeric_space'   => 'abcdefg12345_$^%%&TGY',
    'alpha_space'           => 'abcdefg12345 ',
    'numeric'               => "one, two\r\n",
    'integer'               => "1,003\r\n\r\n\r\n\r\n",
    'boolean'               => "this is not a boolean\r\n\r\n\r\n\r\n",
    'float'                 => "not a float\r\n",
    'valid_url'             => "\r\n\r\nhttp://add",
    'url_exists'            => "http://asdasdasd354.gov",
    'valid_ip'              => "google.com",
    'valid_ipv4'            => "google.com",
    'valid_ipv6'            => "google.com",
    'valid_name'            => '*&((*S))(*09890uiadaiusyd)',
    'contains'              => 'premium',
    'array_size_equal'      => array("1"),
    'array_size_greater'    => array("1"),
    'array_size_lesser'     => array("1","2","3")
);

$valid_data = array(
    'missing'               => 'This is not missing',
    'email'                 => 'sean@wixel.net',
    'max_len'               => '1',
    'min_len'               => '1234',
    'exact_len'             => '1234567890',
    'alpha'                 => 'ÈÉÊËÌÍÎÏÒÓÔasdasdasd',
    'alpha_numeric'         => 'abcdefg12345',
    'alpha_dash'            => 'abcdefg12345-_',
    'alpha_numeric_space'   => 'abcdefg12345 ',
    'alpha_space'           => 'abcdefg ',
    'numeric'               => 2.00,
    'integer'               => 3,
    'boolean'               => FALSE,
    'float'                 => 10.10,
    'valid_url'             => 'https://wixelhq.com',
    'url_exists'            => 'https://wixelhq.com',
    'valid_ip'              => '69.163.138.23',
    'valid_ipv4'            => "255.255.255.255",
    'valid_ipv6'            => "2001:0db8:85a3:08d3:1319:8a2e:0370:7334",
    'valid_name'            => 'Sean Nieuwoudt',
    'contains'              => 'free',
    'array_size_equal'      => array("1","2"),
    'array_size_greater'    => array("1","2","3"),
    'array_size_lesser'     => array("1")
);

echo "\nBEFORE SANITIZE:\n\n";
print_r($invalid_data);

echo "\nAFTER SANITIZE:\n\n";
print_r($validator->sanitize($invalid_data));

echo "\nTHESE ALL FAIL:\n\n";
$validator->validate($invalid_data, $rules);

// Print out the errors using the new get_readable_errors() method:
print_r($validator->get_readable_errors());

if($validator->validate($valid_data, $rules)) {
    echo "\nTHESE ALL SUCCEED:\n\n";
    print_r($valid_data);
}

echo "\nDONE\n\n";
