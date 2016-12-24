@extends('VueCrawler/templateCrawler')

@section('titre')
  Crawler - Resultats
@endsection

@section('contenu')
	<a href="./crawler"><h1>WOBLE</h1></a>
	<div class="bloc">
		<h2>Informations</h2>
		<p>
			URL de départ : {{$url}}
			<br>
			Nombre de pages à scanner : {{$nbPages}}
		</p>
	</div>

	<div class="bloc">
		<h2>Résultats</h2>
		<p>
			@foreach($mots as $mot)
			{{$mot}}<br><br>
			@endforeach
		</p>
	</div>
@endsection
