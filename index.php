<?php   
	
	function url_visit($url)
	{
	
		if (file_exists('lien_controle'))
		{
			$lignes = file("lien_controle");
			$fin = true;

			foreach($lignes as $ligne){
				if(strstr($ligne,$url)){ 
					$fin = false;
					break;
				}
			}

			return $fin;
		}
		
		else
			return false;
	}

	function recuper_info_page($url)
	{
		// Analyse l'url
        $analyse = parse_url($url);
        $serveur = $analyse['host'];
        $page = $analyse['path'];
		
		//On affiches les informations de la page en visite.
		echo "Serveur : ".$serveur;
		echo "<br> Page : ".$page;
		echo "<br>";
		
		
		///*** CONNEXION CURL = RECUPERATION DE LA PAGE ***\\\
		//Initialisation
		$ch = curl_init($url);
		
		//Si le fichier existe déja on le supprime => Economie de place
		if(file_exists('txt_brut')){
			unlink('txt_brut');
		
		}
		
		//On ouvre en écriture et lecture + création du fichier
		$fichier_brut = fopen('txt_brut','a+');

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
		  
		 //On déclenche alors l'extraction des liens 
		extraction_lien($fichier_brut,$serveur);
		
		//On enregistre l'URL comme visitée
		$visite = fopen('lien_controle','a');
		$ajout_fichier = $url;
		fputs($visite,$ajout_fichier);
		fclose($visite);
		
			  
			
	}
		
		
	function extraction_lien($fichier,$prefixe)
	{
		$html_brut = file_get_contents('txt_brut');
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
			$follow_url = $prefixe.$element."\n";
			fputs($fp_fichier_liens, $follow_url);
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
			 
			 if(url_visit($url))
				 echo '<br> URL Déja Visitée';
			 else
				 echo '<br> Première Visite';
	
			 
		}		
	?>
	</body>
</html>