<?php
//namespace Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Cambie el formato de comentario a /**/
        //Aqui habia una linea repetida que causaba errores, asi que la elimine.
        /*$this->call(OrganizationalUnitSeeder::class);
        $this->call(RoleAndPermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CommuneSeeder::class);
        $this->call(EstablishmentSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(CourtSeeder::class);
        $this->call(PoliceUnitSeeder::class);
        $this->call(SubstanceSeeder::class);
        $this->call(ReceptionSeeder::class);
        $this->call(TelephoneSeeder::class);
        $this->call(PlaceSeeder::class);
        $this->call(ProfessionalTableSeeder::class);
        $this->call(MinisterialProgramTableSeeder::class);
        $this->call(ActionTypeTableSeeder::class);
        $this->call(ProfileManageSeeder::class);
        $this->call(ProfessionManageSeeder::class);*/
    }
}
