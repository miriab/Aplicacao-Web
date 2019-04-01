<?php

/*

$page_detail_atts = array(
		'id' => $psform->ID,
		'permalink' => $_POST['psfb_pageurl'],
		'title' => $psform->post_title,
		'mail_replace' => $mail_replace,
		'zmail_replace' => $zmail_replace,
		'j' => $j
	);*/
	
	$page_detail_atts_before = $page_detail_atts;
	$page_detail_atts =	apply_filters( 'psfb_submitnotification_before_start',$page_detail_atts ); /* Returns form submission Id  and saves to db*/


	if(isset($page_detail_atts) && is_array($page_detail_atts)){
		if(isset($page_detail_atts['temp_post_id']) && !empty($page_detail_atts['temp_post_id']) && !is_array($page_detail_atts['temp_post_id'])){
			$mail_replace['psfb_submission_id'] = $page_detail_atts['temp_post_id'];
		}
	} else {
		$page_detail_atts = $page_detail_atts_before;
	}
	
	if(isset($j->mails)){
		$subject = '';
		$content = '';
		$reply_to = '';
		$from_name = '';
		$from_email = '';
		$bcc = '';
		$recipients = '';
		if(isset($j->mails->admin_mail)){
			$mail = $j->mails->admin_mail;
			
			$subject = $mail->subject;
			$content = $mail->content;
			$reply_to = $mail->reply_to;
			$recipients = implode(';',$mail->recipients);
			if(isset($mail->from_name)){
				$from_name = $mail->from_name;
			}
			if(isset($mail->from_email)){
				$from_email = $mail->from_email;
			}	
			if(isset($mail->bcc)){
				$bcc = implode(';',$mail->bcc);
			}
			
			foreach($mail_replace as $k=>$v){
				if(isset($v) && !is_array($v)){
					if(preg_match("/data:(([a-zA-Z0-9-.])+\/([a-zA-Z0-9-.])+);base64,/",$v)){
						
						if(is_array($mail_replace_encoded_attachments) && isset($mail_replace_encoded_attachments[$k])){
							$mail_replace[$k] = $mail_replace_encoded_attachments[$k];
							$v = $mail_replace_encoded_attachments[$k];
						}
					}
					
					
					$subject = str_replace('['.$k.']',$v,$subject);
					$content = str_replace('['.$k.']',$v,$content);
					$reply_to = str_replace('['.$k.']',$v,$reply_to);
					$from_name = str_replace('['.$k.']',$v,$from_name);
					$from_email = str_replace('['.$k.']',$v,$from_email);
					$bcc = str_replace('['.$k.']',$v,$bcc);
					$recipients = str_replace('['.$k.']',$v,$recipients);
				
				}
			}
		
			foreach($psfb_mail_tracking_map as $k=>$v){
				$content = str_replace('['.$v.']',$_REQUEST[$k],$content);
			}
			foreach($psfb_mail_dynamic_values as $k=>$v){
				$content = str_replace('['.$k.']',$v,$content);
			}
			
			
			/********** Automatischer Mail Body **********/
			if(trim($content)==''){
				if(isset($mail->html_mail) && $mail->html_mail==true){
					$content .= '<h1>'.$subject.'</h1>';
		  		$content .= '<table>';
		  	}
				foreach($mail_replace as $k=>$v){
					if(!empty($v) && trim($v)!=''){
						$readable_k = $k;
						foreach($j->fields as $field_row){
							foreach($field_row as $field_detail){
								if($k == $field_detail->name){
									$readable_k = $field_detail->label;
								}
							}
						}
						if($readable_k == $k){
							$readable_k = str_replace('_',' ',$readable_k);
						}
						if(isset($mail->html_mail) && $mail->html_mail==true){
			  			$content .= '<tr><td>'.trim($readable_k).'</td><td>'.$v.'</td></tr>';
			  		} else {
							$content .= trim($readable_k).': '.$v."\r\n";
						}
					}
				}
				
				
				foreach($psfb_mail_tracking_map as $k=>$v){
					if(isset($mail->html_mail) && $mail->html_mail==true){
						$content .= '<tr><td>'.ucwords(trim(str_replace('_',' ',$v))).'</td><td>'.$_REQUEST[$k].'</td></tr>';
					}else{
						$content .= trim(str_replace('_',' ',$v)).': '.$_REQUEST[$k]."\r\n";
					}
				}
				
				if(isset($mail->html_mail) && $mail->html_mail==true){
		  		$content .= '</table>';
		  	}
			}
			
			if(trim($content)==''){
				$content = __('Nothing submitted','psfbldr');
			}
			
			//vars.inc.php
			if(isset($mail->html_mail) && $mail->html_mail==true){
  			//$content .= PSFB_POWERED_BY_HTML;
  		} else {
				$content .= PSFB_POWERED_BY_TXT;
			}
			
			
			if(isset($mail->html_mail) && $mail->html_mail==true){
  			$email_template = PSFB_HTML_EMAIL_TEMPLATE;
  			
  			$email_template = str_replace('<!-- mail_subject -->',$subject,$email_template);
  			$email_template = str_replace('<!-- mail_body -->',$content,$email_template);
  			$email_template = str_replace('<!-- mail_charset -->',get_option( 'blog_charset', 'UTF-8'),$email_template);
  			if(isset($j->link_love) && $j->link_love==true){
  				$email_template = str_replace('<!-- mail_footer -->',PSFB_POWERED_BY_HTML,$email_template);
  			}
  			$content = $email_template;
  		}
			
			$admin_content = $content;
			$admin_content = apply_filters('psfb_submit_admin_content',$content);
			
			
			/*$attachments = array();
			$zattachments = array();
			$has_attachments = false;
			if(count($file_keys)>0){
				$wp_upload_dir = wp_upload_dir();
				if(!is_dir($wp_upload_dir['basedir'].'/planso-forms/')){
					mkdir( $wp_upload_dir['basedir'].'/planso-forms/',0777 );
				}
				
				$upload_dir = $wp_upload_dir['basedir'].'/planso-forms/'.wp_session_id();
				$upload_url = $wp_upload_dir['baseurl'].'/planso-forms/'.wp_session_id();
				if(!is_dir($upload_dir)){
					mkdir( $upload_dir,0777 );
				}
				
				foreach($file_keys as $f){
					if(isset($_FILES[$f])){
						if(is_array($_FILES[$f]['tmp_name'])){
							foreach($_FILES[$f]['tmp_name'] as $key => $tmp_name){
								if($_FILES[$f]['error'][$key]==UPLOAD_ERR_OK){
									$has_attachments = true;
							    $file_name = $_FILES[$f]['name'][$key];
							    $file_size = $_FILES[$f]['size'][$key];
							    $file_tmp = $_FILES[$f]['tmp_name'][$key];
							    $file_type = $_FILES[$f]['type'][$key];  
							    
							    //$wcnt = 1;
							    //$ext = pathinfo($file_name, PATHINFO_EXTENSION);
							    //$orif_file_name = $file_name;
							    //while(is_file($upload_dir.'/'.$file_name)){
							    //	$file_name = str_replace('.'.$ext,$cnt
							    //}
							    
							    move_uploaded_file($file_tmp, $upload_dir.'/'.$file_name);
							    $attachments[] = $upload_dir.'/'.$file_name;
							    $zattachments[$f][] = $upload_dir.'/'.$file_name;
							    $zuattachments[$f][] = $upload_url.'/'.$file_name;
							  }
							}
						} else if($_FILES[$f]['error']==UPLOAD_ERR_OK){
							
							$has_attachments = true;
							$file_name = $_FILES[$f]['name'];
					    $file_size =$_FILES[$f]['size'];
					    $file_tmp =$_FILES[$f]['tmp_name'];
					    $file_type=$_FILES[$f]['type'];  
					    
					    move_uploaded_file($file_tmp, $upload_dir.'/'.$file_name);
					    $attachments[] = $upload_dir.'/'.$file_name;
						  $zattachments[$f][] = $upload_dir.'/'.$file_name;
						  $zuattachments[$f][] = $upload_url.'/'.$file_name;
					    
						}
					}
				}
			}*/
			
			$headers = array();
			

			
			if(!isset($from_email) || empty($from_email) || !is_email(trim($from_email))){
				$headers[] = 'From: "'.get_option( 'blogname', __('Your Wordpress Blog','psfbldr') ).'" <'.get_option( 'admin_email', 'no-reply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) ).'>';
			} else {
				if( isset ($from_name) && trim($from_name) != ''){
					$headers[] = 'From: "'.trim($from_name).'" <'.trim($from_email).'>';
				} else {
					$headers[] = 'From: '.trim($from_email);
				}
			}
			
			if(isset($mail->html_mail) && $mail->html_mail==true){
  			$headers[] = 'Content-Type: text/html; charset='.get_option( 'blog_charset', 'UTF-8');
  		} else {
				$headers[] = 'Content-Type: text/plain; charset='.get_option( 'blog_charset', 'UTF-8');
			}
			if(isset($bcc)){
				$bcca = explode(';',$bcc);
			}
			foreach($bcca as $b){
				if(trim($b)!=''){
					$headers[] = 'Bcc: '.trim($b);
				}
			}
			if(is_email(trim($reply_to))){
				$headers[] = 'Reply-To: '.trim($reply_to);
			}
			
			
			$admin_content = str_replace("\n","\r\n",str_replace("\r\n","\n",$admin_content));
			
			if($has_attachments && count($attachments)>0){
				
				$filtered_mail_contents = apply_filters('psfb_submit_before_admin_mail_send',array('recipients'=>$recipients,'subject'=>$subject,'content'=>$admin_content,'headers'=>$headers,'attachments'=>$attachments));
				wp_mail( explode(';',$filtered_mail_contents['recipients']), $filtered_mail_contents['subject'], $filtered_mail_contents['content'], $filtered_mail_contents['headers'], $filtered_mail_contents['attachments'] );
				
			} else {
				
				$filtered_mail_contents = apply_filters('psfb_submit_before_admin_mail_send',array('recipients'=>$recipients,'subject'=>$subject,'content'=>$admin_content,'headers'=>$headers,'attachments'=>array()));
				if(isset($filtered_mail_contents['attachments']) && is_array($filtered_mail_contents['attachments']) && count($filtered_mail_contents['attachments']) > 0){
					wp_mail( explode(';',$filtered_mail_contents['recipients']), $filtered_mail_contents['subject'], $filtered_mail_contents['content'], $filtered_mail_contents['headers'], $filtered_mail_contents['attachments']);
				} else {
					wp_mail( explode(';',$filtered_mail_contents['recipients']), $filtered_mail_contents['subject'], $filtered_mail_contents['content'], $filtered_mail_contents['headers']);//, $attachments );
				}
			}
		}
		
		$subject = '';
		$content = '';
		$reply_to = '';
		$from_name = '';
		$from_email = '';
		$bcc = '';
		$recipients = '';
		if(isset($j->mails->user_mail)){
			$mail = $j->mails->user_mail;
			
			
			$subject = $mail->subject;
			$content = $mail->content;
			$reply_to = $mail->reply_to;
			$recipients = implode(';',$mail->recipients);
			
			if(isset($mail->from_name)){
				$from_name = $mail->from_name;
			}
			if(isset($mail->from_email)){
				$from_email = $mail->from_email;
			}
			if(isset($mail->bcc)){
				$bcc = implode(';',$mail->bcc);	
			}
			foreach($mail_replace as $k=>$v){
				$subject = str_replace('['.$k.']',$v,$subject);
				$content = str_replace('['.$k.']',$v,$content);
				$reply_to = str_replace('['.$k.']',$v,$reply_to);
				$recipients = str_replace('['.$k.']',$v,$recipients);
				$from_name = str_replace('['.$k.']',$v,$from_name);
				$from_email = str_replace('['.$k.']',$v,$from_email);
				$bcc = str_replace('['.$k.']',$v,$bcc);
			}
			
			foreach($psfb_mail_dynamic_values as $k=>$v){
				$content = str_replace('['.$k.']',$v,$content);
			}
			//vars.inc.php
			if(isset($mail->html_mail) && $mail->html_mail==true){
  			//$content .= PSFB_POWERED_BY_HTML;
  		} else {
				$content .= PSFB_POWERED_BY_TXT;
			}
			
			
			if(isset($mail->html_mail) && $mail->html_mail==true){
  			$email_template = PSFB_HTML_EMAIL_TEMPLATE;
  			
  			$email_template = str_replace('<!-- mail_subject -->',$subject,$email_template);
  			$email_template = str_replace('<!-- mail_body -->',$content,$email_template);
  			$email_template = str_replace('<!-- mail_charset -->',get_option( 'blog_charset', 'UTF-8'),$email_template);
  			if(isset($j->link_love) && $j->link_love==true){
  				$email_template = str_replace('<!-- mail_footer -->',PSFB_POWERED_BY_HTML,$email_template);
  			}
  			$content = $email_template;
  		}
			
			$headers = array();
			if(!isset($from_email) || empty($from_email) || !is_email(trim($from_email))){
				$headers[] = 'From: "'.get_option( 'blogname', __('Your Wordpress Blog','psfbldr') ).'" <'.get_option( 'admin_email', 'no-reply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) ).'>';
			} else {
				if(isset($from_name) && trim($from_name)!=''){
					$headers[] = 'From: "'.trim($from_name).'" <'.trim($from_email).'>';
				} else {
					$headers[] = 'From: '.trim($from_email);
				}
			}
  		if(isset($mail->html_mail) && $mail->html_mail==true){
  			$headers[] = 'Content-Type: text/html; charset='.get_option( 'blog_charset', 'UTF-8');
  		} else {
				$headers[] = 'Content-Type: text/plain; charset='.get_option( 'blog_charset', 'UTF-8');
			}
			if(isset($bcc)){
				$bcca = explode(';',$bcc);
			}
			foreach($bcca as $b){
				if(trim($b)!=''){
					$headers[] = 'Bcc: '.trim($b);
				}
			}
			
			if(is_email(trim($reply_to))){
				$headers[] = 'Reply-To: '.trim($reply_to);
			}
			
			$content = str_replace("\n","\r\n",str_replace("\r\n","\n",$content));
			
			$filtered_mail_contents = apply_filters('psfb_submit_before_user_mail_send',array('recipients'=>$recipients,'subject'=>$subject,'content'=>$content,'headers'=>$headers,'attachments'=>array()));
			
			if(isset($filtered_mail_contents['attachments']) && is_array($filtered_mail_contents['attachments']) && count($filtered_mail_contents['attachments']) > 0){
				wp_mail( explode(';',$filtered_mail_contents['recipients']), $filtered_mail_contents['subject'], $filtered_mail_contents['content'], $filtered_mail_contents['headers'], $filtered_mail_contents['attachments']);
			} else {
				wp_mail( explode(';',$filtered_mail_contents['recipients']), $filtered_mail_contents['subject'], $filtered_mail_contents['content'], $filtered_mail_contents['headers']);//, $attachments );
			}
		}
		
		
	}
	
	
	
	if((empty($psform->post_title) || $psform->post_title == null) && isset($page_formTitle) && !empty($page_formTitle)){
		$psform->post_title = $page_formTitle;
	}
	
	if(isset($j->pushover_user) && !empty($j->pushover_user)){
		if(isset($j->pushover_sound) && !empty($j->pushover_sound)){
			$pushover_sound = $j->pushover_sound;
		} else {
			$pushover_sound = 'pushover';
		}
		
		
		$options = array(
		  CURLOPT_URL => "https://api.pushover.net/1/messages.json",
		  CURLOPT_POSTFIELDS => array(
		    "token" => "aM5ZE5xttJQDnNkABYjwLdxpBUQzBv",
		    "user" => $j->pushover_user,
		    "sound" => $pushover_sound,
		    "message" => $admin_content,
		    "title" => $psform->post_title.' '.__('has been submitted','psfbldr'),
		    "url" =>  $_POST['psfb_pageurl'],
		    "url_title" => get_option( 'blogname', __('Your Wordpress Blog','psfbldr'))
		  ),
		  CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_HEADER         => 0,
      CURLOPT_SSL_VERIFYPEER => 0, 
	    CURLOPT_SSL_VERIFYHOST => 0,
      CURLOPT_USERAGENT      => "PlanSo Forms"
		); 

    $ch      = curl_init(); 
    curl_setopt_array($ch,$options); 
		
		$result = curl_exec($ch);
		curl_close($ch);
	}
	
	if(isset($j->zapier_url) && count($j->zapier_url)>0){
		if(is_array($j->zapier_url)){
			$zurl = $j->zapier_url;
		} else if(is_object($j->zapier_url)){
			$zurl = $j->zapier_url;
		} else {
			$zurl[] = $j->zapier_url;
		}
		
		if($has_attachments && count($zattachments)>0){
			foreach($zuattachments as $k=>$a){
				for($cnt = 0;$cnt < count($a);$cnt++){
					//$zmail_replace[$k.'['.$cnt.']'] = '@' . $a[$cnt];
					//$zmail_replace[$k.'['.$cnt.']'] = $a[$cnt];
					$zmail_replace[$k][$cnt] = '@' . $a[$cnt];
				}
			}
		}
		
		foreach($zurl as $url){
			$options = array( 
					CURLOPT_URL => $url,
	        CURLOPT_RETURNTRANSFER => 1,         // return response 
	        CURLOPT_HEADER         => 0,        // don't return headers 
	        CURLOPT_USERAGENT      => "PlanSo Forms",     // who am i 
	        CURLOPT_CONNECTTIMEOUT => 60,          // timeout on connect 
	        CURLOPT_TIMEOUT        => 60,          // timeout on response 
	        CURLOPT_POST            => 1,            // i am sending post data 
	           CURLOPT_POSTFIELDS     => $zmail_replace,    // this are my post vars 
	        CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl 
	        CURLOPT_SSL_VERIFYPEER => 0        // 
	    ); 
	
	    $ch      = curl_init(); 
	    curl_setopt_array($ch,$options); 
			
			$result = curl_exec($ch);
			curl_close($ch);
		}
	}
	//print_r($attachments);
	
	$filtered = apply_filters('psfb_submit_before_clean_attachments',array('j'=>$j,'mail_replace'=>$mail_replace,'attachments'=>$attachments));
	$attachments = $filtered['attachments'];
	/*
	print_r($attachments);
	Array ( [0] => D:\home\site\wwwroot/wp-content/uploads/planso-forms/e2401aef9e31a45c974b09f4f060d870/San Francisco.jpg )
	exit();
	*/
	/** CLEAN UPLOADED ATTACHMENTS **/
	if(!isset($j->clean_attachments) || $j->clean_attachments===true || $j->clean_attachments===1){
		if($has_attachments && count($attachments)>0){
			foreach($attachments as $f){
				if(is_array($f)){
					foreach($f as $ff){
						if(is_file($ff)){
							chmod($ff,0777);
							unlink($ff);
						}
					}
				} else {
					if(is_file($f)){
						chmod($f,0777);
						unlink($f);
					}
				}
			}
			if(is_dir($upload_dir)){
				chmod($upload_dir,0777);
				$files = array_diff(scandir($upload_dir), array('.','..')); 
		    foreach($files as $file){
		    	if(is_file($file)){
		      	unlink($upload_dir.'/'.$file); 
		      }
		    } 
				rmdir($upload_dir);
			}
		}
	}
	
	if((empty($psform->ID) || $psform->ID == null) && isset($page_formID) && !empty($page_formID)){
		$psform->ID = $page_formID;
	}
	
	
	if(!isset($affiliate_params)){
		$amount = 0;
		if(isset($j->affiliate_amount) && !empty($j->affiliate_amount)){
			$amount = $j->affiliate_amount;
		}
		$affiliate_params = apply_filters( 'psfb_affiliate_gather_params', array(), $amount, $psform->post_title,$psform->ID, $j );
	}
	
	$affiliate_params = apply_filters( 'psfb_affiliate_add_referral', $affiliate_params);
	
	
	$page_detail_atts = array(
		'id' => $psform->ID,
		'permalink' => $_POST['psfb_pageurl'],
		'title' => $psform->post_title,
		'mail_replace' => $mail_replace,
		'zmail_replace' => $zmail_replace,
		'j' => $j
	);
	
	
	do_action( 'psfb_submitnotification_before_exit',$page_detail_atts ); /*  Saving the post content to the db NOT ANYMORE */
	
?>