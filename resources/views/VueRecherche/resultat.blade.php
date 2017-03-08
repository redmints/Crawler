<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf8">
		<title>Woble</title>
		<meta name="viewport" content="width=device-width">
		<link href="/Crawler/public/style.css" rel="stylesheet" type="text/css">
		<link rel="icon" type="image/png" href="img/icon.png">
		<!--Font style-->
		<link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Gloria+Hallelujah" rel="stylesheet">
	</head>
	<body>
		<!--Entete du site-->
		<header>
			<a href="/Crawler/public"><div id="result_title">WOBLE</div></a>
			<form id="search_form_result" action="/Crawler/public/resultat" method="post">
				{{ csrf_field() }}
				<input class="search_bar_result" name="recherche" type="text" value="<?php echo $keywords ; ?>">
				</input>
				<input class="search_button_result" type="submit" value=""></input>
			</form>
		</header>
		<!--Affichage des resultats-->
		<?php
			echo "<br/>";
			echo "Nombre de sites contenant au moins un des mot-clés : ";
			echo $count;
			echo "<br/>";
			echo "Page actuelle : ";
			echo $current_page;
			echo "<br/>";
			echo "Start : ".$start;
			echo "<br/>";

			echo "Liste des résultats par ordre de pertinence : <br/>";
			foreach($results as $a)
			{
				print_r($a);
				echo "<br/>";
			}
			echo "<br/>";
			echo "Liste des requêtes SQL : <br/>";
			foreach($queries as $query)
			{
				echo $query;
				echo "<br/>";
			}
		?>
	</body>
</html>
