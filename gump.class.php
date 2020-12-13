<?php

use GUMP\ArrayHelpers;
use GUMP\EnvHelpers;

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

    public static $basic_tags = '<br><p><a><strong><b><i><em><img><blockquote><code><dd><dl><hr><h1><h2><h3><h4><h5><h6><label><ul><li><span><sub><sup>';

    public static $en_noise_words = "about,after,all,also,an,and,another,any,are,as,at,be,because,been,before,
                                     being,between,both,but,by,came,can,come,could,did,do,each,for,from,get,
                                     got,has,had,he,have,her,here,him,himself,his,how,if,in,into,is,it,its,it's,like,
                                     make,many,me,might,more,most,much,must,my,never,now,of,on,only,or,other,
                                     our,out,over,said,same,see,should,since,some,still,such,take,than,that,
                                     the,their,them,then,there,these,they,this,those,through,to,too,under,up,
                                     very,was,way,we,well,were,what,where,which,while,who,with,would,you,your,a,
                                     b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,$,1,2,3,4,5,6,7,8,9,0,_";

    private static $alpha_regex = 'a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝŸÑàáâãäåçèéêëìíîïðòóôõöùúûüýÿñ';

    public static $trues = ['1', 1, 'true', true, 'yes', 'on'];
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
     * Perform XSS clean to prevent cross site scripting.
     *
     * @param array $data
     * @return array
     */
    public static function xss_clean(array $data)
    {
        foreach ($data as $k => $v) {
            $data[$k] = filter_var($v, FILTER_SANITIZE_STRING);
        }

        return $data;
    }

    /**
     * An empty value for us is: null, empty string or empty array
     *
     * @param  $value
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

                $value = filter_var($value, FILTER_SANITIZE_STRING);
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

            return array_map(function($value, $key) use($rules) {
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

        // v2
        if (is_array($rules) && is_array($rules[0])) {
            $found = array_filter($rules, function($item) use($require_type_of_rules) {
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
        $values = !is_array($input[$field]) ? [ $input[$field] ] : $input[$field];

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
     * @param $field
     * @param array $params
     * @param string $message
     * @param callable|null $transformer
     * @return string
     */
    private function process_error_message($field, array $params, string $message, callable $transformer = null)
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

        $transformer = static function($replace) use($field_class) {
            $replace['{field}'] = sprintf('<span class="%s">%s</span>', $field_class, $replace['{field}']);
            return $replace;
        };

        foreach ($this->errors as $error) {
            $message = $this->get_error_message($messages, $error['field'], $error['rule']);
            $result[] = $this->process_error_message($error['field'],  $error['params'],  $message, $transformer);
        }

        if ($convert_to_string) {
            return array_reduce($result, static function($prev, $next) use($error_class) {
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
        return filter_var($value, FILTER_SANITIZE_STRING);
    }

    /**
     * Converts ['1', 1, 'true', true, 'yes', 'on'] to true, anything else is false ('on' is useful for form checkboxes).
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
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
    protected function validate_required($field, array $input, array $params = [], $value)
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
    protected function validate_contains($field, array $input, array $params)
    {
        $value = mb_strtolower(trim($input[$field]));

        $params = array_map(static function($value) {
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
    protected function validate_contains_list($field, $input, array $params)
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
    protected function validate_doesnt_contain_list($field, $input, array $params)
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
    protected function validate_boolean($field, array $input, array $params = [], $value)
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
    protected function validate_valid_email($field, array $input, array $params = [], $value)
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
    protected function validate_max_len($field, array $input, array $params = [], $value)
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
    protected function validate_min_len($field, array $input, array $params = [], $value)
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
     *
     * @return bool
     */
    protected function validate_exact_len($field, array $input, array $params = [], $value)
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
    protected function validate_between_len($field, $input, array $params, $value)
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
    protected function validate_alpha_numeric($field, array $input, array $params = [], $value)
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
    protected function validate_alpha_dash($field, array $input, array $params = [], $value)
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
    protected function validate_alpha_numeric_dash($field, array $input, array $params = [], $value)
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
    protected function validate_alpha_numeric_space($field, array $input, array $params = [], $value)
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
    protected function validate_alpha_space($field, array $input, array $params = [], $value)
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
    protected function validate_numeric($field, array $input, array $params = [], $value)
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
    protected function validate_integer($field, array $input, array $params = [], $value)
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
    protected function validate_float($field, array $input, array $params = [], $value)
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
    protected function validate_valid_url($field, array $input, array $params = [], $value)
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
    protected function validate_url_exists($field, array $input, array $params = [], $value)
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
    protected function validate_valid_ip($field, array $input, array $params = [], $value)
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
    protected function validate_valid_ipv4($field, array $input, array $params = [], $value)
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
    protected function validate_valid_ipv6($field, array $input, array $params = [], $value)
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
    protected function validate_valid_cc($field, array $input, array $params = [], $value)
    {
        $number = preg_replace('/\D/', '', $value);

        $number_length = mb_strlen($number);

        /**
         * Bail out if $number_length is 0.
         * This can be the case if a user has entered only alphabets
         *
         * @since 1.5
         */
        if ($number_length == 0 ) {
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
    protected function validate_valid_name($field, array $input, array $params = [], $value)
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
    protected function validate_street_address($field, array $input, array $params = [], $value)
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
    protected function validate_iban($field, array $input, array $params = [], $value)
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
    protected function validate_date($field, array $input, array $params = [], $value)
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
    protected function validate_min_age($field, array $input, array $params, $value)
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
     * @return bool
     */
    protected function validate_max_numeric($field, array $input, array $params = [], $value)
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
     * @return bool
     */
    protected function validate_min_numeric($field, array $input, array $params = [], $value)
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
    protected function validate_starts($field, array $input, array $params, $value)
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
    protected function validate_required_file($field, array $input, array $params = [], $value)
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
    protected function validate_extension($field, $input, array $params, $value)
    {
        if (is_array($input[$field]) && $input[$field]['error'] === 0) {
            $params = array_map(function($v) {
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
    protected function validate_equalsfield($field, array $input, array $params, $value)
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
    protected function validate_guidv4($field, array $input, array $params = [], $value)
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
    protected function validate_phone_number($field, array $input, array $params = [], $value)
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
    protected function validate_regex($field, array $input, array $params = [], $value)
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
    protected function validate_valid_json_string($field, array $input, array $params = [], $value)
    {
        if (!is_string($input[$field]) || !is_object(json_decode($value))) {
            return false;
        }

        return true;
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
    protected function validate_valid_array_size_greater($field, array $input, array $params, $value)
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
    protected function validate_valid_array_size_lesser($field, array $input, array $params = [], $value)
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
    protected function validate_valid_array_size_equal($field, array $input, array $params = [], $value)
    {
        return !(!is_array($input[$field]) || count($input[$field]) != $params[0]);
    }

    /**
     * Determine if the provided value is a valid Twitter account.
     *
     * @param string $field
     * @param array $input
     * @param array $params
     * @param mixed $value
     *
     * @return bool
     * @throws Exception if Twitter API has changed, in such case report on GitHub please.
     */
    protected function validate_valid_twitter($field, array $input, array $params = [], $value)
    {
        $json = EnvHelpers::file_get_contents("http://twitter.com/users/username_available?username=".$input[$field]);

        $result = json_decode($json);

        if (!isset($result->reason)) {
            throw new Exception('Twitter JSON response changed. Please report this on GitHub.');
        }

        return $result->reason === "taken";
    }
}
