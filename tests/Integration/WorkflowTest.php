<?php

namespace Tests\Integration;

use GUMP;
use Tests\BaseTestCase;

/**
 * Class WorkflowTest
 *
 * Integration tests for complete validation workflows
 *
 * @package Tests\Integration
 */
class WorkflowTest extends BaseTestCase
{
    /**
     * Test complete user registration workflow
     */
    public function testUserRegistrationWorkflow()
    {
        $userData = [
            'username' => 'john_doe123',
            'email' => 'john.doe@example.com',
            'password' => 'SecurePass123!',
            'password_confirm' => 'SecurePass123!',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => '28',
            'phone' => '+1-555-123-4567',
            'website' => 'https://johndoe.com',
            'bio' => '  Software developer with 5+ years experience.  ',
            'terms_accepted' => 'yes'
        ];

        $gump = new GUMP();

        // Set validation rules
        $gump->validation_rules([
            'username' => 'required|alpha_numeric_dash|min_len,3|max_len,20',
            'email' => 'required|valid_email',
            'password' => 'required|min_len,8|max_len,100',
            'password_confirm' => 'required|equalsfield,password',
            'first_name' => 'required|alpha_space|min_len,2|max_len,50',
            'last_name' => 'required|alpha_space|min_len,2|max_len,50',
            'age' => 'required|integer|min_numeric,18|max_numeric,120',
            'phone' => 'phone_number',
            'website' => 'valid_url',
            'bio' => 'max_len,500',
            'terms_accepted' => 'required|boolean'
        ]);

        // Set filter rules
        $gump->filter_rules([
            'username' => 'trim|sanitize_string',
            'email' => 'trim|sanitize_email',
            'password' => 'trim',
            'password_confirm' => 'trim',
            'first_name' => 'trim|sanitize_string',
            'last_name' => 'trim|sanitize_string',
            'phone' => 'trim',
            'website' => 'trim',
            'bio' => 'trim|sanitize_string',
            'terms_accepted' => 'boolean'
        ]);

        // Set custom error messages
        $gump->set_fields_error_messages([
            'username' => [
                'required' => 'Please choose a username',
                'alpha_numeric_dash' => 'Username can only contain letters, numbers, dashes and underscores'
            ],
            'password_confirm' => [
                'equalsfield' => 'Password confirmation must match your password'
            ],
            'terms_accepted' => [
                'required' => 'You must accept the terms and conditions'
            ]
        ]);

        $result = $gump->run($userData);

        $this->assertNotFalse($result, 'User registration should pass validation');
        $this->assertFalse($gump->errors(), 'There should be no validation errors');

        // Check that filtering was applied
        $this->assertEquals('Software developer with 5+ years experience.', $result['bio']);
        $this->assertTrue($result['terms_accepted']);
    }

    /**
     * Test e-commerce checkout workflow
     */
    public function testEcommerceCheckoutWorkflow()
    {
        $checkoutData = [
            'customer_email' => 'customer@example.com',
            'billing_address' => [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'address_line1' => '123 Main Street',
                'address_line2' => 'Apt 4B',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'US'
            ],
            'shipping_same_as_billing' => 'no',
            'shipping_address' => [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'address_line1' => '456 Oak Avenue',
                'city' => 'Brooklyn',
                'state' => 'NY',
                'postal_code' => '11201',
                'country' => 'US'
            ],
            'payment_method' => 'credit_card',
            'credit_card_number' => '4111111111111111',
            'items' => [
                ['product_id' => 'PROD001', 'quantity' => 2, 'price' => 29.99],
                ['product_id' => 'PROD002', 'quantity' => 1, 'price' => 49.99]
            ],
            'total_amount' => '109.97'
        ];

        $validationRules = [
            'customer_email' => 'required|valid_email',
            'billing_address.first_name' => 'required|alpha_space|min_len,2',
            'billing_address.last_name' => 'required|alpha_space|min_len,2',
            'billing_address.address_line1' => 'required|street_address',
            'billing_address.city' => 'required|alpha_space',
            'billing_address.state' => 'required|exact_len,2|alpha',
            'billing_address.postal_code' => 'required|regex,/^[0-9]{5}(-[0-9]{4})?$/',
            'billing_address.country' => 'required|exact_len,2|alpha',
            'shipping_same_as_billing' => 'required|boolean',
            'shipping_address.first_name' => 'required|alpha_space|min_len,2',
            'shipping_address.last_name' => 'required|alpha_space|min_len,2',
            'shipping_address.address_line1' => 'required|street_address',
            'shipping_address.city' => 'required|alpha_space',
            'shipping_address.state' => 'required|exact_len,2|alpha',
            'shipping_address.postal_code' => 'required|regex,/^[0-9]{5}(-[0-9]{4})?$/',
            'shipping_address.country' => 'required|exact_len,2|alpha',
            'payment_method' => 'required|contains,credit_card;paypal;bank_transfer',
            'credit_card_number' => 'required|valid_cc',
            'items.*.product_id' => 'required|alpha_numeric_dash',
            'items.*.quantity' => 'required|integer|min_numeric,1',
            'items.*.price' => 'required|float|min_numeric,0',
            'total_amount' => 'required|float|min_numeric,0'
        ];

        $result = GUMP::is_valid($checkoutData, $validationRules);

        $this->assertTrue($result, 'E-commerce checkout should pass validation');
    }

