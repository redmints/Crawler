<?php

namespace App\Http\Controllers\ControleurCrawler;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Utils;

class Parse extends Controller
{
  public static function recupLignes($page) {
    return preg_split('/(<[^>]*[^\/]>)/i', $page, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
  }

  public static function purifier($page) {
    $newTab = array();
    $balises = DB::table('balises')->get();
    for($i = 0; $i < count($page); $i++) {
      $ligne = $page[$i];
      for($j = 0; $j < count($balises); $j++) {
        $balise = $balises[$j]->balise;
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

  public static function getBalises($page) {
    $retour = array();
    $balise = "std";
    for($i = 0; $i < count($page); $i++) {
      $ligne = $page[$i];
      // si c'est une balise ouvrante
      if((substr($ligne,0,1) == "<") && (substr($ligne,0,2) != "</")) {
        $balise = $ligne;
      }
      // si c'est une balise fermante
      else if(substr($ligne,0,2) == "</") {
        $balise = "std";
      }
      else {
        // si c'est du contenu de balise
        $mots = explode(" ", $ligne);
        for($j = 0; $j < count($mots); $j++) {
          $mot = Parse::normaliseMot($mots[$j]);
          $balise = Parse::normaliseBalises($balise);
          if(array_key_exists($mot, $retour)) {
            $tabBalises = $retour[$mot];
            $tabBalises[] = $balise;
            $tabBalises[0] += 1;
            $retour[$mot] = $tabBalises;
          }
          else {
            $retour[$mot] = array("1", $balise);
          }
        }
      }
    }
    return $retour;
  }

  public static function getTitle($page) {
    $title = "";
    for($i = 0; $i < count($page); $i++) {
      $ligne = $page[$i];
      if(substr($ligne,0,6) == "<title") {
        $title = $page[$i+=1];
      }
    }
    return $title;
  }

  public static function normaliseMot($str) {
    $str = strtolower($str);
    $str = preg_replace('~[^\pL\d]+~u', '', $str);
    return $str;
  }

  public static function normaliseBalises($str) {
    if(isset($str)) {
      $str = strtolower($str);
      $str = explode(" ", $str);
      $str = $str[0];
      if(substr($str, strlen($str)-1, strlen($str)) == ">") {
        $str = substr($str, 0, strlen($str)-1);
      }
      else if(substr($str, strlen($str)-2, strlen($str)) == "/>") {
        $str = substr($str, 0, strlen($str)-2);
      }
      if(substr($str, 0, 1) == "<") {
        $str = substr($str, 1, strlen($str));
      }
      else if(substr($str, 0, 2) == "</") {
        $str = substr($str, 2, strlen($str));
      }
      return $str;
    }
  }

  public static function recupLiens($url, $lignes) {
    $retour = array();
    $url = Parse::parseUrl($url);
    for($i = 0; $i < count($lignes); $i++) {
      $ligne = $lignes[$i];
      if((substr($ligne,0,1) == "<") && (stristr($ligne, "href"))) {
        $ligne = str_replace(" ", "", $ligne);
        $pos = strpos($ligne, "href=")+6;
        $ligne = substr($ligne,$pos,strlen($ligne));

        if(strpos($ligne, "\"") !== false) {
          $pos = strpos($ligne, "\"");
        }
        else {
          $pos = strpos($ligne, "'");
        }

        $lien = substr($ligne,0,$pos);
        if(substr($lien,0,1) != "#") {
          if((substr($lien,0,7) == "http://") || (substr($lien,0,8) == "https://")) {
            $retour[] = $lien;
          }
          else if(substr($lien,0,1) == "/") {
            $retour[] = $url[0].$lien;
          }
          else {
            $link = "";
            for($j = 0; $j < count($url)-1; $j++) {
              $link .= $url[$j];
              $link .= "/";
            }
            $link .= $lien;
            $retour[] = $link;
          }
        }
      }
    }
    return $retour;
  }

  public static function parseUrl($url) {
    $url = explode("/", $url);
    $url[0] = $url[0]."//".$url[2];
    unset($url[1]);
    unset($url[2]);
    $url = array_values($url);
    return $url;
  }
}
