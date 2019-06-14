<?php

namespace Pingu\Activity\Database\Seeders;

use Illuminate\Database\Seeder;
use Pingu\Forms\Support\Fields\NumberInput;
use Pingu\Forms\Support\Types\Integer;
use Pingu\Permissions\Entities\Permission;
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
            'Section' => 'core',
            'field' => NumberInput::class,
            'type' => Integer::class,
            'validation' => 'required|integer'
        ]);

        Permission::findOrCreate(['name' => 'view activity', 'section' => 'Activity']);
        Permission::findOrCreate(['name' => 'purge activity', 'section' => 'Activity']);
    }
}
