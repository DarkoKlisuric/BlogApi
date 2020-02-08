<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var Factory
     */
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create();
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
        for ($i=0; $i<100; $i++) {
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30))
                ->setPublished($this->faker->dateTimeThisYear)
                ->setContent($this->faker->realText())
                ->setAuthor($this->getReference('admin'))
                ->setSlug($this->faker->slug);

            $this->addReference('blog_post_'. $i, $blogPost );
            $manager->persist($blogPost);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function loadComments(ObjectManager $manager): void
    {
        for ($i=0; $i<100; $i++) {
            for ($j=0, $jMax = random_int(1, 10); $j< $jMax; $j++) {
                $comment = new Comment();
                $comment->setContent($this->faker->realText())
                    ->setPublished($this->faker->dateTimeThisYear)
                    ->setAuthor($this->getReference('admin'))
                    ->setBlogPost($this->getReference('blog_post_'. $i));

                $manager->persist($comment);
            }
        }
        $manager->flush();
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
