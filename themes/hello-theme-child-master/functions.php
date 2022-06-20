<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		'1.0.1'
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );

/*
 * 
 *Custom Shortcode function for showing teams list from custom post type
 * 
 */
function teams_list_shortcode() { 
  ?>
	<ul> 
	<?php
		global $post;
		$args = array(
			'post_type' => 'team',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'order'    => 'ASC',
			'post__not_in' => array( $post->ID )
		);
		$latest_teams = get_posts( $args );

		if ( $latest_teams ) {
			foreach ( $latest_teams as $post ) :?>
				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
			<?php
			endforeach;
			wp_reset_postdata();
		}
	?>	
	</ul>
<?php
}

add_shortcode('team_list', 'teams_list_shortcode');

function wpb_demo_shortcode() { 
  ?>
<div class="elementor-element elementor-widget elementor-widget-accordion" data-element_type="widget" data-widget_type="accordion.default">
	<div class="elementor-widget-container">
		<div class="elementor-accordion" role="tablist">
			<?php
		global $post;
		$args = array(
			'post_type' => 'team',
			'post_status' => 'publish',
			'posts_per_page' => -1,
 			'order'    => 'ASC'
// 			'post__not_in' => array( $post->ID )
		);
		$latest_teams = get_posts( $args );
	
		$total = count($latest_teams);
		$i = $total; 
		if ( $latest_teams ) {
			foreach ( $latest_teams as $post ) :
			$margin = ((($total - $i)/10)*100);
// 			$margin = $i*(100/$total); ?>
			<div class="elementor-accordion-item" style="margin-left:<?php echo $margin;?>%">
				<div id="elementor-tab-title-<?php the_ID(); ?>" class="elementor-tab-title elementor-active" data-tab="<?php the_ID(); ?>" role="tab" aria-controls="elementor-tab-content-<?php the_ID(); ?>" aria-expanded="false" tabindex="0" aria-selected="false"> <span class="elementor-accordion-icon elementor-accordion-icon-right" aria-hidden="true">
															<span class="elementor-accordion-icon-closed"><i class="fas fa-angle-down"></i></span> <span class="elementor-accordion-icon-opened"><i class="fas fa-angle-up"></i></span> </span> <a class="elementor-accordion-title" href=""><?php the_title(); ?></a> </div>
				<div id="elementor-tab-content-<?php the_ID(); ?>" class="elementor-tab-content elementor-clearfix elementor-active" data-tab="<?php the_ID(); ?>" role="tabpanel" aria-labelledby="elementor-tab-title-<?php the_ID(); ?>"><span class="text-white"><?php if ( has_excerpt() ) { the_excerpt(); } ?></span><a href="<?php the_permalink(); ?>" class="text-green">READ MORE <img src="https://demos.webhostingtechlabs.com/emeritus/wp-content/themes/hello-theme-child-master/images/green-right-arrow-small.png"></a></div>
			</div>
		<?php
			$i--;
			endforeach;
			wp_reset_postdata();
		}
	?>	</div>
	</div>
</div>
<?php
}

add_shortcode('teams_accordion', 'wpb_demo_shortcode');

/* 
 * 
 * Action to fetch all featured posts on each post types
 * in post widget query.  
 * 
 */

add_action( 'elementor/query/customquerypost', function( $query ) {
 // Set the custom post type 
$query->set( 'post_type', [ 'post' ] ); 

// Set for the custom field 
$meta_query[] = [          
'key' => '_is_ns_featured_post',          
'value' => [ 'yes' ],          
'compare' => 'in', ];  

$query->set( 'meta_query', $meta_query );

 } );

add_action( 'elementor/query/customqueryvideo', function( $query ) {
 // Set the custom post type 
$query->set( 'post_type', [ 'video' ] ); 

// Set for the custom field 
$meta_query[] = [          
'key' => '_is_ns_featured_post',          
'value' => [ 'yes' ],          
'compare' => 'in', ];  

$query->set( 'meta_query', $meta_query );

 } );

add_action( 'elementor/query/customquerydownload', function( $query ) {
 // Set the custom post type 
$query->set( 'post_type', [ 'downloads' ] ); 

// Set for the custom field 
$meta_query[] = [          
'key' => '_is_ns_featured_post',          
'value' => [ 'yes' ],          
'compare' => 'in', ];  

$query->set( 'meta_query', $meta_query );

 } );


/* 
 * 
 * Fliter Action to set default values of posts type custom field of ACF
 * 
 */
