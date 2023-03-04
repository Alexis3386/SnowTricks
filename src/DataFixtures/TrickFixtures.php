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
use Symfony\Component\String\Slugger\AsciiSlugger;
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
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);
        $slugger = new AsciiSlugger();
        $groups = ['Grab', 'Rotation', 'Flip', 'Rotations désaxées', 'Slide'];
        foreach ($groups as $groupName) {
            $users = $this->userRepository->findAll();
            $group = new Group();
            $group->setName($groupName);
            $manager->persist($group);

            for ($i = 0; $i < 5; $i++) {
                $trick = new Trick();
                $trickName = $this->faker->sentence($this->faker->numberBetween(1, 3));
                $trick->setName($trickName)
                    ->setDescription($this->faker->text($this->faker->numberBetween(100, 2000), true))
                    ->setFeaturedImage('/img/Intermediate_to_Advanced_Boarding.jpg')
                    ->setUser($this->faker->randomElement($users))
                    ->setCreationDate($this->faker->dateTimeBetween('-6 month', 'now'))
                    ->setSlug($slugger->slug($trickName));

                for ($j = 0; $j < $this->faker->numberBetween(1, 5); $j++) {
                    $picture = new Picture();
                    $picture->setPath('/img/placeholder.jpg');
                    $manager->persist($picture);
                    $trick->addPicture($picture);
                }

                for ($j = 0; $j < $this->faker->numberBetween(1, 3); $j++) {
                    $movie = new Movie();
                    $movie->setHtml('<iframe width="100%" height="100%" src="https://www.youtube.com/embed/NpEaa2P7qZI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>');
                    $manager->persist($movie);
                    $trick->addMovie($movie);
                }


                $manager->persist($trick);

                for ($j = 0; $j < 3; $j++) {

                    $comment = new Comment();
                    $comment->setCreationDate($this->faker->dateTimeBetween('-6 month', 'now'));
                    $comment->setContent($this->faker->text($this->faker->numberBetween(5, 500)));
                    $comment->setUser($this->faker->randomElement($users));
                    $comment->setTrick($trick);
                    $manager->persist($comment);
                }

                $group->addTrick($trick);
            }
            $manager->flush();
            $manager->clear();
        }
    }
}
