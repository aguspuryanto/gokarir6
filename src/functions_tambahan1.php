<?php

function wpdev_before_after($content) {
	//$custom_content = '<strong>SURABAYAJOBFAIR.COM --</strong>';
	$custom_content = preg_replace('/<p>/', '<p><b>SURABAYAJOBFAIR.COM --</b>. ', $content, 1);
    $custom_content = $custom_content . "<p>*Original Posts from SURABAYAJOBFAIR.COM</p>";
	
	$pattern ="/<img(.*?)class=\"(.*?)\"(.*?)>/i";
   	$replacement = '<img$1class="$2 img-responsive img-shadow"$3>';
   	$custom_content = preg_replace($pattern, $replacement, $custom_content);
   	return $custom_content;
}
add_filter('the_content', 'wpdev_before_after');

function add_bxslider(){
	global $post;
	//$compny = get_post_meta($post->ID, 'company', true);
    if(is_single() && $post->post_type=="post"){
		wp_enqueue_style('bxslider_style',  'https://cdn.jsdelivr.net/bxslider/4.2.5/jquery.bxslider.css');
	}
}
add_filter( 'wp_enqueue_scripts', 'add_bxslider');

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep   Optional separator.
 * @return string The filtered title.
 */
function wpdocs_theme_name_wp_title( $title, $sep ) {
    if ( is_feed() ) {
        return $title;
    }
     
    global $page, $paged;
 
    $m        = get_query_var( 'm' );
    $year     = get_query_var( 'year' );
	
	// Add the blog name
    $title .= ' - ' . get_bloginfo( 'name', 'display' );
 
    // Add the blog description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) ) {
        $title .= "$sep - $site_description";
    }
 
    // Add a page number if necessary:
    if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
        $title .= "$sep - " . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );
    }
	
	// 
	$bulanindo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	
    return $title;
}
add_filter( 'wp_title', 'wpdocs_theme_name_wp_title', 10, 2 );

$city_id = array(1,22,25,29,30,42,67,77,78,79,81,88,91,92,93,96,102,110,111,127,144,154,169,387,547,553,1009,2485,2804,2494,2284,2636,15565);

function city_id(){
	global $city_id;
	return $city_id;
}


function hargaPaket($i){
	$harga = array(1 => '250000',2 => '200000',3 => '175000',4 => '150000',5 => '130000',6 => '115000',7 => '100000',8 => '90000',9 => '80000',10 => 'CALL');
	foreach($harga as $k => $v){
		if($k==$i) if(is_numeric($v)) return toIDR($v); else return $v;
	}
}

function premium_booked($i, $arr){
	if(in_array($i,$arr)) return '<span class="label label-info">Booked</span >';
}

function getEdu(){
	
	$args_edu = array(
		'orderby'      	=> 'count',
		'order'     	=> 'DESC',
		'include'     	=> '2485,547,553,2494,2284,2636',
		'taxonomy'  	=> 'category'
	);					
	
	$cat_edu = get_categories( $args_edu );
	return $cat_edu;	
}

