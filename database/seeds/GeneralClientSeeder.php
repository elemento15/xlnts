<?php

use Illuminate\Database\Seeder;
use App\Client;

class GeneralClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! Client::where('is_general', true)->first()) {
        	Client::create([
        		'name' => 'Venta Rápida',
        		'rfc'  => 'XAXX010101000',
        		'is_general' => 1
        	]);
        }
    }
}
