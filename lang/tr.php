<?php

return array(
    'required'                 => '{field} alanı zorunludur',
    'valid_email'              => '{field} alanı geçerli bir e-posta adresi olmalı',
    'max_len'                  => '{field} alanı en fazla {param} karakter veya daha az olmalı',
    'min_len'                  => '{field} alanı en az {param} karakter olmalı',
    'exact_len'                => '{field} alanı tam {param} karakter olmalı',
    'between_len'        => '{field} alanı {param[0]} ile {param[1]} karakter arasında olmalı',
    'alpha'                    => '{field} alanı sadece harflerden oluşabilir',
    'alpha_numeric'            => '{field} alanı sadece harf ve sayılardan oluşabilir',
    'alpha_numeric_space'      => '{field} alanı sadece harf, sayı ve boşluklardan oluşabilir',
    'alpha_numeric_dash' => '{field} alanı sadece harf, rakam, çizgi ve alt çizgi içerebilir',
    'alpha_dash'               => '{field} alanı sadece harf ve çizgilerden oluşabilir',
    'alpha_space'              => '{field} alanı sadece harf ve boşluklardan oluşabilir',
    'numeric'                  => '{field} alanı bir sayı olmalı',
    'integer'                  => '{field} alanı sadece tam sayı olabilir',
    'boolean'                  => '{field} alanı Doğru yada Yanlış olmak zorunda',
    'float'                    => '{field} alanı ondalık noktası olan bir sayı olmalı (float)',
    'valid_url'                => '{field} alanı URL olmak zorunda',
    'url_exists'               => '{field} URL bulunamadı',
    'valid_ip'                 => '{field} alanı geçerli bir IP adresi olamlı',
    'valid_ipv4'               => '{field} alanı geçerli bir IPv4 adresi içermesi gerekiyor',
    'valid_ipv6'               => '{field} alanı geçerli bir IPv6 adresi içermesi gerekiyor',
    'guidv4'                   => '{field} alanı geçerli bir GUID içermesi gerekiyor',
    'valid_cc'                 => '{field} geçerli bir kredi kartı numarası değil',
    'valid_name'               => '{field} tam isim olmalı',
    'contains'                 => '{field} sadece aşağıdakilerden biri olabilir: {param}',
    'contains_list'            => '{field} geçerli bir seçenek değil',
    'doesnt_contain_list'      => '{field} alanı kabul edilmeyen bir değer içeriyor',
    'street_address'           => '{field} alanı geçerli bir sokak adresi olmalı',
    'date'                     => '{field} geçerli bir tarih olmalı',
    'min_numeric'              => '{field} alanı {param} veya ondan yüksek sayısal bir değer olması gerekir',
    'max_numeric'              => '{field} alanı {param} veya ondan düşük sayısal bir değer olması gerekir',
    'min_age'                  => '{field} alanı {param} veya daha büyük bir yaşta olması gerekir',
    'starts'                   => '{field} alanı {param} ile başlamalıdır',
    'extension'                => '{field} alanı Aşağıdaki uzantılardan yalnızca biri olabilir: {param}',
    'required_file'            => '{field} alanı zorunlu',
    'equalsfield'              => '{field} alanı {param} alanına eşit değil.',
    'iban'                     => '{field} alanı geçerli bir IBAN olmalı',
    'phone_number'             => '{field} alanı geçerli bir telefon numarası olmalı',
    'regex'                    => '{field} alanı geçerli biçime sahip bir değer içermesi gerekiyor',
    'valid_json_string'        => '{field} alanı geçerli bir JSON biçiminde dize içermesi gerekiyor',
    'valid_array_size_greater' => '{field} alanı {param} veya daha yüksek bir dizi olması gerekir',
    'valid_array_size_lesser'  => '{field} alanı {param} veya daha küçük bir dizi olması gerekir',
    'valid_array_size_equal'   => '{field} alanı {param} boyutuna eşit bir dizi olması gerekiyor',

    // Security validators
    'strong_password'          => '{field} alanı büyük harf, küçük harf, sayı ve özel karakter içeren en az 8 karakter olmalı',
    'jwt_token'                => '{field} alanı geçerli bir JWT token formatında olmalı',
    'hash'                     => '{field} alanı geçerli bir {param} hash olmalı',
    'no_sql_injection'         => '{field} alanı potansiyel SQL injection desenleri içeriyor',
    'no_xss'                   => '{field} alanı potansiyel XSS desenleri içeriyor',

    // Modern web validators
    'uuid'                     => '{field} alanı geçerli bir UUID olmalı',
    'base64'                   => '{field} alanı geçerli base64 kodlanmış veri olmalı',
    'hex_color'                => '{field} alanı geçerli bir onaltılık renk kodu olmalı (örn: #FF0000)',
    'rgb_color'                => '{field} alanı geçerli bir RGB renk formatında olmalı (örn: rgb(255,0,0))',
    'timezone'                 => '{field} alanı geçerli bir saat dilimi tanımlayıcısı olmalı',
    'language_code'            => '{field} alanı geçerli bir dil kodu olmalı (örn: en, en-US)',
    'country_code'             => '{field} alanı geçerli bir ülke kodu olmalı (örn: US, CA)',
    'currency_code'            => '{field} alanı geçerli bir para birimi kodu olmalı (örn: USD, EUR)',

    // Network validators
    'mac_address'              => '{field} alanı geçerli bir MAC adresi formatında olmalı',
    'domain_name'              => '{field} alanı geçerli bir domain adı olmalı',
    'port_number'              => '{field} alanı geçerli bir port numarası olmalı (1-65535)',
    'social_handle'            => '{field} alanı geçerli bir sosyal medya kullanıcı adı formatında olmalı',

    // Geographic validators
    'latitude'                 => '{field} alanı geçerli bir enlem olmalı (-90 ile 90 arası)',
    'longitude'                => '{field} alanı geçerli bir boylam olmalı (-180 ile 180 arası)',
    'postal_code'              => '{field} alanı {param} için geçerli bir posta kodu olmalı',
    'coordinates'              => '{field} alanı lat,lng formatında geçerli koordinatlar olmalı',

    // Enhanced date/time validators
    'future_date'              => '{field} alanı gelecek bir tarih olmalı',
    'past_date'                => '{field} alanı geçmiş bir tarih olmalı',
    'business_day'             => '{field} alanı iş günü olmalı (Pazartesi-Cuma)',
    'valid_time'               => '{field} alanı geçerli bir saat formatında olmalı (HH:MM veya HH:MM:SS)',
    'date_range'               => '{field} alanı {param[0]} ve {param[1]} arasında bir tarih olmalı',

    // Mathematical validators
    'even'                     => '{field} alanı çift sayı olmalı',
    'odd'                      => '{field} alanı tek sayı olmalı',
    'prime'                    => '{field} alanı asal sayı olmalı',

    // Content validators
    'word_count'               => '{field} alanının kelime sayısı gereksinimleri karşılamıyor',
    'camel_case'               => '{field} alanı camelCase formatında olmalı',
    'snake_case'               => '{field} alanı snake_case formatında olmalı',
    'url_slug'                 => '{field} alanı geçerli bir URL slug formatında olmalı',
);
