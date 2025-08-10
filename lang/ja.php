<?php

return array(
    'required'                 => '{field}フィールドは必須です',
    'valid_email'              => '{field}フィールドはメールアドレス形式でなければいけません',
    'max_len'                  => '{field}フィールドは{param}文字以下でなければいけません',
    'min_len'                  => '{field}フィールドは{param}文字以上でなければいけません',
    'exact_len'                => '{field}フィールドは{param}文字でなければいけません',
    'between_len'          => '{field}フィールドは{param1}文字以上{param2}文字以下でなければいけません',
    'alpha_numeric_dash'   => '{field}フィールドは英数字とダッシュのみで構成されていなければいけません',
    'alpha'                    => '{field}フィールドはアルファベットでなければいけません',
    'alpha_numeric'            => '{field}フィールドは英数字でなければいけません',
    'alpha_numeric_space'      => '{field}フィールドは英数字(空白含む)でなければいけません',
    'alpha_dash'               => '{field}フィールドはアルファベット(ダッシュ含む)でなければいけません',
    'alpha_space'              => '{field}フィールドはアルファベット(空白含む)でなければいけません',
    'numeric'                  => '{field}フィールドは数字でなければいけません',
    'integer'                  => '{field}フィールドは整数でなければいけません',
    'boolean'                  => '{field}フィールドは真理値でなければいけません',
    'float'                    => '{field}フィールドは浮動小数点数でなければいけません',
    'valid_url'                => '{field}フィールドはURL形式でなければいけません',
    'url_exists'               => '{field}のURLは存在しません',
    'valid_ip'                 => '{field}フィールドはIPアドレス形式でなければいけません',
    'valid_ipv4'               => '{field}フィールドはIPアドレス形式(IPv4)でなければいけません',
    'valid_ipv6'               => '{field}フィールドはIPアドレス形式(IPv6)でなければいけません',
    'guidv4'                   => '{field}フィールドはGUID形式でなければいけません',
    'valid_cc'                 => '{field}は有効なクレジットカード番号ではありません',
    'valid_name'               => '{field}フィールドは姓名でなければいけません',
    'contains'                 => '{field}は次のうちいずれかでなければいけません。({param})',
    'contains_list'            => '{field}は有効な選択肢ではありません',
    'doesnt_contain_list'      => '{field}は無効な選択肢を含んでいます',
    'street_address'           => '{field}フィールドは住所でなければいけません',
    'date'                     => '{field}フィールドは日付形式でなければいけません',
    'min_numeric'              => '{field}フィールドは{param}以上でなければいけません',
    'max_numeric'              => '{field}フィールドは{param}以下でなければいけません',
    'min_age'                  => '{field}は{param}歳以上でなければいけません',
    'starts'                   => '{field}フィールドは{param}で始まらなければなりません',
    'extension'                => '{field}フィールドは次のうちいずれかの拡張子でなければいけません({param})',
    'required_file'            => '{field}フィールドは必須です',
    'equalsfield'              => '{field}フィールドは{param}と異なります',
    'iban'                     => '{field}フィールドはIBAN形式でなければいけません',
    'phone_number'             => '{field}フィールドは電話番号でなければいけません',
    'regex'                    => '{field}フィールドは有効な形式でなければいけません',
    'valid_json_string'        => '{field}フィールドは有効なJSON形式でなければいけません',
    'valid_array_size_greater' => '{field}フィールドは{param}個以上の配列でなければいけません',
    'valid_array_size_lesser'  => '{field}フィールドは{param}個以下の配列でなければいけません',
    'valid_array_size_equal'   => '{field}フィールドは{param}個の配列でなければいけません',

    // Security validators
    'strong_password'          => '{field}フィールドは大文字、小文字、数字、特殊文字を含む8文字以上でなければいけません',
    'jwt_token'                => '{field}フィールドは有効なJWTトークン形式でなければいけません',
    'hash'                     => '{field}フィールドは有効な{param}ハッシュでなければいけません',
    'no_sql_injection'         => '{field}フィールドは潜在的なSQLインジェクションパターンを含んでいます',
    'no_xss'                   => '{field}フィールドは潜在的なXSSパターンを含んでいます',

    // Modern web validators
    'uuid'                     => '{field}フィールドは有効なUUIDでなければいけません',
    'base64'                   => '{field}フィールドは有効なbase64エンコードデータでなければいけません',
    'hex_color'                => '{field}フィールドは有効な16進数カラーコードでなければいけません（例：#FF0000）',
    'rgb_color'                => '{field}フィールドは有効なRGBカラー形式でなければいけません（例：rgb(255,0,0)）',
    'timezone'                 => '{field}フィールドは有効なタイムゾーン識別子でなければいけません',
    'language_code'            => '{field}フィールドは有効な言語コードでなければいけません（例：en、en-US）',
    'country_code'             => '{field}フィールドは有効な国コードでなければいけません（例：US、CA）',
    'currency_code'            => '{field}フィールドは有効な通貨コードでなければいけません（例：USD、EUR）',

    // Network validators
    'mac_address'              => '{field}フィールドは有効なMACアドレス形式でなければいけません',
    'domain_name'              => '{field}フィールドは有効なドメイン名でなければいけません',
    'port_number'              => '{field}フィールドは有効なポート番号でなければいけません（1-65535）',
    'social_handle'            => '{field}フィールドは有効なソーシャルメディアハンドル形式でなければいけません',

    // Geographic validators
    'latitude'                 => '{field}フィールドは有効な緯度でなければいけません（-90から90）',
    'longitude'                => '{field}フィールドは有効な経度でなければいけません（-180から180）',
    'postal_code'              => '{field}フィールドは{param}の有効な郵便番号でなければいけません',
    'coordinates'              => '{field}フィールドはlat,lng形式の有効な座標でなければいけません',

    // Enhanced date/time validators
    'future_date'              => '{field}フィールドは未来の日付でなければいけません',
    'past_date'                => '{field}フィールドは過去の日付でなければいけません',
    'business_day'             => '{field}フィールドは営業日でなければいけません（月曜日-金曜日）',
    'valid_time'               => '{field}フィールドは有効な時間形式でなければいけません（HH:MMまたはHH:MM:SS）',
    'date_range'               => '{field}フィールドは{param[0]}と{param[1]}の間の日付でなければいけません',

    // Mathematical validators
    'even'                     => '{field}フィールドは偶数でなければいけません',
    'odd'                      => '{field}フィールドは奇数でなければいけません',
    'prime'                    => '{field}フィールドは素数でなければいけません',

    // Content validators
    'word_count'               => '{field}フィールドの単語数が要件を満たしていません',
    'camel_case'               => '{field}フィールドはcamelCase形式でなければいけません',
    'snake_case'               => '{field}フィールドはsnake_case形式でなければいけません',
    'url_slug'                 => '{field}フィールドは有効なURLスラグ形式でなければいけません',
);
