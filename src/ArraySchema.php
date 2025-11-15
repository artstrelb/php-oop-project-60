<?php

namespace Hexlet\Validator;

class ArraySchema
{
    private bool $required;
    private int $size;
    private mixed $shape;

    public function __construct()
    {
        $this->required = false;
        $this->size = 0;
        $this->shape = [];
    }

    public function required(): self
    {
        $this->required = true;

        return $this;
    }

    public function sizeof(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function shape(mixed $params): self
    {
        $this->shape = $params;

        return $this;
    }

    public function isValid(mixed $v): bool
    {
        if ($this->required && !is_array($v)) {
            return false;
        }

        if ($this->size) {
            if (count($v) != $this->size) {
                return false;
            }
        }

        if ($this->shape) {
            foreach ($this->shape as $key => $schema) {
                if (!$schema->isValid($v[$key])) {
                    return false;
                }
            }
        }

        return true;
    }
}
