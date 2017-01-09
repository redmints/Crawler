<?php

namespace App\Http\Controllers\ControleurCrawler;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Utils;

class Statistique extends Controller
{
  public function getForm() {
    return view('VueCrawler/statistique');
  }

  public function postForm() {
    return view('VueCrawler/statistique');
  }
}
