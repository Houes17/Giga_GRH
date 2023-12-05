<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    const RoleSuperAdmin = "SuperAdmin";
    const RoleAdmin = "Admin";
    const RoleControleur = "Controleur";
    const RoleComptable = "Comptable";
    const RoleChefAgent = "Chef-Agent";

    const permissionCreateUser = "create-user";
    const permissionEditUser = "edit-user";
    const permissionListUsers = "list-users";

    const permissionCreateEmploye = "create-employe";
    const permissionEditEmploye = "edit-employe";
    const permissionListEmploye = "list-employe";

    const permissionCreatePresence = "create-presence";
    const permissionEditPresence = "edit-presence";
    const permissionListPresence = "list-presence";

    const permissionCreateSalaire = "create-salaire";
    const permissionEditSalaire = "edit-salaire";
    const permissionListSalaire = "list-salaire";

    const permissionCreateDotation = "create-dotation";
    const permissionEditDotation = "edit-dotation";
    const permissionListDotation = "list-dotation";

    const permissionCreateDepartement = "create-departement";
    const permissionEditDepartement = "edit-departement";
    const permissionListDepartement = "list-departement";

    const permissionCreateVille = "create-ville";
    const permissionEditVille = "edit-ville";
    const permissionListVille = "list-ville";

    const permissionCreateRapport = "create-rapport";
    const permissionEditRapport = "edit-rapport";
    const permissionListRapport = "list-rapport";

    const permissionCreateSite = "create-site";
    const permissionEditSite = "edit-site";
    const permissionListSite = "list-site";

    public function run(): void
    {
        $permissions = [
            self::permissionCreateUser,
            self::permissionEditUser,
            self::permissionListUsers,

            self::permissionCreateEmploye,
            self::permissionEditEmploye,
            self::permissionListEmploye,

            self::permissionCreatePresence,
            self::permissionEditPresence,
            self::permissionListPresence,

            self::permissionCreateSalaire,
            self::permissionEditSalaire,
            self::permissionListSalaire,

            self::permissionCreateDepartement,
            self::permissionEditDepartement,
            self::permissionListDepartement,

            self::permissionCreateDotation,
            self::permissionEditDotation,
            self::permissionListDotation,

            self::permissionCreateSite,
            self::permissionEditSite,
            self::permissionListSite,

            self::permissionCreateVille,
            self::permissionEditVille,
            self::permissionListVille,

            self::permissionCreateRapport,
            self::permissionEditRapport,
            self::permissionListRapport


            
       
        ];
        foreach ($permissions as $key => $value) {
            Permission::firstOrCreate(['name' => $value]);
        }

        $this->setRoleSuperAdmin();
        $this->setRoleAdmin();
        $this->setRoleControleur();
        $this->setRoleComptable();
        $this->setRoleChefAgent();
    }
    
    private function setRoleSuperAdmin()
    {
        $role = Role::firstOrCreate(['name' => self::RoleSuperAdmin]);

        $granted = [
            self::permissionCreateUser,
            self::permissionEditUser,
            self::permissionListUsers,

            self::permissionCreateEmploye,
            self::permissionEditEmploye,
            self::permissionListEmploye,

            self::permissionCreatePresence,
            self::permissionEditPresence,
            self::permissionListPresence,

            self::permissionCreateSalaire,
            self::permissionEditSalaire,
            self::permissionListSalaire,

            self::permissionCreateDepartement,
            self::permissionEditDepartement,
            self::permissionListDepartement,

            self::permissionCreateDotation,
            self::permissionEditDotation,
            self::permissionListDotation,

            self::permissionCreateSite,
            self::permissionEditSite,
            self::permissionListSite,

            self::permissionCreateVille,
            self::permissionEditVille,
            self::permissionListVille,

            self::permissionCreateRapport,
            self::permissionEditRapport,
            self::permissionListRapport
           
        ];

        $role->syncPermissions($granted);
        $admin = User::firstOrCreate(
            ["email"=>"admin@example.net"],
            [
                "name"=>"admin",
                
                "email"=>"admin@example.net",
                "state"=>true,
                "password"=>Hash::make("password")

            ]
        );
        $admin->assignRole(self::RoleSuperAdmin);

    }

    private function setRoleAdmin()
    {
        $role = Role::firstOrCreate(['name' => self::RoleAdmin]);

        $granted = [
            self::permissionCreatePresence,
            self::permissionEditPresence,
            self::permissionListPresence,

            self::permissionCreateSite,
            self::permissionEditSite,
            self::permissionListSite,

            self::permissionCreateEmploye,
            self::permissionEditEmploye,
            self::permissionListEmploye,

            self::permissionCreateDotation,
            self::permissionEditDotation,
            self::permissionListDotation,

            self::permissionCreateSalaire,
            self::permissionEditSalaire,
            self::permissionListSalaire,

            self::permissionCreateVille,
            self::permissionEditVille,
            self::permissionListVille,

            self::permissionCreateDepartement,
            self::permissionEditDepartement,
            self::permissionListDepartement,

            self::permissionCreateRapport,
            self::permissionEditRapport,
            self::permissionListRapport

        ];

        $role->syncPermissions($granted);
        $secret = User::firstOrCreate(
            ["email"=>"secretaire@example.net"],
            [
                "name"=>"patrick",
                
                "email"=>"secretaire@example.net",
                "state"=>true,
                "password"=>Hash::make("password")

            ]
        );
        $secret->assignRole(self::RoleAdmin);
    }
    
    private function setRoleControleur()
    {
        $role = Role::firstOrCreate(['name' => self::RoleControleur]);

        $granted = [
            self::permissionCreatePresence,
            self::permissionEditPresence,
            self::permissionListPresence,
        ];

        $role->syncPermissions($granted);
        $controleur = User::firstOrCreate(
            ["email"=>"controleur@example.net"],
            [
                "name"=>"ivan",
                
                "email"=>"controleur@example.net",
                "state"=>true,
                "password"=>Hash::make("password")

            ]
        );
        $controleur->assignRole(self::RoleControleur);
    }

    private function setRoleComptable(){
        $role = Role::firstOrCreate(['name' => self::RoleComptable]);

        $granted = [
            self::permissionCreateSalaire,
            self::permissionEditSalaire,
            self::permissionListSalaire,

            self::permissionCreateRapport,
            self::permissionEditRapport,
            self::permissionListRapport,

            self::permissionListPresence,
        ];

        $role->syncPermissions($granted);
        $comptable = User::firstOrCreate(
            ["email"=>"comptable@example.net"],
            [
                "name"=>"ivan",
                
                "email"=>"comptable@example.net",
                "state"=>true,
                "password"=>Hash::make("password")

            ]
        );
        $comptable->assignRole(self::RoleComptable);
        
    }
    private function setRoleChefAgent()
    {
        $role = Role::firstOrCreate(['name' => self::RoleChefAgent]);

        $granted = [
            self::permissionCreateDotation,
            self::permissionEditDotation,
            self::permissionListDotation,
        ];

        $role->syncPermissions($granted);
        $chefagent = User::firstOrCreate(
            ["email"=>"chefagent@example.net"],
            [
                "name"=>"chefagent",
                
                "email"=>"chefagent@example.net",
                "state"=>true,
                "password"=>Hash::make("password")

            ]
        );
        $chefagent->assignRole(self::RoleChefAgent);
    }
}
