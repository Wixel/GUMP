# Getting started

GUMP is a standalone PHP input validation and filtering class.

1. Download GUMP
2. Unzip it and copy the directory into your PHP project directory.

Include it in your project:

<pre>
require "gump.class.php";
</pre>

Methods available:

<pre>
GUMP::xss_clean(array $data); // Strips and encodes unwanted characters
GUMP::sanitize(array $input, $fields = NULL); // Sanitizes data and converts strings to UTF-8 (if available)
GUMP::validate(array $input, array $ruleset); // Validates input data according to the provided ruleset (see example)
GUMP::filter(array $input, array $filterset); // Filters input data according to the provided filterset (see example)
</pre>

#  Complete Working Example

The following example is part of a registration form, the flow should be pretty standard

<pre>
require "gump.class.php";

$_POST = GUMP::sanitize($_POST); // You don't have to sanitize, but it's safest to do so.

$rules = array(
	'username' => 'required|alpha_numeric|max_len,100|min_len,6',
	'password' => 'required|max_len,100|min_len,6',
	'email'    => 'required|valid_email',
	'gender'   => 'required|exact_len,1'
);

$filters = array(
	'username' => 'trim|sanitize_string',
	'password' => 'trim|base64',
	'email'    => 'trim|sanitize_email',
	'gender'   => 'trim'
);

$_POST = GUMP::filter($_POST, $filters);

$validated = GUMP::validate($_POST, $rules);

if($validated !== TRUE)
{
	print_r($validated); // Something went wrong
}
else
{
	// Do something, everything went well
}
</pre>

Return Values
-------------

`GUMP::validate()` returns one of two types:

*AN ARRAY* containing key names and validator names when data does not pass the validation.

You can use this array along with your language helpers to determine what error message to show.

*A BOOLEAN* value of TRUE if the validation was successful.

`GUMP::filter()` returns the exact array structure that was parsed as the `$input` parameter, the only difference would be the filtered data.


Available Validators
--------------------
* required `Ensures the specified key value exists and is not empty`
* valid_email `Checks for a valid email address`
* max_len,n `Checks key value length, makes sure it's not longer than the specified length. n = length parameter.`
* min_len,n `Checks key value length, makes sure it's not shorter than the specified length. n = length parameter.`
* exact_len,n `Ensures that the key value length precisely matches the specified length. n = length parameter.`
* alpha `Ensure only alpha characters are present in the key value (a-z, A-Z)`
* alpha_numeric `Ensure only alpha-numeric characters are present in the key value (a-z, A-Z, 0-9)`
* alpha_dash `Ensure only alpha-numeric characters + dashes and underscores are present in the key value (a-z, A-Z, 0-9, _-)`
* numeric `Ensure only numeric key values`
* integer `Ensure only integer key values`
* boolean `Checks for PHP accepted boolean values, returns TRUE for "1", "true", "on" and "yes"`
* float `Checks for float values`
* valid_url `Check for valid URL or subdomain`
* valid_ip `Check for valid IP address`

Available Filters
-----------------
* sanitize_string `Remove script tags and encode HTML entities, similar to GUMP::xss_clean();`
* urlencode `Encode url entities`
* htmlencode `Encode HTML entities`
* sanitize_email `Remove illegal characters from email addresses`
* sanitize_numbers `Remove any non-numeric characters`
* trim `Remove spaces from the beginning or end of strings`
* base64 `Base64 encode the input`
* sha1 `Encrypt the input with the secure sha1 algorithm`
* md5 `MD5 encode the input`


#  Creating your own validators and filters

1. Open the gump.php class file, scroll to the bottom.
2. Add your custom functions in the designated space ( to keep things clean )

<pre>

// ** ------------------------- Custom Validators ----------------------------- ** //	

// Put yours here

// ** ------------------------- Custom Filters -------------------------------- ** //

// Put yours here

</pre>

Remember to create a protected static method with the correct parameter types.

Running the tests:
------------------

1. Open up your terminal
2. cd [GUMP DIRECTORY]
3. php test.php

Your output should look something like:

<pre>

	BEFORE SANITIZE:

	Array
	(
	    [missing] => 
	    [email] => not a valid email\n\n
	    [max_len] => 1234567890\n\n
	    [min_len] => 1\n\n
	    [exact_len] => 123456\n\n
	    [alpha] => *(^*^*&\n\n
	    [alpha_numeric] => abcdefg12345+\n\n
	    [alpha_dash] => ab&lt;script&gt;alert(1);&lt;/script&gt;cdefg12345-_+\n\n
	    [numeric] => one, two\n\n
	    [integer] => 1,003\n\n
	    [boolean] => this is not a boolean\r\n
	    [float] => not a float\n\n
	    [valid_url] => http://add\n\n
	    [valid_ip] => google.com\n\n
	)

	AFTER SANITIZE:

	Array
	(
	    [missing] => 
	    [email] => not a valid emailnn
	    [max_len] => 1234567890nn
	    [min_len] => 1nn
	    [exact_len] => 123456nn
	    [alpha] => *(^*^*&nn
	    [alpha_numeric] => abcdefg12345+nn
	    [alpha_dash] => abalert(1);cdefg12345-_+nn
	    [numeric] => one, twonn
	    [integer] => 1,003nn
	    [boolean] => this is not a booleanrn
	    [float] => not a floatnn
	    [valid_url] => http://addnn
	    [valid_ip] => google.comnn
	)

	THESE ALL FAIL:

	Array
	(
	    [0] => Array
	        (
	            [field] => missing
	            [rule] => validate_required
	        )

	    [1] => Array
	        (
	            [field] => email
	            [rule] => validate_valid_email
	        )

	    [2] => Array
	        (
	            [field] => max_len
	            [rule] => validate_max_len
	        )

	    [3] => Array
	        (
	            [field] => numeric
	            [rule] => validate_numeric
	        )

	    [4] => Array
	        (
	            [field] => integer
	            [rule] => validate_integer
	        )

	    [5] => Array
	        (
	            [field] => float
	            [rule] => validate_float
	        )

	    [6] => Array
	        (
	            [field] => valid_url
	            [rule] => validate_valid_url
	        )

	    [7] => Array
	        (
	            [field] => valid_ip
	            [rule] => validate_valid_ip
	        )

	)

	THESE ALL SUCCEED:

	Array
	(
	    [missing] => This is not missing
	    [email] => sean@wixel.net
	    [max_len] => 1
	    [min_len] => 1234
	    [exact_len] => 1234567890
	    [alpha] => abcdefg
	    [alpha_numeric] => abcdefg12345
	    [alpha_dash] => abcdefg12345-_
	    [numeric] => 2
	    [integer] => 3
	    [boolean] => 
	    [float] => 10.1
	    [valid_url] => http://wixel.net
	    [valid_ip] => 69.163.138.62
	)

	DONE


</pre>
