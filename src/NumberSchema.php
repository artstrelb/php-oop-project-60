<?php

namespace Hexlet\Validator;

class NumberSchema
{
    private bool $required;
    private bool $positive;
    private int $min;
    private int $max;
    private mixed $validators;
    private mixed $fn;

    public function __construct(mixed $validators)
    {
        $this->required = false;
        $this->positive = false;
        $this->min = PHP_INT_MIN;
        $this->max = PHP_INT_MAX;
        $this->validators = $validators;
        $this->fn = null;
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
        if (is_callable($this->fn)) {
            $fn = $this->fn;
            return $fn($v);
        }
        if (is_null($v)) {
            return true;
        }

        return $v >= $this->min && $v <= $this->max;
    }

    public function test(string $name, mixed $value): self
    {
        $fn = $this->validators[$name];

        $this->fn = function ($testValue) use ($fn, $value) {
            return $fn($testValue, $value);
        };

        return $this;
    }
}
