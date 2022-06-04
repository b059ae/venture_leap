<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private array $types = [];
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager): void
    {
        $this->loadTypes($manager);
        $this->loadEvents($manager);
    }

    private function loadTypes(ObjectManager $manager): void
    {
        foreach ($this->getTypeData() as $name) {
            $type = new Type();
            $type->setName($name);
            $this->types[] = $type;

            $manager->persist($type);
        }

        $manager->flush();
    }

    private function getTypeData(): array
    {
        return [
            'info',
            'warning',
            'error',
        ];
    }

    private function loadEvents(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 100; $i++) {
            $event = new Event();
            $event->setDetails($this->faker->text());
            $event->setType($this->types[array_rand($this->types)]);

            $manager->persist($event);
        }

        $manager->flush();
    }
}
