<?php

return [
    'required' => 'Pole {field} jest wymagane',
    'valid_email' => 'Pole {field} musi być poprawnym adresem email',
    'max_len' => 'Pole {field} musi mieć maksymalnie {param} znaków',
    'min_len' => 'Pole {field} musi mieć co najmniej {param} znaków',
    'exact_len' => 'Pole {field} musi mieć dokładnie {param} znaków',
    'between_len' => 'Pole {field} musi mieć od {param[0]} do {param[1]} znaków',
    'alpha' => 'Pole {field} może zawierać tylko litery',
    'alpha_numeric' => 'Pole {field} może zawierać tylko litery i cyfry',
    'alpha_numeric_space' => 'Pole {field} może zawierać tylko litery, cyfry i spacje',
    'alpha_numeric_dash' => 'Pole {field} może zawierać tylko litery, cyfry, myślniki i podkreślenia',
    'alpha_dash' => 'Pole {field} może zawierać tylko litery, myślniki i podkreślenia',
    'alpha_space' => 'Pole {field} może zawierać tylko litery i spacje',
    'numeric' => 'Pole {field} musi być liczbą',
    'integer' => 'Pole {field} musi być liczbą całkowitą',
    'boolean' => 'Pole {field} musi mieć wartość prawda albo fałsz',
    'float' => 'Pole {field} musi być liczbą zmiennoprzecinkową',
    'valid_url' => 'Pole {field} musi być poprawnym adresem URL',
    'url_exists' => 'Adres URL {field} nie istnieje',
    'valid_ip' => 'Pole {field} musi być poprawnym adresem IP',
    'valid_ipv4' => 'Pole {field} musi być poprawnym adresem IPv4',
    'valid_ipv6' => 'Pole {field} musi być poprawnym adresem IPv6',
    'guidv4' => 'Pole {field} musi być poprawnym GUID',
    'valid_cc' => 'Pole {field} nie jest poprawnym numerem karty kredytowej',
    'valid_name' => 'Pole {field} musi być imieniem i nazwiskiem',
    'contains' => 'Pole {field} może mieć tylko jedną z następujących wartości: {param}',
    'contains_list' => 'Pole {field} nie jest poprawną opcją',
    'doesnt_contain_list' => 'Pole {field} zawiera niedozwoloną wartość',
    'street_address' => 'Pole {field} musi być poprawnym adresem ulicy',
    'date' => 'Pole {field} musi być poprawną datą',
    'min_numeric' => 'Pole {field} musi być liczbą większą lub równą {param}',
    'max_numeric' => 'Pole {field} musi być liczbą mniejszą lub równą {param}',
    'min_age' => 'Pole {field} musi mieć wiek większy lub równy {param}',
    'starts' => 'Pole {field} musi zaczynać się od {param}',
    'extension' => 'Pole {field} może mieć tylko jedno z następujących rozszerzeń: {param}',
    'required_file' => 'Pole {field} jest wymagane',
    'equalsfield' => 'Pole {field} nie jest równe polu {param}',
    'iban' => 'Pole {field} musi być poprawnym numerem IBAN',
    'phone_number' => 'Pole {field} musi być poprawnym numerem telefonu',
    'regex' => 'Pole {field} musi mieć poprawny format',
    'valid_json_string' => 'Pole {field} musi być poprawnym ciągiem JSON',
    'valid_array_size_greater' => 'Pole {field} musi być tablicą o rozmiarze większym lub równym {param}',
    'valid_array_size_lesser' => 'Pole {field} musi być tablicą o rozmiarze mniejszym lub równym {param}',
    'valid_array_size_equal' => 'Pole {field} musi być tablicą o rozmiarze równym {param}',

    // Security validators
    'strong_password' => 'Pole {field} musi zawierać co najmniej 8 znaków, w tym wielką literę, małą literę, cyfrę i znak specjalny',
    'jwt_token' => 'Pole {field} musi być poprawnym tokenem JWT',
    'hash' => 'Pole {field} musi być poprawnym hashem {param}',
    'no_sql_injection' => 'Pole {field} zawiera potencjalne wzorce SQL injection',
    'no_xss' => 'Pole {field} zawiera potencjalne wzorce XSS',

    // Modern web validators
    'uuid' => 'Pole {field} musi być poprawnym UUID',
    'base64' => 'Pole {field} musi być poprawnymi danymi zakodowanymi w base64',
    'hex_color' => 'Pole {field} musi być poprawnym kodem koloru heksadecymalnego (np. #FF0000)',
    'rgb_color' => 'Pole {field} musi być poprawnym formatem koloru RGB (np. rgb(255,0,0))',
    'timezone' => 'Pole {field} musi być poprawnym identyfikatorem strefy czasowej',
    'language_code' => 'Pole {field} musi być poprawnym kodem języka (np. en, en-US)',
    'country_code' => 'Pole {field} musi być poprawnym kodem kraju (np. US, CA)',
    'currency_code' => 'Pole {field} musi być poprawnym kodem waluty (np. USD, EUR)',

    // Network validators
    'mac_address' => 'Pole {field} musi być poprawnym adresem MAC',
    'domain_name' => 'Pole {field} musi być poprawną nazwą domeny',
    'port_number' => 'Pole {field} musi być poprawnym numerem portu (1-65535)',
    'social_handle' => 'Pole {field} musi być poprawnym uchwytem mediów społecznościowych',

    // Geographic validators
    'latitude' => 'Pole {field} musi być poprawną szerokością geograficzną (-90 do 90)',
    'longitude' => 'Pole {field} musi być poprawną długością geograficzną (-180 do 180)',
    'postal_code' => 'Pole {field} musi być poprawnym kodem pocztowym dla {param}',
    'coordinates' => 'Pole {field} musi być poprawnymi współrzędnymi w formacie lat,lng',

    // Enhanced date/time validators
    'future_date' => 'Pole {field} musi być datą w przyszłości',
    'past_date' => 'Pole {field} musi być datą w przeszłości',
    'business_day' => 'Pole {field} musi przypadać na dzień roboczy (poniedziałek-piątek)',
    'valid_time' => 'Pole {field} musi być poprawnym czasem (GG:MM lub GG:MM:SS)',
    'date_range' => 'Pole {field} musi być datą pomiędzy {param[0]} i {param[1]}',

    // Mathematical validators
    'even' => 'Pole {field} musi być liczbą parzystą',
    'odd' => 'Pole {field} musi być liczbą nieparzystą',
    'prime' => 'Pole {field} musi być liczbą pierwszą',

    // Content validators
    'word_count' => 'Liczba słów w polu {field} nie spełnia wymagań',
    'camel_case' => 'Pole {field} musi być w formacie camelCase',
    'snake_case' => 'Pole {field} musi być w formacie snake_case',
    'url_slug' => 'Pole {field} musi być poprawnym formatem URL slug',
];
