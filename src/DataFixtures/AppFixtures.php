<?php

namespace App\DataFixtures;

use App\Entity\Playlist;
use App\Entity\Tag;
use Faker\Factory;
use App\Entity\User;
use Metrakit\EddyMalou\TextProvider;
use Metrakit\EddyMalou\EddyMalouProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new EddyMalouProvider($faker));
        $faker->addProvider(new TextProvider($faker));

        $tagNames = ['electro', 'rock', 'rap', 'metal', 'house', 'country', 'variety', 'indie', 'reggae', 'zouk', 'swing'];
        $tags = [];

        foreach ($tagNames as $tagName) {
            $tag = new Tag;
            $tag->setTitle($tagName);

            $tags[] = $tag;
            $manager->persist($tag);
        }

        for ($u = 0; $u < 30; $u++) {
            $user = new User;
            $user->setName($faker->userName)
                ->setEmail("user$u@mail.com")
                ->setDescription($faker->paragraph(2))
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ->setProfilePicture($faker->imageUrl(300, 300));



            for ($p = 0; $p < mt_rand(0, 5); $p++) {
                $playlist = new Playlist;
                $playlist->setTitle($faker->words(mt_rand(1, 4), true))
                    ->setDecription($faker->paragraph(mt_rand(1, 3)))
                    ->setUser($user)
                    ->addTag($faker->randomElement($tags));

                $manager->persist($playlist);
            }

            $manager->persist($user);
        }


        $manager->flush();
    }
}
