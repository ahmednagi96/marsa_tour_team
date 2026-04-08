<?php 
namespace Database\Factories;

use App\Models\Trip;
use App\Models\TripTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition(): array
    {
        return [
            'photo' => 'trip_' . fake()->numberBetween(1, 10) . '.jpg',
            'created_at' => now(),
        ];
    }

    /**
     * Attach default translation rows after creating the base trip row.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Trip $trip): void {
            $this->createTranslations($trip, $this->defaultTranslations());
        });
    }

    public function withTranslations(array $translations = []): static
    {
        return $this->afterCreating(function (Trip $trip) use ($translations): void {
            TripTranslation::query()->where('trip_id', $trip->id)->delete();
            $this->createTranslations($trip, $translations ?: $this->defaultTranslations());
        });
    }

    private function createTranslations(Trip $trip, array $translations): void
    {
        foreach ($translations as $locale => $data) {
            TripTranslation::factory()->create([
                'trip_id' => $trip->id,
                'locale' => $locale,
                'name' => $data['name'] ?? '',
                'description' => $data['description'] ?? null,
            ]);
        }
    }

    private function defaultTranslations(): array
    {
        return [
            'en' => [
                'name' => fake()->words(3, true),
                'description' => fake()->paragraph(2),
            ],
            'ar' => [
                'name' => \Faker\Factory::create('ar_SA')->realText(20),
                'description' => \Faker\Factory::create('ar_SA')->realText(200),
            ],
        ];
    }
}