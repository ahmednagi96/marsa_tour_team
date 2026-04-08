<?php

use Tests\TestCase;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
uses(TestCase::class);
test("it returns a successful response for trips list", function () {
    $response = $this->getJson(route("v1.trips.index"));
    $response->assertStatus(200)
    ->assertJson(['data']);
});