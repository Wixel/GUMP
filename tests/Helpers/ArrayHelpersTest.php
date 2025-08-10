<?php

namespace Tests\Helpers;

use GUMP\ArrayHelpers;
use Tests\BaseTestCase;
use ArrayAccess;

/**
 * Class ArrayHelpersTest
 *
 * @package Tests\Helpers
 */
class ArrayHelpersTest extends BaseTestCase
{
    /**
     * Test data_get method with simple keys
     */
    public function testDataGetWithSimpleKey()
    {
        $data = ['name' => 'John', 'age' => 30];
        
        $this->assertEquals('John', ArrayHelpers::data_get($data, 'name'));
        $this->assertEquals(30, ArrayHelpers::data_get($data, 'age'));
        $this->assertNull(ArrayHelpers::data_get($data, 'nonexistent'));
    }

    /**
     * Test data_get method with default values
     */
    public function testDataGetWithDefault()
    {
        $data = ['name' => 'John'];
        
        $this->assertEquals('Unknown', ArrayHelpers::data_get($data, 'nonexistent', 'Unknown'));
        $this->assertEquals('John', ArrayHelpers::data_get($data, 'name', 'Default'));
    }

    /**
     * Test data_get method with dot notation
     */
    public function testDataGetWithDotNotation()
    {
        $data = [
            'user' => [
                'profile' => [
                    'name' => 'John Doe',
                    'email' => 'john@example.com'
                ]
            ]
        ];
        
        $this->assertEquals('John Doe', ArrayHelpers::data_get($data, 'user.profile.name'));
        $this->assertEquals('john@example.com', ArrayHelpers::data_get($data, 'user.profile.email'));
        $this->assertNull(ArrayHelpers::data_get($data, 'user.profile.phone'));
    }

    /**
     * Test data_get method with null key (returns full array)
     */
    public function testDataGetWithNullKey()
    {
        $data = ['name' => 'John', 'age' => 30];
        
        $this->assertEquals($data, ArrayHelpers::data_get($data, null));
    }

    /**
     * Test data_get method with array key notation
     */
    public function testDataGetWithArrayKey()
    {
        $data = [
            'user' => [
                'profile' => [
                    'name' => 'John Doe'
                ]
            ]
        ];
        
        $result = ArrayHelpers::data_get($data, ['user', 'profile', 'name']);
        $this->assertEquals('John Doe', $result);
    }

    /**
     * Test data_get method with wildcard (*)
     */
    public function testDataGetWithWildcard()
    {
        $data = [
            'users' => [
                ['name' => 'John', 'age' => 30],
                ['name' => 'Jane', 'age' => 25],
                ['name' => 'Bob', 'age' => 35]
            ]
        ];
        
        $names = ArrayHelpers::data_get($data, 'users.*.name');
        $this->assertEquals(['John', 'Jane', 'Bob'], $names);
        
        $ages = ArrayHelpers::data_get($data, 'users.*.age');
        $this->assertEquals([30, 25, 35], $ages);
    }

    /**
     * Test data_get method with multiple wildcards
     */
    public function testDataGetWithMultipleWildcards()
    {
        $data = [
            'groups' => [
                [
                    'users' => [
                        ['name' => 'John'],
                        ['name' => 'Jane']
                    ]
                ],
                [
                    'users' => [
                        ['name' => 'Bob'],
                        ['name' => 'Alice']
                    ]
                ]
            ]
        ];
        
        $names = ArrayHelpers::data_get($data, 'groups.*.users.*.name');
        $this->assertEquals(['John', 'Jane', 'Bob', 'Alice'], $names);
    }

    /**
     * Test data_get method with object properties
     */
    public function testDataGetWithObjectProperties()
    {
        $obj = new \stdClass();
        $obj->name = 'John';
        $obj->profile = new \stdClass();
        $obj->profile->email = 'john@example.com';
        
        $this->assertEquals('John', ArrayHelpers::data_get($obj, 'name'));
        $this->assertEquals('john@example.com', ArrayHelpers::data_get($obj, 'profile.email'));
        $this->assertNull(ArrayHelpers::data_get($obj, 'nonexistent'));
    }

    /**
     * Test data_get method with non-array target and wildcard
     */
    public function testDataGetWithNonArrayAndWildcard()
    {
        $data = 'string';
        
        $this->assertEquals('default', ArrayHelpers::data_get($data, '*.key', 'default'));
    }

    /**
     * Test accessible method with arrays
     */
    public function testAccessibleWithArray()
    {
        $this->assertTrue(ArrayHelpers::accessible([]));
        $this->assertTrue(ArrayHelpers::accessible(['key' => 'value']));
    }

