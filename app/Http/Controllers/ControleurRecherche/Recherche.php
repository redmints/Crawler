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
    return view('VueRecherche/resultat');
  }
}
