<?php

/**
 * Template Name: Schedule
 */

get_header(); 
   
?>

<div <?php post_class( 'container content-main clearfix' ); ?>>
	<?php do_action('layers_before_page_loop'); ?>
    <div class="grid">
        <?php if( have_posts() ) : ?>
            <?php while( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php layers_center_column_class(); ?>>
				<?php the_content(); ?>
                </article>
            <?php endwhile; // while has_post(); ?>
        <?php endif; // if has_post() ?>
    </div>
    <div id="schedule-form-container">
        <form action="" method="POST" id="schedule-form">
            <div role="name">
                <p>
                  <label for="author">Name: <span>&#x2731</span></label>
                  <input id="author" name="author" type="text" value size="30" maxlength="245" aria-required="true" required="required">
                </p>
            </div>
            <div role="email">
                <p>
                    <label for="email">Email: <span>&#x2731</span></label>
                    <input id="email" name="email" type="text" value size="30" maxlength="100" aria-required="true" required="required">
                </p>
            </div>    
            <div role="represent">
                <p>
                    <label for="represent">Congressperson: <span>&#x2731</span></label>
                    <input id="represent" name="represent" type="text" value size="30" maxlength="100" aria-required="true" required="required">
                </p>
            </div>  
            <div role="district">
                <p>
                    <label for="district">State/District: <span>&#x2731</span></label>
                    <input id="district" name="district" type="text" value placeholder="e.g. CA-01" size="30" maxlength="100" aria-required="true" required="required">
                </p>
            </div>  
            <div role="date">
                <p>
                    <label for="date">Date of Meeting: <span>&#x2731</span></label>
                    <input id="datepicker" name="date" type="text" value aria-required="true" required="required">
                </p>
            </div>  
            <div role="content">
                <p>
                    <label for="content">Purpose of Visit: <span>&#x2731</span></label>
                    <textarea id="content" name="content" cols="45" rows="8" maxlength="65525" aria-required="true" required="required"></textarea>
                </p>
            </div>  
            <div class="g-recaptcha" data-sitekey="6Lfo1BgUAAAAAPhmSd1eEGeidVRSi3VXhcxavAn-"></div>
            <div role="submit">
                <p>
                    <input name="submit" type="submit" value="Submit Letter">
                </p>
            </div>

          </form>
    </div>
    <?php do_action('layers_after_page_loop'); ?>
</div>
<?php wp_enqueue_script( 'jqueryui', '/wp-content/plugins/dagos-scheduler/scheduler/jquery-ui.min.js', array( 'jquery' ) ); ?>
<?php wp_enqueue_script( 'scheduler', '/wp-content/plugins/dagos-scheduler/scheduler/scheduler.js', array( 'jqueryui' ) ); ?>
<?php wp_enqueue_script( 'recap', 'https://www.google.com/recaptcha/api.js' ); ?>
<?php wp_enqueue_style( 'scheduler', '/wp-content/plugins/dagos-scheduler/scheduler/scheduler.css' ); ?>
<?php wp_enqueue_style( 'jqueryui', '/wp-content/plugins/dagos-scheduler/scheduler/jquery-ui.min.css' ); ?>
<?php get_footer();