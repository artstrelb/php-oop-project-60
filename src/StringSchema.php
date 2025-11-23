<?php

namespace Hexlet\Validator;

class StringSchema
{
    private bool $required;
    private int $minLength;
    private string $contains;
    private mixed $validators;
    private mixed $fn;

    public function __construct(mixed $validators)
    {
        $this->required = false;
        $this->minLength = 0;
        $this->contains = '';
        $this->validators = $validators;
        $this->fn = null;
    }

    public function required(): self
    {
        $this->required = true;

        return $this;
    }

    public function minLength(int $value): self
    {
        $this->minLength = $value;

        return $this;
    }

    public function contains(string $str): self
    {
        $this->contains = $str;

        return $this;
    }

    public function isValid(?string $str): bool
    {
        if ($this->required) {
            if ($str === null || $str === '') {
                return false;
            }
        }
        if ($this->minLength !== 0) {
            if ($str !== null && mb_strlen($str) < $this->minLength) {
                return false;
            }
        }
        if ($this->contains !== '') {
            if ($str === null || !str_contains($str, $this->contains)) {
                return false;
            }
        }
        if (is_callable($this->fn)) {
            $fn = $this->fn;
            return $fn($str);
        }

        return true;
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
