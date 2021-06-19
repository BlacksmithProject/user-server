<?php
declare(strict_types=1);

namespace App\Tests\Unit\Http\ConstraintRecipe;

use App\Infrastructure\Http\ConstraintRecipe\RegistrationEndpointConstraintRecipe;
use App\Tests\Helper\ConstraintRecipeValidator;
use PHPUnit\Framework\TestCase;

final class RegistrationEndpointContraintRecipeTest extends TestCase
{
    use ConstraintRecipeValidator;

    /** @test */
    public function ok(): void
    {
        // GIVEN
        $data = [
            'email' => 'catelyn.stark@winterfell.north',
            'password' => 'winterIsComing',
        ];

        // WHEN
        $constraintViolationList = $this->validateData($data, new RegistrationEndpointConstraintRecipe());

        // THEN
        $this->assertSame(0, $constraintViolationList->count());
    }

    /**
     * @test
     * @dataProvider provideDataForNok
     */
    public function nok(string $case, array $data, int $expectedCount): void
    {
        // WHEN
        $constraintViolationList = $this->validateData($data, new RegistrationEndpointConstraintRecipe());

        // THEN
        $this->assertSame($expectedCount, $constraintViolationList->count(), $case);
    }

    public function provideDataForNok(): array
    {
        return [
            [
                'case' => 'email and password cannot be missing',
                'data' => [],
                'expectedCount' => 2,
            ],
            [
                'case' => 'email cannot be missing',
                'data' => ['password' => 'winterIsComing'],
                'expectedCount' => 1,
            ],
            [
                'case' => 'email cannot be null',
                'data' => [
                    'email' => null,
                    'password' => 'winterIsComing',
                ],
                'expectedCount' => 1,
            ],
            [
                'case' => 'email must be valid',
                'data' => [
                    'email' => 'not an email',
                    'password' => 'winterIsComing',
                ],
                'expectedCount' => 1,
            ],
            [
                'case' => 'password cannot be missing',
                'data' => ['email' => 'catelyn.stark@winterfell.north'],
                'expectedCount' => 1,
            ],
            [
                'case' => 'password must be a string',
                'data' => [
                    'email' => 'catelyn.stark@winterfell.north',
                    'password' => 123456,
                ],
                'expectedCount' => 1,
            ],
            [
                'case' => 'password cannot be null',
                'data' => [
                    'email' => 'catelyn.stark@winterfell.north',
                    'password' => null,
                ],
                'expectedCount' => 1,
            ],
            [
                'case' => 'password cannot be blank',
                'data' => [
                    'email' => 'catelyn.stark@winterfell.north',
                    'password' => '',
                ],
                'expectedCount' => 1,
            ],
            [
                'case' => 'password length cannot be less than 6',
                'data' => [
                    'email' => 'catelyn.stark@winterfell.north',
                    'password' => '12345',
                ],
                'expectedCount' => 1,
            ],
        ];
    }
}
