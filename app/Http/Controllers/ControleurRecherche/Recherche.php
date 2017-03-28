<?php

namespace App\Http\Controllers\ControleurRecherche;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use PDO;
use App\Http\Controllers\Utils;

class Recherche extends Controller
{
    public function getForm() {
        return view('VueRecherche/recherche');
    }

    public function postForm(Request $request) {
		if($keywords == "")
			$keywords = " ";
        $keywords = str_replace(" ", "+", $request->input('recherche'));
        return redirect("resultat/".$keywords."/1");
    }

    public function getResults($keywords, $start) {
        $tab = $this->recherche($keywords, $start);

        return view('VueRecherche/resultat', compact('tab', 'keywords'));
    }

    public function recherche($parkeywords, $start = 1)
    {
        //temporaire, permet de retourner un tableau au lieu d'un objet
        DB::setFetchMode(PDO::FETCH_ASSOC);

        $keywords = str_replace("+", " ", $parkeywords);
        $words = $parts = explode(" ", $keywords);

        $words_id = array();
        $related_website_ids = array();
        $results = array();
        $q = array();

        foreach($words as $word)
        {
            $query = "SELECT ID FROM keywords WHERE TEXT='".$word."'";
            $result = DB::select($query);
            $q[] = $query;
            if (sizeof($result) > 0)
                $words_id[] = $result[0]["ID"];
        }

        //si la BDD ne contient aucun mot-clé entré, on s'arrête ici
        if(sizeof($words_id) == 0)
            return $tab = array("keywords" => $keywords, "queries" => $q, "current_page" => 1, "count" => 0, "results" => False);

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

        $query = "SELECT DISTINCT websiteid FROM link WHERE keywordid in ".$sqlformat1." AND importance > 1";
        $q[] = $query;
        $results = DB::select($query);

        $count = sizeof($results);
        $nbrpages = ceil(sizeof($results)/10);

        foreach($results as $result)
        {
            $related_website_ids[] = $result['websiteid'];
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

        if ($start < 1)
            $start = 1;

        $query = "SELECT websiteid, SUM(importance) FROM ( SELECT * FROM link WHERE keywordid IN ".$sqlformat1." AND importance > 1 ) temp GROUP BY websiteid ORDER BY SUM(importance) DESC LIMIT ".(($start*10)-10).",10";
        $q[] = $query;
        $results = DB::select($query);

        $tab = array("keywords" => $keywords, "words" => $words, "queries" => $q, "results" => $results, "nbrpages" => $nbrpages, "count" => $count, "current_page" => $start);
        $return = array();
        for($i = 0; $i < count($tab["results"]); $i++) {
            $retour = array();
            $website = $tab["results"][$i];
            $websiteid = $website["websiteid"];
            $websitename = DB::table('website')->where('id', $websiteid)->first();
            $retour["title"] = utf8_decode($websitename["title"]);
            $retour["url"] = $websitename["url"];
            $return[] = $retour;
        }

        //remise en mode objet
        DB::setFetchMode(PDO::FETCH_CLASS);

        $tab["return"] = $return;
        return $tab;
    }
}
