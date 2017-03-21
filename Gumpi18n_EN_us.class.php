<?php

class Gumpi18n_EN_us
{

    public static function get_readable_errors($rule, $field_class, $field, $param)
    {
        switch ($rule) 
        {
            case 'mismatch' :
                $msg = "There is no validation rule for <span class=\"$field_class\">$field</span>";
                break;
            case 'validate_required' :
                $msg = "The <span class=\"$field_class\">$field</span> field is required";
                break;
            case 'validate_valid_email':
                $msg = "The <span class=\"$field_class\">$field</span> field is required to be a valid email address";
                break;
            case 'validate_max_len':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to be $param or shorter in length";
                break;
            case 'validate_min_len':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to be $param or longer in length";
                break;
            case 'validate_exact_len':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to be exactly $param characters in length";
                break;
            case 'validate_alpha':
                $msg = "The <span class=\"$field_class\">$field</span> field may only contain alpha characters(a-z)";
                break;
            case 'validate_alpha_numeric':
                $msg = "The <span class=\"$field_class\">$field</span> field may only contain alpha-numeric characters";
                break;
            case 'validate_alpha_dash':
                $msg = "The <span class=\"$field_class\">$field</span> field may only contain alpha characters &amp; dashes";
                break;
            case 'validate_numeric':
                $msg = "The <span class=\"$field_class\">$field</span> field may only contain numeric characters";
                break;
            case 'validate_integer':
                $msg = "The <span class=\"$field_class\">$field</span> field may only contain a numeric value";
                break;
            case 'validate_boolean':
                $msg = "The <span class=\"$field_class\">$field</span> field may only contain a true or false value";
                break;
            case 'validate_float':
                $msg = "The <span class=\"$field_class\">$field</span> field may only contain a float value";
                break;
            case 'validate_valid_url':
                $msg = "The <span class=\"$field_class\">$field</span> field is required to be a valid URL";
                break;
            case 'validate_url_exists':
                $msg = "The <span class=\"$field_class\">$field</span> URL does not exist";
                break;
            case 'validate_valid_ip':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to contain a valid IP address";
                break;
            case 'validate_valid_cc':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to contain a valid credit card number";
                break;
            case 'validate_valid_name':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to contain a valid human name";
                break;
            case 'validate_contains':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to contain one of these values: ".implode(', ', $param);
                break;
            case 'validate_contains_list':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to contain a value from its drop down list";
                break;
            case 'validate_doesnt_contain_list':
                $msg = "The <span class=\"$field_class\">$field</span> field contains a value that is not accepted";
                break;
            case 'validate_street_address':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to be a valid street address";
                break;
            case 'validate_date':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to be a valid date";
                break;
            case 'validate_min_numeric':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to be a numeric value, equal to, or higher than $param";
                break;
            case 'validate_max_numeric':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to be a numeric value, equal to, or lower than $param";
                break;
            case 'validate_starts':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to start with $param";
                break;
            case 'validate_extension':
                $msg = "The <span class=\"$field_class\">$field</span> field can have the following extensions $param";
                break;
            case 'validate_required_file':
                $msg = "The <span class=\"$field_class\">$field</span> field is required";
                break;
            case 'validate_equalsfield':
                $msg = "The <span class=\"$field_class\">$field</span> field does not equal $param field";
                break;
            case 'validate_min_age':
                $msg = "The <span class=\"$field_class\">$field</span> field needs to have an age greater than or equal to $param";
                break;
            default:
                $msg = "The <span class=\"$field_class\">$field</span> field is invalid";
        }
        
        return $msg;
    }
}

