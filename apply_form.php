<?php
require ("../../../wp-load.php");

$allowed 	= array("pdf", "doc", "docx");
$apply_File = false;

if($_FILES['apply_File']['name']){
	
	$output_dir = 'upload/';
	$filename	= $_FILES['apply_File']['name'];
	$filetemp	= $_FILES['apply_File']['tmp_name'];
	$filesize	= $_FILES['apply_File']['size'];
	
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$apply_doc = $output_dir . slugify($filename);
	$apply_doc .= md5($_POST['email']);
	$apply_doc .= ".".$ext;
		
	if(!in_array($ext, $allowed)) {
		echo '<div class="alert alert-danger">
			<p>Hanya file pdf, doc, docx</p>
		</div>';
	}elseif($filesize > 1000000) {
        echo '<div class="alert alert-danger">
			<p>File terlalu besar!.</p>
		</div>';
    }else{	
		if(!file_exists( $apply_doc )){
			move_uploaded_file($filetemp, $apply_doc);
		}
	}
	
	//echo '<pre>'. ($apply_doc) .' # '.$apply_File.'</pre>';	
	if($apply_doc){
		$time = current_time( 'mysql', 1 );
		if($time){	
			$data = array(
				'comment_post_ID' 		=> $_POST['comment_post_ID'],
				'comment_author' 		=> $_POST['author'],
				'comment_author_email' 	=> $_POST['email'],
				'comment_content' 		=> $_POST['comment'],
				'comment_date' 			=> $time,
				'comment_author_IP' 	=> get_the_user_ip(),
				'comment_agent' 		=> $_SERVER['HTTP_USER_AGENT'],
				'comment_approved' 		=> 1
			);
			
			
			//$cek_double = cek_duplicatecomments();
			$cek_double = cek_duplicate_comments();
			if($cek_double<=0){
				$comment_id = wp_insert_comment($data);
				if( $comment_id ){
					add_comment_meta( $comment_id, 'apply_city', $_POST['apply_city'] );
					add_comment_meta( $comment_id, 'apply_mobile', $_POST['apply_mobile'] );
					add_comment_meta( $comment_id, 'apply_ydate', $_POST['apply_ydate'] );
					add_comment_meta( $comment_id, 'apply_gender', $_POST['apply_gender'] );
					add_comment_meta( $comment_id, 'apply_edu', $_POST['apply_edu'] );
					add_comment_meta( $comment_id, 'apply_doc', $apply_doc );
					
					// Send Email to Company
					$send = send_corporate($comment_id, $_POST['author'], $_POST['email']);
					if($send){
						echo '<div class="alert alert-info">
							<h1>Surat Lamaran Terkirim!.</h1>
							<p>Silahkan tunggu proses selanjutnya. Jika dalam 2 minggu tidak ada panggilan, harap di abaikan / atau silahkan melamar pekerjaan yang lain.</p>
						</div>';
					}
				}
			}else{
				
				echo '<div class="alert alert-info">
					<h1>Anda Pernah Melamar Lowongan Ini!.</h1>
					<p>Silahkan tunggu proses selanjutnya. Jika dalam 2 minggu tidak ada panggilan, harap di abaikan / atau silahkan melamar pekerjaan yang lain.</p>
				</div>';
			}
		}	
	}
}else{
	echo '<div class="alert alert-danger">
		<h1>Peringatan!.</h1>
		<p>Anda Belum Melampirkan RESUME / CV</p>
	</div>';
	return false;
}

function get_the_user_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return ( $ip );
}

