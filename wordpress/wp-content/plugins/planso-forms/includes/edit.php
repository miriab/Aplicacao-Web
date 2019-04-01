<?php

// don't load directly
  


if ( ! defined( 'ABSPATH' ) )
	die( '-1' );

require_once( dirname(__FILE__).'/vars.inc.php' );

$psfb_smtp_notice_dismiss = get_option( 'psfb_smtp_notice_dismiss', false);
$psfb_smtp_plugin_active = false;
if ( is_plugin_active( 'postman-smtp/postman-smtp.php' ) ||   is_plugin_active( 'post-smtp/postman-smtp.php' ) || is_plugin_active( 'easy-wp-smtp/easy-wp-smtp.php' ) ||  is_plugin_active( 'webriti-smtp-mail/webriti-smtp-mail.php' ) ||  is_plugin_active( 'smtp/smtp.php' ) ||  is_plugin_active( 'wp-mail-bank/wp-mail-bank.php' ) ||  is_plugin_active( 'wp-smtp/wp-smtp.php' )) {
	$psfb_smtp_plugin_active = true;
}

?><div class="wrap">
<div style="float:right;">
	<?php
	if ( !is_plugin_active( 'planso-leads/planso-leads.php' ) && !is_dir(dirname(dirname(dirname(__FILE__))).'/planso-leads/' ) ){
	echo '<a href="'.
		wp_nonce_url(
		    add_query_arg(
		        array(
		            'action' => 'install-plugin',
		            'plugin' => 'planso-leads'
		        ),
		        admin_url( 'update.php' )
		    ),
		    'install-plugin_planso-leads'
		)
	.'" target="_blank" class="btn btn-warning btn-xs" title="'. __('PlanSo Leads is the brand new plugin for capturing new leads from the makers of PlanSo Forms','psfbldr').'"><i class="fa fa-download"></i> '. __('Install PlanSo Leads','psfbldr').'</a>';
	}
	?>
	<a href="https://wordpress.org/support/view/plugin-reviews/planso-forms?rate=5#postform" target="_blank" class="btn btn-success btn-xs"><i class="fa fa-heart"></i> <?php echo __('Like PlanSo Forms? Post a review!','psfbldr'); ?></a>
</div>
<h2><?php
	
	if ( !isset($_REQUEST['psfbid']) || empty($_REQUEST['psfbid']) || $_REQUEST['psfbid'] == -1 ) {
		echo esc_html( __( 'Add New Form', 'psfbldr' ) );
		$post_id = -1;
		$shortcode_out = '';
	} else {
		echo esc_html( __( 'Edit Form', 'psfbldr' ) );

		echo ' <a href="' . esc_url( menu_page_url( 'ps-form-builder-new', false ) ) . '" class="add-new-h2">' . esc_html( __( 'Add New', 'psfbldr' ) ) . '</a>';
		$post_id = $_REQUEST['psfbid'];
		$psform = get_post( $post_id);//, $output, $filter );
		
		if(isset($psform->post_content) && !empty($psform->post_content) && strstr($psform->post_content,'{')){
			$j = json_decode($psform->post_content);
		}
		
		$shortcode_out = '<div><input type="text" onfocus="this.select();" onmouseup="return false;" readonly="readonly" value="[psfb id=&quot;'.$post_id.'&quot; title=&quot;'.$psform->post_title.'&quot;]" class="shortcode-in-list-table wp-ui-text-highlight code form-control" style="cursor:text;color:#32373c;"></div>';
	}
	echo ' <a href="' . esc_url( menu_page_url( 'ps-form-builder', false ) ) . '" class="add-new-h2">' . esc_html( __( 'Back to forms', 'psfbldr' ) ) . '</a>';
	
	do_action('psfb_edit_nav_main_after',$post_id);
	
	
	
?></h2>
<?php
if(!$psfb_smtp_notice_dismiss){
	if(strstr($_SERVER['SERVER_SOFTWARE'],'IIS') && $psfb_smtp_plugin_active == false){
		echo '<div id="psfb_message" class="error psfb_message"><p>'.esc_html(__( 'Attention! As you are using a Microsoft IIS for hosting WordPress an SMTP-Mail plugin is needed for the auto-responder mails to work correctly.', 'psfbldr' )).' <a href="';
						echo wp_nonce_url(
						    add_query_arg(
						        array(
						            'action' => 'install-plugin',
						            'plugin' => 'post-smtp'
						        ),
						        admin_url( 'update.php' )
						    ),
						    'install-plugin_post-smtp'
						);
		echo '" target="_blank" class="btn btn-success btn-xs">'. __('Install Post SMTP','psfbldr').'</a></p>';
		echo '<p><strong><a href="#" class="dismiss-notice psfb_smtp_ignore_notice">Dismiss this notice</a></strong></p></div>';
	}else if(!strstr($_SERVER['SERVER_SOFTWARE'],'IIS') && $psfb_smtp_plugin_active == false){
		echo '<div id="psfb_message" class="updated psfb_message"><p>'.esc_html(__( 'For best results PlanSo Forms recommends you to install an SMTP-Mail plugin like Post SMTP.', 'psfbldr' )).' <a href="';
						echo wp_nonce_url(
						    add_query_arg(
						        array(
						            'action' => 'install-plugin',
						            'plugin' => 'post-smtp'
						        ),
						        admin_url( 'update.php' )
						    ),
						    'install-plugin_post-smtp'
						);
		echo '" target="_blank" class="btn btn-success btn-xs">'. __('Install Post SMTP','psfbldr').'</a></p>';
		echo '<p><strong><a href="#" class="dismiss-notice psfb_smtp_ignore_notice">Dismiss this notice</a></strong></p></div>';
	}
}
do_action( 'psfb_admin_notices' );
?>

<br class="clear" />







<script type="text/javascript">


var fieldtypes = {
	divider_templates : {label:'<?php echo __('Predefined fields','psfbldr'); ?>', type:'divider'},
	name : {label:'<?php echo __('Name','psfbldr'); ?>',type:'text',icon:'fa-user'},
	firstname : {label:'<?php echo __('First name','psfbldr'); ?>',type:'text',icon:'fa-user'},
	lastname : {label:'<?php echo __('Last name','psfbldr'); ?>',type:'text',icon:'fa-user'},
	email : {label:'<?php echo __('Email','psfbldr'); ?>',type:'email',icon:'fa-at'},
	/*landline : {label:'<?php echo __('Landline','psfbldr'); ?>',type:'tel',icon:'fa-phone'},*/
	mobil : {label:'<?php echo __('Mobile phone','psfbldr'); ?>',type:'tel',icon:'fa-mobile'},
	tel : {label:'<?php echo __('Phone','psfbldr'); ?>',type:'tel',icon:'fa-phone'},
	divider_generic : {label:'<?php echo __('Generic fields','psfbldr'); ?>', type:'divider'},
	text : {label:'<?php echo __('Text','psfbldr'); ?>',type:'text',icon:'fa-font'},
	textarea : {label:'<?php echo __('Multiline text','psfbldr'); ?>',type:'textarea',rows:true,cols:true,icon:'fa-font'},
	number : {label:'<?php echo __('Number','psfbldr'); ?>',type:'number',min:true,max:true,step:true,icon:'#'},
	divider_date : {label:'<?php echo __('Date and time fields','psfbldr'); ?>', type:'divider'},
	date : {label:'<?php echo __('Date','psfbldr'); ?>',type:'date',icon:'fa-calendar'},
	time : {label:'<?php echo __('Time','psfbldr'); ?>',type:'time',icon:'fa-clock-o'},
	datetime : {label:'<?php echo __('Date and time','psfbldr'); ?>',type:'datetime',icon:'fa-calendar'},
/*
	week : {label:'<?php echo __('Week','psfbldr'); ?>',type:'week'},
	month : {label:'<?php echo __('Month','psfbldr'); ?>',type:'month'},
*/
	divider_select : {label:'<?php echo __('Select fields','psfbldr'); ?>', type:'divider'},
	select : {label:'<?php echo __('Select','psfbldr'); ?>',type:'select',icon:'fa-caret-square-o-down'},
	multiselect : {label:'<?php echo __('Multiselect','psfbldr'); ?>',type:'select',multiple:true,icon:'fa-caret-square-o-down'},
	radio : {label:'<?php echo __('Radio','psfbldr'); ?>',type:'radio',icon:'fa-dot-circle-o'},
	checkbox : {label:'<?php echo __('Checkbox','psfbldr'); ?>',type:'checkbox',icon:'fa-check-square-o'},
	divider_special : {label:'<?php echo __('Special fields','psfbldr'); ?>', type:'divider'},
/*
	range : {label:'<?php echo __('Range','psfbldr'); ?>',type:'range',min:true,max:true,step:true},
	search : {label:'<?php echo __('Search','psfbldr'); ?>',type:'search'},
	hidden : {label:'<?php echo __('Hidden','psfbldr'); ?>',type:'hidden'},
*/
	file : {label:'<?php echo __('Single file','psfbldr'); ?>',type:'file',icon:'fa-file'},
	multifile : {label:'<?php echo __('Multiple files','psfbldr'); ?>',type:'file',multiple:true,icon:'fa-folder-open'},
	url : {label:'<?php echo __('Url','psfbldr'); ?>',type:'url',icon:'fa-link'},
/*
	color : {label:'<?php echo __('Color','psfbldr'); ?>',type:'color'},
*/
	divider_html : {label:'<?php echo __('HTML Tags','psfbldr'); ?>', type:'divider'},
	
	html_div : {label:'<?php echo __('HTML Code','psfbldr'); ?>', type:'div',wrap:true,icon:'fa-terminal'},
	html_hr : {label:'<?php echo __('Horizontal Divider','psfbldr'); ?>', type:'hr',wrap:false,icon:'fa-minus'},
	html_header : {label:'<?php echo __('Headline','psfbldr'); ?>', type:'h',options:[1,2,3,4,5,6],wrap:true,icon:'fa-header'},
	html_paragraph : {label:'<?php echo __('Paragraph','psfbldr'); ?>', type:'p',wrap:true,icon:'fa-paragraph'},
	
	divider_buttons : {label:'<?php echo __('Submit buttons','psfbldr'); ?>', type:'divider'},
/*
	button : {label:'<?php echo __('Button','psfbldr'); ?>',type:'button'},
*/
	submit : {label:'<?php echo __('Submit button','psfbldr'); ?>',type:'submit'}
/*
	imagesubmit : {label:'<?php echo __('Submit-Image','psfbldr'); ?>',type:'image'}
*/
};

var specialfields = [
	'select',
	'checkbox',
	'radio',
	'textarea',
	'multiselect',
	'textarea',
	'submit',
	'submitimage'
];	
var htmlfields = [
	'html_hr',
	'html_header',
	'html_paragraph',
	'html_div'
];	
var selectfields = [
	'select',
	'checkbox',
	'radio',
	'multiselect'
];	
var noplaceholderfields = [
	'select',
	'checkbox',
	'radio',
	'multiselect',
	'submit',
	'submitimage',
	'file',
	'multifile'
];	
var noiconfields = [
	'checkbox',
	'radio',
	'submit',
	'submitimage'/*,
	'select',
	'multiselect',
	'textarea',
	'file',
	'multifile'*/
];

var customfields = [];
var customelements = [];


var dragcontroller = {};
<?php do_action('psfb_edit_js_before_document_ready'); ?>

