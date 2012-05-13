<?php

    class DI {
    
        private static $map;
    
        public static function getInstanceOf($className, $arguments = null) {
        
            // checking if the class exists
            if(!class_exists($className)) {
                throw new Exception("DI: missing class '".$className."'.");
            }
            
            // initialized the ReflectionClass
            $reflection = new ReflectionClass($className);
            
            // creating an instance of the class
            if($arguments === null || count($arguments) == 0) {
               $obj = new $className;
            } else {
                if(!is_array($arguments)) {
                    $arguments = array($arguments);
                }
               $obj = $reflection->newInstanceArgs($arguments);
            }
            
            // injecting
            if($doc = $reflection->getDocComment()) {
                $lines = explode("\n", $doc);
                foreach($lines as $line) {
                    if(count($parts = explode("@Inject", $line)) > 1) {
                        $parts = explode(" ", $parts[1]);
                        if(count($parts) > 1) {
                            $key = $parts[1];
                            $key = str_replace("\n", "", $key);
                            $key = str_replace("\r", "", $key);
                            if(isset(self::$map->$key)) {
                                switch(self::$map->$key->type) {
                                    case "value":
                                        $obj->$key = self::$map->$key->value;
                                    break;
                                    case "class":
                                        $obj->$key = self::getInstanceOf(self::$map->$key->value, self::$map->$key->arguments);
                                    break;
                                    case "classSingleton":
                                        if(self::$map->$key->instance === null) {
                                            $obj->$key = self::$map->$key->instance = self::getInstanceOf(self::$map->$key->value, self::$map->$key->arguments);
                                        } else {
                                            $obj->$key = self::$map->$key->instance;
                                        }
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            
            // return the created instance
            return $obj;
            
        }
        public static function mapValue($key, $value) {
            self::addToMap($key, (object) array(
                "value" => $value,
                "type" => "value"
            ));
        }
        public static function mapClass($key, $value, $arguments = null) {
            self::addToMap($key, (object) array(
                "value" => $value,
                "type" => "class",
                "arguments" => $arguments
            ));
        }
        public static function mapClassAsSingleton($key, $value, $arguments = null) {
            self::addToMap($key, (object) array(
                "value" => $value,
                "type" => "classSingleton",
                "instance" => null,
                "arguments" => $arguments
            ));
        }
        private static function addToMap($key, $obj) {
            if(self::$map === null) {
                self::$map = (object) array();
            }
            self::$map->$key = $obj;
        }
    
    }

?>