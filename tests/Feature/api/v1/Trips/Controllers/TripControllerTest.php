<?php

use App\Models\Trip;
use Illuminate\Support\Facades\Cache;

uses(\Tests\TestCase::class, \Illuminate\Foundation\Testing\RefreshDatabase::class);

test('it returns trips list with standard api response envelope', function () {
    Trip::factory()
        ->count(2)
        ->withTranslations([
            'en' => ['name' => 'Red Sea Trip', 'description' => 'Discover the red sea.'],
            'ar' => ['name' => 'رحلة البحر الأحمر', 'description' => 'استكشف البحر الأحمر.'],
        ])
        ->create();

    $response = $this->getJson(route('v1.trips.index'));

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'status_code' => 200,
            'message' => __('messages.trips_retrieved'),
            'errors' => null,
        ])
        ->assertJsonStructure([
            'success',
            'status_code',
            'message',
            'data' => [
                '*' => ['id', 'name', 'description', 'photo'],
            ],
            'errors',
        ]);
});

test('it respects pagination per_page query param', function () {
    Trip::factory()->count(3)->create();
    Cache::tags(['trips'])->flush();

    $response = $this->getJson(route('v1.trips.index', ['per_page' => 2]));

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

test('it returns localized message for arabic locale', function () {
    Trip::factory()->create();
    app()->setLocale('ar');

    $response = $this->getJson(route('v1.trips.index'));

    $response->assertOk()
        ->assertJsonPath('message', __('messages.trips_retrieved', locale: 'ar'));
});