function custom_search_query( $query ) {
	if ( !is_admin() && $query->is_main_query() ) {
		if ( $query->is_search ) {
			$meta_query_args = array(
				array(
					'key' => 'city',
					'value' => $query->query_vars['l'],
					'compare' => 'LIKE',
				),
			);
			$query->set('meta_query', $meta_query_args);
			$query->set('posts_per_page', '30');
			$query->set('orderby', 'date');
			$query->set('order', 'DESC');
			
			$today = getdate();
			//$query->set('year', $today["year"]);
			//$query->set('monthnum', $today["mon"]);
		};
	};
}
add_filter( 'pre_get_posts', 'custom_search_query');

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function filter_pagetitle($title) {
    global $search_title;
    if ( is_search() ) {
		//$title = apply_filters( 'wp_title', $search_title );
		$title = $search_title;
	}
	
	$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December');

	// FUNGSI BULAN DALAM BAHASA INDONESIA
	function bulan($x){
		$bulan = array ('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		return $bulan[$x];
	}
	
	$this_month = bulan(date("m"));		
	foreach($months as $month) { //start looping the array
		if (strpos($title,$month) !== false) {
			$title = str_replace($month, $this_month, $title);
		}
	}
	
	//if wordpress can't find the title return the default
    return $title;
}
// add_filter('wp_title', 'filter_pagetitle');

/*
	http://wp-snippets.com/pagination-for-twitter-bootstrap/
*/
function page_navi($before = '', $after = '') {
	global $wpdb, $wp_query;
	$request = $wp_query->request;
	$posts_per_page = intval(get_query_var('posts_per_page'));
	$paged = intval(get_query_var('paged'));
	$numposts = $wp_query->found_posts;
	$max_page = $wp_query->max_num_pages;
	if ( $numposts <= $posts_per_page ) { return; }
	if(empty($paged) || $paged == 0) {
		$paged = 1;
	}
	$pages_to_show = 5;
	$pages_to_show_minus_1 = $pages_to_show-1;
	$half_page_start = floor($pages_to_show_minus_1/2);
	$half_page_end = ceil($pages_to_show_minus_1/2);
	$start_page = $paged - $half_page_start;
	if($start_page <= 0) {
		$start_page = 1;
	}
	$end_page = $paged + $half_page_end;
	if(($end_page - $start_page) != $pages_to_show_minus_1) {
		$end_page = $start_page + $pages_to_show_minus_1;
	}
	if($end_page > $max_page) {
		$start_page = $max_page - $pages_to_show_minus_1;
		$end_page = $max_page;
	}
	if($start_page <= 0) {
		$start_page = 1;
	}
		
	//echo $before.'<div class="pagination"><ul class="clearfix">'."";
	if ($paged > 1) {
		$first_page_text = "<<";
		echo '<li class="prev"><a href="'.get_pagenum_link().'" title="First"><span class="glyphicon glyphicon-home"></span> </a></li>';
	}
		
	$prevposts = get_previous_posts_link('? Previous');
	if($prevposts) { echo '<li>' . $prevposts  . '</li>'; }
	else { /*echo '<li class="disabled"><a href="#">? Previous</a></li>';*/ }
	
	for($i = $start_page; $i  <= $end_page; $i++) {
		if($i == $paged) {
			echo '<li class="active"><a href="#">'.$i.'</a></li>';
		} else {
			echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
		}
	}
	//echo '<li class="">' . next_posts_link('Next ?') . '</li>';
	if ($end_page < $max_page) {
		//$last_page_text = ">>";
		echo '<li class="next"><a href="'.get_pagenum_link($max_page).'" title="Last">' . $max_page . ' </a></li>';
	}
	//echo '</ul></div>'.$after."";
}

function my_search() {
	$term = strtolower( $_GET['term'] );
	$suggestions = array();
	
	$loop = new WP_Query( 's=' . $term );
	while( $loop->have_posts() ) {
		$loop->the_post();
		$suggestion = array();
		$suggestion['label'] = get_post_meta( $loop->ID, 'Company', true ); //get_the_title();
		$suggestion['link'] = get_post_meta( $loop->ID, 'Company', true ); //get_permalink();
		$suggestions[] = $suggestion;
	}
	wp_reset_query();
 	
   	$response = json_encode( $suggestions );
   	echo $response;
   	exit();
}
add_action( 'wp_ajax_my_search', 'my_search' );
add_action( 'wp_ajax_nopriv_my_search', 'my_search' );

function myFunction(){
	//check_ajax_referer( 'ajax-login-nonce', 'nonce' );	
	
	parse_str($_POST['values'], $my_array_of_vars);
    print_r($my_array_of_vars);
	die();
}
add_action('wp_ajax_myFunction', 'myFunction');
add_action('wp_ajax_nopriv_myFunction', 'myFunction');

function implode_category($post_id){
	global $post;
	
	$categories = get_the_category($post_id);
	if($categories){
		foreach($categories as $category) {
			$cat[] = '<a class="btn btn-default btn-ads" href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">Lowongan Kerja '.$category->cat_name.'</a>';
		}
	}
	if($cat) return implode(" ", $cat);
}

// REMOVE WP EMOJI
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

/*
	Hide WordPress Admin Bar
	http://falcon1986.wordpress.com/2011/02/27/remove-admin-bar-wordpress-3-1/
*/
add_filter( 'show_admin_bar', '__return_false' );

/*
	How to change WordPress default FROM email address
	http://www.wprecipes.com/how-to-change-wordpress-default-from-email-address
*/
function new_mail_from($email) {
	return "marketing@surabayajobfair.com";
}
function new_mail_from_name($name) {
	return "SurabayaJobfair";
}

add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');

function notiv($content) {
	global $post;
	
	$expire	= "+30 day";
	$xdate 	= get_post_meta($post->ID, 'expired_date', true);
	if($xdate) $expire = "+".$xdate." day";
	
	$pre_content = $content;
	
    if(is_single() && $post->post_type=="post" && $post->ID !=105882) {
		//$pre_content .= '<hr><h2 class="text-center">Penting!!!</h2><blockquote>Cantumkan <strong>surabayajobfair.com (SJF)</strong> dalam surat lamaran Anda. Hati-hati Penipuan! Proses seleksi penerimaan karyawan <strong>seharusnya tidak dipungut biaya apapun</strong>.</blockquote><p>Hanya pelamar yang memenuhi calon karyawan di atas yang akan dipanggil untuk melakukan wawancara. </p><p class="lead text-center">Jika Anda berminat mengisi '.$post->post_title.' , segera siapkan persyaratan/berkas yang dibutuhkan seperti surat lamaran perkerjaan, CV dan pas photo untuk melamar '.$post->post_title.' tersebut. Klik tombol "KIRIM LAMARAN" di bawah ini.</p>';
	}
	
	if($pre_content) $content = $pre_content;
	return $content;
}

add_filter( 'the_content', 'notiv');



function add_google_rel_author() {
	echo '<link rel="author" href="https://plus.google.com/112065748916981585481/posts" />';
}
add_action('wp_head', 'add_google_rel_author');

/*
	http://davidwalsh.name/create-tiny-url-php
*/
function jobq($url)  {  
	//$url = file_get_contents('http://adfoc.us/api/?key=3dda30eb032141a2b2b3100a3dbfb3c9&url='.$url);
	return $url;
}

function kirimke() {
	global $post;

	$url = get_permalink($post->ID);
	$urlsinglat = jobq($url);
	$postdate = count_days($post->post_date);
	$to = get_post_meta($post->ID, 'mailto', true);
	//$cc = get_post_meta($post->ID, 'mailcc', true);	

	if( ( $_POST['post_status'] == 'publish' ) && ( $_POST['original_post_status'] != 'publish' ) ) {
		$subject = 'Surabayajobfair.com : Iklan Lowongan Telah Terpasang';
	}else{
		//$subject = 'Surabayajobfair.com : Iklan Lowongan Telah Terpasang';		

		if($postdate > 30 && is_sticky($post->ID)) :
			$subject = 'Surabayajobfair.com : Iklan Lowongan Premium Telah Berakhir';
		else:
			$subject = 'Surabayajobfair.com : Iklan Lowongan Telah Di Revisi';
		endif;
	}

	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: Surabaya Job Fair <marketing@surabayajobfair.com>' . "\r\n";
	if($cc !=""){
		$headers .= 'Cc: ' . $cc . "\r\n";
	}	

	if( ( $_POST['post_status'] == 'publish' ) && ( $_POST['original_post_status'] != 'publish' ) ) {
		$message = '<strong>Terima Kasih telah memasang iklan lowongan di http://surabayajobfair.com.</strong><br>Iklan Lowongan Anda Telah Terpasang, Klik link di bawah ini untuk melihat komentar/lamaran:<br>		1. ' . $url . '<br>';	

		if( is_sticky($post->ID) ) {
			$message .= '<p>Pembayaran :<br><strong>BCA Rungkut<br>Nomor Rekening : 8220532560<br>Atas Nama : Agus Puryanto<br>ATAU<br>			Mandiri<br>Nomor Rekening : 140-00-1065912-7<br>Atas Nama : Agus Puryanto</strong></p>';
		}		

		$message .= '<p>=================================================================<br>

		<strong>ANDA INGIN PASANG IKLAN PREMIUM?</strong><br>

		=================================================================<br>

		KELEBIHAN IKLAN PREMIUM<br>

		1. Selalu tampil di halaman depan selama 1 Bulan<br>

		2. Langsung tersebar ke lebih dari 6,788 subscribers (pelanggan via email).<br>

		3. Langsung tersebar ke Social Network (Twitter, Facebook, Koprol, dll).<br>

		4. Tanpa Batasan Posisi dan Karakter.<br>

		5. Biaya mulai Rp. 75.000</p>

		<p>Info Pasang Iklan Premium:<br>

		http://surabayajobfair.com/pasang-lowongan/</p>

		<p>Atau kirim materi iklan ke : marketing@surabayajobfair.com</p>

		

		<p>Hotline Pasang Iklan Lowongan Premium :<br>
		<strong>WA : 082244492100<br>SMS : 082140724011<br>
		Email: marketing@surabayajobfair.com<br></p>
		
		<p>GABUNG HRBP INDONESIA Telegram : http://telegram.me/hrbpindo<br>
GABUNG SURABAYAJOBFAIR Telegram : https://telegram.me/joinchat/CP7Mcz-ukkVMHgX1DCApYA<br>
GABUNG SURABAYAJOBFAIR WA (FULL) : https://chat.whatsapp.com/ARqXMjAcPAvInXbBk8zZtQ</strong></p>

		

		================================================================<br>

		email ini terkirim <strong>auto</strong> bersamaan dengan terpublikasinya iklan lowongan anda di <strong>www.surabayajobfair.com</strong>';

	}else{

		if($postdate >= 30) :

			$message = '<p><strong>Terima kasih, telah memasang iklan lowongan premium di www.surabayajobfair.com.</strong></p>

			<p>=================================================================<br>

			<strong>!Pemberitahuan</strong><br>

			Iklan lowongan premium Anda telah melewati masa tayang.<br>

			'.$url.'<br>atau<br>' . $urlsinglat . '<br>

			=================================================================</p>

			<p>Apabila Anda belum mendapatkan kandidat, silahkan ajukan kembali ke:<br>

			marketing@surabayajobfair.com<br>

			<br>

			Atau<br>

			<br>Anda juga bisa memberikan testimoni, saran dan kritik melalui:<br>

			marketing@surabayajobfair.com</p>

			<p>CEO www.surabayajobfair.com<br>

			Agus Puryanto<br>

			082244492100 / 082140724011 / Pin BBM:52460E36</p>';

		else:

			$message = '<p>=================================================================<br>

			!Pemberitahuan<br>

			Iklan lowongan Anda telah direvisi.<br>

			'.$url.'<br>

			=================================================================</p>';
		endif;

	}	

	if($to){
		$multiple_recipients = array('aguspuryanto@gmail.com');
		$_mailto = $to;
		$_mailto = explode(',', $_mailto);
		if(is_array($_mailto)){
			foreach($_mailto as $tomail){
				$multiple_recipients[] = $tomail;
			}
		}else{
			$multiple_recipients[] = $_mailto;
		}

		//print_r($multiple_recipients);
		wp_mail($multiple_recipients, $subject, $message, $headers);
		//wp_mail('aguspuryanto@gmail.com', $subject, $message, $headers);
	}

}

add_action('publish_post', 'kirimke');

function count_days($date){
	$hari = floor((time() - strtotime($date))/86400);
	return $hari;
}

//http://www.wprecipes.com/how-to-display-twitter-like-time-ago-on-your-wordpress-blog
function time_ago( $type = 'post' ) {

	$d = 'comment' == $type ? 'get_comment_time' : 'get_post_time';
	//http://wp-snippets.com/display-time-agotwitter-style/
	$time_difference = current_time('timestamp') - get_the_time('U');
	if($time_difference < 86400) {
		return human_time_diff($d('U'), current_time('timestamp')) . " " . __('ago');
	} else {
		return the_time( get_option('date_format') );
	};
}

//http://www.wprecipes.com/how-to-get-the-first-image-from-the-post-and-display-it
function catch_that_image() {
	global $post, $posts;
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches [1][0];

	$image = wp_get_image_editor( $first_img );
	if ( ! is_wp_error( $image ) ) {
		//$image->rotate( 90 );
		$image->resize( 76, 76, true );
		$image->save( $first_img );
	}
	
	if(empty($first_img)) {
		$first_img = get_template_directory_uri()."/img/logo.jpg"; //"http://placehold.it/250x250";
	}
	
	return $first_img;
}



function tags4meta() {

	$posttags = get_the_tags();

	foreach((array)$posttags as $tag) {

		$tags4meta .= $tag->name . ',';

	}

	$tags4meta = rtrim($tags4meta,',');

	return $tags4meta;

}

/*******************************
 THUMBNAIL SUPPORT
********************************/

add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 76, 76, true );

