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
}
