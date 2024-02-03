<?
// test
//	Telegram bot class
//	Author: bps56@ya.ru
//	Date:	03.03.2024

class telegramClass
{
    
    private $token = '';
    private $chatID;
    private $mode;
    private $dataMode = [ 'text' => 'markdown', 'html' => 'html', 'markdown' => 'markdown' ];
    private $defaultMode = 'text';
    // mode =   html => <b>text<b/> (bold), <i>text<i/> (italic)
    // mode =   text,markdown => *text* (bold), _text_ (italic)

    public function __construct($chatID = '', $mode = '')
    {
        $this->setMode($mode);
        $this->setChat($chatID);
    }
    public function setMode($mode = '')
    {
        $mode = ($mode = trim(mb_strtolower($mode))) ? $mode : $this->defaultMode;
        $this->mode = ($this->dataMode[$mode]) ? $mode : $this->dataMode[$this->defaultMode];
    }
    public function setChat($chatID)
    {
        if ($chatID = trim($chatID)) $this->chatID = $chatID;
    }
    public function send($message, $mode = '')
    {
        if ($mode) $this->setMode($mode);
        if ($this->mode && $this->chatID && $message = trim($message)) {
            $ch = curl_init();
            curl_setopt_array(
                $ch,
                array(
                    CURLOPT_URL => 'https://api.telegram.org/bot' . $this->token . '/sendMessage',
                    CURLOPT_POST => TRUE,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_POSTFIELDS => array(
                        'chat_id' => $this->chatID,
                        'text' => $message,
                        'parse_mode' => $this->mode
                    ),
                )
            );
            $content = curl_exec($ch);
            $return = (curl_getinfo($ch, CURLINFO_RESPONSE_CODE) == 200) ? true : false;
            curl_close($ch);
            unset($message, $ch);
        }
        return $return = ($return) ? $return : false;
    }

    public function data_channel()
    {
        if ($this->token) {
            print_r(json_decode(file_get_contents("https://api.telegram.org/bot" . $this->token . "/getUpdates")));
        }
    }
}
