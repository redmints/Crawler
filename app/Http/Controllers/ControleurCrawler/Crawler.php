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
    $mots = Parse::getImportance($lignes);
    //return view('VueCrawler/resultat', compact('url', 'nbPages', 'mots'));
    return print_r($mots);
  }

  public function recupPage($url) {
    $resource = curl_init();
    curl_setopt($resource, CURLOPT_URL, $url);
    curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
    $page = curl_exec($resource);
    curl_close($resource);
    return $page;
  }
}
