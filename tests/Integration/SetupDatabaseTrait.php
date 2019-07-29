<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

trait SetupDatabaseTrait
{
    use RefreshDatabase; // When using in memory SQLite DB test
    // use DatabaseTransactions; // When using MySQL DB test
}
