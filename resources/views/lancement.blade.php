@extends('templateCrawler')

@section('titre')
  Crawler
@endsection

@section('contenu')
    {!! Form::open(['url' => 'crawler']) !!}
        {!! Form::label('url', 'Entrez une url de départ : ') !!}
        {!! Form::text('url') !!}
        {!! Form::label('nbPages', 'Entrez un nombre de pages à crawler : ') !!}
        {!! Form::text('nbPages') !!}
        {!! Form::submit('Go !') !!}
    {!! Form::close() !!}
@endsection
