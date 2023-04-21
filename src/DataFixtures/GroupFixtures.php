<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use App\Entity\Group;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class GroupFixtures extends Fixture implements FixtureGroupInterface
{

    public function load(ObjectManager $manager): void
    {
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);
        $groupNames = ['Grab', 'Rotation', 'Flip', 'Rotations désaxées', 'Slide'];
        foreach ($groupNames as $groupName) {
            $group = new Group();
            $group->setName($groupName);
            $manager->persist($group);
        };
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod'];
    }
}
