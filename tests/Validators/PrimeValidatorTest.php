<?php

namespace Tests\Validators;

use Tests\BaseTestCase;
use GUMP;

class PrimeValidatorTest extends BaseTestCase
{
    public function testSuccessWhenValidPrimes()
    {
        $primes = [2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47];
        
        foreach ($primes as $prime) {
            $this->assertTrue(GUMP::is_valid([
                'number' => (string)$prime
            ], [
                'number' => 'prime'
            ]), "Failed asserting that $prime is prime");
        }
    }

    public function testErrorWhenNotPrime()
    {
        $nonPrimes = [1, 4, 6, 8, 9, 10, 12, 14, 15, 16, 18, 20, 21, 22];
        
        foreach ($nonPrimes as $nonPrime) {
            $this->assertNotSame(true, GUMP::is_valid([
                'number' => (string)$nonPrime
            ], [
                'number' => 'prime'
            ]), "Failed asserting that $nonPrime is not prime");
        }
    }

    public function testErrorWhenNegativeNumber()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'number' => '-5'
        ], [
            'number' => 'prime'
        ]));
    }

    public function testErrorWhenZero()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'number' => '0'
        ], [
            'number' => 'prime'
        ]));
    }

    public function testErrorWhenNotNumeric()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'number' => 'not-a-number'
        ], [
            'number' => 'prime'
        ]));
    }
}