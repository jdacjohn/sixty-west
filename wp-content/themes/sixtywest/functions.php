<?php
/**
 * Custom Child Theme Functions
 *
 * This file's parent directory can be moved to the wp-content/themes directory 
 * to allow this Child theme to be activated in the Appearance - Themes section of the WP-Admin.
 *
 * Included is a basic theme setup that will add the reponsive stylesheet that comes with Thematic. 
 *
 * More ideas can be found in the community documentation for Thematic
 * @link http://docs.thematictheme.com
 *
 * @package ThematicSampleChildTheme
 * @subpackage ThemeInit
 */



//get rid of some of the widgets that we don't need
function remove_some_widgets(){

	unregister_sidebar( 'secondary-aside' );
	unregister_sidebar( '1st-subsidiary-aside' );
	unregister_sidebar( '2nd-subsidiary-aside' );
	unregister_sidebar( '3rd-subsidiary-aside' );
	unregister_sidebar( 'index-top' );
	unregister_sidebar( 'index-insert' );
	unregister_sidebar( 'index-bottom' );
	unregister_sidebar( 'single-top' );
	unregister_sidebar( 'single-insert' );
	unregister_sidebar( 'single-bottom' );
	unregister_sidebar( 'page-top' );
	unregister_sidebar( 'page-bottom' );
}
add_action( 'widgets_init', 'remove_some_widgets', 11 );
//end get rid of the widgets that we don't need



//get the theme ready for bootstrap
function bootstrapScripts() {
	wp_enqueue_style('bootstrapcssmin', get_bloginfo('stylesheet_directory') . '/bootstrap/css/bootstrap.min.css');
	wp_enqueue_style('bootstrapthememin', get_bloginfo('stylesheet_directory') . '/bootstrap/css/bootstrap-theme.min.css');
	
	wp_enqueue_script('bootstrapjsmain_footer', get_bloginfo('stylesheet_directory') . '/bootstrap/js/bootstrap.min.js', '', '', true);
}
add_action( 'wp_enqueue_scripts', 'bootstrapScripts' );

function metaViewport() {?>
	<meta name="viewport" content="width=device-width">
<?php }
add_action('wp_head','metaViewport');
//end get the theme ready for bootstrap



//Setting up ACF Options Page
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
	acf_add_options_sub_page('All Page Content');
	acf_add_options_sub_page('Social Media');
	
}
//End Setting up ACF Options Page



//Wordpress Mainteanance
function my_embed_oembed_html($html, $url, $attr, $post_id) {
	$newtext = '<div class="video-container">' . $html . '</div>'; //Make it responsive
	$newtext = str_replace( '?feature=oembed', '?feature=oembed&rel=0', $newtext ); //Remove suggested links
	return $newtext;
}
add_filter('embed_oembed_html', 'my_embed_oembed_html', 99, 4);

require_once('wp_bootstrap_navwalker.php');

function remove_sidebar()
{
	return false; 
}
add_filter('thematic_sidebar', 'remove_sidebar');

function replace_last_nav_item($items, $args) {
	return substr_replace($items, '', strrpos($items, $args->after), strlen($args->after));
}
add_filter('wp_nav_menu','replace_last_nav_item',100,2);

function create_post_type() {
	register_post_type( 'companies',
		array(
			'labels' => array(
				'name' => __( 'Companies' ),
				'singular_name' => __( 'Company' )
			),
		'public' => true,
		'has_archive' => false,
		'supports' => array( 'title', 'thumbnail', 'page-attributes'),
		'menu_position' => 5,
		'hierarchical' => true,
		)
	);
	register_post_type( 'team',
		array(
			'labels' => array(
				'name' => __( 'Team' ),
				'singular_name' => __( 'Team' )
			),
		'public' => true,
		'has_archive' => false,
		'supports' => array( 'title', 'thumbnail', 'page-attributes'),
		'menu_position' => 5,
		'hierarchical' => true,
		)
	);
}
add_action( 'init', 'create_post_type' );

function yearshortcode( $atts ) {
	return date('Y');
}
add_shortcode( 'year', 'yearshortcode' );

function childtheme_override_nav_above() {
    // silence
}

