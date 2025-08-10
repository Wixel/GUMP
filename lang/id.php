<?php

return array(
    'required'                 => 'Bagian {field} harus diisi',
    'valid_email'              => 'Bagian {field} harus berisi alamat email yang benar',
    'max_len'                  => 'Bagian {field} harus memiliki {param} karakter atau kurang',
    'min_len'                  => 'Bagian {field} memiliki sedikitnya {param} karakter',
    'exact_len'                => 'Bagian {field} harus memiliki tepat {param} karakter',
    'between_len'           => 'Bagian {field} harus memiliki panjang antara {param1} dan {param2} karakter',
    'alpha_numeric_dash'    => 'Bagian {field} hanya boleh berisi huruf, angka dan tanda hubung (-)',
    'alpha'                    => 'Bagian {field} hanya boleh berisi huruf',
    'alpha_numeric'            => 'Bagian {field} hanya boleh berisi huruf dan angkat',
    'alpha_numeric_space'      => 'Bagian {field} hanya boleh berisi huruf, angkat dan spasi',
    'alpha_dash'               => 'Bagian {field} hanya boleh berisi huruf dan \'-\'',
    'alpha_space'              => 'Bagian {field} hanya boleh berisi huruf dan spasi',
    'numeric'                  => 'Bagian {field} hanya boleh berisi angka',
    'integer'                  => 'Bagian {field} hanya boleh berisi angka tanpa memiliki desimal',
    'boolean'                  => 'Bagian {field} hanya boleh berisi true atau false',
    'float'                    => 'Bagian {field} hanya boleh berisi angka yang memiliki desimal (float)',
    'valid_url'                => 'Bagian {field} harus berupa URL',
    'url_exists'               => 'Bagian {field} tidak memiliki URL didalamnya',
    'valid_ip'                 => 'Bagian {field} harus berupa IP address yang benar',
    'valid_ipv4'               => 'Bagian {field} harus berupa IPv4 address yang benar',
    'valid_ipv6'               => 'Bagian {field} harus berupa IPv6 address yang benar',
    'guidv4'                   => 'Bagian {field} harus berupa GUID yang benar',
    'valid_cc'                 => 'Bagian {field} bukan merupakan nomor kartu kredit yang benar',
    'valid_name'               => 'Bagian {field} harus berupa nama lengkap',
    'contains'                 => 'Bagian {field} hanya boleh berisi salah satu dari pilihan berikut: {param}',
    'contains_list'            => 'Bagian {field} bukan merupakan opsi yang benar',
    'doesnt_contain_list'      => 'Bagian {field} memiliki nilai yang tidak diperbolehkan',
    'street_address'           => 'Bagian {field} harus berupa alamat lengkap yang benar',
    'date'                     => 'Bagian {field} harus memiliki format tanggal yang benar',
    'min_numeric'              => 'Bagian {field} hanya boleh berisi angka, yang memiliki karakter lebih besar atau sama dengan {param}',
    'max_numeric'              => 'Bagian {field} hanya boleh berisi angka, yang memiliki karakter lebih kecil atau sama dengan  {param}',
    'min_age'                  => 'Bagian {field}hanya boleh berisi \'umur\' yang lebih besar atau sama dengan {param}',
    'starts'                   => 'Bagian {field} harus dimulai oleh {param}',
    'extension'                => 'Bagian {field} hanya boleh memiliki salah satu daru ekstensi: {param}',
    'required_file'            => 'Bagian {field} harus diisi',
    'iban'                     => 'Bagian {field} harus berupa IBAN yang benar',
    'equalsfield'              => 'Bagian {field} harus sama dengan Bagian {param}',
    'phone_number'             => 'Bagian {field} harus berupa Nomor Telepon yang benar',
    'regex'                    => 'Bagian {field} harus memiliki nilai dengan format yang benar',
    'valid_json_string'        => 'Bagian {field} harus memiliki format JSON yang benar',
    'valid_array_size_greater' => 'Bagian {field} harus berupa array dengan ukuran lebih besar atau sama dengan {param}',
    'valid_array_size_lesser'  => 'Bagian {field} harus berupa array dengan ukuran lebih kecil atau sama dengan {param}',
    'valid_array_size_equal'   => 'Bagian {field} harus berupa array dengan ukuran {param}',

    // Security validators
    'strong_password'          => 'Bagian {field} harus berisi minimal 8 karakter dengan huruf besar, kecil, angka dan karakter khusus',
    'jwt_token'                => 'Bagian {field} harus berupa format JWT token yang valid',
    'hash'                     => 'Bagian {field} harus berupa hash {param} yang valid',
    'no_sql_injection'         => 'Bagian {field} mengandung pola SQL injection yang berpotensi berbahaya',
    'no_xss'                   => 'Bagian {field} mengandung pola XSS yang berpotensi berbahaya',

    // Modern web validators
    'uuid'                     => 'Bagian {field} harus berupa UUID yang valid',
    'base64'                   => 'Bagian {field} harus berupa data yang dikodekan base64 dengan valid',
    'hex_color'                => 'Bagian {field} harus berupa kode warna hexadecimal yang valid (contoh: #FF0000)',
    'rgb_color'                => 'Bagian {field} harus berupa format warna RGB yang valid (contoh: rgb(255,0,0))',
    'timezone'                 => 'Bagian {field} harus berupa pengenal zona waktu yang valid',
    'language_code'            => 'Bagian {field} harus berupa kode bahasa yang valid (contoh: en, en-US)',
    'country_code'             => 'Bagian {field} harus berupa kode negara yang valid (contoh: US, CA)',
    'currency_code'            => 'Bagian {field} harus berupa kode mata uang yang valid (contoh: USD, EUR)',

    // Network validators
    'mac_address'              => 'Bagian {field} harus berupa format alamat MAC yang valid',
    'domain_name'              => 'Bagian {field} harus berupa nama domain yang valid',
    'port_number'              => 'Bagian {field} harus berupa nomor port yang valid (1-65535)',
    'social_handle'            => 'Bagian {field} harus berupa format handle media sosial yang valid',

    // Geographic validators
    'latitude'                 => 'Bagian {field} harus berupa lintang yang valid (-90 sampai 90)',
    'longitude'                => 'Bagian {field} harus berupa bujur yang valid (-180 sampai 180)',
    'postal_code'              => 'Bagian {field} harus berupa kode pos yang valid untuk {param}',
    'coordinates'              => 'Bagian {field} harus berupa koordinat yang valid dalam format lat,lng',

    // Enhanced date/time validators
    'future_date'              => 'Bagian {field} harus berupa tanggal masa depan',
    'past_date'                => 'Bagian {field} harus berupa tanggal masa lalu',
    'business_day'             => 'Bagian {field} harus jatuh pada hari kerja (Senin-Jumat)',
    'valid_time'               => 'Bagian {field} harus berupa format waktu yang valid (HH:MM atau HH:MM:SS)',
    'date_range'               => 'Bagian {field} harus berupa tanggal antara {param[0]} dan {param[1]}',

    // Mathematical validators
    'even'                     => 'Bagian {field} harus berupa bilangan genap',
    'odd'                      => 'Bagian {field} harus berupa bilangan ganjil',
    'prime'                    => 'Bagian {field} harus berupa bilangan prima',

    // Content validators
    'word_count'               => 'Jumlah kata pada bagian {field} tidak memenuhi persyaratan',
    'camel_case'               => 'Bagian {field} harus dalam format camelCase',
    'snake_case'               => 'Bagian {field} harus dalam format snake_case',
    'url_slug'                 => 'Bagian {field} harus berupa format URL slug yang valid',
);
