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
	$lastsites = DB::table('website')->where('etat','=','1')->orderBy('id', 'desc')->take(10)->get();
	foreach($lastsites as $sites) {
		//On recupere l'id du mot le plus important dans le site
		$keyword_id = DB::table('link')->where('websiteid','=',$sites->id)->orderBy('importance', 'desc')->first();
		$keyword = DB::table('keywords')->where('id','=',$keyword_id->keywordid)->first(); //le mot en question
		$website = DB::table('website')->where('id','=',$keyword_id->websiteid)->first(); //le site en question
		$retour["title"] = $website->title;
		$retour["text"] = $keyword->text;
		$retour["importance"] = $keyword_id->importance;
		$links[] = $retour; //On le met en tab
	}
	
	return view('VueCrawler/statistique', compact('sites', 'nbsites', 'lastsites', 'links'));
	//return view('VueCrawler/statistique');
  }
}
