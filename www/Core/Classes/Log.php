<?php

use Medoo\Medoo;

/**
 * Class Log
 */
final class Log
{
    /**
     * @var
     */
    protected $database;

    /**
     * @var string
     */
    protected $table = 'logs';

    /**
     * @var array
     */
    protected $config;

    /**
     * Log constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->connect_db($config);
    }

    /**
     * @param $data
     * @return bool|string
     */
    protected function insert($data)
    {
        try {
            $this->database->insert($this->table, [
                'success' => $data['success'] ?? 0,
                'user_id' => $data['user_id'] ?? 'broken data',
                'type' => $data['type'] ?? 'broken data',
                'title' => $data['title'] ?? 'broken data',
                'more_info' => $data['more_info'] ?? 'broken data',
                'time' => date('Y-m-d H:i:s', time())
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * make db instance
     *
     * @param $config
     * return void
     */
    protected function connect_db($config)
    {
        $this->database = new Medoo($config);
    }

    public function debug($data)
    {
        $data['level'] = Level::DEBUG;
        $this->insert($data);
    }

    public function info($data)
    {
        $data['level'] = Level::INFO;
        $this->insert($data);
    }

    public function warning($data)
    {
        $data['level'] = Level::WARNING;
        $this->insert($data);
    }

    public function emergency($data)
    {
        $data['level'] = Level::EMERGENCY;
        $this->insert($data);
    }

    public function alert($data)
    {
        $data['level'] = Level::ALERT;
        $this->insert($data);
    }

    public function error($data)
    {
        $data['level'] = Level::ERROR;
        $this->insert($data);
    }
}
