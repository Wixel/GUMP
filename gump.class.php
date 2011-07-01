<?php

/**
 * GUMP - A fast, extensible PHP input validation class
 *
 * @author		Sean Nieuwoudt (http://twitter.com/SeanNieuwoudt)
 * @copyright	Copyright (c) 2011 Wixel.net
 * @link		http://github.com/Wixel/GUMP
 * @version     0.5
 */

class GUMP
{	
	// ** ------------------------- Validation Data ------------------------------- ** //					
		
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
	 * Sanitize the input data
	 * 
	 * @static
	 * @access public
	 * @param  array $data
	 * @return array
	 */
	public static function sanitize(array $input, $fields = NULL)
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
					
					if(function_exists('iconv'))
					{
						$value = iconv('ISO-8859-1', 'UTF-8', $value);
					}
					
					$value = filter_var($value, FILTER_SANITIZE_STRING);		
				}
				
				$input[$field] = $value;
			}
		}
		
		return $input;		
	}
	
	/**
	 * Perform data validation against the provided ruleset
	 * 
	 * @static
	 * @access public
	 * @param  mixed $input
	 * @param  array $ruleset
	 * @return mixed
	 */
	public static function validate(array $input, array $ruleset)
	{
		$errors = array();
		
		foreach($ruleset as $field => $rules)
		{
			$rules = explode('|', $rules);

			foreach($rules as $rule)
			{
				$method = NULL;
				$param  = NULL;
				
				// @TODO: Improve the param parser
				
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
				
				if(is_callable(array(self, $method)))
				{
					$result = self::$method($field, $input, $param);

					if(is_array($result)) // Validation Failed
					{
						$errors[] = $result;
					}
				}
				else
				{
					throw new Exception("Validator method '$method' does not exist.");
				}
			}
		}

		return (count($errors) > 0)? $errors : TRUE;
	}
	
	/**
	 * Filter the input data according to the specified filter set 
	 * 
	 * @static
	 * @access public
	 * @param  mixed $input
	 * @param  array $filterset
	 * @return mixed
	 */
	public static function filter(array $input, array $filterset)
	{
		foreach($filterset as $field => $filters)
		{
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
				
				if(is_callable(array(self, 'filter_'.$filter)))
				{
					$method = 'filter_'.$filter;
					$input[$field] = self::$method($input[$field], $params);
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
	 * @static
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	protected static function filter_noise_words($value, $params = NULL)
	{
		$value = preg_replace('/\s\s+/', chr(32),$value);
		
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
	 * @static
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	protected static function filter_rmpunctuation($value, $params = NULL)
	{
		return preg_replace("/(?![.=$'€%-])\p{P}/u", '', $value);
	}
	
	/**
	 * Translate an input string to a desired language
	 *
	 * Any ISO 639-1 2 character language code may be used 
	 *
	 * See: http://www.science.co.il/language/Codes.asp?s=code2
	 *
	 * @static
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	protected static function filter_translate($value, $params = NULL)
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
		
	/**
	 * Sanitize the string by removing any script tags
	 * 
	 * @static
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	protected static function filter_sanitize_string($value, $params = NULL)
	{
		return filter_var($value, FILTER_SANITIZE_STRING);
	}
	
	/**
	 * Sanitize the string by urlencoding characters
	 * 
	 * @static
	 * @access protected
	 * @param  string $value
	 * @param  array $params	
	 * @return string
	 */
	protected static function filter_urlencode($value, $params = NULL)
	{
		return filter_var($value, FILTER_SANITIZE_ENCODED);  
	}
	
	/**
	 * Sanitize the string by converting HTML characters to their HTML entities
	 * 
	 * @static
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	protected static function filter_htmlencode($value, $params = NULL)
	{
		return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);  
	}
	
	/**
	 * Sanitize the string by removing illegal characters from emails
	 * 
	 * @static
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	protected static function filter_sanitize_email($value, $params = NULL)
	{
		return filter_var($value, FILTER_SANITIZE_EMAIL);  
	}
	
	/**
	 * Sanitize the string by removing illegal characters from numbers
	 * 
	 * @static
	 * @access protected
	 * @param  string $value
	 * @param  array $params
	 * @return string
	 */
	protected static function filter_sanitize_numbers($value, $params = NULL)
	{
		return filter_var($value, FILTER_SANITIZE_NUMBER_INT);  
	}
	
	// ** ------------------------- Validators ------------------------------------ ** //	
	
	/**
	 * Check if the specified key is present and not empty
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_required($field, $input, $param = NULL)
	{
		if(isset($input[$field]) && trim($input[$field]) != '')
		{
			return;
		}
		else
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__
			);
		}
	}
	
	/**
	 * Determine if the provided email is valid
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_valid_email($field, $input, $param = NULL)
	{
		if(!isset($input[$field]))
		{
			return;
		}
	
		if(!filter_var($input[$field], FILTER_VALIDATE_EMAIL))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__
			);
		}
	}
	
	/**
	 * Determine if the provided value length is less or equal to a specific value
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_max_len($field, $input, $param = NULL)
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
			'rule'	=> __FUNCTION__
		);		
	}
	
	/**
	 * Determine if the provided value length is more or equal to a specific value
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_min_len($field, $input, $param = NULL)
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
			'rule'	=> __FUNCTION__
		);
	}
	
	/**
	 * Determine if the provided value length matches a specific value
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_exact_len($field, $input, $param = NULL)
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
			'rule'	=> __FUNCTION__
		);
	}
	
	/**
	 * Determine if the provided value contains only alpha characters
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_alpha($field, $input, $param = NULL)
	{
		if(!isset($input[$field]))
		{
			return;
		}
		
		if(!preg_match("/^([a-z])+$/i", $input[$field]) !== FALSE)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__
			);
		}
	}
	
	/**
	 * Determine if the provided value contains only alpha-numeric characters
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */	
	protected static function validate_alpha_numeric($field, $input, $param = NULL)
	{	
		if(!isset($input[$field]))
		{
			return;
		}
		
		if(!preg_match("/^([a-z0-9])+$/i", $input[$field]) !== FALSE)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__
			);
		}
	}
	
	/**
	 * Determine if the provided value contains only alpha characters with dashed and underscores
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_alpha_dash($field, $input, $param = NULL)
	{
		if(!isset($input[$field]))
		{
			return;
		}
		
		if(!preg_match("/^([-a-z0-9_-])+$/i", $input[$field]) !== FALSE)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__
			);
		}
	}
	
	/**
	 * Determine if the provided value is a valid number or numeric string
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_numeric($field, $input, $param = NULL)
	{
		if(!isset($input[$field]))
		{
			return;
		}
		
		if(!is_numeric($input[$field]))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__
			);
		}
	}
	
	/**
	 * Determine if the provided value is a valid integer
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_integer($field, $input, $param = NULL)
	{
		if(!isset($input[$field]))
		{
			return;
		}
		
		if(!filter_var($input[$field], FILTER_VALIDATE_INT))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__
			);
		}
	}
	
	/**
	 * Determine if the provided value is a PHP accepted boolean
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_boolean($field, $input, $param = NULL)
	{
		if(!isset($input[$field]))
		{
			return;
		}
		
		$bool = filter_var($input[$field], FILTER_VALIDATE_BOOLEAN);
		
		if(!is_bool($bool))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__
			);
		}
	}
	
	/**
	 * Determine if the provided value is a valid float
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_float($field, $input, $param = NULL)
	{
		if(!isset($input[$field]))
		{
			return;
		}
		
		if(!filter_var($input[$field], FILTER_VALIDATE_FLOAT))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__
			);
		}
	}
	
	/**
	 * Determine if the provided value is a valid URL
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_valid_url($field, $input, $param = NULL)
	{
		if(!isset($input[$field]))
		{
			return;
		}
		
		if(!filter_var($input[$field], FILTER_VALIDATE_URL))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__
			);
		}
	}
	
	/**
	 * Determine if a URL exists & is accessible
	 *
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_url_exists($field, $input, $param = NULL)
	{
		if(!isset($input[$field]))
		{
			return;
		}
		
		$url = str_replace(
			array('http://', 'https://', 'ftp://'), '', strtolower($input[$field])
		);
		
		if(!checkdnsrr($url))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__
			);
		}
	}
	
	/**
	 * Determine if the provided value is a valid IP address
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_valid_ip($field, $input, $param = NULL)
	{
		if(!isset($input[$field]))
		{
			return;
		}
		
		if(!filter_var($input[$field], FILTER_VALIDATE_IP) !== FALSE)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __FUNCTION__
			);
		}
	}
	
	/**
	 * Determine if the input is a valid credit card number 
	 *
	 * See: http://stackoverflow.com/questions/174730/what-is-the-best-way-to-validate-a-credit-card-in-php
	 * 
	 * @static
	 * @access protected
	 * @param  string $field
	 * @param  array $input
	 * @return mixed
	 */
	protected static function validate_valid_cc($field, $input, $param = NULL)
	{
		$number = preg_replace('/\D/', '', $input[$field]);		
		
	  	$number_length = strlen($number);
	
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
				'rule'	=> __FUNCTION__
			);
		}
	}
	
} // EOC