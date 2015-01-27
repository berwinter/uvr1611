<?php
/**
 * Configuration Class of the Application (Singleton)
 *
 * Reads the configuration from an ini file and provides the values as properties
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */

class Config
{
	/**
	 * Config location constant
	 */
//    const DEFAULT_CONFIG_FILE = "C:/Users/01111635/Documents/MyData/Netzwerk/Heimnetzwerk/Repositorys/myUvr1611DataLogger/trunk/config/config.ini";
    const DEFAULT_CONFIG_FILE = "config/config.ini";
 
    /**
     * Singleton Interface
     */
    public static $instance;
    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }
 
    /**
     * Constructor
     * @param array $options predefined options
     */
    private function __construct(array $options=array())
    {
        if (empty($options)) {
            $options = parse_ini_file(self::DEFAULT_CONFIG_FILE,true);
        }
        $this->setConfig($options);
    }
 
    /**
     * Sets a array of confif options as properties of this class
     * @param array $options
     * @return Config
     */
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
 
	/**
	 * Provides access to the configuration properties
	 * @param string $name Property name
	 * @throws Exception Property not found
	 */
    public function __get($name)
    {
        throw new Exception('call to undefined property: '.$name);
    }
}
    