<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://johnodagostino.com
 * @since      1.0.0
 *
 * @package    dagos-letters
 * @subpackage dagos-letters/admin/partials
 */

/* #1: ALLOW APPROVED
---------------------------------*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}


/* #2: QUERY LETTERS
---------------------------------*/
global $wpdb;
$table = $wpdb->prefix . 'dagos_letters';

$query = array();
$query[] = "SELECT *";
$query[] = "FROM wp_dagos_letters";
/*
//approved
if ( isset($_GET['approve']) ){
	$_GET['approve'] === '-1' ? '' : $query[] = "AND letter_approved='" . (string)$_GET['approve'] . "'";
}

//filter
if ( isset($_GET['filter']) ){
	$_GET['filter'] === '-1' ? '' : $query[] = "AND letter_type='" . (string)$_GET['filter'] . "'";
}*/

//pagination
$page = 1;
if ( isset($_GET['paged']) ){
	$page = (int)$_GET['paged'];
	$query[] = "LIMIT 20 OFFSET " . ( ( $page - 1 ) * 20 ) . "";
}
else {
	$query[] = "LIMIT 20";
}

$results = $wpdb->get_results( implode( " ", $query ) );

/* #3: VARS TO USE FOR POPPING
---------------------------------*/
$actual_url = ( isset( $_SERVER[ 'HTTPS' ] ) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//numbers
$count_rows = "SELECT count(*) from " . $table . "";
$letter_count = $wpdb->get_var($count_rows);
$per_page = 20;
$prev_page = $page - 1;
$next_page = $page + 1;
$max_pages = ceil( $letter_count / $per_page );


/* #3: BUILD FORM
---------------------------------*/
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="dagos-letters-letter-control-area" class="wrap">
	<h1><?php _e( 'Dago\'s Scheduler' ); ?></h1>

	<!--<ul class="subsubsub">
		<li><strong>Filter by approved:</strong></li><br />
		<li>
			<a href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'approve', '-1' ); return false;" class="<?php if ( ! isset($_GET['approve']) || $_GET['approve'] === '-1' ) echo 'current'; ?>">All</a> |
		</li>
		<li>
			<a href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'approve', '0' ); return false;" class="<?php if ( isset($_GET['approve']) && $_GET['approve'] === '0') echo 'current'; ?>">Pending</a> |
		</li>
		<li>
			<a href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'approve', '1' ); return false;" class="<?php if ( isset($_GET['approve']) && $_GET['approve'] === '1') echo 'current'; ?>">Approved</a>		
		</li>
	</ul>

	<ul class="subsubsub" style="margin-left:30px;">
		<li><strong>Filter by letter type:</strong></li><br />
		<li>
			<a href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'filter', '-1' ); return false;" class="<?php if ( ! isset($_GET['filter']) || $_GET['filter'] === '-1' ) echo 'current'; ?>">All</a> |
		</li>
		<li>
			<a href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'filter', 'correction' ); return false;" class="<?php if ( isset($_GET['filter']) && $_GET['filter'] === 'correction') echo 'current'; ?>">Corrections</a> |
		</li>
		<li>
			<a href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'filter', 'letter' ); return false;" class="<?php if ( isset($_GET['filter']) && $_GET['filter'] === 'letter') echo 'current'; ?>">Letters</a>	|
		</li>
		<li>
			<a href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'filter', 'information' ); return false;" class="<?php if ( isset($_GET['filter']) && $_GET['filter'] === 'information') echo 'current'; ?>">Requests for Information</a>		
		</li>
	</ul>-->

	<form id="letter-form" method="get" >		
		<div class="tablenav top">		
			<h2 class="screen-reader-text">Letters list navigation</h2>
			<div class="tablenav-pages">
				<span class="displaying-num"><?php print $letter_count ?> items</span>
				<span class="pagination-links">
					<?php if ( $page === 1 ) : ?>
						<span class="tablenav-pages-navspan" aria-hidden="true">«</span>
						<span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
					<?php else : ?>
						<a class="first-page" href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'paged', '1' ); return false;">
							<span class="screen-reader-text">First page</span>
							<span aria-hidden="true">«</span>
						</a>

						<a class="prev-page" href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'paged', <?php echo $prev_page; ?> ); return false;">
							<span class="screen-reader-text">Previous page</span>
							<span aria-hidden="true">‹</span>
						</a>
					<?php endif; ?>
						<span class="paging-input">
							<label for="current-page-selector" class="screen-reader-text">Current Page</label>
							<input class="current-page" id="current-page-selector" type="text" name="paged" value="<?php echo $page; ?>" size="3" aria-describedby="table-paging">
							<span class="tablenav-paging-text"> of <span class="total-pages"><?php print $max_pages; ?></span></span>
						</span>
					<?php if ( $page === (int)$max_pages ) : ?>
						<span class="tablenav-pages-navspan" aria-hidden="true">›</span>
						<span class="tablenav-pages-navspan" aria-hidden="true">»</span>
					<?php else : ?>
						<a class="next-page" href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'paged', <?php echo $next_page; ?> ); return false;">
							<span class="screen-reader-text">Next page</span>
							<span aria-hidden="true">›</span>
						</a>

						<a class="last-page" href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'paged', <?php echo $max_pages; ?> ); return false;">
							<span class="screen-reader-text">Last page</span>
							<span aria-hidden="true">»</span>
						</a>
					<?php endif; ?>
				</span>
			</div>
			<br class="clear">
		</div>
		<h2 class="screen-reader-text">Letters list</h2>
		<table class="wp-list-table widefat fixed striped comments">
			<thead>
				<tr>
					<td id="cb" class="manage-column column-cb check-column">
					</td>
					<th scope="col" id="author" class="manage-column column-author">Submitter</th>
					<th scope="col" id="comment" class="manage-column column-comment column-primary">Purpose of Meeting</th>
					<th scope="col" id="response" class="manage-column column-response">Details</th>
					<th scope="col" id="date" class="manage-column column-date">Submitted On</th>	
				</tr>
			</thead>
			<tbody id="the-comment-list" class="">
				<?php 
					if ( empty($results) ) {
						print '<h2>No letters found.</h2>';
					} else {
						foreach ($results as $result) {					
							$ret = '';							
							$content = $result->letter_content;
							$result->letter_complete === '0' ? $class = 'Complete' : $class = 'Not Complete';
							$class === 'Complete' ? $cssClass = 'Approve' : $cssClass = 'Unapprove';

							//date convertions
							$date = strtotime($result->letter_date);
							$time = date('g:i a', $date);
							$date = date('M d, Y', $date);


							//clean up content of letter
							//convert line breaks to p tags
							$content = explode( "\n", $content );

							$ret = '<tr id="comment-' . $result->id .'" class="comment even thread-even depth-1 approved'; 
							$ret .= $result->letter_complete !== '1' ? ' unapproved' : '';
							$ret .= '" data-type="' . $result->letter_type .'" data-id="' . $result->letter_type .'">';
								$ret .= '<th scope="row" class="check-column">';$ret .= '</th>'; 
								//column 1
								$ret .= '<td class="author column-author" data-colname="Author">'; 
									$ret .= '<strong>' . $result->letter_name .'</strong><br>'; 
									$ret .= '<a href="mailto:' . $result->letter_email .'">' . $result->letter_email .'</a><br>'; 
								$ret .= '</td>'; 
								//column 2
								$ret .= '<td class="comment column-comment has-row-actions column-primary" data-colname="Letter">'; 
									$ret .= '<div class="letter-contents">';
										foreach ($content as $c) {
								        	$ret .= '<p>' . $c . '</p>';
								        }
							        $ret .= '</div>';
									$ret .= '<div class="row-actions">';										
										$ret .= '<span class="' . $cssClass . '" data-letter="' . $result->id . '">
													<a aria-label="' . $class . ' this letter">' . $class . '</a>
												</span>';
										$ret .= '<span class="edit" data-letter="' . $result->id . '"> | 
													<a aria-label="Edit this letter">Edit</a>
												</span>';
										$ret .= '<span class="trash" data-letter="' . $result->id . '"> | 
													<a aria-label="Delete this letter">Delete</a>
												</span>'; 									
									$ret .= '</div>'; 
									$ret .= '<button type="button" class="toggle-row">
												<span class="screen-reader-text">Show more details</span>
											</button>'; 
								$ret .= '</td>'; 
								//column 3
								$ret .= '<td class="response column-response" data-colname="In Response To">'; 
									$ret .= '<div class="response-links">'; 
										$ret .= '<strong>' . $result->letter_represent .'</strong><br>'; 
										$ret .= $result->letter_district . '<br>';
										$ret .= 'on ' . $result->letter_meeting_date . '<br>';
									$ret .= '</div>'; 
								$ret .= '</td>'; 
								//column 4
								$ret .= '<td class="date column-date" data-colname="Submitted On">'; 
									$ret .= '<div class="submitted-on">'; 
										$ret .= $date . ' at ' . $time;
									$ret .= '</div>'; 
								$ret .= '</td>'; 
							$ret .= '</tr>'; 
							print $ret;
						}
					}
				?>		
				<tr id="replyrow" class="inline-edit-row"><td colspan="5" class="colspanchange">
					<form id="update-letters">						
						<div id="replycontainer">
							<label for="replycontent" class="screen-reader-text">Comment</label>
							<div id="wp-replycontent-wrap" class="wp-core-ui wp-editor-wrap html-active"><link rel="stylesheet" id="editor-buttons-css" href="http://upholdingdemocracy.org/wp-includes/css/editor.min.css?ver=4.7.3" type="text/css" media="all">
								<div id="wp-replycontent-editor-container" class="wp-editor-container">
									<textarea class="wp-editor-area" rows="20" cols="40" name="replycontent" id="replycontent"></textarea>
								</div>
							</div>

						</div>

						<div id="edithead" style="">
							<div class="inside">
								<label for="author-name">Name</label>
								<input type="text" name="name" size="50" value="" id="author-name">
							</div>
							<div class="inside">
								<label for="author-email">Email</label>
								<input type="text" name="email" size="50" value="" id="author-email">
							</div>
						</div>

						<p id="replysubmit" class="submit">
							<a href="" class="cancel button alignleft">Cancel</a>
							<a href="" class="update button button-primary alignright">Update</a>
						</p>
          				
					</form>
				</tr>	
			</tbody>
			<tfoot>
				<tr>
					<td class="manage-column column-cb check-column"></td>
					<th scope="col" id="author" class="manage-column column-author">Submitter</th>
					<th scope="col" id="comment" class="manage-column column-comment column-primary">Purpose of Meeting</th>
					<th scope="col" id="response" class="manage-column column-response">Details</th>
					<th scope="col" id="date" class="manage-column column-date">Submitted On</th>	
				</tr>
			</tfoot>
		</table>
		<div class="tablenav bottom">
			<div class="tablenav-pages">
				<span class="displaying-num"><?php print $letter_count ?> items</span>
				<span class="pagination-links">
					<?php if ( $page === 1 ) : ?>
						<span class="tablenav-pages-navspan" aria-hidden="true">«</span>
						<span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
					<?php else : ?>
						<a class="first-page" href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'paged', '1' ); return false;">
							<span class="screen-reader-text">First page</span>
							<span aria-hidden="true">«</span>
						</a>

						<a class="prev-page" href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'paged', <?php echo $prev_page; ?> ); return false;">
							<span class="screen-reader-text">Previous page</span>
							<span aria-hidden="true">‹</span>
						</a>
					<?php endif; ?>
						<span class="paging-input">
							<label for="current-page-selector" class="screen-reader-text">Current Page</label>
							<input class="current-page" id="current-page-selector" type="text" name="paged" value="<?php echo $page; ?>" size="3" aria-describedby="table-paging">
							<span class="tablenav-paging-text"> of <span class="total-pages"><?php print $max_pages; ?></span></span>
						</span>
					<?php if ( $page === (int)$max_pages ) : ?>
						<span class="tablenav-pages-navspan" aria-hidden="true">›</span>
						<span class="tablenav-pages-navspan" aria-hidden="true">»</span>
					<?php else : ?>
						<a class="next-page" href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'paged', <?php echo $next_page; ?> ); return false;">
							<span class="screen-reader-text">Next page</span>
							<span aria-hidden="true">›</span>
						</a>

						<a class="last-page" href="" onclick="dagosLettersPrePostURLCleaning('<?php echo $actual_url ?>', 'paged', <?php echo $max_pages; ?> ); return false;">
							<span class="screen-reader-text">Last page</span>
							<span aria-hidden="true">»</span>
						</a>
					<?php endif; ?>
				</span>
			</div>
		<br class="clear">
		</div>
	</form>
