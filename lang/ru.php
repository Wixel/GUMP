<?php

return array(
    'required'                 => 'Поле {field} является обязательным',
    'valid_email'              => 'Поле {field} должно быть Email адресом',
    'max_len'                  => 'Поле {field} должно быть максимум {param} символов',
    'min_len'                  => 'Поле {field} должно быть минимум {param} символов',
    'exact_len'                => 'Поле {field} должно содержать ровно {param} символов',
    'between_len'              => 'Поле {field} должно содержать от {param[0]} до {param[1]} символов',
    'alpha_numeric_dash'       => 'Поле {field} может содержать только буквы, цифры, дефисы и подчеркивания',
    'alpha'                    => 'Поле {field} может содержать только буквы',
    'alpha_numeric'            => 'Поле {field} может содержать только буквы и цифры',
    'alpha_numeric_space'      => 'Поле {field} может содержать только буквы, цифры и пробелы',
    'alpha_dash'               => 'Поле {field} может содержать только буквы и дефис',
    'alpha_space'              => 'Поле {field} может содержать только буквы и пробел',
    'numeric'                  => 'Поле {field} должно быть числом',
    'integer'                  => 'Поле {field} должно быть целым числом',
    'boolean'                  => 'Поле {field} должно быть true или false',
    'float'                    => 'Поле {field} должно быть не целым числом',
    'valid_url'                => 'Поле {field} должно быть ссылкой',
    'url_exists'               => 'Ссылка {field} не доступна',
    'valid_ip'                 => 'Поле {field} должно быть IP адресом',
    'valid_ipv4'               => 'Поле {field} должно быть IPv4 адресом',
    'valid_ipv6'               => 'Поле {field} должно быть IPv6 адресом',
    'guidv4'                   => 'Поле {field} должно быть GUID',
    'valid_cc'                 => 'Номер карты {field} не является валидным',
    'valid_name'               => 'Поле {field} должно содержать полное имя',
    'contains'                 => 'Поле {field} может содержать следующие значения: {param}',
    'contains_list'            => 'Значение {field} не может быть использовано как ответ',
    'doesnt_contain_list'      => 'Значение {field} не может быть использовано',
    'street_address'           => 'Поле {field} должно быть адресом',
    'date'                     => 'Поле {field} должно быть датой',
    'min_numeric'              => 'Поле {field} должно быть числом не менее {param}',
    'max_numeric'              => 'Поле {field} должно быть числом не более {param}',
    'min_age'                  => 'Возраст должен быть более {param}',
    'starts'                   => 'Поле {field} должно начинаться {param}',
    'extension'                => 'Файл {field} должен быть следующим типом: {param}',
    'required_file'            => 'Файл {field} обязателен к загрузке',
    'equalsfield'              => 'Поле {field} должно быть идентичным {param}',
    'iban'                     => 'Поле {field} должно быть правильным IBAN',
    'phone_number'             => 'Поле {field} должно быть правильным номером телефона',
    'regex'                    => 'Поле {field} должно содержать правильное значение',
    'valid_json_string'        => 'Поле {field} должно быть валидной JSON строкой',
    'valid_array_size_greater' => 'Поле {field} должно содержать минимум {param} значений',
    'valid_array_size_lesser'  => 'Поле {field} должно содержать максимум {param} значений',
    'valid_array_size_equal'   => 'Поле {field} должно содержать ровно {param} значений',

    // Security validators
    'strong_password'          => 'Поле {field} должно содержать как минимум 8 символов с заглавными буквами, строчными буквами, цифрами и специальными символами',
    'jwt_token'                => 'Поле {field} должно быть в правильном формате JWT токена',
    'hash'                     => 'Поле {field} должно быть правильным хеш {param}',
    'no_sql_injection'         => 'Поле {field} содержит потенциальные паттерны SQL инъекций',
    'no_xss'                   => 'Поле {field} содержит потенциальные XSS паттерны',

    // Modern web validators
    'uuid'                     => 'Поле {field} должно быть правильным UUID',
    'base64'                   => 'Поле {field} должно быть правильно данные в кодировке base64',
    'hex_color'                => 'Поле {field} должно быть правильным шестнадцатеричным кодом цвета (нпр. #FF0000)',
    'rgb_color'                => 'Поле {field} должно быть в правильном формате цвета RGB (нпр. rgb(255,0,0))',
    'timezone'                 => 'Поле {field} должно быть правильным идентификатором часового пояса',
    'language_code'            => 'Поле {field} должно быть правильным кодом языка (нпр. ru, ru-RU)',
    'country_code'             => 'Поле {field} должно быть правильным кодом страны (нпр. RU, US)',
    'currency_code'            => 'Поле {field} должно быть правильным кодом валюты (нпр. USD, EUR)',

    // Network validators
    'mac_address'              => 'Поле {field} должно быть в правильном формате MAC адреса',
    'domain_name'              => 'Поле {field} должно быть правильным именем домена',
    'port_number'              => 'Поле {field} должно быть правильным номером порта (1-65535)',
    'social_handle'            => 'Поле {field} должно быть в правильном формате для социальных сетей',

    // Geographic validators
    'latitude'                 => 'Поле {field} должно быть правильной широтой (-90 до 90)',
    'longitude'                => 'Поле {field} должно быть правильной долготой (-180 до 180)',
    'postal_code'              => 'Поле {field} должно быть правильным почтовым индексом для {param}',
    'coordinates'              => 'Поле {field} должно быть правильными координатами в формате lat,lng',

    // Enhanced date/time validators
    'future_date'              => 'Поле {field} должно быть будущей датой',
    'past_date'                => 'Поле {field} должно быть прошлой датой',
    'business_day'             => 'Поле {field} должно приходиться на рабочий день (понедельник-пятница)',
    'valid_time'               => 'Поле {field} должно быть в правильном формате времени (HH:MM или HH:MM:SS)',
    'date_range'               => 'Поле {field} должно быть датой между {param[0]} и {param[1]}',

    // Mathematical validators
    'even'                     => 'Поле {field} должно быть четным числом',
    'odd'                      => 'Поле {field} должно быть нечетным числом',
    'prime'                    => 'Поле {field} должно быть простым числом',

    // Content validators
    'word_count'               => 'Поле {field} не соответствует требованиям по количеству слов',
    'camel_case'               => 'Поле {field} должно быть в формате camelCase',
    'snake_case'               => 'Поле {field} должно быть в формате snake_case',
    'url_slug'                 => 'Поле {field} должно быть в правильном формате URL slug',
);
