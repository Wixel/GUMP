# GUMP - A Fast PHP Data Validation & Filtering Library

[![Total Downloads](https://poser.pugx.org/wixel/gump/downloads)](https://packagist.org/packages/wixel/gump)
[![Latest Stable Version](https://poser.pugx.org/wixel/gump/v/stable)](https://packagist.org/packages/wixel/gump)
![Build Status](https://github.com/wixel/gump/actions/workflows/ci.yml/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/Wixel/GUMP/badge.svg?branch=master)](https://coveralls.io/github/Wixel/GUMP?branch=master)
[![License](https://poser.pugx.org/wixel/gump/license)](https://packagist.org/packages/wixel/gump)

## 🚀 Overview

GUMP is a standalone PHP data validation and filtering library that makes validating any data easy and painless without the reliance on a framework. GUMP has been serving the PHP community since **2013** and is trusted by thousands of developers worldwide.

### ✨ Key Features

- **🔒 Zero Dependencies** - Pure PHP, no external dependencies required
- **🌍 19 Languages** - Built-in internationalization support
- **⚡ High Performance** - Lightweight and fast validation processing
- **🔧 Extensible** - Easy to add custom validators and filters
- **📋 40+ Validators** - Comprehensive set of validation rules out of the box
- **🛡️ Security Focused** - Built-in XSS protection and data sanitization
- **🎯 Framework Agnostic** - Works with any PHP project or framework
- **📱 Modern PHP** - Supports PHP 7.1 to 8.4+

## 📋 Table of Contents

- [Installation](#-installation)
- [Requirements](#-requirements)
- [Quick Start](#-quick-start)
- [Usage Examples](#-usage-examples)
- [Available Validators](#-available-validators)
- [Available Filters](#-available-filters)
- [Advanced Usage](#-advanced-usage)
- [Internationalization](#-internationalization)
- [Custom Validators & Filters](#-custom-validators--filters)
- [Configuration](#-configuration)
- [Testing](#-testing)
- [Contributing](#-contributing)
- [Security](#-security)
- [Changelog](#-changelog)
- [Support](#-support)
- [License](#-license)

## 📦 Installation

### Via Composer (Recommended)

```bash
composer require wixel/gump
```

### Manual Installation

1. Download the latest release from [GitHub Releases](https://github.com/wixel/gump/releases)
2. Extract and include `gump.class.php` in your project:

```php
require_once 'path/to/gump.class.php';
```

## 🔧 Requirements

- **PHP**: 7.1, 7.2, 7.3, 7.4, 8.0, 8.1, 8.2, 8.3, 8.4+
- **Extensions**:
  - `ext-mbstring` - Multibyte string support
  - `ext-json` - JSON processing
  - `ext-intl` - Internationalization functions
  - `ext-bcmath` - Arbitrary precision mathematics
  - `ext-iconv` - Character encoding conversion

## 🚀 Quick Start

### Simple Validation

```php
<?php
require_once 'vendor/autoload.php';

$is_valid = GUMP::is_valid([
    'username' => 'johndoe',
    'email'    => 'john@example.com',
    'age'      => '25'
], [
    'username' => 'required|alpha_numeric|min_len,3',
    'email'    => 'required|valid_email',
    'age'      => 'required|integer|min_numeric,18'
]);

if ($is_valid === true) {
    echo "✅ All data is valid!";
} else {
    // Display validation errors
    foreach ($is_valid as $error) {
        echo "❌ " . $error . "\n";
    }
}
```

### Simple Filtering

```php
$filtered = GUMP::filter_input([
    'username' => ' JohnDoe123 ',
    'bio'      => '<script>alert("xss")</script>Clean bio text'
], [
    'username' => 'trim|lower_case',
    'bio'      => 'trim|sanitize_string'
]);

// Result:
// $filtered['username'] = 'johndoe123'
// $filtered['bio'] = 'Clean bio text'
```

## 📚 Usage Examples

### Basic Validation with Custom Error Messages

```php
$gump = new GUMP();

// Set validation rules
$gump->validation_rules([
    'username'    => 'required|alpha_numeric|max_len,100|min_len,6',
    'password'    => 'required|max_len,100|min_len,8',
    'email'       => 'required|valid_email',
    'phone'       => 'required|phone_number',
    'website'     => 'valid_url',
    'birthday'    => 'required|date,Y-m-d|min_age,18'
]);

// Set custom error messages
$gump->set_fields_error_messages([
    'username' => [
        'required'  => 'Please enter a username',
        'min_len'   => 'Username must be at least 6 characters'
    ],
    'email' => [
        'required'    => 'Email address is required',
        'valid_email' => 'Please enter a valid email address'
    ]
]);

// Set filtering rules
$gump->filter_rules([
    'username' => 'trim|sanitize_string',
    'email'    => 'trim|sanitize_email',
    'phone'    => 'trim',
    'website'  => 'trim'
]);

$validated_data = $gump->run($_POST);

if ($gump->errors()) {
    // Handle validation errors
    $errors = $gump->get_readable_errors();
    foreach ($errors as $error) {
        echo "<div class='error'>{$error}</div>";
    }
} else {
    // Process validated and filtered data
    echo "User registered successfully!";
    var_dump($validated_data);
}
```

### File Upload Validation

```php
$is_valid = GUMP::is_valid(array_merge($_POST, $_FILES), [
    'profile_photo' => 'required_file|extension,jpg;jpeg;png;gif',
    'document'      => 'required_file|extension,pdf;doc;docx',
    'username'      => 'required|alpha_numeric'
]);

if ($is_valid !== true) {
    foreach ($is_valid as $error) {
        echo "Upload Error: {$error}\n";
    }
}
```

### Array and Nested Field Validation

```php
$data = [
    'user' => [
        'name'  => 'John Doe',
        'email' => 'john@example.com'
    ],
    'products' => [
        ['name' => 'Product 1', 'price' => 19.99],
        ['name' => 'Product 2', 'price' => 29.99]
    ],
    'tags' => ['php', 'validation', 'security']
];

$is_valid = GUMP::is_valid($data, [
    'user.name'        => 'required|valid_name',
    'user.email'       => 'required|valid_email',
    'products.*.name'  => 'required|min_len,3',
    'products.*.price' => 'required|float|min_numeric,0',
    'tags'             => 'required|valid_array_size_greater,0'
]);
```

## ⚡ Available Validators

GUMP provides 40+ built-in validators for comprehensive data validation:

### 🔤 String Validators
| Validator | Description | Example |
|-----------|-------------|---------|
| `required` | Field must exist and not be empty | `'username' => 'required'` |
| `alpha` | Only alphabetic characters | `'name' => 'alpha'` |
| `alpha_numeric` | Alphanumeric characters only | `'username' => 'alpha_numeric'` |
| `alpha_dash` | Alpha chars with dashes/underscores | `'slug' => 'alpha_dash'` |
| `alpha_numeric_dash` | Alphanumeric with dashes/underscores | `'identifier' => 'alpha_numeric_dash'` |
| `alpha_space` | Alpha chars with spaces | `'full_name' => 'alpha_space'` |
| `alpha_numeric_space` | Alphanumeric with spaces | `'address' => 'alpha_numeric_space'` |

### 📏 Length Validators
| Validator | Description | Example |
|-----------|-------------|---------|
| `min_len,6` | Minimum length requirement | `'password' => 'min_len,8'` |
| `max_len,100` | Maximum length limit | `'title' => 'max_len,100'` |
| `exact_len,10` | Exact length requirement | `'phone' => 'exact_len,10'` |
| `between_len,6;20` | Length between min and max | `'username' => 'between_len,3;20'` |

### 🔢 Numeric Validators
| Validator | Description | Example |
|-----------|-------------|---------|
| `numeric` | Valid number or numeric string | `'price' => 'numeric'` |
| `integer` | Valid integer | `'age' => 'integer'` |
| `float` | Valid float/decimal number | `'rating' => 'float'` |
| `min_numeric,18` | Minimum numeric value | `'age' => 'min_numeric,18'` |
| `max_numeric,100` | Maximum numeric value | `'percentage' => 'max_numeric,100'` |

### 🌐 Format Validators
| Validator | Description | Example |
|-----------|-------------|---------|
| `valid_email` | Valid email format | `'email' => 'valid_email'` |
| `valid_url` | Valid URL format | `'website' => 'valid_url'` |
| `url_exists` | URL exists and is accessible | `'link' => 'url_exists'` |
| `valid_ip` | Valid IP address (v4 or v6) | `'ip' => 'valid_ip'` |
| `valid_ipv4` | Valid IPv4 address | `'server_ip' => 'valid_ipv4'` |
| `valid_ipv6` | Valid IPv6 address | `'ipv6' => 'valid_ipv6'` |
| `phone_number` | Valid phone number | `'phone' => 'phone_number'` |

### 📅 Date & Time Validators
| Validator | Description | Example |
|-----------|-------------|---------|
| `date` | Valid date (ISO 8601) | `'birthday' => 'date'` |
| `date,d/m/Y` | Date with custom format | `'date' => 'date,Y-m-d'` |
| `min_age,18` | Minimum age requirement | `'dob' => 'min_age,21'` |

### 💳 Specialized Validators
| Validator | Description | Example |
|-----------|-------------|---------|
| `valid_cc` | Valid credit card number | `'card' => 'valid_cc'` |
| `iban` | Valid IBAN | `'account' => 'iban'` |
| `guidv4` | Valid GUID v4 format | `'uuid' => 'guidv4'` |
| `valid_json_string` | Valid JSON string | `'config' => 'valid_json_string'` |

### 📂 File Validators
| Validator | Description | Example |
|-----------|-------------|---------|
| `required_file` | File must be uploaded | `'avatar' => 'required_file'` |
| `extension,jpg;png` | Allowed file extensions | `'image' => 'extension,jpg;png;gif'` |

### 🔍 Comparison Validators
| Validator | Description | Example |
|-----------|-------------|---------|
| `contains,value1;value2` | Value in allowed list | `'status' => 'contains,active;pending'` |
| `contains_list,a;b;c` | Value in list (private error) | `'type' => 'contains_list,user;admin'` |
| `doesnt_contain_list,x;y` | Value not in forbidden list | `'username' => 'doesnt_contain_list,admin;root'` |
| `equalsfield,other_field` | Must equal another field | `'password_confirm' => 'equalsfield,password'` |
| `starts,prefix` | Must start with string | `'code' => 'starts,PRE'` |

### 🗂️ Array Validators
| Validator | Description | Example |
|-----------|-------------|---------|
| `valid_array_size_greater,1` | Array size greater than N | `'items' => 'valid_array_size_greater,0'` |
| `valid_array_size_lesser,10` | Array size less than N | `'tags' => 'valid_array_size_lesser,5'` |
| `valid_array_size_equal,3` | Array size equals N | `'coordinates' => 'valid_array_size_equal,2'` |

### ⚙️ Other Validators
| Validator | Description | Example |
|-----------|-------------|---------|
| `boolean` | Valid boolean value | `'active' => 'boolean'` |
| `boolean,strict` | Strict boolean (true/false only) | `'enabled' => 'boolean,strict'` |
| `regex,/pattern/` | Custom regex validation | `'code' => 'regex,/^[A-Z]{2}[0-9]{4}$/'` |
| `valid_name` | Valid human name | `'full_name' => 'valid_name'` |
| `street_address` | Likely street address | `'address' => 'street_address'` |

> **💡 Pro Tip**: When using pipe (`|`) or semicolon (`;`) in validator parameters, use array format:
> ```php
> // ❌ Wrong - will break parsing
> 'field' => 'regex,/part|of;pattern/'
> 
> // ✅ Correct - use array format
> 'field' => ['regex' => '/part|of;pattern/']
> ```

## 🔧 Available Filters

GUMP includes 15+ filters for data sanitization and transformation:

### 🧹 Sanitization Filters
| Filter | Description | Example |
|--------|-------------|---------|
| `trim` | Remove whitespace from ends | `'name' => 'trim'` |
| `sanitize_email` | Remove illegal email chars | `'email' => 'sanitize_email'` |
| `sanitize_numbers` | Keep only numbers | `'phone' => 'sanitize_numbers'` |
| `sanitize_floats` | Keep numbers and decimal | `'price' => 'sanitize_floats'` |
| `sanitize_string` | Remove script tags & XSS | `'content' => 'sanitize_string'` |
| `htmlencode` | Encode HTML entities | `'description' => 'htmlencode'` |
| `urlencode` | URL encode string | `'query' => 'urlencode'` |

### ✏️ Text Transformation Filters
| Filter | Description | Example |
|--------|-------------|---------|
| `lower_case` | Convert to lowercase | `'username' => 'lower_case'` |
| `upper_case` | Convert to uppercase | `'code' => 'upper_case'` |
| `slug` | Convert to URL-friendly slug | `'title' => 'slug'` |
| `boolean` | Convert to boolean | `'active' => 'boolean'` |
| `whole_number` | Convert to whole number | `'quantity' => 'whole_number'` |

### 🎨 Content Filters
| Filter | Description | Example |
|--------|-------------|---------|
| `noise_words` | Remove common noise words | `'content' => 'noise_words'` |
| `rmpunctuation` | Remove all punctuation | `'text' => 'rmpunctuation'` |
| `basic_tags` | Keep only basic HTML tags | `'content' => 'basic_tags'` |
| `ms_word_characters` | Convert MS Word special chars | `'text' => 'ms_word_characters'` |

### Filter Chaining Example

```php
$filtered = GUMP::filter_input([
    'title'       => '  My Amazing Blog Post!!!  ',
    'description' => '<script>alert("xss")</script>This is a description with <b>bold</b> text.',
    'price'       => '$19.99 USD',
    'active'      => 'yes'
], [
    'title'       => 'trim|ms_word_characters|slug',
    'description' => 'trim|sanitize_string',
    'price'       => 'sanitize_floats',
    'active'      => 'boolean'
]);

// Results:
// $filtered['title'] = 'my-amazing-blog-post'
// $filtered['description'] = 'This is a description with bold text.'
// $filtered['price'] = '19.99'
// $filtered['active'] = true
```

## 🏗️ Advanced Usage

### Instance Methods

```php
$gump = new GUMP('en'); // Set language

// Validate data without filtering
$validation_result = $gump->validate($_POST, [
    'email' => 'required|valid_email'
]);

// Filter data without validation
$filtered_data = $gump->filter($_POST, [
    'content' => 'trim|sanitize_string'
]);

// Sanitize data (UTF-8 conversion)
$sanitized = $gump->sanitize($_POST, ['allowed_field1', 'allowed_field2']);

// Get detailed error information
if ($gump->errors()) {
    $readable_errors = $gump->get_readable_errors(); // HTML formatted
    $simple_errors = $gump->get_errors_array();      // Field => message array
}
```

### Field Name Customization

```php
// Set friendly field names for error messages
GUMP::set_field_name('usr_nm', 'Username');
GUMP::set_field_names([
    'usr_nm' => 'Username',
    'pwd'    => 'Password',
    'em'     => 'Email Address'
]);

// Now validation errors will show friendly names
$is_valid = GUMP::is_valid(['usr_nm' => ''], ['usr_nm' => 'required']);
// Error: "Username is required" (instead of "Usr nm is required")
```

### Global Error Message Customization

```php
// Set custom error messages for validators
GUMP::set_error_message('required', 'The {field} field cannot be empty');
GUMP::set_error_message('valid_email', 'Please provide a valid email for {field}');

// Set multiple custom messages
GUMP::set_error_messages([
    'required'      => 'Please fill out the {field} field',
    'min_len'       => '{field} must be at least {param} characters long',
    'valid_email'   => 'The email address in {field} is not valid'
]);
```

## 🌍 Internationalization

GUMP supports 19 languages out of the box:

**Supported Languages**: German (de), Greek (el), English (en), Esperanto (eo), Spanish (es), Persian (fa), French (fr), Hebrew (he), Hungarian (hu), Indonesian (id), Italian (it), Japanese (ja), Dutch (nl), Portuguese Brazil (pt-br), Russian (ru), Turkish (tr), Ukrainian (uk), Vietnamese (vi), Chinese Simplified (zh-CN)

```php
// Set language during instantiation
$gump = new GUMP('es'); // Spanish
$gump = new GUMP('fr'); // French
$gump = new GUMP('de'); // German

// Validation errors will now be in the selected language
$result = $gump->validate(['email' => 'invalid'], ['email' => 'valid_email']);
```

## 🛠️ Custom Validators & Filters

### Adding Custom Validators

```php
// Add a custom validator with callback
GUMP::add_validator('strong_password', function($field, array $input, array $params, $value) {
    // Must contain at least 1 uppercase, 1 lowercase, 1 number, and 1 special char
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', $value);
}, 'The {field} must be a strong password with uppercase, lowercase, number and special character.');

// Usage
$is_valid = GUMP::is_valid(['password' => 'weak'], ['password' => 'strong_password']);

// Check if validator exists
if (GUMP::has_validator('strong_password')) {
    echo "Custom validator is available!";
}
```

### Adding Custom Filters

```php
// Add a custom filter
GUMP::add_filter('mask_email', function($value, array $params = []) {
    $parts = explode('@', $value);
    if (count($parts) === 2) {
        $username = substr($parts[0], 0, 2) . str_repeat('*', strlen($parts[0]) - 2);
        return $username . '@' . $parts[1];
    }
    return $value;
});

// Usage
$filtered = GUMP::filter_input(['email' => 'john@example.com'], ['email' => 'mask_email']);
// Result: 'jo***@example.com'

// Check if filter exists
if (GUMP::has_filter('mask_email')) {
    echo "Custom filter is available!";
}
```

### Extending GUMP Class

```php
class CustomGUMP extends GUMP
{
    // Custom validator method (prefix with 'validate_')
    protected function validate_is_even($field, array $input, array $params = [], $value = null)
    {
        return is_numeric($value) && ($value % 2 == 0);
    }
    
    // Custom filter method (prefix with 'filter_')
    protected function filter_add_prefix($value, array $params = [])
    {
        $prefix = isset($params[0]) ? $params[0] : 'PREFIX_';
        return $prefix . $value;
    }
}

$custom_gump = new CustomGUMP();

// Use custom validator
$result = $custom_gump->validate(['number' => 5], ['number' => 'is_even']);

// Use custom filter  
$filtered = $custom_gump->filter(['name' => 'John'], ['name' => 'add_prefix,MR_']);
// Result: 'MR_John'
```

## ⚙️ Configuration

### Global Delimiter Configuration

Customize the delimiters used in validation rule strings:

```php
// Default configuration
GUMP::$rules_delimiter = '|';                    // Separates rules: 'required|email'
GUMP::$rules_parameters_delimiter = ',';         // Separates parameters: 'min_len,6'
GUMP::$rules_parameters_arrays_delimiter = ';'; // Separates array items: 'contains,a;b;c'

// Custom configuration example
GUMP::$rules_delimiter = '&';                    // 'required&email'
GUMP::$rules_parameters_delimiter = ':';         // 'min_len:6'
GUMP::$rules_parameters_arrays_delimiter = '|'; // 'contains:a|b|c'
```

### Field Character Replacement

```php
// Characters that will be replaced with spaces in field names for error messages
GUMP::$field_chars_to_spaces = ['_', '-', '.'];

// 'user_name' becomes 'User Name' in error messages
// 'first-name' becomes 'First Name' in error messages
```

## 🧪 Testing

GUMP includes comprehensive test coverage with PHPUnit:

```bash
# Install development dependencies
composer install --dev

# Run all tests
composer test

# Run tests with coverage
./vendor/bin/phpunit --coverage-html coverage

# Check documentation consistency
composer check

# Dump documentation (for contributors)
composer dump
```

### Running Tests in Docker

```bash
# Build and run tests in Docker
cd dev/
./build.sh
./run_tests_docker.sh
```

## 🤝 Contributing

We welcome contributions! Please read our [Contributing Guidelines](CONTRIBUTING.md) before submitting PRs.

### Development Setup

1. Fork the repository
2. Clone your fork: `git clone https://github.com/yourusername/gump.git`
3. Install dependencies: `composer install`
4. Create a feature branch: `git checkout -b feature/amazing-feature`
5. Make your changes and add tests
6. Run tests: `composer test`
7. Submit a pull request

### Contribution Guidelines

- **Add tests** for new features and bug fixes
- **Follow PSR-12** coding standards
- **Update documentation** for new validators/filters
- **Add translations** for new error messages
- **Maintain backward compatibility**

### Areas We Need Help With

- 🌍 **Translations** - Help us support more languages
- 🧪 **Test Coverage** - Add more edge case tests
- 📚 **Documentation** - Improve examples and guides
- 🚀 **Performance** - Optimize validation algorithms
- 🛡️ **Security** - Security audits and improvements

## 🛡️ Security

### Reporting Security Vulnerabilities

If you discover a security vulnerability, please send an email to security@wixel.net instead of creating a public issue.

### Security Best Practices

- Always validate AND filter user input
- Use appropriate validators for your data types
- Be cautious with `regex` validator - avoid ReDoS attacks
- Use `sanitize_string` filter to prevent XSS
- Validate file uploads thoroughly
- Keep GUMP updated to the latest version

### Security Features

- **XSS Protection**: Built-in `sanitize_string` filter
- **SQL Injection Prevention**: Proper data validation
- **File Upload Security**: Extension and type validation
- **Input Sanitization**: Multiple sanitization filters
- **Safe Defaults**: Secure by default configuration

## 📝 Changelog

### Version 1.15+ (Current)
- ✅ **Security Fix**: Fixed string sanitization vulnerability
- ✅ **Compatibility**: PHP 8.4 support
- ✅ **Improvement**: Enhanced error message handling
- ✅ **New**: Additional validation rules

### Previous Versions
See [CHANGELOG.md](CHANGELOG.md) for complete version history.

## 💬 Support

### Community Support

- 🐛 **Bug Reports**: [GitHub Issues](https://github.com/wixel/gump/issues)
- 💡 **Feature Requests**: [GitHub Discussions](https://github.com/wixel/gump/discussions)
- 📚 **Documentation**: [GitHub Wiki](https://github.com/wixel/gump/wiki)
- 💬 **Community Chat**: [Discord Server](https://discord.gg/wixel)

### Professional Support

For commercial support, consulting, or custom development:
- 🏢 **Email**: support@wixel.net
- 🌐 **Website**: [wixelhq.com](https://wixelhq.com)

## 📊 Statistics

- ⭐ **GitHub Stars**: 800+
- 📦 **Downloads**: 2M+ via Packagist  
- 🏭 **Production Use**: Thousands of projects
- 🌍 **Languages**: 19 supported languages
- ⚡ **Performance**: <1ms validation time for typical forms
- 🧪 **Test Coverage**: 95%+

## 🏆 Why Choose GUMP?

### ✅ Battle-Tested
- **10+ years** in production
- **Trusted** by thousands of developers
- **Proven** in high-traffic applications

### ⚡ Performance First
- **Zero dependencies** - no bloat
- **Optimized algorithms** - fast validation
- **Memory efficient** - low resource usage

### 🔒 Security Focused
- **XSS protection** built-in
- **Regular security audits**
- **Secure defaults** everywhere

### 🌍 Global Ready
- **19 languages** supported
- **UTF-8 compatible**
- **Timezone aware** date validation

### 🛠️ Developer Friendly
- **Clean, simple API**
- **Excellent documentation**
- **Extensive examples**
- **Framework agnostic**

## 📄 License

GUMP is open-source software licensed under the [MIT License](LICENSE).

```
MIT License

Copyright (c) 2013-2025 Sean Nieuwoudt.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

<div align="center">
[⭐ Star us on GitHub](https://github.com/wixel/gump) • [📦 View on Packagist](https://packagist.org/packages/wixel/gump) • [🐛 Report Issues](https://github.com/wixel/gump/issues)
</div>