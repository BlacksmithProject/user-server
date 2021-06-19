<?php
declare(strict_types=1);

namespace App\Tests\Unit\Http\ConstraintRecipe;

use App\Infrastructure\Http\ConstraintRecipe\SymfonyRequestValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SymfonyRequestValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideData
     */
    public function ok(string $case, array $constraintViolations, ?array $expectedMessage): void
    {
        $constraintViolationList = new ConstraintViolationList($constraintViolations);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('validate')->willReturn($constraintViolationList);

        $requestValidator = new SymfonyRequestValidator($validator);

        $message = $requestValidator->validate([], new NotNull());

        $this->assertSame($expectedMessage, $message, $case);
    }

    public function provideData(): array
    {
        return [
            [
                'case' => '0 violation',
                'data' => [],
                'expectedMessage' => null,
            ],
            [
                'case' => '1 violation',
                'data' => [
                    new ConstraintViolation(
                        '$message',
                        null,
                        [],
                        '$root',
                        '$propertyPath',
                        '$invalidValue'
                    ),
                ],
                'expectedMessage' => [
                    '$propertyPath' => '$message',
                ],
            ],
            [
                'case' => '2 violations',
                'data' => [
                    new ConstraintViolation(
                        '$message',
                        null,
                        [],
                        '$root',
                        '$propertyPath',
                        '$invalidValue'
                    ),
                    new ConstraintViolation(
                        '$message2',
                        null,
                        [],
                        '$root',
                        '$propertyPath2',
                        '$invalidValue'
                    ),
                ],
                'expectedMessage' => [
                    '$propertyPath' => '$message',
                    '$propertyPath2' => '$message2',
                ],
            ],
        ];
    }
}
