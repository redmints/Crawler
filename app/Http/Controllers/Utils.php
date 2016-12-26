<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Utils extends Controller
{
  public static function log($str) {
    $date = date("d/m/Y");
    $heure = date("H:i:s");

    $str = "[".$date." ".$heure."] ".$str."\n";

    $monfichier = fopen('logs.txt', 'a+');
    fputs($monfichier, $str);
    fclose($monfichier);
  }
}
