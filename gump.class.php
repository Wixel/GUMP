<?php

use GUMP\ArrayHelpers;
use GUMP\EnvHelpers;

/**
 * GUMP - A Fast PHP Data Validation & Filtering Library
 * 
 * GUMP is a standalone PHP data validation and filtering library that makes validating 
 * any data easy and painless without the reliance on a framework. Supports 41 validators, 
 * 15+ filters, internationalization (19 languages), and custom validators/filters.
 * 
 * @package GUMP
 * @version 1.x
 * @author Sean Nieuwoudt <sean@wixel.net>
 * @copyright 2013-2025 Sean Nieuwoudt
 * @license MIT
 * @link https://github.com/wixel/gump
 * 
 * @since 1.0
 */
class GUMP
{
    /**
     * Singleton instance of GUMP.
     *
     * @var self|null
     */
    protected static $instance = null;

    /**
     * Contains readable field names that have been manually set.
     *
     * @var array
     */
    protected static $fields = [];

    /**
     * Custom validators.
     *
     * @var array
     */
    protected static $validation_methods = [];

    /**
     * Custom validators error messages.
     *
     * @var array
     */
    protected static $validation_methods_errors = [];

    /**
     * Customer filters.
     *
     * @var array
     */
    protected static $filter_methods = [];

    // ** ------------------------- Instance Helper ---------------------------- ** //

    /**
     * Function to create and return previously created instance
     *
     * @return GUMP
     */
    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    // ** ------------------------- Configuration -------------------------------- ** //

    /**
     * Rules delimiter.
     *
     * @var string
     */
    public static $rules_delimiter = '|';

    /**
     * Rules-parameters delimiter.
     *
     * @var string
     */
    public static $rules_parameters_delimiter = ',';

    /**
     * Rules parameters array delimiter.
     *
     * @var string
     */
    public static $rules_parameters_arrays_delimiter = ';';

    /**
     * Characters that will be replaced to spaces during field name conversion (street_name => Street Name).
     *
     * @var array
     */
    public static $field_chars_to_spaces = ['_', '-'];

    // ** ------------------------- Validation Data ------------------------------- ** //

    /**
     * Basic HTML tags allowed in the basic_tags filter.
     * 
     * @var string
     */
    public static $basic_tags = '<br><p><a><strong><b><i><em><img><blockquote><code><dd><dl><hr><h1><h2><h3><h4><h5><h6><label><ul><li><span><sub><sup>';

    /**
     * English noise words used in the noise_words filter.
     * 
     * @var string
     */
    public static $en_noise_words = "about,after,all,also,an,and,another,any,are,as,at,be,because,been,before,
                                     being,between,both,but,by,came,can,come,could,did,do,each,for,from,get,
                                     got,has,had,he,have,her,here,him,himself,his,how,if,in,into,is,it,its,it's,like,
                                     make,many,me,might,more,most,much,must,my,never,now,of,on,only,or,other,
                                     our,out,over,said,same,see,should,since,some,still,such,take,than,that,
                                     the,their,them,then,there,these,they,this,those,through,to,too,under,up,
                                     very,was,way,we,well,were,what,where,which,while,who,with,would,you,your,a,
                                     b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,$,1,2,3,4,5,6,7,8,9,0,_";

    /**
     * Regex pattern for alpha characters including international characters.
     * 
     * @var string
     */
    private static $alpha_regex = 'a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝŸÑàáâãäåçèéêëìíîïðòóôõöùúûüýÿñ';

    /**
     * Values that are considered TRUE in boolean validation and filtering.
     * 
     * @var array
     */
    public static $trues = ['1', 1, 'true', true, 'yes', 'on'];
    
    /**
     * Values that are considered FALSE in boolean validation and filtering.
     * 
     * @var array
     */
    public static $falses = ['0', 0, 'false', false, 'no', 'off'];

    /**
     * Language for error messages.
     *
     * @var string
     */
    protected $lang;

    /**
     * Custom field-rule messages.
     *
     * @var array
     */
    protected $fields_error_messages = [];

    /**
     * Set of validation rules for execution.
     *
     * @var array
     */
    protected $validation_rules = [];

    /**
     * Set of filters rules for execution.
     *
     * @var array
     */
    protected $filter_rules = [];

    /**
     * Errors.
     *
     * @var array
     */
    protected $errors = [];

    // ** ------------------------- Validation Helpers ---------------------------- ** //

    /**
     * GUMP constructor.
     *
     * @param string $lang
     * @throws Exception when language is not supported
     */
    public function __construct(string $lang = 'en')
    {
        $lang_file_location = __DIR__.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$lang.'.php';

        if (!EnvHelpers::file_exists($lang_file_location)) {
            throw new Exception(sprintf("'%s' language is not supported.", $lang));
        }

        $this->lang = $lang;
    }

    /**
     * Shorthand method for inline validation.
     *
     * @param array $data The data to be validated
     * @param array $validators The GUMP validators
     * @param array $fields_error_messages
     * @return mixed True(boolean) or the array of error messages
     * @throws Exception If validation rule does not exist
     */
    public static function is_valid(array $data, array $validators, array $fields_error_messages = [])
    {
        $gump = self::get_instance();
        $gump->validation_rules($validators);
        $gump->set_fields_error_messages($fields_error_messages);

        if ($gump->run($data) === false) {
            return $gump->get_readable_errors();
        }

        return true;
    }

    /**
     * Shorthand method for running only the data filters.
     *
     * @param array $data
     * @param array $filters
     * @return mixed
     * @throws Exception If filter does not exist
     */
    public static function filter_input(array $data, array $filters)
    {
        $gump = self::get_instance();
        return $gump->filter($data, $filters);
    }

    /**
     * Magic method to generate the validation error messages.
     *
     * @return string
     * @throws Exception
     */
    public function __toString()
    {
        return $this->get_readable_errors(true);
    }

    /**
     * An empty value for us is: null, empty string or empty array
     *
     * @param mixed $value
     * @return bool
     */
    public static function is_empty($value)
    {
        return (is_null($value) || $value === '' || (is_array($value) && count($value) === 0));
    }

