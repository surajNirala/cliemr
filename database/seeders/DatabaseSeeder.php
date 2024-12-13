<?php

namespace Database\Seeders;

use App\Models\CRN;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Database\Seeders\NoteSeeder;
use Database\Seeders\AdviceSeeder;
use Database\Seeders\ComplaintSeeder;
use Database\Seeders\DiagnosisSeeder;
use Database\Seeders\QuickNoteSeeder;
use Database\Seeders\SpecialitySeeder;
use Database\Seeders\TestPrescribeSeeder;
use Database\Seeders\RolesPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        $this->call(RolesPermissionsSeeder::class);

        //Super Admin
        User::create([
            'name' => 'SuperAdmin',
            'username' => 'superAdmin',
            'email' => 'superadmin@gmail.com',
            'phone' => '1234567890',
            'flag' => 1,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        $user = User::find(1);
        $role = Role::find(1);
        $user->roles()->attach($role);
        CRN::create(['user_id'=>$user->id,'crn_name'=>'crn_'.$user->id,'flag'=>1]);

        // Admin
        User::create([
            'name' => 'Admin',
            'username' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '1234567890',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        $user = User::find(2);
        $role = Role::find(2);
        $user->roles()->attach($role);
        CRN::create(['user_id'=>$user->id,'crn_name'=>'crn_'.$user->id]);

        // Doctor
        User::create([
            'name' => 'Srj Doctor',
            'username' => 'srj Doctor',
            'email' => 'doctor@gmail.com',
            'phone' => '1234567890',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        $user = User::find(3);
        $role = Role::find(3);
        $user->roles()->attach($role);

        // FrontDesk
        User::create([
            'name' => 'Front Desk',
            'username' => 'Front Desk',
            'email' => 'frontdesk@gmail.com',
            'phone' => '1234567890',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        $user = User::find(4);
        $role = Role::find(5);
        $user->roles()->attach($role);


        $this->call(SpecialitySeeder::class);
        $this->call(QuickNoteSeeder::class);
        $this->call(AdviceSeeder::class);
        $this->call(TestPrescribeSeeder::class);
        $this->call(ComplaintSeeder::class);
        $this->call(DiagnosisSeeder::class);
        $this->call(NoteSeeder::class);
    }
}
