<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $plans = [
            ['name'=>'Basic','calls_per_minute'=>3,'description'=>'3 calls per minute'],
            ['name'=>'Standard','calls_per_minute'=>5,'description'=>'5 calls per minute'],
            ['name'=>'Premium','calls_per_minute'=>10,'description'=>'10 calls per minute'],
        ];

        foreach ($plans as $p) {
            SubscriptionPlan::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