function childtheme_override_nav_below() {
    // silence
}

function addDividerLine($styles)
{
	?>
	<div class="dividerline<?php echo $styles; ?>">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="line"></div>
				</div>
			</div>
		</div>
	</div>
	<?php	
}

function simpleTextCreator()
{
	if( have_rows('simple_text_creator') ):
				
		while ( have_rows('simple_text_creator') ) : the_row();
	
			if( get_row_layout() == 'normal_text' ):
				$styles = '';
				$selected = get_sub_field('add_padding');
				if(is_array($selected))
				{
					if( in_array('Padding Top', $selected) ) {
						
						$styles = ' paddingtop';
					}
					if( in_array('Padding Bottom', $selected) ) {
						
						$styles .= ' paddingbottom';
					}
				}
			?>
				<div class="normaltext<?php echo $styles; ?>">
					<?php echo get_sub_field('section_content'); ?>
				</div>
			<?php
			elseif( get_row_layout() == 'blue_text' ) :
				$styles = '';
				$selected = get_sub_field('add_padding');
				if(is_array($selected))
				{
					if( in_array('Padding Top', $selected) ) {
						
						$styles = ' paddingtop';
					}
					if( in_array('Padding Bottom', $selected) ) {
						
						$styles .= ' paddingbottom';
					}
				}
			?>
				<div class="bluetext<?php echo $styles; ?>">
					<?php echo get_sub_field('section_content'); ?>
				</div>
			<?php
			elseif( get_row_layout() == 'section_title' ): 
				$styles = '';
				$selected = get_sub_field('add_padding');
				if(is_array($selected))
				{
					if( in_array('Padding Top', $selected) ) {
						
						$styles = ' paddingtop';
					}
					if( in_array('Padding Bottom', $selected) ) {
						
						$styles .= ' paddingbottom';
					}
				}
			?>
				<div class="sectiontitlesimple<?php echo $styles; ?>">
					<?php
						if(get_sub_field('type') == "Small")
						{
							?><h4 class="sectiontitle"><?php echo get_sub_field('section_title'); ?></h4><?php
						}
						else
						{
							?><h2 class="sectiontitle"><?php echo get_sub_field('section_title'); ?></h2><?php
						}
					?>
				</div>
			<?php
			elseif( get_row_layout() == 'small_image' ): 
				$styles = '';
				$selected = get_sub_field('add_padding');
				if(is_array($selected))
				{
					if( in_array('Padding Top', $selected) ) {
						
						$styles = ' paddingtop';
					}
					if( in_array('Padding Bottom', $selected) ) {
						
						$styles .= ' paddingbottom';
					}
				}
			?>
			<div class="smallimage<?php echo $styles; ?>">
				<?php
					if ( get_sub_field('image') ) {
						$attachment_id = get_sub_field('image');
						$size = "large"; // (thumbnail, medium, large, full or custom size)
						$image = wp_get_attachment_image_src( $attachment_id, $size );
					}
				?>
				<img src="<?php echo $image[0]; ?>" alt="Small Image" />
			</div>
			<?php
			elseif( get_row_layout() == 'simple_text_link' ): 
				$styles = '';
				$selected = get_sub_field('add_padding');
				if(is_array($selected))
				{
					if( in_array('Padding Top', $selected) ) {
						
						$styles = ' paddingtop';
					}
					if( in_array('Padding Bottom', $selected) ) {
						
						$styles .= ' paddingbottom';
					}
				}
			?>
			<div class="simpletextlink<?php echo $styles; ?>">
				<a href="<?php echo get_sub_field('link_location'); ?>"<?php if(get_sub_field('new_window') == "Yes") { echo ' target="_blank"'; } ?>>&lt; <?php echo get_sub_field('link_text'); ?></a>
			</div>
			<?php
			endif;
	
		endwhile;
	
	endif;
}

add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_core', '__return_true' );
//End Wordpress Maintenance



