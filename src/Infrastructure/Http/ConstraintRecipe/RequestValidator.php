<?php

namespace App\Infrastructure\Http\ConstraintRecipe;

use Symfony\Component\Validator\Constraint;

interface RequestValidator
{
    public function validate(array $data, Constraint $constraint): ?array;
}
