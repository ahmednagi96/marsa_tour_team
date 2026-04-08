<?php

use App\Models\Trip;

uses(\Tests\TestCase::class, \Illuminate\Foundation\Testing\RefreshDatabase::class);

test('it can create a trip with translations', function () {
    $trip = Trip::factory()

        ->withTranslations([
            'en' => ['name' => 'Luxor Trip'],
            'ar' => ['name' => 'رحلة الأقصر'],
        ])
        ->create([
            'trending'=>1
        ]);

    // 2. Assert: التأكد من وجود الداتا في قاعدة البيانات
    $this->assertDatabaseHas('trips', [
        'id' => $trip->id,
    ]);
    $this->assertEquals("مشهوره",$trip->trending?->label());

    $this->assertDatabaseHas('trip_translations', [
        'trip_id' => $trip->id,
        'locale'  => 'en',
        'name'    => 'Luxor Trip',
    ]);

    $this->assertDatabaseHas('trip_translations', [
        'trip_id' => $trip->id,
        'locale'  => 'ar',
        'name'    => 'رحلة الأقصر',
    ]);
});

test('it returns the correct photo url attribute', function () {
    // 1. Arrange: إنشاء رحلة بصورة معينة
    $trip = Trip::factory()->create(['photo' => 'test.jpg']);

    // 2. Assert: التأكد أن الـ Accessor شغال وبيستخدم الـ asset()
    expect($trip->photo_url)->toBe(asset('images/trips/test.jpg'));
});

test('it returns default image if photo is null', function () {
    $trip = Trip::factory()->create(['photo' => null]);

    expect($trip->photo_url)->toBe(asset('images/default.jpeg'));
});

test('it loads translations automatically with the model', function () {
    $trip = Trip::factory()->create();
    $trip = Trip::query()->findOrFail($trip->id);

    expect($trip->relationLoaded('translations'))->toBeTrue();
});