<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 19/2/18
 * Time: 11:52 AM
 */

namespace AppBundle\Services\Extension\Touple;

class Tuple extends \SplFixedArray {

    protected $prototype;

    public function __construct(array $prototype, array $data = [])
    {
        parent::__construct(count($prototype));

        $this->prototype = $prototype;

        foreach ($data as $offset => $value) {
            $this->offsetSet($offset, $value);
        }
    }

    public function offsetSet($offset, $value)
    {
        if ( ! $this->isValid($offset, $value)) {
            throw new RuntimeException;
        }

        return parent::offsetSet($offset, $value);
    }

    protected function isValid($offset, $value)
    {
        $type = $this->prototype[$offset];

        if ($type === 'mixed' || gettype($value) === $type || $value instanceof $type) {
            return true;
        }

        return false;
    }

    public function __toString()
    {
        return get_class($this) . '(' . implode(', ', $this->toArray()) . ')';
    }

    public static function create(/* $prototype... */)
    {
        $prototype = func_get_args();

        return function() use ($prototype)
        {
            return new static($prototype, func_get_args());
        };
    }

    public static function type($name, array $prototype)
    {
        if (class_exists($name) || function_exists($name)) {
            throw new RuntimeException;
        }

        $eval = sprintf(
            'class %s extends Tuple { ' .
            'public function __construct(array $data) { ' .
            'return parent::__construct(%s, $data); ' .
            '}' .
            '}',
            $name, "['" . implode("','", $prototype) . "']"
        );

        $eval .= sprintf(
            'function %s() { return new %s(func_get_args()); }',
            $name, $name
        );

        eval($eval);
    }

}