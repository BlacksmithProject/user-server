<?php
declare(strict_types=1);

namespace App\Tests\Domain\UseCase;

use App\Domain\Exception\CannotRegisterUser;
use App\Domain\Exception\ServiceIsNotAccessible;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\PlainPassword;
use App\Tests\Domain\Adapter\Fake\FakeIdentifierGenerator;
use App\Tests\Domain\Adapter\Fake\FakeUserProvider;
use App\Tests\Domain\Adapter\Fake\FakeUserRepository;
use App\UseCase\Registration;
use PHPUnit\Framework\TestCase;

final class RegistrationTest extends TestCase
{
    private Registration $registration;

    protected function setUp(): void
    {
        FakeUserProvider::resetStorage();

        $this->registration = new Registration(
            new FakeUserProvider(),
            new FakeUserRepository(),
            new FakeIdentifierGenerator()
        );
    }

    /** @test */
    public function an_identifier_is_returned_when_a_new_user_is_registered(): void
    {
        // GIVEN
        $email = new Email('robb.stark@winterfell.north');
        $password = new PlainPassword('winterIsComing');

        // WHEN
        $user = $this->registration->register($email, $password);

        // THEN
        $this->assertSame('fake-identifier', $user->externalIdentifier());
    }

    /**
     * @test
     */
    public function an_exception_is_thrown_when_email_is_already_used(): void
    {
        // EXPECT
        $this->expectException(CannotRegisterUser::class);

        // GIVEN
        $email = new Email('robb.stark@winterfell.north');
        $password = new PlainPassword('winterIsComing');

        // WHEN
        $this->registration->register($email, $password);
        $this->registration->register($email, $password);
    }

    /**
     * @test
     * @dataProvider notAvailableServices
     */
    public function an_exception_is_thrown_when_a_service_is_not_available(callable $registration): void
    {
        // EXPECT
        $this->expectException(ServiceIsNotAccessible::class);

        // GIVEN
        $email = new Email('robb.stark@winterfell.north');
        $password = new PlainPassword('winterIsComing');

        // WHEN
        $registration()->register($email, $password);
    }

    public function notAvailableServices(): \Generator
    {
        yield "UserProvider is not available." => [
            function () {
                $userProvider = new FakeUserProvider();
                $userProvider->makeItUnavailable();

                return new Registration(
                    $userProvider,
                    new FakeUserRepository(),
                    new FakeIdentifierGenerator()
                );
            },
        ];

        yield "UserRepository is not available." => [
            function () {
                $userRepository = new FakeUserRepository();
                $userRepository->makeItUnavailable();

                return new Registration(
                    new FakeUserProvider(),
                    $userRepository,
                    new FakeIdentifierGenerator()
                );
            },
        ];
    }
}
