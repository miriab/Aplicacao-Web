<?php
$sort_column    = empty( $_REQUEST['orderby'] ) ? 'ID' : $_REQUEST['orderby'];
if ( ! in_array( ( $sort_column ), array( 'ID', 'title', 'date', 'author' ) ) ) {
  $sort_column = 'ID';
}
$sort_ad    = empty( $_REQUEST['order'] ) ? 'DESC' : strtoupper($_REQUEST['order']);
if ( ! in_array( strtoupper( $sort_ad ), array( 'ASC', 'DESC' ) ) ) {
  $sort_ad = 'DESC';
}
if($sort_ad=='ASC'){
	$new_sort = 'desc';
} else {
	$new_sort = 'asc';
}
$page_limit = empty( $_REQUEST['rows'] ) ? 100 : $_REQUEST['rows'];
if(!is_numeric( $page_limit )){
	$page_limit = 100;
}
?>
<style type="text/css">
	body{background-color:inherit;}
</style>
<div class="wrap">
	<h2>
		<?php
			echo esc_html( __( 'PlanSo Forms', 'psfbldr' ) );
			echo ' <a href="' . esc_url( menu_page_url( 'ps-form-builder-new', false ) ) . '" class="add-new-h2">' . esc_html( __( 'Add New', 'psfbldr' ) ) . '</a>';
		?>
	</h2>
<table class="wp-list-table widefat fixed posts">
	<thead>
	<tr>
		<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
			<label class="screen-reader-text" for="cb-select-all-1"><?php echo esc_html( __( 'Select all', 'psfbldr' ) ); ?></label>
			<input id="cb-select-all-1" type="checkbox">
		</th>
		<th scope="col" id="title" class="manage-column column-title sortable <?php echo strtolower($new_sort); ?>" style="">
			<a href="?page=ps-form-builder&amp;orderby=title&amp;order=<?php echo $new_sort; ?>">
				<span><?php echo esc_html( __( 'Title', 'psfbldr' ) ); ?></span>
				<span class="sorting-indicator"></span>
			</a>
		</th>
		<th scope="col" id="shortcode" class="manage-column column-shortcode" style=""><?php echo esc_html( __( 'Shortcode', 'psfbldr' ) ); ?></th>
		<th scope="col" id="author" class="manage-column column-author sortable <?php echo strtolower($new_sort); ?>" style="">
			<a href="?page=ps-form-builder&amp;orderby=author&amp;order=<?php echo $new_sort; ?>">
				<span><?php echo esc_html( __( 'Author', 'psfbldr' ) ); ?></span>
				<span class="sorting-indicator"></span>
			</a>
		</th>
		<th scope="col" id="date" class="manage-column column-date sortable <?php echo strtolower($new_sort); ?>" style="">
			<a href="?page=ps-form-builder&amp;orderby=date&amp;order=<?php echo $new_sort; ?>">
				<span><?php echo esc_html( __( 'Date', 'psfbldr' ) ); ?></span>
				<span class="sorting-indicator"></span>
			</a>
		</th>
	</tr>
	</thead>

	<tfoot>
	<tr>
		<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
			<label class="screen-reader-text" for="cb-select-all-1"><?php echo esc_html( __( 'Select all', 'psfbldr' ) ); ?></label>
			<input id="cb-select-all-1" type="checkbox">
		</th>
		<th scope="col" id="title" class="manage-column column-title sortable <?php echo strtolower($new_sort); ?>" style="">
			<a href="?page=ps-form-builder&amp;orderby=title&amp;order=<?php echo $new_sort; ?>">
				<span><?php echo esc_html( __( 'Title', 'psfbldr' ) ); ?></span>
				<span class="sorting-indicator"></span>
			</a>
		</th>
		<th scope="col" id="shortcode" class="manage-column column-shortcode" style=""><?php echo esc_html( __( 'Shortcode', 'psfbldr' ) ); ?></th>
		<th scope="col" id="author" class="manage-column column-author sortable <?php echo strtolower($new_sort); ?>" style="">
			<a href="?page=ps-form-builder&amp;orderby=author&amp;order=<?php echo $new_sort; ?>">
				<span><?php echo esc_html( __( 'Author', 'psfbldr' ) ); ?></span>
				<span class="sorting-indicator"></span>
			</a>
		</th>
		<th scope="col" id="date" class="manage-column column-date sortable <?php echo strtolower($new_sort); ?>" style="">
			<a href="?page=ps-form-builder&amp;orderby=date&amp;order=<?php echo $new_sort; ?>">
				<span><?php echo esc_html( __( 'Date', 'psfbldr' ) ); ?></span>
				<span class="sorting-indicator"></span>
			</a>
		</th>
	</tr>
	</tfoot>
	
	<tbody id="the-list" data-wp-lists="list:post">
	
