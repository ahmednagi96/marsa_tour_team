<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

class TourFactory extends Factory
{
    protected $model = Tour::class;

    public function definition(): array
    {
        $price = $this->faker->numberBetween(100, 1000);
        $hasDiscount = $this->faker->numberBetween(0, 1) === 1;
        $discountType = $this->faker->numberBetween(0, 1) === 1 ? 'fixed' : 'percentage';
        $discountValueOptions = [10, 20, 50];
        $discountValue = $discountValueOptions[$this->faker->numberBetween(0, 2)];

        return [
            'trip_id'        => Trip::factory(),
            'duration'       => $this->faker->numberBetween(1, 14),
            'price'          => $price,
            'discount_type'  => $hasDiscount ? $discountType : null,
            'discount_value' => $hasDiscount ? $discountValue : 0,
            'sale_start'     => now()->subDays(5),
            'sale_end'       => now()->addDays(20),
            'is_active'      => true,
            'is_favourite'   => $this->faker->numberBetween(1, 5) === 1,
            'photos'         => [
                'default-1.jpg',
                'default-2.jpg',
            ],
            'video'          => 'dQw4w9WgXcQ',
        ];
    }

    /**
     * سينيور تريك: ميثود بتخليه يكريت الترجمات أوتوماتيك
     */
    public function configure()
    {
        return $this->afterCreating(function (Tour $tour) {
            // بيانات عربي
            $tour->translations()->create([
                'locale'      => 'ar',
                'name'        => 'جولة ممتعة في المدينة',
                'country'     => 'مصر',
                'city'        => 'القاهرة',
                'description' => 'هذا الوصف تجريبي لرحلة سياحية رائعة تشمل كافة الخدمات.',
                'services'    => ['وايفاي', 'انتقالات', 'وجبة غداء'],
            ]);

            // بيانات إنجليزي
            $tour->translations()->create([
                'locale'      => 'en',
                'name'        => 'Amazing Tour in City',
                'country'     => 'Egypt',
                'city'        => 'Cairo',
                'description' => 'This is a fake description for a wonderful tour with all services included.',
                'services'    => ['WiFi', 'Transfer', 'Lunch'],
            ]);
        });
    }
}