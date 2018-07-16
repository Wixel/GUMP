<?php
/**
 * GUMP - A fast, extensible PHP input validation class.
 *
 * @author    Sean Nieuwoudt (http://twitter.com/SeanNieuwoudt)
 * @author    Filis Futsarov (http://twitter.com/FilisCode)
 * @copyright Copyright (c) 2013-2018 wixelhq.com
 * @version   2.0
 */
class GUMP {
  // Internal callback container
  private static $registry  = [];

  // Internal language container
  private static $languages = [];

  // Internal configuration container
  private static $configs = [];

  // Getter to return the registry
  public static function registry() {
    return self::$registry;
  }

  // Getter to return the i18n tokens
  public static function languages() {
    return self::$languages;
  }  

  // Getter to return the configs
  public static function configs() {
    return self::$configs;
  }    

  /**
   * Shorthand getter and setter for config values
   *
   * @param string $key  The config key
   * @param mixed  $val  The config value
   * @return mixed The config value
   */
  public static function config($key, $val = null) {
    if($val != null) {
      self::$configs[$key] = $val;
    } else {
      if(array_key_exists($key, self::$configs)) {
        return self::$configs[$key];
      } else {
        return null;
      }
    }
  }

  /**
   * Load a language file and map the keys internally
   */
  public static function load_lang($path_pattern) {
    // load single file
    // load pattern
  }

  /**
   * Register a new callback anon function
   */
  public static function register($key, $callback) {
    if(is_callable($callback)) {
      self::$registry[$key] = $callback;
    } else {
      throw new Exception("{$key} function is not callable.");
    }
  }

  /**
   * Construct the anonymous responder class
   */
  private static function build_responder() {
    return new class {
      public $status;
      public $passed;

      public function __construct() {
        $this->failed = array();
        $this->passed = array();
      }

      public function set($field, $fields, $tokens) {
        foreach($tokens as $t) {
          if(array_key_exists($t['func'], GUMP::registry())) {  
            try {
              $this->passed[$field] = GUMP::call($t['func'], $fields[$field], $t['params']);  
            } catch(Exception $e) {
              // $this->errors[$field]
            }
          } else {
            throw new Exception("'{$t['func']}' is not a callable GUMP function.");
          }          
        }
      }
    };
  }

  /**
   * Call a function from the internal registry, throws exception on failure
   * 
   * @param String $func
   * @param Mixed $value
   * @param Array $params
   */
  public static function call(string $func, $value, $params = array()) {
    if(array_key_exists($func, self::$registry)) {
      return self::$registry[$func]($value, $params);
    } else {
      throw new Exception("{$func} is not a callable GUMP function.");
    }
  }

  /**
   * Map the rule into function & parameters
   * 
   * @param String $rule
   */
  private static function map_rule($rule) {
    $rule = explode(':', $rule);

    $buffer = array(
      'func' => $rule[0]
    );

    if(count($rule) > 1) {
      $buffer['params'] = $rule[1];
    } else {
      $buffer['params'] = null;
    }

    return $buffer;
  }

  /**
   * Parse the rules string into function tokens with params
   * 
   * @param String $rules
   */
  public static function tokenize(string $rules) {
    $tokens = array(); 
    $rules  = explode('|', $rules);
    
    foreach($rules as $rule) {
      array_push($tokens, self::map_rule($rule));
    }

    return $tokens;
  }

  /**
   * Run the functions specified in the rules array
   */
  public static function run($fields, $rules) {
    if(!is_array($fields)) {
      $fields = array(
        'input' => $ipnut
      );

      $rules = array(
        'input' => $rules
      );
    }

    $responder = self::build_responder();

    foreach($fields as $field => $value) {
      if(array_key_exists($field, $rules)) {
        $responder->set($field, $fields, self::tokenize($rules[$field]));
      }
    }

    return $responder;
  }

  /**
   * Reset GUMP and free up memory (you probably don't want to use this)
   *
   * @return void
   */
  public static function cleanup(){
    self::$registry  = [];
    self::$languages = [];
    self::$configs   = [];
  }
}