//Reuseable code pieces
function addSocialMedia($style)
{
	echo '<ul class="socialmedia">';
	
	if( have_rows('social_media_repeater', 'option') ):

    	while ( have_rows('social_media_repeater', 'option') ) : the_row();
			if(get_sub_field('social_media_link'))
			{
				if(get_sub_field('social_media_type') == "Twitter")
				{
					if($style == 'rounded')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialtwitter symbol" title="&#xe486;" target="_blank"></a></li>';
					}
					else if($style == 'circle')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialtwitter symbol" title="&#xe286;" target="_blank"></a></li>';
					}
					else
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialtwitter symbol" title="&#xe086;" target="_blank"></a></li>';	
					}
				}
				if(get_sub_field('social_media_type') == "Facebook")
				{
					if($style == 'rounded')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialfacebook symbol" title="&#xe427;" target="_blank"></a></li>';
					}
					else if($style == 'circle')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialfacebook symbol" title="&#xe227;" target="_blank"></a></li>';
					}
					else
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialfacebook symbol" title="&#xe027;" target="_blank"></a></li>';
					}
				}
				if(get_sub_field('social_media_type') == "LinkedIn")
				{
					if($style == 'rounded')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="sociallinkedin symbol" title="&#xe452;" target="_blank"></a></li>';
					}
					else if($style == 'circle')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="sociallinkedin symbol" title="&#xe252;" target="_blank"></a></li>';
					}
					else
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="sociallinkedin symbol" title="&#xe052;" target="_blank"></a></li>';
					}
				}
				if(get_sub_field('social_media_type') == "Pinterest")
				{
					if($style == 'rounded')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialpinterest symbol" title="&#xe464;" target="_blank"></a></li>';
					}
					else if($style == 'circle')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialpinterest symbol" title="&#xe264;" target="_blank"></a></li>';
					}
					else
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialpinterest symbol" title="&#xe064;" target="_blank"></a></li>';
					}
				}
				if(get_sub_field('social_media_type') == "RSS Feed")
				{
					if($style == 'rounded')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialrss symbol" title="&#xe471;" target="_blank"></a></li>';
					}
					else if($style == 'circle')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialrss symbol" title="&#xe271;" target="_blank"></a></li>';
					}
					else
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialrss symbol" title="&#xe071;" target="_blank"></a></li>';
					}
				}
				if(get_sub_field('social_media_type') == "YouTube")
				{
					if($style == 'rounded')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialyoutube symbol" title="&#xe499;" target="_blank"></a></li>';
					}
					else if($style == 'circle')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialyoutube symbol" title="&#xe299;" target="_blank"></a></li>';
					}
					else
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialyoutube symbol" title="&#xe099;" target="_blank"></a></li>';
					}
				}
				if(get_sub_field('social_media_type') == "Google Plus")
				{
					if($style == 'rounded')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialgoogleplus symbol" title="&#xe439;" target="_blank"></a></li>';
					}
					else if($style == 'circle')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialgoogleplus symbol" title="&#xe239;" target="_blank"></a></li>';
					}
					else
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialgoogleplus symbol" title="&#xe039;" target="_blank"></a></li>';
					}
				}
				if(get_sub_field('social_media_type') == "Vimeo")
				{
					if($style == 'rounded')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialvimeo symbol" title="&#xe489;" target="_blank"></a></li>';
			
					}
					else if($style == 'circle')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialvimeo symbol" title="&#xe289;" target="_blank"></a></li>';
					}
					else
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="socialvimeo symbol" title="&#xe089;" target="_blank"></a></li>';
					}
				}
				if(get_sub_field('social_media_type') == "Email")
				{
					if($style == 'rounded')
					{
						echo '<li><a href="mailto:'.get_sub_field('social_media_link').'" class="email symbol" title="&#xe424;" target="_blank"></a></li>';
					}
					else if($style == 'circle')
					{
						echo '<li><a href="mailto:'.get_sub_field('social_media_link').'" class="email symbol" title="&#xe224;" target="_blank"></a></li>';
					}
					else
					{
						echo '<li><a href="mailto:'.get_sub_field('social_media_link').'" class="email symbol" title="&#xe024;" target="_blank"></a></li>';
					}
				}
				if(get_sub_field('social_media_type') == "Instagram")
				{
					if($style == 'rounded')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="instagram symbol" title="&#xe500;" target="_blank"></a></li>';
					}
					else if($style == 'circle')
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="instagram symbol" title="&#xe300;" target="_blank"></a></li>';
					}
					else
					{
						echo '<li><a href="'.get_sub_field('social_media_link').'" class="instagram symbol" title="&#xe100;" target="_blank"></a></li>';
					}
				}
			}
			
		endwhile;
	
	endif;
	
	echo '</ul>';
}

