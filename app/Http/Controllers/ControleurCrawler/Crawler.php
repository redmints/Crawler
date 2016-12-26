<?php

namespace App\Http\Controllers\ControleurCrawler;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
    $retour = array();
    $balises = DB::table('balises')->get();
    foreach ($mots as $mot => $tabBalises) {
      $importance = 0;
      for($i = 0; $i < count($tabBalises); $i++) {
        for($j = 0; $j < count($balises); $j++) {
          if($balises[$j]->balise == $tabBalises[$i]) {
            $importance += $balises[$j]->poids;
          }
        }
      }
      $retour[$mot] = $importance;
    }
    return $retour;
  }
}
