<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Crawler extends Controller
{
  public function getForm() {
    return view('lancement');
  }

  public function postForm(Request $request) {
    $url = $request->input('url');
    $nbPages = $request->input('nbPages');
    $page = Crawler::recupPage($url);
    $page = Parse::recupMots($page);
    $page = Parse::purifier($page);
    $mots = Parse::giveImportance($page);
    return view('resultat', compact('url', 'nbPages', 'mots'));
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
