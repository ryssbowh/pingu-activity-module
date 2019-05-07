<?php

namespace Pingu\Activity\Database\Seeders;

use Pingu\Forms\Fields\Number;
use Settings;

class ActivityDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::register('activity.lifetime',[
            'Title' => 'Activity life span',
            'Section' => 'Activity Logging',
            'type' => Number::class,
            'validation' => 'required|integer'
        ]);
    }
}