    /**
     * Test file upload with metadata validation
     */
    public function testFileUploadWithMetadata()
    {
        // Simulate file upload data
        $uploadData = [
            'title' => 'My Document',
            'description' => 'Important business document with sensitive information.',
            'category' => 'business',
            'tags' => ['important', 'business', 'confidential'],
            'uploaded_file' => [
                'name' => 'document.pdf',
                'type' => 'application/pdf',
                'size' => 1048576, // 1MB
                'tmp_name' => '/tmp/php123abc',
                'error' => 0
            ]
        ];

        $validationRules = [
            'title' => 'required|min_len,3|max_len,100',
            'description' => 'required|min_len,10|max_len,1000',
            'category' => 'required|contains,business;personal;academic;other',
            'tags' => 'required|valid_array_size_greater,0|valid_array_size_lesser,11',
            'uploaded_file' => 'required_file|extension,pdf;doc;docx'
        ];

        $filterRules = [
            'title' => 'trim|sanitize_string',
            'description' => 'trim|sanitize_string',
            'category' => 'trim|lower_case',
            'tags' => 'sanitize_string'
        ];

        $gump = new GUMP();
        $gump->validation_rules($validationRules);
        $gump->filter_rules($filterRules);

        $result = $gump->run($uploadData);

        $this->assertNotFalse($result, 'File upload with metadata should pass validation');
        $this->assertEquals('business', $result['category']);
        $this->assertIsArray($result['tags']);
    }

    /**
     * Test API payload validation workflow
     */
    public function testApiPayloadValidation()
    {
        $apiPayload = [
            'action' => 'create_user',
            'version' => '1.0',
            'timestamp' => '2024-01-15T10:30:00Z',
            'data' => [
                'user' => [
                    'id' => 'USR12345',
                    'email' => 'newuser@example.com',
                    'profile' => [
                        'name' => 'Alice Johnson',
                        'age' => 25,
                        'preferences' => [
                            'theme' => 'dark',
                            'notifications' => true,
                            'language' => 'en'
                        ]
                    ]
                ],
                'metadata' => [
                    'source' => 'web_app',
                    'client_version' => '2.1.0'
                ]
            ]
        ];

        $validationRules = [
            'action' => 'required|contains,create_user;update_user;delete_user',
            'version' => 'required|regex,/^\d+\.\d+$/',
            'timestamp' => 'required|date,c', // ISO 8601 format
            'data.user.id' => 'required|alpha_numeric_dash',
            'data.user.email' => 'required|valid_email',
            'data.user.profile.name' => 'required|valid_name',
            'data.user.profile.age' => 'required|integer|min_numeric,18|max_numeric,120',
            'data.user.profile.preferences.theme' => 'required|contains,light;dark;auto',
            'data.user.profile.preferences.notifications' => 'required|boolean,strict',
            'data.user.profile.preferences.language' => 'required|exact_len,2|alpha',
            'data.metadata.source' => 'required|alpha_dash',
            'data.metadata.client_version' => 'required|regex,/^\d+\.\d+\.\d+$/'
        ];

        $result = GUMP::is_valid($apiPayload, $validationRules);

        $this->assertTrue($result, 'API payload should pass validation');
    }

