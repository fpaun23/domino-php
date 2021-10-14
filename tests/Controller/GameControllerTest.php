<?php

namespace App\Tests\Controller;

use App\Conf\Conf;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    public function testPLayWithCorrectNumberOfPlayers()
    {
        $args['players'] = $this->generateRandomString(Conf::MIN_PLAYERS);

        $client = static::createClient();
        $client->request('GET', '/play', $args);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider playersProvider
     */
    public function testPLayWithIncorrectNumberOfPlayers($players)
    {
        $client = static::createClient();
        $client->request('GET', '/play', $players);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString("Invalid request parameter : players", $client->getResponse()->getContent());
    }

    public function playersProvider()
    {
        $args['players'] = [
            $this->generateRandomString(Conf::MIN_PLAYERS - 1),
            $this->generateRandomString(Conf::MAX_PLAYERS + 1),
        ];
        return $args;
    }

    function generateRandomString($nbStrings = 1, $length = 5): array
    {
        $res = [];
        for ($i = 0; $i < $nbStrings; $i++) {
            $res[] = substr(str_shuffle(str_repeat($x = 'abcdefghijklmnopqrstuvwxyz', ceil($length / strlen($x)))), 1, $length);
        }
        return $res;
    }
}