function isBlogView(){
	$args = array(
		'public'   => true,
		'_builtin' => false
	);

	$output = 'names';
	$operator = 'and';
	$post_types = get_post_types( $args, $output, $operator ); 

	foreach ( $post_types as $post_type ) 
	{
		if(is_singular($post_type) || is_post_type_archive($post_type))
		{
			return false;		
		}
	}
	
	$args = array(
		'public'   => true,
		'_builtin' => false
	);

	$output = 'names';
	$operator = 'and';
	$taxonomies = get_taxonomies( $args, $output, $operator ); 

	foreach ( $taxonomies as $taxonomy ) 
	{
		if(is_tax($taxonomy))
		{		
			return false;		
		}
	}
	
	
	if(is_archive() || is_home() || is_single())
	{
		return true;
	}
	else
	{
		return false;
	}
}
	
function isCPTView($cpttocheck)
{
	if(is_singular($cpttocheck) || is_post_type_archive($cpttocheck))
	{
		return true;
	}
	else
	{
		return false;
	}
}
//End resuseable code pieces


//Setting up Header
function remove_menu() {
	remove_action('thematic_header','thematic_brandingopen',1);
	remove_action('thematic_header','thematic_blogtitle',3);
	remove_action('thematic_header','thematic_blogdescription',5);
	remove_action('thematic_header','thematic_brandingclose',7);
	remove_action('thematic_header','thematic_access',9);
}
add_action('init', 'remove_menu');

function mobileHeader() {
	?>
	<div class="headertexture">
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					
					<a class="navbar-brand" href="<?php bloginfo('url') ?>/">
						<?php
							if ( get_field('header_logo', 'option') ) {
								$attachment_id = get_field('header_logo', 'option');
								$size = "large"; // (thumbnail, medium, large, full or custom size)
								$image = wp_get_attachment_image_src( $attachment_id, $size );
							}
						?>
						<img src="<?php echo $image[0]; ?>" alt="<?php bloginfo('name') ?>" title="<?php bloginfo('name') ?>" />
					</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<?php
						$args = array(
							'theme_location' => 'primary-menu',
							'title_li' => '', 
							'menu_class' => 'nav navbar-nav navbar-right',
							'container' => false,
							'walker' => new wp_bootstrap_navwalker()
						);
						wp_nav_menu($args);
					?>
				</div><!--/.nav-collapse -->
			</div><!--/.container-fluid -->
		</nav>
	</div>
	<?php
}
add_action('thematic_header','mobileHeader',9);
//End Setting up Header


//Setting up Footer
function remove_footersiteoptions() {
	remove_action('thematic_footer','thematic_siteinfoopen',20);
	remove_action('thematic_footer','thematic_siteinfo',30);
	remove_action('thematic_footer','thematic_siteinfoclose',40);
}
add_action('init', 'remove_footersiteoptions');


function settingUpFooter(){
	?>
	<div class="footercontainer">
		<div class="container">	
			<div class="row copyrightrow text-center">
				<div class="col-xs-12">
					<?php echo apply_filters('the_content', get_field('copyright_message', 'option')); ?>
				</div>
			</div>
		</div>
	<?php
}
add_action('thematic_footer','settingUpFooter',45);
//End Setting up Footer




