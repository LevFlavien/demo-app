<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use Symfony\Component\HttpFoundation\Response;

class WelcomeActionTest extends AbstractActionTest
{
    /**
     * @test
     */
    public function itRedirectsToTheProductsPageWhenTheAccessTokenIsSet(): void
    {
        $client = $this->getClientWithSession(['akeneo_pim_access_token' => 'random_token']);
        $client->request('GET', '/?pim_url=https://httpd');
        $this->assertResponseRedirects('/products', Response::HTTP_FOUND);
    }

    /**
     * @test
     */
    public function itThrowsAExceptionWhenThePimUrlIsMissing(): void
    {
        $client = $this->getClientWithSession([]);
        $client->request('GET', '/');
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function itSavesThePimUrlInSessionAndRenderTheWelcomePage(): void
    {
        $client = $this->getClientWithSession([]);
        $client->request('GET', '/?pim_url=https://httpd');
        $this->assertEquals('https://httpd', $client->getRequest()->getSession()->get('pim_url'));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Welcome');
    }
}
