<?php

namespace RAD\Streams\Tests;

class RouteTest extends TestCase
{
    protected $withDummy = true;

    public function setUp()
    {
        parent::setUp();

        $this->install();
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testGetRoutes()
    {
        $this->visit(route('streams.login'));
        $this->type('admin@admin.com', 'email');
        $this->type('password', 'password');
        $this->press('Login Logging in');

        $urls = [
            route('streams.dashboard'),
            route('streams.media.index'),
            route('streams.settings.index'),
            route('streams.roles.index'),
            route('streams.roles.create'),
            route('streams.roles.show', ['role' => 1]),
            route('streams.roles.edit', ['role' => 1]),
            route('streams.users.index'),
            route('streams.users.create'),
            route('streams.users.show', ['user' => 1]),
            route('streams.users.edit', ['user' => 1]),
            route('streams.posts.index'),
            route('streams.posts.create'),
            route('streams.posts.show', ['post' => 1]),
            route('streams.posts.edit', ['post' => 1]),
            route('streams.pages.index'),
            route('streams.pages.create'),
            route('streams.pages.show', ['page' => 1]),
            route('streams.pages.edit', ['page' => 1]),
            route('streams.categories.index'),
            route('streams.categories.create'),
            route('streams.categories.show', ['category' => 1]),
            route('streams.categories.edit', ['category' => 1]),
            route('streams.menus.index'),
            route('streams.menus.create'),
            route('streams.menus.show', ['menu' => 1]),
            route('streams.menus.edit', ['menu' => 1]),
            route('streams.database.index'),
            //route('streams.database.edit_bread', ['id' => 5]),
            //route('streams.database.edit', ['table' => 'categories']),
            route('streams.database.create'),
        ];

        foreach ($urls as $url) {
            $response = $this->call('GET', $url);
            $this->assertEquals(200, $response->status(), $url.' did not return a 200');
        }
    }
}
