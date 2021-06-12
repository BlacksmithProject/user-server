<?php
declare(strict_types=1);

namespace App\UseCase;

use App\Domain\Exception\CannotRegisterUser;
use App\Domain\Exception\FailedToEncodePassword;
use App\Domain\Exception\ServiceIsNotAccessible;
use App\Domain\Exception\UserNotFound;
use App\Domain\Model\UserToRegister;
use App\Domain\Port\IdentifierGenerator;
use App\Domain\Port\UserProvider;
use App\Domain\Port\UserRepository;
use App\Domain\ReadModel\RegisteredUser;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\PlainPassword;

final class Registration
{
    private UserProvider $userProvider;
    private UserRepository $userRepository;
    private IdentifierGenerator $identifierGenerator;

    public function __construct(
        UserProvider $userProvider,
        UserRepository $userRepository,
        IdentifierGenerator $identifierGenerator
    ) {
        $this->userProvider = $userProvider;
        $this->userRepository = $userRepository;
        $this->identifierGenerator = $identifierGenerator;
    }

    /**
     * @throws CannotRegisterUser
     * @throws ServiceIsNotAccessible
     */
    public function register(Email $email, PlainPassword $plainPassword): RegisteredUser
    {
        if ($this->userProvider->isEmailAlreadyUsed($email)) {
            throw CannotRegisterUser::becauseEmailIsAlreadyUsed($email->value());
        }

        try {
            $userToRegister = new UserToRegister($this->identifierGenerator->generate(), $email, $plainPassword);
        } catch (FailedToEncodePassword $failedToEncodePassword) {
            throw CannotRegisterUser::becausePasswordFailedToBeEncoded();
        }

        $this->userRepository->add($userToRegister);

        try {
            return $this->userProvider->getByEmail($email);
        } catch (UserNotFound $exception) {
            // should never happen, since we added user just before.
            throw new ServiceIsNotAccessible(UserProvider::class);
        }
    }
}
