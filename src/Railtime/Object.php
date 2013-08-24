<?php

namespace Railtime;

/**
 * Abstract class providing utility functions
 */
abstract class Object {
    /**
     * Create an instance of the class (and fill properties)
     *
     * @param array $properties
     * @return self
     * @throws \Exception
     */
    public static function create(array $properties = array()) {
        $klass = get_called_class();
        $object = new $klass;
        foreach ($properties as $name => $value) {
            if (!property_exists($klass, $name)) {
                throw new \Exception("The property {$name} does not exist in the class {$klass}");
            }
            $object->{$name} = $value;
        }
        return $object;
    }
}
