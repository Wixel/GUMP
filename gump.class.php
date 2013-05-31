<?php

/**
 * GUMP - A fast, extensible PHP input validation class
 *
 * @author		Sean Nieuwoudt (http://twitter.com/SeanNieuwoudt)
 * @copyright	Copyright (c) 2011 Wixel.net
 * @link		http://github.com/Wixel/GUMP
 * @version     1.0
 */

class GUMP
{	
	// Validation rules for execution
	protected $validation_rules = array();
	
	// Filter rules for execution
	protected $filter_rules = array(); 
	
	// Instance attribute containing errors from last run
	protected $errors = array();
	
	// ** ------------------------- Validation Data ------------------------------- ** //	
	
	public static $basic_tags	  = "<br><p><a><strong><b><i><em><img><blockquote><code><dd><dl><hr><h1><h2><h3><h4><h5><h6><label><ul><li><span><sub><sup>";				
		
	public static $en_noise_words = "about,after,all,also,an,and,another,any,are,as,at,be,because,been,before,
				  				  	 being,between,both,but,by,came,can,come,could,did,do,each,for,from,get,
				  				  	 got,has,had,he,have,her,here,him,himself,his,how,if,in,into,is,it,its,it's,like,
			      				  	 make,many,me,might,more,most,much,must,my,never,now,of,on,only,or,other,
				  				  	 our,out,over,said,same,see,should,since,some,still,such,take,than,that,
				  				  	 the,their,them,then,there,these,they,this,those,through,to,too,under,up,
				  				  	 very,was,way,we,well,were,what,where,which,while,who,with,would,you,your,a,
				  				  	 b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,$,1,2,3,4,5,6,7,8,9,0,_";
				
	// ** ------------------------- Validation Helpers ---------------------------- ** //	
	
	/**
	 * Shorthand method for inline validation 
	 *
	 * @param array $data The data to be validated
	 * @param array $validators The GUMP validators
	 * @return mixed True(boolean) or the array of error messages
	 */
	public static function is_valid(array $data, array $validators) 
	{
		$gump = new Gump();

		$gump->validation_rules($validators);

		if($gump->run($data) === false) {
			return $gump->get_readable_errors(false);
		} else {
			return true;
		}	
	}	
	
	/**
	 * Magic method to generate the validation error messages
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->get_readable_errors(true);
	}
	
	/**
	 * Perform XSS clean to prevent cross site scripting
	 * 
	 * @static
	 * @access public
	 * @param  array $data
	 * @return array
	 */
	public static function xss_clean(array $data)
	{
		foreach($data as $k => $v)
		{
			$data[$k] = filter_var($v, FILTER_SANITIZE_STRING);		
		}
		
		return $data;
	}
	
	/**
	 * Getter/Setter for the validation rules
	 *
	 * @param array $rules
	 * @return array
	 */
	public function validation_rules(array $rules = array())
	{
		if(!empty($rules)) {
			$this->validation_rules = $rules;
		} else {
			return $this->validation_rules;
		}		
	}
	
	/**
	 * Getter/Setter for the filter rules
	 *
	 * @param array $rules
	 * @return array
	 */	
	public function filter_rules(array $rules = array())
	{
		if(!empty($rules)) {
			$this->filter_rules = $rules;
		} else {
			return $this->filter_rules;
		}
	}	
	
	/**
	 * Run the filtering and validation after each other
	 *
	 * @param array $data
	 * @return array
	 * @return boolean
	 */
	public function run(array $data)
	{
		$data = $this->filter($data, $this->filter_rules());
		
		$validated = $this->validate(
			$data, $this->validation_rules()
		);		
		
		if($validated !== true) {
			return false;
		} else {
		   return $data;
		}
	}
	