function my_acf_load_field($value, $post_id, $field) {
  if (empty($value)) {
    $value = get_post_type($post_id);
	 
	  if($value == 'post'){
		  $value = 'Blog';
	  }
	  else if($value == 'downloads'){
		  $value = 'Download';
	  }
	
  }
	$field['disabled'] = true; 
  return $value;
}

add_filter('acf/load_value/name=post_type', 'my_acf_load_field', 20, 3);

/* 
 * 
 * Action for showing multiple type of posts in post widget query
 * 
 */
add_action( 'elementor/query/customqueryrecent', function( $query ) {
	$query->set( 'post_type', [ 'post', 'video', 'downloads' ] );
} );

/*
 * 
 * Custom footer script
 * 
 */

function my_custom_footer_code(){
?>
<script>
	
	jQuery( document ).on('scroll', function(){
		if ( jQuery( document ).scrollTop() > 76 ){
			jQuery( '.site-header' ).addClass( 'site-header-sticky' );

		} else {
			jQuery( '.site-header' ).removeClass( 'site-header-sticky' );	
		}

	});
	
	/* Below code changes the position of the date before title on home page psot grids */
	jQuery('.elementor-posts-container .elementor-post__text .elementor-post__title').each(function() {
		jQuery(this).siblings('.elementor-post__meta-data').after(this);
	});
	jQuery('.elementor-posts-container .elementor-post .elementor-post__thumbnail__link').each(function() {
		jQuery(this).siblings('.elementor-post__text').after(this);
	});
	
	jQuery(".sf-field-post_type").find("li").each(function()
    {
       var fullPath = jQuery(this).find('label').text(); 
		if(fullPath == 'Videos'){
			jQuery(this).find('label').text('Video');
		}
		else if(fullPath == 'Downloads'){
			jQuery(this).find('label').text('Download');
		}
		else if(fullPath == 'Posts'){
			jQuery(this).find('label').text('Blog');
		}
       
    });
	
	//window.addEventListener("DOMMouseScroll", handleScroll);
	//window.addEventListener("mousewheel", handleScroll);

	function wheelDistance(e) {
		console.log(e);
		if (!e) {
			e = window.event;
		}
		var w = e.wheelDelta,
			d = e.detail;
		if (d) {
			return -d / 3; // Firefox;
		}

		// IE, Safari, Chrome & other browsers
		return w / 120;
	}

	function handleScroll(e) {
		var delta = wheelDistance(e);
		console.log("delta - "+delta);
		var time = 300;
		var distance = 100;

		jQuery('html, body').stop().animate({
			scrollTop: jQuery(window).scrollTop()
			- (distance * delta)
		}, time);
	}
	
	jQuery(document).ready(function(){
		
		jQuery('.back-btn').on('click', function() {
		  window.history.go(-1); 
		  return false;
		});
		
		jQuery(".container.usp > div").css("top","100px");
		
		jQuery(window).scroll(function(event) {
			var top_of_element = jQuery(".usp-cards").offset().top;
			var bottom_of_element = jQuery(".usp-cards").offset().top + jQuery(".usp-cards").outerHeight();
			var bottom_of_screen = jQuery(window).scrollTop() + jQuery(window).innerHeight();
			var top_of_screen = jQuery(window).scrollTop();

			if ((bottom_of_screen > top_of_element) && (top_of_screen < bottom_of_element) && ( jQuery(window).scrollTop() >= top_of_element )){
				console.log('visible');
				var lastScrollTop = 0;
				var st = jQuery(this).scrollTop();
				   if (st > lastScrollTop){
					   // downscroll code
					   
					   var wScroll = (jQuery(this).scrollTop() - jQuery(".usp-cards").offset().top );
					   
// // 					   if(wScroll < 0 ) {
// // 						   jQuery("reset to 0");
// // 						   jQuery('.usp-card._1').css({"transform": "translate3d(0px, 0px, 0px)"});
// // 						   jQuery('.usp-card._2').css({"transform": "translate3d(0px, 0px, 0px)"});
// // 					   }
// // 					   if(wScroll > 0 && wScroll < 420) {
// // 						   console.log('down 1 - '+wScroll);
// // 						   jQuery('.usp-card._1').css({"transform": "translate3d(0px, -"+wScroll+"px, 0px)"});
// // 						   jQuery('.usp-card._2').css({"transform": "translate3d(0px, 0px, 0px)"});
// // 					   }
// // 					   if(wScroll > 420 && wScroll < 840) {
// // 						   console.log('down 2 - '+wScroll);
// // 						   jQuery('.usp-card._1').css({"transform": "translate3d(0px, -420px, 0px)"});
// // 						   jQuery('.usp-card._2').css({"transform": "translate3d(0px, -"+(wScroll-420)+"px, 0px)"});
// // 					   }
// // 					   if(wScroll > 840) {
// // 						   jQuery('.usp-card._1').css({"transform": "translate3d(0px, -420px, 0px)"});
// // 						   jQuery('.usp-card._2').css({"transform": "translate3d(0px, -420px, 0px)"});
// // 					   }
// // 				   } else {
// // 					  // upscroll code
// // 					  console.log('up 1');
// // 				   }
// // 				   lastScrollTop = st;
// // 			} else {
// // 				// the element is not visible, do something else
// // 				console.log('not visible');
// // 				jQuery('.usp-card._1').css({"transform": "translate3d(0px, 0px, 0px)"});
// // 				jQuery('.usp-card._2').css({"transform": "translate3d(0px, 0px, 0px)"});
// // 			}
			
					   
					   if(wScroll < 0 ) {
						   console.log("reset to 0");
						   jQuery('.usp-card._1').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._2').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._3').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._4').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._5').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._6').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._7').css({"transform": "translate3d(0px, 0px, 0px)"});
					   }
					   if(wScroll > 0 && wScroll < 420) {	// Card 1
						   console.log('down 1 - '+wScroll);
						   jQuery('.usp-card._1').css({"transform": "translate3d(0px, -"+wScroll+"px, 0px)"});
						   jQuery('.usp-card._2').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._3').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._4').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._5').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._6').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._7').css({"transform": "translate3d(0px, 0px, 0px)"});
					   }
					   if(wScroll > 520 && wScroll < 840) {		// Card 2
						   console.log('down 2 - '+wScroll);
						   jQuery('.usp-card._1').css({"transform": "translate3d(0px, -420px, 0px)"});
						   jQuery('.usp-card._2').css({"transform": "translate3d(0px, -"+(wScroll-420)+"px, 0px)"});
						   jQuery('.usp-card._3').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._4').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._5').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._6').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._7').css({"transform": "translate3d(0px, 0px, 0px)"});
					   }
					   if(wScroll > 940 && wScroll < 1260) {	// Card 3
						   console.log('down 3 - '+wScroll);
						   jQuery('.usp-card._1').css({"transform": "translate3d(0px, -420px, 0px)"});
						   jQuery('.usp-card._2').css({"transform": "translate3d(0px, -420px, 0px)"});
						   jQuery('.usp-card._3').css({"transform": "translate3d(0px, -"+(wScroll-840)+"px, 0px)"});
						   jQuery('.usp-card._4').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._5').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._6').css({"transform": "translate3d(0px, 0px, 0px)"});
						   jQuery('.usp-card._7').css({"transform": "translate3d(0px, 0px, 0px)"});
					   }
					    if(wScroll > 1360 && wScroll < 1680) {	// Card 4
						   console.log('down 4 - '+wScroll);
							jQuery('.usp-card._1').css({"transform": "translate3d(0px, -500px, 0px)"});
							jQuery('.usp-card._2').css({"transform": "translate3d(0px, -500px, 0px)"});
							jQuery('.usp-card._3').css({"transform": "translate3d(0px, -500px, 0px)"});
						   	jQuery('.usp-card._4').css({"transform": "translate3d(0px, -"+(wScroll-1260)+"px, 0px)"});
						   	jQuery('.usp-card._5').css({"transform": "translate3d(0px, -"+(wScroll-1680)+"px, 0px)"});
						   	jQuery('.usp-card._6').css({"transform": "translate3d(0px, -"+(wScroll-1680)+"px, 0px)"});
						   	jQuery('.usp-card._7').css({"transform": "translate3d(0px, -"+(wScroll-1680)+"px, 0px)"});
					   }
					   if(wScroll > 1780 && wScroll < 2100) {	// Card 5
						   console.log('down 5 - '+wScroll);
						   jQuery('.usp-card._1').css({"transform": "translate3d(0px, -580px, 0px)"});
						   jQuery('.usp-card._2').css({"transform": "translate3d(0px, -580px, 0px)"});
						   jQuery('.usp-card._3').css({"transform": "translate3d(0px, -580px, 0px)"});
						   jQuery('.usp-card._4').css({"transform": "translate3d(0px, -580px, 0px)"});
						   jQuery('.usp-card._5').css({"transform": "translate3d(0px, -"+(wScroll-1680)+"px, 0px)"});
						   jQuery('.usp-card._6').css({"transform": "translate3d(0px, -"+(wScroll-2100)+"px, 0px)"});
						   jQuery('.usp-card._7').css({"transform": "translate3d(0px, -"+(wScroll-2100)+"px, 0px)"});
					   }
					   if(wScroll > 2200 && wScroll < 2520) {	// Card 6
						   console.log('down 6 - '+wScroll);
						   jQuery('.usp-card._1').css({"transform": "translate3d(0px, -660px, 0px)"});
						   jQuery('.usp-card._2').css({"transform": "translate3d(0px, -660px, 0px)"});
						   jQuery('.usp-card._3').css({"transform": "translate3d(0px, -660px, 0px)"});
						   jQuery('.usp-card._4').css({"transform": "translate3d(0px, -660px, 0px)"});
						   jQuery('.usp-card._5').css({"transform": "translate3d(0px, -660px, 0px)"});
						   jQuery('.usp-card._6').css({"transform": "translate3d(0px, -"+(wScroll-2100)+"px, 0px)"});
						   jQuery('.usp-card._7').css({"transform": "translate3d(0px, -"+(wScroll-2520)+"px, 0px)"});
					   }
					   if(wScroll > 2520 && wScroll < 2940) {	// Card 7
						   console.log('down 7 - '+wScroll);
						   jQuery('.usp-card._1').css({"transform": "translate3d(0px, -740px, 0px)"});
						   jQuery('.usp-card._2').css({"transform": "translate3d(0px, -740px, 0px)"});
						   jQuery('.usp-card._3').css({"transform": "translate3d(0px, -740px, 0px)"});
						   jQuery('.usp-card._4').css({"transform": "translate3d(0px, -740px, 0px)"});
						   jQuery('.usp-card._5').css({"transform": "translate3d(0px, -740px, 0px)"});
						   jQuery('.usp-card._6').css({"transform": "translate3d(0px, -740px, 0px)"});
						   jQuery('.usp-card._7').css({"transform": "translate3d(0px, -"+(wScroll-2520)+"px, 0px)"});
						   jQuery('.usp-card._8').css({"transform": "translate3d(0px, -"+(wScroll-2940)+"px, 0px)"});
					   }
					   /*if(wScroll > 2940 ) {	// Card 8
						   console.log('down 8 - '+wScroll);
						   jQuery('.usp-card._1').css({"transform": "translate3d(0px, -810px, 0px)"});
						   jQuery('.usp-card._2').css({"transform": "translate3d(0px, -810px, 0px)"});
						   jQuery('.usp-card._3').css({"transform": "translate3d(0px, -810px, 0px)"});
						   jQuery('.usp-card._4').css({"transform": "translate3d(0px, -810px, 0px)"});
						   jQuery('.usp-card._5').css({"transform": "translate3d(0px, -810px, 0px)"});
						   jQuery('.usp-card._6').css({"transform": "translate3d(0px, -810px, 0px)"});
						   jQuery('.usp-card._7').css({"transform": "translate3d(0px, -810px, 0px)"});
						   //jQuery('.usp-card._8').css({"transform": "translate3d(0px, -800px, 0px)"});
					   }*/
				   } else {
					  // upscroll code
					  console.log('up 1');
				   }
				   lastScrollTop = st;
			} else {
				// the element is not visible, do something else
				console.log('not visible');
				if(top_of_screen < top_of_element) {
					jQuery('.usp-card._1').css({"transform": "translate3d(0px, 0px, 0px)"});
					jQuery('.usp-card._2').css({"transform": "translate3d(0px, 0px, 0px)"});
					jQuery('.usp-card._3').css({"transform": "translate3d(0px, 0px, 0px)"});
					jQuery('.usp-card._4').css({"transform": "translate3d(0px, 0px, 0px)"});
					jQuery('.usp-card._5').css({"transform": "translate3d(0px, 0px, 0px)"});
					jQuery('.usp-card._6').css({"transform": "translate3d(0px, 0px, 0px)"});
					jQuery('.usp-card._7').css({"transform": "translate3d(0px, 0px, 0px)"});
					jQuery('.usp-card._8').css({"transform": "translate3d(0px, 0px, 0px)"});
				}
			}
		});	
	});
	
</script>
<?php 
}
add_action('wp_footer', 'my_custom_footer_code');
