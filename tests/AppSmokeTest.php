<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppSmokeTest extends WebTestCase
{
    public function urlPublicProvider()
    {
        yield ['/'];
    }

    public function urlPrivateProvider()
    {
        yield ['/usuario/perfil'];

    }

    // /**
    //  * @dataProvider urlPrivateProvider
    //  */
    // public function testRoutePrivates($url)
    // {
    //     $client = static::createClient([],[
    //         'PHP_AUTH_USER' => 'digital.nicaragua@gmail.com',
    //         'PHP_AUTH_PW'   => '2323',
    //     ]);
    //     $client->request('GET', $url);
    //     $this->assertResponseIsSuccessful();
    //     // $this->assertSelectorTextContains('strong.version', 'ALFA');
    // }


    /**
     * @dataProvider urlPublicProvider
     */
    public function testRoutePublilcs($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertResponseIsSuccessful();
    }
}