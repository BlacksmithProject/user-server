<?php
declare(strict_types=1);

namespace App\Tests\Helper;

use App\Infrastructure\Http\ConstraintRecipe\ConstraintRecipe;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\ValidatorBuilder;

trait ConstraintRecipeValidator
{
    protected function validateData(array $data, ConstraintRecipe $constraintRecipe): ConstraintViolationListInterface
    {
        $validatorBuilder = new ValidatorBuilder();
        $validator = $validatorBuilder->getValidator();

        return $validator->validate($data, $constraintRecipe->getConstraint());
    }
}
