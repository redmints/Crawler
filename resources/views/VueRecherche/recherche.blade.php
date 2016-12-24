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
		<div id="main_box">
			<a href="./"><h1 id="main_title">WOBLE</h1></a>
			<form id="search_form" action="resultat" method="post">
				{{ csrf_field() }}
				<input class="search_bar" name="recherche" type="text" placeholder="Rechercher...">
				<input class="search_button" type="submit" value="">
			</form>
		</div>
	</body>
</html>
