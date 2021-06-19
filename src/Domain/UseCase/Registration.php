<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Exception\CannotRegisterUser;
use App\Domain\Exception\FailedToEncodePassword;
use App\Domain\Exception\ServiceIsNotAccessible;
use App\Domain\Exception\UserIsAlreadyInStorage;
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
        try {
            if ($this->userProvider->isEmailAlreadyUsed($email)) {
                throw CannotRegisterUser::becauseEmailIsAlreadyUsed($email->value());
            }

            $userToRegister = new UserToRegister($this->identifierGenerator->generate(), $email, $plainPassword);
            $this->userRepository->add($userToRegister);

            return $this->userProvider->getByEmail($email);
        } catch (FailedToEncodePassword $exception) {
            throw CannotRegisterUser::becausePasswordFailedToBeEncoded();
        } catch (UserIsAlreadyInStorage $exception) {
            throw new ServiceIsNotAccessible(UserRepository::class); // should never happen, since we verified it before.
        } catch (UserNotFound $exception) {
            throw new ServiceIsNotAccessible(UserProvider::class); // should never happen, since we added user just before.
        }
    }
}
