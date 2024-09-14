<?php

class TextSpeechIA {
    public static function text_to_speech($text, $post_id = null) {
        if ( !$post_id ) {
            return null;
        }

        $token = self::getAccessToken();
        
        ////$token=getToken();
        $cont='<speak version="1.0" xmlns="http://www.w3.org/2001/10/synthesis" xml:lang="es-ES">
            <voice name="es-ES-AbrilNeural">
                ' . $text . '
            </voice>
        </speak>';
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, 'https://germanywestcentral.tts.speech.microsoft.com/cognitiveservices/v1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($cont));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        
        $headers = array();
        $headers[] = 'Ocp-Apim-Subscription-Key: ' . get_option('p2p-azure-subscription-key'); 
        
        $headers[] = 'Content-Type: application/ssml+xml';
        $headers[] = 'Host: germanywestcentral.tts.speech.microsoft.com';
        $headers[] = 'Content-Length: '.strlen($cont);
        $headers[] = 'Authorization: Bearer '.$token;//Token okay
        $headers[] = 'User-Agent: EasternServer';
        $headers[] = 'X-Microsoft-OutputFormat: audio-16khz-128kbitrate-mono-mp3';
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
         $result = curl_exec($ch);
            
            // file en /post2podcast/post_id.mp3 dentro la la carpeta uploads
            if (!file_exists(wp_upload_dir()['basedir'] . '/post2podcast')) {
                mkdir(wp_upload_dir()['basedir'] . '/post2podcast', 0777, true);
            }
            $file = fopen(wp_upload_dir()['basedir'] . '/post2podcast/' . $post_id . '.mp3', 'w+');
            fputs($file, $result);
            fclose($file);
            return wp_upload_dir()['baseurl'] . '/post2podcast/' . $post_id . '.mp3';
    }

    private static function getAccessToken() {
        $url = 'https://germanywestcentral.api.cognitive.microsoft.com/sts/v1.0/issueToken';
        $ch = curl_init();
        $data_string = json_encode('{body}');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string),
            'Ocp-Apim-Subscription-Key: ' . get_option('p2p-azure-subscription-key')
        ));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        return $result;
    }


    public static function text_to_speech_who($text, $cnt, $voice) {
        $token = self::getAccessToken();
        
        ////$token=getToken();
        $cont='<speak version="1.0" xmlns="http://www.w3.org/2001/10/synthesis" xml:lang="es-ES">
            <voice name="' . $voice . '">
                ' . $text . '
            </voice>
        </speak>';
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, 'https://germanywestcentral.tts.speech.microsoft.com/cognitiveservices/v1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($cont));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        
        $headers = array();
        $headers[] = 'Ocp-Apim-Subscription-Key: ' . get_option('p2p-azure-subscription-key');
        
        
        $headers[] = 'Content-Type: application/ssml+xml';
        $headers[] = 'Host: germanywestcentral.tts.speech.microsoft.com';
        $headers[] = 'Content-Length: '.strlen($cont);
        $headers[] = 'Authorization: Bearer '.$token;//Token okay
        $headers[] = 'User-Agent: EasternServer';
        $headers[] = 'X-Microsoft-OutputFormat: audio-16khz-128kbitrate-mono-mp3';
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
         $result = curl_exec($ch);

         error_log($result);

            $file = fopen('./audios/cuento_' . $cnt . '.mp3', 'w+');
            // si no existe, creamos el fichero
            if (!$file) {
                $file = fopen('./audios/cuento_' . $cnt . '.mp3', 'x+');
            }

            fputs($file, $result);
            fclose($file);
    }

}