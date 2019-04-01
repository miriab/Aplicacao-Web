<?php
/**
 * Plugin Name: PlanSo Forms
 * Plugin URI: http://forms.planso.de/
 * Description: Build forms and manage forms with the PlanSo Form Builder forms management plugin. PlanSo Form Builder makes it easy to create professional forms with drag and drop and all forms can be customnized in an easy and streamlined way.
 * Version: 2.6.2
 * Author: PlanSo.de
 * Author URI: http://forms.planso.de/
 * Text Domain: psfbldr
 * Domain Path: /locale/
 * License: GPL2
 */
/*  Copyright 2015  Stephan Helbig  (email : tech@planso.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

load_plugin_textdomain( 'psfbldr', false, dirname( plugin_basename( __FILE__ ) ).'/locale' );

if ( ! class_exists( 'Recursive_ArrayAccess' ) ) {
	require_once( dirname(__FILE__).'/libs/wp-session-manager/class-recursive-arrayaccess.php' );
}

// Only include the functionality if it's not pre-defined.
if ( ! class_exists( 'WP_Session' ) ) {
	
	// let users change the session cookie name
	if( ! defined( 'WP_SESSION_COOKIE' ) )
		define( 'WP_SESSION_COOKIE', '_wp_session' );
	
	require_once( dirname(__FILE__).'/libs/wp-session-manager/class-wp-session.php' );
	require_once( dirname(__FILE__).'/libs/wp-session-manager/wp-session.php' );
} else {
	if(!function_exists('wp_session_id')){
			
		/**
		 * Return current session id
		 *
		 * @return string
		 */
		function wp_session_id() {
			if ( isset( $_COOKIE[WP_SESSION_COOKIE] ) ) {
				$cookie = stripslashes( $_COOKIE[WP_SESSION_COOKIE] );
				$cookie_crumbs = explode( '||', $cookie );
				return $cookie_crumbs[0];
			}
		}
	}
}
$wp_session = WP_Session::get_instance();

function ps_echo_form($atts, $content= ''){
	if(isset($atts['id']) && is_numeric($atts['id'])){
		if(!isset($atts['title']) || empty($atts['title'])){
			$atts['title'] = __('Unnamed','psfbldr');
		}
		$psform = get_post( $atts['id'] );
		
		$j = json_decode($psform->post_content);
		
		$out = require(dirname(__FILE__).'/includes/form.php');
		
		return $out;
	}
	return;
}

add_shortcode( 'psfb', 'ps_echo_form'  );


add_filter('widget_text', 'do_shortcode'); 

// Original Referrer 
function psfb_set_session_values() 
{
	
	

	global $wp_session;
/*
	if (!session_id()) 
	{
		session_start();
	}
*/

	if (!isset($wp_session['OriginalRef'])) 
	{
		if(isset($wp_session['HTTP_REFERER'])){
			$wp_session['OriginalRef'] = $wp_session['HTTP_REFERER']; 
		} else {
			$wp_session['OriginalRef'] = 'Direct';
		}
	}

	if (!isset($wp_session['LandingPage'])) 
	{
		$wp_session['LandingPage'] = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]; 
	}
	if(isset($wp_session['psfb_track_successfull_submission'])){
		$wp_session['psfb_track_successfull_submission'] = null;
		add_action('wp_footer', 'psfb_track_successfull_submission');
	}
	wp_session_commit();
}
add_action('init', 'psfb_set_session_values');

function psfb_track_successfull_submission(){
	global $wp_session;
?>
<script type="text/javascript">
var _gaq = _gaq || [];_gaq.push(['_trackEvent', 'PlanSo Forms', '<?php echo $wp_session['psfb_track_successfull_submission_form_label']; ?>', '<?php echo $wp_session['psfb_track_successfull_submission_permalink']; ?>']);
try{ga('send', 'event', 'PlanSo Forms', '<?php echo $wp_session['psfb_track_successfull_submission_form_label']; ?>', '<?php echo $wp_session['psfb_track_successfull_submission_form_id']; ?>', {'page': '<?php echo $wp_session['psfb_track_successfull_submission_permalink']; ?>'});}catch(e){console.log('analytics.js not loaded');}
</script>
<?php
	$wp_session['psfb_track_successfull_submission_form_id'] = null;
	$wp_session['psfb_track_successfull_submission_permalink'] = null;
}

