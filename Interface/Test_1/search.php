<?php session_start(); ?>
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
			<a href="index.php"><div id="result_title">WOBLE</div></a>
			<form id="search_form_result" action="#" method="post">
				<input class="search_bar_result" name="recherche" type="text" placeholder="Search..."></input>
				<input class="search_button_result" type="submit" value=""></input>
			</form>
		</header>
		<!--Affichage des resultats-->
		<div id="main_block">
			<?php
			for($i=0; $i<10; $i++){
				?>
				<div class="result_block">
					<h3>Titre du site</h3>
					<a class="link_web" href="#">lienVersleSite.com</a>
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
				<?php
			}
			?>
		</div>
	</body>
</html>