//Setting up Template Options
function addingTemplateStuff()
{
	if( have_rows('template_creator') ):

		 // loop through the rows of data
		while ( have_rows('template_creator') ) : the_row();
	
			if( get_row_layout() == 'slideshow' ):
				$styles .= ' '.get_sub_field('background_color');
			?>
			<div class="slideshow<?php echo $styles; ?>">
				<div class="container-fluid nopadding">
					<?php echo get_new_royalslider(get_sub_field('royal_slider_id')); ?>
					<?php echo '<div class="belowshadow"><img src="'.get_bloginfo('stylesheet_directory').'/images/belowshadownew.png" alt="Below Shadow" class="img-responsive" /></div>'; ?>
				</div>
			</div>
			<?php
			elseif( get_row_layout() == 'companies_archive' ) :
				$styles = '';
				$selected = get_sub_field('add_padding');
				if(is_array($selected))
				{
					if( in_array('Padding Top', $selected) ) {
						
						$styles = ' paddingtop';
					}
					if( in_array('Padding Bottom', $selected) ) {
						
						$styles .= ' paddingbottom';
					}
				}
				$styles .= ' '.get_sub_field('background_color');
			?>
			<div class="companyarchive<?php echo $styles; ?>">
				<div class="container">
					<?php
						if(get_sub_field('type') == 'Stacked With Text and Links')
						{
							$companycount = 0;
							$args = array(
								'posts_per_page' => -1,
								'post_type' => 'companies',
								'orderby' => 'menu_order',
								'order' => 'ASC'
							);
							$the_query = new WP_Query( $args );
							if ( $the_query->have_posts() ) {
								while ( $the_query->have_posts() ) {
									$the_query->the_post();
									?>
									<div class="row companyrow companystacked">
										<div class="col-sm-4 text-center center-block">
											<div class="innerbox">
												<?php
													if(get_field('external_link') == "Yes")
													{
														echo '<a href="'.get_field('link_location').'" target="_blank">';
													}
													else
													{
														echo '<a href="'.get_permalink().'">';
													}
													if ( has_post_thumbnail() ) {
														the_post_thumbnail('full', array('class' => 'img-responsive'));
													} 
													echo '</a>';
												?>
											</div>
											<div class="belowshadow"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/belowshadownew.png" alt="Below Shadow" class="img-responsive" /></div>
										</div>
										<div class="col-sm-8">
											<h3><?php echo get_field('companies_title_area'); ?></h3>
											<?php echo get_field('long_description'); ?>
											<div>
												<?php
													if(get_field('external_link') == "Yes")
													{
														echo '<a href="'.get_field('link_location').'" target="_blank">';
													}
													else
													{
														echo '<a href="'.get_permalink().'">';
													}
													echo '< '.get_field('link_line');
													echo '</a>';
												?>
											</div>
										</div>
									</div>
									<?php
									$companycount++;
								}
							} 
							wp_reset_postdata();
						}
						else
						{
							if(get_sub_field('type') == 'Columns Text Below' )
							{
								$textbelow = true;	
							}
							else
							{
								$textbelow = false;	
							}
							
							$args = array(
								'posts_per_page' => -1,
								'post_type' => 'companies',
								'orderby' => 'menu_order',
								'order' => 'ASC'
							);
							
							$the_query = new WP_Query( $args );
							$companycount = 0;
							
							if ( $the_query->have_posts() ) {
								while ( $the_query->have_posts() ) {
									$the_query->the_post();
									if($companycount == 0)
									{
										echo '<div class="row companyrow">';	
									}
									
									echo '<div class="col-sm-4 text-center center-block">';
									echo '<div class="innerbox">';
									if(get_field('external_link') == "Yes")
									{
										echo '<a href="'.get_field('link_location').'" target="_blank">';
										
									}
									else
									{
										echo '<a href="'.get_permalink().'">';
									}
									if ( has_post_thumbnail() ) {
										the_post_thumbnail('full', array('class' => 'img-responsive'));
									} 
									echo '</a>';
									echo '</div>';
									echo '<div class="belowshadow"><img src="'.get_bloginfo('stylesheet_directory').'/images/belowshadownew.png" alt="Below Shadow" class="img-responsive" /></div>';
									
									if($textbelow)
									{
										echo '<div class="textbelow">';
										echo get_field('short_description');
										echo '</div>';		
									}
									
									echo '</div>';
									$companycount++;
									
									if($companycount == 3)
									{
										echo '</div>';
										$companycount = 0;	
									}
								}
								if($companycount != 0)
								{
									echo '</div>';	
								}
							} 
							wp_reset_postdata();
						}
					?>
				</div>
			</div>
			<?php
			elseif( get_row_layout() == 'special_text_area' ):
				$styles = '';
				$selected = get_sub_field('add_padding');
				if(is_array($selected))
				{
					if( in_array('Padding Top', $selected) ) {
						
						$styles = ' paddingtop';
					}
					if( in_array('Padding Bottom', $selected) ) {
						
						$styles .= ' paddingbottom';
					}
				}
				$styles .= ' '.get_sub_field('background_color');
			?>
			<div class="specialarea<?php echo $styles; ?>">
				<?php
				if(get_sub_field('type') == "Text with Logo")
				{
					?>
					<div class="special">
						<div class="container">
							<div class="row verticaldt">
								<div class="col-sm-2 smalllogo">
									<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/logoicon.png" class="img-responsive center-block" alt="Logo Icon" />
								</div>
								<div class="col-sm-10 specialtext">
									<?php echo get_sub_field('section_text'); ?>
								</div>
							</div>
						</div>
					</div>
					<?php	
				}
				else
				{
					?>
					<div class="specialbackground">
						<div class="container">
							<div class="row">
								<div class="col-md-10 col-md-offset-1 text-center">
									<h2><?php echo get_sub_field('section_title'); ?></h2>
									<div class="sectiontext"><?php echo get_sub_field('section_text'); ?></div>
								</div>
							</div>
						</div>
					</div>
					<?php echo '<div class="belowshadow"><img src="'.get_bloginfo('stylesheet_directory').'/images/belowshadownew.png" alt="Below Shadow" class="img-responsive" /></div>'; ?>
				
					<?php	
				}
				?>
			</div>
			<?php
			elseif( get_row_layout() == 'divider_line' ): 
				$styles = '';
				$selected = get_sub_field('add_padding');
				if(is_array($selected))
				{
					if( in_array('Padding Top', $selected) ) {
						
						$styles = ' paddingtop';
					}
					if( in_array('Padding Bottom', $selected) ) {
						
						$styles .= ' paddingbottom';
					}
				}
				$styles .= ' '.get_sub_field('background_color');
				
				addDividerLine($styles);
			?>
			<?php
			elseif( get_row_layout() == 'divider_image' ):
				$styles = '';
				$selected = get_sub_field('add_padding');
				if(is_array($selected))
				{
					if( in_array('Padding Top', $selected) ) {
						
						$styles = ' paddingtop';
					}
					if( in_array('Padding Bottom', $selected) ) {
					
						$styles .= ' paddingbottom';
					}
				}
				$styles .= ' '.get_sub_field('background_color');
			?>
			<div class="dividerimage<?php echo $styles; ?>"> 
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 nopadding">
							<?php
								if ( get_sub_field('image') ) {
									$attachment_id = get_sub_field('image');
									$size = "large"; // (thumbnail, medium, large, full or custom size)
									$image = wp_get_attachment_image_src( $attachment_id, $size );
								}
							?>		
							<img src="<?php echo $image[0]; ?>" alt="Divider Image" class="img-responsive" />
							<?php
								if(get_sub_field('show_shadow') == "Yes")
								{
									echo '<div class="belowshadow"><img src="'.get_bloginfo('stylesheet_directory').'/images/belowshadownew.png" alt="Below Shadow" class="img-responsive" /></div>';	
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
			elseif( get_row_layout() == 'simple_text_area' ):
				$styles = '';
				$selected = get_sub_field('add_padding');
				if(is_array($selected))
				{
					if( in_array('Padding Top', $selected) ) {
						
						$styles = ' paddingtop';
					}
					if( in_array('Padding Bottom', $selected) ) {
						
						$styles .= ' paddingbottom';
					}
				}
				$styles .= ' '.get_sub_field('background_color');
			?>
			<div class="simpletextarea<?php echo $styles; ?>">
				<div class="container">
					<div class="row">
						<?php
							if(get_sub_field('sidebar') == "Yes" || get_sub_field('sidebar') == 'No Sidebar/Indented Text')
							{
								echo '<div class="col-sm-8">';	
							}
							else
							{
								echo '<div class="col-xs-12">';
							}
				
								simpleTextCreator();
								
							echo '</div>';
							
							if(get_sub_field('sidebar') == "Yes")
							{
								?>
									<div class="col-sm-4 sidebar">
										<?php
											if(get_sub_field('show_logo_in_sidebar') == "Yes")
											{
												?>
													<div class="smalllogo">
														<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/logoicon.png" class="img-responsive" alt="Logo Icon" />
													</div>
												<?php	
											}
										?>
										<?php echo get_sub_field('sidebar_content'); ?>
									</div>
								<?php	
							}
						?>
					</div>
				</div>
			</div>
			<?php
			elseif( get_row_layout() == 'team_archive' ):
				$styles = '';
				$selected = get_sub_field('add_padding');
				if(is_array($selected))
				{
					if( in_array('Padding Top', $selected) ) {
						
						$styles = ' paddingtop';
					}
					if( in_array('Padding Bottom', $selected) ) {
						
						$styles .= ' paddingbottom';
					}
				}
				$styles .= ' '.get_sub_field('background_color');
			?>
			<div class="teamarchive<?php echo $styles; ?>">
				<div class="container">
					<?php
						$args = array(
								'posts_per_page' => -1,
								'post_type' => 'team',
								'orderby' => 'menu_order',
								'order' => 'ASC'
							);
							
							$the_query = new WP_Query( $args );
							$teamcount = 0;
							
							if ( $the_query->have_posts() ) {
								while ( $the_query->have_posts() ) {
									$the_query->the_post();
									if($teamcount == 0)
									{
										echo '<div class="row teamrow">';	
									}
									
									echo '<div class="col-sm-4 text-center">';
									
									echo '<a href="'.get_permalink().'">';
									if ( get_field('image_and_name') ) {
										$attachment_id = get_field('image_and_name');
										$size = "large"; // (thumbnail, medium, large, full or custom size)
										$image = wp_get_attachment_image_src( $attachment_id, $size );
									}
									?>		
									<img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title(); ?>" class="img-responsive" />
									<?php
									echo '</a>';
									echo '<div class="belowshadow"><img src="'.get_bloginfo('stylesheet_directory').'/images/belowshadownew.png" alt="Below Shadow" class="img-responsive" /></div>';
									
									echo '</div>';
									$teamcount++;
									
									if($teamcount == 3)
									{
										echo '</div>';
										$teamcount = 0;	
									}
								}
								if($teamcount != 0)
								{
									echo '</div>';	
								}
							} 
							wp_reset_postdata();
					?>
				</div>
			</div>
			<?php
			endif;
	
		endwhile;
	
	endif;	
}
add_action('thematic_abovecontainer','addingTemplateStuff');
//End Setting up Template Options


//Setting up Team Pages
function childtheme_override_single_post()
{
	if(is_singular('team'))
	{
		teamSingleView();	
	}
}

function teamMember()
{
	?>
	<div class="simpletextarea paddingtop paddingbottom gray">
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail('full', array('class' => 'img-responsive'));
						} 
					?>
					<div class="belowshadow"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/belowshadownew.png" alt="Below Shadow" class="img-responsive" /></div>
				</div>
				<div class="col-sm-8">
					<h2 class="sectiontitle"><?php echo get_the_title(); ?></h2>
					<h3><?php echo get_field('position'); ?></h3>
					<div class="simpletextarea">
						<?php simpleTextCreator(); ?>
					</div>
					<div class="emaillink"><a href="<?php echo get_permalink(25); ?>">&lt; Contact</a></div>
				</div>
			</div>
		</div>
	</div>
	<?php	
}

function teamSingleView()
{
	teamMember();
	
	$args = array(
		'posts_per_page' => -1,
		'post_type' => 'team',
		'post__not_in' => array(get_the_ID()),
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);
	
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			
			addDividerLine(' gray');
			
			teamMember();
		}
	} 
	wp_reset_postdata();
	
	?>
	<div class="specialbackground">
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1 text-center">
					<h2><?php echo get_field('bottom_section_title', 'option'); ?></h2>
					<div class="sectiontext"><?php echo get_field('bottom_section_content', 'option'); ?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="belowshadow"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/belowshadownew.png" alt="Below Shadow" class="img-responsive" /></div>
	<?php addDividerLine(' paddingbottom'); ?>
	<?php
}
//End Setting up Team Pages