<?php
/*
Plugin Name: WCLV 2012 Demo
Plugin URI: 
Description: 
Version: 
Author: 
Author URI: 
License: 
License URI: 
Text Domain: wclv-2012-demo
*/

add_action( 'widgets_init', 'wclv_register_widget' );

function wclv_register_widget() {
    register_widget( 'Github_Repos' );
}


class Github_Repos extends WP_Widget {
    
    public function __construct() {
        parent::__construct(false, 'Github Repos');
    }
    
   
    public function form($instance) {
        // Setup some form defaults
        $defaults = array( 'username' => 'blobaugh' );
        
        // Ensure form data exists
        $instance = wp_parse_args( $instance, $defaults );
        
        ?>

        Github Username: <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'username'); ?>" value="<?php echo $instance['username']; ?>"/>

        <?php
    }
    
    public function widget($args, $instance) {
        
        $repos = wclv_get_repos( $instance['username'] );
        
        echo $args['before_widget'];
        
        echo $args['before_title'] . 'Github Repos' . $args['after_title'];
        
        echo '<ul>';
        foreach( $repos AS $r ) {
           echo '<li>';
           echo "<a href='{$r->url}'>{$r->name}</a>";
           echo '</li>';
        }
        echo '</ul>';
        
        echo $args['after_widget'];
    }
    
} 

function wclv_get_repos( $username ) {
    $response = wp_remote_get( "https://api.github.com/users/$username/repos" );
    if( '200' != wp_remote_retrieve_response_code( $response ) ) 
        return wp_remote_retrieve_response_message($response); // Error retrieving repos
        
    
    $body = wp_remote_retrieve_body($response);
    
    $body = json_decode( $body );
    
    return $body;
}