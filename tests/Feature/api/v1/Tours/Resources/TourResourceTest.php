<?php

use App\Http\Resources\TourResource;
use App\Models\Tour;
use App\Models\Trip;

uses(\Tests\TestCase::class, \Illuminate\Foundation\Testing\RefreshDatabase::class);

test('tour resource formats discounted data correctly', function () {
    $trip = Trip::query()->create();

    $tour = Tour::query()->create([
        'trip_id' => $trip->id,
        'duration' => 5,
        'price' => 1000,
        'discount_type' => 'percentage',
        'discount_value' => 10,
        'sale_start' => now()->subDay(),
        'sale_end' => now()->addDay(),
        'is_active' => true,
        'is_favourite' => false,
    ]);
    $tour->name = 'Test Tour';
    $tour->city = 'Cairo';
    $tour->save();

    $resource = (new TourResource($tour->fresh()))->toArray(request());
dd($resource);
    expect($resource)->toBeArray();
    expect($resource['id'])->toBe($tour->id);
    expect($resource['name'])->toBe($tour->name);
    expect($resource['location'])->toBeArray();
    expect($resource['location']['city'])->toBe('Cairo');

    expect($resource['pricing'])->toBeArray();
    expect($resource['pricing']['original_price'])->toBe(1000.0);
    expect($resource['pricing']['current_price'])->toBe(900.0);
    expect($resource['pricing']['is_on_sale'])->toBeTrue();
    expect($resource['pricing']['discount'])->not->toBeNull();
    expect($resource['pricing']['discount']['type'])->toBe('percentage');
    expect($resource['pricing']['discount']['value'])->toBe(10.0);
});