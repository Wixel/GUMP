<?php

return array(
    'required'                 => 'La kampo “{field}” estas deviga',
    'valid_email'              => 'La kampo “{field}” devas enhavi validan retadreson',
    'max_len'                  => 'La kampo “{field}” devas enhavi maksimume {param} signojn',
    'min_len'                  => 'La kampo “{field}” devas enhavi minimume {param} signojn',
    'exact_len'                => 'La kampo “{field}” devas enhavi precize {param} signojn',
    'between_len'             => 'La kampo “{field}” devas enhavi inter {param} kaj {param2} signojn',
    'alpha_numeric_dash'      => 'La kampo “{field}” povas enhavi nur leterojn, ciferojn kaj streketojn',
    'alpha'                    => 'La kampo “{field}” povas enhavi nur leterojn',
    'alpha_numeric'            => 'La kampo “{field}” povas enhavi nur leterojn kaj ciferojn',
    'alpha_numeric_space'      => 'La kampo “{field}” povas enhavi nur leterojn, ciferojn kaj spacetojn',
    'alpha_dash'               => 'La kampo “{field}” povas enhavi nur leterojn kaj streketojn',
    'alpha_space'              => 'La kampo “{field}” povas enhavi nur leterojn kaj spacetojn',
    'numeric'                  => 'La kampo “{field}” devas esti nombro',
    'integer'                  => 'La kampo “{field}” devas esti nombro sen komo',
    'boolean'                  => 'La kampo “{field}” devas esti vera aŭ malvera',
    'float'                    => 'La kampo “{field}” devas esti nombro kun komo',
    'valid_url'                => 'La kampo “{field}” devas esti URL',
    'url_exists'               => 'La URL “{field}” ne ekzistas',
    'valid_ip'                 => 'La kampo “{field}” devas esti valida IP-adreso',
    'valid_ipv4'               => 'La kampo “{field}” devas enhavi validan IPv4-adreson',
    'valid_ipv6'               => 'La kampo “{field}” devas enhavi validan IPv6-adreson',
    'guidv4'                   => 'La kampo “{field}” devas enhavi validan GUID',
    'valid_cc'                 => 'La kampo “{field}” ne estas valida bankokarta numero',
    'valid_name'               => 'La kampo “{field}” devas esti plena nomo',
    'contains'                 => 'La kampo “{field}” povas esti nur unu el la jenaj: {param}',
    'contains_list'            => 'La kampo “{field}” ne estas valida opcio',
    'doesnt_contain_list'      => 'La kampo “{field}” enhavas neakceptitan valoron',
    'street_address'           => 'La kampo “{field}” devas esti valida strata adreso',
    'date'                     => 'La kampo “{field}” devas esti valida dato',
    'min_numeric'              => 'La kampo “{field}” devas esti nombra valoro egala aŭ pli granda ol {param}',
    'max_numeric'              => 'La kampo “{field}” devas esti nombra valoro egala aŭ malpli granda ol {param}',
    'min_age'                  => 'La kampo “{field}” devas enhavi aĝon egalan aŭ pli altan ol {param}',
    'starts'                   => 'La kampo “{field}” devas komenciĝi per {param}',
    'extension'                => 'La dosiero “{field}” povas havi nur unu el la jenaj finaĵoj: {param}',
    'required_file'            => 'La dosiero “{field}” estas deviga',
    'equalsfield'              => 'La kampo “{field}” ne havas la saman valoron kiel la kampo “{param}”',
    'iban'                     => 'La kampo “{field}” devas enhavi validan IBAN',
    'phone_number'             => 'La kampo “{field}” devas esti valida telefonnumero',
    'regex'                    => 'La kampo “{field}” devas enhavi valoron kun valida formato',
    'valid_json_string'        => 'La kampo “{field}” devas enhavi validan JSON-formatan ĉenon',
    'valid_array_size_greater' => 'La kampoj “{field}” devas esti tabelo kun grandeco egala aŭ pli granda ol {param}',
    'valid_array_size_lesser'  => 'La kampoj “{field}” devas esti tabelo kun grandeco egala aŭ malpli granda ol {param}',
    'valid_array_size_equal'   => 'La kampoj "{field}" devas esti tabelo kun grandeco egala je {param}',

    // Security validators
    'strong_password'          => 'La kampo "{field}" devas enhavi almenaŭ 8 signojn kun majuskloj, minuskloj, numeroj kaj specialaj signoj',
    'jwt_token'                => 'La kampo "{field}" devas esti valida JWT-ĵetona formato',
    'hash'                     => 'La kampo "{field}" devas esti valida {param} haketo',
    'no_sql_injection'         => 'La kampo "{field}" enhavas eblajn SQL-enmeto modelojn',
    'no_xss'                   => 'La kampo "{field}" enhavas eblajn XSS modelojn',

    // Modern web validators
    'uuid'                     => 'La kampo "{field}" devas esti valida UUID',
    'base64'                   => 'La kampo "{field}" devas esti validaj base64-kodaj datumoj',
    'hex_color'                => 'La kampo "{field}" devas esti valida deksesouma kolorkodo (ekz., #FF0000)',
    'rgb_color'                => 'La kampo "{field}" devas esti valida RGB-kolora formato (ekz., rgb(255,0,0))',
    'timezone'                 => 'La kampo "{field}" devas esti valida tempozono-identigilo',
    'language_code'            => 'La kampo "{field}" devas esti valida lingvokodo (ekz., en, en-US)',
    'country_code'             => 'La kampo "{field}" devas esti valida landkodo (ekz., US, CA)',
    'currency_code'            => 'La kampo "{field}" devas esti valida monkodo (ekz., USD, EUR)',

    // Network validators
    'mac_address'              => 'La kampo "{field}" devas esti valida MAC-adresa formato',
    'domain_name'              => 'La kampo "{field}" devas esti valida retregionomo',
    'port_number'              => 'La kampo "{field}" devas esti valida pordonumero (1-65535)',
    'social_handle'            => 'La kampo "{field}" devas esti valida socia reteja tenilo formato',

    // Geographic validators
    'latitude'                 => 'La kampo "{field}" devas esti valida latitudo (-90 ĝis 90)',
    'longitude'                => 'La kampo "{field}" devas esti valida longitudo (-180 ĝis 180)',
    'postal_code'              => 'La kampo "{field}" devas esti valida poŝtkodo por {param}',
    'coordinates'              => 'La kampo "{field}" devas esti validaj koordinatoj en lat,lng formato',

    // Enhanced date/time validators
    'future_date'              => 'La kampo "{field}" devas esti estonta dato',
    'past_date'                => 'La kampo "{field}" devas esti pasinta dato',
    'business_day'             => 'La kampo "{field}" devas esti en negoca tago (lundo-vendredo)',
    'valid_time'               => 'La kampo "{field}" devas esti valida tempoformato (HH:MM aŭ HH:MM:SS)',
    'date_range'               => 'La kampo "{field}" devas esti dato inter {param[0]} kaj {param[1]}',

    // Mathematical validators
    'even'                     => 'La kampo "{field}" devas esti para numero',
    'odd'                      => 'La kampo "{field}" devas esti nepara numero',
    'prime'                    => 'La kampo "{field}" devas esti primo numero',

    // Content validators
    'word_count'               => 'La vorto-kalkulo de la kampo "{field}" ne plenumas la postulojn',
    'camel_case'               => 'La kampo "{field}" devas esti en camelCase formato',
    'snake_case'               => 'La kampo "{field}" devas esti en snake_case formato',
    'url_slug'                 => 'La kampo "{field}" devas esti valida URL-slug formato',
);
