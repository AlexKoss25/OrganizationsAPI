<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('activity_organization')->delete();
        \DB::table('activities')->delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1 уровень
        $food = Activity::create(['name' => 'Еда', 'parent_id' => null]);
        $auto = Activity::create(['name' => 'Автомобили', 'parent_id' => null]);
        $it = Activity::create(['name' => 'IT', 'parent_id' => null]);
        $health = Activity::create(['name' => 'Здоровье', 'parent_id' => null]);

        // 2 уровень
        $meat = Activity::create(['name' => 'Мясная продукция', 'parent_id' => $food->id]);
        $dairy = Activity::create(['name' => 'Молочная продукция', 'parent_id' => $food->id]);
        $bakery = Activity::create(['name' => 'Выпечка', 'parent_id' => $food->id]);

        $cars = Activity::create(['name' => 'Легковые', 'parent_id' => $auto->id]);
        $trucks = Activity::create(['name' => 'Грузовые', 'parent_id' => $auto->id]);

        $software = Activity::create(['name' => 'Разработка ПО', 'parent_id' => $it->id]);
        $network = Activity::create(['name' => 'Сетевое оборудование', 'parent_id' => $it->id]);

        $clinics = Activity::create(['name' => 'Клиники', 'parent_id' => $health->id]);
        $pharmacy = Activity::create(['name' => 'Аптеки', 'parent_id' => $health->id]);

        // 3 уровень
        $spareParts = Activity::create(['name' => 'Запчасти', 'parent_id' => $cars->id]);
        $accessories = Activity::create(['name' => 'Аксессуары', 'parent_id' => $cars->id]);

        $dentistry = Activity::create(['name' => 'Стоматология', 'parent_id' => $clinics->id]);
        $therapy = Activity::create(['name' => 'Терапия', 'parent_id' => $clinics->id]);

        // 4 уровень (для проверки API)
        Activity::create(['name' => 'Специальные запчасти', 'parent_id' => $spareParts->id]);
        Activity::create(['name' => 'Эксклюзивные аксессуары', 'parent_id' => $accessories->id]);

        $this->command->info('Activities seeded successfully!');
    }
}
