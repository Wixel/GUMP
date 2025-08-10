<?php

return array(
    'required'                 => '{field} mező kötelező.',
    'valid_email'              => '{field} mezőnek valós email címnek kell lennie.',
    'max_len'                  => '{field} mező legfeljebb {param} karakter hosszú lehet.',
    'min_len'                  => '{field} mezőnek legalább {param} karakter hosszúnak kell lennie.',
    'exact_len'                => '{field} mezőnek pontosan {param} karakter hosszúnak kell lennie.',
    'between_len'           => '{field} mezőnek {param1} és {param2} karakter között kell lennie.',
    'alpha_numeric_dash'    => '{field} mező csak betűket, számokat és kötőjeleket tartalmazhat.',
    'alpha'                    => '{field} mező csak betűket tartalmazhat.',
    'alpha_numeric'            => '{field} mező csak betűket és számokat tartalmazhat.',
    'alpha_numeric_space'      => '{field} mező csak betűket, számokat és szóközöket tartalmazhat.',
    'alpha_dash'               => '{field} mező csak betűket és alulvonásokat tartalmazhat.',
    'alpha_space'              => '{field} mező csak betűket és szóközöket tartalmazhat.',
    'numeric'                  => '{field} mező csak szám lehet.',
    'integer'                  => '{field} mező csak tizedesjegy nélküli szám (integer) lehet.',
    'boolean'                  => '{field} mező csak logikai változó (true, false) lehet.',
    'float'                    => '{field} mező csak tizedesjegyet tartalmazó szám (float) lehet.',
    'valid_url'                => '{field} mezőnek URL-nek kell lennie.',
    'url_exists'               => '{field} URL nem létezik.',
    'valid_ip'                 => '{field} mezőnek valós IP címnek kell lennie.',
    'valid_ipv4'               => '{field} mezőnek valós IP4 címnek kell lennie.',
    'valid_ipv6'               => '{field} mezőnek valós IP6 címnek kell lennie.',
    'guidv4'                   => '{field} mezőnek valós GUID-nke kell lennie.',
    'valid_cc'                 => '{field} mezőnek valós bankkártya számnak kell lennie.',
    'valid_name'               => '{field} mezőnek valós névnek kell lennie.',
    'contains'                 => '{field} mezőnek a listában szerepelnie kell: {param}.',
    'contains_list'            => '{field} mező nem megfelelő.',
    'doesnt_contain_list'      => '{field} mezőnek nem elfogadott értéke van.',
    'street_address'           => '{field} mezőnek valós címnek kell lennie.',
    'date'                     => '{field} valós dátumnak kell lennie.',
    'min_numeric'              => '{field} mezőnek számnak kell lennie, ami nagyobb vagy egyenlő, mint {param}.',
    'max_numeric'              => '{field} mezőnek számnak kell lennie, ami kisebb vagy egyenlő, mint {param}.',
    'min_age'                  => '{field} mezőnek valós életkornak kell lennie, ami nagyobb vagy egyenlő, mint {param} év.',
    'starts'                   => '{field} mezőnek a következő szöveggel kell kezdődnie: {param}.',
    'extension'                => '{field} mező csak a következő kitrejesztéseket tartalmazhatja: {param}.',
    'required_file'            => '{field} fájl mező kötelező.',
    'equalsfield'              => '{field} mező nem egyezik a(z) {param} mezővel.',
    'iban'                     => '{field} mezőnek valós IBAN számnak kell lennie.',
    'phone_number'             => '{field} mezőnek valós telefonszámnak kell lennie.',
    'regex'                    => '{field} mezőnek megfelelő formátumúnak kell lennie.',
    'valid_json_string'        => '{field} mezőnek valid JSON stringnek kell lennie.',
    'valid_array_size_greater' => '{field} tömbnek nagyobb vagy egyenlő számúnak kell lennie, mint {param}.',
    'valid_array_size_lesser'  => '{field} tömbnek kisebb vagy egyenlő számúnak kell lennie, mint {param}.',
    'valid_array_size_equal'   => '{field} tömbnek {param} számúnak kell lennie.',

    // Security validators
    'strong_password'          => '{field} mezőnek legalább 8 karaktert kell tartalmaznia nagybetűkkel, kisbetűkkel, számokkal és speciális karakterekkel',
    'jwt_token'                => '{field} mezőnek érvényes JWT token formátumúnak kell lennie',
    'hash'                     => '{field} mezőnek érvényes {param} hash-nek kell lennie',
    'no_sql_injection'         => '{field} mező potenciális SQL injection mintákat tartalmaz',
    'no_xss'                   => '{field} mező potenciális XSS mintákat tartalmaz',

    // Modern web validators
    'uuid'                     => '{field} mezőnek érvényes UUID-nek kell lennie',
    'base64'                   => '{field} mezőnek érvényes base64 kódolt adatnak kell lennie',
    'hex_color'                => '{field} mezőnek érvényes hexadecimális színkódnak kell lennie (pl. #FF0000)',
    'rgb_color'                => '{field} mezőnek érvényes RGB színformátumnak kell lennie (pl. rgb(255,0,0))',
    'timezone'                 => '{field} mezőnek érvényes időzóna-azonosítónak kell lennie',
    'language_code'            => '{field} mezőnek érvényes nyelvkódnak kell lennie (pl. en, en-US)',
    'country_code'             => '{field} mezőnek érvényes országkódnak kell lennie (pl. US, CA)',
    'currency_code'            => '{field} mezőnek érvényes valutakódnak kell lennie (pl. USD, EUR)',

    // Network validators
    'mac_address'              => '{field} mezőnek érvényes MAC cím formátumúnak kell lennie',
    'domain_name'              => '{field} mezőnek érvényes domain névnek kell lennie',
    'port_number'              => '{field} mezőnek érvényes port számnak kell lennie (1-65535)',
    'social_handle'            => '{field} mezőnek érvényes közösségi média felhasználónév formátumúnak kell lennie',

    // Geographic validators
    'latitude'                 => '{field} mezőnek érvényes szélességi fokoknak kell lennie (-90 és 90 között)',
    'longitude'                => '{field} mezőnek érvényes hosszúsági fokoknak kell lennie (-180 és 180 között)',
    'postal_code'              => '{field} mezőnek érvényes irányítószámnak kell lennie {param} számára',
    'coordinates'              => '{field} mezőnek érvényes koordinátáknak kell lennie lat,lng formátumban',

    // Enhanced date/time validators
    'future_date'              => '{field} mezőnek jövőbeli dátumnak kell lennie',
    'past_date'                => '{field} mezőnek múltbeli dátumnak kell lennie',
    'business_day'             => '{field} mezőnek munkanapra kell esnie (hétfő-péntek)',
    'valid_time'               => '{field} mezőnek érvényes idő formátumúnak kell lennie (HH:MM vagy HH:MM:SS)',
    'date_range'               => '{field} mezőnek {param[0]} és {param[1]} közötti dátumnak kell lennie',

    // Mathematical validators
    'even'                     => '{field} mezőnek páros számnak kell lennie',
    'odd'                      => '{field} mezőnek páratlan számnak kell lennie',
    'prime'                    => '{field} mezőnek prímszámnak kell lennie',

    // Content validators
    'word_count'               => '{field} mező szószáma nem felel meg a követelményeknek',
    'camel_case'               => '{field} mezőnek camelCase formátumúnak kell lennie',
    'snake_case'               => '{field} mezőnek snake_case formátumúnak kell lennie',
    'url_slug'                 => '{field} mezőnek érvényes URL slug formátumúnak kell lennie',
);