/*******************************
 EXCERPT LENGTH ADJUST
********************************/

function home_excerpt_length($length) {
	return 75;
}
add_filter('excerpt_length', 'home_excerpt_length');



//add_filter('the_content', 'auto_nofollow');
function auto_nofollow($content) {
    //return stripslashes(wp_rel_nofollow($content));
    return preg_replace_callback('/<a>]+/', 'auto_nofollow_callback', $content);
}

function auto_nofollow_callback($matches) {
    $link = $matches[0];
    $site_link = get_bloginfo('url');
    if (strpos($link, 'rel') === false) {
        $link = preg_replace("%(href=S(?!$site_link))%i", 'rel="nofollow" $1', $link);
    } elseif (preg_match("%href=S(?!$site_link)%i", $link)) {
        $link = preg_replace('/rel=S(?!nofollow)S*/i', 'rel="nofollow"', $link);
    }
    return $link;
}



/*******************************

 WIDGETS AREAS

********************************/



if (function_exists('register_sidebar'))

{

    register_sidebar(array(

		'name'			=> 'sidebar',

		'id'			=> 'sidebar-1',

        'before_widget'	=> '<div class="widget">',

        'after_widget'	=> '</div>',

        'before_title'	=> '<h3>',

        'after_title'	=> '</h3>',

    ));

	

	register_sidebar(array(

		'name'			=> 'footer',

		'id'			=> 'footer-1',

        'before_widget'	=> '<div class="widget">',

        'after_widget'	=> '</div>',

        'before_title'	=> '<h3>',

        'after_title'	=> '</h3>',

    ));

}

	

