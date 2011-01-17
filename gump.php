<?php

/**
 * GUMP - A fast, extensible PHP input validation class
 *
 * @author		Sean Nieuwoudt (@SeanNieuwoudt)
 * @copyright	Copyright (c) 2011 Wixel.net
 * @link		http://github.com/Wixel/GUMP
 * @version     0.3
 */

class GUMP
{	
	// ** ------------------------- Validation Helpers ---------------------------- ** //	
	
	/**
	 * Perform CSS clean to prevent cross site scripting
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
						$value = str_replace(array("\r\n", "\r"), "\n", $value);
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

				if(method_exists('Input', $method))
				{
					$result = Input::$method($field, $input, $param);

					if(!is_bool($result))
					{
						$errors[] = $result;
					}
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
				$method = 'filter_'.$filter;

				if(method_exists('Input', $method))
				{
					$input[$field] = Input::$method($input[$field]);
				}
			}
		}	
		
		return $input;
	}
	
	// ** ------------------------- Filters --------------------------------------- ** //	
	
	/**
	 * Sanitize the string by removing any script tags
	 * 
	 * @static
	 * @access protected
	 * @param  string $value
	 * @return string
	 */
	protected static function filter_sanitize_string($value)
	{
		return filter_var($value, FILTER_SANITIZE_STRING);
	}
	
	/**
	 * Sanitize the string by urlencoding() characters
	 * 
	 * @static
	 * @access protected
	 * @param  string $value
	 * @return string
	 */
	protected static function filter_urlencode($value)
	{
		return filter_var($value, FILTER_SANITIZE_ENCODED);  
	}
	
	/**
	 * Sanitize the string by converting HTML characters to their HTML entities
	 * 
	 * @static
	 * @access protected
	 * @param  string $value
	 * @return string
	 */
	protected static function filter_htmlencode($value)
	{
		return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);  
	}
	
	/**
	 * Sanitize the string by removing illegal characters from emails
	 * 
	 * @static
	 * @access protected
	 * @param  string $value
	 * @return string
	 */
	protected static function filter_sanitize_email($value)
	{
		return filter_var($value, FILTER_SANITIZE_EMAIL);  
	}
	
	/**
	 * Sanitize the string by removing illegal characters from numbers
	 * 
	 * @static
	 * @access protected
	 * @param  string $value
	 * @return string
	 */
	protected static function filter_sanitize_numbers($value)
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
			return true;
		}
		else
		{
			return array(
				'field' => $field,
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
		if(isset($input[$field]) && filter_var($input[$field], FILTER_VALIDATE_EMAIL))
		{
			return true;
		}
		else
		{
			return array(
				'field' => $field,
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
		if(isset($input[$field]))
		{
			if(function_exists('mb_strlen'))
			{
				if(mb_strlen($input[$field]) <= (int)$param)
				{
					return TRUE;
				}
			}
			else
			{
				if(strlen($input[$field]) <= (int)$param)
				{
					return TRUE;
				}
			}
		}

		return array(
			'field' => $field,
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
		if(isset($input[$field]))
		{
			if(function_exists('mb_strlen'))
			{
				if(mb_strlen($input[$field]) >= (int)$param)
				{
					return TRUE;
				}
			}
			else
			{
				if(strlen($input[$field]) >= (int)$param)
				{
					return TRUE;
				}
			}
		}

		return array(
			'field' => $field,
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
	protected static function validate_exact_length($field, $input, $param = NULL)
	{
		if(isset($input[$field]))
		{
			if(function_exists('mb_strlen'))
			{
				if(mb_strlen($input[$field]) == (int)$param)
				{
					return TRUE;
				}
			}
			else
			{
				if(strlen($input[$field]) == (int)$param)
				{
					return TRUE;
				}
			}
		}

		return array(
			'field' => $field,
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
		if(isset($input[$field]) && preg_match("/^([a-z])+$/i", $input[$field]) !== FALSE)
		{
			return true;
		}
		else
		{
			return array(
				'field' => $field,
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
		if(isset($input[$field]) && preg_match("/^([a-z0-9])+$/i", $input[$field]) !== FALSE)
		{
			return true;
		}
		else
		{
			return array(
				'field' => $field,
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
		if(isset($input[$field]) && preg_match("/^([-a-z0-9_-])+$/i", $input[$field]) !== FALSE)
		{
			return true;
		}
		else
		{
			return array(
				'field' => $field,
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
		if(isset($input[$field]) && is_numeric($input[$field]))
		{
			return true;
		}
		else
		{
			return array(
				'field' => $field,
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
		if(isset($input[$field]) && filter_var($input[$field], FILTER_VALIDATE_INT))
		{
			return true;
		}
		else
		{
			return array(
				'field' => $field,
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
		if(isset($input[$field]) && filter_var($input[$field], FILTER_VALIDATE_BOOLEAN))
		{
			return true;
		}
		else
		{
			return array(
				'field' => $field,
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
		if(isset($input[$field]) && filter_var($input[$field], FILTER_VALIDATE_FLOAT))
		{
			return true;
		}
		else
		{
			return array(
				'field' => $field,
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
		if(isset($input[$field]) && filter_var($input[$field], FILTER_VALIDATE_URL))
		{
			return true;
		}
		else
		{
			return array(
				'field' => $field,
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
		if(isset($input[$field]) && filter_var($input[$field], FILTER_VALIDATE_IP) !== FALSE)
		{
			return true;
		}
		else
		{
			return array(
				'field' => $field,
				'rule'	=> __FUNCTION__
			);
		}
	}
	
	// ** ------------------------- Custom Validators ----------------------------- ** //	
	
	// Put yours here
	
	// ** ------------------------- Custom Filters -------------------------------- ** //
	
	// Put yours here
	
} // EOC