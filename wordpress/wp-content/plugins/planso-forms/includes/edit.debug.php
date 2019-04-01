<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) )
	die( '-1' );

require_once( dirname(__FILE__).'/vars.inc.php' );


?><div class="wrap">
	<h2><?php
	
	if ( !isset($_REQUEST['psfbid']) || empty($_REQUEST['psfbid']) || $_REQUEST['psfbid'] == -1 ) {
		echo esc_html( __( 'Add New Form', 'psfbldr' ) ).' '.__('Debug mode','psfbldr');
		$post_id = -1;
		$shortcode_out = '';
	} else {
		echo esc_html( __( 'Edit Form', 'psfbldr' ) ).' '.__('Debug mode','psfbldr');

		echo ' <a href="' . esc_url( menu_page_url( 'ps-form-builder-new', false ) ) . '" class="add-new-h2">' . esc_html( __( 'Add New', 'psfbldr' ) ) . '</a>';
		$post_id = $_REQUEST['psfbid'];
		$psform = get_post( $post_id);//, $output, $filter );
		
		
		$shortcode_out = '<div><input type="text" onfocus="this.select();" onmouseup="return false;" readonly="readonly" value="[psfb id=&quot;'.$post_id.'&quot; title=&quot;'.$psform->post_title.'&quot;]" class="shortcode-in-list-table wp-ui-text-highlight code form-control"></div>';
	}
	echo ' <a href="' . esc_url( menu_page_url( 'ps-form-builder', false ) ) . '" class="add-new-h2">' . esc_html( __( 'Back to forms', 'psfbldr' ) ) . '</a>';
	
?></h2>




<form method="post" class="psfb_submit_form" action="<?php echo esc_url( add_query_arg( array( 'psfbid' => $post_id ), menu_page_url( 'ps-form-builder', false ) ) ); ?>">
<input type="hidden" name="action" value="save"/>
<div class="form-group">
  <label><?php echo __('Title','psfbldr'); ?></label>
  <input type="text" id="psfb_title" name="title" class="form-control" value="<?php echo $psform->post_title;?>"/>
</div>
<div class="form-group">
  <label><?php echo __('JSON','psfbldr'); ?></label>
  <textarea id="psfb_json" name="json" class="form-control" rows="15"><?php echo $psform->post_content;?></textarea>
</div>
<div class="form-group">
  <button class="psfb_save_html btn btn-primary" style="float:right;"><?php echo __('Save','psfbldr'); ?></button>
  <div style="clear:both;"></div>
</div>
</form>

<div style="clear:both;"></div>
</div><!-- wrap -->
<div style="clear:both;"></div>