    /**
     * Test accessible method with ArrayAccess objects
     */
    public function testAccessibleWithArrayAccess()
    {
        $arrayAccess = new TestArrayAccess();
        $this->assertTrue(ArrayHelpers::accessible($arrayAccess));
    }

    /**
     * Test accessible method with non-array values
     */
    public function testAccessibleWithNonArray()
    {
        $this->assertFalse(ArrayHelpers::accessible('string'));
        $this->assertFalse(ArrayHelpers::accessible(123));
        $this->assertFalse(ArrayHelpers::accessible(null));
        $this->assertFalse(ArrayHelpers::accessible(new \stdClass()));
    }

    /**
     * Test exists method with arrays
     */
    public function testExistsWithArray()
    {
        $data = ['key1' => 'value1', 'key2' => null, 0 => 'zero'];
        
        $this->assertTrue(ArrayHelpers::exists($data, 'key1'));
        $this->assertTrue(ArrayHelpers::exists($data, 'key2')); // null values still exist
        $this->assertTrue(ArrayHelpers::exists($data, 0));
        $this->assertFalse(ArrayHelpers::exists($data, 'nonexistent'));
    }

    /**
     * Test exists method with ArrayAccess objects
     */
    public function testExistsWithArrayAccess()
    {
        $arrayAccess = new TestArrayAccess();
        $arrayAccess['key1'] = 'value1';
        
        $this->assertTrue(ArrayHelpers::exists($arrayAccess, 'key1'));
        $this->assertFalse(ArrayHelpers::exists($arrayAccess, 'nonexistent'));
    }

    /**
     * Test collapse method with simple arrays
     */
    public function testCollapseWithSimpleArrays()
    {
        $data = [
            ['a', 'b'],
            ['c', 'd'],
            ['e', 'f']
        ];
        
        $result = ArrayHelpers::collapse($data);
        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f'], $result);
    }

    /**
     * Test collapse method with mixed array types
     */
    public function testCollapseWithMixedTypes()
    {
        $data = [
            ['a', 'b'],
            'string', // non-array should be skipped
            ['c', 'd'],
            123, // non-array should be skipped
            ['e', 'f']
        ];
        
        $result = ArrayHelpers::collapse($data);
        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f'], $result);
    }

    /**
     * Test collapse method with empty arrays
     */
    public function testCollapseWithEmptyArrays()
    {
        $data = [
            [],
            ['a', 'b'],
            [],
            ['c']
        ];
        
        $result = ArrayHelpers::collapse($data);
        $this->assertEquals(['a', 'b', 'c'], $result);
    }

    /**
     * Test collapse method with associative arrays
     */
    public function testCollapseWithAssociativeArrays()
    {
        $data = [
            ['name' => 'John', 'age' => 30],
            ['city' => 'NYC', 'country' => 'USA']
        ];
        
        $result = ArrayHelpers::collapse($data);
        $expected = ['name' => 'John', 'age' => 30, 'city' => 'NYC', 'country' => 'USA'];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test collapse method with deeply nested arrays
     */
    public function testCollapseWithDeeplyNested()
    {
        $data = [
            [['nested1'], ['nested2']],
            [['nested3'], ['nested4']]
        ];
        
        $result = ArrayHelpers::collapse($data);
        $expected = [['nested1'], ['nested2'], ['nested3'], ['nested4']];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test edge case with very deep nesting
     */
    public function testDataGetWithVeryDeepNesting()
    {
        $data = [
            'level1' => [
                'level2' => [
                    'level3' => [
                        'level4' => [
                            'level5' => 'deep_value'
                        ]
                    ]
                ]
            ]
        ];
        
        $result = ArrayHelpers::data_get($data, 'level1.level2.level3.level4.level5');
        $this->assertEquals('deep_value', $result);
    }

    /**
     * Test with numeric string keys
     */
    public function testDataGetWithNumericStringKeys()
    {
        $data = [
            '0' => 'zero',
            '1' => 'one',
            '2' => [
                'nested' => 'value'
            ]
        ];
        
        $this->assertEquals('zero', ArrayHelpers::data_get($data, '0'));
        $this->assertEquals('one', ArrayHelpers::data_get($data, '1'));
        $this->assertEquals('value', ArrayHelpers::data_get($data, '2.nested'));
    }
}

/**
 * Test ArrayAccess implementation for testing
 */
class TestArrayAccess implements ArrayAccess
{
    private $data = [];

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }
}