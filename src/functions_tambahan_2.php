<?php

// https://code.tutsplus.com/tutorials/build-a-custom-wordpress-user-flow-part-2-new-user-registration--cms-23810

// add_action('init', 'add_user');

function add_user() {
    $username = 'username123';
    $password = wp_generate_password( 12, true );
    $email = 'drew@example.com';

    /* CARA 1
     */

    $user = get_user_by( 'email', $email );
    if( ! $user ) {

        // Create the new user
        $user_id = wp_create_user( $username, $password, $email );
        if( is_wp_error( $user_id ) ) {
            // examine the error message
            echo( "Error: " . $user_id->get_error_message() );
            exit;
        }

        // Get current user object
        $user = get_user_by( 'id', $user_id );
    }

    // Remove role
    $user->remove_role( 'subscriber' );

    // Add role
    $user->add_role( 'administrator' );


    /* CARA 2
     */

    if( null == username_exists( $email_address ) ) {

      // Generate the password and create the user
      $password = wp_generate_password( 12, false );
      $user_id = wp_create_user( $email_address, $password, $email_address );

      // Set the nickname
      wp_update_user(
        array(
          'ID'          =>    $user_id,
          'nickname'    =>    $email_address
        )
      );

      // Set the role
      $user = new WP_User( $user_id );
      $user->set_role( 'contributor' );

      // Email the user
      wp_mail( $email_address, 'Welcome!', 'Your Password: ' . $password );

    } // end if
}

function add_user_2() {

    $password = wp_generate_password( 12, true );

    $userData = array(
        'user_login' => 'username',
        'first_name' => 'First',
        'last_name' => 'Last',
        'user_pass' => $password,
        'user_email' => 'you@mail.com',
        'user_url' => '',
        'role' => 'administrator'
    );

    wp_insert_user( $userData );
    wp_mail( $email_address, 'Welcome!', 'Your password is: ' . $password );

}

function add_user_3(){

    $user_login = $_POST['user_login'];
    $user_email = $_POST['user_email'];

    $errors = register_new_user($user_login, $user_email);
    if ( !is_wp_error($errors) ) {
        $redirect_to = !empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : 'wp-login.php?checkemail=registered';
        wp_safe_redirect( $redirect_to );
        exit();
    }
}

if ( ! function_exists( 'mytheme_register_nav_menu' ) ) {
 
    function mytheme_register_nav_menu(){
        register_nav_menus( array(
            'primary_menu' => __( 'Primary Menu' ),
            'footer_menu'  => __( 'Footer Menu' ),
        ) );
    }
    add_action( 'after_setup_theme', 'mytheme_register_nav_menu', 0 );
}