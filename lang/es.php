<?php

return array(
    'required'                 => 'El campo {field} es requerido',
    'valid_email'              => 'El campo {field} debe ser una dirección de correo electrónico válida',
    'max_len'                  => 'El campo {field} no puede tener más de {param} caracteres de longitud',
    'min_len'                  => 'El campo {field} debe tener al menos {param} caracteres de longitud',
    'exact_len'                => 'El campo {field} debe tener {param} caracteres de longitud',
    'between_len'             => 'El campo {field} debe tener entre {param} y {param2} caracteres',
    'alpha_numeric_dash'      => 'El campo {field} solo puede contener letras, números y guiones',
    'alpha'                    => 'El campo {field} sólo puede contener letras',
    'alpha_numeric'            => 'El campo {field} sólo puede contener letras y números',
    'alpha_numeric_space'      => 'El campo {field} solo puede contener letras, números y espacios',
    'alpha_dash'               => 'El campo {field} sólo puede contener letras y guiones',
    'alpha_space'              => 'El campo {field} sólo puede contener letras y espacios',
    'numeric'                  => 'El campo {field} sólo puede contener caracteres numéricos',
    'integer'                  => 'El campo {field} sólo puede contener un valor numérico',
    'boolean'                  => 'El campo {field} debe ser verdadero o falso',
    'float'                    => 'El campo {field} sólo puede contener un valor flotante',
    'valid_url'                => 'El campo {field} debe ser una dirección URL válida',
    'url_exists'               => 'El campo {field} debe ser una dirección URL existente',
    'valid_ip'                 => 'El campo {field} debe contener una dirección IP válida',
    'valid_ipv4'               => 'El campo {field} debe contener una dirección IPv4 válida',
    'valid_ipv6'               => 'El campo {field} debe contener una dirección IPv6 válida',
    'guidv4'                   => 'El campo {field} debe contener un GUID válido',
    'valid_cc'                 => 'El campo {field} debe contener un número de tarjeta de crédito válido',
    'valid_name'               => 'El campo {field} debe contener un nombre humano válido',
    'contains'                 => 'El campo {field} debe contener uno de los siguientes valores: {param}',
    'contains_list'            => 'El campo {field} debe contener un valor de su lista desplegable',
    'doesnt_contain_list'      => 'El campo {field} contiene un valor que no es aceptado',
    'street_address'           => 'El campo {field} debe contener una dirección válida',
    'date'                     => 'El campo {field} debe ser una fecha válida',
    'min_numeric'              => 'El campo {field} debe ser un valor numérico mayor o igual que {param}',
    'max_numeric'              => 'El campo {field} debe ser un valor numérico menor o igual que {param}',
    'min_age'                  => 'El campo {field} debe tener una edad mayor o igual que {param}',
    'starts'                   => 'El campo {field} debe comenzar con {param}',
    'extension'                => 'El campo {field} puede contener una de las siguientes extensiones {param}',
    'required_file'            => 'El campo {field} es requerido',
    'equalsfield'              => 'El campo {field} no equivale al campo {param}',
    'iban'                     => 'El campo {field} debe contener un IBAN válido',
    'phone_number'             => 'El campo {field} debe contener un número de teléfono válido',
    'regex'                    => 'El campo {field} debe contener un valor válido',
    'valid_json_string'        => 'El campo {field} debe contener una cadena con el formato JSON válido',
    'valid_array_size_greater' => 'El campo {field} debe ser un arreglo con el tamaño, igual o mayor que {param}',
    'valid_array_size_lesser'  => 'El campo {field} debe ser un arreglo con el tamaño, igual o menor que {param}',
    'valid_array_size_equal'   => 'El campo {field} debe ser un arreglo con el tamaño igual a {param}',

    // Security validators
    'strong_password'          => 'El campo {field} debe contener al menos 8 caracteres con mayúsculas, minúsculas, números y caracteres especiales',
    'jwt_token'                => 'El campo {field} debe ser un formato de token JWT válido',
    'hash'                     => 'El campo {field} debe ser un hash {param} válido',
    'no_sql_injection'         => 'El campo {field} contiene patrones potenciales de inyección SQL',
    'no_xss'                   => 'El campo {field} contiene patrones potenciales de XSS',

    // Modern web validators
    'uuid'                     => 'El campo {field} debe ser un UUID válido',
    'base64'                   => 'El campo {field} debe ser datos codificados en base64 válidos',
    'hex_color'                => 'El campo {field} debe ser un código de color hexadecimal válido (ej. #FF0000)',
    'rgb_color'                => 'El campo {field} debe ser un formato de color RGB válido (ej. rgb(255,0,0))',
    'timezone'                 => 'El campo {field} debe ser un identificador de zona horaria válido',
    'language_code'            => 'El campo {field} debe ser un código de idioma válido (ej. es, es-ES)',
    'country_code'             => 'El campo {field} debe ser un código de país válido (ej. ES, MX)',
    'currency_code'            => 'El campo {field} debe ser un código de moneda válido (ej. USD, EUR)',

    // Network validators
    'mac_address'              => 'El campo {field} debe ser un formato de dirección MAC válido',
    'domain_name'              => 'El campo {field} debe ser un nombre de dominio válido',
    'port_number'              => 'El campo {field} debe ser un número de puerto válido (1-65535)',
    'social_handle'            => 'El campo {field} debe ser un formato de usuario de redes sociales válido',

    // Geographic validators
    'latitude'                 => 'El campo {field} debe ser una latitud válida (-90 a 90)',
    'longitude'                => 'El campo {field} debe ser una longitud válida (-180 a 180)',
    'postal_code'              => 'El campo {field} debe ser un código postal válido para {param}',
    'coordinates'              => 'El campo {field} debe ser coordenadas válidas en formato lat,lng',

    // Enhanced date/time validators
    'future_date'              => 'El campo {field} debe ser una fecha futura',
    'past_date'                => 'El campo {field} debe ser una fecha pasada',
    'business_day'             => 'El campo {field} debe caer en un día hábil (lunes a viernes)',
    'valid_time'               => 'El campo {field} debe ser un formato de tiempo válido (HH:MM o HH:MM:SS)',
    'date_range'               => 'El campo {field} debe ser una fecha entre {param[0]} y {param[1]}',

    // Mathematical validators
    'even'                     => 'El campo {field} debe ser un número par',
    'odd'                      => 'El campo {field} debe ser un número impar',
    'prime'                    => 'El campo {field} debe ser un número primo',

    // Content validators
    'word_count'               => 'El campo {field} no cumple con los requisitos de conteo de palabras',
    'camel_case'               => 'El campo {field} debe estar en formato camelCase',
    'snake_case'               => 'El campo {field} debe estar en formato snake_case',
    'url_slug'                 => 'El campo {field} debe ser un formato de slug URL válido',
);