/*******************************

 PAGINATION

********************************

 * Retrieve or display pagination code.

 *

 * The defaults for overwriting are:

 * 'page' - Default is null (int). The current page. This function will

 *      automatically determine the value.

 * 'pages' - Default is null (int). The total number of pages. This function will

 *      automatically determine the value.

 * 'range' - Default is 3 (int). The number of page links to show before and after

 *      the current page.

 * 'gap' - Default is 3 (int). The minimum number of pages before a gap is 

 *      replaced with ellipses (...).

 * 'anchor' - Default is 1 (int). The number of links to always show at begining

 *      and end of pagination

 * 'before' - Default is '<div class="emm-paginate">' (string). The html or text 

 *      to add before the pagination links.

 * 'after' - Default is '</div>' (string). The html or text to add after the

 *      pagination links.

 * 'title' - Default is '__('Pages:')' (string). The text to display before the

 *      pagination links.

 * 'next_page' - Default is '__('&raquo;')' (string). The text to use for the 

 *      next page link.

 * 'previous_page' - Default is '__('&laquo')' (string). The text to use for the 

 *      previous page link.

 * 'echo' - Default is 1 (int). To return the code instead of echo'ing, set this

 *      to 0 (zero).

 *

 * @author Eric Martin <eric@ericmmartin.com>

 * @copyright Copyright (c) 2009, Eric Martin

 * @version 1.0

 *

 * @param array|string $args Optional. Override default arguments.

 * @return string HTML content, if not displaying.

 */

 

