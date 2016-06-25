<?php

namespace GoogleApiTaskBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GoogleControllerTest extends WebTestCase
{
    public function testCallback() {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/google/callback');
        $this->assertTrue($crawler->filter('html:contains("Lists")')->count() > 0);
    }
    public function testCallbackError() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/google/oauthcallback?error=test');
        $this->assertTrue($crawler->filter('html:contains("You don\'t have access to the application")')->count() > 0);
    }
}