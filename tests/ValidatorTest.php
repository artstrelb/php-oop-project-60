<?php

namespace Hexlet\Validator\Tests;

use PHPUnit\Framework\TestCase;
use Hexlet\Validator\Validator;

class ValidatorTest extends TestCase
{
    public function testString(): void
    {
        $v = new \Hexlet\Validator\Validator();

        $schema = $v->string();
        $schema2 = $v->string();

        $this->assertTrue($schema->isValid(''), 'Empty should be valid');
        $this->assertTrue($schema->isValid(null), 'Null should be valid if not required');
        $this->assertTrue($schema->isValid('what does the fox say'), 'String should be valid');

        $schema->required();

        $this->assertTrue($schema2->isValid(''), 'Empty should be valid');
        $this->assertFalse($schema->isValid(null), 'Null should not be valid when required');
        $this->assertFalse($schema->isValid(''), 'Empty string should not be valid when required');

        $this->assertTrue($schema2->isValid('hexlet'), 'String should be valid');

        $result = $schema->contains('what')->isValid('what does the fox say');
        $this->assertTrue($result, 'Valid string');

        $result = $schema->contains('whatthe')->isValid('what does the fox say');
        $this->assertFalse($result, 'Invalid string');

        $result = $v->string()->minLength(10)->minLength(5)->isValid('Hexlet');
        $this->assertTrue($result, 'Valid string');
    }

    public function testNumber(): void
    {
        $v = new \Hexlet\Validator\Validator();

        $schema = $v->number();

        $this->assertTrue($schema->isValid(null), 'Null should be valid');

        $schema->required();

        $this->assertFalse($schema->isValid(null), 'Null should be invalid when required');

        $this->assertTrue($schema->isValid(7), '7  should be valid');

        $result = $schema->positive()->isValid(10);
        $this->assertTrue($result, '10 positive  should be valid');

        $schema->range(-5, 5);

        $result = $schema->isValid(-3);
        $this->assertFalse($result, '-3 not positive');

        $result = $schema->isValid(5);
        $this->assertTrue($result, '5 in [-5;5] and positive valid');
    }

    public function testArray(): void
    {
        $v = new \Hexlet\Validator\Validator();

        $schema = $v->array();

        $this->assertTrue($schema->isValid(null), 'Null should be valid');

        $schema = $schema->required();

        $this->assertTrue($schema->isValid([]), 'Empty array is valid');
        $this->assertTrue($schema->isValid(['hexlet']), 'Array is valid');

        $schema->sizeof(2);

        $this->assertFalse($schema->isValid(['hexlet']), 'Size not valid');
        $this->assertTrue($schema->isValid(['hexlet', 'code-basics']), 'Array is valid size right');
    }

    public function testSchape(): void
    {
        $v = new \Hexlet\Validator\Validator();

        $schema = $v->array();

        $schema->shape([
            'name' => $v->string()->required(),
            'age' => $v->number()->positive(),
        ]);

        $result = $schema->isValid(['name' => 'kolya', 'age' => 100]);
        $this->assertTrue($result, 'Array should be valid');

        $result = $schema->isValid(['name' => 'maya', 'age' => null]);
        $this->assertTrue($result, 'Array should be valid');

        $result = $schema->isValid(['name' => '', 'age' => null]);
        $this->assertFalse($result, 'Array should not be valid');

        $result = $schema->isValid(['name' => 'ada', 'age' => -5]);
        $this->assertFalse($result, 'Array should not be valid');
    }
}