function emm_paginate($args = null) {

	$defaults = array(

		'page' => null, 'pages' => null, 

		'range' => 4, 'gap' => 5, 'anchor' => 1,

		'before' => '<div class="emm-paginate">', 'after' => '</div>',

		'title' => __(' '),

		'nextpage' => __('&raquo;'), 'previouspage' => __('&laquo'),

		'echo' => 1

	);



	$r = wp_parse_args($args, $defaults);

	extract($r, EXTR_SKIP);



	if (!$page && !$pages) {

		global $wp_query;



		$page = get_query_var('paged');

		$page = !empty($page) ? intval($page) : 1;



		$posts_per_page = intval(get_query_var('posts_per_page'));

		$pages = intval(ceil($wp_query->found_posts / $posts_per_page));

	}

	

	$output = "";

	if ($pages > 1) {	

		$output .= "$before<span class='emm-title'>$title</span>";

		$ellipsis = "<span class='emm-gap'>...</span>";



		if ($page > 1 && !empty($previouspage)) {

			$output .= "<a href='" . get_pagenum_link($page - 1) . "' class='emm-prev'>$previouspage</a>";

		}

		

		$min_links = $range * 2 + 1;

		$block_min = min($page - $range, $pages - $min_links);

		$block_high = max($page + $range, $min_links);

		$left_gap = (($block_min - $anchor - $gap) > 0) ? true : false;

		$right_gap = (($block_high + $anchor + $gap) < $pages) ? true : false;



		if ($left_gap && !$right_gap) {

			$output .= sprintf('%s%s%s', 

				emm_paginate_loop(1, $anchor), 

				$ellipsis, 

				emm_paginate_loop($block_min, $pages, $page)

			);

		}

		else if ($left_gap && $right_gap) {

			$output .= sprintf('%s%s%s%s%s', 

				emm_paginate_loop(1, $anchor), 

				$ellipsis, 

				emm_paginate_loop($block_min, $block_high, $page), 

				$ellipsis, 

				emm_paginate_loop(($pages - $anchor + 1), $pages)

			);

		}

		else if ($right_gap && !$left_gap) {

			$output .= sprintf('%s%s%s', 

				emm_paginate_loop(1, $block_high, $page),

				$ellipsis,

				emm_paginate_loop(($pages - $anchor + 1), $pages)

			);

		}

		else {

			$output .= emm_paginate_loop(1, $pages, $page);

		}



		if ($page < $pages && !empty($nextpage)) {

			$output .= "<a href='" . get_pagenum_link($page + 1) . "' class='emm-next'>$nextpage</a>";

		}



		$output .= $after;

	}



	if ($echo) {

		echo $output;

	}



	return $output;

}



/**

 * Helper function for pagination which builds the page links.

 *

 * @access private

 *

 * @author Eric Martin <eric@ericmmartin.com>

 * @copyright Copyright (c) 2009, Eric Martin

 * @version 1.0

 *

 * @param int $start The first link page.

 * @param int $max The last link page.

 * @return int $page Optional, default is 0. The current page.

 */

