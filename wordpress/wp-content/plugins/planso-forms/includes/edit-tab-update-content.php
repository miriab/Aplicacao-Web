<?php

	
	
	
	
	
	$psfb_licenses = get_option('psfb_planso_license',false);
	
	$license_key_html = '';
	$update_badge = '';
	if(isset($psfb_licenses) && !empty($psfb_licenses)){
		foreach($psfb_licenses as $license){
		
			if(!empty($license['key'])){
				$license_key_html .= '<div class="form-group">';
				$license_key_html .= '<label>'.$license['package']['name'].__(' Package License Key','psfbldr').'</label>';
				$license_key_html .= '<div class="input-group">';
				$license_key_html .= '<input type="text" class="form-control" value="'.$license['key'].'">';
				$license_key_html .= '<span style="cursor:pointer;" data-key="'.$license['key'].'" data-id="'.$license['package']['ID'].'" data-toggle="popover" data-placement="top" data-trigger="hover"  data-content="'.__('This will remove the current domain from the license package','psfbldr').'" data-m_title="'.__('Are you sure?','psfbldr').'" data-m_t1="'.__('Proceed','psfbldr').'" data-expiry="'.$license['valid_till'].'" class="psfb_license_revoke input-group-addon" >'.__('Revoke','psfbldr').'</span>';
				
				$license_key_html .= '</div><p class="text-info">'.__('This license will be expired on:','psfbldr').' '.$license['valid_till'].'</p></div>';
			}
		}
	}else{
		$update_badge = 'no_license';	
	}
	
	
	$url = 'https://planso.de/pub/pspml/2e4a5bf91efee57056eb58e941de3b96601e2e99';
	
	$response = wp_remote_get($url);
	if(!empty($response)){
		$update_j = json_decode(wp_remote_retrieve_body($response));
	}
	
	
	
	
	global $current_user;
  wp_get_current_user();
	$admin_email = $current_user->user_email;
	$first_name = $current_user->user_firstname;
	$last_name = $current_user->user_lastname;
	
	$d = site_url();
	$domain_array = parse_url($d);
	$domain = $domain_array['host'];
	if(strstr($domain,'http://')){
		$domain = str_replace('http://','',$domain);
	}else if(strstr($domain,'https://')){
		$domain = str_replace('https://','',$domain);	
	}
	$iframe_src = 'https://intern.planso.de/pub/pspms/pm/'.urlencode($domain).'/plugin-slug/'.urlencode($admin_email).'/'.$first_name.'/'.$last_name.'/';
		
	
	$packages_html_content = '';
	$tab_content = '';
	$packages_html_content = 	'';
	$packages_html_content .= '<table class="table"><thead><tr>';
	$packages_html_content .= '<th>'.__('Plugin Name','psfbldr').'</th>';
	$packages_html_content .= '<th>'.__('Version','psfbldr').'</th>';
	$packages_html_content .= '<th>'.__('Status','psfbldr').'</th>';
	$packages_html_content .= '<th>'.__('Package Needed','psfbldr').'</th>';
	$packages_html_content .= '<th>'.__('Action','psfbldr').'</th>';
	$packages_html_content .= '</tr></thead><tbody>';
		
	$update_cnt = 0;
	if(isset($update_j->plugins)){
		foreach($update_j->plugins as $k=>$plugins){
			
			$this_package_id = '';
			$p_p_available = false; 
			$p_available = false; 
			$p_p_expired = false;
			$this_package_name = '';
			$p_p_package_name='';	
			$p_p_package_id='';	
			foreach($update_j->packages as $k=>$packages){
				if(isset($psfb_licenses) && !empty($psfb_licenses)){
					foreach($psfb_licenses as $license){
						if($packages->ID == $license['package']['ID']){
							foreach($packages->plugins as $k=>$v){
								if($v == $plugins->slug){
									$date = new DateTime($license['valid_till']);
									$now = new DateTime();
									if($date < $now) {
										$p_p_expired = true;
									}else{
									}	
									$p_p_package_name = $packages->name;
									$p_p_package_id = $packages->ID;
									$p_p_available = true;	
									
								}
							}
						}
					}
				}
				foreach($packages->plugins as $k=>$v){
					if($v == $plugins->slug){
						$this_package_id = $packages->ID;
						$this_package_name = $packages->name;
						$p_available = true;
					}
				}			
			}
			
			if($p_available){
				$action_content = '';
				$availability_content = '';
				$packages_html_content .= '<tr>';
				$packages_html_content .= '<td class="psfb_plugin_name" style="cursor:pointer;">';
				$packages_html_content .= $plugins->name;
				$packages_html_content .= '</td>';
				$packages_html_content .= '<td>';
				//$plugins_url = plugins_url();
				$plugins_url = dirname(dirname(dirname(__FILE__)));
				if(is_dir($plugins_url.'/'.$plugins->slug) && $p_p_available){
					
					$plugin_data = get_plugin_data( $plugins_url.'/'.$plugins->slug.'/'.$plugins->slug.'.php', $markup = true, $translate = true );
					
					$packages_html_content .= $plugin_data['Version'];
					$plugin_path = $plugins_url.'/'.$plugins->slug.'/'.$plugins->slug.'.php';
					if(!is_plugin_active( $plugins->slug.'/'.$plugins->slug.'.php') ){
						$availability_content =  __('Installed (Not Activated)','psfbldr');
						$action_content = '<a href="'.
							wp_nonce_url(admin_url('plugins.php?action=activate&amp;plugin='.$plugin_path), 'activate-plugin_'.$plugin_path)	
						.'" target="_blank" class="btn btn-success btn-xs"><i class="fa fa-check-circle-o"></i> '. __('activate '.$plugins->name.'','psfbldr').'</a>';
					}else if(is_plugin_active( $plugins->slug.'/'.$plugins->slug.'.php')){
						if($plugins->version == $plugin_data['Version']){
							$availability_content =  __('Latest Version is available in your site(Activated)','psfbldr');
							$action_content = '<a><i class="fa fa-check-circle-o fa-2x" aria-hidden="true"></i></a>';
						}else{
							if($p_p_available){
								$update_cnt++; 
								$availability_content =  __('Update Avaiable (Latest version:','psfbldr').$plugins->version.')';
								$action_content = '<a style="cursor:pointer;" class="pspm_update_plugin" data-slug="'.$plugins->slug.'" data-version="'.$plugin_data['Version'].'" data-p_ids="'.implode(',',$plugins->packages).'" data-id="'.$p_p_package_id.'"><i class="fa fa-download" aria-hidden="true"></i> '. __('Update Now','psfbldr').'</a>';
							}else{
								$availability_content =  __('Not licensed','psfbldr');
								$action_content = '<a style="cursor:pointer;" class="pspm_purchase_plugin" data-slug="'.$plugins->slug.'" data-isrc="'.$iframe_src.'" data-id="'.$this_package_id.'"><i class="fa fa-shopping-cart" aria-hidden="true"></i>	'. __('Obtain License','psfbldr').'</a>';	
							}
						}
					}
				}else{
					$packages_html_content .= $plugins->version;
					$availability_content =  __('Not licensed','psfbldr');
					$action_content = '<a style="cursor:pointer;" class="pspm_purchase_plugin" data-slug="'.$plugins->slug.'" data-isrc="'.$iframe_src.'" data-id="'.$this_package_id.'"><i class="fa fa-shopping-cart" aria-hidden="true"></i>	'. __('Obtain License','psfbldr').'</a>';
					if($p_p_available){
						$availability_content =  __('Plugin is available with your package. Please Install.','psfbldr');
						$action_content = '<a style="cursor:pointer;" class="pspm_install_plugin" data-slug="'.$plugins->slug.'" data-version="'.$plugins->version.'" data-p_ids="'.implode(',',$plugins->packages).'" data-id="'.$p_p_package_id.'"><i class="fa fa-download" aria-hidden="true"></i> '. __('Install Now','psfbldr').'</a>';
					}
				}
				$packages_html_content .= '</td>';
				$packages_html_content .= '<td>';
				$packages_html_content .= $availability_content;
				$packages_html_content .= '</td>';
						
				$packages_html_content .= '<td>';
				if(isset($p_p_package_name) && !empty($p_p_package_name)){
					$packages_html_content .= $p_p_package_name;	
				}else{
					$packages_html_content .= $this_package_name;	
				}
				$packages_html_content .= '</td>';
				
				
				$packages_html_content .= '<td>';
				$packages_html_content .= $action_content;
				$packages_html_content .= '</td></tr>';
				$packages_html_content .= '<tr style="display:none;"><td colspan="100%"><div class="psfb_plugin_description">'.$plugins->description.'</div></td></tr>';
			
			
			}
		}
	}
	if($update_cnt != 0){
		$update_badge = $update_cnt;
	}
	$packages_html_content .= '</tbody></table>';
	
	
