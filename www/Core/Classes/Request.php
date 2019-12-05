<?php

/**
 * Class Request
 */
final class Request
{
    public $_request;
    public $_parsed_request;
    public $chat_id;
    public $text;
    public $user_first_name;
    public $user_last_name;
    public $from;
    public $username;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $parsed_request = $this->parse_request();
        $this->_request = $parsed_request;
        $this->chat_id = $parsed_request["message"]["chat"]["id"];
        $this->text = $parsed_request["message"]["text"];
        $this->user_first_name = $parsed_request['message']['from']['first_name'];
        $this->user_last_name = $parsed_request['message']['from']['last_name'];//message->from->id;
        $this->from = $parsed_request['message']['from']['id'];//message->from->id;
        $this->username = $parsed_request['message']['from']['username'];//message->from->id;
    }


    /**
     * @return array
     */
    public function parse_request()
    {
        $rawData = file_get_contents('php://input'); # Get JSON as a string
        $this->_parsed_request = json_decode($rawData, true);
        return $this->_parsed_request;
    }

    /**
     * @param $chat_id
     * @param $text
     * @param null $reply_to_message_id
     * @param string $parse_mode
     * @param bool $disable_web_page_preview
     * @param bool $disable_notification
     * @return array
     */
    public function send_message($chat_id,
                                 $text,
                                 $reply_to_message_id = null,
                                 $parse_mode = 'HTML',
                                 $disable_web_page_preview = false,
                                 $disable_notification = false)
    {
        global $request, $logger;

        $action = 'sendMessage';
        $param = [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => $parse_mode,
            'disable_web_page_preview' => $disable_web_page_preview,
            'disable_notification' => $disable_notification,
            'reply_to_message_id' => $reply_to_message_id
        ];

        $res = $this->connect($action, $param)->result;

        $logger->info([
                'success' => 1,
                'user_id' => $this->chat_id,
                'type' => 'send_message',
                'title' => 'Send Message Action',
                'more_info' => 'more info : ' . $text . ' for this request : ' . $request->text,
            ]
        );

        return !$res['ok'] ?
            $result = Array("success" => 0, "info" => "Error: " . $res['description']) :
            $result = Array("success" => 1, "info" => "Message send");
    }


    /**
     * @param $method
     * @param array $dates
     * @return mixed
     */
    function connect($method, $dates = [])
    {
        global $config, $logger;

        $url = "https://api.telegram.org/bot" . $config['bot_token'] . "/" . ucfirst($method);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dates));
        $res = curl_exec($ch);

        if (curl_error($ch)) {
            var_dump(curl_error($ch));
            $logger->error([
                'success' => 0,
                'user_id' => $this->chat_id,
                'type' => 'connect',
                'title' => 'connect Action',
                'more_info' => 'request to telegram api',
            ]);
        } else {
            $logger->debug([
                'success' => 1,
                'user_id' => $this->chat_id,
                'type' => 'connect',
                'title' => 'connect Action',
                'more_info' => 'request to telegram api',
            ]);

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


        !$res['ok'] ?
            $logger->debug([
                'success' => 0,
                'user_id' => $this->chat_id,
                'type' => 'forward_message',
                'title' => 'Forward Message Action',
                'more_info' => 'description : ' . $res['description'] . ' for this request : ' . $request->text,
            ]) :
            $logger->debug([
                'success' => 1,
                'user_id' => $this->chat_id,
                'type' => 'forward_message',
                'title' => 'Forward Message Action',
                'more_info' => 'description : ' . $res['description'] . ' for this request : ' . $request->text,
            ]);
    }
}