    /**
     * Adds a custom validation rule using a callback function.
     *
     * @param string $rule
     * @param callable $callback
     * @param string $error_message
     *
     * @return void
     * @throws Exception when validator with the same name already exists
     */
    public static function add_validator(string $rule, callable $callback, string $error_message)
    {
        if (method_exists(__CLASS__, self::validator_to_method($rule)) || isset(self::$validation_methods[$rule])) {
            throw new Exception(sprintf("'%s' validator is already defined.", $rule));
        }

        self::$validation_methods[$rule] = $callback;
        self::$validation_methods_errors[$rule] = $error_message;
    }

    /**
     * Adds a custom filter using a callback function.
     *
     * @param string $rule
     * @param callable $callback
     *
     * @return void
     * @throws Exception when filter with the same name already exists
     */
    public static function add_filter(string $rule, callable $callback)
    {
        if (method_exists(__CLASS__, self::filter_to_method($rule)) || isset(self::$filter_methods[$rule])) {
            throw new Exception(sprintf("'%s' filter is already defined.", $rule));
        }

        self::$filter_methods[$rule] = $callback;
    }

    /**
     * Checks if a validator exists.
     *
     * @param string $rule
     *
     * @return bool
     */
    public static function has_validator(string $rule)
    {
        return method_exists(__CLASS__, self::validator_to_method($rule)) || isset(self::$validation_methods[$rule]);
    }

    /**
     * Checks if a filter exists.
     *
     * @param string $filter
     *
     * @return bool
     */
    public static function has_filter(string $filter)
    {
        return method_exists(__CLASS__, self::filter_to_method($filter))
            || isset(self::$filter_methods[$filter])
            || function_exists($filter);
    }

    /**
     * Helper method to extract an element from an array safely
     *
     * @param  mixed $key
     * @param  array $array
     * @param  mixed $default
     *
     * @return mixed
     */
    public static function field($key, array $array, $default = null)
    {
        if (isset($array[$key])) {
            return $array[$key];
        }

        return $default;
    }

    /**
     * Getter/Setter for the validation rules.
     *
     * @param array $rules
     * @return array
     */
    public function validation_rules(array $rules = [])
    {
        if (empty($rules)) {
            return $this->validation_rules;
        }

        $this->validation_rules = $rules;

        return $this->validation_rules;
    }

    /**
     * Set field-rule specific error messages.
     *
     * @param array $fields_error_messages
     * @return array
     */
    public function set_fields_error_messages(array $fields_error_messages)
    {
        return $this->fields_error_messages = $fields_error_messages;
    }

    /**
     * Getter/Setter for the filter rules.
     *
     * @param array $rules
     * @return array
     */
    public function filter_rules(array $rules = [])
    {
        if (empty($rules)) {
            return $this->filter_rules;
        }

        $this->filter_rules = $rules;

        return $this->filter_rules;
    }

    /**
     * Run the filtering and validation after each other.
     *
     * @param array  $data
     * @param bool   $check_fields
     *
     * @return array|bool
     * @throws Exception
     */
    public function run(array $data, $check_fields = false)
    {
        $data = $this->filter($data, $this->filter_rules());

        $validated = $this->validate($data, $this->validation_rules());

        if ($check_fields === true) {
            $this->check_fields($data);
        }

        if ($validated !== true) {
            return false;
        }

        return $data;
    }

    /**
     * Ensure that the field counts match the validation rule counts.
     *
     * @param array $data
     */
    private function check_fields(array $data)
    {
        $ruleset = $this->validation_rules();
        $mismatch = array_diff_key($data, $ruleset);
        $fields = array_keys($mismatch);

        foreach ($fields as $field) {
            $this->errors[] = $this->generate_error_array($field, $data[$field], 'mismatch');
        }
    }

    /**
     * Sanitize the input data.
     *
     * @param array $input
     * @param array $fields
     * @param bool $utf8_encode
     *
     * @return array
     */
    public function sanitize(array $input, array $fields = [], bool $utf8_encode = true)
    {
        if (empty($fields)) {
            $fields = array_keys($input);
        }

        $return = [];

        foreach ($fields as $field) {
            if (!isset($input[$field])) {
                continue;
            }

            $value = $input[$field];
            if (is_array($value)) {
                $value = $this->sanitize($value, [], $utf8_encode);
            }
            if (is_string($value)) {
                if (strpos($value, "\r") !== false) {
                    $value = trim($value);
                }

                if (function_exists('iconv') && function_exists('mb_detect_encoding') && $utf8_encode) {
                    $current_encoding = mb_detect_encoding($value);

                    if ($current_encoding !== 'UTF-8' && $current_encoding !== 'UTF-16') {
                        $value = iconv($current_encoding, 'UTF-8', $value);
                    }
                }

                $value = static::polyfill_filter_var_string($value);
            }

            $return[$field] = $value;
        }

        return $return;
    }

    /**
     * Return the error array from the last validation run.
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Perform data validation against the provided ruleset.
     *
     * @param array $input Input data.
     * @param array $ruleset Validation rules.
     *
     * @return bool|array Returns bool true when no errors. Returns array when errors.
     * @throws Exception
     */
    public function validate(array $input, array $ruleset)
    {
        $this->errors = [];

        foreach ($ruleset as $field => $rawRules) {
            $input[$field] = ArrayHelpers::data_get($input, $field);

            $rules = $this->parse_rules($rawRules);
            $is_required = $this->field_has_required_rules($rules);

            if (!$is_required && self::is_empty($input[$field])) {
                continue;
            }

            foreach ($rules as $rule) {
                $parsed_rule = $this->parse_rule($rule);
                $result = $this->foreach_call_validator($parsed_rule['rule'], $field, $input, $parsed_rule['param']);

                if (is_array($result)) {
                    $this->errors[] = $result;
                    break; // exit on first error
                }
            }
        }

        return (count($this->errors) > 0) ? $this->errors : true;
    }

    /**
     * Parses filters and validators rules group.
     *
     * @param string|array $rules
     * @return array
     */
    private function parse_rules($rules)
    {
        // v2
        if (is_array($rules)) {
            $rules_names = [];
            foreach ($rules as $key => $value) {
                $rules_names[] = is_numeric($key) ? $value : $key;
            }

            return array_map(function ($value, $key) use ($rules) {
                if ($value === $key) {
                    return [ $key ];
                }

                return [$key, $value];
            }, $rules, $rules_names);
        }

        return explode(self::$rules_delimiter, $rules);
    }

