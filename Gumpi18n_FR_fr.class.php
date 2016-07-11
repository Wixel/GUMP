<?php

class Gumpi18n_FR_fr
{

    public static function get_readable_errors($rule, $field_class, $field, $param)
    {
        switch ($rule) 
        {
            case 'mismatch' :
                $msg = "There is no validation rule for <span class=\"$field_class\">$field</span>";
                break;
            case 'validate_required' :
                $msg = "Le champ <span class=\"$field_class\">$field</span> est requis";
                break;
            case 'validate_valid_email':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit être une adresse email valide";
                break;
            case 'validate_max_len':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit avoir au maximum $param caractères";
                break;
            case 'validate_min_len':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit avoir au minimum $param caractères";
                break;
            case 'validate_exact_len':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit avoir $param caractères";
                break;
            case 'validate_alpha':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit contenir que des caractères alpha (a-z)";
                break;
            case 'validate_alpha_numeric':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit contenir que des caractères alpha-numerique";
                break;
            case 'validate_alpha_dash':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit contenir que des caractères alpha &amp; et des tirets";
                break;
            case 'validate_numeric':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit contenir que des chiffres";
                break;
            case 'validate_integer':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit contenir qu'une valeure numérique";
                break;
            case 'validate_boolean':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit contenir qu'une valeur booléenne";
                break;
            case 'validate_float':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit contenir qu'une valeur flottante";
                break;
            case 'validate_valid_url':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit être une URL valide";
                break;
            case 'validate_url_exists':
                $msg = "Le champ <span class=\"$field_class\">$field</span> n'est pas une URL";
                break;
            case 'validate_valid_ip':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit contenir une adresse IP valide";
                break;
            case 'validate_valid_cc':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit contenir un numéro de carte de crédit valide";
                break;
            case 'validate_valid_name':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit contenir un nom humain valide";
                break;
            case 'validate_contains':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit contenir l'une de ces valeurs : ".implode(', ', $param);
                break;
            case 'validate_contains_list':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit contenir une valeur contenue dans la liste déroulante";
                break;
            case 'validate_doesnt_contain_list':
                $msg = "Le champ <span class=\"$field_class\">$field</span> contient une valeur non acceptée";
                break;
            case 'validate_street_address':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit être une adresse postale valide";
                break;
            case 'validate_date':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit être une date valide";
                break;
            case 'validate_min_numeric':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit être une valeur numérique, supérieure ou égale à $param";
                break;
            case 'validate_max_numeric':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit être une valeur numérique, inférieure ou égale à $param";
                break;
            case 'validate_starts':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit commencer par $param";
                break;
            case 'validate_extension':
                $msg = "Le champ <span class=\"$field_class\">$field</span> peut avoir les extensions suivantes : $param";
                break;
            case 'validate_required_file':
                $msg = "Le champ <span class=\"$field_class\">$field</span> est requis";
                break;
            case 'validate_equalsfield':
                $msg = "Le champ <span class=\"$field_class\">$field</span> ne doit pas être égal au champ $param";
                break;
            case 'validate_min_age':
                $msg = "Le champ <span class=\"$field_class\">$field</span> doit être un age supérieur ou égal à $param";
                break;
            default:
                $msg = "Le champ <span class=\"$field_class\">$field</span> est invalide";
        }
        
        return $msg;
    }
}

