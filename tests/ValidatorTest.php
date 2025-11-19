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

        self::assertTrue($schema->isValid(''), 'Empty should be valid');
        self::assertTrue($schema->isValid(null), 'Null should be valid if not required');
        self::assertTrue($schema->isValid('what does the fox say'), 'String should be valid');

        $schema->required();

        self::assertTrue($schema2->isValid(''), 'Empty should be valid');
        self::assertFalse($schema->isValid(null), 'Null should not be valid when required');
        self::assertFalse($schema->isValid(''), 'Empty string should not be valid when required');

        self::assertTrue($schema2->isValid('hexlet'), 'String should be valid');

        $result = $schema->contains('what')->isValid('what does the fox say');
        self::assertTrue($result, 'Valid string');

        $result = $schema->contains('whatthe')->isValid('what does the fox say');
        self::assertFalse($result, 'Invalid string');

        $result = $v->string()->minLength(10)->minLength(5)->isValid('Hexlet');
        self::assertTrue($result, 'Valid string');
    }

    public function testNumber(): void
    {
        $v = new \Hexlet\Validator\Validator();

        $schema = $v->number();

        self::assertTrue($schema->isValid(null), 'Null should be valid');

        $schema->required();

        self::assertFalse($schema->isValid(null), 'Null should be invalid when required');

        self::assertTrue($schema->isValid(7), '7  should be valid');

        $result = $schema->positive()->isValid(10);
        self::assertTrue($result, '10 positive  should be valid');

        $schema->range(-5, 5);

        $result = $schema->isValid(-3);
        self::assertFalse($result, '-3 not positive');

        $result = $schema->isValid(5);
        self::assertTrue($result, '5 in [-5;5] and positive valid');
    }

    public function testArray(): void
    {
        $v = new \Hexlet\Validator\Validator();

        $schema = $v->array();

        self::assertTrue($schema->isValid(null), 'Null should be valid');

        $schema = $schema->required();

        self::assertTrue($schema->isValid([]), 'Empty array is valid');
        self::assertTrue($schema->isValid(['hexlet']), 'Array is valid');

        $schema->sizeof(2);

        self::assertFalse($schema->isValid(['hexlet']), 'Size not valid');
        self::assertTrue($schema->isValid(['hexlet', 'code-basics']), 'Array is valid size right');
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
        self::assertTrue($result, 'Array should be valid');

        $result = $schema->isValid(['name' => 'maya', 'age' => null]);
        self::assertTrue($result, 'Array should be valid');

        $result = $schema->isValid(['name' => '', 'age' => null]);
        self::assertFalse($result, 'Array should not be valid');

        $result = $schema->isValid(['name' => 'ada', 'age' => -5]);
        self::assertFalse($result, 'Array should not be valid');
    }

    public function customValidator(): void
    {
        $v = new \Hexlet\Validator\Validator();

        $fn = fn($value, $start) => str_starts_with($value, $start);

        $v->addValidator('string', 'startWith', $fn);

        // Новые валидаторы вызываются через метод test
        $schema = $v->string()->test('startWith', 'H');

        $result = $schema->isValid('exlet');
        self::assertFalse($result, 'exlet not start with H');

        $result = $schema->isValid('Hexlet');
        self::assertTrue($result, 'Hexlet start with H');

        $fn = fn($value, $min) => $value >= $min;
        $v->addValidator('number', 'min', $fn);

        $schema = $v->number()->test('min', 5);

        $result = $schema->isValid(4);
        self::assertFalse($result, '4 >= 5 is false');

        $result = $schema->isValid(6); // true
        self::assertTrue($result, '6 >= 5 is true');
    }
}
