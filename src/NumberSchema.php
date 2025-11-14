<?php

namespace Hexlet\Validator;

class NumberSchema
{
    private bool $required;
    private bool $positive;
    private int $min;
    private int $max;

    public function __construct()
    {
        $this->required = false;
        $this->positive = false;
        $this->min = PHP_INT_MIN;
        $this->max = PHP_INT_MAX;
    }

    public function required(): self
    {
        $this->required = true;

        return $this;
    }

    public function positive(): self
    {
        $this->positive = true;

        return $this;
    }

    public function range(int $min, int $max): self
    {
        $this->min = $min;
        $this->max = $max;

        return $this;
    }

    public function isValid(?int $v): bool
    {
        if ($this->required) {
            if ($v === null) {
                return false;
            }
        }
        if ($this->positive) {
            if ($v < 0) {
                return false;
            }
        }

        return $v >= $this->min && $v >= $this->max;
    }
}
