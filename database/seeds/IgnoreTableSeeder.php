<?php

use Illuminate\Database\Seeder;

class IgnoreTableSeeder extends Seeder {
  public function run() {
    DB::table('ignore')->delete();

    $mots = array("de","la","le","à","a","dès","et","les","en","des","du","un","dans","sur","pour","par","est","une","que","qui","au","avec"
    ,"où","se","il","ce","leur","son","sont","comme","pas","ne","cette","aux","on","sa","tout","mais","ont","ils","leurs","je","tu","nous"
    ,"vous","même","aussi","ses","ces","cet","tous","","À","ou");

    for($i = 0; $i < count($mots); $i++) {
      DB::table('ignore')->insert([
        'mot' => $mots[$i],
      ]);
    }
  }
}