?>


<script type="text/javascript">
	jQuery(document).ready(function($){
		
		if(typeof $('#psfb_tab_update').data('update_badge') != 'undefined' && $('#psfb_tab_update').data('update_badge')!= '' && $('#psfb_tab_update').data('update_badge') != 0 ){
			
			if($('#psfb_tab_update').data('update_badge') == 'no_license'){
				var t = $('.nav-tabs li a[aria-controls="psfb_update"]').text();	
				$('.nav-tabs li a[aria-controls="psfb_update"]').text('');
				var c = '<span class="label label-warning">'+t+'</span>';
			}else{
				var c = '<span class="badge">'+$('#psfb_tab_update').data('update_badge')+'</span>';
			}
			$('.nav-tabs li a[aria-controls="psfb_update"]').append(' '+c);
		}
		
		
		$('.psfb_save_license').click(function(){
			var form_group = $(this).closest('.form-group');
			
			if(typeof form_group.find('input').val() != 'undefined' && form_group.find('input').val() != ''){
				form_group.removeClass('has-error');
				var dat = $('.psfb_update_license_credentials :input').serialize()+'&action=psfb_update_get_package_details';
			
				$.ajax({
					type:'GET',
					url:ajaxurl,
					data: dat,
					dataType:'json',
					success:function(r){
						if(typeof r.msg != 'undefined' && r.msg != '' && r.msg != null){
							
							
							var content;
							if(r.success){
								content = '<div class="updated"><p>'+r.msg+'</p>';
							}else{
								content = '<div class="error"><p>'+r.msg+'</p>';
							}
							$( '#psfb_content_dialog' ).html(content).dialog({ 
								autoOpen: false,
								width:600,
								maxWidth:'100%',
								title:'Notice',
			  				height:200,
			  				buttons:
					  		[
					  			{ text: 'Ok', icons:{primary:'ui-icon-disk'}, click: function() { $('#psfb_content_dialog').dialog('close'); } }
					  		],
					  		close:function(){
					  			if(typeof r.success != 'undefined' && r.success != '' && r.success != null){
					  				location.reload();
					  			}	
					  		}
				    	}).dialog('open');
						}
					},
					error:function(r){
					}
				});	
			}else{
				form_group.addClass('has-error');
			}
		});
		$('span[data-toggle="popover"]').popover({trigger:'hover'});
		$('.psfb_license_revoke').click(function(){
			
			var dat = 'action=psfb_update_revoke_license&psfb_planso_license_key='+$(this).attr('data-key');
			
	    var title=$(this).data('m_title');
	    var t1=$(this).data('m_t1');
	    var content = $('.psfb_license_revoke_warning_html').html();
			
			var selectedDate = $(this).data('expiry');
			
			var pastDate = new Date(selectedDate);
			var now = new Date();
			
			
			if (pastDate < now) {
				content += $('.psfb_license_revoke_warning_html_1').html();  
			}
			
			
			
			
			$( '#psfb_content_dialog' ).html('<div class="error">'+content+'</div>').dialog({ 
				autoOpen: false,
		  	modal:true,
				width:600,
				maxWidth:'100%',
				title:title,
				height:350,
				buttons:
	  		[
	  			{text:t1, icons:{primary:'ui-icon-disk'}, click: function() {  
	  					$.ajax({
								type:'GET',
								url:ajaxurl,
								data: dat,
								dataType:'json',
								success:function(r){
									if(r != null && typeof r.content != 'undefined' && r.content != '' && r.content != null){
										 
									}else{
										r.content	='<p>'+r.msg+'</p>';
									}
									$( '#psfb_content_dialog' ).html(r.content).attr('title',r.title).dialog({ 
										autoOpen: false,
										width:600,
										maxWidth:'100%',
										title:r.title,
					  				height:300,
					  				//position:{ my: "center", at: "center", of: window },
					  				buttons:
							  		[
							  			{ text: 'Ok',icons:{primary:'ui-icon-disk'}, click: function() { $('#psfb_content_dialog').dialog('close'); } }
							  		],
							  		close:function(){
						  				if(typeof r.success != 'undefined' && r.success != '' && r.success != null){
							  				location.reload();
							  			}	
							  		}
							    }).dialog('open');
								},
								error:function(r){
									
								}
							});	
							$('#psfb_content_dialog').dialog('close'); 
	  				}
	  			},
	  			{ text: 'Cancel',click: function() { $('#psfb_content_dialog').dialog('close'); } }
	  		]
	    }).dialog('open');
		});
		
		$('.pspm_update_plugin').click(function(){
			
			if(typeof $(this).data('id') != 'undefined' && $(this).data('id') != ''){
				var dat = 'action=psfb_update_plugin&slug='+$(this).data('slug')+'&version='+$(this).data('version')+'&package_id='+$(this).data('id');
				
				$.ajax({
					type:'GET',
					url:ajaxurl,
					data: dat,
					dataType: 'json',
					success:function(r){
						if(typeof r.msg != 'undefined' && r.msg != null && r.msg != ''){
							var content;
							if(r.success){
								content = '<div class="updated"><p>'+r.msg+'</p>';
							}else{
								content = '<div class="error"><p>'+r.msg+'</p>';
							}
							
							
							 
							$( '#psfb_content_dialog' ).html(content).dialog({ 
								autoOpen: false,
								width:600,
								maxWidth:'100%',
								title:r.title,
			  				height:250,
			  				buttons:
					  		[
					  			{ text: 'Ok', icons:{primary:'ui-icon-disk'}, click: function() { $('#psfb_content_dialog').dialog('close'); } }
					  		],
					  		close:function(){
					  			
				  				if(typeof r.success != 'undefined' && r.success != '' && r.success != null){
					  				location.reload();
					  			}	
					  		}
				    	}).dialog('open');
							
						}
					},
					error:function(r){
						
					}
				});	
			}
		});
	
		$('.pspm_purchase_plugin').click(function(){
			var slug = $(this).data('slug');
			if(typeof slug != 'undefined' && slug != ''){
				$('#pspm_modal').modal('show');
				var iframe_src = $(this).data('isrc').replace('plugin-slug', slug);
				$('.pspm_pricing_table').html('<iframe src="'+iframe_src+'" height="700" width="100%"><iframe>');
			}
		});
		
		$('.pspm_proceed_payment').click(function(){
			
			var dat={
				'action':'pm_process'
			};
			$.ajax({
				type:'POST',
				url:'https://intern.planso.de/public.pspm.payment.processing.php',
				data: dat,
				dataType:'json',
				success:function(r){
					
				},
				error:function(r){
					
				}
			});	
			
		});
		
		$('.pspm_install_plugin').click(function(){
			
			if(typeof $(this).data('id') != 'undefined' && $(this).data('id') != ''){
				var dat = 'action=psfb_install_plugin&slug='+$(this).data('slug')+'&version='+$(this).data('version')+'&package_id='+$(this).data('p_ids');
				
				$.ajax({
					type:'GET',
					url:ajaxurl,
					data: dat,
					dataType: 'json',
					success:function(r){
						if(typeof r.msg != 'undefined' && r.msg != null && r.msg != ''){
							
							var content;
							if(r.success){
								content = '<div class="updated"><p>'+r.msg+'</p>';
							}else{
								content = '<div class="error"><p>'+r.msg+'</p>';
							}
							$( '#psfb_content_dialog' ).html(content).dialog({ 
								autoOpen: false,
								width:600,
								maxWidth:'100%',
								title:r.title,
			  				height:250,
			  				buttons:
					  		[
					  			{ text: 'Ok', icons:{primary:'ui-icon-disk'}, click: function() { $('#psfb_content_dialog').dialog('close'); } }
					  		],
					  		close:function(){
				  				if(typeof r.success != 'undefined' && r.success != '' && r.success != null){
					  				location.reload();
					  			}	
					  		}
				    	}).dialog('open');
						}
					},
					error:function(r){
						
					}
				});	
			}
		});
		$('.psfb_plugin_name').click(function(){
			$(this).closest('tr').next().toggle();
		});
		
	});
	