    /**
     * Parses filters and validators individual rules.
     *
     * @param string|array $rule
     * @return array
     */
    private function parse_rule($rule)
    {
        // v2
        if (is_array($rule)) {
            return [
                'rule' => $rule[0],
                'param' => $this->parse_rule_params($rule[1] ?? [])
            ];
        }

        $result = [
            'rule' => $rule,
            'param' => []
        ];

        if (strpos($rule, self::$rules_parameters_delimiter) !== false) {
            list($rule, $param) = explode(self::$rules_parameters_delimiter, $rule);

            $result['rule'] = $rule;
            $result['param'] = $this->parse_rule_params($param);
        }

        return $result;
    }

    /**
     * Parse rule parameters.
     *
     * @param string|array $param
     * @return array|string|null
     */
    private function parse_rule_params($param)
    {
        if (is_array($param)) {
            return $param;
        }

        if (strpos($param, self::$rules_parameters_arrays_delimiter) !== false) {
            return explode(self::$rules_parameters_arrays_delimiter, $param);
        }

        return [ $param ];
    }

    /**
     * Checks if array of rules contains a required type of validator.
     *
     * @param array $rules
     * @return bool
     */
    private function field_has_required_rules(array $rules)
    {
        $require_type_of_rules = ['required', 'required_file'];

        // v2 format (using arrays for definition of rules)
        if (is_array($rules) && is_array($rules[0])) {
            $found = array_filter($rules, function ($item) use ($require_type_of_rules) {
                return in_array($item[0], $require_type_of_rules);
            });
            return count($found) > 0;
        }

        $found = array_values(array_intersect($require_type_of_rules, $rules));
        return count($found) > 0;
    }

    /**
     * Helper to convert validator rule name to validator rule method name.
     *
     * @param string $rule
     * @return string
     */
    private static function validator_to_method(string $rule)
    {
        return sprintf('validate_%s', $rule);
    }

    /**
     * Helper to convert filter rule name to filter rule method name.
     *
     * @param string $rule
     * @return string
     */
    private static function filter_to_method(string $rule)
    {
        return sprintf('filter_%s', $rule);
    }

    /**
     * Calls call_validator.
     *
     * @param string $rule
     * @param string $field
     * @param mixed $input
     * @param array $rule_params
     * @return array|bool
     * @throws Exception
     */
    private function foreach_call_validator(string $rule, string $field, array $input, array $rule_params = [])
    {
        $is_required_kind_of_rule = $this->field_has_required_rules([$rule]);

        // Fixes #315
        if ($is_required_kind_of_rule && is_array($input[$field]) && count($input[$field]) === 0) {
            $result = $this->call_validator($rule, $field, $input, $rule_params, $input[$field]);

            return is_array($result) ? $result : true;
        }

        $values = is_array($input[$field]) ? $input[$field] : [ $input[$field] ];

        foreach ($values as $value) {
            $result = $this->call_validator($rule, $field, $input, $rule_params, $value);

            if (is_array($result)) {
                return $result;
            }
        }

        return true;
    }

    /**
     * Calls a validator.
     *
     * @param string $rule
     * @param string $field
     * @param mixed $input
     * @param array $rule_params
     * @return array|bool
     * @throws Exception
     */
    private function call_validator(string $rule, string $field, array $input, array $rule_params = [], $value = null)
    {
        $method = self::validator_to_method($rule);

        // use native validations
        if (is_callable([$this, $method])) {
            $result = $this->$method($field, $input, $rule_params, $value);

            // is_array check for backward compatibility
            return (is_array($result) || $result === false)
                ? $this->generate_error_array($field, $input[$field], $rule, $rule_params)
                : true;
        }

        // use custom validations
        if (isset(self::$validation_methods[$rule])) {
            $result = call_user_func(self::$validation_methods[$rule], $field, $input, $rule_params, $value);

            return ($result === false)
                ? $this->generate_error_array($field, $input[$field], $rule, $rule_params)
                : true;
        }

        throw new Exception(sprintf("'%s' validator does not exist.", $rule));
    }

    /**
     * Calls a filter.
     *
     * @param string $rule
     * @param mixed $value
     * @param array $rule_params
     * @return mixed
     * @throws Exception
     */
    private function call_filter(string $rule, $value, array $rule_params = [])
    {
        $method = self::filter_to_method($rule);

        // use native filters
        if (is_callable(array($this, $method))) {
            return $this->$method($value, $rule_params);
        }

        // use custom filters
        if (isset(self::$filter_methods[$rule])) {
            return call_user_func(self::$filter_methods[$rule], $value, $rule_params);
        }

        // use php functions as filters
        if (function_exists($rule)) {
            return call_user_func($rule, $value, ...$rule_params);
        }

        throw new Exception(sprintf("'%s' filter does not exist.", $rule));
    }

    /**
     * Generates error array.
     *
     * @param string $field
     * @param mixed $value
     * @param string $rule
     * @param array $rule_params
     * @return array
     */
    private function generate_error_array(string $field, $value, string $rule, array $rule_params = [])
    {
        return [
            'field' => $field,
            'value' => $value,
            'rule' => $rule,
            'params' => $rule_params
        ];
    }

    /**
     * Set a readable name for a specified field names.
     *
     * @param string $field
     * @param string $readable_name
     */
    public static function set_field_name(string $field, string $readable_name)
    {
        self::$fields[$field] = $readable_name;
    }

    /**
     * Set readable name for specified fields in an array.
     *
     * @param array $array
     */
    public static function set_field_names(array $array)
    {
        foreach ($array as $field => $readable_name) {
            self::set_field_name($field, $readable_name);
        }
    }

    /**
     * Set a custom error message for a validation rule.
     *
     * @param string $rule
     * @param string $message
     */
    public static function set_error_message(string $rule, string $message)
    {
        self::$validation_methods_errors[$rule] = $message;
    }

    /**
     * Set custom error messages for validation rules in an array.
     *
     * @param array $array
     */
    public static function set_error_messages(array $array)
    {
        foreach ($array as $rule => $message) {
            self::set_error_message($rule, $message);
        }
    }

    /**
     * Get all error messages.
     *
     * @return array
     */
    protected function get_messages()
    {
        $lang_file = __DIR__.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$this->lang.'.php';
        $messages = include $lang_file;

        return array_merge($messages, self::$validation_methods_errors);
    }

    /**
     * Get error message.
     *
     * @param array $messages
     * @param string $field
     * @param string $rule
     * @return mixed|null
     * @throws Exception
     */
    private function get_error_message(array $messages, string $field, string $rule)
    {
        $custom_error_message = $this->get_custom_error_message($field, $rule);
        if ($custom_error_message !== null) {
            return $custom_error_message;
        }

        if (isset($messages[$rule])) {
            return $messages[$rule];
        }

        throw new Exception(sprintf("'%s' validator does not have an error message.", $rule));
    }

