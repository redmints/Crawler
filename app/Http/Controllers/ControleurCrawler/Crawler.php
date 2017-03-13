<?php

namespace App\Http\Controllers\ControleurCrawler;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Website;
use App\Keywords;
use App\Link;
use App\Http\Controllers\Utils;

class Crawler extends Controller
{
  public function getForm() {
    return view('VueCrawler/lancement');
  }

  public function postForm(Request $request) {
    //Récupération des mots à ignorer
    $ignoreMots = array();
    $ignore = DB::table('ignore')->get();
    for($i = 0; $i < count($ignore); $i++) {
      $ignoreMots[$ignore[$i]->mot] = 1;
    }

    set_time_limit(60*60*24*365);
    $url = $request->input('url');
    $nbPages = $request->input('nbPages');
    $pagesCrawl = 0;
    if($url != "reprendre") {
      Crawler::insertUrl($url);
    }
    while((($website = DB::table('website')->where('etat','=','0')->first()) != null) && ($pagesCrawl < $nbPages)) {
      Utils::log("Crawl numero ".$pagesCrawl);
      $url = $website->url;
      $websiteid = $website->id;
      Crawler::go($url, $websiteid, $ignoreMots);
      $pagesCrawl += 1;
    }
    return view('VueCrawler/resultat', compact('pagesCrawl'));
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
      for($i = 1; $i < count($tabBalises); $i++) {
        for($j = 0; $j < count($balises); $j++) {
          if($balises[$j]->balise == $tabBalises[$i]) {
            $importance += $balises[$j]->poids;
          }
        }
      }
      $retour[$mot] = array($importance, $tabBalises[0]);
    }
    return $retour;
  }

  public function parse($url) {
    $page = Crawler::recupPage($url);
    if($page != false) {
      $lignes = Parse::recupLignes($page);
      Utils::log("Peuplement URL");
      Crawler::peuplerUrl($url, $lignes);
      Utils::log("Fin peuplement URL");
      $lignes = Parse::purifier($lignes);
      $title = Parse::getTitle($lignes);
      $balises = Parse::getBalises($lignes);
      $mots = Crawler::getImportance($balises);
      $retour = array($mots, $title);
      return $retour;
    }
    else {
      return false;
    }
  }

  public function insertUrl($url) {
    $website = new Website;
    $websites = DB::table('website')->where('url', $url)->get();
    if(count($websites) == 0) {
      $website->url = $url;
      $website->save();
      return $website->id;
    }
    else {
      return $websites[0]->id;
    }
  }

  public function insertKeyword($mot, $ignoreMots) {
    if(!array_key_exists($mot, $ignoreMots) && strlen($mot) < 27) {
      $keyword = new Keywords;
      $keywords = DB::table('keywords')->where('text', $mot)->get();
      if(count($keywords) == 0) {
        $keyword->text = $mot;
        $keyword->save();
        return $keyword->id;
      }
      else {
        return $keywords[0]->id;
      }
    }
    else {
      return 0;
    }
  }

  public function insertLink($websiteid, $keywordid, $freq, $importance) {
    $link = new Link;
    $link->websiteid = $websiteid;
    $link->keywordid = $keywordid;
    $link->frequency = $freq;
    $link->importance = $importance;
    $link->save();
  }

  public function go($url, $websiteid, $ignoreMots) {
    Utils::log("Début du crawling de ".$url);
    $parse = Crawler::parse($url);
    $title = "";
    if($parse != false) {
      $mots = $parse[0];
      $title = $parse[1];
      Utils::log("Début de l'insertion des mots");
      foreach ($mots as $mot => $tab) {
        $keywordid = Crawler::insertKeyword($mot, $ignoreMots);
        $importance = $tab[0];
        $freq = $tab[1];
        if($keywordid != 0) {
          Crawler::insertLink($websiteid, $keywordid, $freq, $importance);
        }
      }
    }
    $website = Website::where('id', $websiteid)->first();
    $website->title = utf8_encode($title);
    $website->etat = 1;
    $website->save();
    Utils::log("Fin du crawling de ".$url);
  }

  public function peuplerUrl($url, $lignes) {
    $liens = Parse::recupLiens($url, $lignes);
    for($i = 0; $i < count($liens); $i++) {
      Crawler::insertUrl(utf8_encode($liens[$i]));
    }
  }
}
