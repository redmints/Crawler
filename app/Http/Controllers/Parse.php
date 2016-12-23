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
      $ligneFormat = str_replace(' ','',$ligne);
      if(substr($ligneFormat,0,6) == "<title"
      || substr($ligneFormat,0,7) == "</title"
      || substr($ligneFormat,0,3) == "<h1"
      || substr($ligneFormat,0,4) == "</h1"
      || substr($ligneFormat,0,3) == "<h2"
      || substr($ligneFormat,0,4) == "</h2"
      || substr($ligneFormat,0,3) == "<h3"
      || substr($ligneFormat,0,4) == "</h3"
      || substr($ligneFormat,0,3) == "<h4"
      || substr($ligneFormat,0,4) == "</h4"
      || substr($ligneFormat,0,2) == "<p"
      || ctype_alpha(substr($ligneFormat,0,1)) != False
      || substr($ligneFormat,0,3) == "</p") {
        $newTab[] = $ligne;
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
