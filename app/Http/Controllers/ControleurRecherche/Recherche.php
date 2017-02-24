<?php

namespace App\Http\Controllers\ControleurRecherche;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Recherche extends Controller
{
  public function getForm() {
    return view('VueRecherche/recherche');
  }

  public function postForm(Request $request) {
	
	$data = array();
	$data[] = array("Titre bidon 1", "http://bidonville.com");
	$data[] = array("Titre bidon 2", "http://feezzef.com");
	$data[] = array("Titre bidon 3", "http://lomaoe.com");
	
	return view('VueRecherche/resultat')->with($data);
  }
}
