<?php


	
	$d = site_url();
	$domain_array = parse_url($d);
	$domain = $domain_array['host'];
	if(!empty($domain)){
		if(strstr($domain,'http://')){
			$domain = str_replace('http://','',$domain);
		}else if(strstr($domain,'https://')){
			$domain = str_replace('https://','',$domain);	
		}
	}
	
	/*$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,60);
	$out = json_decode(curl_exec($ch));
	curl_close($ch);*/

	$url = 'https://intern.planso.de/pub/pspm/check_domain/'.urlencode($domain);
		
	$response = wp_remote_get($url);
	if(!empty($response)){
		$out = json_decode(wp_remote_retrieve_body($response));
	}
		
	$url1 = 'https://planso.de/pub/pspml/2e4a5bf91efee57056eb58e941de3b96601e2e99';
	
	/*$result1 = wp_remote_get($url1);
	if(!empty($result1['body'])){
		$j = json_decode($result1['body']);	
	}
	*/
	$response1 = wp_remote_get($url1);
	if(!empty($response1)){
		$j = json_decode(wp_remote_retrieve_body($response1));
	}	
	/*$ch1 = curl_init();
	curl_setopt($ch1,CURLOPT_URL,$url1);
	curl_setopt($ch1,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch1,CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($ch1,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($ch1,CURLOPT_CONNECTTIMEOUT,60);
	$out1 = curl_exec($ch1);
	if(!empty($out1)){
		$j = json_decode($out1);
	}
	
	curl_close($ch1);*/
	
	//$plugins_url=plugins_url();
	$upload_dir = dirname(dirname(dirname(__FILE__)));
	
	//if($out->success){	
	
	//if(!empty($j) && !empty($j->plugins)){
		foreach($j->plugins as $k=>$plugins){
			$available = false;
			if(is_dir($upload_dir.'/'.$plugins->slug)){
				if(isset($out->slug) && !empty($out->slug)){
					foreach($out->slug as $kk=>$v){
						if($v==$plugins->slug){
							$available = true;
						}
					}
				}
				if(!$available){
					psfb_plugin_update_rmdirs($upload_dir.'/'.$plugins->slug);
				}
			}
		}
	//}
	//}

?>