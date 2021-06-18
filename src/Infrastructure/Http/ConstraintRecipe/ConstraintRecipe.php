<?php

namespace App\Infrastructure\Http\ConstraintRecipe;

use Symfony\Component\Validator\Constraint;

interface ConstraintRecipe
{
    public function getConstraint(): Constraint;
}
