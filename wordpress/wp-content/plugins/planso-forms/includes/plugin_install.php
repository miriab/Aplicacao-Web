<?php

	
	$msg = __('Error Installing','psfbldr');
	$success = false;	
	if(isset($_REQUEST['package_id']) && !empty($_REQUEST['package_id']) ){
		
		
		$psfb_licenses = get_option('psfb_planso_license',false);
		//$plugins_url = plugins_url();
		
		$d = site_url();
		$domain_array = parse_url($d);
		$domain = $domain_array['host'];
		
		
		if(strstr($domain,'http://')){
			$domain = str_replace('http://','',$domain);
		}else if(strstr($domain,'https://')){
			$domain = str_replace('https://','',$domain);	
		}
		
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
			$title = __('Notice','psfbldr');
			if(function_exists('curl_version')){
				
				$url = 'https://intern.planso.de/pub/pspm/download_urls/'.urlencode($license).'/'.urlencode($domain).'/'.urlencode($slug).'/'.urlencode($version);
					
				$response1 = wp_remote_get($url);
				if(!empty($response1)){
					$headers = wp_remote_retrieve_headers($response1);
					$res1 = json_decode(wp_remote_retrieve_body($response1));
				}
				
				$upload_dir = dirname(dirname(dirname(__FILE__)));
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
					}	
					$msg = __('Cannot be installed as the license is expired or exceeded the maximum number of domains.','psfbldr');//(','psfbldr').$license.__(')
					
				}else if(isset($res1->status) && $res1->status == 'expired'){
					$msg = __('Cannot be installed as the license is expired and cannot be used to update or install any plugins.If you any other licenses available please revoke this license and try to install the plugins with other license.','psfbldr');
					
				}
				$file_permission = '';
				if(!empty($res)){
					$temp_file_name = tempnam(sys_get_temp_dir(), 'Pspm');
					file_put_contents($temp_file_name,$res);
					$file_to_be_unzipped = $temp_file_name;
					$file_permission = substr(sprintf('%o', fileperms($upload_dir)), -4);
					//echo $file_permission;
					$permission_available = false;
					$permission_changed = false;
					if($file_permission != '0777') {
						if(chmod($upload_dir,0777)) {
							$permission_changed = true;
							$permission_available = true;
						}else{
							$msg = __('Error because couldnt change the rigths. Please make sure your plugins directory is set to <em>777</em>. And do not forget to revert the permissions after the update to:','psfbldr').' <em>'.$file_permission.'</em>';
						}
					}else{
						$permission_available = true;	
					}
					if($permission_available){
						if(is_dir($upload_dir.'/'.$res1->slug)){
							$msg = __('Plugin is already installed','psfbldr');
						}else{
							
							if(is_dir($upload_dir)){
								$zip = new ZipArchive;
								if ($zip->open($file_to_be_unzipped) === TRUE) {
							    $zip->extractTo($upload_dir);
							    $zip->close();
							    $unzipfile = true;
								}else {
									$msg = __('Error while unzipping the file','psfbldr');
								  $unzipfile = false;
								}
							}
							if($unzipfile){
								unlink($file_to_be_unzipped);
								$success = true;
								$msg = __('Succesfully Installed','psfbldr');
							}else{
								$msg = __('Error while unzipping files','psfbldr');
							}
							if(!is_dir($upload_dir.'/'.$res1->slug)){
								$msg = __('Plugin was not installed because of rights problem.','psfbldr');
							}
						}
							
					}else{
						$msg = __('Error because there was no permission to change the files. Please make sure your plugins directory is set to <em>777</em>. And do not forget to revert the permissions after the update to:','psfbldr').' <em>'.$file_permission.'</em>';
					}
					if($permission_changed && !empty($file_permission)){
						chmod($upload_dir,octdec($file_permission));
					}	
				}
				
				exit(json_encode(array('success'=>$success,'msg'=>$msg)));
			
			}else{
				$msg = __('CURL doesnt exist','psfbldr');
			}
		}else{
			$msg = __('Invalid package','psfbldr');	
		}

	}
	exit(json_encode(array('success'=>$success,'msg'=>$msg)));
	


?>