	/**
	 * Sanitize the input data
	 * 
	 * @access public
	 * @param  array $data
	 * @return array
	 */
	public function sanitize(array $input, $fields = NULL, $utf8_encode = true)
	{
		$magic_quotes = (bool)get_magic_quotes_gpc();
		
		if(is_null($fields))
		{
			$fields = array_keys($input);
		}

		foreach($fields as $field)
		{
			if(!isset($input[$field]))
			{
				continue;
			}
			else
			{
				$value = $input[$field]; 
				
				if(is_string($value))
				{
					if($magic_quotes === TRUE)
					{
						$value = stripslashes($value);
					}
					
					if(strpos($value, "\r") !== FALSE)
					{
						$value = trim($value);
					}
					
					if(function_exists('iconv') && function_exists('mb_detect_encoding') && $utf8_encode)
					{
						$current_encoding = mb_detect_encoding($value);
						
						if($current_encoding != 'UTF-8' && $current_encoding != 'UTF-16') {
							$value = iconv($current_encoding, 'UTF-8', $value);
						}
					}
					
					$value = filter_var($value, FILTER_SANITIZE_STRING);
				}
				
				$input[$field] = $value;
			}
		}
		
		return $input;		
	}
	
	/**
	 * Return the error array from the last validation run
	 *
	 * @return array
	 */
	public function errors()
	{
		return $this->errors;
	}
	
	/**
	 * Perform data validation against the provided ruleset
	 * 
	 * @access public
	 * @param  mixed $input
	 * @param  array $ruleset
	 * @return mixed
	 */
	public function validate(array $input, array $ruleset)
	{
		$this->errors = array();
		
		foreach($ruleset as $field => $rules)
		{
			#if(!array_key_exists($field, $input))
			#{
			#	continue;
			#}
			
			$rules = explode('|', $rules);

			foreach($rules as $rule)
			{
				$method = NULL;
				$param  = NULL;
				
				if(strstr($rule, ',') !== FALSE) // has params
				{
					$rule   = explode(',', $rule);
					$method = 'validate_'.$rule[0];
					$param  = $rule[1];
				}
				else
				{
					$method = 'validate_'.$rule;
				}

				if(is_callable(array($this, $method)))
				{
					$result = $this->$method($field, $input, $param);

					if(is_array($result)) // Validation Failed
					{
						$this->errors[] = $result;
					}
				}
				else
				{
					throw new Exception("Validator method '$method' does not exist.");
				}
			}
		}

		return (count($this->errors) > 0)? $this->errors : TRUE;
	}
	
	/**
	 * Process the validation errors and return human readable error messages
	 *
	 * @param bool $convert_to_string = false
	 * @param string $field_class
	 * @param string $error_class	
	 * @return array 
	 * @return string	
	 */
	public function get_readable_errors($convert_to_string = false, $field_class="field", $error_class="error-message")
	{
		if(empty($this->errors)) {
			return ($convert_to_string)? null : array();
		}
		
		$resp = array();
		
		foreach($this->errors as $e) {
		
			$field = ucwords(str_replace(array('_','-'), chr(32), $e['field']));
			$param = $e['param'];
			
			switch($e['rule']) {
				case 'validate_required':
					$resp[] = "The <span class=\"$field_class\">$field</span> field is required";
					break;
				case 'validate_valid_email':
					$resp[] = "The <span class=\"$field_class\">$field</span> field is required to be a valid email address";
					break;
				case 'validate_max_len':
					if($param == 1) {
						$resp[] = "The <span class=\"$field_class\">$field</span> field needs to be shorter than $param character";
					} else {
						$resp[] = "The <span class=\"$field_class\">$field</span> field needs to be shorter than $param characters";
					}
					break;
				case 'validate_min_len':
					if($param == 1) {
						$resp[] = "The <span class=\"$field_class\">$field</span> field needs to be longer than $param character";
					} else {
						$resp[] = "The <span class=\"$field_class\">$field</span> field needs to be longer than $param characters";
					}
					break;
				case 'validate_exact_len':
					if($param == 1) {
						$resp[] = "The <span class=\"$field_class\">$field</span> field needs to be exactly $param character in length";
					} else {
						$resp[] = "The <span class=\"$field_class\">$field</span> field needs to be exactly $param characters in length";
					}
					break;
				case 'validate_alpha':
					$resp[] = "The <span class=\"$field_class\">$field</span> field may only contain alpha characters(a-z)";
					break;
				case 'validate_alpha_numeric':
					$resp[] = "The <span class=\"$field_class\">$field</span> field may only contain alpha-numeric characters";
					break;
				case 'validate_alpha_dash':
					$resp[] = "The <span class=\"$field_class\">$field</span> field may only contain alpha characters &amp; dashes";
					break;
				case 'validate_numeric':
					$resp[] = "The <span class=\"$field_class\">$field</span> field may only contain numeric characters";
					break;
				case 'validate_integer':
					$resp[] = "The <span class=\"$field_class\">$field</span> field may only contain a numeric value";
					break;
				case 'validate_boolean':
					$resp[] = "The <span class=\"$field_class\">$field</span> field may only contain a true or false value";
					break;
				case 'validate_float':
					$resp[] = "The <span class=\"$field_class\">$field</span> field may only contain a float value";
					break;
				case 'validate_valid_url':
					$resp[] = "The <span class=\"$field_class\">$field</span> field is required to be a valid URL";
					break;
				case 'validate_url_exists':
					$resp[] = "The <span class=\"$field_class\">$field</span> URL does not exist";
					break;
				case 'validate_valid_ip':
					$resp[] = "The <span class=\"$field_class\">$field</span> field needs to contain a valid IP address";
					break;
				case 'validate_valid_cc':
					$resp[] = "The <span class=\"$field_class\">$field</span> field needs to contain a valid credit card number";
					break;
				case 'validate_valid_name':
					$resp[] = "The <span class=\"$field_class\">$field</span> field needs to contain a valid human name";
					break;
				case 'validate_contains':
					$resp[] = "The <span class=\"$field_class\">$field</span> field needs contain one of these values: ".implode(', ', $param);
					break;
				case 'validate_street_address':
					$resp[] = "The <span class=\"$field_class\">$field</span> field needs to be a valid street address";
					break;
			}
		}		
		
		if(!$convert_to_string) {
			return $resp;
		} else {
			$buffer = '';
			foreach($resp as $s) {
				$buffer .= "<span class=\"$error_class\">$s</span>";
			}
			return $buffer;
		}
	}
	