jQuery(document).ready(function($){
	
	$('.psfb_smtp_ignore_notice').click(function(){
		var dat = {
			action:'psfb_edit_update_smtp_notice_dismiss',
		};
		var me = $(this);
		$.ajax({
			url:ajaxurl,
			data:dat,
			type:'post',
			dataType:'json',
			success:function(r){
				me.closest('.psfb_message').remove();
			},
			error:function(o,r){
				alert('Could not store dismissal');
			}
		});
	});
	
	
	
	$('body').css('background-color','inherit');
	if( $('div.updated.fade').length > 0){
		$('div.updated.fade').css('opacity','1');
	}
	psfb_affix();

	$(window).on("resize", function () {
		psfb_affix();
	});
	
	$.each(fieldtypes,function(key, val){
		//console.log(val.type);
		if(val.type == 'divider'){
			$( '#main_right_container').append('<h4>'+val.label+'</h4>');
		} else {
			var b = '<button data-type="'+ key +'" class="btn btn-default btn-sm">';
			if(typeof val.icon!='undefined'){
				if(val.icon.indexOf('fa-')!=-1){
					b += '<span class="fa '+val.icon+'"></span> ';
				} else {
					b += '<span>'+val.icon+'</span> ';
				}
			}
			b += ''+val.label+'</button>';
			$('#main_right_container').append(b);
			
			if($('#main_right_container button[data-type="signature"]').length > 0){
				$('#main_right_container button[data-type="signature"]').detach().insertAfter('#main_right_container button[data-type="url"]');
			}
			
		}
	});
	
	
	
	
	if( $('#psfb_json').val().length>0 && $('#psfb_json').val().indexOf('[{') != -1){
		var jf = JSON.parse( $('#psfb_json').val() );
		if(typeof jf.mails!='undefined' && typeof jf.mails.user_mail!='undefined' && typeof jf.mails.user_mail.html_mail!='undefined' && jf.mails.user_mail.html_mail==true){
			$('#psfb_user_mail_html').prop('checked','checked');
		}
		if(typeof jf.mails!='undefined' && typeof jf.mails.admin_mail!='undefined' && typeof jf.mails.admin_mail.html_mail!='undefined' && jf.mails.admin_mail.html_mail==true){
			$('#psfb_admin_mail_html').prop('checked','checked');
		}
		if(typeof jf.link_love!='undefined' && jf.link_love==true){
			$('#ps_link_love').prop('checked','checked');
		}
		if(typeof jf.planso_style!='undefined' && jf.planso_style==true){
			$('#planso_style').prop('checked','checked');
		}
		if(typeof jf.allow_prefill!='undefined' && jf.allow_prefill==true){
			$('#allow_prefill').prop('checked','checked');
		}
		if(typeof jf.javascript_antispam=='undefined' || jf.javascript_antispam==true){
			$('#javascript_antispam').prop('checked','checked');
		}
		if(typeof jf.horizontal_form!='undefined'){
			if(jf.horizontal_form==true || jf.horizontal_form=='horizontal'){
				/*$('#horizontal_form').prop('checked','checked');*/
				$('#horizontal_form').val('horizontal');
			} else if(jf.horizontal_form==false || jf.horizontal_form=='vertical'){
				$('#horizontal_form').val('vertical');
			} else {
				$('#horizontal_form').val(jf.horizontal_form);
			}
		} else {
			$('#horizontal_form').val('vertical');
		}
		if(typeof jf.clean_attachments!='undefined' && jf.clean_attachments==true){
			$('#clean_attachments').prop('checked','checked');
		}
		if(typeof jf.datepicker!='undefined'){
			$('#psfb_datepicker').val(jf.datepicker);
		}
		if(typeof jf.date_format!='undefined'){
			$('#psfb_date_format').val(jf.date_format);
		}
		var j = jf.fields;
		$.each(j,function(k,v){
			$.each(v,function(i,val){
				ps_field_drop( false, false, i, val, false );
			});
		});
	}
	
 	
	$( '#main_right_container .btn' ).css({'cursor':'move'}).click(function(){
		var mytype = $(this).attr('data-type');
		var val = fieldtypes[mytype];
		var ui = {draggable: $(this) };
		ps_field_drop( false, ui, false, false, false );
	}).draggable({
		appendTo: 'body',
    helper: 'clone',
    addClasses: false,
    cancel: false,
    refreshPositions:true,
		start:function(event,ui){
			if( $('.form_builder_stage').is(':empty') ){
				var height = $('.form_builder_stage').height();
			} else {
				var height = 50;
			}
		  
		 	$('.form_builder_stage').append( '<div class="empty_helper_row row"><div style="height:'+height+'px;" class="droparea field_container col-md-12"></div></div>' );
		 	
		 	
			$('.field_container').droppable({
				greedy:false,
				tolerance:'intersect',
				addClasses: false,
				accept:'.btn',
				hoverClass: 'bg-success',
				over: function(event,ui){
					
  				var droppableElement = $(this);
  				var row = droppableElement.closest('.row');
  				if(droppableElement.hasClass('droparea')){
  					//ist leeres element - darf gedroppt werden
  				} else {
						ps_remove_dropareas();
	  				var cont_cnt = row.find('.field_container').length;
	  				var col_cls = Math.floor(12/(parseInt(cont_cnt)+2));
	  				
		    		row.find('.field_container').each(function(){
		    			$(this).attr('class','field_container').addClass('col-md-'+ col_cls +'');
		    		});
		    		
		    		$('<div class="droparea field_container col-md-'+ col_cls +'"></div>').insertBefore(droppableElement);
		    		$('<div class="droparea field_container col-md-'+ col_cls +'"></div>').insertAfter(droppableElement);
	  				$( '<div class="row"><div style="height:50px;" class="droparea field_container col-md-12"></div></div>' ).insertBefore(row);
	  				$( '<div class="row"><div style="height:50px;" class="droparea field_container col-md-12"></div></div>' ).insertAfter(row);
	  				
				  	$( '.form_builder_stage .droparea' ).each(function(){
				  		$(this).height( $(this).parent().height() );
				  	});
				  	
	  				$('.droparea').droppable({
	  					greedy:false,
							addClasses: false,
							hoverClass: 'bg-success',
							tolerance:'intersect',//touch,intersect,pointer,fit
							
							drop:function(event,ui){
					    	dragcontroller.dropped = true;
					      ps_field_drop( event, ui, $(this), false, false );
					      $('.droparea').droppable('destroy');
			  				ps_remove_dropareas();
							}
						});
						
	  			}
				},
				drop: function(event,ui){
					
					if( $(this).hasClass('droparea') ){
						try{$('.field_container').droppable('destroy');}catch(e){}
			    	dragcontroller.dropped = true;
			      ps_field_drop( event, ui, $(this), false, false );
			      
	  			}
	  			ps_remove_dropareas();
  				
				}
				
			});
		},
		stop:function(){
			if( $('.empty_helper_row').is(':empty') ){
				$('.empty_helper_row').remove();
			} else {
				$('.empty_helper_row').removeClass('empty_helper_row');
			}
			ps_remove_dropareas();
		}
	});
	
	$('.psfb_open_help_modal').click(function(){
		$('#psfb_help_modal').modal('show');
	});
	$('.psfb_save_perform').click(function(){
		$('.psfb_save_html').trigger('click');
	});
	$('.psfb_save_html').click(function(){
		$('.psfb_generate_json').trigger('click');
		$('#psfb_html').val( $( '.form_builder_stage' ).html() );
	});
	
	$('.psfb_test_form_submit').click(function(){
		$('.psfb_generate_json').trigger('click');
		
		var data = {
			'action': 'psfb_form_submit_test',
			'psfb_form_submit_test_values': 'doit',
			'psfb_test_json': $('#psfb_json').val()
		};
		
		$.post(ajaxurl, data, function(response) {
			alert('<?php echo __('Test submission sent. Please check your email or API target to validate the result.','psfbldr'); ?>');
		});
	});
	
	
	$('.psfb_generate_json').click(function(){
		var j = [];
		$('.form_builder_stage>div.row').each(function(){

			var rind = $(this).index();
			j[rind] = {};			
			
			$(this).find('.field_container').each(function(){
				var mytype = $(this).attr('data-type');
				var mid = $(this).attr('data-id');
				var ind = $(this).index();
				//console.log('mytype:'+mytype);
				j[rind][ind] = {};			
				j[rind][ind].type = mytype;
				j[rind][ind].id = mid;
				
				if( $.inArray(mytype,htmlfields) != -1){
					
					var tag_details = fieldtypes[mytype];
					j[rind][ind].type=tag_details.type;
					var tag = tag_details.type;
					if(typeof tag_details.options != 'undefined' && tag_details.options.length>0){
						j[rind][ind].option = tag_details.options[0];
						tag += tag_details.options[0];
					}
					
					
					if(typeof tag_details.wrap != 'undefined' && tag_details.wrap===true){
						j[rind][ind].content = $(this).find('#field'+mid+'').find(tag).first().html();
					} else {
						
					}
					var myclass = '';
					try{
						myclass = $(this).find('#field'+mid+'').find(tag).first().attr('class').replace('form-control','');
					}catch(e){}
					
					var style = '';
					try{
						style = $(this).find('#field'+mid+'').find(tag).first().attr('style');
					}catch(e){}
						
						
				} else {
				
					if(mytype.indexOf('submit')!=-1){
						var label = $(this).find('#field'+mid+'').html();
						/*
						var label = $(this).find('#field'+mid+'').attr('value');
						*/
					} else {
						var label = $(this).find('label[for="field'+mid+'"]').html();
					}
					var help_text = $(this).find('.help-block').html();
					
					var myclass = '';
					try{
						myclass = $(this).find('#field'+mid+'').attr('class').replace('form-control','');
					}catch(e){}
						
					var placeholder = '';
					try{
						placeholder = $(this).find('#field'+mid+'').attr('placeholder');
					}catch(e){}
						
					var default_value = '';
					try{
						default_value = $(this).find('#field'+mid+'').attr('value');
					}catch(e){}
						
					var maxlength = '';
					try{
						maxlength = $(this).find('#field'+mid+'').attr('maxlength');
					}catch(e){}
					//console.log('maxlength:'+maxlength);
					var number_max = '';
					try{
						number_max = $(this).find('#field'+mid+'').attr('max');
					}catch(e){}
					var number_min = '';
					try{
						number_min = $(this).find('#field'+mid+'').attr('min');
					}catch(e){}	
					var name = '';
					try{
						name = $(this).find('#field'+mid+'').attr('name');
					}catch(e){}
					
					if(mytype=='checkbox' || mytype=='radio'){
						var required = $(this).find('#field'+mid+'').attr('data-required');
						var orientation = $(this).find('#field'+mid+'').attr('data-orientation');
					} else {
						var required = $(this).find('#field'+mid+'').prop('required');
						var orientation = false;
					}
					if (typeof required != 'undefined' && (required == true || required=='true' || required=='required')) {
						required = true;
						label = label.replace('*','');
					} else {
						required = false;
					}
					
					var  hide_label = $(this).find('#field'+mid+'').attr('data-hide_label');
					if (typeof hide_label != 'undefined' && (hide_label == true || hide_label=='true' || hide_label=='1')) {
						hide_label = true;
					} else {
						hide_label = false;
					}
					var readonly = $(this).find('#field'+mid+'').attr('readonly');
					if (typeof readonly !== 'undefined' && readonly !== '') {
						
					} else {
						readonly = '';
					}
					var disabled = $(this).find('#field'+mid+'').attr('disabled');
					if (typeof disabled !== 'undefined' && disabled !== '') {
						
					} else {
						disabled = '';
					}
					
					var  style = $(this).find('#field'+mid+'').attr('style');
					if (typeof style !== 'undefined' && style !== false) {
						
					} else {
						style = '';
					}
					
					var icon = '';
					if( $(this).find('.input-group').length > 0 ){
						icon = $(this).find('.input-group span.fa').attr('class').replace('fa ','');
					}
					
					
				}
				
				var  cond = $(this).attr('data-condition');
				if (typeof cond != 'undefined') {
					j[rind][ind].condition = cond;
				}
				
				if(typeof orientation != 'undefined' && orientation !== false){
					j[rind][ind].orientation = orientation;
				}
				if(typeof label != 'undefined'){
					j[rind][ind].label = label;
				}
				if(typeof help_text != 'undefined'){
					j[rind][ind].help_text = help_text;
				}
				if(typeof myclass != 'undefined'){
					j[rind][ind].class = myclass;
				}
				if(typeof style != 'undefined'){
					j[rind][ind].style = style;
				}
				if(typeof readonly != 'undefined'){
					j[rind][ind].readonly = readonly;
				}
				if(typeof disabled != 'undefined'){
					j[rind][ind].disabled = disabled;
				}
				if(typeof required != 'undefined'){
					j[rind][ind].required = required;
				}
				if(typeof hide_label != 'undefined'){
					j[rind][ind].hide_label = hide_label;
				}
				if(typeof placeholder != 'undefined'){
					j[rind][ind].placeholder = placeholder;
				}
				if(typeof default_value != 'undefined'){
					j[rind][ind].default_value = default_value;
				}
				if(typeof maxlength != 'undefined'){
					j[rind][ind].maxlength = maxlength;
				}
				if(typeof number_max != 'undefined'){
					j[rind][ind].number_max = number_max;
				}
				if(typeof number_min != 'undefined'){
					j[rind][ind].number_min = number_min;
				}
				if(typeof icon != 'undefined'){
					j[rind][ind].icon = icon;
				}
				if(typeof name != 'undefined'){
					j[rind][ind].name = name;
				}
				
				<?php do_action( 'psfb_edit_js_save_attributes_to_object' ); ?>
				
	 			if( $.inArray(mytype,customfields)!= -1 ){
	 				
	 				<?php do_action( 'psfb_save_customfields' ); ?>
	 			} else if( $.inArray(mytype,customelements)!= -1 ){
	 				
	 				<?php do_action( 'psfb_save_customelements' ); ?>
	 			} else if( $.inArray(mytype,selectfields)!= -1 ){
	 				var opts = [];
	 				if(mytype=='select' || mytype=='multiselect'){
	 					
	 					$(this).find('select option').each(function(i){
	 						opts[i] = {};
	 						opts[i].label = $(this).text();
	 						opts[i].val = $(this).attr('value');
	 						opts[i].empty = $(this).attr('data-empty');
	 						
	 						if(typeof $(this).attr('selected') != 'undefined' && $(this).attr('selected') !=''){
	 							opts[i].selected = $(this).attr('selected');
	 						}
	 						
	 					});
	 				} else if(mytype=='checkbox'){
	 					
	 					$(this).find('.checkbox_wrapper label').each(function(i){
	 						opts[i] = {};
	 						opts[i].label = $(this).text();
	 						opts[i].val = $(this).find('input').attr('value');
	 						opts[i].disabled = $(this).find('input').attr('disabled');
	 						if(typeof $(this).find('input').attr('checked') != 'undefined' && $(this).find('input').attr('checked') !=''){
	 							opts[i].checked = $(this).find('input').attr('checked');
	 						}
	 						j[rind][ind].name = $(this).find('input').attr('name');
	 					});
	 				} else if(mytype=='radio'){
	 					
	 					$(this).find('.radio_wrapper label').each(function(i){
	 						opts[i] = {};
	 						opts[i].label = $(this).text();
	 						opts[i].val = $(this).find('input').attr('value');
	 						opts[i].empty = $(this).find('input').attr('date-empty');
	 						opts[i].disabled = $(this).find('input').attr('disabled');
	 						if(typeof $(this).find('input').attr('checked') != 'undefined' && $(this).find('input').attr('checked') !=''){
	 							opts[i].checked = $(this).find('input').attr('checked');
	 						}
	 						j[rind][ind].name = $(this).find('input').attr('name');
	 						
	 					});
	 				}
	 				j[rind][ind].select_options = opts;
	 			} else {
	 				//no select field
	 			}
			});
		});
		
		
		var jj = {fields:j};
		
		jj.mails = {};
		jj.mails.admin_mail = {};
		jj.mails.admin_mail.content = $('#admin_mail_content').val();
		jj.mails.admin_mail.subject = $('#admin_mail_subject').val();
		if(typeof $('#admin_mail_from_name').val() != 'undefined' && $('#admin_mail_from_name').val() != ''){
			jj.mails.admin_mail.from_name = $('#admin_mail_from_name').val();
		}
		if(typeof $('#admin_mail_from_email').val() != 'undefined' && $('#admin_mail_from_email').val() != ''){
			jj.mails.admin_mail.from_email = $('#admin_mail_from_email').val();
		}
		jj.mails.admin_mail.reply_to = $('#admin_mail_reply_to').val();
		
		if($('#admin_mail_recipients').val().indexOf(';') != -1){
			jj.mails.admin_mail.recipients = $('#admin_mail_recipients').val().split(';');
		}else if($('#admin_mail_recipients').val().indexOf(',') != -1){
			jj.mails.admin_mail.recipients = $('#admin_mail_recipients').val().split(',');
		}else if($('#admin_mail_recipients').val().indexOf(' ') != -1){
			jj.mails.admin_mail.recipients = $('#admin_mail_recipients').val().split(',');
		}else{
			jj.mails.admin_mail.recipients = [];
			jj.mails.admin_mail.recipients.push($('#admin_mail_recipients').val());
		}
		
		if(typeof $('#admin_mail_bcc').val() != 'undefined' && $('#admin_mail_bcc').val() != ''){
			if($('#admin_mail_bcc').val().indexOf(';') != -1){
				jj.mails.admin_mail.bcc = $('#admin_mail_bcc').val().split(';');
			}else if($('#admin_mail_bcc').val().indexOf(',') != -1){
				jj.mails.admin_mail.bcc = $('#admin_mail_bcc').val().split(',');
			}else if($('#admin_mail_bcc').val().indexOf(' ') != -1){
				jj.mails.admin_mail.bcc = $('#admin_mail_bcc').val().split(',');
			}else{
				jj.mails.admin_mail.bcc= [];
				jj.mails.admin_mail.bcc.push($('#admin_mail_bcc').val());
			}
		}
		
		jj.mails.user_mail = {};
		jj.mails.user_mail.content = $('#user_mail_content').val();
		jj.mails.user_mail.subject = $('#user_mail_subject').val();
		if(typeof $('#user_mail_from_name').val() != 'undefined' && $('#user_mail_from_name').val() != ''){
			jj.mails.user_mail.from_name = $('#user_mail_from_name').val();
		}
		if(typeof $('#user_mail_from_email').val() != 'undefined' && $('#user_mail_from_email').val() != ''){
			jj.mails.user_mail.from_email = $('#user_mail_from_email').val();
		}
		jj.mails.user_mail.reply_to = $('#user_mail_reply_to').val();
		if($('#user_mail_recipients').val().indexOf(';') != -1){
			jj.mails.user_mail.recipients = $('#user_mail_recipients').val().split(';');
		}else if($('#user_mail_recipients').val().indexOf(',') != -1){
			jj.mails.user_mail.recipients = $('#user_mail_recipients').val().split(',');
		}else if($('#user_mail_recipients').val().indexOf(' ') != -1){
			jj.mails.user_mail.recipients = $('#user_mail_recipients').val().split(',');
		}else{
			jj.mails.user_mail.recipients= [];
			jj.mails.user_mail.recipients.push($('#user_mail_recipients').val());
		}
		
		if(typeof $('#user_mail_bcc').val() != 'undefined' && $('#user_mail_bcc').val() != ''){
			if($('#user_mail_bcc').val().indexOf(';') != -1){
				jj.mails.user_mail.bcc = $('#user_mail_bcc').val().split(';');
			}else if($('#user_mail_bcc').val().indexOf(',') != -1){
				jj.mails.user_mail.bcc = $('#user_mail_bcc').val().split(',');
			}else if($('#user_mail_bcc').val().indexOf(' ') != -1){
				jj.mails.user_mail.bcc = $('#user_mail_bcc').val().split(',');
			}else{
				jj.mails.user_mail.bcc = [];
				jj.mails.user_mail.bcc.push($('#user_mail_bcc').val());
			}
		}
		
		
		if( $('#psfb_admin_mail_html').length>0 ){
			if( $('#psfb_admin_mail_html').is(':checked') ){
				jj.mails.admin_mail.html_mail = true;
			} else {
				jj.mails.admin_mail.html_mail = false;
			}
		}
		if( $('#psfb_user_mail_html').length>0){
			if( $('#psfb_user_mail_html').is(':checked') ){
				jj.mails.user_mail.html_mail = true;
			} else {
				jj.mails.user_mail.html_mail = false;
			}
		}
		if( $('#ps_link_love').is(':checked') ){
			jj.link_love = true;
		} else {
			jj.link_love = false;
		}
		if( $('#planso_style').is(':checked') ){
			jj.planso_style = true;
		} else {
			jj.planso_style = false;
		}
		if( $('#allow_prefill').is(':checked') ){
			jj.allow_prefill = true;
		} else {
			jj.allow_prefill = false;
		}
		if( $('#javascript_antispam').is(':checked') ){
			jj.javascript_antispam = true;
		} else {
			jj.javascript_antispam = false;
		}
		if( $('#clean_attachments').is(':checked') ){
			jj.clean_attachments = true;
		} else {
			
		}
		
		jj.horizontal_form = $('#horizontal_form').val();
		
		jj.datepicker = $('#psfb_datepicker').val();
		
		jj.date_format = $('#psfb_date_format').val();
		
		jj.thankyou_page_url = $('#thankyou_page_url').val();
		
		jj.form_success_message = $('#form_success_message').val();
		
		jj.style_success_color = $('#style_success_color').val();
		
		jj.form_error_message = $('#form_error_message').val();
		
		jj.style_error_color = $('#style_error_color').val();
		
		jj.form_warning_message = $('#form_warning_message').val();
		
		jj.style_warning_color = $('#style_warning_color').val();
		
		jj.form_field_required_warning_message = $('#form_field_required_warning_message').val();
		
		jj.form_file_upload_error_message = $('#form_file_upload_error_message').val();
		
		jj.form_field_invalid_email_message = $('#form_field_invalid_email_message').val();
		
		jj.form_field_invalid_url_message = $('#form_field_invalid_url_message').val();
		
		
		
		
		if( $('input.zapier_url').length > 0){
			jj.zapier_url = [];
			$('input.zapier_url').each(function(i){
				if( $(this).val() != ''){
					jj.zapier_url[i] = $(this).val();
				}
			});
		}
		if( $('input.pushover_user').length > 0){
			jj.pushover_user =  $('input.pushover_user').val();
		}
		if( $('select.pushover_sound').length > 0){
			jj.pushover_sound =  $('select.pushover_sound').val();
		}
		<?php do_action('psfb_edit_js_before_save_json'); ?>
		
		$('#psfb_title').val( $('.psfb_title_input').val() );
		$('#psfb_json').val( JSON.stringify( jj ) );
	});
	
	
	$('#admin_mail_content,#user_mail_content,#admin_mail_subject,#user_mail_subject,#admin_mail_recipients,#user_mail_recipients,#admin_mail_bcc,#user_mail_bcc,#admin_mail_from_email,#user_mail_from_email,#admin_mail_from_name,#user_mail_from_name,#admin_mail_reply_to,#user_mail_reply_to').focusout(function(){
		if( $(this).attr('id').indexOf('admin') != -1){
			var mode = 'admin';
		} else {
			var mode = 'user';
		}
		$('.ps_add_variable[data-mode="'+mode+'"]').data('field', $(this).attr('id')).data('position', $(this).getCursorPosition() );
		
	});
	
	<?php do_action('psfb_edit_js_document_ready'); ?>
});
function ps_remove_dropareas(){
	var $ = jQuery;
	$( '.form_builder_stage .droparea' ).each(function(){
		if( $(this).parent().find('.field_container').length > 1){
			$(this).remove();
		} else {
			$(this).parent().remove();
		}
	});
	$( '.form_builder_stage .row').each(function(){
		var cont_cnt = $(this).find('.field_container').length;
		$(this).find('.field_container').each(function(){
			$(this).attr('class','field_container').addClass('col-md-'+ Math.floor(12/(parseInt(cont_cnt)))+'');
		});
 	});
}
function psfb_handle_edit_special_tabs_closing(){
	var $ = jQuery;
	$('#fieldeditor .selectoptionstab').hide();
	
	
	<?php do_action('psfb_edit_js_close_edit_tabs'); ?>
}
function ps_field_drop( event, ui, target, j, createcol ){
	var $ = jQuery;
	if(j == false){
		var me = ui.draggable;
	  var mytype = me.attr('data-type');
	  var myLabel = fieldtypes[mytype].label;
	  //console.log(ui);
	  //console.log(target);
	  //console.log(event);
	  if( $(target).hasClass('droparea') ){
	  	//console.log('I am in droparea');
	  	if( $(target).parent().find('.field_container').length > 1){
	  		//console.log('I am in existing row');
	  		var row_mode = 'col';
	  	} else {
	  		//console.log('I am in new row');
	  		var row_mode = 'new';
	  	}
	  } else {
	  	row_mode = 'plain';
	  }
	} else {
		//INTRODUCE new mytype property to be absolutely sure
		var mytype = j.type;
		$.each(fieldtypes,function(k,v){
			if( v.type == j.type && k.indexOf('html_')!=-1){
				mytype = k;
			}
		});
		
		var myLabel = j.label;
		if(target==0){
	  	var row_mode = 'plain';
	  } else {
	  	var row_mode = 'plain_col';
	  }
	}
	//console.log(row_mode);
  var row = '';
  var dynID = $( '.form_builder_stage .form-group' ).length;
  while( $('#field'+dynID).length > 0 ){
  	dynID ++;
  }
  var myFieldType = fieldtypes[mytype];
  //var myLabel = myFieldType.label;
  //console.log(target);
  
  if(row_mode=='plain'){
 		row += '<div class="row" data-type="'+mytype+'" data-id="'+dynID+'">';
  }
  row += '<div class="col-md-12 field_container" data-type="'+mytype+'" data-id="'+dynID+'"';
  
  if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
  	row += ' data-hide_label="true"';
  }
  if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
  	row += ' data-required="required"';
  }
  if(typeof j.readonly!='undefined' && j.readonly=='readonly'){
  	row += ' readonly="readonly"';
  }
  if(typeof j.disabled != 'undefined' && j.disabled=='disabled'){
  	row += ' disabled="disabled"';
  }
  if(typeof j.style!='undefined' && j.style!=''){
  	row += ' data-style="'+j.style+'"';
  }
  if(typeof j.class!='undefined' && j.class!=''){
  	row += ' data-class="'+j.class+'"';
  }
  if(typeof j.icon!='undefined' && j.icon!=''){
  	row += ' data-icon="'+j.icon+'"';
  }
  
  row += '>';
  
  
  
  row += '<div class="options">'+$('.editoptions_template').html()+'</div>';
  
  row += '<div class="form-group">';
  
  if(mytype != 'submit' && mytype!='submitimage' && mytype.substr(0,5)!='html_'){
  	
	  row += '<label for="field'+dynID+'" class="field_label"';
	  if(typeof j.hide_label!='undefined' && j.hide_label==true){
	  	row += ' style="display:none;"';
	  }
	  row += '>'+myLabel;
	  
		if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
			row += '*';
	  }
		row += '</label>';
	}
  if( $.inArray(mytype,customfields) != -1 ){
  	
  	<?php do_action('psfb_edit_js_customfields_create'); ?>
  	
  } else if( $.inArray(mytype,customelements) != -1 ){
  	
  	<?php do_action('psfb_edit_js_customelements_create'); ?>
  	
  } else if( $.inArray(mytype,htmlfields)!= -1 ){
		
		var tag_details = fieldtypes[mytype];
		row += '<div class="psfb_html_content" id="field'+dynID+'" data-tag="'+tag_details.type+'"';
		if(typeof tag_details.options != 'undefined'){
			row += ' data-options="'+JSON.stringify(tag_details.options)+'"';
		}
		row += '>';
		if(typeof tag_details.wrap != 'undefined' && tag_details.wrap===true){
			row += '<'+tag_details.type+'';
			
			if(typeof tag_details.options != 'undefined'){
				if(typeof j.option != 'undefined'){
					row += j.option;
				} else {
					row += tag_details.options[0];
				}
			}
	    if(typeof j.style!='undefined' && j.style!=''){
	    	row += ' style="'+j.style+'"';
	    }
	    if(typeof j.class!='undefined' && j.class!=''){
	    	row += ' class="'+j.class+'"';
	    }
     	
			row += '>';
			if(typeof j.content != 'undefined'){
				row += j.content;
			} else {
				row += '<?php echo __('YOUR CONTENT GOES HERE','psfbldr'); ?>';
			}
			row += '</'+tag_details.type+'';
			if(typeof tag_details.options != 'undefined'){
				if(typeof j.option != 'undefined'){
					row += j.option;
				} else {
					row += tag_details.options[0];
				}
			}
		
			row += '>';
			
		} else {
			row += '<'+tag_details.type+'';
			
	    if(typeof j.style!='undefined' && j.style!=''){
	    	row += ' style="'+j.style+'"';
	    }
	    if(typeof j.class!='undefined' && j.class!=''){
	    	row += ' class="'+j.class+'"';
	    }
	   
	    row += '/>';
		}
		
		
		row += '</div>';
    	
		
  } else {
  	if( $.inArray(mytype,specialfields)!= -1 ){
	    //special cases
	    if(mytype == 'textarea'){
	    	if(typeof j.icon!='undefined' && j.icon!='' && j.icon!='undefined'){
		    	//console.log(j.icon);
			  	row += '<div class="input-group">';
			  	row += '<div class="input-group-addon">';
			  	row += '<span class="fa '+j.icon+'"></span>';
			  	row += '</div>';
		  	}
	    	row += '<textarea id="field'+dynID+'" class="form-control';
		    if(typeof j.class!='undefined' && j.class!=''){
		    	row += j.class;
		    }
		    row += '"';
		    if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
		    	row += ' required="required"';
		    }
		    if(typeof j.readonly!='undefined' && j.readonly=='readonly'){
		    	row += ' readonly="readonly"';
		    }
		    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
		    	row += ' data-hide_label="true"';
		    }
		    if(typeof j.style!='undefined' && j.style!=''){
		    	row += ' style="'+j.style+'"';
		    }
		    if(typeof j.cols!='undefined' && j.cols!=''){
		    	row += ' cols="'+j.cols+'"';
		    }
		    if(typeof j.rows!='undefined' && j.rows!=''){
		    	row += ' rows="'+j.rows+'"';
		    }
		    if(typeof j.placeholder!='undefined' && j.placeholder!=''){
		    	row += ' placeholder="'+j.placeholder+'"';
		    }
		    
			  if(typeof j.maxlength!='undefined' && j.maxlength!=''){
		    	row += ' maxlength="'+j.maxlength+'"';
		    }
		    
		    if(typeof j.name!='undefined' && j.name!='' && j.name!='undefined'){
		    	row += ' name="'+j.name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
		    } else {
					row += ' name="'+myLabel.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
		    }
		    
		    <?php do_action('psfb_edit_js_attributes_create'); ?>
		    
		    row += '>';
		    if(typeof j.default_value!='undefined' && j.default_value!=''){
		    	row += j.default_value;
		    }
		    row += '</textarea>';
		    if(typeof j.icon!='undefined' && j.icon!='' && j.icon!='undefined'){
			  	row += '</div>';
		  	}
	    } else if(mytype == 'submit' || mytype == 'button' || mytype == 'reset' || mytype=='submitimage'){
	    	if(mytype!='submitimage'){
	    		row += '<button type="'+myFieldType.type+'" id="field'+dynID+'"';
	    	} else {
	    		row += '<input type="'+myFieldType.type+'" id="field'+dynID+'"';
	    	}
	    	row += ' class="';
	    	if(mytype=='submit'){/* || (typeof j.src=='undefined' || j.src=='')){*/
	    		if(typeof j.class=='undefined' || j.class==null || $.trim(j.class)=='' || j.class.indexOf('btn btn-primary')!=-1){
	    			row += 'btn btn-primary';
	    		}
	    		
	    	}
	    	
		    if(typeof j.class!='undefined' && j.class!=''){
		    	if(j.class.indexOf('btn-primary')!=-1){
		    		j.class = j.class.replace(/btn-primary/g,'',j.class);
		    	}
		    	if(j.class.indexOf('btn ')!=-1){
		    		j.class = j.class.replace(/btn /g,'',j.class);
		    	}
		    	row += ' '+j.class;
		    }
		    row += '"';
		    
		    if(typeof j.force_label!='undefined' && (j.force_label==true || j.force_label=='true' || j.force_label=='1')){
		    	row += ' data-force_label="true"';
		    }
		    if(typeof j.style!='undefined' && j.style!=''){
		    	row += ' style="'+j.style+'"';
		    }
		    if(mytype=='submitimage' && typeof j.src!='undefined' && j.src!=''){
			    if(typeof j.label!='undefined' && j.label!=''){
			    	row += ' value="'+j.label+'"';
			    } else {
			    	row += ' value="<?php echo __('Submit','psfbldr'); ?>"';
			    }
		    	row += ' src="'+j.src+'"';
		    }
		    <?php do_action('psfb_edit_js_attributes_create'); ?>
		    if(mytype!='submitimage'){
			    row += '>';
			    if(typeof j.label!='undefined' && j.label!=''){
			    	row += j.label;
			    } else {
			    	row += '<?php echo __('Submit','psfbldr'); ?>';
			    }
			    row += '</button>';
			  } else {
			  	row +='/>';
			  }
	    } else if(mytype == 'radio'){
	    	if(typeof j.orientation!='undefined' && j.orientation!==false){
	    		var orientation = j.orientation;
	    	} else {
	    		var orientation = 'horizontal';
	    	}
	    	if(orientation=='horizontal'){
	    		var wrap_div = false;
	    	} else {
	    		var wrap_div = true;
	    	}
	    	
	    	
			  if(typeof j.name!='undefined' && j.name!='' && j.name!='undefined'){
			  	var tmp_name = j.name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_');
			  } else {
			  	var tmp_name = myLabel.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_');
			  }
			  
			  if( $('.form_builder_stage .field_container .form-group :input[name="'+tmp_name+'"]').length > 0 ){
			  	
			  	var inc_cnt = 1;
			  	while( $('.form_builder_stage .field_container .form-group :input[name="'+tmp_name+'_'+inc_cnt+'"]').length > 0 ){
			  		inc_cnt ++;
			  	}
			  	tmp_name = tmp_name +'_' + inc_cnt;
			  }
	    	
	    	if(j==false || typeof j.select_options == 'undefined' || j.select_options.length<1){
	    		row += '<div class="radio_wrapper" id="field'+dynID+'"';
					if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
			    	row += ' data-required="required"';
			    }
			    
			    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
			    	row += ' data-hide_label="true"';
			    }
			    if(typeof j.orientation!='undefined' && j.orientation!==false){
			    	row += ' data-orientation="'+j.orientation+'"';
			    } else {
			    	row += ' data-orientation="horizontal"';
			    }
			    row += '>';
			    
		   		if(wrap_div){
						row += '<div class="radio">';
						
					}
					row += '<label';
					if(!wrap_div){
						row += ' class="radio-inline"';
						
					}
					row += '><input type="radio" name="'+tmp_name+'" value="">';
					row += myLabel+' 1';
					row += '</label>';
					
					
		   		if(wrap_div){
						row += '</div>';
					}
		   		if(wrap_div){
						row += '<div class="radio">';
					}
					row += '<label';
					if(!wrap_div){
						row += ' class="radio-inline"';
					}
					row += '><input type="radio" name="'+tmp_name+'" value="">';
					row += myLabel+' 2';
					row += '</label>';
					
		   		if(wrap_div){
						row += '</div>';
					}
					row += '</div>';
				} else {
					//select_options":[{"label":"Radio-Schaltfläche 1","val":""},{"
					row += '<div class="radio_wrapper" id="field'+dynID+'"';
					if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
			    	row += ' data-required="required"';
			    }
			    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
			    	row += ' data-hide_label="true"';
			    }
			    if(typeof j.orientation!='undefined' && j.orientation!==false){
			    	row += ' data-orientation="'+j.orientation+'"';
			    } else {
			    	row += ' data-orientation="horizontal"';
			    }
			    <?php do_action('psfb_edit_js_attributes_create'); ?>
			    row += '>';
			    
					$.each(j.select_options,function(key,value){
						
			   		if(wrap_div){
							row += '<div class="radio">';
						}
						row += '<label';
						if(!wrap_div){
							row += ' class="radio-inline"';
						}
						row += '><input type="radio" value="'+value.val+'"';
							
		    		if(typeof value.empty != 'undefined' && value.empty !=''){
							row += ' data-empty="'+value.empty+'"  ';
						}
		    		if(typeof value.checked != 'undefined' && value.checked !=''){
		    			row += ' checked="checked"';
		    		}
		    		if(typeof value.disabled !='undefined' && value.disabled=='disabled'){
				    	row += ' disabled="disabled"';
				    }
						//name="optionsfield'+dynID+'"
				    row += ' name="'+tmp_name+'"';
				    <?php do_action('psfb_edit_js_attributes_create'); ?>
						row += '>';
						row += value.label;
						row += '</label>';
						
			   		if(wrap_div){
							row += '</div>';
						}
					});
					row += '</div>';
				}
	    } else if(mytype == 'checkbox'){
	    	if(typeof j.orientation!='undefined' && j.orientation!==false){
	    		var orientation = j.orientation;
	    	} else {
	    		var orientation = 'horizontal';
	    	}
	    	if(orientation=='horizontal'){
	    		var inner_class = 'checkbox-inline';
	    		var wrap_div = false;
	    	} else {
	    		var inner_class = '';
	    		var wrap_div = true;
	    	}
	    	if(typeof j.name!='undefined' && j.name!='' && j.name!='undefined'){
			  	var tmp_name_chk = j.name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_');
			  } else {
			  	var tmp_name_chk = myLabel.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_');
			  }
			  
			  if( $('.form_builder_stage .field_container .form-group :input[name="'+tmp_name_chk+'"]').length > 0 ){
			  	var inc_cnt_chk = 1;
			  	while( $('.form_builder_stage .field_container .form-group :input[name="'+tmp_name_chk+'_'+inc_cnt_chk+'"]').length > 0 ){
			  		inc_cnt_chk ++;
			  	}
			  	tmp_name_chk = tmp_name_chk +'_' + inc_cnt_chk;
			  }
	    	
	    	if(j==false || typeof j.select_options == 'undefined' || j.select_options.length<1){
		    	row += '<div class="checkbox_wrapper" id="field'+dynID+'"';
					if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
			    	row += ' data-required="required"';
			    }
			   /* if(typeof j.disabled!='undefined' && j.disabled=='disabled'){
			    	row += ' disabled="disabled"';
			    }*/
			    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
			    	row += ' data-hide_label="true"';
			    }
			    if(typeof j.orientation!='undefined' && j.orientation!==false){
			    	row += ' data-orientation="'+j.orientation+'"';
			    } else {
			    	row += ' data-orientation="horizontal"';
			    }
			    row += '>';
			    
		   		if(wrap_div){
						row += '<div class="checkbox">';
					}
					row += '<label';
					if(!wrap_div){
						row += ' class="checkbox-inline"';
					}
					row += '><input type="checkbox" name="'+tmp_name_chk+'" value="">';
					row += myLabel+' 1';
					row += '</label>';
					if(wrap_div){
						row += '</div>';
					}
		   		if(wrap_div){
						row += '<div class="checkbox">';
					}
					row += '<label';
					if(!wrap_div){
						row += ' class="checkbox-inline"';
					}
					row += '><input type="checkbox" name="'+tmp_name_chk+'" value="">';
					row += myLabel+' 2';
					row += '</label>';
					if(wrap_div){
						row += '</div>';
					}
					row += '</div>';
				} else {
					//select_options":[{"label":"Radio-Schaltfläche 1","val":""},{"
					row += '<div class="checkbox_wrapper" id="field'+dynID+'"';
					if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
			    	row += ' data-required="required"';
			    }
			    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
			    	row += ' data-hide_label="true"';
			    }
			    if(typeof j.orientation!='undefined' && j.orientation!==false){
			    	row += ' data-orientation="'+j.orientation+'"';
			    } else {
			    	row += ' data-orientation="horizontal"';
			    }
			    <?php do_action('psfb_edit_js_attributes_create'); ?>
			    row += '>';
			    
					$.each(j.select_options,function(key,value){
						if(wrap_div){
							row += '<div class="checkbox">';
						}
						row += '<label';
						if(!wrap_div){
							row += ' class="checkbox-inline"';
						}
						row += '><input type="checkbox"';
						
						if(typeof value.checked != 'undefined' && value.checked !=''){
		    			row += 'checked="checked"';
		    		}
						if(typeof value.disabled !='undefined' && value.disabled=='disabled'){
				    	row += ' disabled="disabled"';
				    }
				    row += ' name="'+tmp_name_chk+'"';
				    
						row += ' value="'+value.val+'">';
						row += value.label;
						row += '</label>';
						if(wrap_div){
							row += '</div>';
						}
					});
					row += '</div>';
				}
	    } else if(mytype == 'select' || mytype == 'multiselect'){
	    	if(typeof j.icon!='undefined' && j.icon!='' && j.icon!='undefined'){
		    	//console.log(j.icon);
			  	row += '<div class="input-group">';
			  	row += '<div class="input-group-addon">';
			  	row += '<span class="fa '+j.icon+'"></span>';
			  	row += '</div>';
		  	}
	    	row += '<select id="field'+dynID+'" class="form-control';
		    if(typeof j.class!='undefined' && j.class!=''){
		    	row += j.class;
		    }
		    row += '"';
		    
	    	if(typeof myFieldType.multiple!='undefined' && myFieldType.multiple==true){
		    	row += ' multiple="multiple"';
		    }
		    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
		    	row += ' data-hide_label="true"';
		    }
		    if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
		    	row += ' required="required"';
		    }
		    if(typeof j.disabled!='undefined' &&  j.disabled=='disabled'){
		    	row += ' disabled="disabled"';
		    }
		    if(typeof j.style!='undefined' && j.style!=''){
		    	row += ' style="'+j.style+'"';
		    }
		    
		    if(typeof j.name!='undefined' && j.name!='' && j.name!='undefined'){
		    	row += ' name="'+j.name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
		    } else {
		    	row += ' name="'+myLabel.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
		    }
		    <?php do_action('psfb_edit_js_attributes_create'); ?>
	    	row += '>';
	    	if(j==false || typeof j.select_options == 'undefined' || j.select_options.length<1){
		    	row += '<option value="">'+myLabel+'</option>';
		    } else {
		    	$.each(j.select_options,function(key,value){
		    		row += '<option value="'+value.val+'"';
		    		
		    		if(typeof value.empty != 'undefined' && value.empty !=''){
							row += ' data-empty="'+value.empty+'"  ';
						}
		    		if(typeof value.selected != 'undefined' && value.selected !=''){
		    			row += 'selected="selected"';
		    		}
		    		row += '>'+value.label+'</option>';
		    	});
		    }
		    row += '</select>';
		    if(typeof j.icon!='undefined' && j.icon!='' && j.icon!='undefined'){
			  	row += '</div>';
		  	}
	    }
	  } else if(typeof myFieldType != 'undefined'){
	  	
	  	//input group
	  	
	    if(typeof j.icon!='undefined' && j.icon!='' && j.icon!='undefined'){
	    	
		  	row += '<div class="input-group">';
		  	row += '<div class="input-group-addon">';
		  	row += '<span class="fa '+j.icon+'"></span>';
		  	row += '</div>';
	  	}
	  	
	    row += '<input id="field'+dynID+'"';
	    
	    if(typeof myFieldType.type!='undefined'){
	    	row += ' type="'+ myFieldType.type +'"';
	    }
	    if(typeof myFieldType.multiple!='undefined' && myFieldType.multiple==true){
	    	row += ' multiple="multiple"';
	    }
	    if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
	    	row += ' required="required"';
	    }
	    if(typeof j.readonly!='undefined' &&  j.readonly=='readonly'){
	    	row += ' readonly="readonly"';
	    }
	    /*if(typeof j.disabled != 'undefined' &&  j.disabled=='disabled'){
	    	row += ' disabled="disabled"';
	    }*/
	    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
	    	row += ' data-hide_label="true"';
	    }
	    row += ' class="form-control';
	    if(typeof j.class!='undefined' && j.class!='' && j.class!='undefined'){
	    	row += j.class;
	    }
	    row += '"';
	    
	    if(typeof j.style!='undefined' && j.style!='' && j.style!='undefined'){
	    	row += ' style="'+j.style+'"';
	    }
     	if(typeof j.maxlength!='undefined' && j.maxlength!=''){
	    	row += ' maxlength="'+j.maxlength+'"';
	    }
	    if(typeof j.number_max != 'undefined' && j.number_max != ''){
	    	row += ' max="'+j.number_max+'"';
	    }
	    if(typeof j.number_min != 'undefined' && j.number_min != '' ){
	    	row += ' min="'+j.number_min+'"';
	    }
	    if(typeof j.default_value!='undefined' && j.default_value!='' && j.default_value!='undefined'){
	    	row += ' value="'+j.default_value+'"';
	    }
	    if(typeof j.placeholder!='undefined' && j.placeholder!='' && j.placeholder!='undefined'){
	    	row += ' placeholder="'+j.placeholder+'"';
	    }
	    /*if(typeof j.maxlength!='undefined' && j.maxlength!='' && j.maxlength!='undefined'){
	    	row += ' maxlength="'+j.maxlength+'"';
	    }*/
	    
	    if(typeof j.name!='undefined' && j.name!='' && j.name!='undefined'){
	    	row += ' name="'+j.name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
	    } else {
	    	row += ' name="'+myLabel.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
	    }
	     <?php do_action('psfb_edit_js_attributes_create'); ?>
	    row += '>';
	    
	    if(typeof j.icon!='undefined' && j.icon!='' && j.icon!='undefined'){
	   		row += '</div>';//input-group
	   	}
	  }
	}
  
  row += '<p class="help-block">';
  if(typeof j.help_text!='undefined' && j.help_text!='' && j.help_text!='undefined'){
  	row += j.help_text;
  }
  row += '</p>';
  
  row += '</div>';
  
  row += '</div>';
  
  if(row_mode=='plain'){
  	row += '</div>';//end row
  	$( '.form_builder_stage' ).append( row );
  	
  	$( '.form_builder_stage' ).find('.field_container').last().attr('data-condition',j.condition);
  } else if(row_mode=='plain_col'){
  	$( '.form_builder_stage .row:last-child' ).append( row );
  	
  	$( '.form_builder_stage' ).find('.field_container').last().attr('data-condition',j.condition);
  	var colcnt = $( '.form_builder_stage .row:last-child .field_container' ).length;
  	$( '.form_builder_stage .row:last-child .field_container' ).attr('class','field_container').addClass('col-md-'+ Math.floor( 12 / colcnt) +'');
  	
  	$( '.form_builder_stage' ).find('.field_container').last().attr('data-condition',j.condition);
  } else {
  	$( row ).insertAfter( $(target) );
  	
  	$( '.form_builder_stage' ).find('.field_container').last().attr('data-condition',j.condition);
  }
  
  ps_manage_form_vars();
  
  
	
  
  $('.form_builder_stage').sortable({
  	appendTo: document.body,
  	axis: 'y',
  	containment: 'parent',
//  	cursorAt: { left: 5 },
  	forceHelperSize: true,
  	handle: '.options .move-v',
  	tolerance: 'pointer',
  	cursor:'ns-resize',
  	items:'>div'
  });
  
  
  
  $('button.psfb_trigger_all_events').unbind('click').click(function(){
  	
  	$('.form_builder_stage .row .field_container').draggable({
  	appendTo: 'body',
    helper: 'original',
    addClasses: false,
    cancel: false,
    refreshPositions:true,
    revert:true,
    zIndex:55555,
  	cursorAt:{left: 25,top:50},
    handle: '.options .move-hv',
  	start:function(event,ui){
				var height = 50;
		  
		 	$('.form_builder_stage').append( '<div class="empty_helper_row row"><div style="height:'+height+'px;" class="droparea field_container col-md-12"></div></div>' );
		 	
			$('.field_container').droppable({
				greedy:false,
				tolerance:'pointer',
				addClasses: false,
				accept:'.field_container',
				hoverClass: 'bg-success',
				over: function(event,ui){
					
  				var droppableElement = $(this);
  				var row = droppableElement.closest('.row');
  				if(droppableElement.hasClass('droparea')){
  					//is empty - allowed to be dropped
  				} else {
						ps_remove_dropareas();
	  				var cont_cnt = row.find('.field_container').length;
	  				var col_cls = Math.floor(12/(parseInt(cont_cnt)+2));
	  				
		    		row.find('.field_container').each(function(){
		    			$(this).attr('class','field_container').addClass('col-md-'+ col_cls +'');
		    		});
		    		
		    		$('<div class="droparea field_container col-md-'+ col_cls +'"></div>').insertBefore(droppableElement);
		    		$('<div class="droparea field_container col-md-'+ col_cls +'"></div>').insertAfter(droppableElement);
	  				$( '<div class="row"><div style="height:50px;" class="droparea field_container col-md-12"></div></div>' ).insertBefore(row);
	  				$( '<div class="row"><div style="height:50px;" class="droparea field_container col-md-12"></div></div>' ).insertAfter(row);
	  				
				  	$( '.form_builder_stage .droparea' ).each(function(){
				  		$(this).height( $(this).parent().height() );
				  	});
				  	
	  				$('.droparea').css({position:'static','z-index':2}).droppable({
	  					greedy:false,
							addClasses: false,
							hoverClass: 'bg-success',
							tolerance:'pointer',//touch,intersect,pointer,fit
							
							drop:function(event,ui){
					    	dragcontroller.dropped = true;
					      ui.draggable.insertAfter($(this));
					      $('.droparea').droppable('destroy');
			  				ps_remove_dropareas();
							}
						});
						
	  			}
				},
				drop: function(event,ui){
					
					if( $(this).hasClass('droparea') ){
						try{$('.field_container').droppable('destroy');}catch(e){}
			    	dragcontroller.dropped = true;
			      
					  ui.draggable.insertAfter($(this));
			      
	  			}
	  			ps_remove_dropareas();
					$('.form_builder_stage .field_container').removeAttr('style');
  				
				}
				
			});
		},
		revert : function(event, ui) {
      
      $(this).data("uiDraggable").originalPosition = {
          top : 0,
          left : 0
      };
      // return boolean
      return !event;
    },
		stop:function(){
			if( $('.empty_helper_row').is(':empty') ){
				$('.empty_helper_row').remove();
			} else {
				$('.empty_helper_row').removeClass('empty_helper_row');
			}
			$('.form_builder_stage .row:empty').remove();
			ps_remove_dropareas();
			$('.form_builder_stage .field_container').removeAttr('style');
		}
  	});
  	
	  $('.form_builder_stage button.delete').unbind('click').click(function(){
	  	if( $(this).closest('.row').find('.field_container').length > 1){
	  		$(this).closest('.field_container').remove();
	  	} else {
	  		$(this).closest('.row').remove();
	  	}
	  	ps_remove_dropareas();
	  });
		
		$('.form_builder_stage button.copy').unbind('click').click(function(){
			
			var me = $(this);
			//ps_field_drop( event, ui, $(this), false, false );
			
			
			var thisform = $(this).closest('form');
			var h = $(this).closest('.row');
			h.clone().appendTo(thisform);
			var cnt = thisform.find('.field_container').length-2;
			$('.form_builder_stage .row:last .field_container').each(function(){
				cnt++;
				
				var me = $(this);
				me.attr('data-id',cnt).closest('.row').attr('data-id',cnt);
				var mytype = me.attr('data-type');//|| me.attr('data-type').indexOf('survey_rating_') != 1
				
				me.find('.form-group .radio_wrapper').attr('id','field'+cnt);
				me.find('.form-group .checkbox_wrapper').attr('id','field'+cnt);
				if(mytype == 'radio' || mytype.indexOf('survey_rating_') != -1 || mytype=='checkbox'){	//me.attr('data-type')=='survey_rating_scale' || me.attr('data-type')=='survey_grid'){
					
					me.find('.form-group .field_label').attr('for','field'+cnt);
					var tmp_name = me.find('.form-group .field_label').text();
					tmp_name = tmp_name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_');//me.find('input[type="radio"]').attr('name');////
					
					if($('.form_builder_stage .field_container .form-group :input[name="'+tmp_name+'"]').length > 0 ){
				  	var inc_cnt = 1;
				  	while( $('.form_builder_stage .field_container .form-group :input[name="'+tmp_name+'_'+inc_cnt+'"]').length > 0 ){
				  		inc_cnt ++;
				  	}
				  	tmp_name = tmp_name +'_' + inc_cnt;
				  	
				  }	
					me.find('.form-group :input').each(function(){
						var in_me = $(this);
						in_me.attr('name',tmp_name);
					});	
				}else if(mytype=='survey_grid'){
					
					me.find('.form-group .table-responsive').attr('id','field'+cnt);
					me.find('.form-group .field_label').attr('for','field'+cnt);
					me.find('.form-group .table-responsive .psfb_custom_survey_grid_row').each(function(){
						
						var tmp_name = $(this).text();
						tmp_name = tmp_name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_');//me.find('input[type="radio"]').attr('name');////
						
						if($('.form_builder_stage .field_container .form-group :input[name="'+tmp_name+'"]').length > 0 ){
					  	var inc_cnt = 1;
					  	while( $('.form_builder_stage .field_container .form-group :input[name="'+tmp_name+'_'+inc_cnt+'"]').length > 0 ){
					  		inc_cnt ++;
					  	}
					  	tmp_name = tmp_name +'_' + inc_cnt;
					  }	
						$(this).closest('tr').find('input[type="radio"]').attr('name',tmp_name);
					});	
						
				}else if(mytype=='signature'){
					
					me.find('.form-group .field_label').attr('for','field'+cnt);
					me.find('.form-group .psfb_signature_canvas').attr('id','psfb_canvas_'+cnt);
					var tmp_name = me.find('.form-group .field_label').text();
					tmp_name = tmp_name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_');//me.find('input[type="radio"]').attr('name');////
					
					if($('.form_builder_stage .field_container .form-group :input[name="'+tmp_name+'"]').length > 0 ){
				  	var inc_cnt = 1;
				  	while( $('.form_builder_stage .field_container .form-group :input[name="'+tmp_name+'_'+inc_cnt+'"]').length > 0 ){
				  		inc_cnt ++;
				  	}
				  	tmp_name = tmp_name +'_' + inc_cnt;
				  }	
					me.find('.form-group input[type="text"]').attr('name',tmp_name);
				}else{
					me.attr('data-id',cnt);
					me.find('.form-group :input').attr('id','field'+cnt);
					me.find('.form-group label').attr('for','field'+cnt);
					var tmp_name = me.find('.form-group .field_label').text();//in_me.attr('name');
					tmp_name = tmp_name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_');
					
					if($('.form_builder_stage .field_container .form-group :input[name="'+tmp_name+'"]').length > 0 ){
				  	var inc_cnt = 1;
				  	while( $('.form_builder_stage .field_container .form-group :input[name="'+tmp_name+'_'+inc_cnt+'"]').length > 0 ){
				  		inc_cnt ++;
				  	}
				  	tmp_name = tmp_name +'_' + inc_cnt;
				  	me.find('.form-group :input').attr('name',tmp_name);
				  }	
				}
				
			});
			$('button.psfb_trigger_all_events').trigger('click');
			
				var offset = $('.form_builder_stage .row:last').offset().top ;//- $(window).scrollTop()
				//console.log($('.form_builder_stage .row:last').offset().top);
				//console.log(offset);
				//console.log($(window).scrollTop());
		    if(offset > window.innerHeight){
		      $('html,body').animate({scrollTop: offset}, 500);
		    }
				
				ps_manage_form_vars();
				psfb_on_stage_change();
		  	ps_remove_dropareas();
		  	
	  	
	  });  
	  
	  ////////////// MODAL EDIT CLICK ///////////////
	  
	  $('.form_builder_stage button.edit').unbind('click').click(function(){
	  	
	  	var mytype = $(this).closest('.field_container').attr('data-type');
	  	
	  	if( $.inArray(mytype,htmlfields)!=-1){
	  		
	  		
	  		var tag_details = fieldtypes[mytype];
				if(typeof tag_details.wrap!='undefined' && tag_details.wrap===true){
	  			var tag = tag_details.type;
	  			if(typeof tag_details.options!='undefined'){
	  				//ACHTUNG AUSWAHL BEREITSTELLEN
	  				tag += tag_details.options[0];
	  			}
	  			$('#field_html_content').val( $(this).closest('.field_container').find('.psfb_html_content').find(tag).first().html() );
	  		}
	  		
		  	$('#field_cssstyle').val( $(this).closest('.field_container').find('.psfb_html_content').find(tag).first().attr('style') );
		  	if( $('#field_cssstyle').val()=='undefined'){
		  		$('#field_cssstyle').val('');
		  	}
		  	/*
		  	$('#field_maxlength').val( $(this).closest('.field_container').find('.psfb_html_content').find(tag).first().attr('maxlength') );
		  	if( $('#field_maxlength').val()=='undefined'){
		  		$('#field_maxlength').val('');
		  	}*/
	  		var cssclass = $(this).closest('.field_container').find('.psfb_html_content').find(tag).first().attr('class');
		  	if(typeof cssclass!='undefined'){
		  		if(cssclass.indexOf('psfb_html_content')!=-1){
		  			cssclass=cssclass.replace('psfb_html_content','');
		  		}
		  	} else {
		  		cssclass = '';
		  	}
		  	$('#field_cssclass').val( cssclass );
		  	if( $('#field_cssclass').val()=='undefined'){
		  		$('#field_cssclass').val('');
		  	}
	  		
	  		
	  		
	  	} else {
		  	var my_field_container = $(this).closest('.field_container');
		  	if(mytype.indexOf('submit')!=-1){
		  		$('#field_label').val( my_field_container.find('.form-group button').html() );
		  		/*
		  		$('#field_label').val( my_field_container.find('.form-group :input').attr('value') );
		  		*/
		  	} else {
			  	$('#field_label').val( my_field_container.find('.form-group .field_label').html().replace('*','') );
			  }
			  if( $('#field_label').val()=='undefined'){
		  		$('#field_label').val('');
		  	}
		  	$('#field_label').unbind('change').change(function(){
		  		
		  		$('.field_name_error').remove();
		  		if( $('#field_label').val() == ''){
		  			//Error field name must not be blank
		  			$('#field_label').addClass('has-error').closest('.form-group').addClass('has-error').append('<p class="field_name_error help-block"><?php echo __('The field label may not be empty! You can hide the label by activating the checkbox down right.</p>','psfbldr'); ?>');
		  			$('button.savefield').addClass('btn-danger').prop('disabled',true);
		  		} else {
		  			$('#field_label').removeClass('has-error').closest('.form-group').removeClass('has-error');
		  			$('button.savefield').removeClass('btn-danger').prop('disabled',false);
		  		}
		  		
		  		$('#field_name').val( $(this).val().replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_') );
		  		
		  		var current_name = $('#field_name').val();
		  		var current_original_name = $('#field_name_orig').val();
		  		if( current_name == current_original_name ){
		  			var check_cnt = 1;
		  		} else {
		  			var check_cnt = 0;
		  		}
		  		var test_name = current_name+'_1';
		  		
		  		if( $('.form_builder_stage .field_container .form-group :input[name="'+current_name+'"]').closest('.form-group').length > check_cnt ){
		  			
		  			var inc_cnt = 1;
		  			var test_name = current_name+'_'+inc_cnt;
		  			while( $('.form_builder_stage .field_container .form-group :input[name="'+current_name+'_'+inc_cnt+'"]').closest('.form-group').length > 0 && test_name!=current_original_name ){
		  				inc_cnt ++;
		  				test_name = current_name+'_'+inc_cnt;
		  			}
		  			$('#field_name').val(current_name+'_'+inc_cnt);
		  		}
		  	});
		  	$('#field_helptext').val( my_field_container.find('.form-group .help-block').html() );
		  	if( $('#field_helptext').val()=='undefined'){
		  		$('#field_helptext').val('');
		  	}
		  	$('#field_placeholder').val( my_field_container.find('.form-group :input').attr('placeholder') );
		  	if( $('#field_placeholder').val()=='undefined'){
		  		$('#field_placeholder').val('');
		  	}
		  	$('#field_default_value').val( my_field_container.find('.form-group :input').attr('value') );
		  	if( $('#field_default_value').val()=='undefined'){
		  		$('#field_default_value').val('');
		  	}
		  	
		  	$('#field_cssstyle').val( my_field_container.find('.form-group :input').attr('style') );
		  	if( $('#field_cssstyle').val()=='undefined'){
		  		$('#field_cssstyle').val('');
		  	}
		  	
		  	
		  	if(mytype == 'number'){
		  		$('.field_number_ranges_wrapper').show();
		  		$('.field_maxlength_wrapper').hide();
		  		$('#field_numbermax').val( my_field_container.find('.form-group :input').attr('max') );
		  		$('#field_numbermin').val( my_field_container.find('.form-group :input').attr('min') );
		  		
		  	}else{
		  		$('.field_number_ranges_wrapper').hide();
		  		$('.field_maxlength_wrapper').show();
			  	$('#field_maxlength').val( my_field_container.find('.form-group :input').attr('maxlength') );
			  	if( $('#field_maxlength').val()=='undefined'){
			  		$('#field_maxlength').val('');
			  	}
			  }	
		  	var cssclass = my_field_container.find('.form-group :input').attr('class');
		  	if(typeof cssclass!='undefined'){
		  		if(cssclass.indexOf('form-control')!=-1){
		  			cssclass=cssclass.replace('form-control','');
		  		}
		  		if(cssclass.indexOf('btn btn-primary')!=-1){
		  			cssclass=cssclass.replace('btn btn-primary','');
		  		}
		  	} else {
		  		cssclass = '';
		  	}
		  	$('#field_cssclass').val( $.trim( cssclass ) );
		  	if( $('#field_cssclass').val()=='undefined'){
		  		$('#field_cssclass').val('');
		  	}
		  	$('#field_name').val( my_field_container.find('.form-group :input').attr('name') );
		  	if( $('#field_name').val()=='undefined'){
		  		$('#field_name').val('');
		  	}
		  	$('#field_name_orig').val( $('#field_name').val() );
		  	
		  	
		  	if( my_field_container.find('.checkbox_wrapper').length > 0 ){
		  		var req = my_field_container.find('.checkbox_wrapper').attr('data-required');
		  		var orientation = my_field_container.find('.checkbox_wrapper').attr('data-orientation');
		  	} else if( my_field_container.find('.radio_wrapper').length > 0 ){
		  		
		  		var req = my_field_container.find('.radio_wrapper').attr('data-required');
		  		var orientation = my_field_container.find('.radio_wrapper').attr('data-orientation');
		  	} else {
		  		var req = my_field_container.find('.form-group :input').prop('required');
		  		var orientation = false;
		  	}
		  	
		  	
		  	var readonly =  my_field_container.find('.form-group :input').attr('readonly') ;
		  	
		  	if(typeof readonly != 'undefined' && readonly == 'readonly'){
		  		readonly = true;
		  		$('#field_readonly').prop('checked',true);
		  	} else {
		  		readonly = false;
		  		$('#field_readonly').prop('checked',false);
		  	}
		  	
		  	/*console.log('disabled:'+disabled);*/
		  	
		  	
		  	if(typeof req!='undefined' && (req == 'required' || req==true || req=='true') ){
		  		req = true;
		  		$('#field_required').prop('checked',true);
		  	} else {
		  		req = false;
		  		$('#field_required').prop('checked',false);
		  	}
		  	
		  	if(typeof orientation!='undefined' && orientation !== false ){
		  		$('#field_orientation').val( orientation );
		  	}
		  	if( my_field_container.find('.checkbox_wrapper').length > 0 ){
		  		var hide_label = my_field_container.find('.checkbox_wrapper').attr('data-hide_label');
		  		//var disabled =  my_field_container.find('.checkbox_wrapper').attr('disabled');
		  	} else if( my_field_container.find('.radio_wrapper').length > 0 ){
		  		var hide_label = my_field_container.find('.radio_wrapper').attr('data-hide_label');
		  		//var disabled =  my_field_container.find('.radio_wrapper').attr('disabled');
		  	} else {
		  		var hide_label = my_field_container.find('.form-group :input').attr('data-hide_label');
		  		var disabled =  my_field_container.find('.form-group :input').attr('disabled');
		  	}
		  	if(typeof disabled != 'undefined' && disabled == 'disabled'){
		  		disabled = true;
		  		$('#field_disabled').prop('checked',true);
		  	} else {
		  		disabled = false;
		  		$('#field_disabled').prop('checked',false);
		  	}
		  	
		  	if(typeof hide_label!='undefined' && (hide_label == '1' || hide_label==true || hide_label=='true') ){
		  		hide_label = true;
		  		$('#field_hide_label').prop('checked',true);
		  	} else {
		  		hide_label = false;
		  		$('#field_hide_label').prop('checked',false);
		  	}
		  	
		  	$('#field_icon').val('');
		  	$('#field_icon_addon').html('');
		  	if( my_field_container.find('.input-group').length > 0){
		  		$('#field_icon').val( my_field_container.find('.input-group .fa').attr('class').replace('fa ','') );
		  		$('#field_icon_addon').html('<span class="fa '+my_field_container.find('.input-group .fa').attr('class').replace('fa ','')+'"></span>');
		  	}
		  	
		  	
		  	if( $.inArray(mytype,noiconfields)!= -1  ){
		  		$('.field_icon_wrapper').hide();
		  	} else {
		  		$('.field_icon_wrapper').show();
		  	}
		  	if( $.inArray(mytype,noplaceholderfields)!= -1  ){
		  		$('.field_placeholder_wrapper').hide();
		  		$('.field_default_value_wrapper').hide();
		  		$('.field_readonly_wrapper').hide();
		  		if(mytype.indexOf('survey_') != -1 || mytype == 'select' || mytype == 'radio' || mytype == 'checkbox' || mytype == 'multiselect'){
		  			$('.field_disabled_wrapper').show();
		  			$('#field_hide_label').closest('.form-group').removeClass('col-md-6').addClass('col-md-4');
		  			$('#field_required').closest('.form-group').removeClass('col-md-6').addClass('col-md-4');
		  		}else{
		  			$('.field_disabled_wrapper').hide();
		  			$('#field_hide_label').closest('.form-group').removeClass('col-md-4').addClass('col-md-6');
		  			$('#field_required').closest('.form-group').removeClass('col-md-4').addClass('col-md-6');
		  		}
		  		
		  		$('.field_maxlength_wrapper').hide();
		  		//if(mytype != 'range'){
		  		$('.field_number_ranges_wrapper').hide();
		  		//}
		  	} else {
		  		$('.field_placeholder_wrapper').show();
		  		$('.field_default_value_wrapper').show();
		  		$('.field_readonly_wrapper').show();
		  		$('.field_disabled_wrapper').hide();
		  		$('#field_hide_label').closest('.form-group').removeClass('col-md-6').addClass('col-md-4');
		  		$('#field_required').closest('.form-group').removeClass('col-md-6').addClass('col-md-4');
		  		if(mytype == 'number'){
		  			$('.field_number_ranges_wrapper').show();
		  		}else{
		  			$('.field_maxlength_wrapper').show();
		  		}
		  	}
		  	
		  	
		  	if( mytype=='radio' || mytype=='checkbox'){
		  		$('.field_orientation_wrapper').show();
		  	} else {
		  		$('.field_orientation_wrapper').hide();
		  	}
		  	
		  	
		  	<?php do_action( 'psfb_edit_js_add_attributes' ); ?>
		  	
		  	psfb_handle_edit_special_tabs_closing();
		  	if( $.inArray(mytype,customfields)!= -1 ){
		  		
		  		<?php do_action( 'psfb_edit_js_handle_edit_customfields' ); ?>
		  		
		  		
		  	} else if( $.inArray(mytype,customelements)!= -1 ){
		  		//console.log(mytype);
		  		<?php do_action( 'psfb_edit_js_handle_edit_customelements' ); ?>
		  		
		  		
		  	} else if( $.inArray(mytype,selectfields)!= -1 ){
		    	//selectfield
		    	
		    	
		    	
		    	$('.selectoptionstab').show();
		    	$('.selectoptions_content').html('');
		    	
		    	if(mytype == 'select' || mytype == 'multiselect'){
		    		$('.selectoptions_default_and_empty').show();
		    		$('.selectoptions_default_checkbox').hide();
		    		$('.selectoptions_content_desc_default_and_empty').show();
		    		$('.selectoptions_content_desc_empty').show();
		  			my_field_container.find('option').each(function(){
		  				var h = $('.selectoptions_template').html();
		  				$('.selectoptions_content').append(h);
		  				console.log($(this).attr('data-empty'));
		  				if($(this).attr('data-empty') == 'true' &&  $(this).attr('data-empty') != 'false'){
		  					$('.selectoptions_content .field_option_value_empty:last').prop('checked',true);
		  					$('.selectoptions_content .field_option_value:last').attr('disabled','');	
		  				}
		  				
		  				if($(this).attr('selected') == 'selected'){
		  					$('.selectoptions_content .field_option_value_default:last').prop('checked',true);
		  				}
		  				$('.selectoptions_content .field_option_value:last').val( $(this).attr('value') );
		  				$('.selectoptions_content .field_option_label:last').val( $(this).text() );
		  				
		  				
		  				
		  				$('.selectoptions_content .delete_selectoption:last').unbind('click').click(function(){
		  					$(this).closest('.row').remove();
		  				});
		  				
		  				
		  				
		  			});
		  		} else if(mytype == 'radio'){
		  			$('.selectoptions_default_checkbox').hide();
		  			$('.selectoptions_content_desc_empty').show();
		  			$('.selectoptions_default_and_empty').show();
		  			
		  			my_field_container.find('input[type="radio"]').each(function(){
		  				var h = $('.selectoptions_template').html();
		  				$('.selectoptions_content').append(h);
		  				$('.selectoptions_content .field_option_value:last').val( $(this).attr('value') );
		  				$('.selectoptions_content .field_option_label:last').val( $(this).parent().text() );
		  				if($(this).attr('data-empty') == true){
		  					$('.selectoptions_content .field_option_value_empty:last').prop('checked',true);
		  					$('.selectoptions_content .field_option_value:last').attr("disabled",'');	
		  					
		  				}
		  				if($(this).prop('checked') == 'checked' || $(this).prop('checked') == true){
		  					$('.selectoptions_content .field_option_value_default:last').prop('checked',true);
		  					
		  				}
		  				if($(this).attr('disabled') == 'disabled'){
		  					$('#field_disabled').prop('checked',true);
		  				}
		  				
		  				$('.selectoptions_content .delete_selectoption:last').unbind('click').click(function(){
		  					$(this).closest('.row').remove();
		  				});
		  			});
		  		} else if(mytype == 'checkbox'){
		  			$('.selectoptions_content_desc_empty').hide();
		  			//$('.field_option_value_default').hide();
		  			$('.selectoptions_default_checkbox').show();
		  			$('.selectoptions_default_and_empty').hide();
		  			//$('.selectoptions_content_desc_default_and_empty').hide();
		  			my_field_container.find('input[type="checkbox"]').each(function(){
		  				var h = $('.selectoptions_template').html();
		  				$('.selectoptions_content').append(h);
		  				if($(this).attr('checked') == 'checked'){
		  					$('.selectoptions_content').find('.field_option_value_default_checkbox:last').prop('checked',true);
		  				}
		  				if($(this).attr('disabled') == 'disabled'){
		  					$('#field_disabled').prop('checked',true);
		  				}
		  				$('.selectoptions_content .field_option_value:last').val( $(this).attr('value') );
		  				$('.selectoptions_content .field_option_label:last').val( $(this).parent().text() );
		  				$('.selectoptions_content .delete_selectoption:last').unbind('click').click(function(){
		  					$(this).closest('.row').remove();
		  				});
		  			});
		  		}
		  		$('.selectoptions_content').sortable({
		  			appendTo: document.body,
				  	axis: 'y',
				  	containment: 'parent',
				  	forceHelperSize: true,
				  	tolerance: 'pointer',
				  	cursor:'ns-resize',
				  	items:'>div'
		  		});
		  		$('.add_selectoption').unbind('click').click(function(){
		  			var h = $('.selectoptions_template').html();
		  			//var radio_name = 'name="radio_'+$('.selectoptions_content .row').length+'"';
		  			//h = h.replace(/name="radio"/g,radio_name);
		  			
		  			$('.selectoptions_content').append(h);
		  			//$('.selectoptions_content').find('.field_option_value_empty').trigger('change');
						$('.selectoptions_content .delete_selectoption:last').unbind('click').click(function(){
							$(this).closest('.row').remove();
						});
						
						
						$('.field_option_value_empty').unbind('change').change(function(){
							if($(this).is(':checked')){
								
								$(this).closest('.form-group').find('.field_option_value').attr("disabled",'');	
									
								
							}else{
								$(this).closest('.form-group').find('.field_option_value').removeAttr("disabled");
								
							}
						}).trigger("change");
						
					});
		  		
		  		
		  		$('.toggle_selectoption').unbind('click').click(function(){
		  			
		  			if( $('.selectoptions_content').is(':hidden') ){
		  				//build rows from textarea
		  				
		  				$('.selectoptions_quick_content').trigger('blur');
		  				$('.selectoptions_quick_content').hide();
		  				$('.selectoptions_quick_content_desc').hide();
		  				$('.add_selectoption').show();
		  				$('.selectoptions_content').show();
		  				$('.selectoptions_content_desc').show();
		  				
		  			} else {
		  				//fill texarea from rows
		  				var h = '';
		  				$('.selectoptions_content .row').each(function(i){
		  					if(i>0)h += '\n';
		  					h += $(this).find('.field_option_label').val();
		  					
		  					if( $(this).find('.field_option_value').val()!='' ){
		  						h += ',';
		  						h += $(this).find('.field_option_value').val();
		  					}
		  					var default_checked = false;
		  					if($(this).find('.field_option_value_default').is(':checked') && $(this).find('.field_option_value_default').is(':visible')){
		  						if($(this).find('.field_option_value').val()==''){
		  							h += ',';
		  						}
		  						h += ',';
		  						h += 'd';
		  						default_checked = true;
		  					}else if($(this).find('.field_option_value_default_checkbox').is(':checked') && $(this).find('.selectoptions_default_checkbox').is(':visible')){
		  						if($(this).find('.field_option_value').val()==''){
		  							h += ',';
		  						}
		  						h += ',';
		  						h += 'd';
		  						default_checked = true;
		  					}
		  					if($(this).find('.field_option_value_empty').is(':checked')){
		  						
		  						if(default_checked == false ){
		  							if($(this).find('.field_option_value').val()==''){
		  								h += ',';
		  							}
		  							h += ',';
		  						}
		  						h += 'e';
		  					}
		  				});
		  				$('.selectoptions_quick_content textarea').val( h );
		  				$('.selectoptions_content').hide();
		  				$('.selectoptions_content_desc').hide();
		  				$('.add_selectoption').hide();
		  				$('.selectoptions_quick_content').show();
		  				$('.selectoptions_quick_content_desc').show();
		  			}
		  		}).trigger('click');
		  		$('.selectoptions_quick_content textarea').unbind('blur').blur(function(){
		  			var lines = $(this).val().split('\n');
		  			
		  			$('.selectoptions_content').html('');
		  			$.each(lines,function(i,line){
		  				$('.add_selectoption').trigger('click');
		  				var default_empty_value = null;
		  				var transformed_lines = $.csv.toArray( line );
		   				if(typeof transformed_lines[0] != 'undefined' && transformed_lines[0] != ''){
		  					$('.selectoptions_content .field_option_label:last').val( transformed_lines[0] );
		  				}	
		  				if(typeof transformed_lines[1] != 'undefined' && transformed_lines[1] != ''){
		  					$('.selectoptions_content .field_option_value:last').val( transformed_lines[1] );
		  				}	
		  				if(typeof transformed_lines[2] != 'undefined' && transformed_lines[2] != '' && transformed_lines[2] == 'de'){
		  						$('.selectoptions_content .field_option_value_default:last').prop('checked',true);
		  						$('.selectoptions_content .field_option_value_empty:last').prop('checked',true);
		  						$('.selectoptions_content .field_option_value:last').attr("disabled",'');
		  				}else if(typeof transformed_lines[2] != 'undefined' && transformed_lines[2] != '' && transformed_lines[2] == 'd'){
		  						$('.selectoptions_content .field_option_value_default:last').prop('checked',true);
		  						$('.selectoptions_content .field_option_value_default_checkbox:last').prop('checked',true);
		  				}else if(typeof transformed_lines[2] != 'undefined' && transformed_lines[2] != '' && transformed_lines[2] == 'e'){
		  						$('.selectoptions_content .field_option_value_empty:last').prop('checked',true);
		  						$('.selectoptions_content .field_option_value:last').attr("disabled",'');
		  				}
		  				
		  				
		  			});
		  			
		  		});
		  		
		  	} else {
		  		psfb_handle_edit_special_tabs_closing();
		  	}
		  	
		  }//end if not html
	  	
	  	$('.selectoptions_content').find('.field_option_value_empty').unbind('change').change(function(){
	  		
				if($(this).is(':checked')){
					
					$(this).closest('.form-group').find('.field_option_value').attr("disabled",'');		
					
				}else{
					
					$(this).closest('.form-group').find('.field_option_value').removeAttr("disabled");
				}
		
			}).trigger("change");
	  	
	  	
	  	$('.add_conditionset').unbind('click').click(function(){
				var h = $('.condition_set_template').html();
				$('.condition_content').append(h);
				$('.condition_content .delete_conditionset:last').unbind('click').click(function(){
					$(this).closest('.condition_set').remove();
				});
				
				/*$('.condition_field_condition').change(function(){
					if(){
						
					}
					
				});*/
				
		  	$('.add_conditionoption').unbind('click').click(function(){
					var h = $('.condition_template').html();
					$(this).closest('.condition_set').find('.condition_set_content').append(h);
					$('.condition_set_content .delete_conditionoption').unbind('click').click(function(){
						$(this).closest('.row').remove();
					});
				});
				$('.add_conditionoption:last').trigger('click');
			});
			var h = '';
			/*$('.form_builder_stage .form-group').each(function(){
				var cname = $(this).find('label:first').html();
				var cvalue = $(this).find(':input').attr('name');
				if(typeof cname!='undefined' && cname!=''){
					
					
				} else {
					if(typeof cvalue!='undefined' && cvalue!=''){
						cname = cvalue;
					}else{
						
					}
				}
				
				if(typeof cvalue!='undefined' && cvalue!='' && cvalue!='undefined'){
					h += '<option value="'+cvalue+'">'+cname+'</option>';
				}
			});*/
			
			var attrnames_c = [];
			$('.form_builder_stage .form-group').each(function(){
				$(this).find(':input').each(function(){
					var f_type = $(this).closest('.field_container').attr('data-type');
					if(f_type!='signature'){
						var cname;
						var cvalue = $(this).attr('name');
						
						if(f_type == 'survey_grid'){
							cname = $(this).closest('tr').find('th').text();
						}else{
							cname = $(this).closest('.form-group').find('label:first').text();
						}
						if(typeof cname!='undefined' && cname!=''){
							
						} else {
							if(typeof cvalue!='undefined' && cvalue!=''){
								cname = cvalue;
							}
						}
						/*if( typeof cname != 'undefined' && cname != ''){
							h += '<option value="'+cvalue+'">'+cname+'</option>';
						}*/
						if( typeof cname != 'undefined' && cname != ''){
							if( $.inArray(cvalue,attrnames_c)!=-1 ){
								
							} else {
								h += '<option value="'+cvalue+'">'+cname+'</option>';
								attrnames_c.push(cvalue);
							}
						}
					}
				});
			});
			$('.condition_template .condition_field_select').html(h);
	  	
	  	$('.condition_content').html('');
	  	
			var cond = $(this).closest('.field_container').attr('data-condition');
			
			if(typeof cond!='undefined'){
				if( $.type(cond)=='object'){
					var c = cond;
				} else {
					var c = JSON.parse(cond);
				}
				
				if(typeof c.groups!='undefined' && c.groups.length > 0){
					$.each(c.groups,function(k,v){
						$('.add_conditionset').trigger('click');
						$('.condition_set_type:last').val( v.groupOp );
						$('.condition_set_action:last').val( v.groupAction );
						
						$('.condition_set_content:last').html('');
						if(typeof v.rules != 'undefined' && v.rules.length > 0){
							$.each(v.rules, function(kk,vv){
								$('.add_conditionoption:last').trigger('click');
								$('.condition_field_select:last').val( vv.field );
								$('.condition_field_condition:last').val( vv.op );
								$('.condition_field_value:last').val( vv.data );
							});
						}
					});
				}
			}
	  	
	  	$('#field_icon').iconpicker({inputSearch: false,placement:'bottomRight',templates:{search:'<input type="search" class="form-control iconpicker-search" placeholder="<?php echo __('Type to filter','psfbldr'); ?>" />'} /*options*/ });
	  	
	  	$('#fieldeditor').modal('show').attr('data-type',$(this).closest('.field_container').attr('data-type') ).attr('data-id',$(this).closest('.field_container').attr('data-id') );
	  	
			if( $.inArray(mytype,htmlfields)!=-1){
				$('#fieldeditor .basicstab').hide();
				
				psfb_handle_edit_special_tabs_closing();
				var tag_details = fieldtypes[mytype];
	 			if(typeof tag_details.wrap!='undefined' && tag_details.wrap===true){
					$('#fieldeditor .basicshtmltab').show();
				$('#fieldeditor a[href="#tab-basicshtml"]').tab('show');
				} else {
					$('#fieldeditor .basicshtmltab').hide();
					$('#fieldeditor a[href="#tab-expert"]').tab('show');
				}
			} else {
				$('#fieldeditor .basicshtmltab').hide();
				$('#fieldeditor .basicstab').show();
				$('#fieldeditor a[href="#tab-basics"]').tab('show');
			}
			//$('#fieldeditor li a:visible').first().tab('show');
	  	//$('#fieldeditor a:first').tab('show');
	  	
	  	$('#fieldeditor .cancelsavefield,#fieldeditor .modal-header button.close').unbind('click').click(function(){
	  		$('.field_name_error').remove();
		  	$('#field_label').removeClass('has-error').closest('.form-group').removeClass('has-error');
		  	$('button.savefield').removeClass('btn-danger').prop('disabled',false);
		  	$('fieldeditor').modal('close');
	  	});
	  	/************ MODAL SAVE BUTTON *********/
	  	$('#fieldeditor .savefield').unbind('click').click(function(){
	  		var myID = $('#fieldeditor').attr('data-id');
	  		var mytype = $('#fieldeditor').attr('data-type');
	  		
	  		$('#field_label').trigger('change');
	  		
	  		
	  		$('.field_name_error').remove();
	  		if( $('#field_label').is(':visible') ){
		  		if( $('#field_label').val() == ''){
		  			//Error field name must not be blank
		  			$('#field_label').addClass('has-error').closest('.form-group').addClass('has-error').append('<p class="field_name_error help-block"><?php echo __('The field label may not be empty! You can hide the label by activating the checkbox down right.</p>','psfbldr'); ?>');
		  			return false;
		  		} else {
		  			$('#field_label').removeClass('has-error').closest('.form-group').removeClass('has-error');
		  		}
		  	} else {
	  			$('#field_label').removeClass('has-error').closest('.form-group').removeClass('has-error');
	  		}
	  		
	  		if( $.inArray(mytype,htmlfields)!=-1){
	  			var tag_details = fieldtypes[mytype];
	  			if(typeof tag_details.wrap!='undefined' && tag_details.wrap===true){
		  			var tag = tag_details.type;
		  			if(typeof tag_details.options!='undefined'){
		  				//ACHTUNG AUSWAHL BEREITSTELLEN
		  				tag += tag_details.options[0];
		  			}
		  			$('.field_container[data-id="'+myID+'"] .psfb_html_content').find(tag).html( $('#field_html_content').val() );
		  		}
		  		
		  		$('.field_container[data-id="'+myID+'"]').find('.psfb_html_content').find(tag).first().attr('style', $('#field_cssstyle').val() );
		  		$('.field_container[data-id="'+myID+'"]').find('.psfb_html_content').find(tag).first().attr('class', $('#field_cssclass').val() );
		  		
	  		} else {
		  		
		  		$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('placeholder',$('#field_placeholder').val());
		  		$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('value',$('#field_default_value').val());
		  		
		  		//$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('readonly',$('#field_default_value').val());
		  		if(mytype != 'number' && typeof $('#field_maxlength').val() != 'undefined' && $('#field_maxlength').val() != ''){
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('maxlength', $('#field_maxlength').val() );
		  		}else if(mytype == 'number'){
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').removeAttr('maxlength');
		  			if(typeof $('#field_numbermax').val() != 'undefined' && $('#field_numbermax').val() != ''){
		  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('max', $('#field_numbermax').val() );
		  			}else{
		  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').removeAttr('max');
		  			}
		  			if(typeof $('#field_numbermin').val() != 'undefined' && $('#field_numbermin').val() != ''){
		  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('min', $('#field_numbermin').val() );	
		  			}else{
		  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').removeAttr('min');	
		  			}
		  		}
		  		$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('style', $.trim( $('#field_cssstyle').val() ) );
		  		
		  		if(mytype.indexOf('submit')!=-1){
		  			
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').html($('#field_label').val());
		  			/*
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('value',$('#field_label').val());
		  			*/
		  			var tcls = $.trim( $('#field_cssclass').val() );
		  			if( typeof tcls=='undefined' || tcls =='' ){
		  				tcls = 'btn btn-primary ';
		  			}
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('class', tcls );
		  			
		  		} else {
		  			
		  			var name = $('#field_name').val();
		  			var new_name = name;
		  			var wcnt = 1;
		  			while( $('.field_container .form-group [name="'+new_name+'"]').length > 1){
		  				new_name = name+'_'+wcnt;
		  				wcnt ++;
		  			}
		  			if(new_name!=name){
		  				name = new_name;
		  			}
		  			
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('name',name);
		  			
		  			if( $('#field_name_orig').val()!=name ){
		  				//achtung variablen sind nicht mehr identisch - evtl. alert oder automatisch tauschen (alter wert gegen neuen wert?
		  				
		  				$('#admin_mail_content').val( $('#admin_mail_content').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  				$('#user_mail_content').val( $('#user_mail_content').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  				
		  				$('#admin_mail_subject').val( $('#admin_mail_subject').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  				$('#user_mail_subject').val( $('#user_mail_subject').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  				
		  				$('#admin_mail_recipients').val( $('#admin_mail_recipients').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  				$('#user_mail_recipients').val( $('#user_mail_recipients').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  				
		  				if(typeof $('#admin_mail_bcc').val() != 'undefined' && $('#admin_mail_bcc').val() != ''){
		  					$('#admin_mail_bcc').val( $('#admin_mail_bcc').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  				}
		  				if(typeof $('#user_mail_bcc').val() != 'undefined'  && $('#user_mail_bcc').val() != ''){
		  					$('#user_mail_bcc').val( $('#user_mail_bcc').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  				}
		  				
		  				if(typeof $('#admin_mail_from_name').val() != 'undefined' && $('#admin_mail_from_name').val() != ''){
		  					$('#admin_mail_from_name').val( $('#admin_mail_from_name').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  				}
		  				if(typeof $('#user_mail_from_name').val() != 'undefined'  && $('#user_mail_from_name').val() != ''){
		  					$('#user_mail_from_name').val( $('#user_mail_from_name').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  				}
		  				
		  				if(typeof $('#admin_mail_from_email').val() != 'undefined' && $('#admin_mail_from_email').val() != ''){
		  					$('#admin_mail_from_email').val( $('#admin_mail_from_email').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  				}
		  				if(typeof $('#user_mail_from_email').val() != 'undefined'  && $('#user_mail_from_email').val() != ''){
		  					$('#user_mail_from_email').val( $('#user_mail_from_email').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  				}
		  				
		  				$('#admin_mail_reply_to').val( $('#admin_mail_reply_to').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  				$('#user_mail_reply_to').val( $('#user_mail_reply_to').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
		  			}
		  			
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('class','form-control '+ $('#field_cssclass').val() );
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').html( $('#field_label').val() );
			  		
			  		if($('#field_required').is(':checked')==1){
			  			if( $('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').length > 0){
			  				$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').attr('data-required','required');
			  			} else if( $('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').length > 0){
			  				$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').attr('data-required','required');
			  			} else {
			  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('required','required');
			  			}
			  			$('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').append( '*' );
			  		} else {
			  			$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').attr('data-required',false);
			  			$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').attr('data-required',false);
			  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').removeAttr('required');
			  			$('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').html( $('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').html().replace('*','') );
			  		}
			  		if($('#field_readonly').is(':checked')==1){
			  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('readonly','readonly');
			  		} else {
			  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').removeAttr('readonly');
			  		}
			  		
			  		if($('#field_disabled').is(':checked')==1){
			  			$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper :input').attr('disabled','disabled');
			  			
			  		} else {
			  			$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper :input').removeAttr('disabled');
			  		}
	  				
			  		if($('#field_hide_label').is(':checked')==1){
			  			if( $('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').length > 0){
			  				
			  				$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').attr('data-hide_label',true);
			  				
			  			} else if( $('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').length > 0){
			  				
			  				$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').attr('data-hide_label',true);
			  				
			  			} else {
			  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('data-hide_label',true);
			  				
			  			}
			  			$('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').hide();
			  			
			  			
			  		} else {
			  			$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').attr('data-hide_label',false);
			  			$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').attr('data-hide_label',false);
			  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('data-hide_label',false);
			  			$('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').show();
			  		}
			  		
	  				
		  		}
		  		$('.field_container[data-id="'+myID+'"]').find('.form-group .help-block').html( $('#field_helptext').val() );
		  	} //ende if not html	
	  		
	  		if( $('.condition_wrapper .condition_set').length > 0){
	  			var c = {};
	  			c.groups = [];
	  			var i = 0;
	  			$('.condition_wrapper .condition_set').each(function(){
	  				c.groups[i] = {};
	  				c.groups[i].groupOp = $(this).find('.condition_set_type').val();
	  				c.groups[i].groupAction = $(this).find('.condition_set_action').val();
	  				if( $('.condition_wrapper .condition_set_content .form-group').length > 0){
	  					c.groups[i].rules = [];
	  					var ii = 0;
	  					$(this).find('.condition_set_content .form-group').each(function(){
	  						c.groups[i].rules[ii] = {};
	  						c.groups[i].rules[ii].field = $(this).find('.condition_field_select').val();
	  						c.groups[i].rules[ii].op = $(this).find('.condition_field_condition').val();
	  						c.groups[i].rules[ii].data = $(this).find('.condition_field_value').val();
	  						ii ++;
	  					});
	  				}
	  				i ++;
	  			});
	  			$('.field_container[data-id="'+myID+'"]').attr('data-condition',JSON.stringify(c) );
	  			
	  		} else {
	  			$('.field_container[data-id="'+myID+'"]').attr('data-condition',null).removeData('condition');
	  			
	  		}
	  		
	  		<?php do_action( 'psfb_edit_js_save_attributes' ); ?>
	  		
	  		
	  		if( $.inArray(mytype,customfields)!= -1 ){
		  		
		  		<?php do_action( 'psfb_edit_js_handle_save_customfields' ); ?>
		  		
		  		
		  	} else if( $.inArray(mytype,customelements)!= -1 ){
		  		
		  		
		  		<?php do_action( 'psfb_edit_js_handle_save_customelements' ); ?>
		  		
		  		
		  	} else if( $.inArray(mytype,selectfields)!= -1 ){
		    	//selectfield
		    	//$('#fieldeditor .selectoptions').show();
		    	
		    	if(mytype == 'select' || mytype == 'multiselect'){
	  				$('.field_container[data-id="'+myID+'"]').find('.form-group select').html('');
	  				$('.selectoptions_content .row').each(function(){
	  					var label = $(this).find('.field_option_label').val();
	  					var empty = false;
	  					if($(this).find('.field_option_value_empty').is(':checked')){
	  						empty = true;
	  					}
	  					var val = $(this).find('.field_option_value').val();	
	  					
	  					
	  					$('select#field'+myID)
									.append( $('<option></option>') 
				        	.attr('value', val )
				        	.attr('data-empty', empty )
				      	  .text( label )
			       	 	);
	  					
			      	if(typeof $(this).find('.field_option_value_default:checked').val() != 'undefined' && $(this).find('.field_option_value_default:checked').val() !=''){
			      		$('select#field'+myID).find('option:last').attr('selected','selected');
			       	} 
			       	
	  				});
	  				
	  				
	  				if( $('.field_container[data-id="'+myID+'"]').find('.input-group').length>0){
		  				//remove input-group
		  				$('.field_container[data-id="'+myID+'"]').find('.input-group-addon').remove();
		  				$('.field_container[data-id="'+myID+'"]').find('.form-group select').unwrap();
		  			}
		  			if( $('#field_icon').val()!=''){
		  				//add input-group
		  				$('.field_container[data-id="'+myID+'"]').find('.form-group select').wrap('<div class="input-group"></div>');
		  				$('.field_container[data-id="'+myID+'"]').find('.input-group').prepend('<div class="input-group-addon"><span class="fa '+$('#field_icon').val()+'"></span></div>');
		  			} else {
		  				
		  			}
	  				
	  			} else if(mytype == 'radio'){
	  				$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').html('');
	  				$('.field_container[data-id="'+myID+'"]').find('.form-group .help-block').remove();
	  				/*
	  					$('.field_container[data-id="'+myID+'"]').find('.form-group')
	  						.html( $('<div></div>')
	  							.addClass('radio_wrapper') );
	  				*/
	  				$('.selectoptions_content .row').each(function(){
	  					var label = $(this).find('.field_option_label').val();
	  					var val = $(this).find('.field_option_value').val();
	  					var empty = false;
	  					if($(this).find('.field_option_value_empty').is(':checked')){
	  						empty = true;
	  					}
	  					
	  					$('.field_container[data-id="'+myID+'"]').find('.form-group .radio_wrapper')
	  						//	.append( $('<div></div>')
	  						//		.addClass('radio')
		  						.append( $('<label class="radio-inline"></label>') 
						        .append( $('<input>')
						        	.attr('type', 'radio' )
						        	
						        	.attr('data-empty', empty )
						        	//.attr('name', 'field'+myID ) //$('#field_label').val()
						        	.attr('name', $('#field_name').val() )
						        	.attr('value', val )
						        )
						        .append( label )
						    //  )
				        );
				      if($('#field_disabled').is(':checked')){
		  					$('.field_container[data-id="'+myID+'"]').find('.form-group .radio_wrapper :input:last').attr('disabled','disabled');
		  				}else{
		  					$('.field_container[data-id="'+myID+'"]').find('.form-group .radio_wrapper :input:last').removeAttr('disabled');
		  					
		  				}
				        
				        
	  					if(typeof $(this).find('.field_option_value_default:checked').val() != 'undefined' && $(this).find('.field_option_value_default:checked').val() !=''){
	  						$('.field_container[data-id="'+myID+'"]').find('.form-group .radio_wrapper').find('input:last').attr('checked', 'checked' );
	  			    } 
	  				});
	  				
	  				$('.field_container[data-id="'+myID+'"]').find('.form-group')
			        .append( $('<p></p>' )
			        	.addClass('help-block')
			        	.html( $('#field_helptext').val() )
			        );
	  			} else if(mytype == 'checkbox'){
	  				$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').html('');
	  				$('.field_container[data-id="'+myID+'"]').find('.form-group .help-block').remove();
	  				/*
	  					$('.field_container[data-id="'+myID+'"]').find('.form-group')
	  						.html( $('<div></div>')
	  							.addClass('checkbox_wrapper') );
	  				*/
	  				
	  				$('.selectoptions_content .row').each(function(){
	  					var label = $(this).find('.field_option_label').val();
	  					var val = $(this).find('.field_option_value').val();
	  					
	  					$('.field_container[data-id="'+myID+'"]').find('.form-group .checkbox_wrapper')
	  							.append( $('<label class="checkbox-inline"></label>') 
						        .append( $('<input>')
						        	.attr('type', 'checkbox' )
						        	//.attr('name',  'field'+myID )//$('#field_label').val())
						        	.attr('name',  $('#field_name').val() )//$('#field_label').val())
						        	.attr('value', val )
						        )
						        .append( label )
				        );
				      if(typeof $(this).find('.field_option_value_default_checkbox:checked').val() != 'undefined' && $(this).find('.field_option_value_default_checkbox:checked').val() !=''){
	  						$('.field_container[data-id="'+myID+'"]').find('.form-group .checkbox_wrapper input:last').attr('checked', 'checked' );
	  			    }
	  			    if($('#field_disabled').is(':checked')){
		  					$('.field_container[data-id="'+myID+'"]').find('.form-group .checkbox_wrapper input:last').attr('disabled','disabled');
		  				}else{
		  					$('.field_container[data-id="'+myID+'"]').find('.form-group .checkbox_wrapper input:last').removeAttr('disabled');
		  					
		  				}
	  				});
	  				$('.field_container[data-id="'+myID+'"]').find('.form-group')
			        .append( $('<p></p>' )
			        	.addClass('help-block')
			        	.html( $('#field_helptext').val() )
			        );
	  				
	  			}
	  			
	  			if(mytype == 'checkbox' || mytype == 'radio'){
		  			var orientation = $('#field_orientation').val();
			  		if(typeof orientation != 'undefined'){
		  				$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').attr('data-orientation', orientation );
		  				$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').attr('data-orientation', orientation );
		  				if(orientation == 'horizontal'){
		  					//Do nothing - fields have just been built in horizontal mode
		  				} else {
		  					$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').find('label').removeClass('checkbox-inline').wrap('<div class="checkbox"></div>');
		  					$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').find('label').removeClass('radio-inline').wrap('<div class="radio"></div>');
		  				}
		  			}
		  		}
	  			
	  			
	  		} else {
	  			//no selectfield
	  			if( $('.field_container[data-id="'+myID+'"]').find('.input-group').length>0){
	  				//remove input-group
	  				$('.field_container[data-id="'+myID+'"]').find('.input-group-addon').remove();
	  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').unwrap();
	  			}
	  			if( $('#field_icon').val()!=''){
	  				//add input-group
	  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').wrap('<div class="input-group"></div>');
	  				$('.field_container[data-id="'+myID+'"]').find('.input-group').prepend('<div class="input-group-addon"><span class="fa '+$('#field_icon').val()+'"></span></div>');
	  			} else {
	  				
	  			}
	  		}
	  		
	  		$('#fieldeditor').modal('hide');
	  		ps_manage_form_vars();
				psfb_on_stage_change();
	  	});
	  });/* Edit click finish */
	  
	}).trigger('click');
	psfb_on_stage_change();
	
}
function psfb_affix(){
	var $ = jQuery;
	
	$('#psfb_tab_form_stage_row').css('min-height', Math.max($('#main_right_container').height(),$('#main_center').height()) );
	
	var affixoffset = ($('#main_right').offset().top);
	var stage_offset = $('#psfb_tab_form_stage_row').offset().top;
	$(window).unbind('scroll').scroll(function () {
		if( $(window).width() >= 970 &&  $('#main_right').is(':visible')){
			if( $('#main_right_container').height() <  $('#main_center').height() ){
				var scrollTop = $(window).scrollTop();
				if ( (scrollTop+30) <= affixoffset) {
					$('#main_right_container').removeClass('psfb_affix').removeClass('psfb_affix-bottom').css({'height':'auto','overflow-y':'visible','width':'auto'});
				} else {
					var my_offset = Math.min(0, parseInt($('#main_right_container').css('top').replace('px','')) );
					var my_bottom = ( my_offset + $('#main_right_container').height() );
					var stage_bottom = ( stage_offset-scrollTop + $('#psfb_tab_form_stage_row').height()  );
					
					$('#main_right_container').addClass('psfb_affix').css({'height': Math.min( (stage_bottom-30), ($(window).height()- 30 ) ) ,'overflow-y':'auto','width':($('#main_right').width()+15)+'px'} );
					
				}
			} else {
				$('#main_right_container').removeClass('psfb_affix').removeClass('psfb_affix-bottom').css('top','');
			}
		} else {
			$('#main_right_container').removeClass('psfb_affix').removeClass('psfb_affix-bottom').css('top','');
		}
	}).trigger('scroll');
	
}

function psfb_on_stage_change(){
	var $ = jQuery;
	
	<?php do_action( 'psfb_edit_js_on_stage_change' ); ?>
	$('.form_builder_stage .row').css('borderBottom','none');
	psfb_affix();
	return;
}

function ps_manage_form_vars(){
	var $ = jQuery;
	var h = '';
	var attrnames = [];
	h += '<h4>Form Fields</h4>';
	$('.form_builder_stage .field_container .form-group').each(function(){
		$(this).find(':input').each(function(){
			
			var f_type = $(this).closest('.field_container').attr('data-type');
			var label;
			var attr = $(this).attr('name');
			
			if(f_type == 'survey_grid'){
				label = $(this).closest('tr').find('th').text();
			}else{
				label = $(this).closest('.form-group').find('label:first').text();
			}
			
			
			if( typeof attr != 'undefined' && attr.length > 0 ){
				if( $.inArray(attr,attrnames)!=-1 ){
					
				} else {
					h += '<button type="button" class="btn btn-default ps_add_variable" data-mode="admin" data-field="admin_mail_content" data-position="0" data-name="'+ attr +'">['+ label +']</button>';
					attrnames.push(attr);
				}
			}
		});
	});
	
	
	<?php do_action( 'psfb_edit_javascript_add_form_vars' ); ?>
	
	$('.ps_admin_mail_variables_stage').html( h );
	$('.ps_user_mail_variables_stage').html( h.replace(/admin/g,'user') );
	
	
	$('.ps_add_variable').click( function() {
		var mode = 'user';
		if( $(this).attr('data-mode') == 'admin'){
			var mode = 'admin';
		}
		var el = $('#'+ $(this).data('field') );
		
		if(el != false){
	    var caretPos = $(this).data('position');
	    
	    var textAreaTxt = el.val();
	    var txtToAdd = '['+$(this).data('name')+'] ';
	    el.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
	    //el.focus();
	    var newCaretPos = (parseInt(caretPos)+parseInt(txtToAdd.length));
	    el.setSelection(newCaretPos,newCaretPos);
	   	
	  } else {
	  	alert('<?php echo __('Please position the cursor at the position/field where you want to insert the variable','psfbldr'); ?>');
	  }
	});
	
}





jQuery.fn.getCursorPosition = function(){
    if(this.lengh == 0) return -1;
    return jQuery(this).getSelectionStart();
};
jQuery.fn.getSelection = function(){
    if(this.lengh == 0) return -1;
    var s = jQuery(this).getSelectionStart();
    var e = jQuery(this).getSelectionEnd();
    return this[0].value.substring(s,e);
};
jQuery.fn.getSelectionStart = function(){
    if(this.lengh == 0) return -1;
    input = this[0];
 
    var pos = input.value.length;
 
    if (input.createTextRange) {
        var r = document.selection.createRange().duplicate();
        r.moveEnd('character', input.value.length);
        if (r.text == '') 
        pos = input.value.length;
        pos = input.value.lastIndexOf(r.text);
    } else if(typeof(input.selectionStart)!="undefined")
    pos = input.selectionStart;
 
    return pos;
};
jQuery.fn.getSelectionEnd = function(){
    if(this.lengh == 0) return -1;
    input = this[0];
 
    var pos = input.value.length;
 
    if (input.createTextRange) {
        var r = document.selection.createRange().duplicate();
        r.moveStart('character', -input.value.length);
        if (r.text == '') 
        pos = input.value.length;
        pos = input.value.lastIndexOf(r.text);
    } else if(typeof(input.selectionEnd)!="undefined")
    pos = input.selectionEnd;
 
    return pos;
};
jQuery.fn.setSelection = function(selectionStart, selectionEnd) {
    if(this.lengh == 0) return this;
    input = this[0];
 
    if (input.createTextRange) {
        var range = input.createTextRange();
        range.collapse(true);
        range.moveEnd('character', selectionEnd);
        range.moveStart('character', selectionStart);
        range.select();
    } else if (input.setSelectionRange) {
        input.focus();
        input.setSelectionRange(selectionStart, selectionEnd);
    }
 
    return this;
};
jQuery.fn.setCursorPosition = function(position){
    if(this.lengh == 0) return this;
    return jQuery(this).setSelection(position, position);
};


<?php do_action( 'psfb_edit_javascript' ); ?>

</script>
<?php do_action( 'psfb_edit_after_javascript' ); ?>
<style type="text/css">



.form_builder_stage .field_container .options{
	display:none;
	margin-bottom:-40px;
	z-index:5;
	float:right;
	position:relative;
	top:0px;
	right:0px;
}
.form_builder_stage .field_container:hover .options{
	display:block;
	margin-top:-5px;
}

.droparea{
	border:1px dashed lightgray;
	min-height:50px;
	margin-bottom:0.5em;
}
.form_builder_stage .row:last-child{
	padding-bottom:25px;
}



.form_builder_stage:empty{
	
	min-height:590px;
	border:1px dashed lightgray;
	vertical-align: middle;
	text-align:center;
	position:relative;
}
.form_builder_stage:empty:before { content: '<?php echo __('1. Start by dragging fields to your form','psfbldr'); ?>'; color: #666; font-size: 2em;z-index:8;padding:0; position:relative;top:80px;width:inherit;padding-bottom:400px;background-image:url(<?php echo plugins_url( '/images/arrow-drag-in-up-left.png', (dirname(__FILE__)) ); ?>);background-position:100% 20%;background-repeat:no-repeat;}

.form_builder_stage:empty:after { content: '<?php echo __('2. Continue by saving afterwards','psfbldr'); ?>'; color: #999; font-size: 1.5em;z-index:9;padding:0; position:absolute;bottom:20px;right:20px;width:inherit;padding-bottom:100px;background-image:url(<?php echo plugins_url( '/images/arrow-drag-in-down-right.png', (dirname(__FILE__)) ); ?>);background-position:100% 100%;background-repeat:no-repeat;}

#wpfooter{position:static!important;}

#main_right_container.psfb_affix-top {
  position: static;
}
#main_right_container.psfb_affix {
  top: 30px;
  position: fixed;
}
#main_right_container.psfb_affix-bottom {
  position: absolute;
}

/* anspress fix */
.tab-content{
	max-width: none;
  overflow: inherit;
  padding: 0;
  border: 0px none;
  margin: 0;
  min-height: inherit;
}

.selectoptions_content_desc_default{
text-align:right;	
}

.nav-tabs a[data-toggle="tab"]{
	display:block!important;
}

<?php do_action( 'psfb_edit_cssstyles' ); ?>
</style>
<!--email_off-->

<div class="editoptions_template" style="display:none;">
<button type="button" class="delete btn btn-danger btn-xs"><span class="fa fa-trash"></span> <?php echo __('Delete','psfbldr'); ?></button>

<button type="button" class="edit btn btn-success btn-xs"><span class="fa fa-pencil-square-o"></span> <?php echo __('Edit','psfbldr'); ?></button>
<button type="button" class="copy btn btn-default btn-xs"><span class="fa fa-clone"></span> <?php echo __('Copy Row','psfbldr'); ?></button>	
	<!--<div type="button" class="move-h btn btn-default btn-xs" style="cursor:ew-resize;" title="<?php echo __('Move','psext'); ?>"><span class="fa fa-arrows-h"></span></div>-->
	<div type="button" class="move-v btn btn-default btn-xs" style="cursor:ns-resize;" title="<?php echo __('Move','psext'); ?>"><span class="fa fa-arrows-v"></span></div>
	<div type="button" class="move-hv btn btn-default btn-xs" style="cursor:move;" title="<?php echo __('Move','psext'); ?>"><span class="fa fa-arrows"></span></div>
</div>

<style type="text/css">
	.modal-backdrop{z-index:5!important;}
	.modal-dialog{z-index:10!important;}
</style>

<div class="modal fade" id="psfb_help_modal" tabindex="-1" role="dialog" aria-labelledby="psfb_help_modal_label" aria-hidden="true">
  <div class="modal-dialog" style="z-index:1041!important;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo __('Close','psfbldr'); ?>"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="psfb_help_modal_label"><?php echo __('Help &amp; Hints','psfbldr'); ?></h4>
      </div>
      <div class="modal-body">
      	
      	
      	<?php 
      	if ( $psfb_smtp_plugin_active == true ) {
      		//plugin is activated
      	} else if(is_dir( dirname(dirname(dirname(__FILE__))).'/post-smtp' )){
      		//plugin is installed but inactive
      		?>
      		<div class="form-group">
	      		<label><?php echo __('Post SMTP installed but inactive','psfbldr'); ?></label>
	      		<a href="<?php 
	      			echo admin_url( 'plugins.php' ).'?plugin_status=inactive';
	      			/*
	      			echo wp_nonce_url(
					    add_query_arg(
					        array(
					            'action' => 'activate',
					            'plugin' => 'post-smtp/postman-smtp.php',
					            'plugin_status' => 'all',
					            'paged' => 1
					            
					        ),
					        admin_url( 'plugins.php' )
					    ),
					    'activate_post-smtp%2Fpost-smtp.php_all_1'
						);*/
	      		?>" target="_blank" class="btn btn-success btn-xs"><?php echo __('Activate Post SMTP','psfbldr'); ?></a>
	      		<p class="help-block"><?php echo __('By clicking the button you will be transfered to your plugin-section. Please find Post SMTP there and click on &#39;activate&#39;. Don&#39;nt forget to configure the plugin afterwards.','psfbldr'); ?></p>
	      	</div>
      	<?php
				} else {
					//plugin is not installed
					?>
					<div class="form-group">
      		<label><?php echo __('Trouble sending emails?','psfbldr'); ?></label>
      		<a href="<?php
					echo wp_nonce_url(
					    add_query_arg(
					        array(
					            'action' => 'install-plugin',
					            'plugin' => 'post-smtp'
					        ),
					        admin_url( 'update.php' )
					    ),
					    'install-plugin_post-smtp'
					);
					?>" target="_blank" class="btn btn-success btn-xs"><?php echo __('Install Post SMTP','psfbldr'); ?></a>
      		<p class="help-block"><?php echo __('The button above will lead you to the plugin installation of','psfbldr'); ?> <a href="https://wordpress.org/plugins/post-smtp/" target="_blank">Post SMTP</a></p>
      	</div>
      	<?php
				}
      	?>
      	
      	<?php 
      	if ( is_plugin_active( 'goodbye-captcha/goodbye-captcha.php' ) ) {
      		//plugin is activated
      	} else if(is_dir( dirname(dirname(dirname(__FILE__))).'/goodbye-captcha' )){
      		//plugin is installed but inactive
      		?>
      		<div class="form-group">
	      		<label><?php echo __('GoodbyeCaptcha installed but inactive','psfbldr'); ?></label>
	      		<a href="<?php 
	      			echo admin_url( 'plugins.php' ).'?plugin_status=inactive';
	      			/*
	      			echo wp_nonce_url(
					    add_query_arg(
					        array(
					            'action' => 'activate',
					            'plugin' => 'post-smtp/postman-smtp.php',
					            'plugin_status' => 'all',
					            'paged' => 1
					            
					        ),
					        admin_url( 'plugins.php' )
					    ),
					    'activate_post-smtp%2Fpost-smtp.php_all_1'
						);*/
	      		?>" target="_blank" class="btn btn-success btn-xs"><?php echo __('Activate GoodbyeCaptcha','psfbldr'); ?></a>
	      		<p class="help-block"><?php echo __('By clicking the button you will be transfered to your plugin-section. Please find GoodbyeCaptcha there and click on &#39;activate&#39;. Don&#39;nt forget to configure the plugin afterwards.','psfbldr'); ?></p>
	      	</div>
      	<?php
				} else {
					//plugin is not installed
					?>
					<div class="form-group">
      		<label><?php echo __('Looking for better SPAM protection?','psfbldr'); ?></label>
      		<a href="<?php
					echo wp_nonce_url(
					    add_query_arg(
					        array(
					            'action' => 'install-plugin',
					            'plugin' => 'goodbye-captcha'
					        ),
					        admin_url( 'update.php' )
					    ),
					    'install-plugin_goodbye-captcha'
					);
					?>" target="_blank" class="btn btn-success btn-xs"><?php echo __('Install GoodbyeCaptcha','psfbldr'); ?></a>
      		<p class="help-block"><?php echo __('The button above will lead you to the plugin installation of','psfbldr'); ?> <a href="https://wordpress.org/plugins/goodbye-captcha/" target="_blank">GoodbyeCaptcha</a></p>
      	</div>
      	<?php
				}
      	?>
      	
      	
      	<div class="form-group">
      		<label><?php echo __('Visit support forum','psfbldr'); ?></label>
      		<a href="https://wordpress.org/support/plugin/planso-forms" target="_blank" class="btn btn-success btn-xs"><?php echo __('Support forum','psfbldr'); ?></a>
      		<p class="help-block"><?php echo __('The button above will open a new window and lead you to the PlanSo Forms section of the WordPress support forum.','psfbldr'); ?></p>
      	</div>
      	
      	<div class="form-group">
      		<label><?php echo __('Test form submission using dummy values','psfbldr'); ?></label>
      		<button type="button" class="psfb_test_form_submit btn btn-success btn-xs"><?php echo __('Test submission','psfbldr'); ?></button>
      		<p class="help-block"><?php echo __('By clicking the above button PlanSo Forms will simulate a form submission. This is usefull for testing email settings or other API connections.','psfbldr'); ?></p>
      	</div>
      	
      	<form method="post" target="_blank" action="https://www.planso.de/planso-plugins/submit-form-settings.php">
	      	<div style="display:none;">
      			<?php
      				$current_user = wp_get_current_user();
      				
      				echo '<div class="form-group"><input type="text" name="email" value="'.$current_user->user_email.'"></div>';
      				echo '<div class="form-group"><input type="text" name="first_name" value="'.$current_user->user_firstname.'"></div>';
      				echo '<div class="form-group"><input type="text" name="last_name" value="'.$current_user->user_lastname .'"></div>';
      				echo '<div class="form-group"><input type="text" name="url" value="http://'.$_SERVER['HTTP_HOST'].'"></div>';
      				echo '<div class="form-group"><textarea name="server">'.print_r($_SERVER,true).'</textarea></div>';
      			?>
      			<div class="form-group"><textarea name="json" id="psfb_submit_settings_json"></textarea></div>
      		</div>
	      	<div class="form-group">
	      		<label><?php echo __('Request support','psfbldr'); ?></label>
	      		<textarea name="msg" required="required" class="form-control" rows="7" placeholder="<?php echo __('Please describe your problem as detailed as possible','psfbldr'); ?>"></textarea>
	      		<button class="psfb_submit_form_settings_to_planso btn btn-danger btn-xs" type="submit" onmousedown="jQuery('#psfb_submit_settings_json').val( jQuery('#psfb_json').val() );"><?php echo __('Submit message and settings','psfbldr'); ?></button>
	      		<p class="help-block"><?php echo __('By clicking the button you will send your name, email, form settings and server details to PlanSo. With the data submitted the PlanSo Forms Team can offer you better support.','psfbldr'); ?></p>
	      	</div>
      	</form>
      	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close','psfbldr'); ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" id="fieldeditor" tabindex="-1" role="dialog" aria-labelledby="fieldeditorlabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo __('Close','psfbldr'); ?>"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="fieldeditorlabel"><?php echo __('Edit field','psfbldr'); ?></h4>
      </div>
      <div class="modal-body">
        
        
        <div role="tabpanel">
				
				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active basicstab"><a href="#tab-basics" aria-controls="basics" role="tab" data-toggle="tab"><?php echo __('Basic','psfbldr'); ?></a></li>
				    <li role="presentation" class="basicshtmltab"><a href="#tab-basicshtml" aria-controls="basicshtml" role="tab" data-toggle="tab"><?php echo __('Basic','psfbldr'); ?></a></li>
				    <li role="presentation" class="selectoptionstab"><a href="#tab-selectoptions" aria-controls="selectoptions" role="tab" data-toggle="tab"><?php echo __('Select values','psfbldr'); ?></a></li>
				    <?php do_action('psfb_edit_modal_after_selectoptions_tab'); ?>
				    <li role="presentation" class="experttab"><a href="#tab-expert" aria-controls="profile" role="tab" data-toggle="tab"><?php echo __('Advanced','psfbldr'); ?></a></li>
				    
				  </ul>
        
        	<div class="tab-content">
        
		        <div class="basics tab-pane active" id="tab-basics" role="tabpanel">
		        	
		        	
						  <div class="form-group">
						    <label for="field_label"><?php echo __('Field label','psfbldr'); ?></label>
						    <input type="text" id="field_label" class="form-control">
						    <p class="help-block"><?php echo __('Enter the label that describes the field','psfbldr'); ?></p>
						  </div>
						  
						  <div class="form-group field_default_value_wrapper">
						    <label for="field_default_value"><?php echo __('Default Value','psfbldr'); ?></label>
						    <input type="text" id="field_default_value" class="form-control">
						    <p class="help-block"><?php echo __('This value will be pre-filled in the field once the form is loaded.','psfbldr'); ?></p>
						  </div>
						  
						  <div class="form-group field_placeholder_wrapper">
						    <label for="field_placeholder"><?php echo __('Placeholder','psfbldr'); ?></label>
						    <input type="text" id="field_placeholder" class="form-control">
						    <p class="help-block"><?php echo __('The placeholder is placed within empty fields and helps your users filling out the form','psfbldr'); ?></p>
						  </div>
						  
						  <div class="form-group">
						    <label for="field_helptext"><?php echo __('Help-Text','psfbldr'); ?></label>
						    <textarea id="field_helptext" class="form-control"></textarea>
						    <p class="help-block"><?php echo __('Provide additional help for filling out this field','psfbldr'); ?></p>
						  </div>
						  
						  <div class="form-group field_icon_wrapper">
						    <label for="field_icon"><?php echo __('Icon','psfbldr'); ?></label>
						    <div class="input-group">
									<input id="field_icon" data-placement="bottomRight" class="form-control icp icp-auto" type="text" />
									<span class="input-group-addon" id="field_icon_addon"></span>
								</div>
						    <p class="help-block"><?php echo __('Please enter a Font-Awesome icon class. i.e. &quot;fa-user&quot;. Available Icons are found here: ','psfbldr'); ?><a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font-Awesome</a></p>
						  </div>
						  
						  <div class="form-group field_orientation_wrapper">
						    <label for="field_orientation"><?php echo __('Orientation','psfbldr'); ?></label>
						    <select id="field_orientation" class="form-control">
						    	<option value="horizontal"><?php echo __('Horizontal (inline)','psfbldr'); ?></option>
						    	<option value="vertical"><?php echo __('Vertical (one per line)','psfbldr'); ?></option>
						    </select>
						    <p class="help-block"><?php echo __('This lets you choose the orientation of your checkbox and radio fields','psfbldr'); ?></p>
						  </div>
						  
						  <div class="row">
						  	
							  <div class="form-group col-md-4">
							    <label for="field_required"><?php echo __('Mandatory','psfbldr'); ?></label>
							  	<div class="checkbox">
							  		<label>
							  			<input type="checkbox" id="field_required" value="1"> <?php echo __('Mandatory field','psfbldr'); ?>
							  		</label>
							  	</div>
							    <p class="help-block"><?php echo __('Check this to mark the field as required','psfbldr'); ?></p>
							  </div>
							  
							  
							  <div class="form-group col-md-4">
							    <label for="field_hide_label"><?php echo __('Hide label','psfbldr'); ?></label>
							  	<div class="checkbox">
							  		<label>
							  			<input type="checkbox" id="field_hide_label" value="1"> <?php echo __('Hide label of field','psfbldr'); ?>
							  		</label>
							  	</div>
							    <p class="help-block"><?php echo __('Check this to hide the label of the field','psfbldr'); ?></p>
							  </div>
							  
							  <div class="form-group col-md-4 field_readonly_wrapper">
							    <label for="field_readonly_label"><?php echo __('Read Only','psfbldr'); ?></label>
							  	<div class="checkbox">
							  		<label>
							  			<input type="checkbox" id="field_readonly" value="1"> <?php echo __('Read Only field','psfbldr'); ?>
							  		</label>
							  	</div>
							    <p class="help-block"><?php echo __('Check this to make the field readonly','psfbldr'); ?></p>
							  </div>
							  <div class="form-group col-md-4 field_disabled_wrapper" style="display:none;">
							    <label for="field_disabled_label"><?php echo __('Disable','psfbldr'); ?></label>
							  	<div class="checkbox">
							  		<label>
							  			<input type="checkbox" id="field_disabled" value="1"> <?php echo __('Disable the field','psfbldr'); ?>
							  		</label>
							  	</div>
							    <p class="help-block"><?php echo __('Check this to make the field disabled','psfbldr'); ?></p>
							  </div>
							 
						  
						</div>
							<?php do_action( 'psfb_edit_modal_basics_after_mandatory' ); ?>
						
						  
		        </div><!-- ende basics -->
		        
		        
		        
		        <div class="basicshtml tab-pane" id="tab-basicshtml" role="tabpanel">
		        	
						  
						  <div class="form-group">
						    <label for="field_html_content"><?php echo __('Content','psfbldr'); ?></label>
						    <textarea id="field_html_content" class="form-control"></textarea>
						    <p class="help-block"><?php echo __('Enter your content here. You can use HTML tags to enrich this block.','psfbldr'); ?></p>
						  </div>
						  
		        </div><!-- ende basicshtml -->
		        <div class="selectoptions tab-pane" id="tab-selectoptions" role="tabpanel">
		        	<div class="selectoptions_template" style="display:none;">
		        		
		        		<div class="row form-group">
		        			
		        			<div class="col-md-4">
		        				<input type="text" class="form-control field_option_label" placeholder="<?php echo __('Label','psfbldr'); ?>">
		        			</div>
		        			<div class="col-md-4">
		        				<input type="text" class="form-control field_option_value" placeholder="<?php echo __('Value','psfbldr'); ?>">
		        			</div>
		        			
		        			<div class="col-md-1 selectoptions_default_and_empty">
		        				<input type="radio" class="field_option_value_default" name="radio">
		        			</div>
		        			<div class="col-md-1 selectoptions_default_and_empty" >
		        				<input type="checkbox" class="field_option_value_empty" name="checkbox">
		        			</div>	
		        			
		        			<div class="selectoptions_default_checkbox col-md-1" >
		        				<input type="checkbox" class="field_option_value_default_checkbox" name="checkbox">
		        			</div>	
		        			<div class="col-md-2">
		        				<button class="delete_selectoption btn btn-danger btn-xs" tabindex="-1"><span class="fa fa-trash"></span></button>
		        			</div>
		        			
		        		</div>
		        		
		        	</div>
		        	
		        	<div class="selectoptions">
		        		
							  <div class="form-group">
							    <label for="field_helptext"><?php echo __('Option value pairs','psfbldr'); ?></label>
								  <div class="row selectoptions_content_desc">
			        			<div class="col-md-4" title="<?php echo __('The visible part','psfbldr'); ?>">
			        				<?php echo __('Label','psfbldr'); ?>
			        			</div>
			        			<div class="col-md-3" title="<?php echo __('The value will be submitted to you','psfbldr'); ?>">
			        				<?php echo __('Value','psfbldr'); ?>
			        			</div>
			        			
			        			<div class="col-md-2 selectoptions_content_desc_default selectoptions_content_desc_default_and_empty" >
		        					<?php echo __('Default','psfbldr'); ?>
		        				</div>
		        				<div class="col-md-1 selectoptions_content_desc_empty selectoptions_content_desc_default_and_empty">
		        					<?php echo __('Empty','psfbldr'); ?>
		        				</div>
		        					
			        			<div class="col-md-2">
			        				
			        				
			        			</div>
			        			
			        		</div>
			        		<div class="row selectoptions_quick_content_desc" style="display:none;">
			        			
			        			<div class="col-md-12">
			        				<?php echo __('Please enter one option per line. If you want to use a value different from the label please divide your label value pairs with "," like <em>mylabel,myvalue</em>. If you want to use a comma (,) in either your label or value you additionally have to wrap the corresponding string in quotes like <em>"mylabel with, comma","myvalue, with comma"</em>. For empty or default values you can use <abbr title="Default">d</abbr>,<abbr title="Empty">e</abbr> or <abbr title="Default &amp; Empty">de</abbr> as the third option after the value as in: <em>mylabel,myvalue, <abbr title="Default &amp; Empty">de</abbr></em>.','psfbldr'); ?>
			        			</div>
			        			
			        		</div>
								  
			        		<div class="selectoptions_content">
			        			
			        		</div>
			        		<div class="selectoptions_quick_content" style="display:none;">
			        			<textarea class="form-control" rows="10"></textarea>
			        		</div>
			        		<div class="selectoptions_option">
			        			<button class="btn btn-success btn-xs add_selectoption"><span class="fa fa-plus"></span> <?php echo __('Add option','psfbldr'); ?></button>
			        			<button class="btn btn-default btn-xs toggle_selectoption"><span class="fa fa-random"></span> <?php echo __('Toggle option edit mode','psfbldr'); ?></button>
			        			
			        		</div>
			        		
							  </div>
		        	</div>
		        </div>
		        
		        <?php do_action('psfb_edit_modal_after_selectoptions_tab_content'); ?>
		        
		        <div class="expert tab-pane" id="tab-expert" role="tabpanel">
		        	
						  <div class="form-group" style="display:none;">
						    <label for="field_name"><?php echo __('Field variable name','psfbldr'); ?></label>
						    <input type="text" id="field_name" class="form-control">
						    <input type="hidden" id="field_name_orig" >
						    <p class="help-block"><?php echo __('This variable can be used in the emails sent upon submit','psfbldr'); ?></p>
						  </div>
		        	
						  <div class="form-group">
						    <label for="field_cssclass"><?php echo __('CSS Class','psfbldr'); ?></label>
						    <input type="text" id="field_cssclass" class="form-control">
						    <p class="help-block"><?php echo __('Add an individual CSS class name to this field','psfbldr'); ?></p>
						  </div>
						  
						  <div class="form-group">
						    <label for="field_cssstyle"><?php echo __('CSS Style','psfbldr'); ?></label>
						    <input type="text" id="field_cssstyle" class="form-control">
						    <p class="help-block"><?php echo __('Format this field with custom inline CSS rules','psfbldr'); ?></p>
						  </div>
						  <div class="form-group field_maxlength_wrapper" style="display:none;">
						    <label for="field_maxlength"><?php echo __('Max length','psfbldr'); ?></label>
						    <input type="number" id="field_maxlength" class="form-control">
						    <p class="help-block"><?php echo __('Restrict number of characters allowed by this input.Default is unlimited.','psfbldr'); ?></p>
						  </div>
						  <div class="row field_number_ranges_wrapper" style="display:none;">
						  	<div class="col-md-6">
						  		<div class="form-group">
								    <label for="field_numbermin"><?php echo __('Min','psfbldr'); ?></label>
								    <input type="number" id="field_numbermin" class="form-control">
								    <p class="help-block"><?php echo __('Minumum number allowed to be entered','psfbldr'); ?></p>
								  </div>
						  	</div>
						  	<div class="col-md-6">
						  		<div class="form-group">
								    <label for="field_numbermax"><?php echo __('Max','psfbldr'); ?></label>
								    <input type="number" id="field_numbermax" class="form-control">
								    <p class="help-block"><?php echo __('Maximum number allowed to be entered','psfbldr'); ?></p>
								  </div>
						  	</div>
						  	
					  	</div>
						  
						  <?php do_action( 'psfb_edit_modal_advanced_after_style' ); ?>
		        	<!-- prefill value from get/post -->
		        </div><!-- ende expert -->
		      </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default cancelsavefield" data-dismiss="modal"><?php echo __('Cancel','psfbldr'); ?></button>
        <button type="button" class="btn btn-primary savefield"><?php echo __('Update','psfbldr'); ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


 
<div id="titlediv">
	<div id="titlewrap">
		<input type="text" size="30" value="<?php if(isset($psform) && isset($psform->post_title))echo $psform->post_title;?>" class="psfb_title_input" id="title" spellcheck="true" autocomplete="off" placeholder="<?php echo __('Form Title','psfbldr'); ?>">
	<?php echo $shortcode_out; ?>
	</div>
	<div class="inside">
		<div id="edit-slug-box" class="hide-if-no-js">
			</div>
	</div>
</div>

<?php do_action( 'psfb_edit_after_title_input' ); ?>


<section class="container-fluid">
	<div class="row">
		<div class="form-group">
			<div class="btn-group" role="group" style="float:right;">
				<button class="psfb_open_help_modal btn btn-default" title="<?php echo __('Help','psfbldr'); ?>"><span class="fa fa-life-saver"></span></button>
				<button class="psfb_save_perform btn btn-primary"><?php echo __('Save','psfbldr'); ?></button>
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>
</section>


 <div role="tabpanel">
				
	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" class="active"><a href="#psfb_tab_form_stages" aria-controls="form_stage" role="tab" data-toggle="tab"><?php echo __('Form fields','psfbldr'); ?></a></li>
	   	<?php do_action( 'psfb_edit_tabs_link_before_mails' ); ?>
	    <li role="presentation"><a href="#psfb_tab_admin_email" aria-controls="admin_email" role="tab" data-toggle="tab"><?php echo __('Admin email','psfbldr'); ?></a></li>
	    <li role="presentation"><a href="#psfb_tab_user_email" aria-controls="user_email" role="tab" data-toggle="tab"><?php echo __('User email','psfbldr'); ?></a></li>
	    <li role="presentation"><a href="#psfb_tab_thankyou_page" aria-controls="thankyou_page" role="tab" data-toggle="tab"><?php echo __('Thankyou page','psfbldr'); ?></a></li>
	    <li role="presentation"><a href="#psfb_tab_additional_settings" aria-controls="additional_settings" role="tab" data-toggle="tab"><?php echo __('Additional settings','psfbldr'); ?></a></li>
	  	
	  	<?php 
				if(defined('PLANSO_FORMS_PRO')){
			?>
	  	<li role="presentation"><a href="#psfb_tab_pro" aria-controls="psfb_pro" role="tab" data-toggle="tab"><?php echo __('PlanSo Forms Pro','psfbldr'); ?></a></li>
	  	<?php 
				}
			?>
			
	  	<?php do_action( 'psfb_edit_tabs_link' ); ?>
	  	<li role="presentation"><a href="#psfb_tab_update" aria-controls="psfb_update" role="tab" data-toggle="tab"><?php echo __('Update','psfbldr'); ?></a></li>
	  </ul>
  
  	<div class="tab-content">
  
      <div class="form_stage tab-pane active" id="psfb_tab_form_stages" role="tabpanel">
			

<section class="container-fluid postbox "><!-- container-fluid -->
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('Form settings','psfbldr'); ?></strong></span></h3>
					
				</div>
				<br class="clear">
				<div id="psfb_tab_form_stage_row">
					<section id="main_center" class="col-md-9">
						<section id="main_center_center">
							<section id="content">
								
								<form class="form_builder_stage planso-form-builder" onsubmit="return false;"></form>
								
								
							</section>
						</section>
					</section>
					<aside id="main_right" class="col-md-3">
						<section id="main_right_container">
							
							
						</section>
						<br class="clear">
						<br class="clear">
					</aside>
				</div>
			</div>
		</div>
		
	</section><!-- ende .row -->
</section><!-- ende .container -->

<?php do_action( 'psfb_edit_after_form_stage' ); ?>


				<div style="clear:both;"></div>
			</div>
			<div class="thankyou_page tab-pane" id="psfb_tab_thankyou_page" role="tabpanel">
			

<section class="container-fluid postbox "><!-- container-fluid -->
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('Thank you page','psfbldr'); ?></strong></span></h3>
				</div>
				<br class="clear">
				<section class="col-md-12">
					
					  <?php do_action( 'psfb_edit_in_thankyoupage_content_top' ); ?>
						<div class="form-group">
					    <label for="thankyou_page_url"><?php echo __('Thank you page url','psfbldr'); ?></label>
					    <input type="text" placeholder="http://" id="thankyou_page_url" class="form-control thankyou_page_url" value="<?php if(isset($j->thankyou_page_url))echo $j->thankyou_page_url; ?>">
					    <p class="help-block"><?php echo __('Enter the website address (inkl. http://) of the page the user should be redirected to after successfully submitting the form','psfbldr'); ?></p>
					  </div>
					  <?php do_action( 'psfb_edit_in_thankyoupage_content' ); ?>
				</section>
			</div>
		</div>
	</section>
</section>

<?php do_action( 'psfb_edit_after_thankyoupage' ); ?>



			</div>
			<div class="admin_email tab-pane" id="psfb_tab_admin_email" role="tabpanel">
			

<section class="container-fluid postbox ps_user_mail_wrapper"><!-- container-fluid -->
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('Admin Mail settings','psfbldr'); ?></strong></span>
					<?php 
						$psfb_admin_user_mail_notice = '';
						if ( $psfb_smtp_plugin_active ) {
							//plugin is installed
						}else if(is_dir( dirname(dirname(dirname(__FILE__))).'/postman-smtp' ) || is_dir( dirname(dirname(dirname(__FILE__))).'/post-smtp' )){
      				//plugin is installed but inactive
      					$psfb_admin_user_mail_notice = '
		      			
			      		<p class="bg-warning help-block" style="padding: 0.4em 0.6em;border-radius: 4px;box-shadow: 0 0 4px #ffdd00;">'.__('Activate Post Smtp plugin to have more mail settings and better delivery of mails.','psfbldr').'</p>
			      		<a href="';
			      		$psfb_admin_user_mail_notice .= admin_url( 'plugins.php' ).'?plugin_status=inactive.';
			      		$psfb_admin_user_mail_notice .= '" target="_blank" class="btn btn-success btn-xs"> '.__('Activate Post SMTP','psfbldr').' </a>
			      		
		      		';
						} else if ( !$psfb_smtp_plugin_active ) {
		      		//plugin is activated
		      		
		      		$psfb_admin_user_mail_notice = '
		      			<p class="bg-warning help-block" style="padding: 0.4em 0.6em;border-radius: 4px;box-shadow: 0 0 4px #ffdd00;">
		      				'.__('Please install a Smtp Plugin, so that you can have more mail settings and better delivery of mails.','psfbldr').'
		      		 		<a href="'.wp_nonce_url(add_query_arg( array( 'action' => 'install-plugin', 'plugin' => 'post-smtp' ),admin_url( 'update.php' )),'install-plugin_post-smtp').'" class="btn btn-warning btn-xs">'. __('Install Post-SMTP','psfbldr').'</a>
		      			</p>
		      		';
		      	}
		      	echo $psfb_admin_user_mail_notice;
		      	?>
		      	</h3>
				</div>
				<br class="clear">
				
				<section class="col-md-9">
					
					<div class="ps_admin_mail_stage">
						<?php 
							if(isset($j)){
								
								if(isset($j->mails) && isset($j->mails->admin_mail)){
									$admin_mail = $j->mails->admin_mail;
								} else {
									$admin_mail = new stdClass;
									$admin_mail->content = '';
									$admin_mail->subject = '';
									$admin_mail->from_name = get_option( 'blogname', __('Your Wordpress Blog','psfbldr') );
									$admin_mail->from_email = get_option( 'admin_email', 'no-reply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) );
									$admin_mail->reply_to = '';
									$admin_mail->recipients = array(get_option( 'admin_email', 'info@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) ));
									$admin_mail->bcc = array();
								}
								if(isset($j->mails) && isset($j->mails->user_mail)){
									$user_mail = $j->mails->user_mail;
								} else {
									$user_mail = new stdClass;
									$user_mail->content = '';
									$user_mail->subject = '';
									$user_mail->from_name = get_option( 'blogname', __('Your Wordpress Blog','psfbldr') );
									$user_mail->from_email = get_option( 'admin_email', 'no-reply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) );
									$user_mail->reply_to = get_option( 'admin_email', 'info@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) );
									$user_mail->recipients = array();
									$user_mail->bcc = array();
								}
							} else {
								$admin_mail = new stdClass;
									$admin_mail->content = '';
									$admin_mail->subject = '';
									$admin_mail->from_name = get_option( 'blogname', __('Your Wordpress Blog','psfbldr') );
									$admin_mail->from_email = get_option( 'admin_email', 'no-reply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) );
									$admin_mail->reply_to = '';
									$admin_mail->recipients = array(get_option( 'admin_email', 'info@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) ));
									$admin_mail->bcc = array();
								$user_mail = new stdClass;
									$user_mail->content = '';
									$user_mail->subject = '';
									$user_mail->from_name = get_option( 'blogname', __('Your Wordpress Blog','psfbldr') );
									$user_mail->from_email = get_option( 'admin_email', 'no-reply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) );
									$user_mail->reply_to = get_option( 'admin_email', 'info@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) );
									$user_mail->recipients = array();
									$user_mail->bcc = array();
							}
						?>
						<div class="form-group">
					    <label for="admin_mail_subject"><?php echo __('Admin mail subject','psfbldr'); ?></label>
					    <input type="text" id="admin_mail_subject" class="form-control admin_mail_subject" value="<?php echo $admin_mail->subject; ?>">
					    <p class="help-block"><?php echo __('This is the subject of the email sent to you/the admin','psfbldr'); ?></p>
					  </div>
						
						<?php
							//WordPress Editor
							//wp_editor( $admin_mail->content, 'admin_mail_content' , array('editor_class'=>'form-control','textarea_rows'=>'6') );//
						?> 
						
						<div class="form-group">
					    <label for="admin_mail_content"><?php echo __('Admin mail body','psfbldr'); ?></label>
					    <textarea rows="7" id="admin_mail_content" class="form-control admin_mail_content"><?php echo $admin_mail->content; ?></textarea>					    <p class="help-block"><?php echo __('This is the content of the email sent to you/the admin','psfbldr'); ?></p>
					  </div>
						
						<div class="form-group">
					    <label for="admin_mail_recipients"><?php echo __('Admin mail recipient(s)','psfbldr'); ?></label>
					    <input type="text" id="admin_mail_recipients" class="form-control admin_mail_recipients" value="<?php if(isset($admin_mail->recipients) && count($admin_mail->recipients)>0)echo implode(';',$admin_mail->recipients) ?>">
					    <p class="help-block"><?php echo __('Enter one or more recipient email address for the admin mail. Divide multiple recipients with ; or , or with a space','psfbldr'); ?></p>
					  </div>
					  
						<?php 
		      	if (  $psfb_smtp_plugin_active == true) {
		      		//plugin is activated
		      		?>
		      		<div class="form-group">
						    <label for="admin_mail_bcc"><?php echo __('Admin mail bcc recipient(s)','psfbldr'); ?></label>
						    <input type="text" id="admin_mail_bcc" class="form-control admin_mail_bcc" value="<?php if(isset($admin_mail->bcc) && count($admin_mail->bcc)>0)echo implode(';',$admin_mail->bcc) ?>">
						    <p class="help-block"><?php echo __('Enter one or more bcc email address for the admin mail. Divide multiple recipients with ; or , or with a space','psfbldr'); ?></p>
					  	</div>
					 
						  <div class="row">
						  	<div class="col-md-6">
						  		<div class="form-group">
								    <label for="admin_mail_from_name"><?php echo __('Admin mail from name','psfbldr'); ?></label>
								    <input type="text" id="admin_mail_from_name" class="form-control admin_mail_from_name" value="<?php if(isset($admin_mail->from_name)) echo $admin_mail->from_name; ?>">
								    <p class="help-block"><?php echo __('This is the senders name for the email sent to you/the admin','psfbldr'); ?></p>
								  </div>
						  	</div>
						  	<div class="col-md-6">
						  		<div class="form-group">
								    <label for="admin_mail_from_email"><?php echo __('Admin mail from email','psfbldr'); ?></label>
								    <input type="text" id="admin_mail_from_email" class="form-control admin_mail_from_email" value="<?php if(isset($admin_mail->from_email)) echo $admin_mail->from_email; ?>">
								    <p class="help-block"><?php echo __('This is the senders email adress for the email sent to you/the admin','psfbldr'); ?></p>
								  </div>
						  	</div>
						  </div>
						
							
						  <?php
		      	} else {
							//plugin is not installed
						
						}
			      ?>
			      <div class="form-group">
					    <label for="admin_mail_reply_to"><?php echo __('Admin mail reply to address','psfbldr'); ?></label>
					    <input type="text" id="admin_mail_reply_to" class="form-control admin_mail_reply_to" value="<?php echo $admin_mail->reply_to; ?>">
					    <p class="help-block"><?php echo __('Enter one email address that is used as the reply adress when answering the admin mail.','psfbldr'); ?></p>
					  </div>
						<?php do_action( 'psfb_edit_after_admin_mail_reply_to' ); ?>
						
						
					</div>
					
				</section>
				<aside class="col-md-3">
					
					<div class="ps_admin_mail_variables_stage">
						
					</div>
					
				</aside>
			</div>
		</div>
	</section>
</section>


<?php do_action( 'psfb_edit_after_admin_mail' ); ?>




			</div>
			<div class="user_email tab-pane" id="psfb_tab_user_email" role="tabpanel">
			

<section class="container-fluid postbox "><!-- container-fluid -->
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('User Mail settings','psfbldr'); ?></strong></span>
						<?php 
		      	echo $psfb_admin_user_mail_notice;
		      	?>
					</h3>
				</div>
				<br class="clear">
				
				<section class="col-md-9">
					
					<div class="ps_user_mail_stage">
						
						<div class="form-group">
					    <label for="user_mail_subject"><?php echo __('User mail subject','psfbldr'); ?></label>
					    <input type="text" id="user_mail_subject" class="form-control user_mail_subject" value="<?php echo $user_mail->subject; ?>">
					    <p class="help-block"><?php echo __('This is the subject of the email sent to the user of the form','psfbldr'); ?></p>
					  </div>
						
						<?php
							//WordPress Editor
							//wp_editor( $admin_mail->content, 'admin_mail_content' , array('editor_class'=>'form-control','textarea_rows'=>'6') );//
						?> 
						
						<div class="form-group">
					    <label for="user_mail_content"><?php echo __('User mail body','psfbldr'); ?></label>
					    <textarea rows="7" id="user_mail_content" class="form-control user_mail_content"><?php echo $user_mail->content; ?></textarea>
					    <p class="help-block"><?php echo __('This is the content of the email sent to the user of the form','psfbldr'); ?></p>
					  </div>
						
						
						<div class="form-group">
					    <label for="user_mail_recipients"><?php echo __('User mail recipient(s)','psfbldr'); ?></label>
					    <input type="text" id="user_mail_recipients" class="form-control user_mail_recipients" value="<?php if(isset($user_mail->recipients) && count($user_mail->recipients)>0)echo implode(';',$user_mail->recipients) ?>">
					    <p class="help-block"><?php echo __('Enter one or more recipient email address for the user mail. Divide multiple recipients with ; or , or with a space','psfbldr'); ?></p>
					  </div>
					  
					  <?php 
		      	if ( $psfb_smtp_plugin_active == true) {
		      		//plugin is activated
		      		?>
		      		<div class="form-group">
					    	<label for="user_mail_bcc"><?php echo __('User mail bcc recipient(s)','psfbldr'); ?></label>
					    	<input type="text" id="user_mail_bcc" class="form-control user_mail_bcc" value="<?php if(isset($user_mail->bcc) && count($user_mail->bcc)>0)echo implode(';',$user_mail->bcc) ?>">
					    	<p class="help-block"><?php echo __('Enter one or more bcc email address for the user mail. Divide multiple recipients with ; or , or with a space','psfbldr'); ?></p>
					  	</div>
					  
						  <div class="row">
						  	<div class="col-md-6">
						  		<div class="form-group">
								    <label for="user_mail_from_name"><?php echo __('User mail from name','psfbldr'); ?></label>
								    <input type="text" id="user_mail_from_name" class="form-control user_mail_from_name" value="<?php if(isset($user_mail->from_name)) echo $user_mail->from_name; ?>">
								    <p class="help-block"><?php echo __('This is the senders name for the email sent to the user','psfbldr'); ?></p>
								  </div>
						  	</div>
						  	<div class="col-md-6">
						  		<div class="form-group">
								    <label for="user_mail_from_email"><?php echo __('User mail from email','psfbldr'); ?></label>
								    <input type="text" id="user_mail_from_email" class="form-control user_mail_from_email" value="<?php if(isset($user_mail->from_email)) echo $user_mail->from_email; ?>">
								    <p class="help-block"><?php echo __('This is the senders email adress for the email sent to the user','psfbldr'); ?></p>
								  </div>
						  	</div>
						  </div>
						
							
						 	<?php
		      	} else {
							//plugin is not installed
						
						}
			      ?>
						<div class="form-group">
						    <label for="user_mail_reply_to"><?php echo __('User mail reply to address','psfbldr'); ?></label>
						    <input type="text" id="user_mail_reply_to" class="form-control user_mail_reply_to" value="<?php echo $user_mail->reply_to; ?>">
						    <p class="help-block"><?php echo __('Enter one email address that is used as the reply adress when answering the user mail.','psfbldr'); ?></p>
						  </div>
						<?php do_action( 'psfb_edit_after_user_mail_reply_to' ); ?>
						
					</div>
					
				</section>
				<aside class="col-md-3">
					
					<div class="ps_user_mail_variables_stage">
						
					</div>
					
				</aside>
			</div>
		</div>
	</section>
</section>

<?php do_action( 'psfb_edit_after_user_mail' ); ?>




			</div>

			<?php 
				if(defined('PLANSO_FORMS_PRO')){
			?>
			<div class="psfb_pro tab-pane" id="psfb_tab_pro" role="tabpanel">
				
				<?php do_action( 'psfb_edit_pro_settings' ); ?>
			
			</div>
			<?php
				}
			
				//require( dirname(__FILE__).'/settings.php' );
				require( dirname(__FILE__).'/edit-tab-update-content.php' );
		
			?>
								
			<div class="additional_settings tab-pane" id="psfb_tab_additional_settings" role="tabpanel">
			

<section class="container-fluid postbox "><!-- container-fluid -->
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('Additional settings','psfbldr'); ?></strong></span></h3>
				</div>
				<br class="clear">
				
				<section class="col-md-12">
					
					<div class="row">
						<div class="col-md-6">
							
							<div class="form-group checkbox">
						    <label for="ps_link_love">
						    	<input type="checkbox" id="ps_link_love" name="ps_link_love" value="1">
						    	<?php echo __('Get good karma and spread some link love','psfbldr'); ?>
						    </label>
						    <!-- <p class="help-block"><?php echo __('When checked, a powered by link will appear below this form.','psfbldr'); ?></p> -->
						  </div>
						
							<div class="form-group checkbox">
						    <label for="planso_style">
						    	<input type="checkbox" id="planso_style" name="planso_style" value="1">
						    	<?php echo __('Include special Stylesheet based on bootstrap 3.0 if your form does not look good','psfbldr'); ?>
						    </label>
						    <!-- <p class="help-block"><?php echo __('This will add a stylesheet to make your form look as close to the preview as possible.','psfbldr'); ?></p> -->
						  </div>
						
							<div class="form-group checkbox">
						    <label for="clean_attachments">
						    	<input type="checkbox" id="clean_attachments" name="clean_attachments" value="1">
						    	<?php echo __('Do not delete submitted attachments after mailing them','psfbldr'); ?>
						    </label>
						    <!-- <p class="help-block"><?php echo __('If checked all attachments will reside on your server and will not be deleted anymore after they have been attached to the admin email.','psfbldr'); ?></p> -->
						  </div>
						
							<div class="form-group checkbox">
						    <label for="allow_prefill">
						    	<input type="checkbox" id="allow_prefill" name="allow_prefill" value="1">
						    	<?php echo __('Allow fields to be pre populated with a value using $_GET or $_POST','psfbldr'); ?>
						    </label>
						    <!-- <p class="help-block"><?php echo __('If checked all attachments will reside on your server and will not be deleted anymore after they have been attached to the admin email.','psfbldr'); ?></p> -->
						  </div>
						
							<div class="form-group checkbox">
						    <label for="javascript_antispam">
						    	<input type="checkbox" id="javascript_antispam" name="javascript_antispam" value="1">
						    	<?php echo __('Enable special javascript based anti spam protection','psfbldr'); ?>
						    </label>
						    <p class="help-block"><?php echo __('If checked a special hidden field will be appended to your form via javascript. The form will break for users with javascript disabled!','psfbldr'); ?></p>
						  </div>
							
						</div>
						<div class="col-md-6">
							
						<!-- 
							<div class="form-group checkbox">
						    <label for="horizontal_form">
						    	<input type="checkbox" id="horizontal_form" name="horizontal_form" value="1">
						    	<?php echo __('Place labels side by side with fields','psfbldr'); ?>
						    </label>
						    <p class="help-block"><?php echo __('If checked all attachments will reside on your server and will not be deleted anymore after they have been attached to the admin email.','psfbldr'); ?></p>
						  </div>
						  -->
						  <div class="form-group">
						    <label for="horizontal_form">
						    	<?php echo __('Place labels side by side with fields','psfbldr'); ?>
						    </label>
						    <select id="horizontal_form" name="horizontal_form" class="form-control">
						    	<option value="vertical"><?php echo __('Place field labels above fields','psfbldr'); ?></option>
						    	<option value="horizontal"><?php echo __('Place labels side by side with fields (Label width: 16%)','psfbldr'); ?></option>
						    	<option value="horizontal_3"><?php echo __('Place labels side by side with fields (Label width: 25%)','psfbldr'); ?></option>
						    	<option value="horizontal_4"><?php echo __('Place labels side by side with fields (Label width: 33%)','psfbldr'); ?></option>
						    	<option value="horizontal_5"><?php echo __('Place labels side by side with fields (Label width: 41%)','psfbldr'); ?></option>
						    	<option value="horizontal_6"><?php echo __('Place labels side by side with fields (Label width: 50%)','psfbldr'); ?></option>
						    </select>
						    <p class="help-block"><?php echo __('Labels can placed beside the fields according to your theme using different types of widths.','psfbldr'); ?></p>
						  </div>
						  
							<div class="form-group">
						    <label for="psfb_datepicker"><?php echo __('Select the Datepicker that best fits your theme','psfbldr'); ?></label>
					    	<select id="psfb_datepicker" name="psfb_datepicker" class="form-control">
					    		<option value="bootstrap-datetimepicker">Bootstrap DateTimePicker (Eonasdan)</option>
					    		<option value="bootstrap-datepicker-eternicode">Bootstrap Datepicker(Eternicode)</option>
					    		<option value="jquery-ui-datepicker">jQuery UI Datepicker</option>
					    <!--
					    		<option value="bootstrap-datepicker">Bootstrap Datepicker(Eyecon)</option>
					    -->
					    	</select>
						    
						    <p class="help-block"><?php echo __('Depending on your theme you might want to choose a different datepicker for date fields.','psfbldr'); ?></p>
						  </div>
						  
						  
							<div class="form-group">
						    <label for="psfb_date_format">
						    	<?php echo __('Date format','psfbldr'); ?>
						    </label>
						    	<input type="text" id="psfb_date_format" name="psfb_date_format" class="form-control" value="<?php
						    	if( isset($j) && isset($j->date_format) && !empty($j->date_format)){
						    		echo $j->date_format;
						    	} else {
						    		echo get_option('date_format');
						    	}
						    	
						    	?>">
						    	
						    <p class="help-block"><?php echo __('Enter the date format that you want to be used in datepicker fields.','psfbldr'); ?></p>
						  </div>
						  
							
						</div>
					</div>
						
					  
				</section>
			</div>
		</div>
	</section>
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('Default messages','psfbldr'); ?></strong></span></h3>
				</div>
				<br class="clear">
				
				<section class="col-md-12">
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="form_success_message"><?php echo __('Form success message','psfbldr'); ?></label>
					    	<input type="text" id="form_success_message" class="form-control form_success_message" placeholder="<?php echo __('Your submission was successful.','psfbldr'); ?>" value="<?php if(isset($j->form_success_message))echo $j->form_success_message; ?>"> <!--<?php echo $j->form_success_message; ?>-->
					    	<p class="help-block"><?php echo __('This message will be displayed after succesfull submission of the form.','psfbldr'); ?></p>
					  	</div>
							<div class="form-group">
								<label for="form_error_message"><?php echo __('Form error message','psfbldr'); ?></label>
					    	<input type="text" id="form_error_message" class="form-control form_error_message" placeholder="<?php echo __('Attention! There has been an error submitting the form. Please check the marked fields below.','psfbldr'); ?>" value="<?php if(isset($j->form_error_message))echo $j->form_error_message; ?>">
					    	<p class="help-block"><?php echo __('This message will be displayed after there has been an error submitting the form.','psfbldr'); ?></p>
					  	</div>
					  	
					  	<div class="form-group">
								<label for="form_warning_message"><?php echo __('Form warning message','psfbldr'); ?></label>
					    	<input type="text" id="form_warning_message" class="form-control form_warning_message" placeholder="<?php echo __('Warning!','psfbldr'); ?>" value="<?php if(isset($j->form_warning_message))echo $j->form_warning_message; ?>">
					    	<p class="help-block"><?php echo __('This message will be displayed after there has been an warning.','psfbldr'); ?></p>
					  	</div>
							
					  	
					  	<div class="form-group">
								<label for="form_field_required_warning_message"><?php echo __('Form field required warning message','psfbldr'); ?></label>
					    	<input type="text" id="form_field_required_warning_message" class="form-control form_field_required_warning_message" placeholder="<?php echo __('This field is required','psfbldr'); ?>" value="<?php if(isset($j->form_field_required_warning_message))echo $j->form_field_required_warning_message; ?>">
					    	<p class="help-block"><?php echo __('This message will be displayed when required field is empty.','psfbldr'); ?></p>
					  	</div>
					  	
					  	<div class="form-group">
								<label for="form_file_upload_error_message"><?php echo __('File upload error message','psfbldr'); ?></label>
					    	<input type="text" id="form_file_upload_error_message" class="form-control form_file_upload_error_message" placeholder="<?php echo __('File upload error','psfbldr'); ?>" value="<?php if(isset($j->form_file_upload_error_message))echo $j->form_file_upload_error_message; ?>">
					    	<p class="help-block"><?php echo __('This message will be displayed when upload field (Which is required) is empty.','psfbldr'); ?></p>
					  	</div>
					  	<div class="form-group">
								<label for="form_field_invalid_email_message"><?php echo __('Invalid E-mail message','psfbldr'); ?></label>
					    	<input type="text" id="form_field_invalid_email_message" class="form-control form_field_invalid_email_message" placeholder="<?php echo __('Invalid E-Mail','psfbldr'); ?>" value="<?php if(isset($j->form_field_invalid_email_message))echo $j->form_field_invalid_email_message; ?>">
					    	<p class="help-block"><?php echo __('This message will be displayed when email field value is not valid email ID.','psfbldr'); ?></p>
					  	</div>
					  	<div class="form-group">
								<label for="form_field_invalid_url_message"><?php echo __('Invalid URL message','psfbldr'); ?></label>
					    	<input type="text" id="form_field_invalid_url_message" class="form-control form_field_invalid_url_message" placeholder="<?php echo __('Invalid URL','psfbldr'); ?>" value="<?php if(isset($j->form_field_invalid_url_message))echo $j->form_field_invalid_url_message; ?>">
					    	<p class="help-block"><?php echo __('This message will be displayed when URL field value is not valid URL.','psfbldr'); ?></p>
					  	</div>
						</div>
						<div class="col-md-6">
							
							<div class="form-group">
								<label for="style_success_color"><?php echo __('Success message color','psfbldr'); ?></label>
					    	<input type="text" id="style_success_color" class="form-control style_success_color" value="<?php if(isset($j->style_success_color))echo $j->style_success_color; ?>">
					    	<p class="help-block"><?php echo __('Color of success message: i.e. green: <em>#339933</em>','psfbldr'); ?></p>
					  	</div>
						  <div class="form-group">
								<label for="style_error_color"><?php echo __('Error message color','psfbldr'); ?></label>
					    	<input type="text" id="style_error_color" class="form-control style_error_color" value="<?php if(isset($j->style_error_color))echo $j->style_error_color; ?>">
					    	<p class="help-block"><?php echo __('Color of  error message: i.e. red: <em>#DD0000</em>','psfbldr'); ?></p>
					  	</div>
					  	<div class="form-group">
								<label for="style_warning_color"><?php echo __('Warning message color','psfbldr'); ?></label>
					    	<input type="text" id="style_warning_color" class="form-control style_warning_color" value="<?php if(isset($j->style_warning_color))echo $j->style_warning_color; ?>">
					    	<p class="help-block"><?php echo __('Color of  formating warning message: i.e. orange: <em>#DD9900</em>','psfbldr'); ?></p>
					  	</div>
					  	<!--<div class="form-group">
								<label for="style_field_required_warning"><?php echo __('Required field warning message style','psfbldr'); ?></label>
					    	<input type="text" id="style_field_required_warning" class="form-control style_field_required_warning" value="<?php if(isset($j->style_field_required_warning))echo $j->style_field_required_warning; ?>">
					    	<p class="help-block"><?php echo __('CSS style for formating required field warning message: i.e. <em>color:green;border 1px solid green;padding:5px;</em>','psfbldr'); ?></p>
					  	</div>
							<div class="form-group">
								<label for="style_file_upload_error"><?php echo __('File upload error message style','psfbldr'); ?></label>
					    	<input type="text" id="style_file_upload_error" class="form-control style_file_upload_error" value="<?php if(isset($j->style_file_upload_error))echo $j->style_file_upload_error; ?>">
					    	<p class="help-block"><?php echo __('CSS style for formating file upload error message: i.e. <em>color:green;border 1px solid green;padding:5px;</em>','psfbldr'); ?></p>
					  	</div>
					  	<div class="form-group">
								<label for="style_invalid_email"><?php echo __('Invalid email message style','psfbldr'); ?></label>
					    	<input type="text" id="style_invalid_email" class="form-control style_invalid_email" value="<?php if(isset($j->style_invalid_email))echo $j->style_invalid_email; ?>">
					    	<p class="help-block"><?php echo __('CSS style for formating invalid email message: i.e. <em>color:green;border 1px solid green;padding:5px;</em>','psfbldr'); ?></p>
					  	</div>
					  	<div class="form-group">
								<label for="style_invalid_url"><?php echo __('Invalid URL message style','psfbldr'); ?></label>
					    	<input type="text" id="style_invalid_url" class="form-control style_invalid_url" value="<?php if(isset($j->style_invalid_url))echo $j->style_invalid_url; ?>">
					    	<p class="help-block"><?php echo __('CSS style for formating invalid URL message: i.e. <em>color:green;border 1px solid green;padding:5px;</em>','psfbldr'); ?></p>
					  	</div>-->
						</div>
					</div>
						
					  
				</section>
			</div>
		</div>
	</section>
</section>

<?php do_action( 'psfb_edit_after_additional_settings' ); ?>


			</div>
			<?php do_action( 'psfb_edit_tabs_content' ); ?>
		</div><!-- tab-content -->
	</div><!-- tabpanel -->
<button class="psfb_trigger_all_events" style="display:none;"></button>
<form method="post" class="psfb_submit_form" action="<?php echo esc_url( add_query_arg( array( 'psfbid' => $post_id ), menu_page_url( 'ps-form-builder', false ) ) ); ?>">
<input type="hidden" name="action" value="save"/>
<div class="form-group" style="display:none;">
  <label><?php echo __('Form HTML','psfbldr'); ?></label>
  <textarea id="psfb_html" class="form-control"></textarea>
  <textarea id="psfb_title" name="title" class="form-control"></textarea>
  
</div>
<div class="form-group" style="display:none;">
  <label><?php echo __('JSON','psfbldr'); ?></label>
  <textarea id="psfb_json" name="json" class="form-control"><?php if(isset($psform) && isset($psform->post_content)){echo $psform->post_content;}?></textarea>
  
  <button class="psfb_test_form_submit btn btn-default" type="button"><?php echo __('Test form submission','psfbldr'); ?></button>
  <button class="psfb_generate_json btn btn-default" type="button" ><?php echo __('Generate','psfbldr'); ?></button>
</div>
<div class="form-group">
	<div class="btn-group" role="group" style="float:right;">
		<button type="button" class="psfb_open_help_modal btn btn-default" title="<?php echo __('Help','psfbldr'); ?>"><span class="fa fa-life-saver"></span></button>
		<button class="psfb_save_html btn btn-primary" style="float:right;"><?php echo __('Save','psfbldr'); ?></button>
	</div>
  <div style="clear:both;"></div>
</div>
</form>

<div style="clear:both;"></div>
</div><!-- wrap -->
<div style="clear:both;"></div>
<!--/email_off-->