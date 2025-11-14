<?php

namespace Hexlet\Validator;

class StringSchema
{
    private bool $required;
    private int $minLength;
    private string $contains;

    public function __construct()
    {
        $this->required = false;
        $this->minLength = 0;
        $this->contains = '';
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
            if (empty($str)) {
                return false;
            }
        }
        if ($this->minLength) {
            if (!empty($str) && mb_strlen($str) < $this->minLength) {
                return false;
            }
        }
        if (!empty($this->contains)) {
            if (empty($str) || !str_contains($str, $this->contains)) {
                return false;
            }
        }

        return true;
    }
}