	/**
	 * Filter the input data according to the specified filter set 
	 * 
	 * @access public
	 * @param  mixed $input
	 * @param  array $filterset
	 * @return mixed
	 */
	public function filter(array $input, array $filterset)
	{
		foreach($filterset as $field => $filters)
		{
			if(!array_key_exists($field, $input))
			{
				continue;
			}

			$filters = explode('|', $filters);

			foreach($filters as $filter)
			{	
				$params = NULL;
				
				if(strstr($filter, ',') !== FALSE)
				{
					$filter = explode(',', $filter);
					
					$params = array_slice($filter, 1, count($filter) - 1);
					
					$filter = $filter[0];
				}
				
				if(is_callable(array($this, 'filter_'.$filter)))
				{
					$method = 'filter_'.$filter;
					$input[$field] = $this->$method($input[$field], $params);
				}
				else if(function_exists($filter))
				{
					$input[$field] = $filter($input[$field]);
				}
				else
				{
					throw new Exception("Filter method '$filter' does not exist.");
				}
			}
		}	
		
		return $input;
	}
	
	// ** ------------------------- Filters --------------------------------------- ** //
	
	/**
	 * Replace noise words in a string (http://tax.cchgroup.com/help/Avoiding_noise_words_in_your_search.htm)
	 * 
	 * Usage: '<index>' => 'noise_words'
	 *	
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	protected function filter_noise_words($value, $params = NULL)
	{
		$value = preg_replace('/\s\s+/u', chr(32),$value);
		
		$value = " $value ";
				
		$words = explode(',', self::$en_noise_words);
		
		foreach($words as $word)
		{
			$word = trim($word);
			
			$word = " $word "; // Normalize
			
			if(stripos($value, $word) !== FALSE)
			{
				$value = str_ireplace($word, chr(32), $value);
			}
		}

		return trim($value);
	}
	
	/**
	 * Remove all known punctuation from a string
	 * 
	 * Usage: '<index>' => 'rmpunctuataion'
	 *	
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	protected function filter_rmpunctuation($value, $params = NULL)
	{
		return preg_replace("/(?![.=$'€%-])\p{P}/u", '', $value);
	}
	
	/**
	 * Translate an input string to a desired language [DEPRECIATED]
	 *
	 * Any ISO 639-1 2 character language code may be used 
	 *
	 * See: http://www.science.co.il/language/Codes.asp?s=code2
	 *
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	/*
	protected function filter_translate($value, $params = NULL)
	{
		$input_lang  = 'en';
		$output_lang = 'en';
		
		if(is_null($params))
		{
			return $value;
		}
		
		switch(count($params))
		{
			case 1:
				$input_lang  = $params[0];
				break;
			case 2:
				$input_lang  = $params[0];
				$output_lang = $params[1];
				break;
		}
		
		$text = urlencode($value);

		$translation = file_get_contents(
			"http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q={$text}&langpair={$input_lang}|{$output_lang}" 
		);
		
		$json = json_decode($translation, true);
		
		if($json['responseStatus'] != 200)
		{
			return $value;
		}
		else
		{
			return $json['responseData']['translatedText'];
		}		
	}
	*/
		
