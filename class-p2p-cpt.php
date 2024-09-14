<?php

class Post2Podcast_CPT {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'register_post_type' ) );

        // creamos un metabox para el CPT p2p_episode
        add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ) );

        // al guardar el post, si se ha pulsado el botón de generar audio, se genera el audio
        add_action( 'save_post', array( __CLASS__, 'save_post' ) );
    }

    // creamos el CPT p2p_episode
    public static function register_post_type() {
        $labels = array(
            'name'               => _x( 'Episodes', 'post type general name', 'post2podcast' ),
            'singular_name'      => _x( 'Episode', 'post type singular name', 'post2podcast' ),
            'menu_name'          => _x( 'Episodes', 'admin menu', 'post2podcast' ),
            'name_admin_bar'     => _x( 'Episode', 'add new on admin bar', 'post2podcast' ),
            'add_new'            => _x( 'Add New', 'episode', 'post2podcast' ),
            'add_new_item'       => __( 'Add New Episode', 'post2podcast' ),
            'new_item'           => __( 'New Episode', 'post2podcast' ),
            'edit_item'          => __( 'Edit Episode', 'post2podcast' ),
            'view_item'          => __( 'View Episode', 'post2podcast' ),
            'all_items'          => __( 'All Episodes', 'post2podcast' ),
            'search_items'       => __( 'Search Episodes', 'post2podcast' ),
            'parent_item_colon'  => __( 'Parent Episodes:', 'post2podcast' ),
            'not_found'          => __( 'No episodes found.', 'post2podcast' ),
            'not_found_in_trash' => __( 'No episodes found in Trash.', 'post2podcast' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'post2podcast' ),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => false,
            'rewrite'            => array( 'slug' => 'episode' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'rest_api'           => true,
            'menu_icon'          => 'dashicons-microphone',
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

        register_post_type( 'p2p_episode', $args );
    }

    // le creamos un metabox con un botón para generarle un audio al post, y en caso de tenerlo, que lo muestre
    public static function add_meta_box() {
        add_meta_box(
            'p2p_episode_audio',
            'Episode Audio',
            array( __CLASS__, 'render_meta_box' ),
            'p2p_episode',
            'normal',
            'high'
        );
    }

    public static function render_meta_box( $post ) {

        $audio = get_post_meta( $post->ID, 'p2p_episode_audio', true );
        
        wp_nonce_field( 'p2p_episode_audio', 'p2p_episode_audio_nonce' );
        ?>
        <p>
            <input type="checkbox" id="p2p_episode_generate_audio" name="p2p_episode_generate_audio" value="1">
            <label for="p2p_episode_generate_audio">Generate Audio on save episode.</label>
        </p>
        <p>
            <label for="p2p_episode_audio">Audio</label>
            <input type="text" id="p2p_episode_audio" name="p2p_episode_audio" value="<?php echo $audio; ?>">
        </p>
        
        <?php if ( $audio ) : ?>
            <audio controls>
                <source src="<?php echo $audio; ?>" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        <?php endif; ?>
        <?php
    }

    public static function save_post( $post_id ) {
        if ( ! isset( $_POST['p2p_episode_audio_nonce'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['p2p_episode_audio_nonce'], 'p2p_episode_audio' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( isset( $_POST['p2p_episode_generate_audio'] ) ) {
            $audio = Post2Podcast_Generator::generate_audio( $post_id );
            update_post_meta( $post_id, 'p2p_episode_audio', $audio );
        }
    }
}
Post2Podcast_CPT::init();