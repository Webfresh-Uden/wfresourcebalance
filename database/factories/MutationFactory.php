<?php

namespace factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use WebFresh\UserManager\Models\WfumUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MutationFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    protected $model = WfumUser::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'blocked' => false,
            'shadow_opt_out' => 0,
        ];
    }
}