    /**
     * Get custom error message for field and rule.
     *
     * @param string $field
     * @param string $rule
     * @return string|null
     */
    private function get_custom_error_message(string $field, string $rule)
    {
        $rule_name = str_replace('validate_', '', $rule);
        return $this->fields_error_messages[$field][$rule_name] ?? null;
    }

    /**
     * Process error message string.
     *
     * @param string $field
     * @param array $params
     * @param string $message
     * @param callable|null $transformer
     * @return string
     */
    private function process_error_message($field, array $params, string $message, ?callable $transformer = null)
    {
        // if field name is explicitly set, use it
        if (array_key_exists($field, self::$fields)) {
            $field = self::$fields[$field];
        } else {
            $field = ucwords(str_replace(self::$field_chars_to_spaces, chr(32), $field));
        }

        // if param is a field (i.e. equalsfield validator)
        if (isset($params[0]) && array_key_exists($params[0], self::$fields)) {
            $params[0] = self::$fields[$params[0]];
        }

        $replace = [
            '{field}' => $field,
            '{param}' => implode(', ', $params)
        ];

        foreach ($params as $key => $value) {
            $replace[sprintf('{param[%s]}', $key)] = $value;
        }

        // for get_readable_errors() <span>
        if ($transformer) {
            $replace = $transformer($replace);
        }

        return strtr($message, $replace);
    }

    /**
     * Process the validation errors and return human readable error messages.
     *
     * @param bool   $convert_to_string = false
     * @param string $field_class
     * @param string $error_class
     * @return array|string
     * @throws Exception if validator doesn't have an error message to set
     */
    public function get_readable_errors(bool $convert_to_string = false, string $field_class = 'gump-field', string $error_class = 'gump-error-message')
    {
        if (empty($this->errors)) {
            return $convert_to_string ? '' : [];
        }

        $messages = $this->get_messages();
        $result = [];

        $transformer = static function ($replace) use ($field_class) {
            $replace['{field}'] = sprintf('<span class="%s">%s</span>', $field_class, $replace['{field}']);
            return $replace;
        };

        foreach ($this->errors as $error) {
            $message = $this->get_error_message($messages, $error['field'], $error['rule']);
            $result[] = $this->process_error_message($error['field'], $error['params'], $message, $transformer);
        }

        if ($convert_to_string) {
            return array_reduce($result, static function ($prev, $next) use ($error_class) {
                return sprintf('%s<span class="%s">%s</span>', $prev, $error_class, $next);
            });
        }

        return $result;
    }

    /**
     * Process the validation errors and return an array of errors with field names as keys.
     *
     * @return array
     * @throws Exception
     */
    public function get_errors_array()
    {
        $messages = $this->get_messages();
        $result = [];

        foreach ($this->errors as $error) {
            $message = $this->get_error_message($messages, $error['field'], $error['rule']);
            $result[$error['field']] = $this->process_error_message($error['field'], $error['params'], $message);
        }

        return $result;
    }

    /**
     * Filter the input data according to the specified filter set.
     *
     * @param mixed  $input
     * @param array  $filterset
     * @return mixed
     * @throws Exception
     */
    public function filter(array $input, array $filterset)
    {
        foreach ($filterset as $field => $filters) {
            if (!array_key_exists($field, $input)) {
                continue;
            }

            $filters = $this->parse_rules($filters);

            foreach ($filters as $filter) {
                $parsed_rule = $this->parse_rule($filter);

                if (is_array($input[$field])) {
                    $input_array = &$input[$field];
                } else {
                    $input_array = array(&$input[$field]);
                }

                foreach ($input_array as &$value) {
                    $value = $this->call_filter($parsed_rule['rule'], $value, $parsed_rule['param']);
                }

                unset($input_array, $value);
            }
        }

        return $input;
    }

    // ** ------------------------- Filters --------------------------------------- ** //

    /**
     * Replace noise words in a string (http://tax.cchgroup.com/help/Avoiding_noise_words_in_your_search.htm).
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_noise_words($value, array $params = [])
    {
        $value = preg_replace('/\s\s+/u', chr(32), $value);

        $value = " $value ";

        $words = explode(',', self::$en_noise_words);

        foreach ($words as $word) {
            $word = trim($word);

            $word = " $word "; // Normalize

            if (stripos($value, $word) !== false) {
                $value = str_ireplace($word, chr(32), $value);
            }
        }

        return trim($value);
    }

    /**
     * Remove all known punctuation from a string.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_rmpunctuation($value, array $params = [])
    {
        return preg_replace("/(?![.=$'€%-])\p{P}/u", '', $value);
    }

    /**
     * Sanitize the string by urlencoding characters.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_urlencode($value, array $params = [])
    {
        return filter_var($value, FILTER_SANITIZE_ENCODED);
    }

    /**
     * Sanitize the string by converting HTML characters to their HTML entities.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_htmlencode($value, array $params = [])
    {
        return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    /**
     * Sanitize the string by removing illegal characters from emails.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_sanitize_email($value, array $params = [])
    {
        return filter_var($value, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Sanitize the string by removing illegal characters from numbers.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_sanitize_numbers($value, array $params = [])
    {
        return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Sanitize the string by removing illegal characters from float numbers.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_sanitize_floats($value, array $params = [])
    {
        return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }


    /**
     * Sanitize the string by removing any script tags.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_sanitize_string($value, array $params = [])
    {
        return self::polyfill_filter_var_string($value);
    }

    /**
     * Implemented to replace FILTER_SANITIZE_STRING behaviour deprecated in php8.1
     *
     * @param mixed $value
     * @return string
     */
    private static function polyfill_filter_var_string($value)
    {
        $str = preg_replace('/\x00|<[^>]*>?/', '', $value);
        return (string)str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
    }

    /**
     * Converts ['1', 1, 'true', true, 'yes', 'on'] to true, anything else is false ('on' is useful for form checkboxes).
     *
     * @param mixed $value
     * @param array $params
     *
     * @return bool
     */
    protected function filter_boolean($value, array $params = [])
    {
        if (in_array($value, self::$trues, true)) {
            return true;
        }

        return false;
    }

