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
use Symfony\Component\Uid\Uuid;

/**
 * Functional test for the controllers defined inside the UserController used
 * for managing the current logged user.
 *
 * See https://symfony.com/doc/current/testing.html#functional-tests
 *
 * Whenever you test resources protected by a firewall, consider using the
 * technique explained in:
 * https://symfony.com/doc/current/testing/http_authentication.html
 *
 * Execute the application tests using this command (requires PHPUnit to be installed):
 *
 *     $ cd your-symfony-project/
 *     $ ./vendor/bin/phpunit
 */
class TypeControllerTest extends WebTestCase
{
    public function testStore(): void
    {
        $id = Uuid::v1();
        $data = [
            'name' => "Test $id",
        ];
        $client = static::createClient();
        $client->request(
            'POST',
            "/v1/types",
            [],
            [],
            ["CONTENT_TYPE" => "application/json"],
            $this->getContainer()->get('serializer')->serialize($data, 'json')
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);

        // Create event
        $data = [
            'details' => 'Test Details',
            'type' => "Test $id",
        ];
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
        $this->assertEquals("Test $id", $results[0]['type']);
    }
}
