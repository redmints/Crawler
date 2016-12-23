<?php
	
	function decoupe(){
		//Load the HTML page
		$html = file_get_contents('txt_brut.html');
		//Create a new DOM document
		$dom = new DOMDocument;
		 
		//Parse the HTML. The @ is used to suppress any parsing errors
		//that will be thrown if the $html string isn't valid XHTML.
		@$dom->loadHTML($html);
		
		//Get all links. You could also use any other tag name here,
		//like 'img' or 'table', to extract other tags.
		
		$title = $dom->getElementsByTagName('title');
		$body = $dom->getElementsByTagName('body');
		$h1s = $dom->getElementsByTagName('h1');
		$h2s = $dom->getElementsByTagName('h2');
		$h3s = $dom->getElementsByTagName('h3');
		$h4s = $dom->getElementsByTagName('h4');
		$bs = $dom->getElementsByTagName('b');
		$is = $dom->getElementsByTagName('i');
		
		$script = $dom->getElementsByTagName('script');
		$video = $dom->getElementsByTagName('video');
		
		$array_title = array();
		$array_body = array();
		$array_h1 = array();
		$array_h2 = array();
		$array_h3 = array();
		$array_h4 = array();
		$array_b = array();
		$array_i = array();
		
		foreach ($title as $word_title){
			$array_title = explode(" ",$word_title->nodeValue);
		}
		
		foreach ($h1s as $h1){
			$part_h1 = explode(" ",$h1->nodeValue);
			foreach($part_h1 as $word_h1){
				$array_h1[] = $word_h1;
			}
		}
		
		foreach ($h2s as $h2){
			$part_h2 = explode(" ",$h2->nodeValue);
			foreach ($part_h2 as $word_h2){
				$array_h2[] = $word_h2;
			}
		}
		
		foreach ($h3s as $h3){
			$part_h3 = explode(" ",$h3->nodeValue);
			foreach ($part_h3 as $word_h3){
				$array_h3[] = $word_h3;
			}
		}
		
		foreach ($h4s as $h4){
			$part_h4 = explode(" ",$h4->nodeValue);
			foreach ($part_h4 as $word_h4){
				$array_h4[] = $word_h4;
			}
		}
		
		foreach ($bs as $b){
			$part_b = explode(" ",$b->nodeValue);
			foreach ($part_b as $word_b){
				$array_b[] = $word_b;
			}
		}
		
		foreach ($is as $i){
			$part_i = explode(" ",$i->nodeValue);
			foreach ($part_i as $word_i){
				$array_i[] = $word_i;
			}
		}
		
		foreach ($h1s as $word_h1){
			$remove_list[] = $word_h1;
		}
		foreach ($h2s as $word_h2){
			$remove_list[] = $word_h2;
		}
		foreach ($h3s as $word_h3){
			$remove_list[] = $word_h3;
		}
		foreach ($h4s as $word_h4){
			$remove_list[] = $word_h4;
		}
		foreach ($bs as $bs){
			$remove_list[] = $bs;
		}
		foreach ($is as $is){
			$remove_list[] = $is;
		}
		foreach ($script as $word_script){
			$remove_list[] = $word_script;
		}
		foreach ($video as $word_video){
			$remove_list[] = $word_video;
		}
		
		if (isset($remove_list)){
			foreach ($remove_list as $remove_word){
				$remove_word->parentNode->removeChild($remove_word);
			}
		}
		
		
		foreach ($body as $word_body){
			$part_body = explode(" ",$word_body->nodeValue);
			foreach($part_body as $word_body){
				$array_body[] = $word_body;
			}
		}
		
		/* Insertion de sous tableaux de taille 2 dans un tableau qui servira pour les insertions dans la base de données
		
		Valeurs dans la base de données
		
		Title = 1
		Body = 2
		H1 = 3
		H2 = 4
		H3 = 5
		H4 = 6
		B = 7
		I = 8
		
		*/
		
		$mot_page = array();
		
		foreach ($array_title as $word_title){
			if ($word_title != ""){
				$mot_page[] = array(validation_mot($word_title), 1);
			}
		}
		
		foreach ($array_body as $word_body){
			if ($word_body != ""){
				$mot_page[] = array(validation_mot($word_body), 2);
			}
		}
		
		foreach ($array_h1 as $word_h1){
			if ($word_h1 != ""){
				$mot_page[] = array(validation_mot($word_h1), 3);
			}
		}
		
		foreach ($array_h2 as $word_h2){
			if ($word_h2 != ""){
				$mot_page[] = array(validation_mot($word_h2), 4);
			}
		}
		
		foreach ($array_h3 as $word_h3){
			if ($word_h3 != ""){
				$mot_page[] = array(validation_mot($word_h3), 5);
			}
		}
		
		foreach ($array_h4 as $word_h4){
			if ($word_h4 != ""){
				$mot_page[] = array(validation_mot($word_h4), 6);
			}
		}
		
		foreach ($array_b as $word_b){
			if ($word_b != ""){
				$mot_page[] = array(validation_mot($word_b), 7);
			}
		}
		
		foreach ($array_i as $word_i){
			if ($word_i != ""){
				$mot_page[] = array(validation_mot($word_i), 8);
			}
		}
		
		$csv_file = fopen('mot_page.csv', 'w+');
		
		foreach($mot_page as $line){
			if ($line[0] != "")
				fputcsv($csv_file, $line, '|');
		}
		
		fclose($csv_file);
		
	}
	
	function validation_mot($mot){
		
		$mot = str_split($mot);
		
		$char_non_valide = array('"','-','?','.',';',':','(',')',',','_','/','|','=','^','#','%','~',"\n",'\'','!','\\','{','}','*');
		$mot_valide = "";
		
		foreach($mot as $char){
			if (!in_array($char, $char_non_valide)){
				$mot_valide = $mot_valide.$char;
			}
		}
		
		return $mot_valide;
	}
	
	decoupe();
	
?>