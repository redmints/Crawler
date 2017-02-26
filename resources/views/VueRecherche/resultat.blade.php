<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf8">
		<title>Woble</title>
		<meta name="viewport" content="width=device-width">
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="icon" type="image/png" href="img/icon.png">
		<!--Font style-->
		<link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Gloria+Hallelujah" rel="stylesheet">
	</head>
	<body>
		<!--Entete du site-->
		<header>
			<a href="./"><div id="result_title">WOBLE</div></a>
			<form id="search_form_result" action="resultat" method="post">
				{{ csrf_field() }}
				<input class="search_bar_result" name="recherche" type="text" value="<?php echo $keywords ; ?>">
				</input>
				<input class="search_button_result" type="submit" value=""></input>
			</form>
		</header>
		<?php print_r($words) ; ?>
		<!--Affichage des resultats-->
		<div id="main_block"> 
			@foreach($results as $tab)
				<div class="result_block">
					<h3>{{ $tab[0] }}</h3>
					<a class="link_web" href="#">{{ $tab[1] }}</a>
					<span class="resume">
						Qui cum venisset ob haec festinatis itineribus Antiochiam,
						praestrictis palatii ianuis, contempto Caesare, quem videri decuerat,
						ad praetorium cum pompa sollemni perrexit morbosque diu causatus nec
						regiam introiit nec processit in publicum.
						Qui cum venisset ob haec festinatis itineribus Antiochiam,
						praestrictis palatii ianuis, contempto Caesare, quem videri decuerat,
						ad praetorium cum pompa sollemni perrexit morbosque diu causatus nec
						regiam introiit nec processit in publicum.
					</span>
				</div>
			@endforeach
		</div>
	</body>
</html>
