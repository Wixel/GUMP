<?php

return array(
    'required'                 => '{field} الزامی میباشد',
    'valid_email'              => '{field} باید یک آدرس ایمیل معتبر باشد ',
    'max_len'                  => '{field} باید حد اکثر {param} کاراکتر یا کمتر باشد',
    'min_len'                  => '{field} باید حد اقل {param} کاراکتر یا بیشتر باشد',
    'exact_len'                => '{field} باید دقیقا {param} کاراکتر باشد',
    'between_len'             => '{field} باید بین {param} و {param2} کاراکتر باشد',
    'alpha_numeric_dash'      => '{field} فقط باید شامل حروف، اعداد و خط فاصله باشد',
    'alpha'                    => '{field} فقط باید شامل حروف باشد',
    'alpha_numeric'            => '{field} فقط باید شامل حروف و اعداد باشد',
    'alpha_numeric_space'      => '{field} فقط باید شامل حروف، اعداد و فاصله باشد',
    'alpha_dash'               => '{field} فقط باید شامل حروف و خط فاصله یا خط تیره باشد',
    'alpha_space'              => '{field} فقط باید شامل حروف و فاصله باشد',
    'numeric'                  => '{field} باید یک عدد باشد',
    'integer'                  => '{field} باید یک عدد بدون نقطه اعشار یا ممیز باشد',
    'boolean'                  => '{field} باید درست/صحیح یا نادرست/غلط باشد',
    'float'                    => '{field} باید عدد با نقطه اعشار یا ممیز باشد',
    'valid_url'                => '{field} باید یک آدرس وب باشد',
    'url_exists'               => '{field} آدرس وب/اینترنت وجود ندارد',
    'valid_ip'                 => '{field} باید یک IP آدرس معتبر باشد',
    'valid_ipv4'               => '{field} باید یک آدرس IPv4 معتبر باشد',
    'valid_ipv6'               => '{field} باید یک آدرس IPv6 معتبر باشد',
    'guidv4'                   => '{field} باید یک GUID معتبر داشته باشد',
    'valid_cc'                 => '{field} شماره کارت اعتباری درست نیست',
    'valid_name'               => '{field} باید یک نام معتبر باشد',
    'contains'                 => 'The {field} فقط میتواند یکی از موارد {param} زیر باشد',
    'contains_list'            => '{field} این گزینه درست نیست',
    'doesnt_contain_list'      => '{field} شامل یک مقدار نادرست هست',
    'street_address'           => '{field} باید یک آدرس خیابان معتبر باشد',
    'date'                     => '{field} باید یک تاریخ معتبر باشد',
    'min_numeric'              => '{field} باید یک مقدار عددی مساوی یا بزرگتر از {param} باشد',
    'max_numeric'              => '{field} باید یک مقدار عددی مساوی یا کوچکتر از {param} باشد',
    'min_age'                  => '{field} باید مقدار عمر یا سن شخص مساوی یا بزرگتر از {param} باشد',
    'starts'                   => '{field} باید با {param} شروع گردد',
    'extension'                => '{field} فقط میتواند یکی از پسوند های {param} را داشته باشد',
    'required_file'            => '{field} الزامی است',
    'equalsfield'              => '{field} برابر با {param} نمی باشد',
    'iban'                     => '{field} باید یک IBAN معتبر باشد',
    'phone_number'             => '{field} باید یک شماره تلفن معتبر باشد',
    'regex'                    => '{field} باید شامل یک مقدار با فرمت درست و معتبر باشد',
    'valid_json_string'        => '{field} باید شامل یک فرمت درست و معتبر JSON باشد',
    'valid_array_size_greater' => '{field} باید یک آرایه که اندازه آن مساوی یا بزرگتر از {param} باشد',
    'valid_array_size_lesser'  => '{field} باید یک آرایه که اندازه آن مساوی یا کوچکتر از {param} باشد',
    'valid_array_size_equal'   => '{field} باید یک آرایه که اندازه آن برابر با {param} باشد',

    // Security validators
    'strong_password'          => '{field} باید حداقل ۸ کاراکتر با حروف بزرگ، کوچک، عدد و کاراکتر خاص شامل باشد',
    'jwt_token'                => '{field} باید فرمت معتبر JWT توکن باشد',
    'hash'                     => '{field} باید یک هش معتبر {param} باشد',
    'no_sql_injection'         => '{field} شامل الگوهای احتمالی SQL injection است',
    'no_xss'                   => '{field} شامل الگوهای احتمالی XSS است',

    // Modern web validators
    'uuid'                     => '{field} باید یک UUID معتبر باشد',
    'base64'                   => '{field} باید داده‌های کدگذاری شده base64 معتبر باشد',
    'hex_color'                => '{field} باید یک کد رنگ شانزده‌شانزدهی معتبر باشد (مثل #FF0000)',
    'rgb_color'                => '{field} باید یک فرمت رنگ RGB معتبر باشد (مثل rgb(255,0,0))',
    'timezone'                 => '{field} باید یک شناسه منطقه زمانی معتبر باشد',
    'language_code'            => '{field} باید یک کد زبان معتبر باشد (مثل en، en-US)',
    'country_code'             => '{field} باید یک کد کشور معتبر باشد (مثل US، CA)',
    'currency_code'            => '{field} باید یک کد ارز معتبر باشد (مثل USD، EUR)',

    // Network validators
    'mac_address'              => '{field} باید فرمت آدرس MAC معتبر باشد',
    'domain_name'              => '{field} باید یک نام دامنه معتبر باشد',
    'port_number'              => '{field} باید یک شماره پورت معتبر باشد (۱-۶۵۵۳۵)',
    'social_handle'            => '{field} باید فرمت هندل شبکه‌های اجتماعی معتبر باشد',

    // Geographic validators
    'latitude'                 => '{field} باید یک عرض جغرافیایی معتبر باشد (۹۰- تا ۹۰)',
    'longitude'                => '{field} باید یک طول جغرافیایی معتبر باشد (۱۸۰- تا ۱۸۰)',
    'postal_code'              => '{field} باید یک کد پستی معتبر برای {param} باشد',
    'coordinates'              => '{field} باید مختصات معتبر در فرمت lat,lng باشد',

    // Enhanced date/time validators
    'future_date'              => '{field} باید یک تاریخ آینده باشد',
    'past_date'                => '{field} باید یک تاریخ گذشته باشد',
    'business_day'             => '{field} باید در یک روز کاری باشد (دوشنبه-جمعه)',
    'valid_time'               => '{field} باید یک فرمت زمان معتبر باشد (HH:MM یا HH:MM:SS)',
    'date_range'               => '{field} باید تاریخی بین {param[0]} و {param[1]} باشد',

    // Mathematical validators
    'even'                     => '{field} باید یک عدد زوج باشد',
    'odd'                      => '{field} باید یک عدد فرد باشد',
    'prime'                    => '{field} باید یک عدد اول باشد',

    // Content validators
    'word_count'               => 'شمارش کلمات {field} الزامات را برآورده نمی‌کند',
    'camel_case'               => '{field} باید در فرمت camelCase باشد',
    'snake_case'               => '{field} باید در فرمت snake_case باشد',
    'url_slug'                 => '{field} باید فرمت URL slug معتبر باشد',
);
