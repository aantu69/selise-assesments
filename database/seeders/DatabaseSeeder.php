<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(PermissionRoleUserSeeder::class);

        // Category::factory()
        //     ->times(2)
        //     ->create(['parent_id' => null])
        //     ->each(
        //         fn (Category $category) => Category::factory()
        //             ->times(2)
        //             ->create(['parent_id' => $category->id])
        //             ->each(
        //                 fn (Category $category) => Category::factory()
        //                     ->times(2)
        //                     ->create(['parent_id' => $category->id])
        //                     ->each(
        //                         fn (Category $category) => Category::factory()
        //                             ->times(2)
        //                             ->create(['parent_id' => $category->id])
        //                     )
        //             )
        //     );
    }
}
