<?php

class Post2Podcast_RSS {

    public static function init() {
        // generamos un feed para los lectores de podcast a partir de los c2c_episodes
        add_action( 'init', array( __CLASS__, 'add_feed' ) );
        add_action( 'template_redirect', array( __CLASS__, 'template_redirect' ) );

    }

    public static function add_feed() {
        add_feed( 'podcast', array( __CLASS__, 'render_feed' ) );
    }

    /*
    public static function render_feed() {

        header('Content-Type: application/rss+xml; charset=UTF-8');

        $args = array(
            'post_type' => 'p2p_episode',
            'posts_per_page' => -1
        );
        $episodes = get_posts( $args );

        $feed = '<?xml version="1.0" encoding="UTF-8"?>';
        $feed .= '<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">';
        $feed .= '<channel>';
        $feed .= '<title>Post2Podcast</title>';
        $feed .= '<link>' . get_bloginfo( 'url' ) . '</link>';
        $feed .= '<language>es-ES</language>';
        $feed .= '<itunes:author>Post2Podcast</itunes:author>';
        $feed .= '<itunes:summary>Post2Podcast</itunes:summary>';
        $feed .= '<itunes:owner>';
        $feed .= '<itunes:name>Post2Podcast</itunes:name>';
        $feed .= '<itunes:email>' . get_bloginfo( 'admin_email' ) . '</itunes:email>';
        $feed .= '</itunes:owner>';
        $feed .= '<itunes:image href="' . get_bloginfo( 'url' ) . '/wp-content/uploads/2020/06/p2p-logo.png" />';
        $feed .= '<itunes:category text="Technology" />';
        $feed .= '<itunes:explicit>no</itunes:explicit>';

        foreach ( $episodes as $episode ) {
            $audio = get_post_meta( $episode->ID, 'p2p_episode_audio', true );

            // duración del audio
            $duration = 0;
            if ( file_exists( $audio ) ) {
                $duration = round( filesize( $audio ) / 128000 );
            }
            // duration to mm:ss
            $duration = gmdate( 'i:s', $duration );

            $feed .= '<item>';
            $feed .= '<title>' . $episode->post_title . '</title>';
            $feed .= '<link>' . get_permalink( $episode->ID ) . '</link>';
            $feed .= '<guid>' . get_permalink( $episode->ID ) . '</guid>';
            $feed .= '<description>' . $episode->post_content . '</description>';
            $feed .= '<enclosure url="' . $audio . '" length="0" type="audio/mpeg" />';
            $feed .= '<itunes:author>Post2Podcast</itunes:author>';
            $feed .= '<itunes:summary>' . $episode->post_content . '</itunes:summary>';
            $feed .= '<itunes:duration>' . $duration . '</itunes:duration>';
            $feed .= '<itunes:explicit>no</itunes:explicit>';
            $feed .= '</item>';
        }

        $feed .= '</channel>';
        $feed .= '</rss>';

        echo $feed;
        die();
    }
    */

public static function render_feed() {
    header('Content-Type: application/rss+xml; charset=UTF-8');

    $args = array(
        'post_type' => 'p2p_episode',
        'posts_per_page' => -1
    );
    $episodes = get_posts( $args );

    $feed = '<?xml version="1.0" encoding="UTF-8"?>';
    $feed .= '<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">';
    $feed .= '<channel>';
    $feed .= '<title>Cuentos para aprender</title>';
    // description
    $feed .= '<description><![CDATA[¡Sumérgete en el mundo de Cuentos para Aprender, el podcast perfecto para los pequeños curiosos! Cada episodio es una aventura en sí misma, narrando cuentos mágicos que abarcan desde la ciencia y la tecnología hasta la historia y más allá. Con personajes entrañables y lecciones valiosas en cada historia, tus niños se embarcarán en un viaje de descubrimiento y diversión. Suscríbete a Cuentos para Aprender y acompaña a tus hijos en un camino lleno de conocimiento y alegría. Ideal para la hora del cuento, antes de dormir o cualquier momento de aprendizaje y entretenimiento.]]></description>';
    $feed .= '<link>' . get_bloginfo( 'url' ) . '</link>';
    $feed .= '<atom:link href="' . get_feed_link( 'podcast' ) . '" rel="self" type="application/rss+xml" />';
    $feed .= '<language>es</language>';
    $feed .= '<itunes:author>Antonio Blanco Oliva</itunes:author>';
    $feed .= '<itunes:summary><![CDATA[¡Sumérgete en el mundo de Cuentos para Aprender, el podcast perfecto para los pequeños curiosos! Cada episodio es una aventura en sí misma, narrando cuentos mágicos que abarcan desde la ciencia y la tecnología hasta la historia y más allá. Con personajes entrañables y lecciones valiosas en cada historia, tus niños se embarcarán en un viaje de descubrimiento y diversión. Suscríbete a Cuentos para Aprender y acompaña a tus hijos en un camino lleno de conocimiento y alegría. Ideal para la hora del cuento, antes de dormir o cualquier momento de aprendizaje y entretenimiento.]]></itunes:summary>';
    $feed .= '<itunes:owner>';
    $feed .= '<itunes:name>Cuentos para aprender</itunes:name>';
    $feed .= '<itunes:email>' . get_bloginfo( 'admin_email' ) . '</itunes:email>';
    $feed .= '</itunes:owner>';
    $feed .= '<itunes:image href="https://ablancodev.com/wp-content/uploads/2024/09/cuentos-para-aprender_portada.png" />';
    $feed .= '<itunes:category text="Kids &amp; Family" />';
    $feed .= '<itunes:explicit>false</itunes:explicit>';

    foreach ( $episodes as $episode ) {
        $content = apply_filters( 'the_content', $episode->post_content );
        $content = strip_tags( $content );
        $content = str_replace( '"', '\"', $content );
        $content = str_replace( '&nbsp;', ' ', $content );

        $feed .= '<item>';
        $feed .= '<title><![CDATA[' . $episode->post_title . ']]></title>';
        $feed .= '<link>' . get_permalink( $episode->ID ) . '</link>';
        $feed .= '<guid>' . get_permalink( $episode->ID ) . '</guid>';
        $feed .= '<description><![CDATA[' . $episode->post_content . ']]></description>';
        $feed .= '<enclosure url="' . get_post_meta( $episode->ID, 'p2p_episode_audio', true ) . '" type="audio/mpeg" length="0" />';
        $feed .= '<itunes:author>Cuentos para aprender</itunes:author>';
        $feed .= '<itunes:summary><![CDATA[' . $content . ']]></itunes:summary>';
        $feed .= '<itunes:duration>00:00</itunes:duration>';
        $feed .= '<itunes:explicit>false</itunes:explicit>';

        $thumbnail_url = get_the_post_thumbnail_url($episode, 'full');
        if ($thumbnail_url) {
            $feed .= '<itunes:image href="' . $thumbnail_url . '" />';
        }
        $feed .= '</item>';
    }

    $feed .= '</channel>';
    $feed .= '</rss>';

    echo $feed;
    die();
}

    public static function template_redirect() {
        if ( is_feed( 'podcast' ) ) {
            self::render_feed();
        }
    }
}
Post2Podcast_RSS::init();