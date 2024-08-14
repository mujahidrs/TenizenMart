<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Wallet;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $role_customer = Role::create(['name' => 'customer']);
        $role_bank = Role::create(['name' => 'bank']);
        $role_kantin = Role::create(['name' => 'kantin']);
        $role_admin = Role::create(['name' => 'admin']);

        $user_customer = User::create([
            'name' => 'customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('customer')
        ]);

        $user_bank = User::create([
            'name' => 'bank',
            'email' => 'bank@gmail.com',
            'password' => Hash::make('bank')
        ]);

        $user_kantin = User::create([
            'name' => 'kantin',
            'email' => 'kantin@gmail.com',
            'password' => Hash::make('kantin')
        ]);

        $user_admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin')
        ]);

        RoleUser::create([
            'user_id' => $user_customer->id,
            'role_id' => $role_customer->id
        ]);

        RoleUser::create([
            'user_id' => $user_bank->id,
            'role_id' => $role_bank->id
        ]);
        
        RoleUser::create([
            'user_id' => $user_kantin->id,
            'role_id' => $role_kantin->id
        ]);

        RoleUser::create([
            'user_id' => $user_admin->id,
            'role_id' => $role_admin->id
        ]);

        $category_makanan = Category::create(['name' => 'Makanan']);
        $category_minuman = Category::create(['name' => 'Minuman']);
        $category_atk = Category::create(['name' => 'ATK']);

        Product::create([
            'name' => 'Chitato',
            'price' => 15000,
            'description' => 'Chitato khas Jakarta',
            'category_id' => $category_makanan->id,
            'stock' => 10
        ]);

        Product::create([
            'name' => 'Superstar',
            'price' => 5000,
            'description' => 'superstar',
            'category_id' => $category_makanan->id,
            'stock' => 5
        ]);

        Product::create([
            'name' => 'Superstar',
            'price' => 5000,
            'description' => 'superstar',
            'category_id' => $category_makanan->id,
            'stock' => 5
        ]);

        Product::create([
            'name' => 'Superstar',
            'price' => 5000,
            'description' => 'superstar',
            'category_id' => $category_makanan->id,
            'stock' => 5
        ]);

        Product::create([
            'name' => 'Superstar',
            'price' => 5000,
            'description' => 'superstar',
            'category_id' => $category_makanan->id,
            'stock' => 5
        ]);

        Product::create([
            'name' => 'Le minerale',
            'price' => 2000,
            'description' => 'minuman',
            'category_id' => $category_minuman->id,
            'stock' => 5
        ]);

        Product::create([
            'name' => 'Le minerale',
            'price' => 2000,
            'description' => 'minuman',
            'category_id' => $category_minuman->id,
            'stock' => 5
        ]);

        Product::create([
            'name' => 'Le minerale',
            'price' => 2000,
            'description' => 'minuman',
            'category_id' => $category_minuman->id,
            'stock' => 5
        ]);

        Product::create([
            'name' => 'Le minerale',
            'price' => 2000,
            'description' => 'minuman',
            'category_id' => $category_minuman->id,
            'stock' => 5
        ]);

        Product::create([
            'name' => 'Le minerale',
            'price' => 2000,
            'description' => 'minuman',
            'category_id' => $category_minuman->id,
            'stock' => 5
        ]);

        Product::create([
            'name' => 'Pensil',
            'price' => 2500,
            'description' => 'menulis',
            'category_id' => $category_atk->id,
            'stock' => 5
        ]);

        Product::create([
            'name' => 'Pensil',
            'price' => 2500,
            'description' => 'menulis',
            'category_id' => $category_atk->id,
            'stock' => 5
        ]);

        Product::create([
            'name' => 'Pensil',
            'price' => 2500,
            'description' => 'menulis',
            'category_id' => $category_atk->id,
            'stock' => 5
        ]);

        Product::create([
            'name' => 'Pensil',
            'price' => 2500,
            'description' => 'menulis',
            'category_id' => $category_atk->id,
            'stock' => 5
        ]);

        Product::create([
            'name' => 'Pensil',
            'price' => 2500,
            'description' => 'menulis',
            'category_id' => $category_atk->id,
            'stock' => 5
        ]);

        Wallet::create([
            'user_id' => $user_customer->id,
            'income' => 50000,
            'description' => 'Top Up'
        ]);
    }
}
