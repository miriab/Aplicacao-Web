<?php




	$title = __('message','psfbldr');
	if(isset($_REQUEST['psfb_planso_license_key']) && !empty($_REQUEST['psfb_planso_license_key'])){
		
		$d = site_url();
		$domain_array = parse_url($d);
		$domain = $domain_array['host'];
		if(strstr($domain,'ht0tp://')){
			$domain = str_replace('http://','',$domain);
		}else if(strstr($domain,'https://')){
			$domain = str_replace('https://','',$domain);	
		}
		$psfb_licenses = get_option('psfb_planso_license',false);
		
		$url = 'https://intern.planso.de/pub/pspm/revoke_license/'.urlencode($_REQUEST['psfb_planso_license_key']).'/'.urlencode($domain);
		
		$response = wp_remote_get($url);
		if(!empty($response)){
			$out = json_decode(wp_remote_retrieve_body($response));
		}
		
		if(!empty($out) && isset($out->success) && $out->success){
			$r_license = $out->license;
			if(!empty($psfb_licenses) && is_array($psfb_licenses)){
				foreach($psfb_licenses as $key=>$license){
					if($license['key'] == $out->license){
						unset($psfb_licenses[$key]);
						$success = true;
					}
				}
			}
			if($success){
				update_option('psfb_planso_license',$psfb_licenses);
				
				require( dirname(__FILE__).'/check_domain.php' );
				
				$content = '';
				$content .= '<div class="updated"><p>'.__('License Revoked Succesfully','psfbldr').'</p>';
				$content .= '<p>'.__('Please find below the lincense revoked','psfbldr').'</p><p><strong>'.$r_license.'</strong></p></div>';
				
				exit(json_encode(array('success'=>true,'content'=>$content,'title'=>$title)));
			}else{
				exit(json_encode(array('success'=>false,'msg'=>__('Error as no such license exists','psfbldr'),'title'=>$title)));	
			}	
		}else{
			exit(json_encode(array('success'=>false,'msg'=>__('Error as no such license exists','psfbldr'),'title'=>$title)));		
		}
		
	}
	exit(json_encode(array('success'=>false,'msg'=>__('No key received','psfbldr'),'title'=>$title)));





?>