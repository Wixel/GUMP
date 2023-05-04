<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class AlphaValidatorTest
 *
 * @package Tests
 */
class AlphaValidatorTest extends BaseTestCase
{
    public function testSuccess()
    {
        $this->assertTrue($this->validate('alpha', 'ÀÁÂÃÄÅČÇĎÈÉÊËÌÍÎÏĹĽÑŇÒÓÔÕÖŔŘŠßŤÙÚŮÛÜÝŸŽàáâãäåčçďèéêëìíîïĺľñňðòóôõöŕřšťùúůûüýÿž'));
    }

    public function testError()
    {
        $this->assertNotTrue($this->validate('alpha', 'ÀÁÂÃÄÅČÇĎÈÉÊËÌÍÎÏĹĽÑŇÒÓÔÕÖŔŘŠßŤÙÚŮÛÜÝŸŽàáâãäåčçďèéêëìíîïĺľñňðòóôõöŕřšťùúůûüýÿž'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate('alpha', ''));
    }
}