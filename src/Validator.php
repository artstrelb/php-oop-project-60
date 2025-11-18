<?php

namespace Hexlet\Validator;

class Validator
{
    private mixed $validators;

    public function __construct()
    {
        $this->validators = [];
    }

    public function string(): StringSchema
    {
        $validators = array_key_exists('string', $this->validators) ? $this->validators['string'] : [];

        return new StringSchema($validators);
    }

    public function number(): NumberSchema
    {
        $validators = array_key_exists('number', $this->validators) ? $this->validators['number'] : [];

        return new NumberSchema($validators);
    }

    public function array(): ArraySchema
    {
        $validators = array_key_exists('array', $this->validators) ? $this->validators['array'] : [];

        return new ArraySchema($validators);
    }

    public function addValidator(string $type, string $name, mixed $fn): self
    {
        if (!array_key_exists($type, $this->validators)) {
            $this->validators[$type] = [];
        }

        $this->validators[$type][$name] = $fn;

        return $this;
    }
}