function emm_paginate_loop($start, $max, $page = 0) {

	$output = "";

	for ($i = $start; $i <= $max; $i++) {

		$output .= ($page === intval($i)) 

			? "<span class='emm-page emm-current'>$i</span>" 

			: "<a href='" . get_pagenum_link($i) . "' class='emm-page'>$i</a>";

	}

	return $output;

}



/*******************************

 CUSTOM COMMENTS

********************************/



function mytheme_comment($comment, $args, $depth) {

	$GLOBALS['comment'] = $comment; ?>

	

	<li <?php comment_class('clearfix'); ?> id="li-comment-<?php comment_ID() ?>">

		<?php echo get_avatar($comment,$size='38',$default='http://www.gravatar.com/avatar/61a58ec1c1fba116f8424035089b7c71?s=32&d=&r=G' ); ?>

		

		<div id="comment-<?php comment_ID(); ?>">

			

	  

			<div class="text">

				<?php comment_text() ?>

			</div>

	  

			<?php if ($comment->comment_approved == '0') : ?>

			<em><?php _e('Your comment is awaiting moderation.') ?></em><br />

			<?php endif; ?>



			<div class="reply">

			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>

			</div>

		</div>

	</li>

<?php 

}



/* FOR USER ONLY */



//http://falcon1986.wordpress.com/2011/03/09/addremove-contact-info-fields-wordpress-user-profiles/

// Add extra contact info to user profile page

function extra_contact_info($contactmethods) {

    unset($contactmethods['aim']);

    //unset($contactmethods['yim']);

    unset($contactmethods['jabber']);

    $contactmethods['facebook'] = 'Facebook';

    $contactmethods['twitter'] = 'Twitter';

    $contactmethods['linkedin'] = 'LinkedIn';



    return $contactmethods;

}

add_filter('user_contactmethods', 'extra_contact_info');



//http://www.webfroze.com/automatically-alert-your-users-about-new-blog-posts.html

function email_members($post_ID)  {

    global $wpdb;

    

	$url = get_permalink($post_ID);

	$urlsinglat = jobq($url);

	

	foreach( $wpdb->get_results("SELECT user_email FROM $wpdb->users;") as $key => $object)

    {

		$user_emails[] = $object->user_email;

    }

    $users = implode(',', $user_emails);

    mail($users, "New Post from Surabaya Jobfair!", 'A new post has been published on http://www.surabayajobfair.com. Check it out.<br>' . $url . '<br>atau<br>' . $urlsinglat . '');

    return $post_ID;

}

//add_action('publish_post', 'email_members');







/*

	http://wpsnipp.com/index.php/functions-php/track-post-views-without-a-plugin-using-post-meta/

*/

function getPostViews($postID){

    $count_key = 'post_views_count';

    $count = get_post_meta($postID, $count_key, true);

    if($count==''){

        delete_post_meta($postID, $count_key);

        add_post_meta($postID, $count_key, '0');

        return "0 View";

    }

    return $count.' Views';

}



function setPostViews($postID) {

    $count_key = 'post_views_count';

    $count = get_post_meta($postID, $count_key, true);

    if($count==''){

        $count = 0;

        delete_post_meta($postID, $count_key);

        add_post_meta($postID, $count_key, '0');

    }else{

        $count++;

        update_post_meta($postID, $count_key, $count);

    }

}



/*

	http://sumtips.com/2011/03/add-delete-edit-custom-fields-wordpress.html

*/

function add_custom_meta_boxes($post_ID) {

	global $wpdb;

	if(!wp_is_post_revision($post_ID)) {

		add_post_meta($post_ID, 'JobType', 'Full Time', true);

	}

}

//add_action('admin_init','add_custom_meta_boxes');



//add_action('publish_page', 'add_custom_field_automatically');

//add_action('publish_post', 'add_custom_field_automatically');

function add_custom_field_automatically($post_ID) {

	global $wpdb;

	if(!wp_is_post_revision($post_ID)) {

		add_post_meta($post_ID, 'field-name', 'custom value', true);

	}

}



/*

	Display or Count how many times a post has been viewed.

	id = the post id and action = display or count

	

	http://www.free-php.net/796/wordpress-count-post-views-without-plugin/

*/

