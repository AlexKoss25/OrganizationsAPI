<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Activity;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('activity_organization')->delete();
        \DB::table('organizations')->delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $activities = Activity::all()->keyBy('name');

        $orgsData = [
            ['name' => 'ТехКорп', 'phones' => ['+7 123 456 78 90', '+7 123 456 78 91'], 'building_id' => 1, 'activities' => [$activities['Мясная продукция']->id]],
            ['name' => 'Молочный Дом', 'phones' => ['+7 111 222 33 44', '+7 920 248 44 88'], 'building_id' => 2, 'activities' => [$activities['Молочная продукция']->id]],
            ['name' => 'АвтоПро', 'phones' => ['+7 222 333 44 55'], 'building_id' => 3, 'activities' => [$activities['Легковые']->id, $activities['Запчасти']->id]],
            ['name' => 'Грузовичок', 'phones' => ['+7 333 444 55 66'], 'building_id' => 4, 'activities' => [$activities['Грузовые']->id]],
            ['name' => 'Фуди', 'phones' => ['+7 444 555 66 77'], 'building_id' => 5, 'activities' => [$activities['Еда']->id]],
            ['name' => 'АвтоАксессуары', 'phones' => ['+7 555 666 77 88'], 'building_id' => 6, 'activities' => [$activities['Аксессуары']->id]],
            ['name' => 'СвежМясо', 'phones' => ['+7 666 777 88 99', '+7 999 601 97 75', '+7 999 907 54 85'], 'building_id' => 7, 'activities' => [$activities['Мясная продукция']->id]],
            ['name' => 'ДэйриКинг', 'phones' => ['+7 777 888 99 00'], 'building_id' => 8, 'activities' => [$activities['Молочная продукция']->id]],
            ['name' => 'АвтоЛюкс', 'phones' => ['+7 888 999 00 11'], 'building_id' => 9, 'activities' => [$activities['Легковые']->id]],
            ['name' => 'МегаТрак', 'phones' => ['+7 999 000 11 22', '+7 945 452 55 55'], 'building_id' => 10, 'activities' => [$activities['Грузовые']->id]],
        ];

        foreach ($orgsData as $data) {
            $org = Organization::create([
                'name' => $data['name'],
                'phones' => json_encode($data['phones']),
                'building_id' => $data['building_id'],
            ]);
            $org->activities()->sync($data['activities']);
        }

        $this->command->info('Organizations seeded successfully!');
    }
}
