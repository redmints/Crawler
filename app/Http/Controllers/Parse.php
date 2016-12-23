<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Parse extends Controller
{
    public static function recupMots($page) {
      return preg_split('/(<[^>]*[^\/]>)/i', $page, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    }

    public static function purifier($page) {
      for($i = 0; $i < count($page); $i++) {
        $ligne = $page[$i];
        $tabLigne = explode(" ", $ligne);
        if($tabLigne[0] != "<h1>" || $tabLigne[0] != "<h2>")
      }
    }
}
