<?php
/**
 * GUMP - A fast, extensible PHP input validation class.
 *
 * @author Sean Nieuwoudt (http://twitter.com/SeanNieuwoudt)
 * @author Filis Futsarov (http://twitter.com/filisdev)
 *
 * @version 1.6
 */

use GUMP\Helpers;

class GUMP
{
    // Singleton instance of GUMP
    protected static $instance = null;

    // Validation rules for execution
    protected $validation_rules = [];

    // Filter rules for execution
    protected $filter_rules = [];

    // Instance attribute containing errors from last run
    protected $errors = [];

    // Contain readable field names that have been set manually
    protected static $fields = [];

    // Custom validation methods
    protected static $validation_methods = [];

    // Custom validation methods error messages and custom ones
    protected static $validation_methods_errors = [];

    // Customer filter methods
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

    // field characters below will be replaced with a space.
    protected $fieldCharsToRemove = array('_', '-');

    protected $lang;


    // ** ------------------------- Validation Helpers ---------------------------- ** //

    public function __construct(string $lang = 'en')
    {
        $lang_file_location = __DIR__.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$lang.'.php';

        if (!Helpers::file_exists($lang_file_location)) {
             throw new Exception('Language with key "'.$lang.'" does not exist');
        }

        $this->lang = $lang;
    }

    /**
     * Shorthand method for inline validation.
     *
     * @param array $data       The data to be validated
     * @param array $validators The GUMP validators
     *
     * @return mixed True(boolean) or the array of error messages
     */
    public static function is_valid(array $data, array $validators)
    {
        $gump = self::get_instance();

        $gump->validation_rules($validators);

        if ($gump->run($data) === false) {
            return $gump->get_readable_errors(false);
        } else {
            return true;
        }
    }

    /**
     * Shorthand method for running only the data filters.
     *
     * @param array $data
     * @param array $filters
     *
     * @return mixed
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
     * @static
     *
     * @param array $data
     *
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
     * @param string   $rule
     * @param callable $callback
     * @param string   $error_message optional for backwards compatibility
     *
     * @return bool
     *
     * @throws Exception
     */
    public static function add_validator(string $rule, callable $callback, string $error_message = null)
    {
        $method = 'validate_'.$rule;

        if (method_exists(__CLASS__, $method) || isset(self::$validation_methods[$rule])) {
            throw new Exception("Validator rule '$rule' already exists.");
        }

        self::$validation_methods[$rule] = $callback;
        if ($error_message) {
            self::$validation_methods_errors[$rule] = $error_message;
        }

        return true;
    }

    /**
     * Adds a custom filter using a callback function.
     *
     * @param string   $rule
     * @param callable $callback
     *
     * @return bool
     *
     * @throws Exception
     */
    public static function add_filter(string $rule, callable $callback)
    {
        $method = 'filter_'.$rule;

        if (method_exists(__CLASS__, $method) || isset(self::$filter_methods[$rule])) {
            throw new Exception("Filter rule '$rule' already exists.");
        }

        self::$filter_methods[$rule] = $callback;

        return true;
    }

