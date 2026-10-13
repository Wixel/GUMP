<?php

namespace Tests\Validators;

use Tests\BaseTestCase;

class RequiredWithoutValidatorTest extends BaseTestCase
{
    public function testSuccessWhenAllTrailingFieldsArePresent()
    {
        $result = $this->gump->validate(
            [
                'phone' => '0912345678',
                'mobile' => '0998765432',
            ],
            [
                'contact_email' => 'required_without,phone;mobile',
            ]
        );

        $this->assertTrue($result);
    }

    public function testErrorWhenAnyTrailingFieldIsEmptyAndFieldIsMissing()
    {
        $result = $this->gump->validate(
            [
                'phone' => '0912345678',
                'mobile' => '',
            ],
            [
                'contact_email' => 'required_without,phone;mobile',
            ]
        );

        $this->assertEquals([
            [
                'field' => 'contact_email',
                'value' => null,
                'rule' => 'required_without',
                'params' => ['phone', 'mobile'],
            ]
        ], $result);
    }

    public function testErrorWhenAllTrailingFieldsAreEmptyAndFieldIsMissing()
    {
        $result = $this->gump->validate(
            [
                'phone' => '',
                'mobile' => '',
            ],
            [
                'contact_email' => 'required_without,phone;mobile',
            ]
        );

        $this->assertEquals([
            [
                'field' => 'contact_email',
                'value' => null,
                'rule' => 'required_without',
                'params' => ['phone', 'mobile'],
            ]
        ], $result);
    }
}