/** Register Admin Menu */
add_action( 'admin_menu', 'ps_form_builder_admin_menu' );

/** Hook Admin Menu */
function ps_form_builder_admin_menu() {
	/*
	add_menu_page( 
		$page_title, 
		$menu_title, 
		$capability, 
		$menu_slug, 
		$function, 
		$icon_url,
		$position 
	);
	*/
	$edit = add_menu_page( 
		__('PlanSo Form Builder','psfbldr'), 
		__('PlanSo Form Builder','psfbldr'), 
		'manage_options', 
		'ps-form-builder', 
		'ps_form_builder_list',
		plugins_url( '/images/planso-logo-gears-transparent-20x20.png', (__FILE__) ),
		'65.87255991543'
	);
	add_submenu_page( 'ps-form-builder', __('Create new PlanSo Form','psfbldr'), __('New Form','psfbldr'), 'manage_options', 'ps-form-builder-new', 'ps_form_builder_options');
	do_action( 'psfb_admin_menu' );
	add_action( 'load-' . $edit, 'psfb_load_contact_form_admin' );
}


add_action('admin_init', 'psfb_register');

 
function psfb_register() {
    $args = array(
        'label' => __('PlanSo Forms'),
        'singular_label' => __('PlanSo Form'),
        'public' => false,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => true,
        'supports' => array('title', 'editor')
    );
 
    register_post_type( 'psfb' , $args );
}

function psfb_current_action() {
	if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] )
		return $_REQUEST['action'];

	if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] )
		return $_REQUEST['action2'];

	return false;
}

add_action( 'admin_notices', 'psfb_leads_promotion_admin_updated_message' );
function psfb_leads_promotion_admin_updated_message() {
	
	$psfb_leads_promo_notice_dismiss = get_option( 'psfb_leads_promo_notice_dismiss', false);
	if(!$psfb_leads_promo_notice_dismiss && !is_plugin_active( 'planso-leads/planso-leads.php' )){
		
		$bloginfo = get_bloginfo('language');
		
		if($bloginfo == 'de-DE'){
			$target_url = 'http://www.planso.de/planso-leads/';	
		}else{
			$target_url = 'http://www.planso.net/planso-leads/';	
		}
		
		?>
			<div id="psfb_leads_promo_message" class="updated psfb_leads_promo_message">
				<p><?php echo __( 'Do you like PlanSo Forms?  Then you should check out the brand new PlanSo Leads: it will help you capture more leads and increase your conversions.', 'psfbldr' )?>
					<a href="<?php echo wp_nonce_url(
						    add_query_arg(
						        array(
						            'action' => 'install-plugin',
						            'plugin' => 'planso-leads'
						        ),
						        admin_url( 'update.php' )
						    ),
						    'install-plugin_planso-leads'
						); ?>" target="_blank" class="btn btn-success btn-xs"><?php echo __('Install PlanSo Leads','psfbldr')?></a> 
				</p>
				<p>
					<strong>
						<a href="#" class="psfb_leads_promo_ignore_notice"><?php echo __('Dismiss this notice','psfbldr') ?></a> | <a href="<?php echo $target_url; ?>" target="_blank"><?php echo __('Visit PlanSo Leads','psfbldr')?></a>
					</strong>
				</p>
			</div>
			<script type="text/javascript">
			jQuery(document).ready(function($){
				$('.psfb_leads_promo_ignore_notice').click(function(){
					var dat = {
						action:'psfb_update_leads_promo_notice_dismiss',
					};
					var me = $(this);
					$.ajax({
						url:ajaxurl,
						data:dat,
						type:'post',
						dataType:'json',
						success:function(r){
							me.closest('.psfb_leads_promo_message').remove();
						},
						error:function(o,r){
							alert('Could not store dismissal');
						}
					});
				});
				
			});
			</script>
			
		<?php
		
	}
	return;
}


