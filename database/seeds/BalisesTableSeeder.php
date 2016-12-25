<?php

use Illuminate\Database\Seeder;

class BalisesTableSeeder extends Seeder {
  public function run() {
    DB::table('balises')->delete();

    DB::table('balises')->insert([
      'balise' => 'std',
      'poids' => '1'
    ]);
    DB::table('balises')->insert([
      'balise' => 'p',
      'poids' => '2'
    ]);
    DB::table('balises')->insert([
      'balise' => 'h4',
      'poids' => '3'
    ]);
    DB::table('balises')->insert([
      'balise' => 'h3',
      'poids' => '4'
    ]);
    DB::table('balises')->insert([
      'balise' => 'h2',
      'poids' => '5'
    ]);
    DB::table('balises')->insert([
      'balise' => 'h1',
      'poids' => '6'
    ]);
    DB::table('balises')->insert([
      'balise' => 'title',
      'poids' => '7'
    ]);
  }
}