</div>

<script type="text/javascript">
	'use strict';

	/* #1: CLEAN URL BEFORE PASSING VARS
	----------------------------------------------------*/
	function dagosLettersPrePostURLCleaning( url, vara, val ) {
		//get variables
		var args = {},
			splitter,
			outputURL;

		//split current URL
		splitter = url.split('?');
		outputURL = splitter[0] + '?'; // start rebuilding url

		splitter = splitter[1].split('&');
		outputURL += splitter[0];

		for (var i = 0 ; i < splitter.length ; i++ ) {
			var temp = splitter[i].split('=');	
			args[temp[0]] = temp[1];
		};

		//add or update target variable
		args[vara] = val;
		
		//rebuild new url query
		args['approve'] ? outputURL += '&approve=' + args['approve'] : '';
		args['paged'] ? outputURL += '&paged=' + args['paged'] : '';
		args['filter'] ? outputURL += '&filter=' + args['filter'] : '';

		window.location.href = outputURL;
	}

	/* #2: APPROVE/UNAPPROVE LETTER
	----------------------------------------------------*/
	jQuery(document).ready(function(){
		jQuery('.trash').add('.Approve').add('.Unapprove').on('click', function(){
			event.preventDefault();

			jQuery.ajax('http://upholdingdemocracy.org/wp-content/plugins/dagos-scheduler/admin/partials/dagos-letters-admin-menu-actions.php', {
				cache: false,
				data: {
					'id': 		jQuery(this).attr('data-letter'),
					'target': 	jQuery(this).attr('class') 
				},
				success: function(data) {
					window.location.reload();
				}
			})

		});
	});

	/* #3: QUICK EDIT
	----------------------------------------------------*/
	jQuery(document).ready(function(){

		//edit screen
		jQuery('.edit').on('click', function(){
			event.preventDefault();

			//data
			var rowID = jQuery(this).attr('data-letter'),
				rowPostID = jQuery(this).attr('data-post-id'),
				rowContent = jQuery(' #comment-' + rowID + ' .letter-contents ').text(),
				rowEmail = jQuery(' #comment-' + rowID + ' td[data-colname="Author"] a ').attr('href'),
				rowName = jQuery(' #comment-' + rowID + ' td[data-colname="Author"] strong ').text();

			//restore all
			jQuery('#replyrow').hide();
			jQuery('.comment').show();

			//show edit, hide current row	
			jQuery('#replyrow').show().insertAfter('#comment-' + rowID);
			jQuery('#comment-' + rowID).hide();

			//populate edit screen
			rowEmail = rowEmail.split(':');
			jQuery(' #replyrow #replycontainer textarea ').val(rowContent);
			jQuery(' #replyrow #author-name ').attr( 'value', rowName);
			jQuery(' #replyrow #author-email ').attr( 'value', rowEmail[1]);
			jQuery('#replysubmit a:eq(1)').attr( {
				'data-post-id': rowPostID,
				'data-letter-id': rowID	
			});
		});

		//cancel transaction
		jQuery('#replysubmit .cancel').on('click', function(){
			event.preventDefault();

			//restore all
			jQuery('#replyrow').hide();
			jQuery('.comment').show();

		});

		//update letter
		jQuery('#replysubmit .update').on('click', function(){
			event.preventDefault();
			jQuery.ajax('http://upholdingdemocracy.org/wp-content/plugins/dagos-scheduler/admin/partials/dagos-letters-admin-menu-actions.php', {
				cache: false,
				data: {
					'id': 		jQuery(this).attr('data-letter-id'),
					'name': 	jQuery(' #replyrow #author-name ').attr( 'value' ),
					'email': 	jQuery(' #replyrow #author-email ').attr( 'value' ), 
					'content':  jQuery(' #replyrow #replycontainer textarea ').val()
				},
				success: function(data) {
					window.location.reload();
				}
			})

		});

	});

</script>