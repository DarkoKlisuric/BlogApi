<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class BlogController
 * @package App\Controller
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @param int $page
     * @param Request $request
     * @return JsonResponse
     * @Route("/{page}", name="blog_list", defaults={"page": 5}, requirements={"page"="\d+"})
     */
    public function list($page = 1, Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $repostitory = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repostitory->findAll();

        return $this->json(
            [
                'page' => $page,
                'limit' => $limit,
                'data' => array_map(function (BlogPost $item) {
                    return $this->generateUrl('blog_by_slug', ['slug' => $item->getSlug()]);
                }, $items)
            ]
        );
    }

    /**
     * @param BlogPost $post
     * @return JsonResponse
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"})
     * @ParamConverter("post", class="App:BlogPost")
     */
    public function post($post): JsonResponse
    {
        return $this->json($post);
    }

    /**
     * @param BlogPost $post
     * @return JsonResponse
     * @Route("/post/{slug}", name="blog_by_slug")
     */
    public function postBySlug(BlogPost $post): JsonResponse
    {
        return $this->json($post);
    }

    /**
     * @param Request $request
     * @Route("/add", name="blog_add", methods={"POST"})
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function add(Request $request): JsonResponse
    {
        /**@var Serializer $serializer */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }
}