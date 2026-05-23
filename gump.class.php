<?php

declare(strict_types=1);

use GUMP\ArrayHelpers;
use GUMP\EnvHelpers;
use GUMP\Filtering\ClosureFilter;
use GUMP\Filtering\Filter;
use GUMP\Filtering\FilterRegistry;
use GUMP\Validation\ClosureValidator;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;
use GUMP\Validation\ValidatorRegistry;

/**
 * GUMP - A Fast PHP Data Validation & Filtering Library
 *
 * GUMP is a standalone PHP data validation and filtering library that makes validating
 * any data easy and painless without the reliance on a framework. Supports 76 validators,
 * 16 filters, internationalization (19 languages), and custom validators/filters.
 *
 * @package GUMP
 * @version 1.x
 * @author Sean Nieuwoudt <sean@underwulf.com>
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
     * Readable field names that have been manually set.
     *
     * @var array
     */
    protected static $fields = [];

    /**
     * Custom validator error messages.
     *
     * @var array
     */
    protected static $validation_methods_errors = [];

    /**
     * Validator registry. Shared across all GUMP instances by design — the
     * static API (GUMP::add_validator) modifies this registry, so any GUMP
     * instance can see custom validators added by callers.
     */
    protected static ?ValidatorRegistry $validator_registry = null;

    /**
     * Filter registry. Shared across all GUMP instances by design — see
     * $validator_registry note.
     */
    protected static ?FilterRegistry $filter_registry = null;

    /**
     * Per-instance validator overrides. Populated by GUMP#register_validator().
     * Checked BEFORE the static (global) registry — same rule on an instance
     * shadows the global one without modifying it. Null until first use.
     */
    protected ?ValidatorRegistry $local_validators = null;

    /**
     * Per-instance filter overrides. See $local_validators note.
     */
    protected ?FilterRegistry $local_filters = null;

    protected static function registry(): ValidatorRegistry
    {
        if (self::$validator_registry === null) {
            self::$validator_registry = new ValidatorRegistry();
            self::register_built_in_validators(self::$validator_registry);
        }

        return self::$validator_registry;
    }

    protected static function filter_registry(): FilterRegistry
    {
        if (self::$filter_registry === null) {
            self::$filter_registry = new FilterRegistry();
            self::register_built_in_filters(self::$filter_registry);
        }

        return self::$filter_registry;
    }

    private static function register_built_in_validators(ValidatorRegistry $registry): void
    {
        $validators = [
            \GUMP\Validation\Validators\RequiredValidator::class,
            \GUMP\Validation\Validators\ContainsValidator::class,
            \GUMP\Validation\Validators\ContainsListValidator::class,
            \GUMP\Validation\Validators\DoesntContainListValidator::class,
            \GUMP\Validation\Validators\BooleanValidator::class,
            \GUMP\Validation\Validators\ValidEmailValidator::class,
            \GUMP\Validation\Validators\MaxLenValidator::class,
            \GUMP\Validation\Validators\MinLenValidator::class,
            \GUMP\Validation\Validators\ExactLenValidator::class,
            \GUMP\Validation\Validators\BetweenLenValidator::class,
            \GUMP\Validation\Validators\AlphaValidator::class,
            \GUMP\Validation\Validators\AlphaNumericValidator::class,
            \GUMP\Validation\Validators\AlphaDashValidator::class,
            \GUMP\Validation\Validators\AlphaNumericDashValidator::class,
            \GUMP\Validation\Validators\AlphaNumericSpaceValidator::class,
            \GUMP\Validation\Validators\AlphaSpaceValidator::class,
            \GUMP\Validation\Validators\NumericValidator::class,
            \GUMP\Validation\Validators\IntegerValidator::class,
            \GUMP\Validation\Validators\FloatValidator::class,
            \GUMP\Validation\Validators\ValidUrlValidator::class,
            \GUMP\Validation\Validators\UrlExistsValidator::class,
            \GUMP\Validation\Validators\ValidIpValidator::class,
            \GUMP\Validation\Validators\ValidIpv4Validator::class,
            \GUMP\Validation\Validators\ValidIpv6Validator::class,
            \GUMP\Validation\Validators\ValidCcValidator::class,
            \GUMP\Validation\Validators\ValidNameValidator::class,
            \GUMP\Validation\Validators\StreetAddressValidator::class,
            \GUMP\Validation\Validators\IbanValidator::class,
            \GUMP\Validation\Validators\DateValidator::class,
            \GUMP\Validation\Validators\MinAgeValidator::class,
            \GUMP\Validation\Validators\MaxNumericValidator::class,
            \GUMP\Validation\Validators\MinNumericValidator::class,
            \GUMP\Validation\Validators\StartsValidator::class,
            \GUMP\Validation\Validators\RequiredFileValidator::class,
            \GUMP\Validation\Validators\ExtensionValidator::class,
            \GUMP\Validation\Validators\EqualsfieldValidator::class,
            \GUMP\Validation\Validators\Guidv4Validator::class,
            \GUMP\Validation\Validators\PhoneNumberValidator::class,
            \GUMP\Validation\Validators\RegexValidator::class,
            \GUMP\Validation\Validators\ValidJsonStringValidator::class,
            \GUMP\Validation\Validators\ValidArraySizeGreaterValidator::class,
            \GUMP\Validation\Validators\ValidArraySizeLesserValidator::class,
            \GUMP\Validation\Validators\ValidArraySizeEqualValidator::class,
            \GUMP\Validation\Validators\StrongPasswordValidator::class,
            \GUMP\Validation\Validators\JwtTokenValidator::class,
            \GUMP\Validation\Validators\HashValidator::class,
            \GUMP\Validation\Validators\NoSqlInjectionValidator::class,
            \GUMP\Validation\Validators\NoXssValidator::class,
            \GUMP\Validation\Validators\UuidValidator::class,
            \GUMP\Validation\Validators\Base64Validator::class,
            \GUMP\Validation\Validators\HexColorValidator::class,
            \GUMP\Validation\Validators\RgbColorValidator::class,
            \GUMP\Validation\Validators\TimezoneValidator::class,
            \GUMP\Validation\Validators\LanguageCodeValidator::class,
            \GUMP\Validation\Validators\CountryCodeValidator::class,
            \GUMP\Validation\Validators\CurrencyCodeValidator::class,
            \GUMP\Validation\Validators\MacAddressValidator::class,
            \GUMP\Validation\Validators\DomainNameValidator::class,
            \GUMP\Validation\Validators\PortNumberValidator::class,
            \GUMP\Validation\Validators\SocialHandleValidator::class,
            \GUMP\Validation\Validators\LatitudeValidator::class,
            \GUMP\Validation\Validators\LongitudeValidator::class,
            \GUMP\Validation\Validators\PostalCodeValidator::class,
            \GUMP\Validation\Validators\CoordinatesValidator::class,
            \GUMP\Validation\Validators\FutureDateValidator::class,
            \GUMP\Validation\Validators\PastDateValidator::class,
            \GUMP\Validation\Validators\BusinessDayValidator::class,
            \GUMP\Validation\Validators\ValidTimeValidator::class,
            \GUMP\Validation\Validators\DateRangeValidator::class,
            \GUMP\Validation\Validators\EvenValidator::class,
            \GUMP\Validation\Validators\OddValidator::class,
            \GUMP\Validation\Validators\PrimeValidator::class,
            \GUMP\Validation\Validators\WordCountValidator::class,
            \GUMP\Validation\Validators\CamelCaseValidator::class,
            \GUMP\Validation\Validators\SnakeCaseValidator::class,
            \GUMP\Validation\Validators\UrlSlugValidator::class,
        ];

        foreach ($validators as $class) {
            $registry->register(new $class());
        }
    }

    private static function register_built_in_filters(FilterRegistry $registry): void
    {
        $filters = [
            \GUMP\Filtering\Filters\NoiseWordsFilter::class,
            \GUMP\Filtering\Filters\RmpunctuationFilter::class,
            \GUMP\Filtering\Filters\UrlencodeFilter::class,
            \GUMP\Filtering\Filters\HtmlencodeFilter::class,
            \GUMP\Filtering\Filters\SanitizeEmailFilter::class,
            \GUMP\Filtering\Filters\SanitizeNumbersFilter::class,
            \GUMP\Filtering\Filters\SanitizeFloatsFilter::class,
            \GUMP\Filtering\Filters\SanitizeStringFilter::class,
            \GUMP\Filtering\Filters\BooleanFilter::class,
            \GUMP\Filtering\Filters\BasicTagsFilter::class,
            \GUMP\Filtering\Filters\WholeNumberFilter::class,
            \GUMP\Filtering\Filters\MsWordCharactersFilter::class,
            \GUMP\Filtering\Filters\LowerCaseFilter::class,
            \GUMP\Filtering\Filters\UpperCaseFilter::class,
            \GUMP\Filtering\Filters\SlugFilter::class,
            \GUMP\Filtering\Filters\TrimFilter::class,
        ];

        foreach ($filters as $class) {
            $registry->register(new $class());
        }
    }

    // ** ------------------------- Instance Helper ---------------------------- ** //

    /**
     * Function to create and return previously created instance
     *
     * @return GUMP
     */
    public static function get_instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
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
    public static function is_empty(mixed $value): bool
    {
        return is_null($value) || $value === '' || (is_array($value) && count($value) === 0);
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
        if (self::has_validator($rule)) {
            throw new Exception(sprintf("'%s' validator is already defined.", $rule));
        }

        self::registry()->register(new ClosureValidator($rule, \Closure::fromCallable($callback)));
        self::$validation_methods_errors[$rule] = $error_message;
    }

    /**
     * Register a first-class Validator implementation. Preferred over add_validator() when you have
     * a real class; skips the callable adapter and gives you typed access to ValidationContext.
     *
     * @param Validator $validator
     * @param string    $error_message optional template — same placeholder rules as add_validator()
     * @throws Exception when a validator with the same rule name is already registered
     */
    public static function register_validator(Validator $validator, string $error_message = ''): void
    {
        $rule = $validator->rule();

        if (self::has_validator($rule)) {
            throw new Exception(sprintf("'%s' validator is already defined.", $rule));
        }

        self::registry()->register($validator);

        if ($error_message !== '') {
            self::$validation_methods_errors[$rule] = $error_message;
        }
    }

    /**
     * Register a Validator on THIS instance only — does not affect other GUMP instances or the
     * global registry. Instance-local validators shadow same-named global validators when this
     * instance dispatches. Use when you want isolated per-request customisation (e.g. Octane,
     * Swoole, RoadRunner) without polluting the shared registry.
     *
     * Returns $this for chaining.
     */
    public function register_local_validator(Validator $validator): self
    {
        if ($this->local_validators === null) {
            $this->local_validators = new ValidatorRegistry();
        }

        $this->local_validators->register($validator);

        return $this;
    }

    /**
     * Reset the validator registry to its built-in defaults.
     * Test helper — clears any validators added via add_validator().
     */
    public static function reset_custom_validators(): void
    {
        self::$validator_registry        = null;
        self::$validation_methods_errors = [];
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
        if (self::has_filter($rule)) {
            throw new Exception(sprintf("'%s' filter is already defined.", $rule));
        }

        self::filter_registry()->register(new ClosureFilter($rule, \Closure::fromCallable($callback)));
    }

    /**
     * Register a first-class Filter implementation. Preferred over add_filter() when you have
     * a real class; skips the callable adapter.
     *
     * @throws Exception when a filter with the same rule name is already registered
     */
    public static function register_filter(Filter $filter): void
    {
        $rule = $filter->rule();

        if (self::has_filter($rule)) {
            throw new Exception(sprintf("'%s' filter is already defined.", $rule));
        }

        self::filter_registry()->register($filter);
    }

    /**
     * Register a Filter on THIS instance only. See register_local_validator() for the rationale —
     * same model, applies to filtering.
     *
     * Returns $this for chaining.
     */
    public function register_local_filter(Filter $filter): self
    {
        if ($this->local_filters === null) {
            $this->local_filters = new FilterRegistry();
        }

        $this->local_filters->register($filter);

        return $this;
    }

    /**
     * Reset the filter registry to its built-in defaults.
     * Test helper — clears any filters added via add_filter().
     */
    public static function reset_custom_filters(): void
    {
        self::$filter_registry = null;
    }

    /**
     * FILTER_SANITIZE_STRING-style sanitiser (PHP 8.1+ deprecated the original).
     * Used internally and by SanitizeStringFilter.
     */
    public static function polyfill_filter_var_string($value): string
    {
        $str = preg_replace('/\x00|<[^>]*>?/', '', (string) $value);

        return (string) str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
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
        return self::registry()->has($rule)
            || method_exists(__CLASS__, sprintf('validate_%s', $rule));
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
        return self::filter_registry()->has($filter)
            || method_exists(__CLASS__, sprintf('filter_%s', $filter))
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

            return array_map(static function ($value, $key) {
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
                'param' => $this->parse_rule_params($rule[1] ?? []),
            ];
        }

        $result = [
            'rule' => $rule,
            'param' => [],
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
     * @return array<int, string>|array<int|string, mixed>
     */
    private function parse_rule_params($param): array
    {
        if (is_array($param)) {
            return $param;
        }

        $param = (string) $param;

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
        if (isset($rules[0]) && is_array($rules[0])) {
            $found = array_filter($rules, function ($item) use ($require_type_of_rules) {
                return in_array($item[0], $require_type_of_rules);
            });

            return count($found) > 0;
        }

        $found = array_values(array_intersect($require_type_of_rules, $rules));

        return count($found) > 0;
    }

    /**
     * Calls call_validator.
     *
     * @param string $rule
     * @param string $field
     * @param array $input
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
     * @param array $input
     * @param array $rule_params
     * @return array|bool
     * @throws Exception
     */
    private function call_validator(string $rule, string $field, array $input, array $rule_params = [], $value = null)
    {
        $context = new ValidationContext($field, $input, $rule_params);

        // 1. Instance-local overrides win over globals (allows isolated per-GUMP customisation).
        if ($this->local_validators !== null && $this->local_validators->has($rule)) {
            $result = $this->local_validators->get($rule)->validate($value, $context);

            return $result->isValid()
                ? true
                : $this->generate_error_array($field, $input[$field], $rule, $rule_params);
        }

        // 2. Global registry.
        if (self::registry()->has($rule)) {
            $result = self::registry()->get($rule)->validate($value, $context);

            return $result->isValid()
                ? true
                : $this->generate_error_array($field, $input[$field], $rule, $rule_params);
        }

        // 3. Subclass extension: GUMP descendants may declare validate_<rule> as a protected method.
        $method = sprintf('validate_%s', $rule);
        if (is_callable([$this, $method])) {
            $result = $this->$method($field, $input, $rule_params, $value);

            return (is_array($result) || $result === false)
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
        // 1. Instance-local overrides win over globals.
        if ($this->local_filters !== null && $this->local_filters->has($rule)) {
            return $this->local_filters->get($rule)->apply($value, $rule_params);
        }

        // 2. Global registry.
        if (self::filter_registry()->has($rule)) {
            return self::filter_registry()->get($rule)->apply($value, $rule_params);
        }

        // Subclass extension: GUMP descendants may declare filter_<rule> as a protected method.
        $method = sprintf('filter_%s', $rule);
        if (is_callable([$this, $method])) {
            return $this->$method($value, $rule_params);
        }

        // Fallback: any PHP function with a matching name (e.g. 'strip_tags', 'md5').
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
            'params' => $rule_params,
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
    protected function get_messages(): array
    {
        $lang_file = __DIR__.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$this->lang.'.php';
        $messages = include $lang_file;

        if (!is_array($messages)) {
            throw new Exception(sprintf("Language file '%s' did not return an array.", $this->lang));
        }

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
            '{param}' => implode(', ', $params),
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
     * @param array  $input
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
                    $input_array = [&$input[$field]];
                }

                foreach ($input_array as &$value) {
                    $value = $this->call_filter($parsed_rule['rule'], $value, $parsed_rule['param']);
                }

                unset($input_array, $value);
            }
        }

        return $input;
    }


}
