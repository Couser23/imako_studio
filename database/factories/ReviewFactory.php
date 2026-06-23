<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\User::where('role', 'user')->inRandomOrder()->first()?->id ?? \App\Models\User::factory()->create(['role' => 'user'])->id,
            'employee_id' => \App\Models\User::where('role', 'pegawai')->inRandomOrder()->first()?->id ?? \App\Models\User::factory()->create(['role' => 'pegawai'])->id,
            'rating' => $this->faker->numberBetween(3, 5),
            'comment' => $this->faker->randomElement([
                'Bagus sih, tapi proses editingnya lumayan lama. Tapi overall oke.',
                'Mantap, profesional kerjanya.',
                'Puas banget! Detailnya dapet, warnanya juga pas sesuai request.',
                'Fotografernya ramah banget dan asik diajak ngobrol!',
                'Sangat memuaskan, kualitas foto luar biasa.',
                'Sesi foto berjalan lancar, hasil juga cepat dikirim.',
            ]),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            },
        ];
    }
}
