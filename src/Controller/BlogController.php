<?php
// src/Controller/BlogController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
    * @Route("/blog/list", name="blog_list")
    */
    public function list()
    {
        $list = $this->getDoctrine()
        ->getRepository(Post::class)
        ->findAll();

        $posts = [];
        foreach( $list as $el){
            $temp = [
                "id" => $el->getId(),
                "title" => $el->getTitle(),
                "author" => $el->getAuthor(),
                "text" => $el->getText(),
                "date" => $el->getDate(),
            ];
            array_push($posts, $temp);
        }

        return $this->render('blog/list.html.twig', [
            'list' => $posts,
        ]);

    }

    /**
    * @Route("/blog/post/{id}", name="blog_post")
    */
    public function post(int $id)
    {
        $post = $this->getDoctrine()
        ->getRepository(Post::class)
        ->find($id);

        $temp = [
            "id" => $post->getId(),
            "title" => $post->getTitle(),
            "author" => $post->getAuthor(),
            "text" => $post->getText(),
            "date" => $post->getDate(),
        ];

        return $this->render('blog/post.html.twig', [
            'post' => $temp,
        ]);

    }


    /**
    * @Route("/blog/create", name="blog_create")
    */
    public function create()
    {
        return $this->render('blog/create.html.twig', []);
    }



    /**
    * @Route("/blog/create/submit", name="blog_create_submit")
    * @param $request
    */
    public function create_submit(Request $request)
    {
        $title = $request->get("title");
        $author = $request->get("author");
        $text = $request->get("text");
        $date = time();

        if (!($title && $author && $text)){
            //empty data
            return;
        }


        $entityManager = $this->getDoctrine()->getManager();

        $post = new Post();
        
        $post->setTitle($title);
        $post->setAuthor($author);
        $post->setText($text);
        $post->setDate(new \DateTime());

        $entityManager->persist($post);
        $entityManager->flush();
        $id = $post->getId();
        return $this->redirectToRoute("blog_post", ["id" => $id]);
    }










}
