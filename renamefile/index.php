<?php
	
	$tmp_url = './tmp/pending/';
	$target_url = './originals/1/';
	$sanctuary_file = 'perfil.txt';

	$image_names = _renameDuplicatedFiles($tmp_url, $target_url, $sanctuary_file);

	print_r($image_names);
	
	function _renameDuplicatedFiles($tmp_url, $target_url,$sanctuary_file = null){

		$tmp = scandir($tmp_url);
		$target = scandir($target_url);
		// array slice remove . e .. da lista
		
		$image_names = [];

		$duplicates = array_slice( array_intersect($tmp, $target), 2 );

		$sanctuary_newname = "";

		

		foreach( $duplicates as $file ){
			$info = pathinfo($file);
			$flag = 1;

			$pending_filename = $tmp_url.$file;
			$sanctuary_filename = $target_url.$file;

			while(file_exists($pending_filename) || file_exists($sanctuary_filename)){

				// pega nome do arquivo
				$file_name =  basename($file,'.'.$info['extension']);

				// pega a extensão do arquivo
				$file_explode = explode('.', $file);
				$ext = end($file_explode);

				//Nome antigo do arquivo que será renomeado
				$old_name = $tmp_url.$file;

				//Novo Nome
				$new_name = $file_name."_".$flag.".".$ext;

				//Nome do arquivo pendente e do Santuário
				$pending_filename = $tmp_url.$new_name;
				$sanctuary_filename = $target_url.$new_name;

				$flag++;
			}
		
			//Verifica se o arquivo é da galeria ou da foto principal do santuário 
			if(!empty($sanctuary_file) && $sanctuary_file === $file){
				$image_names["sanctuary"] = $new_name;

			} 

			//PRINT PARA TESTE -- Descomentar se for testar
			/*echo "<br>Antigo Nome<br>";
			print_r($old_name);
			echo "<br>Novo Nome<br>";
			print_r($pending_filename);
			echo "<br>rename de ".$old_name." para ".$pending_filename."<br>";
			*/

			rename($old_name,$pending_filename);
		}

		$after_rename = scandir($tmp_url);
		$image_names["gallery"] = array_slice( $after_rename, 2 );

		if(!empty($image_names["sanctuary"])){
			$index = array_keys($image_names["gallery"],$image_names["sanctuary"]);
		} else {
			$index = array_keys($image_names["gallery"],$sanctuary_file);
		}

		if(!empty($index))
			unset($image_names["gallery"][$index[0]]);
			$image_names["gallery"] = array_values($image_names["gallery"]);

        return $image_names;
    }
?>