function psfb_update_leads_promo_notice_dismiss(){
	update_option('psfb_leads_promo_notice_dismiss',true);
	echo json_encode(array('success'=>true));
	wp_die();
}
add_action( 'wp_ajax_psfb_update_leads_promo_notice_dismiss', 'psfb_update_leads_promo_notice_dismiss' );


add_action( 'psfb_admin_notices', 'psfb_admin_updated_message' );
function psfb_admin_updated_message() {
	
	if ( empty( $_REQUEST['message'] ) )
		return;

	if ( 'created' == $_REQUEST['message'] )
		$updated_message = esc_html( __( 'PlanSo form created.', 'psfbldr' ) );
	elseif ( 'saved' == $_REQUEST['message'] )
		$updated_message = esc_html( __( 'PlanSo form saved.', 'psfbldr' ) );
	elseif ( 'deleted' == $_REQUEST['message'] )
		$updated_message = esc_html( __( 'PlanSo form deleted.', 'psfbldr' ) );

	if ( empty( $updated_message ) )
		return;

?>
<div id="message" class="updated"><p><?php echo $updated_message; ?></p></div>
<?php
}


function psfb_submit_form(){
	if(!isset($_POST['psfb_form_submit'])){
		return;
	}
	
	require( dirname(__FILE__).'/includes/submit.php' );
	
}
add_action('init', 'psfb_submit_form');

function psfb_submit_test_values(){
	if(!isset($_POST['psfb_form_submit_test_values']) || $_POST['psfb_form_submit_test_values']!='doit'){
		return;
	}
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	//print_r($_POST['psfb_test_json']);
	$submitted_j = json_decode(stripslashes($_POST['psfb_test_json']));
	
	require_once(dirname(__FILE__).'/includes/vars.inc.php');
	
	if(isset($submitted_j) && isset($submitted_j->fields)){
		foreach($submitted_j->fields as $row){
			foreach($row as $field){
				if( isset($fieldtypes[$field->type]) ){
					if(!isset($field->name)){
						$field->name = preg_replace("/[^A-Za-z0-9_]+/", '_', $field->label);
					}
					
					if(isset($fieldtypes[$field->type]['testvalue'])){
						if($fieldtypes[$field->type]['testvalue'] == 'select_options'){
							if($fieldtypes[$field->type]['multiple']==true){
								$_POST[$field->name][] = $field->select_options[0]->val;
							} else {
								$_POST[$field->name] = $field->select_options[0]->val;
							}
						} else if($fieldtypes[$field->type]['testvalue'] == 'field_options'){
							if($fieldtypes[$field->type]['multiple']==true){
								$_POST[$field->name][] = $field->field_options[0]->val;
							} else {
								$_POST[$field->name] = $field->field_options[0]->val;
							}
						} else {
							if($fieldtypes[$field->type]['multiple']==true){
								$_POST[$field->name][] = $fieldtypes[$field->type]['testvalue'];
							} else {
								$_POST[$field->name] = $fieldtypes[$field->type]['testvalue'];
							}
						}
					}
				}
			}
		}
	}
	print_r($_POST);
	require( dirname(__FILE__).'/includes/submit.php' );
	exit();
}
add_action( 'wp_ajax_psfb_form_submit_test', 'psfb_submit_test_values' );




function psfb_edit_update_smtp_notice_dismiss(){
	update_option('psfb_smtp_notice_dismiss',true);
	echo json_encode(array('success'=>true));
	wp_die();
}
add_action( 'wp_ajax_psfb_edit_update_smtp_notice_dismiss', 'psfb_edit_update_smtp_notice_dismiss' );