	/**
	 * Sanitize the string by removing any script tags
	 * 
	 * Usage: '<index>' => 'sanitize_string'
	 *	
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	protected function filter_sanitize_string($value, $params = NULL)
	{
		return filter_var($value, FILTER_SANITIZE_STRING);
	}
	
	/**
	 * Sanitize the string by urlencoding characters
	 * 
	 * Usage: '<index>' => 'urlencode'
	 *	
	 * @access protected
	 * @param  string $value
	 * @param  array $params	
	 * @return string
	 */
	protected function filter_urlencode($value, $params = NULL)
	{
		return filter_var($value, FILTER_SANITIZE_ENCODED);  
	}
	
	/**
	 * Sanitize the string by converting HTML characters to their HTML entities
	 * 
	 * Usage: '<index>' => 'htmlencode'
	 *	
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	protected function filter_htmlencode($value, $params = NULL)
	{
		return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);  
	}
	
	/**
	 * Sanitize the string by removing illegal characters from emails
	 * 
	 * Usage: '<index>' => 'sanitize_email'
	 *	
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	protected function filter_sanitize_email($value, $params = NULL)
	{
		return filter_var($value, FILTER_SANITIZE_EMAIL);  
	}
	
	/**
	 * Sanitize the string by removing illegal characters from numbers
	 * 
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	protected function filter_sanitize_numbers($value, $params = NULL)
	{
		return filter_var($value, FILTER_SANITIZE_NUMBER_INT);  
	}
	
	/**
	 * Filter out all HTML tags except the defined basic tags
	 * 
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */	
	protected function filter_basic_tags($value, $params = NULL)
	{
		return strip_tags($value, self::$basic_tags);
	}
	
	// ** ------------------------- Validators ------------------------------------ ** //	
	
