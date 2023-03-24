<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(private UserPasswordHasherInterface $paswordHasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->faker->seed(157);
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@test.com')
            ->setUsername('Alex')
            ->setPathPhoto('img/defaultavatar.png')
            ->setPassword($this->paswordHasher->hashPassword($user, 'password'));
        $manager->persist($user);

        for ($i = 0; $i < 4; $i++) {
            $user = new User();
            $user->setEmail($this->faker->safeEmail())
                ->setUsername($this->faker->firstName())
                ->setPathPhoto('img/defaultavatar.png')
                ->setPassword($this->paswordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
