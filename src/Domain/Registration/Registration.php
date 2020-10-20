<?php

declare(strict_types=1);

namespace App\Domain\Registration;

use App\Domain\Registration\IO\Input;
use App\Domain\Registration\IO\Output;
use App\Domain\Registration\Model\User;
use App\Domain\Registration\Port\ActivationCodeGenerator;
use App\Domain\Registration\Port\UserProvider;
use App\Domain\Registration\Port\UserRepository;
use App\Domain\Registration\ValueObject\ActivationCode;

final class Registration
{
    private UserProvider $provider;
    private UserRepository $repository;
    private ActivationCodeGenerator $activationCodeGenerator;

    public function __construct(
        UserProvider $provider,
        UserRepository $repository,
        ActivationCodeGenerator $activationCodeGenerator
    ) {
        $this->repository = $repository;
        $this->provider = $provider;
        $this->activationCodeGenerator = $activationCodeGenerator;
    }

    /**
     * @throws Exception\EmailIsAlreadyUsed
     * @throws Exception\EmailIsNotValid
     * @throws Exception\PasswordIsTooShort
     */
    public function __invoke(Input $input): Output
    {
        if ($this->provider->isEmailAlreadyUsed($input->getEmail())) {
            throw new Exception\EmailIsAlreadyUsed();
        }

        $uuid = $this->repository->nextId();
        $code = $this->activationCodeGenerator->generate();

        $user = new User($uuid, new ActivationCode($code), $input->getEmail(), $input->getPassword());
        $this->repository->save($user);

        try {
            $readUser = $this->provider->byUuid($uuid);
        } catch (Exception\UserCouldNotBeFound $exception) {
            $message = 'User could not be found right after being registered';
            throw new \RuntimeException($message);
        }

        return Output::fromUser($readUser);
    }
}
