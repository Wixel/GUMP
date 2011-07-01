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
# Note that filters and validators are separate rule sets and method calls. There is a good reason for this. 

require "gump.class.php";

$_POST = GUMP::sanitize($_POST); // You don't have to sanitize, but it's safest to do so.

$rules = array(
	'username'    => 'required|alpha_numeric|max_len,100|min_len,6',
	'password'    => 'required|max_len,100|min_len,6',
	'email'       => 'required|valid_email',
	'gender'      => 'required|exact_len,1',
	'credit_card' => 'trim|valid_cc',
);

$filters = array(
	'username' 	  => 'trim|sanitize_string',
	'password'	  => 'trim|base64',
	'email'    	  => 'trim|sanitize_email',
	'gender'   	  => 'trim',
	'bio'		  => 'noise_words'
);

$validated = GUMP::validate(
	GUMP::filter($_POST, $filters), $rules
);

if($validated === TRUE)
{
	// Do something, everything went well
}
else
{	
	print_r($validated); // Something went wrong
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
* url_exists `Check to see if the url exists and is accessible`
* valid_ip `Check for valid IP address`
* valid_cc `Check for a valid credit card number (Uses the MOD10 Checksum Algorithm)`

Available Filters
-----------------
Filters can be any PHP function that returns a string. You don't need to create your own if a PHP function exists that does what you want the filter to do.

* sanitize_string `Remove script tags and encode HTML entities, similar to GUMP::xss_clean();`
* urlencode `Encode url entities`
* htmlencode `Encode HTML entities`
* sanitize_email `Remove illegal characters from email addresses`
* sanitize_numbers `Remove any non-numeric characters`
* trim `Remove spaces from the beginning or end of strings`
* base64_encode `Base64 encode the input`
* base64_decode `Base64 decode the input`
* sha1 `Encrypt the input with the secure sha1 algorithm`
* md5 `MD5 encode the input`
* noise_words `Remove noise words from string`
* json_encode `Create a json representation of the input` 
* json_decode `Decode a json string` 
* rmpunctuation `Remove all known puncutation characters from a string`
* translate `Translate the input string to any desired language eg. From English -> Spanish: translate,en,es`

#  Creating your own validators and filters

Simply create your own class that extends the GUMP class.

<pre>
	
require("gump.class.php");

class MyClass extends GUMP
{
	public static function filter_myfilter($value)
	{
		...
	}
	
	public static function validate_myvalidator($field, $input, $param = NULL)
	{
		...
	}
	
} // EOC

$validated = MyClass::validate($_POST, $rules);

</pre>

Remember to create a protected static methods with the correct parameter types and counts.

For filter methods, prepend the method name with "filter_".
For validator methods, prepend the method name with "validate_".

Running the examples:
------------------

1. Open up your terminal
2. cd [GUMP DIRECTORY/examples]
3. php [file].php

The output will depend on the input data.

# TODO

* An in array match method that allows to check for pre-defined values
* A currency validator
* An address validator
* A country validator
* Location co-ordinates validator
* HTML validator
* Language validation ... determine if a piece of text is a specified language
* Validate a spam domain or IP.
* Validate a spam email address
* Validate spam text with askimet or something similar
* Improve documentation
* More examples
* W3C validation filter?
* A filter that integrates with an HTML tidy service?: http://infohound.net/tidy/
* Add a twitter & facebook profile url validator: http://stackoverflow.com/questions/2845243/check-if-twitter-username-exists
* Add more logical examples - log in form, profile update form, blog post form, etc etc.