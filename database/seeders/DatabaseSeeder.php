<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Enums\UserType;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    User::factory(10)->create();

    Provider::create([
      'name' => 'MTN airtime',
      'code' => 'MTN',
      'status' => true
    ]);

    Provider::create([
      'name' => 'GLO airtime',
      'code' => 'GLO',
      'status' => true
    ]);
  }
}
