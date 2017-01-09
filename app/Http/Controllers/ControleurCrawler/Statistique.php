<?php

namespace App\Http\Controllers\ControleurCrawler;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Utils;

class Statistique extends Controller
{
  public function getForm() {
    $sites = DB::table('website')->where('etat','=','1')->get();
    $nbsites = count($sites);
    $keywords = DB::table('keywords')->orderBy('frequency', 'desc')->take(10)->get();
    $links = array();
    for($i = 1; $i < 6; $i++) {
      $keyword_id = DB::table('link')->where('websiteid','=',$i)->orderBy('frequency', 'desc')->first();
      $retour["frequency"] = $keyword_id->frequency;
      $keyword = DB::table('keywords')->where('id','=',$keyword_id->keywordid)->first();
      $website = DB::table('website')->where('id','=',$keyword_id->websiteid)->first();
      $retour["title"] = $website->title;
      $retour["text"] = $keyword->text;
      $links[] = $retour;
    }
    return view('VueCrawler/statistique', compact('sites', 'keywords', 'links', 'nbsites'));
    //return print_r($links);
  }

  public function postForm() {
    return view('VueCrawler/statistique');
  }
}
