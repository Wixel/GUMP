<?php

return array(
    'required'     => '{field} là bắt buộc',
    'valid_email'       => '{field} phải là địa chỉ email hợp lệ',
    'max_len'      => '{field} cần phải có {param} ký tự trở xuống',
    'min_len'      => '{field} cần có ít nhất {param} ký tự',
    'exact_len'        => '{field} cần có chính xác các ký tự {param}',
    'between_len'      => '{field} cần nằm trong khoảng {param[0]} và {param[1]} ký tự',
    'alpha'        => '{field} chỉ có thể chứa các chữ cái',
    'alpha_numeric'        => '{field} chỉ có thể chứa các chữ cái và số',
    'alpha_numeric_space'      => '{field} chỉ có thể chứa các chữ cái, số và khoảng trắng',
    'alpha_numeric_dash'       => '{field} chỉ có thể chứa các chữ cái, số, dấu gạch ngang và dấu gạch dưới',
    'alpha_dash'       => '{field} chỉ có thể chứa các chữ cái, dấu gạch ngang và dấu gạch dưới',
    'alpha_space'      => '{field} chỉ có thể chứa các chữ cái và dấu cách',
    'numeric'      => '{field} phải là một số',
    'integer'      => '{field} phải là một số không có số thập phân',
    'boolean'      => '{field} phải đúng hoặc sai',
    'float'        => '{field} phải là một số có dấu thập phân (dấu phẩy)',
    'valid_url'        => '{field} phải là một URL',
    'url_exists'       => 'URL {field} không tồn tại',
    'valid_ip'     => '{field} cần phải là địa chỉ IP hợp lệ',
    'valid_ipv4'       => '{field} cần chứa địa chỉ IPv4 hợp lệ',
    'valid_ipv6'       => '{field} cần chứa địa chỉ IPv6 hợp lệ',
    'guidv4'       => '{field} cần chứa GUID hợp lệ',
    'valid_cc'     => '{field} không phải là số thẻ tín dụng hợp lệ',
    'valid_name'       => '{field} phải là tên đầy đủ',
    'contains'     => '{field} chỉ có thể là một trong những trường hợp sau: {param}',
    'contains_list'        => '{field} không phải là một tùy chọn hợp lệ',
    'doesnt_contain_list'      => '{field} chứa giá trị không được chấp nhận',
    'street_address'       => '{field} cần phải là địa chỉ đường phố hợp lệ',
    'date'     => '{field} phải là một ngày hợp lệ',
    'min_numeric'      => '{field} cần phải là một giá trị số, bằng hoặc cao hơn {param}',
    'max_numeric'      => '{field} cần phải là một giá trị số, bằng hoặc thấp hơn {param}',
    'min_age'      => '{field} cần có tuổi lớn hơn hoặc bằng {param}',
    'starts'       => '{field} cần bắt đầu bằng {param}',
    'extension'        => '{field} chỉ có thể có một trong các mở rộng sau: {param}',
    'required_file'        => '{field} là bắt buộc',
    'equalsfield'      => '{field} không bằng {param}',
    'iban'     => '{field} cần chứa IBAN hợp lệ',
    'phone_number'     => '{field} cần phải là Số điện thoại hợp lệ',
    'regex'        => '{field} cần chứa một giá trị với định dạng hợp lệ',
    'valid_json_string'        => '{field} cần chứa chuỗi định dạng JSON hợp lệ',
    'valid_array_size_greater'     => 'Các {field} cần phải là một mảng có kích thước, bằng hoặc cao hơn {param}',
    'valid_array_size_lesser'      => 'Các {field} cần phải là một mảng có kích thước, bằng hoặc thấp hơn {param}',
    'valid_array_size_equal'       => 'Các {field} cần phải là một mảng có kích thước bằng {param}',

    // Security validators
    'strong_password'          => '{field} phải chứa ít nhất 8 ký tự với chữ hoa, chữ thường, số và ký tự đặc biệt',
    'jwt_token'                => '{field} phải là định dạng JWT token hợp lệ',
    'hash'                     => '{field} phải là hash {param} hợp lệ',
    'no_sql_injection'         => '{field} chứa các mẫu SQL injection tiềm ẩn',
    'no_xss'                   => '{field} chứa các mẫu XSS tiềm ẩn',

    // Modern web validators
    'uuid'                     => '{field} phải là UUID hợp lệ',
    'base64'                   => '{field} phải là dữ liệu được mã hóa base64 hợp lệ',
    'hex_color'                => '{field} phải là mã màu thập lục phân hợp lệ (ví dụ: #FF0000)',
    'rgb_color'                => '{field} phải là định dạng màu RGB hợp lệ (ví dụ: rgb(255,0,0))',
    'timezone'                 => '{field} phải là định danh múi giờ hợp lệ',
    'language_code'            => '{field} phải là mã ngôn ngữ hợp lệ (ví dụ: en, en-US)',
    'country_code'             => '{field} phải là mã quốc gia hợp lệ (ví dụ: US, CA)',
    'currency_code'            => '{field} phải là mã tiền tệ hợp lệ (ví dụ: USD, EUR)',

    // Network validators
    'mac_address'              => '{field} phải là định dạng địa chỉ MAC hợp lệ',
    'domain_name'              => '{field} phải là tên miền hợp lệ',
    'port_number'              => '{field} phải là số cổng hợp lệ (1-65535)',
    'social_handle'            => '{field} phải là định dạng tên người dùng mạng xã hội hợp lệ',

    // Geographic validators
    'latitude'                 => '{field} phải là vĩ độ hợp lệ (-90 đến 90)',
    'longitude'                => '{field} phải là kinh độ hợp lệ (-180 đến 180)',
    'postal_code'              => '{field} phải là mã bưu chính hợp lệ cho {param}',
    'coordinates'              => '{field} phải là tọa độ hợp lệ theo định dạng lat,lng',

    // Enhanced date/time validators
    'future_date'              => '{field} phải là ngày tương lai',
    'past_date'                => '{field} phải là ngày quá khứ',
    'business_day'             => '{field} phải rơi vào ngày làm việc (Thứ Hai-Thứ Sáu)',
    'valid_time'               => '{field} phải là định dạng thời gian hợp lệ (HH:MM hoặc HH:MM:SS)',
    'date_range'               => '{field} phải là ngày từ {param[0]} đến {param[1]}',

    // Mathematical validators
    'even'                     => '{field} phải là số chẵn',
    'odd'                      => '{field} phải là số lẻ',
    'prime'                    => '{field} phải là số nguyên tố',

    // Content validators
    'word_count'               => 'Số từ của {field} không đáp ứng yêu cầu',
    'camel_case'               => '{field} phải ở định dạng camelCase',
    'snake_case'               => '{field} phải ở định dạng snake_case',
    'url_slug'                 => '{field} phải là định dạng URL slug hợp lệ',
);
