<?php




	$msg = __('Error Installing','psfbldr');
	$success = false;	
if(isset($_REQUEST['psfb_planso_license_key']) && !empty($_REQUEST['psfb_planso_license_key'])){
	
	$d = site_url();
	$domain_array = parse_url($d);
	$domain = $domain_array['host'];
	if(strstr($domain,'http://')){
		$domain = str_replace('http://','',$domain);
	}else if(strstr($domain,'https://')){
		$domain = str_replace('https://','',$domain);	
	}
	
	$url = 'https://intern.planso.de/pub/pspm/check_license/'.urlencode($_REQUEST['psfb_planso_license_key']).'/'.urlencode($domain);
		
	$response = wp_remote_get($url);
	if(!empty($response)){
		$out = json_decode(wp_remote_retrieve_body($response));
	}
		
	
	if(!empty($out) && !isset($out->error)){
		
		$arr = array(
			'key' => $out->license_key,
			'valid_till' => $out->valid_till,
	   	'package' => array(
	   		'ID' => $out->package_id,
	   		'name' => $out->package_name
	 		)
	  );
	  
		$psfb_licenses = get_option('psfb_planso_license',false);
		$is_saved=false;
		if(!empty($psfb_licenses) && is_array($psfb_licenses)){
			foreach($psfb_licenses as $license){
				if($license['key'] == $arr['key']){
					$is_saved = true;
				}
			}
			if(!$is_saved && !empty($arr['key'])){
				array_push($psfb_licenses,$arr);
			}
		}else{
			if(!empty($arr['key'])){
				$psfb_licenses = array($arr);
			}
		}
		update_option('psfb_planso_license',$psfb_licenses);
		exit(json_encode(array('success'=>true,'msg'=>__('Package registered succesfully','psfbldr'))));
		
	}else{
		if(isset($out->msg) && !empty($out->msg)){
			exit(json_encode(array('success'=>false,'msg'=>$out->msg,'error'=>$out->error)));	
		}else{
			exit(json_encode(array('success'=>false,'msg'=>$msg,'error'=>$out->error)));		
		}
	}
}

exit(json_encode(array('success'=>false,'msg'=>__('No sufficient details','psfbldr'))));




?>