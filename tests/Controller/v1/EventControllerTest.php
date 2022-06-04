<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional test for the controllers defined inside BlogController.
 *
 * See https://symfony.com/doc/current/testing.html#functional-tests
 *
 * Execute the application tests using this command (requires PHPUnit to be installed):
 *
 *     $ cd your-symfony-project/
 *     $ ./vendor/bin/phpunit
 */
class EventControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $limit = 5;

        $client = static::createClient();
        $client->request('GET', "/v1/events?limit=$limit");

        $this->assertResponseIsSuccessful();
        $results = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount($limit, $results);
        foreach ($results as $event) {
            $this->assertNotEmpty($event['details']);
            $this->assertNotEmpty($event['type']);
            $this->assertNotEmpty($event['created_at']);
        }
    }

    public function testFilter(): void
    {
        $limit = 5;
        $type = 'info';

        $client = static::createClient();
        $client->request('GET', "/v1/events?type=$type&limit=$limit");

        $this->assertResponseIsSuccessful();
        $results = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount($limit, $results);
        foreach ($results as $event) {
            $this->assertEquals($type, $event['type']);
        }
    }

    public function testStore(): void
    {
        $data = [
            'details' => 'Test Details',
            'type' => 'info',
        ];
        $client = static::createClient();
        $client->request(
            'POST',
            "/v1/events",
            [],
            [],
            ["CONTENT_TYPE" => "application/json"],
            $this->getContainer()->get('serializer')->serialize($data, 'json')
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);

        // Get last event
        $limit = 1;
        $client->request('GET', "/v1/events?limit=$limit");

        $this->assertResponseIsSuccessful();
        $results = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount($limit, $results);
        $this->assertEquals($data['type'], $results[0]['type']);
        $this->assertEquals($data['details'], $results[0]['details']);
    }
}
