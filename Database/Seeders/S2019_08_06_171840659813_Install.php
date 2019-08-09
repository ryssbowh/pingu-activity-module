<?php

namespace Pingu\Activity\Database\Seeders;

use Pingu\Core\Seeding\DisableForeignKeysTrait;
use Pingu\Core\Seeding\MigratableSeeder;
use Pingu\Forms\Support\Fields\NumberInput;
use Pingu\Menu\Entities\Menu;
use Pingu\Permissions\Entities\Permission;
use Pingu\Settings\Entities\Settings as SettingsModel;
use Pingu\Settings\Forms\Types\Integer;

class S2019_08_06_171840659813_Install extends MigratableSeeder
{
    use DisableForeignKeysTrait;

    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        Settings::register('activity.lifetime',[
            'Title' => 'Activity life span',
            'Section' => 'core',
            'field' => NumberInput::class,
            'type' => Integer::class,
            'validation' => 'required|integer'
        ]);

        Permission::create(['name' => 'view activity', 'section' => 'Activity']);
        Permission::create(['name' => 'purge activity', 'section' => 'Activity']);
    }

    /**
     * Reverts the database seeder.
     */
    public function down(): void
    {
        if($perm = Permission::where('name', 'view activity')->first()){
            $perm->delete();
        }
        if($perm = Permission::where('name', 'purge activity')->first()){
            $perm->delete();
        }
        if($set = SettingsModel::where('name', 'activity.lifetime')->first()){
            $set->delete();
        }
    }
}