    /**
     * Helper method to extract an element from an array safely
     *
     * @param  mixed $key
     * @param  array $array
     * @param  mixed $default
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
     *
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
     * Getter/Setter for the filter rules.
     *
     * @param array $rules
     *
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
     * @param string $rules_delimiter
     * @param string $parameters_delimiters
     *
     * @return array
     *
     * @throws Exception
     */
    public function run(array $data, $check_fields = false, $rules_delimiter='|', $parameters_delimiters=',')
    {
        $data = $this->filter($data, $this->filter_rules(), $rules_delimiter, $parameters_delimiters);

        $validated = $this->validate(
            $data, $this->validation_rules(),
            $rules_delimiter, $parameters_delimiters
        );

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
            $this->errors[] = array(
                'field' => $field,
                'value' => $data[$field],
                'rule' => 'mismatch',
                'param' => null,
            );
        }
    }

    /**
     * Sanitize the input data.
     *
     * @param array $input
     * @param null  $fields
     * @param bool  $utf8_encode
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
            } else {
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

                        if ($current_encoding != 'UTF-8' && $current_encoding != 'UTF-16') {
                            $value = iconv($current_encoding, 'UTF-8', $value);
                        }
                    }

                    $value = filter_var($value, FILTER_SANITIZE_STRING);
                }

                $return[$field] = $value;
            }
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
     * If any rule's parameter contains either '|' or ',', the corresponding default separator can be changed
     *
     * @param mixed  $input
     * @param array  $ruleset
     * @param string $rules_delimiter
     * @param string $parameters_delimiter
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function validate(array $input, array $ruleset, string $rules_delimiter='|', string $parameters_delimiter=',')
    {
        $this->errors = [];

        foreach ($ruleset as $field => $rules) {
            $rules = explode($rules_delimiter, $rules);

            if (!isset($input[$field])) {
                $input[$field] = null;
            }

            $require_rule_found = $this->find_required_rule($rules);

            foreach ($rules as $rule) {
                if (is_null($require_rule_found) && self::is_empty($input[$field])) continue;
                if (!$this->field_doesnt_have_errors($field, $this->errors)) continue;

                $parsed_rule = $this->parse_rule($rule);
                $result = $this->call_rule($parsed_rule['rule'], $field, $input, $parsed_rule['param']);

                if (is_array($result)) {
                    $this->errors[] = $result;
                }
            }
        }

        return (count($this->errors) > 0) ? $this->errors : true;
    }

    private function find_required_rule(array $rules)
    {
        $require_type_of_rules = ['required', 'required_file'];
        $found = array_values(array_intersect($require_type_of_rules, $rules));

        return count($found) > 0 ? $found[0] : null;
    }

    private function field_doesnt_have_errors(string $field, array $errors)
    {
        return array_search($field, array_column($errors, 'field')) === false;
    }

    private function parse_rule(string $rule)
    {
        $result = [];
        $result['rule'] = $rule;
        $result['param'] = null;

        if (strstr($rule, ',') !== false) {
            [$rule, $param] = explode(',', $rule);

            $result['rule'] = $rule;
            $result['param'] = $param;
        }

        return $result;
    }

    private function rule_to_method(string $rule)
    {
        return sprintf('validate_%s', $rule);
    }

    private function call_rule(string $rule, string $field, $input, string $rule_param = null)
    {
        $method = $this->rule_to_method($rule);

        if (is_callable([$this, $method])) {
            $result = $this->$method($field, $input, $rule_param);

            // is_array check for backward compatibility
            return (is_array($result) || $result === false)
                ? $this->generate_error_array($field, $input[$field], $method, $rule_param)
                : true;
        } elseif (isset(self::$validation_methods[$rule])) {
            $result = call_user_func(self::$validation_methods[$rule], $field, $input, $rule_param);

            return ($result === false)
                ? $this->generate_error_array($field, $input[$field], $rule, $rule_param)
                : true;
        }

        throw new Exception("Validator method '$method' does not exist.");
    }

    private function generate_error_array(string $field, $value, string $rule, string $rule_param = null)
    {
        return [
            'field' => $field,
            'value' => $value,
            'rule' => $rule,
            'param' => $rule_param
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
     * Usage:
     *
     * GUMP::set_field_names(array(
     *  "name" => "My Lovely Name",
     *  "username" => "My Beloved Username",
     * ));
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
     * Usage:
     *
     * GUMP::set_error_messages(array(
     *  "validate_required"     => "{field} is required",
     *  "validate_valid_email"  => "{field} must be a valid email",
     * ));
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
     * Get error messages.
     *
     * @return array
     */
    protected function get_messages()
    {
        $lang_file = __DIR__.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$this->lang.'.php';
        $messages = include $lang_file;

        if (count(self::$validation_methods_errors) > 0) {
            $messages = array_merge($messages, self::$validation_methods_errors);
        }

        return $messages;
    }

    /**
     * Process the validation errors and return human readable error messages.
     *
     * @param bool   $convert_to_string = false
     * @param string $field_class
     * @param string $error_class
     *
     * @return array
     * @return string
     * @throws Exception when validator doesn't have a set error message
     */
    public function get_readable_errors(bool $convert_to_string = false, string $field_class = 'gump-field', string $error_class = 'gump-error-message')
    {
        if (empty($this->errors)) {
            return ($convert_to_string) ? null : [];
        }

        $resp = [];

        // Error messages
        $messages = $this->get_messages();

        foreach ($this->errors as $e) {
            $field = ucwords(str_replace($this->fieldCharsToRemove, chr(32), $e['field']));
            $param = $e['param'];

            // Let's fetch explicitly if the field names exist
            if (array_key_exists($e['field'], self::$fields)) {
                $field = self::$fields[$e['field']];

                // If param is a field (i.e. equalsfield validator)
                if (array_key_exists($param, self::$fields)) {
                    $param = self::$fields[$e['param']];
                }
            }

            // Messages
            if (isset($messages[$e['rule']])) {
                if (is_array($param)) {
                    $param = implode(', ', $param);
                }
                $message = str_replace('{param}', $param, str_replace('{field}', '<span class="'.$field_class.'">'.$field.'</span>', $messages[$e['rule']]));
                $resp[] = $message;
            } else {
                throw new Exception('Rule "'.$e['rule'].'" does not have an error message');
            }
        }

        if (!$convert_to_string) {
            return $resp;
        } else {
            $buffer = '';
            foreach ($resp as $s) {
                $buffer .= "<span class=\"$error_class\">$s</span>";
            }
            return $buffer;
        }
    }

    /**
     * Process the validation errors and return an array of errors with field names as keys.
     *
     * @param $convert_to_string
     *
     * @return array | null (if empty)
     * @throws Exception
     */
    public function get_errors_array(bool $convert_to_string = false)
    {
        if (empty($this->errors)) {
            return ($convert_to_string) ? null : [];
        }

        $resp = [];

        // Error messages
        $messages = $this->get_messages();

        foreach ($this->errors as $e) {
            $field = ucwords(str_replace(array('_', '-'), chr(32), $e['field']));
            $param = $e['param'];

            // Let's fetch explicitly if the field names exist
            if (array_key_exists($e['field'], self::$fields)) {
                $field = self::$fields[$e['field']];

                // If param is a field (i.e. equalsfield validator)
                if (array_key_exists($param, self::$fields)) {
                    $param = self::$fields[$e['param']];
                }
            }

            if (!isset($messages[$e['rule']])) {
                throw new Exception('Rule "'.$e['rule'].'" does not have an error message');
            }

            // show first validation error
            if (!isset($resp[$e['field']])) {
                if (is_array($param)) {
                    $param = implode(', ', $param);
                }
                $message = str_replace('{param}', $param, str_replace('{field}', $field, $messages[$e['rule']]));
                $resp[$e['field']] = $message;
            }
        }

        return $resp;
    }

    /**
     * Filter the input data according to the specified filter set.
     * If any filter's parameter contains either '|' or ',', the corresponding default separator can be changed
     *
     * @param mixed  $input
     * @param array  $filterset
     * @param string $filters_delimeter
     * @param string $parameters_delimiter
     *
     * @throws Exception
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function filter(array $input, array $filterset, string $filters_delimeter='|', string $parameters_delimiter=',')
    {
        foreach ($filterset as $field => $filters) {
            if (!array_key_exists($field, $input)) {
                continue;
            }

            $filters = explode($filters_delimeter, $filters);

            foreach ($filters as $filter) {
                $params = null;

                if (strstr($filter, $parameters_delimiter) !== false) {
                    $filter = explode($parameters_delimiter, $filter);
                    $params = array_slice($filter, 1, count($filter) - 1);

                    $filter = $filter[0];
                }

                if (is_array($input[$field])) {
                    $input_array = &$input[$field];
                } else {
                    $input_array = array(&$input[$field]);
                }

                foreach ($input_array as &$value) {
                    $value = $this->call_filter($filter, $value, $params);
                }
            }
        }

        return $input;
    }

    public function call_filter(string $filter, $value, $params)
    {
        if (is_callable(array($this, 'filter_'.$filter))) {
            $method = 'filter_'.$filter;
            return $this->$method($value, $params);
        } elseif (function_exists($filter)) {
            return $filter($value);
        } elseif (isset(self::$filter_methods[$filter])) {
            return call_user_func(self::$filter_methods[$filter], $value, $params);
        }

        throw new Exception("Filter method '$filter' does not exist.");
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
    protected function filter_noise_words($value, $params = null)
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
    protected function filter_rmpunctuation($value, $params = null)
    {
        return preg_replace("/(?![.=$'€%-])\p{P}/u", '', $value);
    }

    /**
     * Sanitize the string by removing any script tags.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_sanitize_string($value, $params = null)
    {
        return filter_var($value, FILTER_SANITIZE_STRING);
    }

    /**
     * Sanitize the string by urlencoding characters.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_urlencode($value, $params = null)
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
    protected function filter_htmlencode($value, $params = null)
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
    protected function filter_sanitize_email($value, $params = null)
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
    protected function filter_sanitize_numbers($value, $params = null)
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
    protected function filter_sanitize_floats($value, $params = null)
    {
        return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * Filter out all HTML tags except the defined basic tags.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_basic_tags($value, $params = null)
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
    protected function filter_whole_number($value, $params = null)
    {
        return intval($value);
    }

    /**
     * Convert MS Word special characters to web safe characters. ([“, ”, ‘, ’, –, …] => [", ", ', ', -, ...])
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_ms_word_characters($value, $params = null)
    {
        $word_open_double  = '“';
        $word_close_double = '”';
        $web_safe_double   = '"';

        $value = str_replace(array($word_open_double, $word_close_double), $web_safe_double, $value);

        $word_open_single  = '‘';
        $word_close_single = '’';
        $web_safe_single   = "'";

        $value = str_replace(array($word_open_single, $word_close_single), $web_safe_single, $value);

        $word_em     = '–';
        $web_safe_em = '-';

        $value = str_replace($word_em, $web_safe_em, $value);

        $word_ellipsis = '…';
        $web_ellipsis  = '...';

        $value = str_replace($word_ellipsis, $web_ellipsis, $value);

        return $value;
    }

    /**
     * Converts to lowercase.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_lower_case($value, $params = null)
    {
        return strtolower($value);
    }

    /**
     * Converts to uppercase.
     *
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    protected function filter_upper_case($value, $params = null)
    {
        return strtoupper($value);
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
    protected function filter_slug($value, $params = null)
    {
        $delimiter = '-';
        return strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $value))))), $delimiter));
    }

    // ** ------------------------- Validators ------------------------------------ ** //

    /**
     * Ensures the specified key value exists and is not empty.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_required($field, $input, $param = null)
    {
        if (isset($input[$field]) && !self::is_empty($input[$field])) {
            return;
        }

        return false;
    }


    /**
     * Verify that a value is contained within the pre-defined value set.
     *
     * @example_parameter 'value1' 'space separated value'
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_contains($field, $input, $param = null)
    {
        $param = trim(strtolower($param));

        $value = trim(strtolower($input[$field]));

        if (preg_match_all('#\'(.+?)\'#', $param, $matches, PREG_PATTERN_ORDER)) {
            $param = $matches[1];
        } else {
            $param = explode(chr(32), $param);
        }

        if (in_array($value, $param)) { // valid, return nothing
            return;
        }

        return false;
    }

    /**
     * Verify that a value is contained within the pre-defined value set. Error message will NOT show the list of possible values.
     *
     * @example_parameter value1;value2
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_contains_list($field, $input, $param = null)
    {
        $param = trim(strtolower($param));

        $value = trim(strtolower($input[$field]));

        $param = explode(';', $param);

        // consider: in_array(strtolower($value), array_map('strtolower', $param)

        if (in_array($value, $param)) { // valid, return nothing
            return;
        } else {
            return false;
        }
    }

    /**
     * Verify that a value is contained within the pre-defined value set. Error message will NOT show the list of possible values.
     *
     * @example_parameter value;value;value
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_doesnt_contain_list($field, $input, $param = null)
    {
        $param = trim(strtolower($param));

        $value = trim(strtolower($input[$field]));

        $param = explode(';', $param);

        if (!in_array($value, $param)) { // valid, return nothing
            return;
        } else {
            return false;
        }
    }

    /**
     * Determine if the provided email has valid format.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_valid_email($field, $input, $param = null)
    {
        if (!filter_var($input[$field], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
    }

    /**
     * Determine if the provided value length is less or equal to a specific value.
     *
     * @example_parameter 240
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_max_len($field, $input, $param = null)
    {
        if (Helpers::functionExists('mb_strlen')) {
            if (mb_strlen($input[$field]) <= (int) $param) {
                return;
            }
        } else if (strlen($input[$field]) <= (int) $param) {
            return;
        }

        return false;
    }

    /**
     * Determine if the provided value length is more or equal to a specific value.
     *
     * @example_parameter 4
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_min_len($field, $input, $param = null)
    {
        if (Helpers::functionExists('mb_strlen')) {
            if (mb_strlen($input[$field]) >= (int) $param) {
                return;
            }
        } else if (strlen($input[$field]) >= (int) $param) {
            return;
        }

        return false;
    }

    /**
     * Determine if the provided value length matches a specific value.
     *
     * @example_parameter 5
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_exact_len($field, $input, $param = null)
    {
        if (Helpers::functionExists('mb_strlen')) {
            if (mb_strlen($input[$field]) == (int) $param) {
                return;
            }
        } else if (strlen($input[$field]) == (int) $param) {
            return;
        }

        return false;
    }

    /**
     * Determine if the provided value contains only alpha characters.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_alpha($field, $input, $param = null)
    {
        if (!preg_match('/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i', $input[$field]) !== false) {
            return false;
        }
    }

    /**
     * Determine if the provided value contains only alpha-numeric characters.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_alpha_numeric($field, $input, $param = null)
    {
        if (!preg_match('/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i', $input[$field]) !== false) {
            return false;
        }
    }

    /**
     * Determine if the provided value contains only alpha characters with dashed and underscores.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_alpha_dash($field, $input, $param = null)
    {
        if (!preg_match('/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ_-])+$/i', $input[$field]) !== false) {
            return false;
        }
    }

    /**
     * Determine if the provided value contains only alpha numeric characters with dashed and underscores.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_alpha_numeric_dash($field, $input, $param = null)
    {
        if (!preg_match('/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ_-])+$/i', $input[$field]) !== false) {
            return false;
        }
    }

    /**
     * Determine if the provided value contains only alpha numeric characters with spaces.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_alpha_numeric_space($field, $input, $param = null)
    {
        if (!preg_match("/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\s])+$/i", $input[$field]) !== false) {
            return false;
        }
    }

    /**
     * Determine if the provided value contains only alpha characters with spaces.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_alpha_space($field, $input, $param = null)
    {
        if (!preg_match("/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\s])+$/i", $input[$field]) !== false) {
            return false;
        }
    }

    /**
     * Determine if the provided value is a valid number or numeric string.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_numeric($field, $input, $param = null)
    {
        if (!is_numeric($input[$field])) {
            return false;
        }
    }

    /**
     * Determine if the provided value is a valid integer.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_integer($field, $input, $param = null)
    {
        if (filter_var($input[$field], FILTER_VALIDATE_INT) === false || is_bool($input[$field]) || is_null($input[$field])) {
            return false;
        }
    }

    /**
     * Determine if the provided value is a PHP accepted boolean. Also returns true for strings: yes/no, on/off, 1/0, true/false.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_boolean($field, $input, $param = null)
    {
        if (!isset($input[$field]) || empty($input[$field]) && $input[$field] !== 0) {
            return;
        }

        $booleans = array('1',1, '0',0, 'true',true, 'false',false, 'yes','no', 'on','off');
        if (in_array($input[$field], $booleans, true)) {
            return;
        }

        return false;
    }

    /**
     * Determine if the provided value is a valid float.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_float($field, $input, $param = null)
    {
        if (filter_var($input[$field], FILTER_VALIDATE_FLOAT) === false) {
            return false;
        }
    }

    /**
     * Determine if the provided value is a valid URL.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_valid_url($field, $input, $param = null)
    {
        if (!filter_var($input[$field], FILTER_VALIDATE_URL)) {
            return false;
        }
    }

    /**
     * Determine if a URL exists & is accessible.
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_url_exists($field, $input, $param = null)
    {
        $url = parse_url(strtolower($input[$field]));

        if (isset($url['host'])) {
            $url = $url['host'];
        }

        if (Helpers::functionExists('checkdnsrr') && Helpers::functionExists('idn_to_ascii')) {
            if (Helpers::checkdnsrr(idn_to_ascii($url, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46), 'A') === false) {
                return false;
            }
        } elseif (Helpers::gethostbyname($url) == $url) {
            return false;
        }
    }

    /**
     * Determine if the provided value is a valid IP address.
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_valid_ip($field, $input, $param = null)
    {
        if (!filter_var($input[$field], FILTER_VALIDATE_IP) !== false) {
            return false;
        }
    }

    /**
     * Determine if the provided value is a valid IPv4 address.
     *
     * @see What about private networks? What about loop-back address? 127.0.0.1 http://en.wikipedia.org/wiki/Private_network
     * @see http://pastebin.com/UvUPPYK0
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_valid_ipv4($field, $input, $param = null)
    {
        if (!filter_var($input[$field], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // removed !== FALSE

            return false;
        }
    }

    /**
     * Determine if the provided value is a valid IPv6 address.
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_valid_ipv6($field, $input, $param = null)
    {
        if (!filter_var($input[$field], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return false;
        }
    }

    /**
     * Determine if the input is a valid credit card number.
     *
     * @see http://stackoverflow.com/questions/174730/what-is-the-best-way-to-validate-a-credit-card-in-php
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_valid_cc($field, $input, $param = null)
    {
        $number = preg_replace('/\D/', '', $input[$field]);

        if (Helpers::functionExists('mb_strlen')) {
            $number_length = mb_strlen($number);
        } else {
            $number_length = strlen($number);
        }

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

        if ($total % 10 == 0) {
            return; // Valid
        }

        return false;
    }

    /**
     * Determine if the input is a valid human name.
     *
     * @see https://github.com/Wixel/GUMP/issues/5
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_valid_name($field, $input, $param = null)
    {
        if (!preg_match("/^([a-z \p{L} '-])+$/i", $input[$field]) !== false) {
            return false;
        }
    }

    /**
     * Determine if the provided input is likely to be a street address using weak detection.
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_street_address($field, $input, $param = null)
    {
        // Theory: 1 number, 1 or more spaces, 1 or more words
        $hasLetter = preg_match('/[a-zA-Z]/', $input[$field]);
        $hasDigit = preg_match('/\d/', $input[$field]);
        $hasSpace = preg_match('/\s/', $input[$field]);

        $passes = $hasLetter && $hasDigit && $hasSpace;

        if (!$passes) {
            return false;
        }
    }

    /**
     * Determine if the provided value is a valid IBAN.
     *
     * @param string $field
     * @param array $input
     * @param null $param
     *
     * @return mixed
     */
    protected function validate_iban($field, $input, $param = null)
    {
        static $character = array(
            'A' => 10, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16,
            'H' => 17, 'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21, 'M' => 22,
            'N' => 23, 'O' => 24, 'P' => 25, 'Q' => 26, 'R' => 27, 'S' => 28,
            'T' => 29, 'U' => 30, 'V' => 31, 'W' => 32, 'X' => 33, 'Y' => 34,
            'Z' => 35, 'B' => 11
        );

        if (!preg_match("/\A[A-Z]{2}\d{2} ?[A-Z\d]{4}( ?\d{4}){1,} ?\d{1,4}\z/", $input[$field])) {
            return false;
        }

        $iban = str_replace(' ', '', $input[$field]);
        $iban = substr($iban, 4).substr($iban, 0, 4);
        $iban = strtr($iban, $character);

        if (bcmod($iban, 97) != 1) {
            return false;
        }
    }

    /**
     * Determine if the provided input is a valid date (ISO 8601) or specify a custom format (optional).
     *
     * @example_parameter d/m/Y
     *
     * @param string $field
     * @param string $input date ('Y-m-d') or datetime ('Y-m-d H:i:s')
     * @param string $param Custom date format
     *
     * @return mixed
     */
    protected function validate_date($field, $input, $param = null)
    {
        // Default
        if (!$param) {
            $cdate1 = date('Y-m-d', strtotime($input[$field]));
            $cdate2 = date('Y-m-d H:i:s', strtotime($input[$field]));

            if ($cdate1 != $input[$field] && $cdate2 != $input[$field]) {
                return false;
            }
        } else {
            $date = \DateTime::createFromFormat($param, $input[$field]);

            if ($date === false || $input[$field] != date($param, $date->getTimestamp())) {
                return false;
            }
        }
    }

    /**
     * Determine if the provided input meets age requirement (ISO 8601).
     *
     * @example_parameter 18
     *
     * @param string $field
     * @param string $input
     * @param string $param int
     *
     * @return mixed
     */
    protected function validate_min_age($field, $input, $param = null)
    {
        $inputDatetime = new DateTime(Helpers::date('Y-m-d', strtotime($input[$field])));
        $todayDatetime = new DateTime(Helpers::date('Y-m-d'));

        $interval = $todayDatetime->diff($inputDatetime);
        $yearsPassed = $interval->y;

        if ($yearsPassed >= $param) {
            return;
        }

        return false;
    }

    /**
     * Determine if the provided numeric value is lower or equal to a specific value.
     *
     * @example_parameter 50
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_max_numeric($field, $input, $param = null)
    {
        if (is_numeric($input[$field]) && is_numeric($param) && ($input[$field] <= $param)) {
            return;
        }

        return false;
    }

    /**
     * Determine if the provided numeric value is higher or equal to a specific value.
     *
     * @example_parameter 1
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_min_numeric($field, $input, $param = null)
    {
        if (is_numeric($input[$field]) && is_numeric($param) && ($input[$field] >= $param)) {
            return;
        }

        return false;
    }

    /**
     * Determine if the provided value starts with param.
     *
     * @example_parameter Z
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_starts($field, $input, $param = null)
    {
        if (strpos($input[$field], $param) !== 0) {
            return false;
        }
    }

    /**
     * Determine if the file was successfully uploaded.
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_required_file($field, $input, $param = null)
    {
        if (isset($input[$field]) && is_array($input[$field]) && $input[$field]['error'] === 0) {
            return;
        }

        return false;
    }

    /**
     * Check the uploaded file for extension. Doesn't check mime-type yet.
     *
     * @example_parameter png;jpg;gif
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_extension($field, $input, $param = null)
    {
        if (is_array($input[$field]) && $input[$field]['error'] === 0) {
            $param = trim(strtolower($param));
            $allowed_extensions = explode(';', $param);

            $path_info = pathinfo($input[$field]['name']);
            $extension = isset($path_info['extension']) ? $path_info['extension'] : false;

            if ($extension && in_array(strtolower($extension), $allowed_extensions)) {
                return;
            }
        }

        return false;
    }

    /**
     * Determine if the provided field value equals current field value.
     *
     * @example_parameter other_field_name
     *
     * @param string $field
     * @param string $input
     * @param string $param field to compare with
     *
     * @return mixed
     */
    protected function validate_equalsfield($field, $input, $param = null)
    {
        if ($input[$field] == $input[$param]) {
            return;
        }

        return false;
    }

    /**
     * Determine if the provided field value is a valid GUID (v4)
     *
     * @param  string $field
     * @param  string $input
     * @param  string $param
     * @return mixed
     */
    protected function validate_guidv4($field, $input, $param = null)
    {
        if (preg_match("/\{?[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}\}?$/", $input[$field])) {
            return;
        }

        return false;
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
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_phone_number($field, $input, $param = null)
    {
        $regex = '/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i';

        if (!preg_match($regex, $input[$field])) {
            return false;
        }
    }

    /**
     * Custom regex validator.
     *
     * @example_parameter /test-[0-9]{3}/
     * @example_value     test-123
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_regex($field, $input, $param = null)
    {
        $regex = $param;
        if (!preg_match($regex, $input[$field])) {
            return false;
        }
    }

    /**
     * Determine if the provided value is a valid JSON string.
     *
     * @example_value {"test": true}
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_valid_json_string($field, $input, $param = null)
    {
        if (!is_string($input[$field]) || !is_object(json_decode($input[$field]))) {
            return false;
        }
    }

    /**
     * Check if an input is an array and if the size is more or equal to a specific value.
     *
     * @example_parameter 1
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_valid_array_size_greater($field, $input, $param = null)
    {
        if (!is_array($input[$field]) || sizeof($input[$field]) < (int)$param) {
            return false;
        }
    }

    /**
     * Check if an input is an array and if the size is less or equal to a specific value.
     *
     * @example_parameter 1
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_valid_array_size_lesser($field, $input, $param = null)
    {
        if (!is_array($input[$field]) || sizeof($input[$field]) > (int)$param) {
            return false;
        }
    }

    /**
     * Check if an input is an array and if the size is equal to a specific value.
     *
     * @example_parameter 1
     *
     * @param string $field
     * @param array  $input
     *
     * @return mixed
     */
    protected function validate_valid_array_size_equal($field, $input, $param = null)
    {
        if (!is_array($input[$field]) || sizeof($input[$field]) != (int)$param) {
            return false;
        }
    }

    /**
     * Determine if the provided value is a valid Twitter account.
     *
     * @param  string $field
     * @param  array  $input
     * @return mixed
     */
    protected function validate_valid_twitter($field, $input, $param = null)
    {
        $json = Helpers::file_get_contents("http://twitter.com/users/username_available?username=".$input[$field]);

        $result = json_decode($json);

        if ($result->reason !== "taken") {
            return false;
        }
    }
}