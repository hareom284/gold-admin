<?php

namespace Modules\Subscriptions\database\seeders;

use Illuminate\Database\Seeder;

class PlanTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('plan')->delete();

        \DB::table('plan')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Monthly Plan',
                'identifier' => 'monthly_plan',
                'android_identifier' => NULL,
                'apple_identifier' => NULL,
                'price' => 3000,
                'discount' => 0,
                'discount_percentage' => NULL,
                'total_price' => 3000,
                'level' => 1,
                'duration' => 'Days',
                'duration_value' => 31,
                'status' => 1,
                'description' => 'Monthly Plan',
                'created_by' => 2,
                'updated_by' => 2,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2024-07-11 04:42:21',
                'updated_at' => '2024-07-11 04:42:21',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Yearly Plan',
                'identifier' => 'yearly_plan',
                'android_identifier' => NULL,
                'apple_identifier' => NULL,
                'price' => 35000,
                'discount' => 0,
                'discount_percentage' => 0,
                'total_price' => 35000,
                'level' => 2,
                'duration' => 'Days',
                'duration_value' => 365,
                'status' => 1,
                'description' => 'Yearly plan',
                'created_by' => 2,
                'updated_by' => 2,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2024-07-11 04:43:13',
                'updated_at' => '2024-10-08 09:28:11',
            )
        ));


    }
}
