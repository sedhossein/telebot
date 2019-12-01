<?php


/**
 * Class User
 */
class User
{
    /**
     * Telegram Bot ID
     *
     * @var    string
     * @access private
     */
    private $bot_key;

    /**
     * Telegram User Unique ID
     *
     * @var    string
     * @access private
     */
    public $chat_id;


    /**
     * Konstruktor => Setzt Bot ID
     *
     * @param string $chat_id chat id
     * @access public
     */
    public function __construct($chat_id)
    {
        global $config;

        $this->bot_key = $config['bot_token'];
        $this->chat_id = $chat_id;
    }


    /**
     * Nachricht senden
     *
     * <b>Output:</b><br>
     * <code>
     *  Array
     *  (
     *      [success] => 1 oder 0
     *      [info]    => Zeigt Info oder Fehlermeldung
     *  )
     * </code>
     *
     * @param string $chat_id required    ID des Telegram Chats
     * @param string $text required    Text der gesendet werden soll
     * @param string $parse_mode optinal        Markdown oder HTML für z.B. fettgedruckte Texte
     * @param boolean $disable_web_page_preview optinal        Legt fest ob Webpreview deaktivert werden soll
     * @param boolean $disable_notification optinal        Benachrichtigung deaktivieren
     * @param integer $reply_to_message_id optinal        Nachrichten ID für den "Antworten" Modus (reply)
     * @return    array
     * @access public
     */
    public function send_message($text, $reply_to_message_id = NULL, $parse_mode = 'HTML', $disable_web_page_preview = false, $disable_notification = false)
    {
        global $request, $logger;

        $action = 'sendMessage';
        $param = array(
            'chat_id' => $this->chat_id,
            'text' => $text,
            'parse_mode' => $parse_mode,
            'disable_web_page_preview' => $disable_web_page_preview,
            'disable_notification' => $disable_notification,
            'reply_to_message_id' => $reply_to_message_id
        );

        $res = $this->connect($action, $param)->result;

        $logger->info([
            'success' => 1,
            'user_id' => $this->chat_id,
            'type' => 'send_message',
            'title' => 'Send Message Action',
            'more_info' => 'more info : ' . $text . ' for this request : ' . $request->text,
        ]);

        return !$res['ok'] ?
            ["success" => 0, "info" => "Error: " . $res['description']] :
            ["success" => 1, "info" => "Message send"];
    }

    /**
     *
     */
    public function update_last_action()
    {
        global $database;

        $database->update('users', [
            'last_action' => date('Y-m-d H:i:s', time()),
        ], [
            'user_id' => $this->chat_id
        ]);
    }


    /**
     * @param $method
     * @param array $dates
     * @return mixed
     */
    function connect($method, $dates = [])
    {
        global $config;

        $method = ucfirst($method);
        $url = "https://api.telegram.org/bot" . $config['bot_token'] . "/" . $method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dates));
        $res = curl_exec($ch);
        if (curl_error($ch)) {
            var_dump(curl_error($ch));
        } else {
            return json_decode($res);
        }
    }


    /**
     * @param $to
     * @param $from
     * @param $message_id
     * @return bool|string
     */
    function forward_message($to, $from, $message_id)
    {
        global $request, $logger;

        $res = $this->connect('ForwardMessage', [
            'chat_id' => $to,
            'from_chat_id' => $from,
            'message_id' => $message_id
        ])->result;


        if (!$res['ok']) {
            $logger->info([
                'success' => 0,
                'user_id' => $this->chat_id,
                'type' => 'forward_message',
                'title' => 'Forward Message Action',
                'more_info' => 'description : ' . $res['description'] . ' for this request : ' . $request->text,
            ]);
        } else {
            $logger->warning([
                'success' => 1,
                'user_id' => $this->chat_id,
                'type' => 'forward_message',
                'title' => 'Forward Message Action',
                'more_info' => 'description : ' . $res['description'] . ' for this request : ' . $request->text,
            ]);
        }
    }