function arixWp_PostViews( $id, $action ) {

	$axCountMeta 	= 'ax_post_views'; // Your Custom field that stores the views

	$axCount 	= get_post_meta($id, $axCountMeta, true);

	if ( $axCount == '' ) {

		if ( $action == 'count' ) {

			$axCount = 0;

		}

		delete_post_meta( $id, $axCountMeta );

		add_post_meta( $id, $axCountMeta, 0 );

		if ( $action == 'display' ) {

			echo "0 Views";

		}		

	} else {

		if ( $action == 'count' ) {

			$axCount++;

			update_post_meta( $id, $axCountMeta, $axCount );

		} else {

			echo $axCount . ' Views';	

		}

	}	

}



/*

	Automatically Create Meta Description From The_content

	http://wpsnipp.com/index.php/functions-php/automatically-create-meta-description-from-the_content/

*/

function create_meta_desc() {
	global $post;

	if (!is_single()) {
		return;
	}

	$post = get_post($post->ID);
	setup_postdata( $post ); // hello
	$output = esc_attr( get_the_excerpt() );
	echo "
<meta name='description' content='$output' />";
}

add_action('wp_head', 'create_meta_desc');



/**

 * Add Google+ meta tags to header

 *

 * @uses	get_the_ID()  Get post ID

 * @uses	setup_postdata()  setup postdata to get the excerpt

 * @uses	wp_get_attachment_image_src()  Get thumbnail src

 * @uses	get_post_thumbnail_id  Get thumbnail ID

 * @uses	the_title()  Display the post title

 *

 * @author c.bavota

 */

//add_action( 'wp_head', 'add_google_plus_meta' );

function add_google_plus_meta() {

 

	if( is_single() ) {

 

		global $post; 

		$post_id = get_the_ID();

		setup_postdata( $post );

 

		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'thumbnail' );

		$thumbnail = empty( $thumbnail ) ? '' : '<meta itemprop="image" content="' . esc_url( $thumbnail[0] ) . '">';

	?>

 

	<!-- Google+ meta tags -->

	<meta itemprop="name" content="<?php esc_attr( the_title() ); ?>">

	<meta itemprop="description" content="<?php echo esc_attr( get_the_excerpt() ); ?>">

	<?php echo $thumbnail . "\n"; ?>

	 

	<!-- eof Google+ meta tags -->

	<?php
	} 
}


function remove_the_dashboard () {

	if (current_user_can('level_10')) {

		return;

	}else {

		global $menu, $submenu, $user_ID;

		$the_user = new WP_User($user_ID);

		reset($menu); $page = key($menu);

		while ((__('Dashboard') != $menu[$page][0]) && next($menu))

		$page = key($menu);

		if (__('Dashboard') == $menu[$page][0]) unset($menu[$page]);

		reset($menu); $page = key($menu);

		while (!$the_user->has_cap($menu[$page][1]) && next($menu))

		$page = key($menu);

		if (preg_match('#wp-admin/?(index.php)?$#',$_SERVER['REQUEST_URI']) && ('index.php' != $menu[$page][2]))

		wp_redirect(get_option('siteurl') . '/wp-admin/post-new.php');

	}

}


function remove_first_image ($content) {

	if (!is_page() && !is_feed() && !is_feed() && !is_home()) {

		$content = preg_replace("/<img[^>]+\>/i", "", $content, 1);

	}

	return $content;

}

add_filter('the_content', 'remove_first_image');



function my_search_query( $query ) {

	// not an admin page and is the main query

	if ( !is_admin() && $query->is_main_query() ) {

		if ( is_search() ) {

			$query->set( 'orderby', 'date' );

		}

	}

}

add_action( 'pre_get_posts', 'my_search_query' );

function toIDR($number){
	$number = (float)$number;
	return number_format($number,0,',','.');
}

add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
function wpdocs_set_html_mail_content_type() {
    return 'text/html';
}

function change_search_url_rewrite() {
    if ( is_search() && ! empty( $_GET['s'] ) ) {
        wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) );
        exit();
    }   
}
add_action( 'template_redirect', 'change_search_url_rewrite' );

function wpse8170_phpmailer_init(PHPMailer $mailer){
	$mailer->IsSMTP();
	$mailer->Host = "ssl://smtp.gmail.com"; // your SMTP server
	$mailer->Port = 465;
	$mailer->SMTPAuth = true; // Force it to use Username and Password to authenticate
	$mailer->Username = 'marketing@surabayajobfair.com';
	$mailer->Password = 'sekawanRayaa255';
  
	// Additional settings…
	$mailer->SMTPSecure = "ssl"; // Choose SSL or TLS, if necessary for your server
	$mailer->From = "marketing@surabayajobfair.com";
	$mailer->FromName = "SURABAYAJOBFAIR";
	$mailer->SMTPDebug = false; // write 0 if you don't want to see client/server communication in page
	$mailer->CharSet  = "utf-8";
}

add_action( 'phpmailer_init', 'wpse8170_phpmailer_init', 10, 1);

