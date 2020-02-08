<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function loadBlogPosts(ObjectManager $manager): void
    {
        $blogPost = new BlogPost();
        $blogPost->setTitle('A first post!')
            ->setPublished(new \DateTime())
            ->setContent('Text content')
            ->setAuthor($this->getReference('admin'))
            ->setSlug('a-first-post');

        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('A second post!')
            ->setPublished(new \DateTime())
            ->setContent('Text content')
            ->setAuthor($this->getReference('admin'))
            ->setSlug('a-secong-post');

        $manager->persist($blogPost);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadComments(ObjectManager $manager): void
    {

    }

    public function loadUsers(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('admin')
            ->setEmail('admin@blog.com')
            ->setName('Darko Klisurić')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'a'
            ));
        $this->addReference('admin', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
