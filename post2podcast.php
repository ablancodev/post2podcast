<?php
/**
 * Plugin Name: Post2Podcast
 */

    require_once( 'class-p2p-cpt.php' );
    require_once( 'class-p2p-generator.php' );
    require_once( 'class-p2p-rss.php' );
   
    require_once( 'textspeechia/TextSpeechIA.php' );


    // creamos una sección en el admin
      add_action( 'admin_menu', 'post2podcast_menu' );
      function post2podcast_menu() {
          add_menu_page( 'Post2Podcast', 'Post2Podcast', 'manage_options', 'post2podcast', 'post2podcast_admin' );
      }

      function post2podcast_admin() {
         // if sent
         if ( isset( $_POST['p2p-azure-subscription-key'] ) ) {
             update_option( 'p2p-azure-subscription-key', sanitize_text_field($_POST['p2p-azure-subscription-key']) );
         }
          echo '<h1>Post2Podcast</h1>';
          // grupo de campos para IA: donde indicar los parámetros de azure
         ?>
         <form method="post" action="">
             <?php
             // Subscription-Key
             ?>
            <label for="p2p-azure-subscription-key">Subscription Key</label>
            <input type="text" id="p2p-azure-subscription-key" name="p2p-azure-subscription-key" value="<?php echo get_option( 'p2p-azure-subscription-key' ); ?>">
            <?php
             submit_button();
             ?>
         </form>
         <?php
      }