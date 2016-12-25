<?php

namespace App\Http\Controllers\ControleurCrawler;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Crawler extends Controller
{
  public function getForm() {
    return view('VueCrawler/lancement');
  }

  public function postForm(Request $request) {
    $url = $request->input('url');
    $nbPages = $request->input('nbPages');
    $page = Crawler::recupPage($url);
    $lignes = Parse::recupLignes($page);
    $lignes = Parse::purifier($lignes);
    $balises = Parse::getBalises($lignes);
    $mots = Crawler::getImportance($balises);
    return view('VueCrawler/resultat', compact('url', 'nbPages', 'mots'));
  }

  public function recupPage($url) {
    $resource = curl_init();
    curl_setopt($resource, CURLOPT_URL, $url);
    curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
    $page = curl_exec($resource);
    curl_close($resource);
    return $page;
  }

  public function getImportance($mots) {
    foreach ($mots as $mot => $tabBalises) {
      $importance = 0;
      for($i = 0; $i < count($tabBalises); $i++) {
        if($tabBalises[$i] == "std") {
          $importance += 1;
        }
        else if($tabBalises[$i] == "p") {
          $importance += 2;
        }
        else if($tabBalises[$i] == "h4") {
          $importance += 3;
        }
        else if($tabBalises[$i] == "h3") {
          $importance += 4;
        }
        else if($tabBalises[$i] == "h2") {
          $importance += 5;
        }
        else if($tabBalises[$i] == "h1") {
          $importance += 6;
        }
        else if($tabBalises[$i] == "title") {
          $importance += 7;
        }
      }
      $retour[$mot] = $importance;
    }
    return $retour;
  }
}
