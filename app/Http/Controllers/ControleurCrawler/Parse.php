<?php

namespace App\Http\Controllers\ControleurCrawler;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Parse extends Controller
{
  public static function recupLignes($page) {
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

  public static function getImportance($page) {
    $retour = array();
    $balise = "STD";
    for($i = 0; $i < count($page); $i++) {
      $ligne = $page[$i];
      // si c'est une balise ouvrante
      if((substr($ligne,0,1) == "<") && (substr($ligne,0,2) != "</")) {
        $balise = $ligne;
      }
      // si c'est une balise fermante
      else if(substr($ligne,0,2) == "</") {
        $balise = "STD";
      }
      else {
        // si c'est du contenu de balise
        $mots = explode(" ", $ligne);
        for($j = 0; $j < count($mots); $j++) {
          $mot = Parse::normalise($mots[$j]);
          if(array_key_exists($mot, $retour)) {
            $tabBalises = $retour[$mot];
            if(!in_array($balise, $tabBalises)) {
              $tabBalises[] = $balise;
            }
            $retour[$mot] = $tabBalises;
          }
          else {
            $retour[$mot] = array($balise);
          }
        }
      }
    }
    return $retour;
  }

  public static function normalise($str) {
    $str = strtolower($str);
    $str = preg_replace('~[^\pL\d]+~u', '', $str);
    return $str;
  }
}
