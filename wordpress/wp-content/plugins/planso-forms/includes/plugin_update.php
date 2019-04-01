<?php

	
	$msg = __('Error Installing','psfbldr');
	$success = false;	
	$content = '';
	$title = '';
	if(isset($_REQUEST['package_id']) && !empty($_REQUEST['package_id']) ){
		
		$psfb_licenses = get_option('psfb_planso_license',false);
		//$plugins_url = plugins_url();
		//$plugins_url = dirname(dirname(dirname(__FILE__)));
		$upload_dir = dirname(dirname(dirname(__FILE__)));
		$d = site_url();
		$domain_array = parse_url($d);
		$domain = $domain_array['host'];
		if(strstr($domain,'http://')){
			$domain = str_replace('http://','',$domain);
		}else if(strstr($domain,'https://')){
			$domain = str_replace('https://','',$domain);	
		}
		$msg = __('License not available','psfbldr');
		$go = false;
		foreach($psfb_licenses as $lic){
			if(strstr($_REQUEST['package_id'],',')){
				$av_pids = explode(',',$_REQUEST['package_id']);	
			}else{
				$av_pids = array('0'=>$_REQUEST['package_id']);	
			}
			if(in_array($lic['package']['ID'],$av_pids)){
				if(!empty($license)){
					$license .= '@';
				}
				$license .= $lic['key'];
				$slug = $_REQUEST['slug'];
				$version = $_REQUEST['version'];
				$go = true;
			}
		}	
		if($go){
			if(function_exists('curl_version')){
				$url = 'https://intern.planso.de/pub/pspm/download_urls/'.urlencode($license).'/'.urlencode($domain).'/'.urlencode($slug).'/'.urlencode($version);
				
				$response1 = wp_remote_get($url);
					
				if(!empty($response1)){
					$headers = wp_remote_retrieve_headers($response1);
					$res1 = json_decode(wp_remote_retrieve_body($response1));
				}
				
				$res = '';
				if(!empty($res1->url)){
					
					$response = wp_remote_get($res1->url);
					if(!empty($response)){
						$headers = wp_remote_retrieve_headers($response);
						$res = wp_remote_retrieve_body($response);
					}
					if(!empty($headers['content-disposition'])){
						$file_name = str_replace('"','',str_replace('attachment; filename="',' ',$headers['Content-Disposition'])); 
					}else{
						$msg = __('Error while extracting the filename.','psfbldr');
					}
				}else if(isset($res1->status) && $res1->status == 'blocked'){
					
					if(is_dir($upload_dir.'/'.$res1->slug)){
						psfb_plugin_update_rmdirs($upload_dir.'/'.$res1->slug);
						$msg = __('Cannot be updated as the license is expired or exceeded the maximum number of domains.','psfbldr');
					}
				}else if(isset($res1->status) && $res1->status == 'expired'){
					$msg = __('Cannot be updated as the license is expired or exceeded the maximum number of domains.','psfbldr');
				}else{
					$msg = __('.','psfbldr');	
				}
				if(!empty($res)){
					$file_permission = '';
					if($version != $res1->version){
						$temp_file_name = tempnam(sys_get_temp_dir(), 'Pspm');
						file_put_contents($temp_file_name,$res);
						$file_to_be_unzipped = $temp_file_name;
						$file_permission = substr(sprintf('%o', fileperms($upload_dir)), -4);
						
						$permission_available = false;
						$permission_changed = false;
						
						if($file_permission != '0777') {
							if( chmod($upload_dir, 0777)  ) {
								$permission_changed = true;
								$permission_available = true;
							}else{
								$msg = __('Error because couldnt change the rigths. Please make sure your plugins directory is set to <em>777</em>. And do not forget to revert the permissions after the update to:','psfbldr').' <em>'.$file_permission.'</em>';
							}
						}else{
							$permission_available = true;	
						}
						
						if( $permission_available ) {
							if(is_dir($upload_dir.'/'.$res1->slug)){
								rename($upload_dir.'/'.$res1->slug,$upload_dir.'/'.$res1->slug.'.bak');
							}
							if(is_dir($upload_dir)){
								$zip = new ZipArchive;
								if ($zip->open($file_to_be_unzipped) === TRUE) {
							  	$zip->extractTo($upload_dir);
							  	$zip->close();
									$unzipfile = true;
								}else {
								  $unzipfile = false;
								}
							}
							if($unzipfile){
								unlink($file_to_be_unzipped);
								if(is_dir($upload_dir.'/'.$res1->slug.'.bak')){
									psfb_plugin_update_rmdirs($upload_dir.'/'.$res1->slug.'.bak');
									$success = true;
									$msg = __('Succesfully updated','psfbldr');
								}else{
									$msg = __('Error as the old version is not removed','psfbldr');
								}
							}else{
								if(is_dir($upload_dir.'/'.$res1->slug.'.bak')){
									rename($upload_dir.'/'.$res1->slug.'.bak',$upload_dir.'/'.$res1->slug);
								}	
								$msg = __('Error while unzipping files','psfbldr');
							}
						}else{
							$msg = __('Error because there was no permission to change the files. Please make sure your plugins directory is set to <em>777</em>. And do not forget to revert the permissions after the update to:','psfbldr').' <em>'.$file_permission.'</em>';
						}
							
						if($permission_changed && !empty($file_permission)){
							chmod($upload_dir,octdec($file_permission));
						}	
					
					}else{
						$msg = __('Latest version of the plugin is available. Please check','psfbldr');
					}
				}
				
				$title = __('Notice','psfbldr');
				exit(json_encode(array('success'=>$success,'msg'=>$msg,'title'=>$title)));
				
			}else{
				$msg = __('CURL doesnt exist','psfbldr');
			}
		}

	}
	exit(json_encode(array('success'=>$success,'msg'=>$msg)));
	


?>