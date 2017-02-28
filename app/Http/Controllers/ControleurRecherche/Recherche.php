<?php

namespace App\Http\Controllers\ControleurRecherche;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use PDO;

class Recherche extends Controller
{
  public function getForm() {
    return view('VueRecherche/recherche');
  }

  public function postForm(Request $request) {

    //temporaire, permet de retourner un tableau au lieu d'un objet
    DB::setFetchMode(PDO::FETCH_ASSOC);

	$keywords = $request->input('recherche');
	$words = $parts = explode(" ", $keywords);

    $words_id = array();
    $related_website_ids = array();
    $results = array();
    $q = array();

    foreach($words as $word)
    {
        $words_id[] = DB::select("SELECT ID FROM KEYWORDS WHERE TEXT='".$word."'")[0]["ID"];
    }

    $sqlformat1 = "(";

    $index = 1;
    foreach($words_id as $word_id)
    {
        $sqlformat1 .= "'".$word_id."'";
        if ($index != sizeof($words_id))
            $sqlformat1 .= ",";
        $index++;
    }
    $sqlformat1 .= ")";

    $query = "SELECT DISTINCT WEBSITEID FROM LINK WHERE KEYWORDID in ".$sqlformat1." AND IMPORTANCE > 1";
    $q[] = $query;
    $results = DB::select($query);

    foreach($results as $result)
    {
        $related_website_ids[] = $result['WEBSITEID'];
    }

    $sqlformat = "(";

    $index = 1;
    foreach($related_website_ids as $related_website_id)
    {
        $sqlformat .= "'".$related_website_id."'";
        if ($index != sizeof($related_website_ids))
            $sqlformat .= ",";
        $index++;
    }
    $sqlformat .= ")";

    $query = "SELECT WEBSITEID, SUM(IMPORTANCE) FROM ( SELECT * FROM LINK WHERE KEYWORDID IN ".$sqlformat1." AND IMPORTANCE > 1 ) temp GROUP BY WEBSITEID ORDER BY SUM(IMPORTANCE) DESC";
    $q[] = $query;
    $results = DB::select($query);

    /* données de test */
	$data = array();

	$data[] = array("Titre bidon 1", "http://bidonville.com");
	$data[] = array("Titre bidon 2", "http://feezzef.com");
	$data[] = array("Titre bidon 3", "http://lomaoe.com");

	$tab = array("data" => $data, "keywords" => $keywords, "words" => $words, "related_website_ids" => $related_website_ids, "results" => $results, "queries" => $q);

    //remise en mode objet
    DB::setFetchMode(PDO::FETCH_CLASS);

	return view('VueRecherche/resultat')->with($tab);
  }
}
