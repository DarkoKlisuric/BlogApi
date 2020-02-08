<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $blogPost = new BlogPost();
        $blogPost->setTitle('A first post!')
            ->setPublished(new \DateTime())
            ->setContent('Text content')
            ->setAuthor('Darko')
            ->setSlug('a-first-post');

        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('A second post!')
            ->setPublished(new \DateTime())
            ->setContent('Text content')
            ->setAuthor('Darko')
            ->setSlug('a-secong-post');

        $manager->persist($blogPost);

        $manager->flush();
    }
}
