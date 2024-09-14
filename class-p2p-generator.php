<?php

class Post2Podcast_Generator {
    public static function generate_audio( $post_id ) {
        // get content of the post filtered
        $post = get_post( $post_id );
        $text = apply_filters( 'the_content', $post->post_content );

        // As plain text
        $text = strip_tags( $text );

        // escapamos comillas
        $text = str_replace( '"', '\"', $text );

        // &nbsp;
        $text = str_replace( '&nbsp;', ' ', $text );

        $file_path = TextSpeechIA::text_to_speech($text, $post_id);
        return $file_path;
    }
}