function wpse8170_filter_content($content){
	global $post;
	if ( get_post_type( $post->ID ) == 106689 ) {
		remove_filter( 'the_content', 'wpautop' );
		//remove_filter( 'the_excerpt', 'wpautop' );
		add_filter( 'the_content', 'km_remove_wpautop_line_breaks' );
		
		$content_post = get_post($post->ID);
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
		$content = strip_tags($content, '<p><br/>');
		return $content;
	}
}

add_filter('wp', 'wpse8170_filter_content');
function km_remove_wpautop_line_breaks( $content ) {
	return wpautop( $content, false );
}

/*
 * CUSTOM WP-JSON API
 */
 
add_action( 'rest_api_init', 'slug_register_postmeta' );
function slug_register_postmeta() {
    register_rest_field( 'post',
        'meta',
        array(
            'get_callback'    => 'slug_get_company',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function slug_get_company( $object, $field_name, $request ) {
    // return get_post_meta( $object[ 'id' ], $field_name, true );
    return array(
		'company' => get_post_meta( $object[ 'id' ], 'company', true ),
		'company_address' => get_post_meta( $object[ 'id' ], 'company_address', true ),
		'city' => get_post_meta( $object[ 'id' ], 'city', true ),
		'mailto' => get_post_meta( $object[ 'id' ], 'mailto', true )
	);
}

function get_sticky_posts(WP_REST_Request $request) {
	
	$options = get_option('tp_option');
	$sticky_posts = array();
	for($i= 1; $i<= 9; $i++) {
		if(!empty($options["id_".$i])) $sticky_posts[] = $options["id_".$i];
	}
	
    $request['filter'] = [
        'post__in' => $sticky_posts, //get_option( 'sticky_posts' ),
		// 'order' => 'asc',  
		// 'orderby' => 'post_date',  
		'posts_per_page' => -1
    ];

    $response = new WP_REST_Posts_Controller('post');
    $posts = $response->get_items($request);

    return $posts;
}

function get_sticky_id(WP_REST_Request $request){
	
	/* $args = array(
		'post__in' => get_option( 'tp_option' ),
		'orderby' => 'post_date', 
		'posts_per_page' => -1
	);
	
	$my_query = new WP_Query( $args );
	$post_id = array();	
	if ( $my_query->have_posts() ) : while ( $my_query->have_posts() ) : $my_query->the_post();
		
		$post_id[] = array( 'id' => $my_query->post->ID );
		
	endwhile; endif;
	wp_reset_query(); */
	
	$options = get_option('tp_option');
	$posts = array();
	for($i= 1; $i<= 30; $i++) {
		if(!empty($options["id_".$i])) $posts[] = array("id" => $options["id_".$i]); //get_post($options["id_$i"]);
	}

    return $posts;
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'wp/v2', '/sticky_post', array(
        'methods' => 'GET',
        'callback' => 'get_sticky_posts',
    ));
	
	register_rest_route( 'wp/v2', '/sticky_id', array(
        'methods' => 'GET',
        'callback' => 'get_sticky_id',
    ));
});

add_action( 'rest_api_init', 'register_api_hooks' );

function register_api_hooks() {
  register_rest_route(
    'wp/v2', '/login/',
    array(
      'methods'  => 'GET',
      'callback' => 'login',
    )
  );
}

function login($request){
    $creds = array();
    $creds['user_login'] = $request["username"];
    $creds['user_password'] =  $request["password"];
    $creds['remember'] = true;
    $user = wp_signon( $creds, false );

    if ( is_wp_error($user) )
      echo $user->get_error_message();

    return $user;
}

//add_action( 'after_setup_theme', 'custom_login' );

add_filter('the_content', 'remove_hyperlink');
function remove_hyperlink($content) {
    global $post;
    if($post->post_type==="blog"){
    	// echo $post->post_type;
    	// $content = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $content);

		// $content = preg_replace('/ <hr>.*/', '', $content);
		// $content = preg_replace('/\b<hr>.*$/', '', $content);
		// $content = preg_replace('/<h3>Baca Juga:(.*?)<\/h3>/i', '', $content);
		// $content = preg_replace("/<h3>Baca Juga:.*<\/h3>/", "", $content);
		$content = preg_replace("/<h3>Baca Juga:[^>]+>/", "", $content);
		$content = preg_replace("|\<h3.*\>Baca Juga:(.*\n*)\</h2\>|isU", "", $content);
		// $content = substr($content, 0, strpos( $content, 'Baca Juga:'));
	}
	return $content;

	/*Urbanhire STANDARD
	IDR390K / MONTH
	IDR3.900K / YEAR*/
}

?>