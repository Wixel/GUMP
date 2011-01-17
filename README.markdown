GUMP is a standalone PHP input validation and filtering class.

# Getting started

1. Download GUMP
2. Unzip it and copy the directory into your PHP project directory.

Include it in your project:

<pre>
require "gump.php"
</pre>

Methods available:

<pre>
GUMP::xss_clean(array $data); // Strips and encodes unwanted characters
GUMP::sanitize(array $input, $fields = NULL); // Sanitizes data and converts strings to UTF-8 (if available)
GUMP::validate(array $input, array $ruleset); // Validates input data according to the provided ruleset (see example)
GUMP::filter(array $input, array $filterset) // Filters input data according to the provided filterset (see example)
</pre>

#  Complete Working Example

The following example is part of a registration form, the flow should be pretty standard

<pre>
require "gump.php"

$_POST = GUMP::sanitize($_POST);

$rules = array(
	'username' => 'required|alpha_numeric|max_len,100|min_len,6',
	'password' => 'required|max_len,100|min_len,6',
	'email'    => 'required|valid_email',
	'gender'   => 'required|exact_len,1'
);

$validated = GUMP::validate($_POST, $rules);

if($validated !== TRUE)
{
	print_r($validated);
}
else
{
	// Do something, everything went well
}
</pre>

Available Validators
--------------------
* required `Ensures the specified key value exists and is not empty`
* valid_email `Checks for a valid email address`
* max_len `Checks key value length, makes sure it's not longer than the specified length`
* min_len `Checks key value length, makes sure it's not shorter than the specified length`
* exact_length `Ensures that the key value length precisely matches the specified length`
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
* sha1 `Encode the input with the secure sha1 algorithm`