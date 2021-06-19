<?php
declare(strict_types=1);

namespace App\Tests\Integration;

use App\Tests\Helper\TestConnectionProvider;
use App\Tests\Unit\Adapter\Fake\FakeUserProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RegistrationHttpEndpointTest extends WebTestCase
{
    use TestConnectionProvider;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    /** @test */
    public function a_user_is_properly_registered(): void
    {
        // WHEN
        $this->client->request('POST', '/users', [
            'email' => 'daenerys.targaryen@dragon.stone',
            'password' => 'fireAndBlood',
        ]);

        // THEN
        $this->assertSame(201, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $responseContent);
        $this->assertSame(['id' => 'fake-identifier'], $responseContent);
    }

    /**
     * @test
     */
    public function a_bad_request_response_is_sent_when_a_parameters_are_invalid(): void
    {
        // WHEN
        $this->client->request('POST', '/users', []);

        // THEN
        $this->assertSame(400, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function a_bad_request_response_is_sent_when_email_is_invalid(): void
    {
        // WHEN
        $this->client->request('POST', '/users', [
            'email' => 'invalid',
            'password' => 'winterIsComing',
        ]);

        // THEN
        $this->assertSame(400, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function a_bad_request_response_is_sent_when_password_is_invalid(): void
    {
        // WHEN
        $this->client->request('POST', '/users', [
            'email' => 'eddard.stark@winterfell.north',
            'password' => '1',
        ]);

        // THEN
        $this->assertSame(400, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function an_unprocessable_entity_response_is_sent_when_registration_cannot_register_user(): void
    {
        // GIVEN
        $this->client->request('POST', '/users', [
            'email' => 'daenerys.targaryen@dragon.stone',
            'password' => 'fireAndBlood',
        ]);

        // WHEN
        $this->client->request('POST', '/users', [
            'email' => 'daenerys.targaryen@dragon.stone',
            'password' => 'fireAndBlood',
        ]);

        // THEN
        $this->assertSame(422, $this->client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('error',json_decode($this->client->getResponse()->getContent(), true));
    }

    /**
     * @test
     */
    public function an_internal_server_error_response_is_sent_when_a_service_is_unavailble(): void
    {
        $container = self::getContainer();
        /** @var FakeUserProvider $fakeUserProvider */
        $fakeUserProvider = $container->get('App\Domain\Port\UserProvider');
        $fakeUserProvider->makeItUnavailable();

        // WHEN
        $this->client->request('POST', '/users', [
            'email' => 'daenerys.targaryen@dragon.stone',
            'password' => 'fireAndBlood',
        ]);

        // THEN
        $this->assertSame(500, $this->client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('error',json_decode($this->client->getResponse()->getContent(), true));
    }
}
