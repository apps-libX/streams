<?php

namespace RAD\Streams\Tests;

class LoginTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->install();
    }

    public function testSuccessfulLoginWithDefaultCredentials()
    {
        $this->visit(route('streams.login'));
        $this->type('admin@admin.com', 'email');
        $this->type('password', 'password');
        $this->press('Login Logging in');
        $this->seePageIs(route('streams.dashboard'));
    }

    public function testShowAnErrorMessageWhenITryToLoginWithWrongCredentials()
    {
        $this->visit(route('streams.login'))
             ->type('john@Doe.com', 'email')
             ->type('pass', 'password')
             ->press('Login Logging in')
             ->seePageIs(route('streams.login'))
             ->see('The given credentials don\'t match with an user registered.')
             ->seeInField('email', 'john@Doe.com');
    }
}
