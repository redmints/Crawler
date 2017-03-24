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
        $keywords = str_replace(" ", "+", $request->input('recherche'));
        return redirect("resultat/".$keywords."/0");
    }

    public function getResults($keywords, $start) {
        $tab = $this->recherche($keywords, $start);

        return view('VueRecherche/resultat')->with($tab);
    }

    public function recherche($parkeywords, $start = 0)
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
            $result = DB::select("SELECT ID FROM keywords WHERE TEXT='".$word."'");
            if (sizeof($result) > 0)
                $words_id[] = $result[0]["ID"];
        }

        //si la BDD ne contient aucun mot-clé entré, on s'arrête ici
        if(sizeof($words_id) == 0)
            return $tab = array("keywords" => $keywords, "results" => False);

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

        if ($start < 0)
            $start = 0;

        $query = "SELECT websiteid, SUM(importance) FROM ( SELECT * FROM link WHERE keywordid IN ".$sqlformat1." AND importance > 1 ) temp GROUP BY websiteid ORDER BY SUM(importance) DESC LIMIT ".$start.",10";
        $q[] = $query;
        $results = DB::select($query);

        if($start < 10)
            $current_page = 1;
        else
        {
            $current_page = ceil(($start+1)/10);
        }

        $tab = array("keywords" => $keywords, "words" => $words, "related_website_ids" => $related_website_ids, "results" => $results, "queries" => $q, "count" => $count, "current_page" => $current_page, "start" => $start);

        //remise en mode objet
        DB::setFetchMode(PDO::FETCH_CLASS);

        return $tab;
    }
}