//
//    /**
//     * Bild senden
//     *
//     * <b>Output:</b><br>
//     * <code>
//     *  Array
//     *  (
//     *      [success] => 1 oder 0
//     *      [info]	=> Zeigt Info oder Fehlermeldung
//     *  )
//     * </code>
//     *
//     * @param	string	$chat_id	required	ID des Telegram Chats
//     * @param	string	$photo		required	Bild das gesendet werden soll
//     * @param	string	$caption	optional	Bildbeschreibung
//     * @return	array
//     * @access	public
//     */
//    public function send_photo($chat_id, $photo, $caption = NULL)
//    {
//        $action = 'sendPhoto';
//        $param = array(
//            'chat_id'	=>	$chat_id,
//            'photo'		=>	$this->curlFile($photo),
//            'caption'	=>	$caption
//        );
//
//        $res = $this->send($action, $param);
//        if (!$res['ok'])
//            $result = Array("success" => 0, "info"	=>	"Error: " . $res['description']);
//        else
//            $result = Array("success" => 1,	"info"	=>	"Photo send");
//
//        return $result;
//    }
//
//    /**
//     * Dateien senden
//     *
//     * <b>Output:</b><br>
//     * <code>
//     *  Array
//     *  (
//     *      [success] => 1 oder 0
//     *      [info]	=> Zeigt Info oder Fehlermeldung
//     *  )
//     * </code>
//     *
//     * @param	string	$chat_id	required	ID des Telegram Chats
//     * @param	string	$document	required	Datei die gesendet werden soll
//     * @return	array
//     * @access	public
//     */
//    public function send_document($chat_id, $document)
//    {
//        $action = 'sendDocument';
//        $param = array(
//            'chat_id'	=>	$chat_id,
//            'document'	=>	$this->curlFile($document)
//        );
//
//        $res = $this->send($action, $param);
//        if (!$res['ok'])
//            $result = Array("success" => 0, "info"	=>	"Error: " . $res['description']);
//        else
//            $result = Array("success" => 1,	"info"	=>	"Document send");
//
//        return $result;
//    }
//
//    /**
//     * Audio senden
//     *
//     * <b>Output:</b><br>
//     * <code>
//     *  Array
//     *  (
//     *      [success] => 1 oder 0
//     *      [info]	=> Zeigt Info oder Fehlermeldung
//     *  )
//     * </code>
//     *
//     * @param	string	$chat_id	required	ID des Telegram Chats
//     * @param	string	$audio		required	Audio Datei die gesendet werden soll
//     * @param	string	$interpret	optional	Interpret
//     * @param	string	$title		optional	Titel
//     * @return	array
//     * @access	public
//     */
//    public function send_audio($chat_id, $audio, $interpret = NULL, $title = NULL)
//    {
//        $action = 'sendAudio';
//        $param = array(
//            'chat_id'	=>	$chat_id,
//            'audio'		=>	$this->curlFile($audio),
//            'performer'	=>	$interpret,
//            'title'		=>	$title
//        );
//
//        $res = $this->send($action, $param);
//        if (!$res['ok'])
//            $result = Array("success" => 0, "info"	=>	"Error: " . $res['description']);
//        else
//            $result = Array("success" => 1,	"info"	=>	"Audio send");
//
//        return $result;
//    }
//
//    /**
//     * Video senden
//     *
//     * <b>Output:</b><br>
//     * <code>
//     *  Array
//     *  (
//     *      [success] => 1 oder 0
//     *      [info]	=> Zeigt Info oder Fehlermeldung
//     *  )
//     * </code>
//     *
//     * @param	string	$chat_id	required	ID des Telegram Chats
//     * @param	string	$video		required	Viedeo das gesendet werden soll
//     * @param	string	$caption	optional	Videobeschreibung
//     * @return	array
//     * @access	public
//     */
//    public function send_video($chat_id, $video, $caption = NULL)
//    {
//        $action = 'sendPhoto';
//        $param = array(
//            'chat_id'	=>	$chat_id,
//            'video'		=>	$this->curlFile($video),
//            'caption'	=>	$caption
//        );
//
//        $res = $this->send($action, $param);
//        if (!$res['ok'])
//            $result = Array("success" => 0, "info"	=>	"Error: " . $res['description']);
//        else
//            $result = Array("success" => 1,	"info"	=>	"Video send");
//
//        return $result;
//    }
//
//    /**
//     * Chat Aktion senden
//     *
//     * <b>Output:</b><br>
//     * <code>
//     *  Array
//     *  (
//     *      [success] => 1 oder 0
//     *      [info]	=> Zeigt Info oder Fehlermeldung
//     *  )
//     * </code>
//     *
//     * @param	string	$chat_id	required	ID des Telegram Chats
//     * @param	integer	$type		required	1 => Nachrichten, 2 => Fotos, 3 => Viedeo aufnehmen, 4 => Viedeo senden/hochladen, 5 => Audio aufnehmen, 6 => Audio senden/hochladen, 7 => Dateien
//     * @return	array
//     * @access	public
//     */
//    public function send_chatAction($chat_id, $type)
//    {
//        $do_action = "";
//
//        switch($type)
//        {
//            case 1:
//                $do_action = "typing";
//                break;
//
//            case 2:
//                $do_action = "upload_photo";
//                break;
//
//            case 3:
//                $do_action = "record_video";
//                break;
//
//            case 4:
//                $do_action = "upload_video";
//                break;
//
//            case 5:
//                $do_action = "record_audio";
//                break;
//
//            case 6:
//                $do_action = "upload_audio";
//                break;
//
//            case 7:
//                $do_action = "upload_document";
//                break;
//        }
//
//        $action = 'sendChatAction';
//        $param = array(
//            'chat_id'	=>	$chat_id,
//            'action'	=>	$do_action
//        );
//
//        $res = $this->send($action, $param);
//        if (!$res['ok'])
//            $result = Array("success" => 0, "info"	=>	"Error: " . $res['description']);
//        else
//            $result = Array("success" => 1,	"info"	=>	"Chat Action send");
//
//        return $result;
//    }
//
//    /**
//     * User aus Gruppe kicken
//     *
//     * <b>Output:</b><br>
//     * <code>
//     *  Array
//     *  (
//     *      [success] => 1 oder 0
//     *      [info]	=> Zeigt Info oder Fehlermeldung
//     *  )
//     * </code>
//     *
//     * @param	string	$chat_id	required	ID des Telegram Chats
//     * @param	integer	$user_id	required	ID des Users der gekickt werden soll
//     * @return	array
//     * @access public
//     */
//    public function kick_chatMember($chat_id, $user_id)
//    {
//        $action = 'kickChatMember';
//        $param = array(
//            'chat_id'					=>	$chat_id,
//            'user_id'					=>	$user_id
//        );
//        $res = $this->send($action, $param);
//        if (!$res['ok'])
//            $result = Array("success" => 0, "info"	=>	"Error: " . $res['description']);
//        else
//            $result = Array("success" => 1,	"info"	=>	"Member kicked");
//        return $result;
//    }
//
//    /**
//     * Ban von einem User entfernen
//     *
//     * <b>Output:</b><br>
//     * <code>
//     *  Array
//     *  (
//     *      [success] => 1 oder 0
//     *      [info]	=> Zeigt Info oder Fehlermeldung
//     *  )
//     * </code>
//     *
//     * @param	string	$chat_id	required	ID des Telegram Chats
//     * @param	integer	$user_id	required	ID des Users der entbannt werden soll
//     * @return	array
//     * @access public
//     */
//    public function unbanChatMember($chat_id, $user_id)
//    {
//        $action = 'unbanChatMember';
//        $param = array(
//            'chat_id'					=>	$chat_id,
//            'user_id'					=>	$user_id
//        );
//        $res = $this->send($action, $param);
//        if (!$res['ok'])
//            $result = Array("success" => 0, "info"	=>	"Error: " . $res['description']);
//        else
//            $result = Array("success" => 1,	"info"	=>	"Member kicked");
//        return $result;
//    }
//
//    /**
//     * Auswahl Keyboard zeigen
//     *
//     * <b>Output:</b><br>
//     * <code>
//     *  Array
//     *  (
//     *      [success] => 1 oder 0
//     *      [info]	=> Zeigt Info oder Fehlermeldung
//     *  )
//     * </code>
//     *
//     * @param	string	$chat_id	required	ID des Telegram Chats
//     * @param	string	$text		required	Text der gesendet werden soll
//     * @param	array	$keyboard	required	Auswahlfelder z.B. array( array( "Zeile1 Test1", "Zeile1 Test2" ), array( "Zeile2 Test3", "Zeile2 Test4" ) )
//     * @return	array
//     * @access	public
//     */
//    public function sendKeyboard($chat_id, $text, $keyboard = Array())
//    {
//        $action = 'sendMessage';
//        $param = array(
//            'chat_id'		=>	$chat_id,
//            'reply_markup'	=>	json_encode(array("keyboard" => $keyboard)),
//            'text'			=>	$text
//        );
//
//        $res = $this->send($action, $param);
//        if (!$res['ok'])
//            $result = Array("success" => 0, "info"	=>	"Error: " . $res['description']);
//        else
//            $result = Array("success" => 1,	"info"	=>	"Keyboard show");
//
//        return $result;
//    }
//
//    /**
//     * Auswahl Keyboard ausblenden
//     *
//     * <b>Output:</b><br>
//     * <code>
//     *  Array
//     *  (
//     *      [success] => 1 oder 0
//     *      [info]	=> Zeigt Info oder Fehlermeldung
//     *  )
//     * </code>
//     *
//     * @param	string	$chat_id	required	ID des Telegram Chats
//     * @param	string	$text		required	Text der gesendet werden soll
//     * @return	array
//     * @access	public
//     */
//    public function hideKeyboard($chat_id, $text)
//    {
//        $action = 'sendMessage';
//        $param = array(
//            'chat_id'		=>	$chat_id,
//            'reply_markup'	=>	json_encode(array("hide_keyboard" => true)),
//            'text'			=>	$text
//        );
//
//        $res = $this->send($action, $param);
//        if (!$res['ok'])
//            $result = Array("success" => 0, "info"	=>	"Error: " . $res['description']);
//        else
//            $result = Array("success" => 1,	"info"	=>	"Keyboard hide");
//
//        return $result;
//    }
//
//    /**
//     * Webhook setzen
//     *
//     * <b>Output:</b><br>
//     * <code>
//     *  Array
//     *  (
//     *      [success] => 1 oder 0
//     *      [info]	=> Zeigt Info oder Fehlermeldung
//     *  )
//     * </code>
//     *
//     * @param	string	$url	required	URL zu der Datei mit der der Telegram Bot verbunden werden soll
//     * @return	array
//     * @access	public
//     */
//    public function setWebhook($url = NULL)
//    {
//        $result = Array();
//
//        if (empty($url))
//            $result = Array("success" => 0, "info" => "Keine gültige URL angegeben");
//        else
//        {
//            $url .= "?sender=telegram";
//            $res = $this->send('setWebhook', array('url' => $url));
//            if (!$res['ok'])
//                $result = Array("success" => 0, "info" => "Webhook was not set! Error: " . $res['description']);
//            else
//                $result = Array("success" => 1, "info"	=>	$res['description']);
//        }
//
//        return $result;
//    }
//
//    /**
//     * Webhook löschen
//     *
//     * <b>Output:</b><br>
//     * <code>
//     *  Array
//     *  (
//     *      [success] => 1 oder 0
//     *      [info]	=> Zeigt Info oder Fehlermeldung
//     *  )
//     * </code>
//     *
//     * @return	array
//     * @access	public
//     */
//    public function delWebhook()
//    {
//        $result = Array();
//
//        $res = $this->send('setWebhook');
//        if (!$res['ok'])
//            $result = Array("success" => 0, "info" => "Webhook was not delete! Error: " . $res['description']);
//        else
//            $result = Array("success" => 1, "info"	=>	$res['description']);
//
//
//        return $result;
//    }
//
//    /**
//     * create curl file
//     *
//     * @param string $fileName
//     * @return string
//     */
//    private function curlFile($fileName)
//    {
//        $filename = realpath($fileName);
//
//        if (!is_file($filename))
//            throw new Exception('File does not exists');
//
//        if (function_exists('curl_file_create'))
//            return curl_file_create($filename);
//        return "@$filename";
//    }
}

?>