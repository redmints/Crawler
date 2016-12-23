<<<<<<< HEAD:index.php
<?php

	//Fonction qui verifie que l'url a dÃ©ja Ã©tÃ© controlÃ© par le bot
	function url_visit($url)
	{
		//Si le fichier lien_controle existe
		if (file_exists('lien_controle'))
		{
			$lignes = file("lien_controle");
			$fin = false;
			//On verifie que le lien est dans le fichier
			foreach($lignes as $ligne){
				//Si l'url est dans le fichier on retourne true
				if(strstr($ligne,$url)){
					$fin = true;
					break;
				}
			}
			return $fin;
		}

		else
			return false;
	}


	//Fonction verifiant que l'url est dÃ©ja dans le fichier
	function url_present($url)
	{
		//Si le fichier lien_suivre existe
		if (file_exists('lien_a_suivre'))
		{
			$lignes = file("lien_a_suivre");
			$fin = false;
			foreach($lignes as $ligne){
				if(strstr($ligne,$url)){
					$fin = true;
					break;
				}
			}
			return $fin;
		}

		else
			return false;
	}


	//Fonction de rÃ©cupÃ©ration de la page donnÃ© en paramÃ¨tre
	function recuper_info_page($url)
	{
		if(!url_visit($url))
		{
			echo "<p color='red'> PremiÃ¨re Visite </p>";

			// Analyse l'url
			$analyse = parse_url($url);
			$serveur = $analyse['host'];
			$page = $analyse['path'];

			// decoupage des mots de l'url, mots Ã  mettre dans le fichier cvs
			// ehcnainement de explode et de "append" pour crÃ©er la liste des mots

			$array_url = explode('.',$serveur);
			//print_r($array_url);
			$part_url = explode('/',$page);
			foreach ($part_url as $word_url){
				$piece_url = explode('-',$word_url);
				foreach ($piece_url as $morceau){
					$array_url_temp[] = explode('.',$morceau);
				}
			}
			foreach ($array_url_temp as $part){
				if ($part['0'] != ""){
					$array_url[] = $part['0'];
				}
			}

			// Ã©criture des mots dans le fichier csv
			$csv_file = fopen('mot_page.csv','a+');

			foreach ($array_url as $mot){
				$mot_url = array($mot, 0);
				fputcsv($csv_file, $mot_url, '|');
			}

			fclose($csv_file);

			//On affiches les informations de la page en visite.
			echo "Serveur : ".$serveur;
			echo "<br> Page : ".$page;
			echo "<br>";


			///*** CONNEXION CURL = RECUPERATION DE LA PAGE ***\\\
			//Initialisation
			$ch = curl_init($url);

			//Si le fichier existe dÃ©ja on le supprime => Economie de place
			if(file_exists('txt_brut.html')){
				unlink('txt_brut.html');

			}

			//On ouvre en Ã©criture et lecture + crÃ©ation du fichier
			$fichier_brut = fopen('txt_brut.html','a+');
			//On redirige le rÃ©sultat sur notre fichier
			curl_setopt($ch, CURLOPT_FILE, $fichier_brut);

			//On indique que nous ne voulons pas les HEADER
			curl_setopt($ch, CURLOPT_HEADER, 0);

			// exÃ©cution de curl
			curl_exec($ch);

			// fermeture de la session curl
			curl_close($ch);

			//fermeture de notre fichier
			fclose($fichier_brut);

			// appel de la fonction decoupe, qui va crÃ©er un fichier csv avec les mots de la page (et leur index)

			include 'decoupe.php';

			 //On dÃ©clenche alors l'extraction des liens
			extraction_lien($fichier_brut,$serveur);

			//On enregistre l'URL comme visitÃ©e si il n'est pas connue
			if(!url_visit($url))
			{
				$visite = fopen('lien_controle','a');
				$ajout_fichier = $url."\n";
				fputs($visite,$ajout_fichier);
				fclose($visite);

			}


		}
		else
			echo "<p color='red'> URL connue </p>";

	}

	//Fonction d'extraction des liens
	function extraction_lien($fichier,$prefixe)
	{
		$html_brut = file_get_contents('txt_brut.html');
		//On parse et rÃ©cupÃ¨re les liens
		preg_match_all('#"/?[a-zA-Z0-9_./-]+.(php|html|htm)"#', $html_brut, $liens_extraits);


		if (file_exists('liens_a_suivre')) {

		$fp_fichier_liens = fopen('liens_a_suivre', 'a');
		// Boucle pour enregistrer les liens dans le fichier
		// On recharge Ã  chaque tour l'Ã©lÃ©ment afin de supprimer les doublons sur la mÃªme page.
		foreach ($liens_extraits[0] as $element) {
			$lien_tempo = file_get_contents('liens_a_suivre');
			// on enlÃ¨ve les "" qui entourent les liens
			 $element = preg_replace('#"#', '', $element);
			 //On prÃ©pare ce qu'on veut ajouter au fichier
			 $follow_url = $prefixe.$element."\n";

			$pattern = '#'.$follow_url.'#';
			//Si le lien n'est pas dÃ©ja prÃ©sent dans le fichier on l'ajout dans ce dernier
			if (!preg_match($pattern, $lien_tempo)) {
				fputs($fp_fichier_liens, $follow_url);
			}


		}
	}

	  // si le fichier contenant les liens n'existe pas
	  else {
		// on le crÃ©Ã©
		$fp_fichier_liens = fopen('liens_a_suivre', 'a');
		// puis on fait une boucle pour enregistrer tous les liens ds 1 fichier
		foreach ($liens_extraits[0] as $element) {
			$element = preg_replace('#"#', '', $element);
			$follow_url = $prefixe.$element;
			if(!url_present($follow_url))
			{
				$follow_url = $prefixe.$element."\n";
				fputs($fp_fichier_liens, $follow_url);
			}

		}
	  }

	  // fermeture fu fichier contenant les liens
	  fclose($fp_fichier_liens);

}

