<?php
class Core {
	function checkEmpty($data)
	{
	    if(!empty($data['hostname']) && !empty($data['username']) && !empty($data['database']) && !empty($data['url']) && !empty($data['template'])){
	        return true;
	    }else{
	        return false;
	    } 
	}

	function show_message($type,$message) {
		return $message;
	}
	
	function getAllData($data) {
		return $data;
	}

	function write_config($data) {
 

        $template_path 	= 'includes/templatevthree.php';
        
		$output_path 	= '../application/config/database.php';

		$database_file = file_get_contents($template_path);

		$new  = str_replace("%HOSTNAME%",$data['hostname'],$database_file);
		$new  = str_replace("%USERNAME%",$data['username'],$new);
		$new  = str_replace("%PASSWORD%",$data['password'],$new);
		$new  = str_replace("%DATABASE%",$data['database'],$new);

		$handle = fopen($output_path,'w+');
		@chmod($output_path,0777);
		
		if(is_writable(dirname($output_path))) {

			if(fwrite($handle,$new)) {

				$template_path_user 	= 'assets/sqlcommandtemp.sql';
		        
				$output_path_user 	= 'assets/sqlcommand.sql';

				$database_file_user = file_get_contents($template_path_user);

				$password = $data['admin_password']; 

				$new_user  = str_replace("%ADMINEMAIL%",$data['admin_email'],$database_file_user);

				$params = [
					'cost' => 12
				];

				if (empty($password) || strpos($password, "\0") !== FALSE || strlen($password) > 32)
				{
					return FALSE;
				}else{
					$password = password_hash($password, PASSWORD_BCRYPT, $params);
				}

				$new_user  = str_replace("%ADMINPASSWORD%",$password,$new_user);

				$handle_user = fopen($output_path_user,'w+');
				@chmod($output_path_user,0777);
				
				if(is_writable(dirname($output_path_user))) {

					if(fwrite($handle_user,$new_user)) {
						
						$template_path_url 	= '../assets/template/base-url.php';
				        
						$output_path_url 	= '../application/config/config.php';

						$database_file_url = file_get_contents($template_path_url);

						// $new_url  = str_replace("%BASEURL%",$data['app_url'],$database_file_url);

						$handle_url = fopen($output_path_url,'w+');
						@chmod($output_path_url,0777);
						
						if(is_writable(dirname($output_path_url))) {

							if(fwrite($handle_url,$database_file_url)) {
								return true;
							} else {
								return false;
							}
						} else {
							return false;
						}

					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function checkFile(){
	    $output_path = '../application/config/database.php';
	    
	    if (file_exists($output_path)) {
           return true;
        } 
        else{
            return false;
        }
	}

	function delete_directory($dir) {
		
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {

				if ($object != "." && $object != "..") {

					if (filetype($dir."/".$object) == "dir"){

						// return 'this is folder';
						$dir_sec = $dir."/".$object;
						if (is_dir($dir_sec)) {
							$objects_sec = scandir($dir_sec);
							foreach ($objects_sec as $object_sec) {
								if ($object_sec != "." && $object_sec != "..") {
									if (filetype($dir_sec."/".$object_sec) == "dir") 
										rmdir($dir_sec."/".$object_sec); 
									else
										unlink($dir_sec."/".$object_sec);
								}
							}
							rmdir($dir_sec);
						}

					}else{
						unlink($dir."/".$object);
					}

				}

			}
			return rmdir($dir);
		}
		
	}
}