    /**
     * Filter out all HTML tags except the defined basic tags.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_basic_tags($value, array $params = [])
    {
        return strip_tags($value, self::$basic_tags);
    }

    /**
     * Convert the provided numeric value to a whole number.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_whole_number($value, array $params = [])
    {
        return intval($value);
    }

    /**
     * Convert MS Word special characters to web safe characters. ([“ ”] => ", [‘ ’] => ', [–] => -, […] => ...)
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_ms_word_characters($value, array $params = [])
    {
        return str_replace(['“', '”', '‘', '’', '–', '…'], ['"', '"', "'", "'", '-', '...'], $value);
    }

    /**
     * Converts to lowercase.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_lower_case($value, array $params = [])
    {
        return mb_strtolower($value);
    }

    /**
     * Converts to uppercase.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_upper_case($value, array $params = [])
    {
        return mb_strtoupper($value);
    }

    /**
     * Converts value to url-web-slugs.
     *
     * @see https://stackoverflow.com/questions/40641973/php-to-convert-string-to-slug
     * @see http://cubiq.org/the-perfect-php-clean-url-generator
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_slug($value, array $params = [])
    {
        $delimiter = '-';
        return mb_strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $value))))), $delimiter));
    }

    /**
     * Remove spaces from the beginning and end of strings.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_trim($value, array $params = [])
    {
        return trim($value);
    }

    // ** ------------------------- Validators ------------------------------------ ** //

    /**
     * Ensures the specified key value exists and is not empty (not null, not empty string, not empty array).
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_required($field, array $input, array $params = [], $value = null)
    {
        return isset($value) && !self::is_empty($value);
    }

    /**
     * Verify that a value is contained within the pre-defined value set.
     *
     * @example_parameter one;two;use array format if one of the values contains semicolons
     *
     * @param string $field
     * @param array  $input
     * @param array $params
     *
     * @return bool
     */
    protected function validate_contains($field, array $input, array $params = [], $value = null)
    {
        $value = mb_strtolower(trim($input[$field]));

        $params = array_map(static function ($value) {
            return mb_strtolower(trim($value));
        }, $params);

        return in_array($value, $params, true);
    }

    /**
     * Verify that a value is contained within the pre-defined value set. Error message will NOT show the list of possible values.
     *
     * @example_parameter value1;value2
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_contains_list($field, array $input, array $params = [], $value = null)
    {
        return $this->validate_contains($field, $input, $params);
    }

    /**
     * Verify that a value is contained within the pre-defined value set. Error message will NOT show the list of possible values.
     *
     * @example_parameter value1;value2
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_doesnt_contain_list($field, array $input, array $params = [], $value = null)
    {
        return !$this->validate_contains($field, $input, $params);
    }

    /**
     * Determine if the provided value is a valid boolean. Returns true for: yes/no, on/off, 1/0, true/false. In strict mode (optional) only true/false will be valid which you can combine with boolean filter.
     *
     * @example_parameter strict
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_boolean($field, array $input, array $params = [], $value = null)
    {
        if (isset($params[0]) && $params[0] === 'strict') {
            return in_array($input[$field], [true, false], true);
        }

        $booleans = [];
        foreach (self::$trues as $true) {
            $booleans[] = $true;
        }
        foreach (self::$falses as $false) {
            $booleans[] = $false;
        }

        return in_array($input[$field], $booleans, true);
    }

    /**
     * Determine if the provided email has valid format.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value individual value (in case of array)
     *
     * @return bool
     */
    protected function validate_valid_email($field, array $input, array $params = [], $value = null)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Determine if the provided value length is less or equal to a specific value.
     *
     * @example_parameter 240
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_max_len($field, array $input, array $params = [], $value = null)
    {
        return mb_strlen($value) <= (int)$params[0];
    }

    /**
     * Determine if the provided value length is more or equal to a specific value.
     *
     * @example_parameter 4
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_min_len($field, array $input, array $params = [], $value = null)
    {
        return mb_strlen($value) >= (int)$params[0];
    }

    /**
     * Determine if the provided value length matches a specific value.
     *
     * @example_parameter 5
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     * @param mixed  $value
     *
     * @return bool
     */
    protected function validate_exact_len($field, array $input, array $params = [], $value = null)
    {
        return mb_strlen($value) == (int)$params[0];
    }

    /**
     * Determine if the provided value length is between min and max values.
     *
     * @example_parameter 3;11
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_between_len($field, array $input, array $params = [], $value = null)
    {
        return $this->validate_min_len($field, $input, [$params[0]], $value)
            && $this->validate_max_len($field, $input, [$params[1]], $value);
    }

    /**
     * Determine if the provided value contains only alpha characters.
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     * @param mixed  $value
     * @return bool
     */
    protected function validate_alpha($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^(['.self::$alpha_regex.'])+$/i', $value) > 0;
    }

    /**
     * Determine if the provided value contains only alpha-numeric characters.
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     *
     * @return bool
     */
    protected function validate_alpha_numeric($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^(['.self::$alpha_regex.'0-9])+$/i', $value) > 0;
    }

    /**
     * Determine if the provided value contains only alpha characters with dashed and underscores.
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     *
     * @return bool
     */
    protected function validate_alpha_dash($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^(['.self::$alpha_regex.'_-])+$/i', $value) > 0;
    }

    /**
     * Determine if the provided value contains only alpha numeric characters with dashed and underscores.
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     *
     * @return bool
     */
    protected function validate_alpha_numeric_dash($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^(['.self::$alpha_regex.'0-9_-])+$/i', $value) > 0;
    }

    /**
     * Determine if the provided value contains only alpha numeric characters with spaces.
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     *
     * @return bool
     */
    protected function validate_alpha_numeric_space($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^(['.self::$alpha_regex.'\s0-9])+$/i', $value) > 0;
    }

    /**
     * Determine if the provided value contains only alpha characters with spaces.
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     *
     * @return bool
     */
    protected function validate_alpha_space($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^(['.self::$alpha_regex.'\s])+$/i', $value) > 0;
    }

    /**
     * Determine if the provided value is a valid number or numeric string.
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     *
     * @return bool
     */
    protected function validate_numeric($field, array $input, array $params = [], $value = null)
    {
        return is_numeric($value);
    }

    /**
     * Determine if the provided value is a valid integer.
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     *
     * @return bool
     */
    protected function validate_integer($field, array $input, array $params = [], $value = null)
    {
        return !(filter_var($value, FILTER_VALIDATE_INT) === false || is_bool($value) || is_null($value));
    }

