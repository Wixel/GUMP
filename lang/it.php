<?php

return array(
    'required'                 => 'Il campo {field} è obbligatorio',
    'valid_email'              => 'Il campo {field} deve essere un indirizzo email valido',
    'max_len'                  => 'Il campo {field} deve essere di {param} caratteri o inferiore',
    'min_len'                  => 'Il campo {field} deve essere di almeno {param} caratteri',
    'between_len'           => 'Il campo {field} deve avere una lunghezza tra {param1} e {param2} caratteri',
    'alpha_numeric_dash'    => 'Il campo {field} può contenere solo lettere, numeri e trattini',
    'exact_len'                => 'Il campo {field} deve essere di {param} caratteri esatti',
    'alpha'                    => 'Il campo {field} deve contenere solo lettere',
    'alpha_numeric'            => 'Il campo {field} può contenere solo lettere e numeri',
    'alpha_numeric_space'      => 'Il campo {field} può contenere solo lettere, numeri e spazi',
    'alpha_dash'               => 'Il campo {field} può contenere solo lettere e trattini',
    'alpha_space'              => 'Il campo {field} può contenere solo lettere e spazi',
    'numeric'                  => 'Il campo {field} deve essere un numero',
    'integer'                  => 'Il campo {field} deve essere un numero senza virgola',
    'boolean'                  => 'Il campo {field} deve essere vero o falso',
    'float'                    => 'Il campo {field} deve essere un numero con almeno un numero dopo la virgola',
    'valid_url'                => 'Il campo {field} deve essere un URL',
    'url_exists'               => 'L\'URL {field} non esiste',
    'valid_ip'                 => 'Il campo {field} deve essere un indirizzo IP valido',
    'valid_ipv4'               => 'Il campo {field} deve contenere un indirizzo IPv4 valido',
    'valid_ipv6'               => 'Il campo {field} deve contenere un indirizzo IPv6 valido',
    'guidv4'                   => 'Il campo {field} deve contenere un GUID valido',
    'valid_cc'                 => 'Il campo {field} non è un numero di carta di credito valido',
    'valid_name'               => 'Il campo {field} deve contenere un nome completo',
    'contains'                 => 'Il campo {field} può contenere solo uno dei seguenti valori: {param}',
    'contains_list'            => 'Il campo {field} non è un\'opzione valida',
    'doesnt_contain_list'      => 'Il campo {field} contiene un valore che non è accettato',
    'street_address'           => 'Il campo {field} deve contenere un indirizzo valido',
    'date'                     => 'Il campo {field} deve contenere una data valida',
    'min_numeric'              => 'Il campo {field} deve contenere un valore numerico maggiore o uguale a {param}',
    'max_numeric'              => 'Il campo {field} deve contenere un valore numerico minore o uguale a {param}',
    'min_age'                  => 'Il campo {field} deve contenere un\'età maggiore o uguale a {param}',
    'starts'                   => 'Il campo {field} deve cominciare con {param}',
    'extension'                => 'Il campo {field} può avere solo le seguenti estensioni: {param}',
    'required_file'            => 'Il campo {field} è obbligatorio',
    'equalsfield'              => 'Il campo {field} non è uguale al campo {param}',
    'iban'                     => 'Il campo {field} non contiene un IBAN valido',
    'phone_number'             => 'Il campo {field} deve contenere un numero di telefono valido',
    'regex'                    => 'Il campo {field} deve contenere un valore in un formato valido',
    'valid_json_string'        => 'Il campo {field} deve contenere una stringa in formato JSON corretto',
    'valid_array_size_greater' => 'Il campo {field} deve essere un array di dimensioni maggiori o uguali a {param}',
    'valid_array_size_lesser'  => 'Il campo {field} deve essere un array di dimensioni minori o uguali a {param}',
    'valid_array_size_equal'   => 'Il campo {field} deve essere un array di dimensioni uguali a {param}',

    // Security validators
    'strong_password'          => 'Il campo {field} deve contenere almeno 8 caratteri con maiuscole, minuscole, numeri e caratteri speciali',
    'jwt_token'                => 'Il campo {field} deve essere un formato token JWT valido',
    'hash'                     => 'Il campo {field} deve essere un hash {param} valido',
    'no_sql_injection'         => 'Il campo {field} contiene potenziali pattern di SQL injection',
    'no_xss'                   => 'Il campo {field} contiene potenziali pattern XSS',

    // Modern web validators
    'uuid'                     => 'Il campo {field} deve essere un UUID valido',
    'base64'                   => 'Il campo {field} deve essere dati codificati base64 validi',
    'hex_color'                => 'Il campo {field} deve essere un codice colore esadecimale valido (es. #FF0000)',
    'rgb_color'                => 'Il campo {field} deve essere un formato colore RGB valido (es. rgb(255,0,0))',
    'timezone'                 => 'Il campo {field} deve essere un identificatore di fuso orario valido',
    'language_code'            => 'Il campo {field} deve essere un codice lingua valido (es. it, it-IT)',
    'country_code'             => 'Il campo {field} deve essere un codice paese valido (es. IT, US)',
    'currency_code'            => 'Il campo {field} deve essere un codice valuta valido (es. USD, EUR)',

    // Network validators
    'mac_address'              => 'Il campo {field} deve essere un formato indirizzo MAC valido',
    'domain_name'              => 'Il campo {field} deve essere un nome dominio valido',
    'port_number'              => 'Il campo {field} deve essere un numero porta valido (1-65535)',
    'social_handle'            => 'Il campo {field} deve essere un formato handle social media valido',

    // Geographic validators
    'latitude'                 => 'Il campo {field} deve essere una latitudine valida (-90 a 90)',
    'longitude'                => 'Il campo {field} deve essere una longitudine valida (-180 a 180)',
    'postal_code'              => 'Il campo {field} deve essere un codice postale valido per {param}',
    'coordinates'              => 'Il campo {field} deve essere coordinate valide in formato lat,lng',

    // Enhanced date/time validators
    'future_date'              => 'Il campo {field} deve essere una data futura',
    'past_date'                => 'Il campo {field} deve essere una data passata',
    'business_day'             => 'Il campo {field} deve cadere in un giorno lavorativo (lunedì-venerdì)',
    'valid_time'               => 'Il campo {field} deve essere un formato ora valido (HH:MM o HH:MM:SS)',
    'date_range'               => 'Il campo {field} deve essere una data tra {param[0]} e {param[1]}',

    // Mathematical validators
    'even'                     => 'Il campo {field} deve essere un numero pari',
    'odd'                      => 'Il campo {field} deve essere un numero dispari',
    'prime'                    => 'Il campo {field} deve essere un numero primo',

    // Content validators
    'word_count'               => 'Il campo {field} non soddisfa i requisiti di conteggio parole',
    'camel_case'               => 'Il campo {field} deve essere in formato camelCase',
    'snake_case'               => 'Il campo {field} deve essere in formato snake_case',
    'url_slug'                 => 'Il campo {field} deve essere un formato slug URL valido',
);
