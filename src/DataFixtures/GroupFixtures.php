<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use App\Entity\Group;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GroupFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function __construct(private readonly UserRepository $userRepository)
    {

    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);
        $groups = ['Grab', 'Rotation', 'Flip', 'Rotations désaxées', 'Slide'];
        foreach ($groups as $groupName) {
            $group = new Group();
            $group->setName($groupName);
            $manager->persist($group);
        };

        $manager->flush();
    }
    public
    static function getGroups(): array
    {
       return ['groupFixture'];
    }
}