    /**
     * Determine if the provided value is a valid float.
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     *
     * @return bool
     */
    protected function validate_float($field, array $input, array $params = [], $value = null)
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
    }

    /**
     * Determine if the provided value is a valid URL.
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     *
     * @return bool
     */
    protected function validate_valid_url($field, array $input, array $params = [], $value = null)
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Determine if a URL exists & is accessible.
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     *
     * @return bool
     */
    protected function validate_url_exists($field, array $input, array $params = [], $value = null)
    {
        $url = parse_url(mb_strtolower($value));

        if (isset($url['host'])) {
            $url = $url['host'];
        }

        return EnvHelpers::checkdnsrr(idn_to_ascii($url, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46), 'A') !== false;
    }

    /**
     * Determine if the provided value is a valid IP address.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_valid_ip($field, array $input, array $params = [], $value = null)
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Determine if the provided value is a valid IPv4 address.
     *
     * @see What about private networks? What about loop-back address? 127.0.0.1 http://en.wikipedia.org/wiki/Private_network
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_valid_ipv4($field, array $input, array $params = [], $value = null)
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }

    /**
     * Determine if the provided value is a valid IPv6 address.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_valid_ipv6($field, array $input, array $params = [], $value = null)
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }

    /**
     * Determine if the input is a valid credit card number.
     *
     * @see http://stackoverflow.com/questions/174730/what-is-the-best-way-to-validate-a-credit-card-in-php
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_valid_cc($field, array $input, array $params = [], $value = null)
    {
        $number = preg_replace('/\D/', '', $value);

        $number_length = mb_strlen($number);

        /**
         * Bail out if $number_length is 0.
         * This can be the case if a user has entered only alphabets
         *
         * @since 1.5
         */
        if ($number_length == 0) {
            return false;
        }

        $parity = $number_length % 2;

        $total = 0;

        for ($i = 0; $i < $number_length; ++$i) {
            $digit = $number[$i];

            if ($i % 2 == $parity) {
                $digit *= 2;

                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $total += $digit;
        }

        return $total % 10 == 0;
    }

    /**
     * Determine if the input is a valid human name.
     *
     * @see https://github.com/Wixel/GUMP/issues/5
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_valid_name($field, array $input, array $params = [], $value = null)
    {
        return preg_match("/^([a-z \p{L} '-])+$/i", $value) > 0;
    }

    /**
     * Determine if the provided input is likely to be a street address using weak detection.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_street_address($field, array $input, array $params = [], $value = null)
    {
        // Theory: 1 number, 1 or more spaces, 1 or more words
        $has_letter = preg_match('/[a-zA-Z]/', $value);
        $has_digit = preg_match('/\d/', $value);
        $has_space = preg_match('/\s/', $value);

        return $has_letter && $has_digit && $has_space;
    }

    /**
     * Determine if the provided value is a valid IBAN.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_iban($field, array $input, array $params = [], $value = null)
    {
        $character = [
            'A' => 10, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16,
            'H' => 17, 'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21, 'M' => 22,
            'N' => 23, 'O' => 24, 'P' => 25, 'Q' => 26, 'R' => 27, 'S' => 28,
            'T' => 29, 'U' => 30, 'V' => 31, 'W' => 32, 'X' => 33, 'Y' => 34,
            'Z' => 35, 'B' => 11
        ];

        if (!preg_match("/\A[A-Z]{2}\d{2} ?[A-Z\d]{4}( ?\d{4}){1,} ?\d{1,4}\z/", $value)) {
            return false;
        }

        $iban = str_replace(' ', '', $value);
        $iban = substr($iban, 4).substr($iban, 0, 4);
        $iban = strtr($iban, $character);

        return bcmod($iban, 97) == 1;
    }

    /**
     * Determine if the provided input is a valid date (ISO 8601) or specify a custom format (optional).
     *
     * @example_parameter d/m/Y
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_date($field, array $input, array $params = [], $value = null)
    {
        // Default
        if (count($params) === 0) {
            $cdate1 = date('Y-m-d', strtotime($value));
            $cdate2 = date('Y-m-d H:i:s', strtotime($value));

            return !($cdate1 != $value && $cdate2 != $value);
        }

        $date = \DateTime::createFromFormat($params[0], $value);

        return !($date === false || $value != date($params[0], $date->getTimestamp()));
    }

    /**
     * Determine if the provided input meets age requirement (ISO 8601). Input should be a date (Y-m-d).
     *
     * @example_parameter 18
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     * @throws Exception
     */
    protected function validate_min_age($field, array $input, array $params = [], $value = null)
    {
        $inputDatetime = new DateTime(EnvHelpers::date('Y-m-d', strtotime($value)));
        $todayDatetime = new DateTime(EnvHelpers::date('Y-m-d'));

        $interval = $todayDatetime->diff($inputDatetime);
        $yearsPassed = $interval->y;

        return $yearsPassed >= $params[0];
    }

    /**
     * Determine if the provided numeric value is lower or equal to a specific value.
     *
     * @example_parameter 50
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     * @param mixed  $value
     * @return bool
     */
    protected function validate_max_numeric($field, array $input, array $params = [], $value = null)
    {
        return is_numeric($value) && is_numeric($params[0]) && ($value <= $params[0]);
    }

    /**
     * Determine if the provided numeric value is higher or equal to a specific value.
     *
     * @example_parameter 1
     *
     * @param string $field
     * @param array  $input
     * @param array  $params
     * @param mixed  $value
     * @return bool
     */
    protected function validate_min_numeric($field, array $input, array $params = [], $value = null)
    {
        return is_numeric($value) && is_numeric($params[0]) && ($value >= $params[0]);
    }

    /**
     * Determine if the provided value starts with param.
     *
     * @example_parameter Z
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     * @return bool
     */
    protected function validate_starts($field, array $input, array $params = [], $value = null)
    {
        return strpos($value, $params[0]) === 0;
    }

    /**
     * Determine if the file was successfully uploaded.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_required_file($field, array $input, array $params = [], $value = null)
    {
        return isset($input[$field]) && is_array($input[$field]) && $input[$field]['error'] === 0;
    }

    /**
     * Check the uploaded file for extension. Doesn't check mime-type yet.
     *
     * @example_parameter png;jpg;gif
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_extension($field, array $input, array $params = [], $value = null)
    {
        if (isset($input[$field]['error']) && gettype($input[$field]['error']) == 'string') {
            $input[$field]['error'] = (int) $input[$field]['error'];
        }
        
        if (!is_array($input[$field])) {
            return false;
        }

        // file is not required (empty upload)
        if ($input[$field]['error'] === 4 && $input[$field]['size'] === 0 && $input[$field]['name'] === '') {
            return true;
        }

        // when successfully uploaded we proceed to verify the extension
        if ($input[$field]['error'] === 0) {
            $params = array_map(function ($v) {
                return trim(mb_strtolower($v));
            }, $params);

            $path_info = pathinfo($input[$field]['name']);
            $extension = $path_info['extension'] ?? null;

            return $extension && in_array(mb_strtolower($extension), $params, true);
        }

        return false;
    }

    /**
     * Determine if the provided field value equals current field value.
     *
     * @example_parameter other_field_name
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_equalsfield($field, array $input, array $params = [], $value = null)
    {
        return $input[$field] == $input[$params[0]];
    }

    /**
     * Determine if the provided field value is a valid GUID (v4)
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_guidv4($field, array $input, array $params = [], $value = null)
    {
        return preg_match("/\{?[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}\}?$/", $value) > 0;
    }

    /**
     * Determine if the provided value is a valid phone number.
     *
     * @example_value 5555425555
     * @example_value 555-555-5555
     * @example_value 1(519) 555-4444
     * @example_value 1-555-555-5555
     * @example_value 1-(555)-555-5555
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_phone_number($field, array $input, array $params = [], $value = null)
    {
        $regex = '/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i';

        return preg_match($regex, $value) > 0;
    }

    /**
     * Custom regex validator.
     *
     * @example_parameter /test-[0-9]{3}/
     * @example_value     test-123
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_regex($field, array $input, array $params = [], $value = null)
    {
        return preg_match($params[0], $value) > 0;
    }

    /**
     * Determine if the provided value is a valid JSON string.
     *
     * @example_value {"test": true}
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_valid_json_string($field, array $input, array $params = [], $value = null)
    {
         return is_string($input[$field])
             && is_array(json_decode($value, true))
             && (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Check if an input is an array and if the size is more or equal to a specific value.
     *
     * @example_parameter 1
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_valid_array_size_greater($field, array $input, array $params = [], $value = null)
    {
        if (!is_array($input[$field]) || count($input[$field]) < $params[0]) {
            return false;
        }

        return true;
    }

    /**
     * Check if an input is an array and if the size is less or equal to a specific value.
     *
     * @example_parameter 1
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_valid_array_size_lesser($field, array $input, array $params = [], $value = null)
    {
        if (!is_array($input[$field]) || count($input[$field]) > $params[0]) {
            return false;
        }

        return true;
    }

    /**
     * Check if an input is an array and if the size is equal to a specific value.
     *
     * @example_parameter 1
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_valid_array_size_equal($field, array $input, array $params = [], $value = null)
    {
        return !(!is_array($input[$field]) || count($input[$field]) != $params[0]);
    }

    // ** ------------------------- Security Validators --------------------------- ** //

    /**
     * Validate strong password with uppercase, lowercase, number and special character.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_strong_password($field, array $input, array $params = [], $value = null)
    {
        // At least 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special char
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value) > 0;
    }

    /**
     * Validate JWT token format.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_jwt_token($field, array $input, array $params = [], $value = null)
    {
        $parts = explode('.', $value);
        if (count($parts) !== 3) {
            return false;
        }
        
        foreach ($parts as $part) {
            if (!preg_match('/^[A-Za-z0-9_-]+$/', $part)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Validate hash format for specified algorithm.
     *
     * @example_parameter md5
     * @example_parameter sha1  
     * @example_parameter sha256
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_hash($field, array $input, array $params = [], $value = null)
    {
        $algorithm = $params[0] ?? 'md5';
        
        $patterns = [
            'md5' => '/^[a-f0-9]{32}$/i',
            'sha1' => '/^[a-f0-9]{40}$/i', 
            'sha256' => '/^[a-f0-9]{64}$/i',
            'sha512' => '/^[a-f0-9]{128}$/i'
        ];
        
        return isset($patterns[$algorithm]) && preg_match($patterns[$algorithm], $value) > 0;
    }

    /**
     * Detect common SQL injection patterns.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_no_sql_injection($field, array $input, array $params = [], $value = null)
    {
        $patterns = [
            '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION)\b)/i',
            '/(\b(OR|AND)\s+\d+\s*=\s*\d+)/i',
            '/[\'";]/i',
            '/--/i',
            '/\/\*/i',
            '/\*\//i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Enhanced XSS detection beyond basic sanitize_string.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_no_xss($field, array $input, array $params = [], $value = null)
    {
        $patterns = [
            '/<script[^>]*>.*?<\/script>/is',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<iframe[^>]*>.*?<\/iframe>/is',
            '/<object[^>]*>.*?<\/object>/is',
            '/<embed[^>]*>/i',
            '/expression\s*\(/i',
            '/vbscript:/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return false;
            }
        }
        
        return true;
    }

    // ** ------------------------- Modern Web Validators ------------------------- ** //

    /**
     * Validate UUID format (any version).
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_uuid($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value) > 0;
    }

    /**
     * Validate base64 encoded data.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_base64($field, array $input, array $params = [], $value = null)
    {
        return base64_encode(base64_decode($value, true)) === $value;
    }

    /**
     * Validate hexadecimal color code.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_hex_color($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value) > 0;
    }

    /**
     * Validate RGB color format.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_rgb_color($field, array $input, array $params = [], $value = null)
    {
        if (preg_match('/^rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)$/i', $value, $matches)) {
            $r = (int)$matches[1];
            $g = (int)$matches[2]; 
            $b = (int)$matches[3];
            return $r >= 0 && $r <= 255 && $g >= 0 && $g <= 255 && $b >= 0 && $b <= 255;
        }
        return false;
    }

    /**
     * Validate timezone identifier.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_timezone($field, array $input, array $params = [], $value = null)
    {
        return in_array($value, timezone_identifiers_list());
    }

    /**
     * Validate language code (ISO 639).
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_language_code($field, array $input, array $params = [], $value = null)
    {
        // ISO 639-1 (2 letter) or 639-1 with country code (en-US)
        return preg_match('/^[a-z]{2}(-[A-Z]{2})?$/', $value) > 0;
    }

    /**
     * Validate country code (ISO 3166).
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_country_code($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^[A-Z]{2}$/', $value) > 0;
    }

    /**
     * Validate currency code (ISO 4217).
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_currency_code($field, array $input, array $params = [], $value = null)
    {
        $currencies = [
            'USD', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'SEK', 'NZD',
            'MXN', 'SGD', 'HKD', 'NOK', 'TRY', 'ZAR', 'BRL', 'INR', 'KRW', 'RUB'
        ];
        return in_array($value, $currencies);
    }

    // ** ------------------------- Network Validators ---------------------------- ** //

    /**
     * Validate MAC address format.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_mac_address($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', $value) > 0;
    }

    /**
     * Validate domain name format (without protocol).
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_domain_name($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,}$/', $value) > 0;
    }

    /**
     * Validate port number (1-65535).
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_port_number($field, array $input, array $params = [], $value = null)
    {
        return is_numeric($value) && $value >= 1 && $value <= 65535;
    }

    /**
     * Validate social media handle format.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_social_handle($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^@?[A-Za-z0-9_]{1,15}$/', $value) > 0;
    }

    // ** ------------------------- Geographic Validators ------------------------- ** //

    /**
     * Validate latitude coordinate (-90 to 90).
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_latitude($field, array $input, array $params = [], $value = null)
    {
        return is_numeric($value) && $value >= -90 && $value <= 90;
    }

    /**
     * Validate longitude coordinate (-180 to 180).
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_longitude($field, array $input, array $params = [], $value = null)
    {
        return is_numeric($value) && $value >= -180 && $value <= 180;
    }

    /**
     * Validate postal code for specified country.
     *
     * @example_parameter US
     * @example_parameter CA
     * @example_parameter UK
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_postal_code($field, array $input, array $params = [], $value = null)
    {
        $country = $params[0] ?? 'US';
        
        $patterns = [
            'US' => '/^\d{5}(-\d{4})?$/',
            'CA' => '/^[A-Za-z]\d[A-Za-z] ?\d[A-Za-z]\d$/',
            'UK' => '/^[A-Za-z]{1,2}\d[A-Za-z\d]? ?\d[A-Za-z]{2}$/',
            'DE' => '/^\d{5}$/',
            'FR' => '/^\d{5}$/',
            'AU' => '/^\d{4}$/',
            'JP' => '/^\d{3}-\d{4}$/'
        ];
        
        return isset($patterns[$country]) && preg_match($patterns[$country], $value) > 0;
    }

    /**
     * Validate coordinates in lat,lng format.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_coordinates($field, array $input, array $params = [], $value = null)
    {
        if (preg_match('/^(-?\d+\.?\d*),\s*(-?\d+\.?\d*)$/', $value, $matches)) {
            $lat = (float)$matches[1];
            $lng = (float)$matches[2];
            return $lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180;
        }
        return false;
    }

    // ** ------------------------- Enhanced Date/Time Validators ---------------- ** //

    /**
     * Validate that date is in the future.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_future_date($field, array $input, array $params = [], $value = null)
    {
        $timestamp = strtotime($value);
        return $timestamp !== false && $timestamp > time();
    }

    /**
     * Validate that date is in the past.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_past_date($field, array $input, array $params = [], $value = null)
    {
        $timestamp = strtotime($value);
        return $timestamp !== false && $timestamp < time();
    }

    /**
     * Validate that date falls on a business day (Monday-Friday).
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_business_day($field, array $input, array $params = [], $value = null)
    {
        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return false;
        }
        $dayOfWeek = date('N', $timestamp);
        return $dayOfWeek >= 1 && $dayOfWeek <= 5;
    }

    /**
     * Validate time format (HH:MM:SS or HH:MM).
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_valid_time($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $value) > 0;
    }

    /**
     * Validate date falls within specified range.
     *
     * @example_parameter 2024-01-01;2024-12-31
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_date_range($field, array $input, array $params = [], $value = null)
    {
        if (count($params) < 2) {
            return false;
        }
        
        $timestamp = strtotime($value);
        $startTimestamp = strtotime($params[0]);
        $endTimestamp = strtotime($params[1]);
        
        if ($timestamp === false || $startTimestamp === false || $endTimestamp === false) {
            return false;
        }
        
        return $timestamp >= $startTimestamp && $timestamp <= $endTimestamp;
    }

    // ** ------------------------- Mathematical Validators ---------------------- ** //

    /**
     * Validate that number is even.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_even($field, array $input, array $params = [], $value = null)
    {
        return is_numeric($value) && (int)$value % 2 === 0;
    }

    /**
     * Validate that number is odd.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_odd($field, array $input, array $params = [], $value = null)
    {
        return is_numeric($value) && (int)$value % 2 === 1;
    }

    /**
     * Validate that number is prime.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_prime($field, array $input, array $params = [], $value = null)
    {
        if (!is_numeric($value)) {
            return false;
        }
        
        $num = (int)$value;
        if ($num < 2) {
            return false;
        }
        if ($num === 2) {
            return true;
        }
        if ($num % 2 === 0) {
            return false;
        }
        
        for ($i = 3; $i <= sqrt($num); $i += 2) {
            if ($num % $i === 0) {
                return false;
            }
        }
        
        return true;
    }

    // ** ------------------------- Content Validators ---------------------------- ** //

    /**
     * Validate word count within specified range.
     *
     * @example_parameter min,10,max,500
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_word_count($field, array $input, array $params = [], $value = null)
    {
        $wordCount = str_word_count($value);
        
        for ($i = 0; $i < count($params); $i += 2) {
            if ($params[$i] === 'min' && isset($params[$i + 1])) {
                if ($wordCount < (int)$params[$i + 1]) {
                    return false;
                }
            }
            if ($params[$i] === 'max' && isset($params[$i + 1])) {
                if ($wordCount > (int)$params[$i + 1]) {
                    return false;
                }
            }
        }
        
        return true;
    }

    /**
     * Validate camelCase format.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_camel_case($field, array $input, array $params = [], $value = null)
    {
        return !empty($value) && preg_match('/^[a-z][a-zA-Z0-9]*$/', $value) > 0;
    }

    /**
     * Validate snake_case format.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_snake_case($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^[a-z][a-z0-9_]*$/', $value) > 0;
    }

    /**
     * Validate URL slug format.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate_url_slug($field, array $input, array $params = [], $value = null)
    {
        return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value) > 0;
    }
}
