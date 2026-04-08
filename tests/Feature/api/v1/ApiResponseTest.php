<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
uses(TestCase::class,RefreshDatabase::class);
test("it returns a successful response for trips list", function () {
    $response = $this->getJson(route("v1.trips.index"));
    $response->assertStatus(200);
});