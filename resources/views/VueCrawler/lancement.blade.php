@extends('VueCrawler/templateCrawler')

@section('titre')
  Crawler - Lancement
@endsection

@section('contenu')
	<a href="./"><h1>WOBLE</h1></a>
	<div class="formulaire">
		<h2>Lancement du crawler</h2>
		<p>
			{!! Form::open(['url' => 'crawler']) !!}
				{{ csrf_field() }}
				{!! Form::label('url', 'Entrez une url de départ : ') !!} <br>
				{!! Form::text('url', '', ['required' => 'required']) !!} <br>
				{!! Form::label('nbPages', 'Entrez un nombre de pages à crawler : ') !!} <br>
				{!! Form::number('nbPages', '', ['required' => 'required']) !!}<br><br>
				{!! Form::submit('Lancer', ['class'=>'red_button']) !!}
			{!! Form::close() !!}
		</p>
	</div>
@endsection
