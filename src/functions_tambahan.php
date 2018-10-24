<?php
function ordinal($cardinal, $mode=false) {
    /* http://www.internoetics.com/2014/02/25/add-st-nd-rd-th-to-a-number-with-php/ */
	
	$test_c = abs($cardinal) % 10; 
	$extension = ((abs($cardinal) %100 < 21 && abs($cardinal) %100 > 4) ? 'th' : (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th')); 
	if($mode==true) return $cardinal. '<small>' . $extension . '</small>'; else return $cardinal.$extension;
}

/* Cek duplikasi komentar berdasar "comment_post_ID", "comment_author", "comment_author_email"
 */ 

function cek_duplicate_comments(){
	global $wpdb;
	
	$comment_post_ID		= $_POST['comment_post_ID'];
	$comment_author			= $_POST['author'];
	$comment_author_email	= $_POST['email'];
	$comment_content		= $_POST['comment'];
	
	$double = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND comment_approved != 'trash' AND ( comment_author = '$comment_author' OR comment_author_email = '$comment_author_email' ) AND comment_content = '$comment_content' LIMIT 1";
	
	return $wpdb->get_row($double, 'ARRAY_N');
}

function defer_parsing_of_js ( $url ) {
	if ( FALSE === strpos( $url, '.js' ) ) return $url;
	if ( strpos( $url, 'jquery.js' ) ) return $url;
	return "$url' defer ";
}
//add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );

function revconcept_get_images($post_id) {
    global $post;
 
    $thumbnail_ID = get_post_thumbnail_id();
 
    $images = get_children( array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
 
    if ($images) :
 
        foreach ($images as $attachment_id => $image) :
 
             $img_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true); //alt
             if ($img_alt == '') : $img_alt = $image->post_title; endif;
 
             $big_array = image_downsize( $image->ID, 'large' );
             $img_url = $big_array[0];
 
             echo '<li>';
             echo '<img src="';
             echo $img_url;
             echo '" alt="';
             echo $img_alt;
             echo '" />';
             echo '</li><!--end slide-->';
 
		endforeach;
	endif;
}

function _get_images() {
	/*$query_images_args = array(
		'post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 'posts_per_page' => 5,
	);

	$query_images = new WP_Query( $query_images_args );
	$images = array();
	foreach ( $query_images->posts as $image) {
		$images[]= wp_get_attachment_url( $image->ID );
	}
	return $images;*/
	
	global $wpdb;
	$sql = "SELECT * FROM wp_posts WHERE post_type='attachment' AND post_status ='inherit' ORDER BY post_date DESC LIMIT 10";
	$posts = $wpdb->get_results($sql);
	
	$images = array();
	foreach ( $posts as $image) {
		$images[]= $image->guid;
	}
	return $images;
}

/*
 * http://sgwordpress.com/teaches/display-total-number-social-shares-wordpress-post/
 */
 
function sparklette_social_shares() {
    $url = get_permalink( $post_id ); 
    $json = file_get_contents("http://api.sharedcount.com/?url=" . rawurlencode($url));
    $counts = json_decode($json, true);
    $totalcounts= $counts["Twitter"] + $counts["Facebook"]["total_count"] + $counts["GooglePlusOne"];
    return $totalcounts . " shares";
}

add_theme_support( 'post-thumbnails' );

function mythumbnail_id($post_id){
	if ( has_post_thumbnail( $post_id ) ) {
		$image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) );
		if ( ! empty( $image_url[0] ) ) {
			//echo '<a href="' . esc_url( $large_image_url[0] ) . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">';
			//echo get_the_post_thumbnail( $post->ID, 'thumbnail' ); 
			//echo '</a>';
			return get_the_post_thumbnail( $post_id, 'thumbnail' );
		}else{
			return '<img class="img-responsive" src="http://placehold.it/100x100?text='.$i.'" alt="'.( $post->post_title ).'">';
		}
	}
}

// Wordpress Ajax Comments
add_action('wp_enqueue_scripts', 'apply_formjs');
function apply_formjs(){
	wp_enqueue_script('apply-form', get_template_directory_uri().'/apply_form.js', array(), '1.0.0', true);
}

function get_age($birthDate){
	$_age = floor((time() - strtotime($birthDate)) / 31556926);
	return $_age;
}

// Our custom post type function
function create_posttype() {
	register_post_type( 'blog',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'Blog' ),
				'singular_name' => __( 'Blog' )
			),
			'public' => true,
			'has_archive' => true,
			'taxonomies' => array('category'),
			'capability_type' => 'post',
			'rewrite' => array('slug' => 'blog'),
		)
	);
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );
// add_action( 'admin_init', 'create_posttype' );

function add_my_post_types_to_query( $query ) {
	if ( is_home() && $query->is_main_query() )
		$query->set( 'post_type', array( 'post', 'blog' ) );
	return $query;
}
add_action( 'pre_get_posts', 'add_my_post_types_to_query' );

/*	
 * Date Function 
 * http://abetobing.com/blog/function-indonesian-date-untuk-menampilkan-tanggal-dalam-format-indonesia-49.html
 */

function indonesian_date ($timestamp = '', $date_format = 'l, j F Y', $suffix = '') {
    if (trim ($timestamp) == '')
    {
        $timestamp = time ();
    }
    elseif (!ctype_digit ($timestamp))
    {
        $timestamp = strtotime ($timestamp);
    }
    # remove S (st,nd,rd,th) there are no such things in indonesia :p
    $date_format = preg_replace ("/S/", "", $date_format);
    $pattern = array (
        '/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
        '/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
        '/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
        '/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
        '/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
        '/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
        '/April/','/June/','/July/','/August/','/September/','/October/',
        '/November/','/December/',
    );
    $replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
        'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
        'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
        'Januari','Februari','Maret','April','Juni','Juli','Agustus','September',
        'Oktober','November','Desember',
    );
    $date = date ($date_format, $timestamp);
    $date = preg_replace ($pattern, $replace, $date);
    //$date = "{$date} {$suffix}";
    $date = "{$date}";
    return $date;
}

/**** https://www.sourcewp.com/remove-query-strings-static-resources/ 
 **/
 
function remove_script_version( $src ){
	$parts = explode( '?ver', $src );
	return $parts[0];
}
add_filter( 'script_loader_src', 'remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'remove_script_version', 15, 1 );

/* function showdnsserver(){
	$dns = dns_get_record(preg_replace('/www\./i', '', $_SERVER['SERVER_NAME']), DNS_NS);
	echo '<!-- Name Server Records :';
	echo $dns[0]['target'].', '.$dns[1]['target'];
	echo '//-->
	';
}
add_action('wp_footer', 'showdnsserver'); */

function update_option_title(){

	// FUNGSI BULAN DALAM BAHASA INDONESIA
	function getbulan($x){
		$bulan = array ('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		return $bulan[$x];
	}

	$this_month = getbulan(date("m"));
	// echo $this_month . "<br>";
	$wp_title = "Lowongan Kerja Surabaya " . date('Y');
	$site_title = get_option( 'blogname' );
	// echo $wp_title . "<br>";

	if (getbulan(date("m"))!==$this_month) {
		$wp_title = substr_replace($wp_title, $this_month." ", -4, -4);
		echo $wp_title;
		update_option( 'blogname', $wp_title );
	}
}

add_action('init','update_option_title');
?>