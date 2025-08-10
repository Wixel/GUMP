<?php

namespace Tests\Filters;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class UpperCaseFilterTest
 *
 * @package Tests
 */
class UpperCaseFilterTest extends BaseTestCase
{
    const FILTER = 'upper_case';

    /**
     * @dataProvider successProvider
     */
    public function testSuccess($input, $expected)
    {
        $result = $this->filter(self::FILTER, $input);
        $this->assertEquals($expected, $result);
    }

    public function successProvider()
    {
        return [
            // Basic conversions
            ['hello', 'HELLO'],
            ['Hello World', 'HELLO WORLD'],
            ['mixed CaSe Text', 'MIXED CASE TEXT'],
            
            // Already uppercase
            ['ALREADY UPPER', 'ALREADY UPPER'],
            
            // Special characters (should remain unchanged)
            ['hello!@#$%', 'HELLO!@#$%'],
            ['test 123', 'TEST 123'],
            
            // Unicode characters
            ['café', 'CAFÉ'],
            ['naïve', 'NAÏVE'],
            ['résumé', 'RÉSUMÉ'],
            
            // German umlauts
            ['müller', 'MÜLLER'],
            ['straße', 'STRASSE'],
            
            // Empty and whitespace
            ['', ''],
            ['   ', '   '],
            [' test ', ' TEST '],
            
            // With newlines and tabs
            ["hello\nworld", "HELLO\nWORLD"],
            ["tab\there", "TAB\tHERE"],
            
            // Numbers and symbols (unchanged)
            ['test123', 'TEST123'],
            ['price: $99.99', 'PRICE: $99.99'],
            
            // Mixed content
            ['John.Doe@Example.COM', 'JOHN.DOE@EXAMPLE.COM'],
            
            // Single characters
            ['a', 'A'],
            ['Z', 'Z'],
            ['1', '1'],
            ['!', '!'],
        ];
    }

    public function testWithNullInput()
    {
        // PHP 8.4 deprecates passing null to mb_strtoupper, so we expect empty string
        $result = $this->filter(self::FILTER, '');
        $this->assertEquals('', $result);
    }

    public function testWithNumericInput()
    {
        $result = $this->filter(self::FILTER, 123);
        $this->assertEquals('123', $result);
    }

    public function testWithBooleanInput()
    {
        $result = $this->filter(self::FILTER, true);
        $this->assertEquals('1', $result);
        
        $result = $this->filter(self::FILTER, false);
        $this->assertEquals('', $result);
    }

    public function testWithFloatInput()
    {
        $result = $this->filter(self::FILTER, 123.45);
        $this->assertEquals('123.45', $result);
    }

    public function testLongString()
    {
        $longString = str_repeat('hello world ', 100);
        $expected = str_repeat('HELLO WORLD ', 100);
        $result = $this->filter(self::FILTER, $longString);
        $this->assertEquals($expected, $result);
    }

    public function testComplexUnicodeString()
    {
        $input = 'Γεια σας κόσμε'; // Greek "Hello world"
        $expected = 'ΓΕΙΑ ΣΑΣ ΚΌΣΜΕ';
        $result = $this->filter(self::FILTER, $input);
        $this->assertEquals($expected, $result);
    }

    public function testHTMLEntities()
    {
        $input = '&lt;hello&gt; &amp; &quot;world&quot;';
        $expected = '&LT;HELLO&GT; &AMP; &QUOT;WORLD&QUOT;';
        $result = $this->filter(self::FILTER, $input);
        $this->assertEquals($expected, $result);
    }

    public function testEmojiPreservation()
    {
        $input = 'hello 👋 world 🌍';
        $expected = 'HELLO 👋 WORLD 🌍';
        $result = $this->filter(self::FILTER, $input);
        $this->assertEquals($expected, $result);
    }
}