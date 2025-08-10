<?php

return array(
    'required'             => 'Le champ {field} est obligatoire',
    'valid_email'          => 'Le champ {field} doit &#234;tre un email valide',
    'max_len'              => 'Le champ {field} doit avoir un nombre de caract&#232;re de {param} ou moins',
    'min_len'              => 'Le champ {field} doit avoir un nombre de caract&#232;re de {param} ou plus',
    'between_len'             => 'Le champ {field} doit avoir un nombre de caractères entre {param} et {param2}',
    'alpha_numeric_dash'      => 'Le champ {field} doit seulement contenir des caractères alpha (a-z), numériques (0-9) et tirets',
    'alpha_numeric_space'     => 'Le champ {field} doit seulement contenir des caractères alpha (a-z), numériques (0-9) et espaces',
    'exact_len'            => 'Le champ {field} doit avoir un nombre de caract&#232;re de {param}',
    'alpha'                => 'Le champ {field} doit seulement contenir des caract&#232;res alpha (a-z)',
    'alpha_numeric'        => 'Le champ {field} doit seulement contenir des caract&#232;res alpha-num&#233;rique (a-z)',
    'alpha_dash'           => 'Le champ {field} doit seulement contenir des caract&#232;res alpha (a-z) et tiret',
    'alpha_space'          => 'Le champ {field} doit seulement contenir des caract&#232;res alpha (a-z) et espace',
    'numeric'              => 'Le champ {field} doit seulement contenir des caract&#232;res num&#233;riques',
    'integer'              => 'Le champ {field} doit &#234;tre une valeur num&#233;rique',
    'boolean'              => 'Le champ {field} doit &#234;tre vrai ou faux',
    'float'                => 'Le champ {field} doit &#234;tre une valeur d&#233;cimale',
    'valid_url'            => 'Le champ {field} doit &#234;tre une URL valide',
    'url_exists'           => 'L&#39;URL {field} n&#39;existe pas',
    'valid_ip'             => 'Le champ {field} doit contenir une adresse IP valide',
    'valid_ipv4'           => 'Le champ {field} doit contenir une adresse IPv4 valide',
    'valid_ipv6'           => 'Le champ {field} doit contenir une adresse IPv6 valide',
    'guidv4'               => 'Le champ {field} doit contenir un GUID valide',
    'valid_cc'             => 'Le champ {field} doit contenir un num&#233;ro de carte bancaire valide',
    'valid_name'           => 'Le champ {field} doit contenir un nom humain valide',
    'contains'             => 'Le champ {field} doit contenir une des ces valeurs: {param}',
    'contains_list'        => 'Le champ {field} doit contenir une valeur du menu d&#233;roulant',
    'doesnt_contain_list'  => 'Le champ {field} contient une valeur qui n&#39;est pas acceptable',
    'street_address'       => 'Le champ {field} doit &#234;tre une adresse postale valide',
    'date'                 => 'Le champ {field} doit &#234;tre une date valide',
    'min_numeric'          => 'Le champ {field} doit &#234;tre une valeur num&#233;rique &#233;gale ou sup&#233;rieur à {param}',
    'max_numeric'          => 'Le champ {field} doit &#234;tre une valeur num&#233;rique &#233;gale ou inf&#233;rieur à {param}',
    'min_age'              => 'Le champ {field} doit &#234;tre un &#226;ge &#233;gal ou sup&#233;rieur à {param}',
    'starts'               => 'Le champ {field} doit commencer par {param}',
    'extension'            => 'Le champ {field} doit avoir les extensions suivantes {param}',
    'required_file'        => 'Le champ {field} est obligatoire',
    'equalsfield'          => 'Le champ {field} n&#39;est pas &#233;gale au champ {param}',
    'iban'                 => 'Le champ {field} doit contenir un IBAN valide',
    'phone_number'         => 'Le champ {field} doit contenir un num&#233;ro de t&#233;l&#233;phone valide',
    'regex'                => 'Le champ {field} doit contenir une valeur valide',
    'valid_array_size_greater'=> 'Le champ {field} doit être un tableau dont la taille est supérieure ou égale à {param}',
    'valid_array_size_lesser' => 'Le champ {field} doit être un tableau dont la taille est inférieure ou égale à {param}',
    'valid_array_size_equal'  => 'Le champ {field} doit être un tableau dont la taille est égale à {param}',
    'valid_json_string'    => 'Le champ {field} doit avoir un format JSON',

    // Security validators
    'strong_password'          => 'Le champ {field} doit contenir au moins 8 caractères avec majuscules, minuscules, chiffres et caractères spéciaux',
    'jwt_token'                => 'Le champ {field} doit être un format de jeton JWT valide',
    'hash'                     => 'Le champ {field} doit être un hash {param} valide',
    'no_sql_injection'         => 'Le champ {field} contient des motifs potentiels d\'injection SQL',
    'no_xss'                   => 'Le champ {field} contient des motifs potentiels de XSS',

    // Modern web validators
    'uuid'                     => 'Le champ {field} doit être un UUID valide',
    'base64'                   => 'Le champ {field} doit être des données encodées en base64 valides',
    'hex_color'                => 'Le champ {field} doit être un code couleur hexadécimal valide (ex. #FF0000)',
    'rgb_color'                => 'Le champ {field} doit être un format de couleur RGB valide (ex. rgb(255,0,0))',
    'timezone'                 => 'Le champ {field} doit être un identifiant de fuseau horaire valide',
    'language_code'            => 'Le champ {field} doit être un code de langue valide (ex. fr, fr-FR)',
    'country_code'             => 'Le champ {field} doit être un code de pays valide (ex. FR, CA)',
    'currency_code'            => 'Le champ {field} doit être un code de devise valide (ex. USD, EUR)',

    // Network validators
    'mac_address'              => 'Le champ {field} doit être un format d\'adresse MAC valide',
    'domain_name'              => 'Le champ {field} doit être un nom de domaine valide',
    'port_number'              => 'Le champ {field} doit être un numéro de port valide (1-65535)',
    'social_handle'            => 'Le champ {field} doit être un format d\'identifiant de médias sociaux valide',

    // Geographic validators
    'latitude'                 => 'Le champ {field} doit être une latitude valide (-90 à 90)',
    'longitude'                => 'Le champ {field} doit être une longitude valide (-180 à 180)',
    'postal_code'              => 'Le champ {field} doit être un code postal valide pour {param}',
    'coordinates'              => 'Le champ {field} doit être des coordonnées valides au format lat,lng',

    // Enhanced date/time validators
    'future_date'              => 'Le champ {field} doit être une date future',
    'past_date'                => 'Le champ {field} doit être une date passée',
    'business_day'             => 'Le champ {field} doit tomber sur un jour ouvrable (lundi-vendredi)',
    'valid_time'               => 'Le champ {field} doit être un format d\'heure valide (HH:MM ou HH:MM:SS)',
    'date_range'               => 'Le champ {field} doit être une date entre {param[0]} et {param[1]}',

    // Mathematical validators
    'even'                     => 'Le champ {field} doit être un nombre pair',
    'odd'                      => 'Le champ {field} doit être un nombre impair',
    'prime'                    => 'Le champ {field} doit être un nombre premier',

    // Content validators
    'word_count'               => 'Le champ {field} ne respecte pas les exigences de comptage de mots',
    'camel_case'               => 'Le champ {field} doit être au format camelCase',
    'snake_case'               => 'Le champ {field} doit être au format snake_case',
    'url_slug'                 => 'Le champ {field} doit être un format de slug d\'URL valide',
);
