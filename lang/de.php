<?php

return array(
    'required'                 => 'Das Feld "{field}" ist erforderlich.',
    'valid_email'              => 'Das Feld "{field}" muss eine g&uuml;ltige E-Mail-Adresse sein.',
    'max_len'                  => 'Das Feld "{field}" darf maximal {param} Zeichen enthalten.',
    'min_len'                  => 'Das Feld "{field}" muss mindestens {param} Zeichen enthalten.',
    'exact_len'                => 'Das Feld "{field}" muss genau {param} Zeichen enthalten.',
    'between_len'              => 'Das Feld "{field}" muss zwischen {param[0]} und {param[1]} Zeichen enthalten.',
    'alpha'                    => 'Das Feld "{field}" darf nur Buchstaben enthalten.',
    'alpha_numeric'            => 'Das Feld "{field}" darf nur Buchstaben und Ziffern enthalten.',
    'alpha_numeric_space'      => 'Das Feld "{field}" darf nur Buchstaben, Ziffern und Leerzeichen enthalten.',
    'alpha_numeric_dash'      => 'Das Feld "{field}" darf nur Buchstaben, Ziffern und Leerzeichen enthalten.',
    'alpha_dash'               => 'Das Feld "{field}" darf nur Buchstaben und Bindestriche enthalten.',
    'alpha_space'              => 'Das Feld "{field}" darf nur Buchstaben und Leerzeichen enthalten.',
    'numeric'                  => 'Das Feld "{field}" muss eine Nummer sein.',
    'integer'                  => 'Das Feld "{field}" muss eine Nummer ohne Nachkommastellen (ganze Zahl) sein.',
    'boolean'                  => 'Das Feld "{field}" muss entweder wahr oder falsch sein.',
    'float'                    => 'Das Feld "{field}" muss eine Nummer mit einem Dezimalpunkt (Gleitpunktzahl) sein.',
    'valid_url'                => 'Das Feld "{field}" muss eine URL sein.',
    'url_exists'               => 'Die im Feld "{field}" angegebene URL existiert nicht.',
    'valid_ip'                 => 'Das Feld "{field}" muss eine g&uuml;ltige IP-Adresse sein.',
    'valid_ipv4'               => 'Das Feld "{field}" muss eine g&uuml;ltige IPv4-Adresse enthalten.',
    'valid_ipv6'               => 'Das Feld "{field}" muss eine g&uuml;ltige IPv6-Adresse enthalten.',
    'guidv4'                   => 'Das Feld "{field}" muss eine g&uuml;ltige GUID enthalten.',
    'valid_cc'                 => 'Das Feld "{field}" ist keine g&uuml;ltige Kreditkartennummer.',
    'valid_name'               => 'Das Feld "{field}" muss ein voller Name sein.',
    'contains'                 => 'Das Feld "{field}" kann nur eines der folgenden sein: {param}',
    'contains_list'            => 'Das Feld "{field}" ist keine g&uuml;ltige Wahl.',
    'doesnt_contain_list'      => 'Das Feld "{field}" enth&auml;lt einen nicht akzeptierten Wert.',
    'street_address'           => 'Das Feld "{field}" muss eine g&uuml;ltige Stra&szlig;enangabe sein.',
    'date'                     => 'Das Feld "{field}" muss ein g&uuml;ltiges Datum sein.',
    'min_numeric'              => 'Das Feld "{field}" muss ein numerischer Wert gr&ouml;&szlig;ergleich {param} sein.',
    'max_numeric'              => 'Das Feld "{field}" muss ein numerischer Wert kleinergleich {param} sein.',
    'min_age'                  => 'Das Feld "{field}" muss ein Alter gr&ouml;&szlig;ergleich {param} haben.',
    'starts'                   => 'Das Feld "{field}" muss mit {param} beginnen.',
    'extension'                => 'Das Feld "{field}" kann nur eine der folgenden Erweiterungen haben: {param}',
    'required_file'            => 'Das Feld "{field}" ist erforderlich.',
    'equalsfield'              => 'Das Feld "{field}" ist nicht gleich dem Feld "{param}".',
    'iban'                     => 'Das Feld "{field}" muss eine g&uuml;ltige IBAN enthalten.',
    'phone_number'             => 'Das Feld "{field}" muss eine g&uuml;ltige Telefonnummer sein.',
    'regex'                    => 'Das Feld "{field}" muss einen Wert im g&uuml;ltigem Format enthalten.',
    'valid_json_string'        => 'Das Feld "{field}" muss eine g&uuml;ltige JSON-Format-Zeichenfolge enthalten.',
    'valid_array_size_greater' => 'Das Feld "{field}" muss ein Array mit einer Gr&ouml;&szlig;e gr&ouml;&szlig;ergleich {param} sein.',
    'valid_array_size_lesser'  => 'Das Feld "{field}" muss ein Array mit einer Gr&ouml;&szlig;e kleinergleich {param} sein.',
    'valid_array_size_equal'   => 'Das Feld "{field}" muss ein Array mit einer Gr&ouml;&szlig;e gleich {param} sein.',

    // Security validators
    'strong_password'          => 'Das Feld "{field}" muss mindestens 8 Zeichen mit Gro&szlig;-, Kleinbuchstaben, Zahlen und Sonderzeichen enthalten.',
    'jwt_token'                => 'Das Feld "{field}" muss ein g&uuml;ltiges JWT-Token-Format sein.',
    'hash'                     => 'Das Feld "{field}" muss ein g&uuml;ltiger {param} Hash sein.',
    'no_sql_injection'         => 'Das Feld "{field}" enth&auml;lt potentielle SQL-Injection-Muster.',
    'no_xss'                   => 'Das Feld "{field}" enth&auml;lt potentielle XSS-Muster.',

    // Modern web validators
    'uuid'                     => 'Das Feld "{field}" muss eine g&uuml;ltige UUID sein.',
    'base64'                   => 'Das Feld "{field}" muss g&uuml;ltige base64-kodierte Daten sein.',
    'hex_color'                => 'Das Feld "{field}" muss ein g&uuml;ltiger hexadezimaler Farbcode sein (z.B. #FF0000).',
    'rgb_color'                => 'Das Feld "{field}" muss ein g&uuml;ltiges RGB-Farbformat sein (z.B. rgb(255,0,0)).',
    'timezone'                 => 'Das Feld "{field}" muss eine g&uuml;ltige Zeitzone-Kennung sein.',
    'language_code'            => 'Das Feld "{field}" muss ein g&uuml;ltiger Sprachcode sein (z.B. de, de-DE).',
    'country_code'             => 'Das Feld "{field}" muss ein g&uuml;ltiger L&auml;ndercode sein (z.B. DE, AT).',
    'currency_code'            => 'Das Feld "{field}" muss ein g&uuml;ltiger W&auml;hrungscode sein (z.B. USD, EUR).',

    // Network validators
    'mac_address'              => 'Das Feld "{field}" muss ein g&uuml;ltiges MAC-Adressen-Format sein.',
    'domain_name'              => 'Das Feld "{field}" muss ein g&uuml;ltiger Domain-Name sein.',
    'port_number'              => 'Das Feld "{field}" muss eine g&uuml;ltige Port-Nummer sein (1-65535).',
    'social_handle'            => 'Das Feld "{field}" muss ein g&uuml;ltiges Social-Media-Handle-Format sein.',

    // Geographic validators
    'latitude'                 => 'Das Feld "{field}" muss ein g&uuml;ltiger Breitengrad sein (-90 bis 90).',
    'longitude'                => 'Das Feld "{field}" muss ein g&uuml;ltiger L&auml;ngengrad sein (-180 bis 180).',
    'postal_code'              => 'Das Feld "{field}" muss eine g&uuml;ltige Postleitzahl f&uuml;r {param} sein.',
    'coordinates'              => 'Das Feld "{field}" muss g&uuml;ltige Koordinaten im Format lat,lng sein.',

    // Enhanced date/time validators
    'future_date'              => 'Das Feld "{field}" muss ein zuk&uuml;nftiges Datum sein.',
    'past_date'                => 'Das Feld "{field}" muss ein vergangenes Datum sein.',
    'business_day'             => 'Das Feld "{field}" muss auf einen Werktag fallen (Montag-Freitag).',
    'valid_time'               => 'Das Feld "{field}" muss ein g&uuml;ltiges Zeitformat sein (HH:MM oder HH:MM:SS).',
    'date_range'               => 'Das Feld "{field}" muss ein Datum zwischen {param[0]} und {param[1]} sein.',

    // Mathematical validators
    'even'                     => 'Das Feld "{field}" muss eine gerade Zahl sein.',
    'odd'                      => 'Das Feld "{field}" muss eine ungerade Zahl sein.',
    'prime'                    => 'Das Feld "{field}" muss eine Primzahl sein.',

    // Content validators
    'word_count'               => 'Das Feld "{field}" erf&uuml;llt nicht die Wortanzahl-Anforderungen.',
    'camel_case'               => 'Das Feld "{field}" muss im camelCase-Format sein.',
    'snake_case'               => 'Das Feld "{field}" muss im snake_case-Format sein.',
    'url_slug'                 => 'Das Feld "{field}" muss ein g&uuml;ltiges URL-Slug-Format sein.',
);
