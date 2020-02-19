<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use GUMP;
use ReflectionClass;

abstract class BaseTestCase extends TestCase
{
    protected $prophet;

    /**
     * @var GUMP
     */
    protected $gump;

    public function setUp(): void
    {
        $this->prophet = new Prophet;
        $this->gump = new GUMP;
    }

    protected function tearDown(): void
    {
        $this->prophet->checkPredictions();

        \Mockery::close();

        $this->resetCustomFieldsLabels();
        $this->resetCustomValidators();
        $this->resetCustomFilters();
        unset($this->gump);
    }

    public static function setPrivateField(string $class, $object, $property, $value)
    {
        $reflectionClass = new ReflectionClass($class);
        $reflectionProperty = $reflectionClass->getProperty($property);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
    }

    public static function getPrivateField($obj, $prop)
    {
        $reflection = new ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }

    public function resetCustomValidators()
    {
        self::setPrivateField(GUMP::class, $this->gump, 'validation_methods', []);
        self::setPrivateField(GUMP::class, $this->gump, 'validation_methods_errors', []);
    }

    public function resetCustomFilters()
    {
        self::setPrivateField(GUMP::class, $this->gump, 'filter_methods', []);
    }

    public function resetCustomFieldsLabels()
    {
        self::setPrivateField(GUMP::class, $this->gump, 'fields', []);
    }

    public function validate($rule, $value)
    {
        return $this->gump->validate([
            'test' => $value
        ], [
            'test' => $rule
        ]);
    }
}