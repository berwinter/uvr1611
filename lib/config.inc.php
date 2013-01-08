<?php
class Config
{
    const DEFAULT_CONFIG_FILE = 'config/config.ini';
 
    public static $instance;

    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }
 
        return self::$instance;
    }
 
    private function __construct(array $options=array())
    {
        if (empty($options)) {
            $options = parse_ini_file(
            	self::DEFAULT_CONFIG_FILE,
                true
            );
        }
 
        $this->setConfig($options);
    }
 
    private function setConfig(array $options=array())
    {
        foreach ($options as $key => $value) {
 
            if (is_array($value) && !empty($value)) {
                $value = new self($value);
            }
 
            $this->$key = $value;
        }
 
        return $this;
    }
 

    public function __get($name)
    {
        throw new Exception('call to undefined property: ' . $name);
    }
}
    