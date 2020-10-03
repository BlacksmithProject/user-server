<?php
declare(strict_types=1);

namespace App\Registration;

use App\Registration\IO\Input;
use App\Registration\IO\Output;
use App\Registration\Port\UserProvider;
use App\Registration\Port\UserRepository;
use App\Registration\Model\User;

class Registration
{
    private UserRepository $repository;
    private UserProvider $provider;

    public function __construct(UserRepository $repository, UserProvider $provider)
    {
        $this->repository = $repository;
        $this->provider = $provider;
    }

    public function __invoke(Input $input): Output
    {
        if ($this->provider->isEmailAlreadyUsed($input->getEmail())) {
            return Output::fromMessage('email is already used');
        }

        $uuid = $this->repository->nextId();

        try {
            $user = new User($uuid, $input->getEmail(), $input->getPassword());
            $this->repository->save($user);
        } catch (Exception\EmailIsNotValid $e) {
            return Output::fromMessage('email is not valid');
        } catch (Exception\PasswordIsTooShort $e) {
            return Output::fromMessage('password is too short');
        }

        try {
            $readUser = $this->provider->byUuid($uuid);
        } catch (Exception\UserCouldNotBeFound $e) {
            throw new \RuntimeException('User could not be found right after being registered');
        }

        return Output::fromUser($readUser);
    }
}
