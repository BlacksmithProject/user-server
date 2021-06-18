<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\ConstraintRecipe;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SymfonyRequestValidator implements RequestValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $data, Constraint $constraint): ?array
    {
        $errors = $this->validator->validate($data, $constraint);

        if ($errors->count() === 0) {
            return null;
        }

        $message = [];
        /** @var ConstraintViolationInterface $error */
        foreach ($errors as $error) {
            $message[trim($error->getPropertyPath(), "[]")] = $error->getMessage();
        }

        return $message;
    }
}
