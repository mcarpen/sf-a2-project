<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/{page}", name="homepage", defaults={"page"=1})
     * @param $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function homepage($page)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var ArticleRepository $repo */
        $repo     = $em->getRepository(Article::class);
        $articles = $repo->loadAll(6, ($page - 1) * 6);
        $count    = $repo->countQuery();
        $maxPagination = (int)ceil($count / 6);
        $minPage = (int)max(1, ($page - 5));
        $maxPage = (int)min($maxPagination, ($page + 5));
        $max = 0;
        while (abs($minPage - $maxPage) < 10) {
            if ($minPage > 1) {
                $minPage--;
            }
            if ($maxPage < $maxPagination) {
                $maxPage++;
            }
            $max++;
            if ($max > 10) {
                break;
            }
        }

        return $this->render('homepage.html.twig', [
            'articles'      => $articles,
            'currentPage'   => $page,
            'maxPagination' => $maxPagination,
            'minPage'       => $minPage,
            'maxPage'       => $maxPage,
            'count'         => $count,
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