	/**
	 * Verify that a value is contained within the pre-defined value set
	 * 
	 * Usage: '<index>' => 'contains,value value value'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_contains($field, $input, $param = NULL)
	{
		$param = trim(strtolower($param));
		
		$value = trim(strtolower($input[$field]));
		
		if (preg_match_all('#\'(.+?)\'#', $param, $matches, PREG_PATTERN_ORDER)) {
			$param = $matches[1];
		} else  {
			$param = explode(chr(32), $param);
		}

		if(in_array($value, $param)) { // valid, return nothing
			return;
		} else {
			return array(
				'field' => $field,
				'value' => $value,
				'rule'	=> __FUNCTION__,
				'param' => $param
			);			
		}
	}	
	
	/**
	 * Check if the specified key is present and not empty
	 * 
	 * Usage: '<index>' => 'required'
	 *
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_required($field, $input, $param = NULL)
	{
		if(isset($input[$field]) && trim($input[$field]) != '')
		{
			return;
		}
		else
		{
			return array(
				'field' => $field,
				'value' => NULL,
				'rule'	=> __FUNCTION__,
				'param' => $param
			);
		}
	}
	
	/**
	 * Determine if the provided email is valid
	 * 
	 * Usage: '<index>' => 'valid_email'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_valid_email($field, $input, $param = NULL)
	{
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
	
		if(!filter_var($input[$field], FILTER_VALIDATE_EMAIL))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param				
			);
		}
	}
	
	/**
	 * Determine if the provided value length is less or equal to a specific value
	 * 
	 * Usage: '<index>' => 'max_len,240'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_max_len($field, $input, $param = NULL)
	{
		if(!isset($input[$field]))
		{
			return;
		}
		
		if(function_exists('mb_strlen'))
		{
			if(mb_strlen($input[$field]) <= (int)$param)
			{
				return;
			}
		}
		else
		{
			if(strlen($input[$field]) <= (int)$param)
			{
				return;
			}
		}

		return array(
			'field' => $field,
			'value' => $input[$field],
			'rule'	=> __FUNCTION__,
			'param' => $param
		);		
	}
	
	/**
	 * Determine if the provided value length is more or equal to a specific value
	 * 
	 * Usage: '<index>' => 'min_len,4'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_min_len($field, $input, $param = NULL)
	{
		if(!isset($input[$field]))
		{
			return;
		}
		
		if(function_exists('mb_strlen'))
		{
			if(mb_strlen($input[$field]) >= (int)$param)
			{
				return;
			}
		}
		else
		{
			if(strlen($input[$field]) >= (int)$param)
			{
				return;
			}
		}

		return array(
			'field' => $field,
			'value' => $input[$field],
			'rule'	=> __FUNCTION__,
			'param' => $param			
		);
	}
	
	/**
	 * Determine if the provided value length matches a specific value
	 * 
	 * Usage: '<index>' => 'exact_len,5'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_exact_len($field, $input, $param = NULL)
	{
		if(!isset($input[$field]))
		{
			return;
		}

		if(function_exists('mb_strlen'))
		{
			if(mb_strlen($input[$field]) == (int)$param)
			{
				return;
			}
		}
		else
		{
			if(strlen($input[$field]) == (int)$param)
			{
				return;
			}
		}

		return array(
			'field' => $field,
			'value' => $input[$field],
			'rule'	=> __FUNCTION__,
			'param' => $param			
		);
	}
	
	/**
	 * Determine if the provided value contains only alpha characters
	 * 
	 * Usage: '<index>' => 'alpha'
	 *
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_alpha($field, $input, $param = NULL)
	{
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
		
		if(!preg_match("/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i", $input[$field]) !== FALSE)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param				
			);
		}
	}
	
	/**
	 * Determine if the provided value contains only alpha-numeric characters
	 * 
	 * Usage: '<index>' => 'alpha_numeric'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */	
	protected function validate_alpha_numeric($field, $input, $param = NULL)
	{	
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
		
		if(!preg_match("/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i", $input[$field]) !== FALSE)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param				
			);
		}
	}
	
	/**
	 * Determine if the provided value contains only alpha characters with dashed and underscores
	 * 
	 * Usage: '<index>' => 'alpha_dash'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_alpha_dash($field, $input, $param = NULL)
	{
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
		
		if(!preg_match("/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ_-])+$/i", $input[$field]) !== FALSE)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param				
			);
		}
	}
	
	/**
	 * Determine if the provided value is a valid number or numeric string
	 * 
	 * Usage: '<index>' => 'numeric'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_numeric($field, $input, $param = NULL)
	{
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
		
		if(!is_numeric($input[$field]))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param				
			);
		}
	}
	
	/**
	 * Determine if the provided value is a valid integer
	 * 
	 * Usage: '<index>' => 'integer'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_integer($field, $input, $param = NULL)
	{
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
		
		if(!filter_var($input[$field], FILTER_VALIDATE_INT))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param				
			);
		}
	}
	
	/**
	 * Determine if the provided value is a PHP accepted boolean
	 * 
	 * Usage: '<index>' => 'boolean'
	 *
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_boolean($field, $input, $param = NULL)
	{
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
		
		$bool = filter_var($input[$field], FILTER_VALIDATE_BOOLEAN);
		
		if(!is_bool($bool))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param				
			);
		}
	}
	
	/**
	 * Determine if the provided value is a valid float
	 * 
	 * Usage: '<index>' => 'float'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_float($field, $input, $param = NULL)
	{
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
		
		if(!filter_var($input[$field], FILTER_VALIDATE_FLOAT))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param				
			);
		}
	}
	
	/**
	 * Determine if the provided value is a valid URL
	 * 
	 * Usage: '<index>' => 'valid_url'
	 *
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_valid_url($field, $input, $param = NULL)
	{
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
		
		if(!filter_var($input[$field], FILTER_VALIDATE_URL))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param				
			);
		}
	}
	
	/**
	 * Determine if a URL exists & is accessible
	 *
	 * Usage: '<index>' => 'url_exists'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_url_exists($field, $input, $param = NULL)
	{
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
		
		$url = str_replace(
			array('http://', 'https://', 'ftp://'), '', strtolower($input[$field])
		);

		if(function_exists('checkdnsrr'))
		{
			if(!checkdnsrr($url))
			{
				return array(
					'field' => $field,
					'value' => $input[$field],
					'rule'	=> __FUNCTION__,
					'param' => $param					
				);
			}	
		}
		else
		{
			if(gethostbyname($url) == $url)
			{
				return array(
					'field' => $field,
					'value' => $input[$field],
					'rule'	=> __FUNCTION__,
					'param' => $param					
				);
			}
		}
	}
	
	/**
	 * Determine if the provided value is a valid IP address
	 * 
	 * Usage: '<index>' => 'valid_ip'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_valid_ip($field, $input, $param = NULL)
	{
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
		
		if(!filter_var($input[$field], FILTER_VALIDATE_IP) !== FALSE)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param				
			);
		}
	}
	
	/**
	 * Determine if the provided value is a valid IPv4 address
	 * 
	 * Usage: '<index>' => 'valid_ipv4'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_valid_ipv4($field, $input, $param = NULL)
	{
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
		
		if(!filter_var($input[$field], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== FALSE)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param				
			);
		}
	}	
	
	/**
	 * Determine if the provided value is a valid IPv6 address
	 * 
	 * Usage: '<index>' => 'valid_ipv6'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_valid_ipv6($field, $input, $param = NULL)
	{
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
		
		if(!filter_var($input[$field], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== FALSE)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param				
			);
		}
	}	
	
	/**
	 * Determine if the input is a valid credit card number 
	 *
	 * See: http://stackoverflow.com/questions/174730/what-is-the-best-way-to-validate-a-credit-card-in-php
	 * Usage: '<index>' => 'valid_cc'	
	 * 
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_valid_cc($field, $input, $param = NULL)
	{
		if(!isset($input[$field]) || empty($input[$field]))
		{
			return;
		}
		
		$number = preg_replace('/\D/', '', $input[$field]);		
		
		if(function_exists('mb_strlen'))
		{
			$number_length = mb_strlen($input[$field]);
		}
		else
		{
			$number_length = strlen($input[$field]);
		}		
	
	  	$parity = $number_length % 2;
	
	 	$total = 0;
	
	  	for($i = 0; $i < $number_length; $i++) 
		{
	    	$digit = $number[$i];

	    	if ($i % 2 == $parity) 
			{
	      		$digit *= 2;
	
	      		if ($digit > 9) 
				{
	        		$digit -= 9;
	      		}
	    	}

	    	$total += $digit;
	  	}	
	
		if($total % 10 == 0)
		{
			return; // Valid
		}
		else
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param				
			);
		}
	}
	
	/**
	 * Determine if the input is a valid human name [Credits to http://github.com/ben-s]
	 *
	 * See: https://github.com/Wixel/GUMP/issues/5
	 * Usage: '<index>' => 'valid_name'
	 * 
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_valid_name($field, $input, $param = NULL)
	{
		if(!isset($input[$field])|| empty($input[$field]))
		{
			return;
		}
		
	    if(!preg_match("/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïñðòóôõöùúûüýÿ '-])+$/i", $input[$field]) !== FALSE)
	    {
	        return array(
	            'field' => $field,
	            'value' => $input[$field],
	            'rule'  => __FUNCTION__,
				'param' => $param	
	        );
	    }
	}
	
	/**
	 * Determine if the provided input is likely to be a street address using weak detection
	 * 
	 * Usage: '<index>' => 'street_address'
	 *	
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected function validate_street_address($field, $input, $param = NULL)
	{	
		if(!isset($input[$field])|| empty($input[$field]))
		{
			return;
		}
		
		// Theory: 1 number, 1 or more spaces, 1 or more words
		$hasLetter = preg_match('/[a-zA-Z]/', $input[$field]);
		$hasDigit  = preg_match('/\d/'      , $input[$field]);
		$hasSpace  = preg_match('/\s/'      , $input[$field]);
		
		$passes = $hasLetter && $hasDigit && $hasSpace;
				
		if(!$passes) {
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__,
				'param' => $param
			);
		}
	}	
	
} // EOC