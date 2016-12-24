@extends('VueCrawler/templateCrawler')

@section('titre')
  Crawler - Resultats
@endsection

@section('contenu')
<<<<<<< HEAD
    L'url est {{$url}}
    <br>
    Le nombre de pages est {{$nbPages}}
    <br><br>

    @foreach($mots as $mot)
     {{$mot}}<br>
    @endforeach
=======
	<a href="./crawler"><h1>WOBLE</h1></a>
	<div class="bloc">
		<h2>Informations</h2>
		<p>
			URL de départ : {{$url}}
			<br>
			Nombre de pages à scanner : {{$nbPages}}
		</p>
	</div>
>>>>>>> 45b5611bec87a5b1291bfbf2f99c6bf188a20b6f

	<div class="bloc">
		<h2>Résultats</h2>
		<p>
			@foreach($mots as $mot)
			{{$mot}}<br><br>
			@endforeach
		</p>
	</div>
@endsection