    /**
     * Test multi-step form validation (wizard-style)
     */
    public function testMultiStepFormValidation()
    {
        // Step 1: Basic Information
        $step1Data = [
            'step' => '1',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '555-123-4567'
        ];

        $step1Rules = [
            'step' => 'required|exact_len,1|numeric',
            'first_name' => 'required|alpha_space',
            'last_name' => 'required|alpha_space',
            'email' => 'required|valid_email',
            'phone' => 'phone_number'
        ];

        $step1Result = GUMP::is_valid($step1Data, $step1Rules);
        $this->assertTrue($step1Result, 'Step 1 should pass validation');

        // Step 2: Address Information
        $step2Data = array_merge($step1Data, [
            'step' => '2',
            'address' => '123 Main St',
            'city' => 'Anytown',
            'state' => 'CA',
            'zip' => '90210',
            'country' => 'US'
        ]);

        $step2Rules = array_merge($step1Rules, [
            'step' => 'required|exact_len,1|numeric',
            'address' => 'required|street_address',
            'city' => 'required|alpha_space',
            'state' => 'required|exact_len,2|alpha',
            'zip' => 'required|regex,/^[0-9]{5}(-[0-9]{4})?$/',
            'country' => 'required|exact_len,2|alpha'
        ]);

        $step2Result = GUMP::is_valid($step2Data, $step2Rules);
        $this->assertTrue($step2Result, 'Step 2 should pass validation');

        // Step 3: Final preferences
        $step3Data = array_merge($step2Data, [
            'step' => '3',
            'newsletter' => 'yes',
            'terms' => 'on',
            'marketing_emails' => 'no'
        ]);

        $step3Rules = array_merge($step2Rules, [
            'step' => 'required|exact_len,1|numeric',
            'newsletter' => 'required|boolean',
            'terms' => 'required|boolean',
            'marketing_emails' => 'required|boolean'
        ]);

        $step3Result = GUMP::is_valid($step3Data, $step3Rules);
        $this->assertTrue($step3Result, 'Step 3 should pass validation');
    }

    /**
     * Test complex nested validation with error handling
     */
    public function testComplexNestedValidationWithErrors()
    {
        $complexData = [
            'company' => [
                'name' => '', // This will fail - required field empty
                'employees' => [
                    ['name' => 'John Doe', 'email' => 'john@example.com', 'age' => 30],
                    ['name' => 'Jane Smith', 'email' => 'invalid-email', 'age' => 17], // Invalid email and age
                    ['name' => 'Bob Johnson', 'email' => 'bob@example.com', 'age' => 45]
                ],
                'departments' => [
                    'engineering' => ['budget' => 100000, 'head' => 'Alice Brown'],
                    'marketing' => ['budget' => 50000, 'head' => ''] // Empty head name
                ]
            ]
        ];

        $validationRules = [
            'company.name' => 'required|min_len,3',
            'company.employees.*.name' => 'required|valid_name',
            'company.employees.*.email' => 'required|valid_email',
            'company.employees.*.age' => 'required|integer|min_numeric,18',
            'company.departments.engineering.budget' => 'required|integer|min_numeric,0',
            'company.departments.engineering.head' => 'required|valid_name',
            'company.departments.marketing.budget' => 'required|integer|min_numeric,0',
            'company.departments.marketing.head' => 'required|valid_name'
        ];

        $gump = new GUMP();
        $result = $gump->validate($complexData, $validationRules);

        $this->assertNotTrue($result, 'Complex data with errors should fail validation');
        $this->assertTrue($gump->errors(), 'There should be validation errors');

        $errors = $gump->get_errors_array();
        $this->assertNotEmpty($errors, 'Error array should not be empty');

        // Check that specific errors are caught
        $errorFields = array_keys($errors);
        $this->assertContains('company.name', $errorFields, 'Company name error should be present');
    }

    /**
     * Test performance with large dataset
     */
    public function testPerformanceWithLargeDataset()
    {
        // Create a large dataset for performance testing
        $largeDataset = [];
        for ($i = 0; $i < 1000; $i++) {
            $largeDataset["item_$i"] = [
                'name' => "Item $i",
                'price' => rand(10, 1000) / 10,
                'category' => 'category_' . ($i % 5),
                'active' => $i % 2 === 0
            ];
        }

        $validationRules = [];
        for ($i = 0; $i < 1000; $i++) {
            $validationRules["item_$i.name"] = 'required|min_len,3';
            $validationRules["item_$i.price"] = 'required|float|min_numeric,0';
            $validationRules["item_$i.category"] = 'required|alpha_dash';
            $validationRules["item_$i.active"] = 'required|boolean';
        }

        $startTime = microtime(true);
        $result = GUMP::is_valid($largeDataset, $validationRules);
        $endTime = microtime(true);

        $executionTime = $endTime - $startTime;

        $this->assertTrue($result, 'Large dataset should pass validation');
        $this->assertLessThan(5.0, $executionTime, 'Validation should complete in under 5 seconds');
    }
}