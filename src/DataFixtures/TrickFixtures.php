<?php

namespace App\DataFixtures;

use App\Repository\GroupRepository;
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

    public function __construct(private readonly UserRepository  $userRepository,
                                private readonly GroupRepository $groupRepository)
    {
        $this->faker = Factory::create('fr_FR');
        $this->faker->seed(3258);
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
            GroupFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);
        $slugger = new AsciiSlugger();
        $groups = $this->groupRepository->findAll();
        $users = $this->userRepository->findAll();
        $imagesPath = ['/img/figure1.jpg', '/img/figure2.jpg', '/img/figure3.jpg', '/img/figure4.jpg', '/img/figure5.jpg'];
        $moviesCode = ['_8TBfD5VPnM', '8KotvBY28Mo', 'V9xuy-rVj9w', 'h70kgLV2_Vg', 'QMrelVooJR4'];

        foreach ($groups as $groupSelect) {

            for ($i = 0; $i < 5; $i++) {
                $trick = new Trick();
                $trickName = $this->faker->sentence($this->faker->numberBetween(1, 3));
                $trick->setName($trickName)
                    ->setDescription($this->faker->text($this->faker->numberBetween(100, 2000), true))
                    ->setUser($this->faker->randomElement($users))
                    ->setCreationDate($this->faker->dateTimeBetween('-6 month', 'now'))
                    ->setModificationDate($trick->getCreationDate())
                    ->setSlug($slugger->slug($trickName));

                for ($j = 0; $j < $this->faker->numberBetween(1, 3); $j++) {
                    $picture = new Picture();
                    $picture->setPath($this->faker->randomElement($imagesPath));
                    $manager->persist($picture);
                    $trick->addPicture($picture);
                }

                for ($j = 0; $j < $this->faker->numberBetween(1, 3); $j++) {
                    $movie = new Movie();
                    $movie->setCode($this->faker->randomElement($moviesCode));
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

                $groupSelect->addTrick($trick);
            }
            $manager->flush();
        }
    }
}
