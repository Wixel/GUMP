<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class XssCleanTest
 *
 * @package Tests
 */
class XssCleanTest extends BaseTestCase
{
    public function testSuccess()
    {
        $result = GUMP::xss_clean([
            'input' => '<script>alert(1); $("body").remove(); </script>'
        ]);

        $this->assertEquals([
            'input' => 'alert(1); $(&#34;body&#34;).remove(); '
        ], $result);
    }
}