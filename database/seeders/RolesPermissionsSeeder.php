<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ########## Roles ##################
        $superadmin = Role::create([
            'user_id'=> 1,
            'name' => 'SuperAdmin'
        ]);
        $admin = Role::create([
            'user_id'=> 1,
            'name' => 'Admin'
        ]);
        $doctor = Role::create([
            'user_id'=> 1,
            'name' => 'Doctor'
        ]);
        $front_desk = Role::create([
            'user_id'=> 1,
            'name' => 'Front Desk'
        ]);
        $anthropometry = Role::create([
            'user_id'=> 1,
            'name' => 'Anthropometry'
        ]);
        $lab_tech = Role::create([
            'user_id'=> 1,
            'name' => 'Lab Tech'
        ]);
        $nurse = Role::create([
            'user_id'=> 1,
            'name' => 'Nurse'
        ]);
        $billing_desk = Role::create([
            'user_id'=> 1,
            'name' => 'Billing Desk'
        ]);
        $resident_doctor = Role::create([
            'user_id'=> 1,
            'name' => 'Resident Doctor'
        ]);

        ##########  Permissions ###########
        $treatment_by_doctor = Permission::create([
            'user_id'=> 1,
            'name' => 'treatment by doctor',
            'description' => 'This permission allows to treat the patient.',
        ]);
        $add_patient = Permission::create([
            'user_id'=> 1,
            'name' => 'Add Patient',
            'description' => 'This permission allows the user to add a new patient in to the system.',
        ]);
        $front_desk_permission = Permission::create([
            'user_id'=> 1,
            'name' => 'Front Desk Appointment and Billing',
            'description' => 'This permission allows the user to manage the appointment calendar and billing.',
        ]);
        $front_desk_administration = Permission::create([
            'user_id'=> 1,
            'name' => 'Front Desk Administration',
            'description' => 'This permission allows the user to manage appointments and billing along with   access to billing and operations reports.',
        ]);
        $lab_management = Permission::create([
            'user_id'=> 1,
            'name' => 'Lab Management',
            'description' => 'This permission allows the user to access lab module to record test values.',
        ]);
        $lab_administration = Permission::create([
            'user_id'=> 1,
            'name' => 'Lab Administration',
            'description' => 'This permission allows the user to access lab module, manage lab services and modify the lab bills.',
        ]);
        $record_patient_value = Permission::create([
            'user_id'=> 1,
            'name' => 'Record Patient Value',
            'description' => 'This permission allows the user to add the patient vitals.',
        ]);
        $branch_dministration = Permission::create([
            'user_id'=> 1,
            'name' => 'Branch Administration',
            'description' => 'This permission allows the user to access the branch admin module and manage other users in the system.',
        ]);
        $delete_bills = Permission::create([
            'user_id'=> 1,
            'name' => 'Delete Bills',
            'description' => 'This permission allows user to delete the bills.',
        ]);
        $update_unit_price_while_billing = Permission::create([
            'user_id'=> 1,
            'name' => 'Update Unit Price While Billing',
            'description' => 'This will allow the user to change the unit price of the service while billing.',
        ]);
        
        # Assign Permison to role #################        
        $superadmin->permissions()->attach([
            $treatment_by_doctor->id,
            $add_patient->id,
            $front_desk_permission->id,
            $front_desk_administration->id,
            $lab_management->id,
            $lab_administration->id,
            $record_patient_value->id,
            $branch_dministration->id,
            $delete_bills->id,
            $update_unit_price_while_billing->id,
        ]);

        $admin->permissions()->attach([
            $treatment_by_doctor->id,
            $add_patient->id,
            $front_desk_permission->id,
            $front_desk_administration->id,
            $lab_management->id,
            $lab_administration->id,
            $record_patient_value->id,
            $branch_dministration->id,
            $update_unit_price_while_billing->id,
        ]);

        $doctor->permissions()->attach([
            $treatment_by_doctor->id,
            $add_patient->id,
            $front_desk_permission->id,
            $front_desk_administration->id,
            $lab_management->id,
            $lab_administration->id,
            $record_patient_value->id,
            $branch_dministration->id,
        ]);

        $front_desk->permissions()->attach([
            $front_desk_permission->id,
            $front_desk_administration->id,
            $lab_management->id,
            $lab_administration->id,
            $record_patient_value->id,
            $branch_dministration->id,
            $update_unit_price_while_billing->id,
        ]);

        $anthropometry->permissions()->attach([
            $lab_management->id,
            $lab_administration->id,
            $record_patient_value->id,
            $branch_dministration->id,
            $update_unit_price_while_billing->id,
        ]);

        $lab_tech->permissions()->attach([
            $lab_management->id,
            $lab_administration->id,
        ]);

        $nurse->permissions()->attach([
            $record_patient_value->id,
            $branch_dministration->id,
        ]);

        $billing_desk->permissions()->attach([
            $update_unit_price_while_billing->id,
        ]);

        $resident_doctor->permissions()->attach([
            $add_patient->id,
            $front_desk_permission->id,
            $front_desk_administration->id,
            $lab_management->id,
            $lab_administration->id,
            $record_patient_value->id,
            $branch_dministration->id,
            $update_unit_price_while_billing->id,
        ]);

    }
}