add_action( 'wp_ajax_psfb_update_get_package_details', 'psfb_update_get_package_details' );
if(!function_exists('psfb_update_get_package_details')){
	function psfb_update_get_package_details() {
		require( dirname(__FILE__).'/includes/check_license.php' );
		wp_die();
	}
}
add_action( 'wp_ajax_psfb_update_plugin', 'psfb_update_plugin' );
if(!function_exists('psfb_update_plugin')){
	function psfb_update_plugin(){
		require( dirname(__FILE__).'/includes/plugin_update.php' );
		
		wp_die();
	}
}
add_action( 'wp_ajax_psfb_install_plugin', 'psfb_install_plugin' );
if(!function_exists('psfb_install_plugin')){
	function psfb_install_plugin(){
		require( dirname(__FILE__).'/includes/plugin_install.php' );
		wp_die();
	}
}
add_action( 'wp_ajax_psfb_update_revoke_license', 'psfb_update_revoke_license' );
if(!function_exists('psfb_update_revoke_license')){
	function psfb_update_revoke_license() {
		require( dirname(__FILE__).'/includes/revoke_license.php' );
		wp_die();
	}
}

function psfb_plugin_update_rmdirs($dir) {
	if(!$dir)return false;
	if($dir == dirname(__FILE__))return false;
	if($dir == dirname(dirname(dirname(__FILE__))))return false;//dir ist plugins/
	if(empty($dir))return false;	
	if (!is_dir($dir) || is_link($dir)) return unlink($dir);
	foreach (scandir($dir) as $file) {
		if ($file == '.' || $file == '..') continue;
		if (!psfb_plugin_update_rmdirs($dir . '/' . $file)) {
			chmod($dir . '/' . $file, 0777);
			if (!psfb_plugin_update_rmdirs($dir . '/' . $file)) return false;
		}
	}
	return rmdir($dir);
}


/*
function psfb_plugin_update_get_headers_from_curl_response($res){
  $headers = array();
  $header_text = substr($res, 0, strpos($res, "\r\n\r\n"));
  foreach (explode("\r\n", $header_text) as $i => $line){
    if($i === 0){
      $headers['http_code'] = $line;
    } else{
      list ($key, $value) = explode(': ', $line);
      $headers[$key] = $value;
    }
  }
  return $headers;
}*/

if ( ! wp_next_scheduled( 'psfb_plugin_update_check_domain' ) ) {
  wp_schedule_event( time(), 'daily', 'psfb_plugin_update_check_domain' );
}

add_action( 'psfb_plugin_update_check_domain', 'psfb_plugin_update_check_domain_function' );
if(!function_exists('psfb_plugin_update_check_domain_function')){
	function psfb_plugin_update_check_domain_function() {
		require( dirname(__FILE__).'/includes/check_domain.php' );
	}
}

function psfb_save_form(){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	$id = $_REQUEST['psfbid'];
	$title = $_REQUEST['title'];
	$post_content = $_REQUEST['json'];
	
	if( substr(trim($post_content),0,1)=='{'){
		//is json
	} else {
		$post_content = base64_decode(trim($post_content));
	}
	
	$filtered_contents = apply_filters('psfb_before_save_to_db',array('id'=>$id,'title'=>$title,'post_content'=>$post_content));
	
	$id = $filtered_contents['id'];
	$title = $filtered_contents['title'];
	$post_content = $filtered_contents['post_content'];
	
	if ( !isset($id) || empty($id) || $id == -1 ) {
		$id = wp_insert_post( array(
			'post_type' => 'psfb',
			'post_status' => 'draft',
			'ping_status' => 'closed',
			'comment_status' => 'closed',
			'post_title' => $title,
			'post_content' => trim( $post_content ) ) );
	} else {
		$post_id = wp_update_post( array(
			'ID' => (int) $id,
			'post_status' => 'draft',
			'ping_status' => 'closed',
			'comment_status' => 'closed',
			'post_title' => $title,
			'post_content' => trim( $post_content ) ) );
	}
	$msg = 'saved';
	if(empty($_POST['psfbid']) || $_POST['psfbid'] == -1){
		$msg = 'created';
	}
	
	$query = array(
		'message' => $msg,
		'psfbid' => $id );

	$redirect_to = add_query_arg( $query, menu_page_url( 'ps-form-builder-new', false ) );
	wp_safe_redirect( $redirect_to );
	exit();
}

