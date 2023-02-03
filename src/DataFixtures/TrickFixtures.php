<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Group;
use App\Entity\Movie;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\Picture;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct(private UserRepository $userRepository)
    {
        $this->faker = Factory::create('fr_FR');
        $this->faker->seed(3258);
    }

    public function getDependencies()
    {
        return [
            AppFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this->userRepository->findAll();
        $groups = ['Grab', 'Rotation', 'Flip', 'Rotations désaxées', 'Slide'];
        foreach ($groups as $groupName) {
            $group = new Group();
            $group->setName($groupName);
            $manager->persist($group);

            for ($i = 0; $i < $this->faker->numberBetween(2, 12); $i++) {

                $trick = new Trick();
                $trick->setName($this->faker->word($this->faker->numberBetween(1, 5)))
                    ->setDescription($this->faker->text($this->faker->numberBetween(100, 2000), true));

                for ($i = 0; $i < $this->faker->numberBetween(1, 5); $i++) {
                    $picture = new Picture();
                    $picture->setPath('/img/placeholer.jpg');
                    $manager->persist($picture);
                    $trick->addPicture($picture);
                }


                for ($i = 0; $i < $this->faker->numberBetween(1, 3); $i++) {
                    $movie = new Movie();
                    $movie->setHtml('<iframe width="560" height="315" src="https://www.youtube.com/embed/NpEaa2P7qZI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>');
                    $manager->persist($movie);
                    $trick->addMovie($movie);
                }


                $manager->persist($trick);

                for ($i = 0; $i < 3; $i++) {

                    $comment = new Comment();
                    $comment->setCreationDate($this->faker->dateTimeBetween('-6 month', 'now'));
                    $comment->setContent($this->faker->text($this->faker->numberBetween(2, 1000)));
                    $comment->setUser($this->faker->randomElement($users));
                    $comment->setTrick($trick);
                    $manager->persist($comment);
                }

                $group->addTrick($trick);
            }
        }
        $manager->flush();
    }
}
