<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\ConstraintRecipe;

use App\Domain\ValueObject\PlainPassword;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

final class RegistrationEndpointConstraintRecipe implements ConstraintRecipe
{
    public function getConstraint(): Constraint
    {
        return new Collection([
            'fields' => [
                'email' => [
                    new NotNull(),
                    new Email(),
                ],
                'password' => [
                    new NotNull(),
                    new Type('string'),
                    new Length([
                        'min' => PlainPassword::MINIMUM_LENGTH,
                    ]),
                ],
            ],
        ]);
    }
}
