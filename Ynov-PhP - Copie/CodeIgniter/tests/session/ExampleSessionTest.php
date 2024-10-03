<?php

namespace Ynov-PhP - Copie\CodeIgniter\tests\session;

use Copie\CodeIgniter\system\Test\CIUnitTestCase;
use Copie\CodeIgniter\app\Config\Services;

/**
 * @internal
 */
final class ExampleSessionTest extends CIUnitTestCase
{
    public function testSessionSimple(): void
    {
        $session = Services::session();

        $session->set('logged_in', 123);
        $this->assertSame(123, $session->get('logged_in'));
    }
}
