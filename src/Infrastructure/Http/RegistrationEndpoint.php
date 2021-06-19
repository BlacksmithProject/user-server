<?php
declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Domain\Exception\CannotRegisterUser;
use App\Domain\Exception\EmailIsInvalid;
use App\Domain\Exception\PlainPasswordIsInvalid;
use App\Domain\Exception\ServiceIsNotAccessible;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\PlainPassword;
use App\Infrastructure\Http\ConstraintRecipe\ConstraintRecipe;
use App\Infrastructure\Http\Helper\ErrorJsonResponse;
use App\Infrastructure\Http\ConstraintRecipe\RequestValidator;
use App\Domain\UseCase\Registration;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RegistrationEndpoint
{
    private Registration $registration;
    private RequestValidator $validator;
    private ConstraintRecipe $endpointConstraintRecipe;

    public function __construct(
        Registration $registration,
        RequestValidator $validator,
        ConstraintRecipe $endpointConstraintRecipe
    ) {
        $this->registration = $registration;
        $this->validator = $validator;
        $this->endpointConstraintRecipe = $endpointConstraintRecipe;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $errors = $this->validator->validate($request->request->all(), $this->endpointConstraintRecipe->getConstraint());

        if ($errors !== null) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->registration->register(
                new Email($request->request->get('email')),
                new PlainPassword($request->request->get('password'))
            );
        } catch (EmailIsInvalid|PlainPasswordIsInvalid $exception) {
            return ErrorJsonResponse::format($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (CannotRegisterUser $exception) {
            return ErrorJsonResponse::format($exception->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ServiceIsNotAccessible $exception) {
            return ErrorJsonResponse::format($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'id' => $user->externalIdentifier(),
        ], Response::HTTP_CREATED);
    }
}
