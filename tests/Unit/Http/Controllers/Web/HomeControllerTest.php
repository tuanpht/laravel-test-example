<?php

namespace Tests\Unit\Http\Controllers\Web;

use Tests\TestCase;
use App\Http\Controllers\Web\HomeController;

class HomeControllerTest extends TestCase
{
    public function testIndexCorrectView()
    {
        $controller = new HomeController;

        $view = $controller->index();

        $this->assertEquals('home', $view->name());
    }
}