</script>


	
	<div class="psfb_update tab-pane" id="psfb_tab_update" data-update_badge="<?php echo $update_badge; ?>" role="tabpanel">
						
		<section class="container-fluid postbox "> 
			<div class="metabox-holder">
				<div class="meta-box-sortables">
					<div id="titlediv">
						<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('PlanSo Forms Updates','psfbldr'); ?></strong></span></h3>
					</div>
					
					<br class="clear">
					<div class="update_settings col-md-12">
						<div class="psfb_update_license_credentials">
						
						<?php echo $license_key_html; ?>
						
						<div class="form-group">
							<label><?php echo __('New License Key','psfbldr'); ?></label>
							<div class="input-group">
								<input type="text" class="form-control" name="psfb_planso_license_key" >
								<span class="input-group-addon psfb_save_license" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="<?php echo __('This will add a new license to the current domain.','psfbldr'); ?>"><i style="cursor:pointer;" class="fa fa-plus"> <?php echo __('Add','psfbldr'); ?></i></span>
							</div>
								
							<p class="help-block"><?php echo __('Please fill in your PlanSo License Key','psfbldr'); ?></p>
						</div>
						<div class="psfb_license_revoke_warning_html" style="display:none;">
							
								<p class="text-info"><b><?php echo __('Please be aware that after revoking, the plugins related to this license will be deleted','psfbldr');?></b></p>
							
						</div>
						<div class="psfb_license_revoke_warning_html_1" style="display:none;">
							
							
							<p class="text-info"><?php echo __('Note: If want to transfer your license to a new site but your license has expired you need to follow these steps before revoking the license here:','psfbldr');?></p>
							<p class="text-info"><?php echo __('1) Download the plugins manually via ftp.','psfbldr');?></p>
							<p class="text-info"><?php echo __('2) Enter the license key in the new site. ','psfbldr');?></p>
							<p class="text-info"><?php echo __('3) Upload the plugins to the new site via ftp.','psfbldr');?></p>
						</div>
						</div>
					</div>
					<div class="packages_list col-md-12">
						<?php echo $packages_html_content; ?>
						<div class="modal fade bs-example-modal-lg" id="pspm_modal" tabindex="-1" role="dialog" aria-labelledby="pspm_modallabel" aria-hidden="true">
						  <div class="modal-dialog modal-lg">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo __('Close','psfbldr'); ?>"><span aria-hidden="true">&times;</span></button>
						        <h4 class="modal-title" id="pspm_modallabel"><?php echo __('Pricing options','psfbldr'); ?></h4>
						      </div>
						      <div class="modal-body">
						        
						        <div class="pspm_pricing_table">
						        		
						        
						        </div>
						        
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default cancelsavefield" data-dismiss="modal"><?php echo __('Cancel','psfbldr'); ?></button>
						        
						      </div>
						    </div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</section>
	</div>
	
	
	<div id="psfb_content_dialog" title="<?php echo __('Notice','psfbldr'); ?>" style="max-width:100%;"></div>
	
				
	  