function psfb_load_contact_form_admin(){
	global $plugin_page;

	$action = psfb_current_action();
	
	if ( 'save' == $action ) {
		psfb_save_form();
		
		exit();
	}
	if ( 'copy' == $action ) {
		
		$psform = get_post($_REQUEST['psfbid']);
		$j = json_decode($psform->post_content);
		$psform->post_content = addslashes(json_encode($j));
		$id = wp_insert_post(
			array(
				'post_type' => 'psfb',
				'post_status' => 'publish',
				'post_title' => __('[Copy]','psfbldr').' '.$psform->post_title,
				'post_content' => trim( $psform->post_content ) 
			) 
		);
	
	
		$query = array(
			'message' => 'created',
			'psfbid' => $id 
		);
		
		$redirect_to = add_query_arg( $query, menu_page_url( 'ps-form-builder', false ) );
		wp_safe_redirect( $redirect_to );
		exit();
	}
	
	if ( 'delete' == $action ) {
		
		wp_delete_post( $_REQUEST['psfbid'], true );
		$query = array();
		$redirect_to = add_query_arg( $query, menu_page_url( 'ps-form-builder', false ) );
		wp_safe_redirect( $redirect_to );
		exit();
	}
}

function ps_form_builder_enqueue($hook) {
	//echo('<h1>'.$hook.'</h1>');
  if ( !strstr($hook,'ps-form-builder')) {
      return;
  }
	wp_register_style( 'font-awesome',plugins_url( '/css/font-awesome-4.3.0/css/font-awesome.min.css', (__FILE__) ) ,array() ,'4.2.0');
	wp_enqueue_style( 'font-awesome');
	wp_enqueue_style( 'bootstrap',plugins_url( '/css/bootstrap/full/bootstrap.min.css', (__FILE__) ) );
	wp_enqueue_style( 'bootstrap-theme',plugins_url( '/css/bootstrap/full/bootstrap-theme.min.css', (__FILE__) ) );
	wp_enqueue_style( 'font-awesome-icon-picker',plugins_url( '/js/font-awesome-icon-picker/css/fontawesome-iconpicker.min.css', (__FILE__) ) );
	
	//wp_enqueue_style( 'jquery-ui-css',plugins_url( '/css/jquery-ui.min.css', (__FILE__) ) ,array() ,'v1.11.2');
	//wp_enqueue_style( 'jquery-ui');
	
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	
	
	wp_register_script( 'bootstrap-tooltip',plugins_url( '/js/bootstrap/src/tooltip.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	wp_register_script( 'bootstrap-modal',plugins_url( '/js/bootstrap/src/modal.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	wp_register_script( 'bootstrap-collapse',plugins_url( '/js/bootstrap/src/collapse.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	wp_register_script( 'bootstrap-popover',plugins_url( '/js/bootstrap/src/popover.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	wp_register_script( 'bootstrap-tab',plugins_url( '/js/bootstrap/src/tab.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	wp_register_script( 'bootstrap-transition',plugins_url( '/js/bootstrap/src/transition.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	wp_register_script( 'bootstrap-dropdown',plugins_url( '/js/bootstrap/src/dropdown.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	wp_register_script( 'font-awesome-icon-picker',plugins_url( '/js/font-awesome-icon-picker/js/fontawesome-iconpicker.min.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	
	wp_register_script( 'jquery-csv-parsing',plugins_url( '/js/jquery.csv-0.71.min.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	//wp_register_script( 'jquery-ui',plugins_url( '/js/jquery-ui.min.js', (__FILE__) ), array('jquery'), '1.11.4', true );
	
	wp_enqueue_style('wp-jquery-ui-dialog');
	//wp_enqueue_script('jquery-ui-core', array('jquery'));
	wp_enqueue_script('jquery-ui-dialog', array('jquery-ui-dialog'));
	
	wp_enqueue_script( 'bootstrap-tooltip' );
	wp_enqueue_script( 'bootstrap-modal' );
	wp_enqueue_script( 'bootstrap-collapse' );
	wp_enqueue_script( 'bootstrap-popover' );
	wp_enqueue_script( 'bootstrap-tab' );
	wp_enqueue_script( 'bootstrap-transition' );
	wp_enqueue_script( 'bootstrap-dropdown' );
	wp_enqueue_script( 'font-awesome-icon-picker' );
	
	
	wp_enqueue_script( 'jquery-csv-parsing' );
	//wp_enqueue_script( 'jquery-ui' );
		
}
add_action( 'admin_enqueue_scripts', 'ps_form_builder_enqueue' );





function ps_form_builder_list() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	require_once(dirname(__FILE__).'/includes/list.php');
//	exit();
}
/** Generate Admin Page */
function ps_form_builder_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	if(isset($_REQUEST['psfb_debug']) && $_REQUEST['psfb_debug']==1){
		require_once(dirname(__FILE__).'/includes/edit.debug.php');
	} else {
		require_once(dirname(__FILE__).'/includes/edit.php');
	}
}







// add new buttons
add_filter('mce_buttons', 'psfb_register_buttons');
function psfb_register_buttons($buttons) {
	array_push($buttons, 'separator', 'planso_forms_shortcodes');
	return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
add_filter('mce_external_plugins', 'psfb_register_tinymce_javascript');

function psfb_register_tinymce_javascript($plugin_array) {
	psfb_add_tinymce_button();
	$plugin_array['planso_forms_shortcodes'] = plugins_url('/js/mce-button.js',__FILE__);
	return $plugin_array;
}

/*
?>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('.psfb_leads_promo_ignore_notice').click(function(){
		var dat = {
			action:'psfb_update_leads_promo_notice_dismiss',
		};
		var me = $(this);
		$.ajax({
			url:ajaxurl,
			data:dat,
			type:'post',
			dataType:'json',
			success:function(r){
				me.closest('.psfb_leads_promo_message').remove();
			},
			error:function(o,r){
				alert('Could not store dismissal');
			}
		});
	});
	
});
</script>
<?php
*/

function psfb_add_tinymce_button(){
	
	$out = '<script type="text/javascript">';
	$out .= '/* <![CDATA[ */';
	$out .= 'var planso = {};planso.forms = {};';
	
	$out .= 'planso.forms.shortcodes = [';
	
	$shortcodes = query_posts( 'post_type=psfb&posts_per_page=20');
	$fcnt = 0;
	foreach($shortcodes as $row){
		if($fcnt > 0)$out .= ',';
		$id = $row->ID;
		if(isset($row->post_title) && !empty($row->post_title)){
			$title = $row->post_title; 
		} else {
			$title = __('Unnamed form','psfbldr');
		}
		
		$out .= '{';
		$out .= "text:'".$title."',onclick:function(){planso.forms.editor.insertContent( '[psfb id=\"".$id."\" title=\"".$title."\"]');}";
		$out .= '}';
		$fcnt ++;
	}
	
	$out .= '];';
	$out .= '/* ]]> */';
	$out .= '</script>';
	$out .= '<style type="text/css">.mce-i-planso-gears-icon{background-image:url(\''.plugins_url( 'images/planso-logo-gears-transparent-72x72.png', (__FILE__)).'\');}</style>';
	echo $out;
}
