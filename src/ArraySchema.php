<?php

namespace Hexlet\Validator;

class ArraySchema
{
    private bool $required;
    private int $size;
    private mixed $shape;
    private mixed $validators;
    private mixed $fn;

    public function __construct(mixed $validators)
    {
        $this->required = false;
        $this->size = 0;
        $this->shape = [];
        $this->validators = $validators;
        $this->fn = null;
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

        if ($this->size !== 0) {
            if (count($v) !== $this->size) {
                return false;
            }
        }

        foreach ($this->shape as $key => $schema) {
            if (!$schema->isValid($v[$key])) {
                return false;
            }
        }

        if (is_callable($this->fn)) {
            $fn = $this->fn;
            return $fn($v);
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