<?php
 

$r = query_posts( 'post_type=psfb&posts_per_page='.$page_limit.'&order='.$sort_ad.'&orderby='.$sort_column);
if($r && count($r)>0){
	foreach($r as $row){
		
		//UPDATE POST STATUS IF NECCESSARY
		if($row->post_status!='draft'){
			$post_id = wp_update_post(
				array(
					'ID' => (int) $row->ID,
					'post_status' => 'draft',
					'ping_status' => 'closed',
					'comment_status' => 'closed' 
				) 
			);
		}
		
		?>
		<tr class="alternate">
			<th scope="row" class="check-column">
				<input type="checkbox" name="post[]" value="1981">
			</th>
			<td class="title column-title">
				<strong>
					<a class="row-title" href="<?php echo esc_url( add_query_arg( array( 'psfbid' => $row->ID ), menu_page_url( 'ps-form-builder-new', false ) ) ); ?>" title="<?php echo __('Edit','psfbldr').' '.$row->post_title.''; ?>">
						<?php 
					if(isset($row->post_title) && !empty($row->post_title)){
						echo $row->post_title; 
					} else {
						echo __('Unnamed form','psfbldr');
					}
				?> 
					</a>
				</strong>
				<div class="row-actions">
					<?php do_action('psfb_list_entry_actions_before',$row); ?>
					<span class="edit">
						<a href="<?php echo esc_url( add_query_arg( array( 'psfbid' => $row->ID ), menu_page_url( 'ps-form-builder-new', false ) ) ); ?>"><?php echo __('Edit','psfbldr'); ?></a>
						 | 
					</span>
					<span class="copy">
						<a href="<?php echo esc_url( add_query_arg( array( 'psfbid' => $row->ID,'action' => 'copy' ), menu_page_url( 'ps-form-builder', false ) ) ); ?>"><?php echo __('Copy','psfbldr'); ?></a>
						 | 
					</span>
					<span class="delete">
						<a href="<?php echo esc_url( add_query_arg( array( 'psfbid' => $row->ID,'action' => 'delete' ), menu_page_url( 'ps-form-builder', false ) ) ); ?>" onclick="if(confirm('<?php echo __('Are you sure you want to delete this form?','psfbldr'); ?>')){return true;}else{return false;}"><?php echo __('Delete','psfbldr'); ?></a>
					</span>
					<?php do_action('psfb_list_entry_actions_after',$row); ?>
				</div>
			</td>
			<td class="shortcode column-shortcode">
				<input type="text" onfocus="this.select();" onmouseup="return false;" readonly="readonly" value="[psfb id=&quot;<?php echo $row->ID; ?>&quot; title=&quot;<?php echo $row->post_title; ?>&quot;]" class="shortcode-in-list-table wp-ui-text-highlight code form-control" style="cursor:text;color:#32373c;">
			</td>
			<td class="author column-author"><?php
				
				$user = get_user_by( 'id', $row->post_author );
				echo  $user->first_name . ' ' . $user->last_name;
			?>	
			</td>
			<td class="date column-date">
				<abbr title="<?php echo date_i18n( get_option( 'date_format' ), strtotime( $row->post_date ) ) .' '. strftime('%H:%M:%S',strtotime( $row->post_date ) ); ?>">
					<!-- 20.10.2014 -->
					<?php
						 echo date_i18n( get_option( 'date_format' ), strtotime( $row->post_date ) );
					?>
				</abbr>
			</td>
		</tr>
<?php
	}
}
?>
		</tbody>
</table>


</div>