function send_corporate($comment_id, $author='', $email=''){
	global $allowed;
	
	$post_id 	= isset($_POST['comment_post_ID']) ? $_POST['comment_post_ID'] : '';	
	$author	 	= isset($_POST['author']) ? $_POST['author'] : '';
	$email	 	= isset($_POST['email']) ? $_POST['email'] : '';
	//$mail_to 	= get_post_meta($post_id, 'mail_to', true);	
	
	$comments 	= get_comments(array(
		'post_id' => $post_id,
		'status' => 'approve',
		'meta_key' => 'apply_doc'
	));
	
	$this_comments = get_comments( array('ID' => $comment_id) );	
	if($post_id){
		//$attachments = array( WP_CONTENT_DIR . '/uploads/file_to_attach.zip' );
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: SURABAYAJOBFAIR <marketing@surabayajobfair.com>' . "\r\n";
		
		$message = '<!DOCTYPE html>
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<style type="text/css">
				table, th, td {
					border: 1px solid black;
				}
				th {
					height: 50px;
				}
				th, td {
					padding: 15px;
					text-align: left;
				}
			</style>
		</head>
		<body>		
			<p>Berikut ini adalah daftar pelamar online. Iklan Lowongan "<b>'.add_query_arg( array('utm_campaign' => 'apply', 'utm_medium' => 'email'), get_permalink($post_id)).'</b>"</p>
			
			<blockquote>'.$this_comments[0]->comment_content.'</blockquote>
			
			<table cellpadding="4" cellspacing="0" border="1" width="100%">
				<tbody>
					<tr style="text-align:center;font-weight:bold;background-color:#c2d69b">
						<td>Nama</td>
						<td>Email</td>
						<td>Telp. / HP</td>
						<td>Kota</td>
						<td>Pend.</td>
						<td>Usia</td>
						<td>Resume</td>
					</tr>';
			
				if($comments){
					foreach($comments as $comment) :
						$apply_doc = get_comment_meta( $comment->comment_ID, 'apply_doc', true );
						//echo '<pre>'.$apply_doc.'</pre>';
						$ext_doc = pathinfo($apply_doc, PATHINFO_EXTENSION);

						if(in_array($ext_doc, $allowed)) {
							$apply_doc = '<a target="_blank" href="'.get_template_directory_uri().'/'.$apply_doc.'"> DOWNLOAD RESUME</a>';
						}else{
							$apply_doc = '#';
						}

						$message .= '<tr>
							<td> '.ucwords( $comment->comment_author ).' </td>
							<td> '.$comment->comment_author_email.' </td>
							<td> '.get_comment_meta( $comment->comment_ID, 'apply_mobile', true ).' </td>
							<td> '.get_comment_meta( $comment->comment_ID, 'apply_city', true ).' </td>
							<td> '.get_comment_meta( $comment->comment_ID, 'apply_edu', true ).' </td>
							<td> '.get_age( get_comment_meta( $comment->comment_ID, 'apply_ydate', true ) ).' Th</td>
							<td> '.$apply_doc.'</td>
						</tr>';
					endforeach;
				}
				
			$message .= '</tbody>
			</table>
		
			<p>Email ini adalah notifikasi surat lamaran via http://surabayajobfair.com, jika tidak berkenan silahkan kirimkan aduan ke : marketing@surabayajobfair.com.</p>
			<p>Versi 0.5 (beta)</p>
		</body>
		</html>';
		
		$to = get_post_meta($post_id, 'mailto', true);
		$subject = 'SURAT LAMARAN : '. strtoupper(get_the_title($post_id)).' - VIA SURABAYAJOBFAIR.COM';
		$subject = isset($subject) ? $subject : 'SURAT LAMARAN VIA SURABAYAJOBFAIR.COM';
		
		/*$multiple_recipients = array('aguspuryanto@gmail.com');
		$_mailto = explode(',', $mail_to);
		if(is_array($_mailto)){
			foreach($_mailto as $tomail){
				$multiple_recipients[] = $tomail;
			}
		}else{
			$multiple_recipients[] = $mail_to;
		}*/
		
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
		$send = wp_mail($multiple_recipients, $subject, $message, $headers);
		return $send;
	}
}

function slugify($text){ 
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $text));
}

function cek_duplicatecomments(){	
	return cek_duplicate_comments();
}