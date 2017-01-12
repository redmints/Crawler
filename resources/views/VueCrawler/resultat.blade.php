@extends('VueCrawler/templateCrawler')

@section('titre')
  Crawler - Resultats
@endsection

@section('scripts')
@endsection

@section('contenu')
	<a href="./crawler"><h1>WOBLE</h1></a>

	<div class="bloc">
		<h2>Résultats</h2>
		<p>
			Le crawl s'est bien déroulé<br>
			{{$pagesCrawl}} pages traitées
		</p>
		<input class="red_button" type="button" value="Statistiques" onclick="self.location.href='stats'">
	</div>
@endsection
