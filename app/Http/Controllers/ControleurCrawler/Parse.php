<?php

namespace App\Http\Controllers\ControleurCrawler;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Parse extends Controller
{
  public static function recupMots($page) {
    return preg_split('/(<[^>]*[^\/]>)/i', $page, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
  }

  public static function purifier($page) {
    $newTab = array();
    $balises[] = "title";
    $balises[] = "h1";
    $balises[] = "h2";
    $balises[] = "h3";
    $balises[] = "h4";
    $balises[] = "p";
    for($i = 0; $i < count($page); $i++) {
      $ligne = $page[$i];
      for($j = 0; $j < count($balises); $j++) {
        $balise = $balises[$j];
        if(((substr($ligne,0,strlen($balise)+1) == "<".$balise)
        || (substr($ligne,0,1) != "<")
        || (substr($ligne,0,strlen($balise)+2) == "</".$balise))
        && (!in_array($ligne, $newTab))) {
          $newTab[] = $ligne;
        }
      }
    }
    return $newTab;
  }

  public static function giveImportance($page) {
    for($i = 0; $i < count($page); $i++) {
      $ligne = $page[$i];
      if(substr($ligne,0,1) != "<") {
        $mots = explode(" ", $ligne);
        for($j = 0; $j < count($mots); $j++) {
          $retour[] = $mots[$j];
        }
      }
    }
    return $retour;
  }
}
