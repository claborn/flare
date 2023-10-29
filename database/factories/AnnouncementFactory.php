<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Flare\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Announcement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'message'     => Str::random(100),
            'expires_at'  => now(),
            'event_id'    => null,
        ];
    }
}
