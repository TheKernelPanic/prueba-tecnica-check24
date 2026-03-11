<?php

namespace App\Tests\integration;

use App\Enums\CarForm;
use App\Enums\CarUse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CalculateHttpRequestTest
 * @package App\Tests\integration
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
class CalculateHttpRequestTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = static::getContainer()->get('doctrine')->getManager();

        $schemaTool = new SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    /**
     * @return void
     */
    public function testServerRespondsSuccess(): void
    {
        $faker  = Factory::create();

        $requestBody = [
            'driver_age' => $faker->numberBetween(20, 60),
            'car_form'   => $faker->randomElement(
                array_map(
                    fn($element) => $element->value,
                    CarForm::cases()
                )
            ),
            'car_use'    => $faker->randomElement(
                array_map(
                    fn($element) => $element->value,
                    CarUse::cases()
                )
            ),
        ];

        $this->client->request(
            'POST',
            '/calculate',
            server: [
                'CONTENT_TYPE' => 'application/json'
            ],
            content: json_encode($requestBody)
        );

        $this->assertResponseStatusCodeSame(200);
    }
}
