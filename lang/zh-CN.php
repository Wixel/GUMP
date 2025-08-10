<?php

return array(
    'required'                 => '字段 {field} 必填',
    'valid_email'              => '字段 {field} 必须是一个有效的邮箱地址',
    'max_len'                  => '字段 {field} 不能大于 {param} 个字符',
    'min_len'                  => '字段 {field} 不能小于 {param} 个字符',
    'exact_len'                => '字段 {field} 必须恰好是 {param} 个字符',
    'between_len'              => '字段 {field} 必须介于 {param[0]}-{param[1]} 个字符之间',
    'alpha'                    => '字段 {field} 只能包含字母',
    'alpha_numeric'            => '字段 {field} 只能包含字母和数字',
    'alpha_numeric_space'      => '字段 {field} 只能包含字母、数字和空格',
    'alpha_numeric_dash'       => '字段 {field} 只能包含字母、数字、中划线(-)和下划线(_)',
    'alpha_dash'               => '字段 {field} 只能包含字母、中划线(-)和下划线(_)',
    'alpha_space'              => '字段 {field} 只能包含字母和空格',
    'numeric'                  => '字段 {field} 必须是一个数字',
    'integer'                  => '字段 {field} 必须是一个整数',
    'boolean'                  => '字段 {field} 必须是布尔值',
    'float'                    => '字段 {field} 必须是小数',
    'valid_url'                => '字段 {field} 必须是一个有效的网址',
    'url_exists'               => '字段 {field} 网址不存在',
    'valid_ip'                 => '字段 {field} 必须是一个有效的IP地址',
    'valid_ipv4'               => '字段 {field} 必须是一个有效的IPv4地址',
    'valid_ipv6'               => '字段 {field} 必须是一个有效的IPv6地址',
    'guidv4'                   => '字段 {field} 必须是一个有效的GUID',
    'valid_cc'                 => '字段 {field} 不是一个有效的信用卡账号',
    'valid_name'               => '字段 {field} 应该是一全名',
    'contains'                 => '字段 {field} 只能是以下其中之一: {param}',
    'contains_list'            => '字段 {field} 不是一个有效的值',
    'doesnt_contain_list'      => '字段 {field} 包含一个不被接受的值',
    'street_address'           => '字段 {field} 必须是一个有效的街道地址，注：不适用于中文）',
    'date'                     => '字段 {field} 必须是一个有效的日期',
    'min_numeric'              => '字段 {field} 必须是一个数字, 且大于等于 {param}',
    'max_numeric'              => '字段 {field} 必须是一个数字, 且小于等于 {param}',
    'min_age'                  => '字段 {field} 需要是个大于等于 {param} 的年龄',
    'starts'                   => '字段 {field} 需要以 {param} 开始',
    'extension'                => '字段 {field} 只支持以下文件格式: {param}，注：不检查Mime-Type',
    'required_file'            => '字段 {field} 文件上传不成功',
    'equalsfield'              => '字段 {field} 不等于字段 {param}',
    'iban'                     => '字段 {field} 必须是一个有效的国际银行帐户号码(IBAN)',
    'phone_number'             => '字段 {field} 必须是一个有效的手机号码',
    'regex'                    => '字段 {field} 必须是一个有效格式的值',
    'valid_json_string'        => '字段 {field} 必须是一个有效的JSON字符串',
    'valid_array_size_greater' => '字段 {field} 必须是一个数组，且至少有 {param} 个元素',
    'valid_array_size_lesser'  => '字段 {field} 必须是一个数组，且最多有 {param} 个元素',
    'valid_array_size_equal'   => '字段 {field} 必须是一个数组，且有 {param} 个元素',

    // Security validators
    'strong_password'          => '字段 {field} 必须包含至少8个字符，包括大写字母、小写字母、数字和特殊字符',
    'jwt_token'                => '字段 {field} 必须是有效的JWT令牌格式',
    'hash'                     => '字段 {field} 必须是有效的{param}哈希值',
    'no_sql_injection'         => '字段 {field} 包含潜在的SQL注入模式',
    'no_xss'                   => '字段 {field} 包含潜在的XSS模式',

    // Modern web validators
    'uuid'                     => '字段 {field} 必须是有效的UUID',
    'base64'                   => '字段 {field} 必须是有效的base64编码数据',
    'hex_color'                => '字段 {field} 必须是有效的十六进制颜色代码（例如：#FF0000）',
    'rgb_color'                => '字段 {field} 必须是有效的RGB颜色格式（例如：rgb(255,0,0)）',
    'timezone'                 => '字段 {field} 必须是有效的时区标识符',
    'language_code'            => '字段 {field} 必须是有效的语言代码（例如：en、en-US）',
    'country_code'             => '字段 {field} 必须是有效的国家代码（例如：US、CA）',
    'currency_code'            => '字段 {field} 必须是有效的货币代码（例如：USD、EUR）',

    // Network validators
    'mac_address'              => '字段 {field} 必须是有效的MAC地址格式',
    'domain_name'              => '字段 {field} 必须是有效的域名',
    'port_number'              => '字段 {field} 必须是有效的端口号（1-65535）',
    'social_handle'            => '字段 {field} 必须是有效的社交媒体用户名格式',

    // Geographic validators
    'latitude'                 => '字段 {field} 必须是有效的纬度（-90到90）',
    'longitude'                => '字段 {field} 必须是有效的经度（-180到180）',
    'postal_code'              => '字段 {field} 必须是{param}的有效邮政编码',
    'coordinates'              => '字段 {field} 必须是lat,lng格式的有效坐标',

    // Enhanced date/time validators
    'future_date'              => '字段 {field} 必须是未来日期',
    'past_date'                => '字段 {field} 必须是过去日期',
    'business_day'             => '字段 {field} 必须是工作日（周一至周五）',
    'valid_time'               => '字段 {field} 必须是有效的时间格式（HH:MM或HH:MM:SS）',
    'date_range'               => '字段 {field} 必须是{param[0]}和{param[1]}之间的日期',

    // Mathematical validators
    'even'                     => '字段 {field} 必须是偶数',
    'odd'                      => '字段 {field} 必须是奇数',
    'prime'                    => '字段 {field} 必须是质数',

    // Content validators
    'word_count'               => '字段 {field} 的字数不符合要求',
    'camel_case'               => '字段 {field} 必须是驼峰命名格式',
    'snake_case'               => '字段 {field} 必须是下划线命名格式',
    'url_slug'                 => '字段 {field} 必须是有效的URL slug格式',
);
