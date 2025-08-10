<?php

return array(
    'required'                 => 'שדה {field} הינו חובה',
    'valid_email'              => 'שדה {field} מוכרח להיות כתובת דוא"ל חוקית',
    'max_len'                  => 'שדה {field} מוכרח להיות {param} תווים או פחות',
    'min_len'                  => 'שדה {field} מוכרח להיות לפחות {param} תווים',
    'exact_len'                => 'שדה {field} מוכרח להיות בדיוק {param} תווים',
    'between_len'               => 'שדה {field} מוכרח להיות בין {param} ל-{param2} תווים.',
    'alpha_numeric_dash'        => 'שדה {field} יכול להכיל אותיות, מספרים ומקפים בלבד.',
    'alpha'                    => 'שדה {field} יכול להכיל אותיות בלבד',
    'alpha_numeric'            => 'שדה {field} יכול להכיל אותיות ומספרים בלבד',
    'alpha_numeric_space'      => 'שדה {field} יכול להכיל אותיות, מספרים ורווחים בלבד',
    'alpha_dash'               => 'שדה {field} יכול להכיל אותיות ומקפים בלבד',
    'alpha_space'              => 'שדה {field} יכול להכיל אותיות ורווחים בלבד',
    'numeric'                  => 'שדה {field} מוכרח להיות מספר',
    'integer'                  => 'שדה {field} מוכרח להיות מספר שלם',
    'boolean'                  => 'שדה {field} מוכרח להיות אמת או שקר',
    'float'                    => 'שדה {field} מוכרח להיות מספר עם נקודה עשרונית',
    'valid_url'                => 'שדה {field} מוכרח להיות כתובת אתר',
    'url_exists'               => 'שדה {field} אינו כתובת אתר קיימת',
    'valid_ip'                 => 'שדה {field} מוכרח להיות כתובת IP חוקית',
    'valid_ipv4'               => 'שדה {field} מוכרח להכיל כתובת IPv4 חוקית',
    'valid_ipv6'               => 'שדה {field} מוכרח להכיל כתובת IPv6 חוקית',
    'guidv4'                   => 'שדה {field} מוכרח להכיל GUID תקין',
    'valid_cc'                 => 'שדה {field} אינו מספר כרטיס אשראי חוקי',
    'valid_name'               => 'שדה {field} מוכרח להכיל שם מלא',
    'contains'                 => 'שדה {field} יכול להכיל רק אחד מן הערכים הבאים: {param}',
    'contains_list'            => 'שדה {field} אינו אפשרות חוקית',
    'doesnt_contain_list'      => 'שדה {field} מכיל ערך שאינו מקובל',
    'street_address'           => 'שדה {field} מוכרח להיות כתובת רחוב חוקית',
    'date'                     => 'שדה {field} מוכרח להיות תאריך חוקי',
    'min_numeric'              => 'שדה {field} מוכרח להיות ערך מספרי, שווה ל, או גבוה מ {param}',
    'max_numeric'              => 'שדה {field} מוכרח להיות ערך מספרי, שווה או נמוך מ {param}',
    'min_age'                  => 'שדה {field} מוכרח להיות גיל גדול או שווה ל {param}',
    'starts'                   => 'שדה {field} מוכרח להתחיל עם {param}',
    'extension'                => 'שדה {field} יכול להיות רק אחת מן הסיומות הבאות: {param}',
    'required_file'            => 'שדה {field} הינו שדה קובץ חובה',
    'equalsfield'              => 'שדה {field} אינו שווה לשדה {param}',
    'iban'                     => 'שדה {field} מוכרח להכיל IBAN חוקי',
    'phone_number'             => 'שדה {field} מוכרח להיות מספר טלפון חוקי',
    'regex'                    => 'שדה {field} מוכרח להכיל ערך בפורמט חוקי',
    'valid_json_string'        => 'שדה {field} להכיל מחרוזת בפורמט JSON חוקי',
    'valid_array_size_greater' => 'שדה {field} מוכרח להיות מערך בעל גודל, שווה ל, או גבוה מ {param}',
    'valid_array_size_lesser'  => 'שדה {field} מוכרח להיות מערך עם גודל, שווה או נמוך מ {param}',
    'valid_array_size_equal'   => 'שדה {field} מוכרח להיות מערך עם גודל שווה ל {param}',

    // Security validators
    'strong_password'          => 'שדה {field} מוכרח להכיל לפחות 8 תווים עם אותיות גדולות, קטנות, מספר ותו מיוחד',
    'jwt_token'                => 'שדה {field} מוכרח להיות בפורמט JWT token חוקי',
    'hash'                     => 'שדה {field} מוכרח להיות hash {param} חוקי',
    'no_sql_injection'         => 'שדה {field} מכיל דפוסי SQL injection פוטנציאליים',
    'no_xss'                   => 'שדה {field} מכיל דפוסי XSS פוטנציאליים',

    // Modern web validators
    'uuid'                     => 'שדה {field} מוכרח להיות UUID חוקי',
    'base64'                   => 'שדה {field} מוכרח להיות מידע מקודד base64 חוקי',
    'hex_color'                => 'שדה {field} מוכרח להיות קוד צבע הקסדצימלי חוקי (כמו #FF0000)',
    'rgb_color'                => 'שדה {field} מוכרח להיות בפורמט צבע RGB חוקי (כמו rgb(255,0,0))',
    'timezone'                 => 'שדה {field} מוכרח להיות מזהה אזור זמן חוקי',
    'language_code'            => 'שדה {field} מוכרח להיות קוד שפה חוקי (כמו en, en-US)',
    'country_code'             => 'שדה {field} מוכרח להיות קוד מדינה חוקי (כמו US, CA)',
    'currency_code'            => 'שדה {field} מוכרח להיות קוד מטבע חוקי (כמו USD, EUR)',

    // Network validators
    'mac_address'              => 'שדה {field} מוכרח להיות בפורמט כתובת MAC חוקי',
    'domain_name'              => 'שדה {field} מוכרח להיות שם דומיין חוקי',
    'port_number'              => 'שדה {field} מוכרח להיות מספר פורט חוקי (1-65535)',
    'social_handle'            => 'שדה {field} מוכרח להיות בפורמט משתמש רשתות חברתיות חוקי',

    // Geographic validators
    'latitude'                 => 'שדה {field} מוכרח להיות קו רוחב חוקי (-90 עד 90)',
    'longitude'                => 'שדה {field} מוכרח להיות קו אורך חוקי (-180 עד 180)',
    'postal_code'              => 'שדה {field} מוכרח להיות מיקוד חוקי עבור {param}',
    'coordinates'              => 'שדה {field} מוכרח להיות קואורדינטות חוקיות בפורמט lat,lng',

    // Enhanced date/time validators
    'future_date'              => 'שדה {field} מוכרח להיות תאריך עתידי',
    'past_date'                => 'שדה {field} מוכרח להיות תאריך עבר',
    'business_day'             => 'שדה {field} מוכרח להיות ביום עסקים (ראשון-חמישי)',
    'valid_time'               => 'שדה {field} מוכרח להיות בפורמט זמן חוקי (HH:MM או HH:MM:SS)',
    'date_range'               => 'שדה {field} מוכרח להיות תאריך בין {param[0]} ו-{param[1]}',

    // Mathematical validators
    'even'                     => 'שדה {field} מוכרח להיות מספר זוגי',
    'odd'                      => 'שדה {field} מוכרח להיות מספר אי-זוגי',
    'prime'                    => 'שדה {field} מוכרח להיות מספר ראשוני',

    // Content validators
    'word_count'               => 'ספירת המילים של שדה {field} לא עומדת בדרישות',
    'camel_case'               => 'שדה {field} מוכרח להיות בפורמט camelCase',
    'snake_case'               => 'שדה {field} מוכרח להיות בפורמט snake_case',
    'url_slug'                 => 'שדה {field} מוכרח להיות בפורמט URL slug חוקי',
);