?>

<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="default.css" />
		<title>- Bot RÃ©cupÃ©ration -</title>
	</head>
	<body>
	<h1> RÃ©cupÃ©ration des liens d'une page donnÃ©e </h1>

	Veuillez rentrer un lien ici :
	<form method="POST" action="index.php" class="formulaire">
		Page (URL) : <input type="text" name="url" required>
		<br>
		<input type="submit" name="button" value="Ok">
	</form>
	<?php
		if(isset($_POST['button']))
		{
			$url = $_POST['url'];
			recuper_info_page($url);

		}
	?>
	</body>
</html>
=======
<?php   
	
	//Fonction qui verifie que l'url a déja été controlé par le bot
	function url_visit($url)
	{
		//Si le fichier lien_controle existe
		if (file_exists('lien_controle'))
		{
			$lignes = file("lien_controle");
			$fin = false;
			//On verifie que le lien est dans le fichier
			foreach($lignes as $ligne){
				//Si l'url est dans le fichier on retourne true
				if(strstr($ligne,$url)){ 
					$fin = true;
					break;
				}
			}
			return $fin;
		}
		
		else
			return false;
	}
	
	
	//Fonction verifiant que l'url est déja dans le fichier
	function url_present($url)
	{
		//Si le fichier lien_suivre existe
		if (file_exists('lien_a_suivre'))
		{
			$lignes = file("lien_a_suivre");
			$fin = false;
			foreach($lignes as $ligne){
				if(strstr($ligne,$url)){ 
					$fin = true;
					break;
				}
			}
			return $fin;
		}
		
		else
			return false;
	}
		
	
	//Fonction de récupération de la page donné en paramètre
	function recuper_info_page($url)
	{
		if(!url_visit($url))
		{
			echo "<p color='red'> Première Visite </p>";
			
			// Analyse l'url
			$analyse = parse_url($url);
			$serveur = $analyse['host'];
			$page = $analyse['path'];
			
			// decoupage des mots de l'url, mots à mettre dans le fichier cvs
			// ehcnainement de explode et de "append" pour créer la liste des mots
			
			$array_url = explode('.',$serveur);
			//print_r($array_url);
			$part_url = explode('/',$page);
			foreach ($part_url as $word_url){
				$piece_url = explode('-',$word_url);
				foreach ($piece_url as $morceau){
					$array_url_temp[] = explode('.',$morceau);
				}
			}
			foreach ($array_url_temp as $part){
				if ($part['0'] != ""){
					$array_url[] = $part['0'];
				}
			}
			
			// écriture des mots dans le fichier csv
			$csv_file = fopen('mot_page.csv','a+');
			
			foreach ($array_url as $mot){
				$mot_url = array($mot, 0);
				fputcsv($csv_file, $mot_url, '|');
			}
			
			fclose($csv_file);
			
			//On affiches les informations de la page en visite.
			echo "Serveur : ".$serveur;
			echo "<br> Page : ".$page;
			echo "<br>";
			
			
			///*** CONNEXION CURL = RECUPERATION DE LA PAGE ***\\\
			//Initialisation
			$ch = curl_init($url);
			
			//Si le fichier existe déja on le supprime => Economie de place
			if(file_exists('txt_brut.html')){
				unlink('txt_brut.html');
			
			}
			
			//On ouvre en écriture et lecture + création du fichier
			$fichier_brut = fopen('txt_brut.html','a+');
			//On redirige le résultat sur notre fichier
			curl_setopt($ch, CURLOPT_FILE, $fichier_brut);
					
			//On indique que nous ne voulons pas les HEADER
			curl_setopt($ch, CURLOPT_HEADER, 0);
					
			// exécution de curl
			curl_exec($ch);
					
			// fermeture de la session curl
			curl_close($ch);
		   
			//fermeture de notre fichier
			fclose($fichier_brut);
			
			// appel de la fonction decoupe, qui va créer un fichier csv avec les mots de la page (et leur index)
			
			include 'decoupe.php';
			  
			 //On déclenche alors l'extraction des liens 
			extraction_lien($fichier_brut,$serveur);
			
			//On enregistre l'URL comme visitée si il n'est pas connue
			if(!url_visit($url))
			{
				$visite = fopen('lien_controle','a');
				$ajout_fichier = $url."\n";
				fputs($visite,$ajout_fichier);
				fclose($visite);
				
			}
			
		
		}	
		else
			echo "<p color='red'> URL connue </p>";
			
	}
		
	//Fonction d'extraction des liens
	function extraction_lien($fichier,$prefixe)
	{
		$html_brut = file_get_contents('txt_brut.html');
		//On parse et récupère les liens
		preg_match_all('#"/?[a-zA-Z0-9_./-]+.(php|html|htm)"#', $html_brut, $liens_extraits);
		
		
		if (file_exists('liens_a_suivre')) {		
		
		$fp_fichier_liens = fopen('liens_a_suivre', 'a');
		// Boucle pour enregistrer les liens dans le fichier
		// On recharge à chaque tour l'élément afin de supprimer les doublons sur la même page.
		foreach ($liens_extraits[0] as $element) {		
			$lien_tempo = file_get_contents('liens_a_suivre');
			// on enlève les "" qui entourent les liens
			 $element = preg_replace('#"#', '', $element);
			 //On prépare ce qu'on veut ajouter au fichier
			 $follow_url = $prefixe.$element."\n";
							
			$pattern = '#'.$follow_url.'#';
			//Si le lien n'est pas déja présent dans le fichier on l'ajout dans ce dernier
			if (!preg_match($pattern, $lien_tempo)) {
				fputs($fp_fichier_liens, $follow_url);
			}
			
		
		}
	}
			
	  // si le fichier contenant les liens n'existe pas 
	  else {
		// on le créé
		$fp_fichier_liens = fopen('liens_a_suivre', 'a');
		// puis on fait une boucle pour enregistrer tous les liens ds 1 fichier
		foreach ($liens_extraits[0] as $element) {
			$element = preg_replace('#"#', '', $element);
			$follow_url = $prefixe.$element;
			if(!url_present($follow_url))
			{
				$follow_url = $prefixe.$element."\n";
				fputs($fp_fichier_liens, $follow_url);
			}
				
		}
	  }
			
	  // fermeture fu fichier contenant les liens
	  fclose($fp_fichier_liens);
		
}
	
?>

<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="default.css" />
		<title>- Bot Récupération -</title>				
	</head>
	<body>
	<h1> Récupération des liens d'une page donnée </h1>
	
	Veuillez rentrer un lien ici :
	<form method="POST" action="index.php" class="formulaire">	
		Page (URL) : <input type="text" name="url" required>
		<br>
		<input type="submit" name="button" value="Ok">
	</form> 
	<?php
		if(isset($_POST['button']))
		{
			$url = $_POST['url'];
			recuper_info_page($url);
			 
		}		
	?>
	</body>
</html>
>>>>>>> f27fc8d3d0d46522e86a3a048dd0d27753c573a5:Crawler/index.php
