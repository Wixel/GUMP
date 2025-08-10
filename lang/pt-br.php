<?php

return array(
    'required'                 => 'O preenchimento do campo {field} é obrigatório',
    'valid_email'              => 'O campo {field} precisa conter um e-mail válido',
    'max_len'                  => 'O campo {field} pode conter no máximo {param} caracteres',
    'min_len'                  => 'O campo {field} precisa conter no mínimo {param} caracteres',
    'exact_len'                => 'O campo {field} precisa conter exatamente {param} caracteres',
    'between_len' => 'O campo {field} deve conter entre {param[0]} e {param[1]} caracteres',
    'alpha_numeric_dash' => 'O campo {field} pode conter apenas letras, números e traços',
    'alpha'                    => 'O campo {field} pode conter apenas letras',
    'alpha_numeric'            => 'O campo {field} pode conter apenas letras e números',
    'alpha_numeric_space'      => 'O campo {field} pode conter apenas letras, números e espaços',
    'alpha_dash'               => 'O campo {field} pode conter apenas letras e traços',
    'alpha_space'              => 'O campo {field} pode conter apenas letras e espaços',
    'numeric'                  => 'O campo {field} precisa ser um número',
    'integer'                  => 'O campo {field} precisa ser um número inteiro, sem decimal',
    'boolean'                  => 'O campo {field} deve ser verdadeiro ou falso',
    'float'                    => 'O campo {field} precisa ser um número com (float) casas decimais',
    'valid_url'                => 'O campo {field} precisa ser uma url válida',
    'url_exists'               => 'O campo {field} possui uma url que não existe',
    'valid_ip'                 => 'O campo {field} precisa conter um IP válido',
    'valid_ipv4'               => 'O campo {field} precisa conter um endereço de IPv4 válido',
    'valid_ipv6'               => 'O campo {field} precisa conter um endereço de IPv6 válido',
    'guidv4'                   => 'O campo {field} precisa conter um valor válido de GUID',
    'valid_cc'                 => 'O campo {field} não possui um valor de cartão de crédito válido',
    'valid_name'               => 'O campo {field} precisa conter um nome completo',
    'contains'                 => 'O campo {field} pode conter apenas um dos valores a seguir: {param}',
    'contains_list'            => 'O campo {field} foi preenchido com uma opção inválida',
    'doesnt_contain_list'      => 'O campo {field} contém um valor que não é aceito',
    'street_address'           => 'O campo {field} precisa conter um nome de rua válido',
    'date'                     => 'O campo {field} precisa ser uma data válida',
    'min_numeric'              => 'O campo {field} precisa conter um valor numérico, igual, ou maior que {param}',
    'max_numeric'              => 'O campo {field} precisa conter um valor numérico, igual, ou menor que {param}',
    'min_age'                  => 'O campo {field} precisa conter uma idade maior ou igual a {param}',
    'starts'                   => 'O campo {field} precisa iniciar com {param}',
    'extension'                => 'O campo {field} permite apenas os seguintes formatos: {param}',
    'required_file'            => 'O campo {field} é de preenchimento obrigatório',
    'equalsfield'              => 'O campo {field} não é igual ao campo {param}',
    'iban'                     => 'O campo {field} precisa conter um número IBAN válido',
    'phone_number'             => 'O campo {field} precisa conter um número de telefone válido',
    'regex'                    => 'O campo {field} precisa conter um valor com formato válido',
    'valid_json_string'        => 'O campo {field} precisa conter um string com formato JSON',
    'valid_array_size_greater' => 'O campo {field} precisa conter um array com tamanho, igual, ou maior que {param}',
    'valid_array_size_lesser'  => 'O campo {field} precisa conter um array com tamanho, igual, ou menor que {param}',
    'valid_array_size_equal'   => 'O campo {field} precisa conter um array com tamanho igual a {param}',

    // Security validators
    'strong_password'          => 'O campo {field} deve conter pelo menos 8 caracteres com maiúsculas, minúsculas, números e caracteres especiais',
    'jwt_token'                => 'O campo {field} deve ser um formato de token JWT válido',
    'hash'                     => 'O campo {field} deve ser um hash {param} válido',
    'no_sql_injection'         => 'O campo {field} contém padrões potenciais de SQL injection',
    'no_xss'                   => 'O campo {field} contém padrões potenciais de XSS',

    // Modern web validators
    'uuid'                     => 'O campo {field} deve ser um UUID válido',
    'base64'                   => 'O campo {field} deve ser dados codificados em base64 válidos',
    'hex_color'                => 'O campo {field} deve ser um código de cor hexadecimal válido (ex: #FF0000)',
    'rgb_color'                => 'O campo {field} deve ser um formato de cor RGB válido (ex: rgb(255,0,0))',
    'timezone'                 => 'O campo {field} deve ser um identificador de fuso horário válido',
    'language_code'            => 'O campo {field} deve ser um código de idioma válido (ex: en, en-US)',
    'country_code'             => 'O campo {field} deve ser um código de país válido (ex: US, CA)',
    'currency_code'            => 'O campo {field} deve ser um código de moeda válido (ex: USD, EUR)',

    // Network validators
    'mac_address'              => 'O campo {field} deve ser um formato de endereço MAC válido',
    'domain_name'              => 'O campo {field} deve ser um nome de domínio válido',
    'port_number'              => 'O campo {field} deve ser um número de porta válido (1-65535)',
    'social_handle'            => 'O campo {field} deve ser um formato de identificador de rede social válido',

    // Geographic validators
    'latitude'                 => 'O campo {field} deve ser uma latitude válida (-90 a 90)',
    'longitude'                => 'O campo {field} deve ser uma longitude válida (-180 a 180)',
    'postal_code'              => 'O campo {field} deve ser um código postal válido para {param}',
    'coordinates'              => 'O campo {field} deve ser coordenadas válidas no formato lat,lng',

    // Enhanced date/time validators
    'future_date'              => 'O campo {field} deve ser uma data futura',
    'past_date'                => 'O campo {field} deve ser uma data passada',
    'business_day'             => 'O campo {field} deve cair em um dia útil (Segunda-feira a Sexta-feira)',
    'valid_time'               => 'O campo {field} deve ser um formato de hora válido (HH:MM ou HH:MM:SS)',
    'date_range'               => 'O campo {field} deve ser uma data entre {param[0]} e {param[1]}',

    // Mathematical validators
    'even'                     => 'O campo {field} deve ser um número par',
    'odd'                      => 'O campo {field} deve ser um número ímpar',
    'prime'                    => 'O campo {field} deve ser um número primo',

    // Content validators
    'word_count'               => 'A contagem de palavras do campo {field} não atende aos requisitos',
    'camel_case'               => 'O campo {field} deve estar no formato camelCase',
    'snake_case'               => 'O campo {field} deve estar no formato snake_case',
    'url_slug'                 => 'O campo {field} deve ser um formato de URL slug válido',
);
