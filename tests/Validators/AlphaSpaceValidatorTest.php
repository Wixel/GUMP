<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class AlphaSpaceValidatorTest
 *
 * @package Tests
 */
class AlphaSpaceValidatorTest extends BaseTestCase
{
    public function testSuccess()
    {
        $this->assertTrue($this->validate('alpha_space', ' azÀÁÂÃÄÅČÇĎÈÉÊËÌÍÎÏĹĽÑŇÒÓÔÕÖŔŘŠßŤÙÚŮÛÜÝŸŽàáâãäåčçďèéêëìíîïĺľñňðòóôõöŕřšťùúůûüýÿž'));
    }

    public function testError()
    {
        $this->assertNotTrue($this->validate('alpha_space', '1 azÀÁÂÃÄÅČÇĎÈÉÊËÌÍÎÏĹĽÑŇÒÓÔÕÖŔŘŠßŤÙÚŮÛÜÝŸŽàáâãäåčçďèéêëìíîïĺľñňðòóôõöŕřšťùúůûüýÿž'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate('alpha_space', ''));
    }
}