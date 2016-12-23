@extends('VueCrawler/templateCrawler')

@section('titre')
  Crawler
@endsection

@section('contenu')
    L'url est {{$url}}
    <br>
    Le nombre de pages est {{$nbPages}}
    <br><br>

    @foreach($mots as $mot)
     {{$mot}}<br><br><br>
    @endforeach

@endsection
