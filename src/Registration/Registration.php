<?php
declare(strict_types=1);

namespace App\Registration;

use App\Registration\IO\Input;
use App\Registration\IO\Output;
use App\Registration\Port\ActivationCodeGenerator;
use App\Registration\Port\UserProvider;
use App\Registration\Port\UserRepository;
use App\Registration\Model\User;

class Registration
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

        $user = new User($uuid, $input->getEmail(), $input->getPassword());
        $this->repository->save($user);

        try {
            $readUser = $this->provider->byUuid($uuid);
        } catch (Exception\UserCouldNotBeFound $e) {
            throw new \RuntimeException('User could not be found right after being registered');
        }

        $activationCode = $this->activationCodeGenerator->generateForUser($uuid);

        return Output::fromUserAndActivationCode($readUser, $activationCode);
    }
}
