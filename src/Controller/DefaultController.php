<?php

namespace App\Controller;

use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        $em   = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Article::class);

        $articles = $repo->findAll();

        return $this->render('homepage.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/{slug}", name="article")
     *
     * @param string $slug
     */
    public function article(string $slug)
    {
        $em      = $this->getDoctrine()->getManager();
        $repo    = $em->getRepository(Article::class);
        $article = $repo->findOneBy([
            'slug' => $slug,
        ]);

        // 404 ou page qui affiche l'article
    }
}
