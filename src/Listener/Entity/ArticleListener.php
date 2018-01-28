<?php

namespace App\Listener\Entity;

use App\Entity\Article;
use Cocur\Slugify\SlugifyInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

class ArticleListener
{
    /**
     * @var SlugifyInterface
     */
    private $slugify;

    public function __construct(SlugifyInterface $slugify)
    {
        $this->slugify = $slugify;
    }

    /**
     * @PrePersist
     *
     * @param Article            $article
     * @param LifecycleEventArgs $event
     */
    public function prePersistHandler(Article $article, LifecycleEventArgs $event)
    {
        if ($article->getTitle() !== null) {
            $slug = $this->slugify->slugify($article->getTitle());
            $article->setSlug($slug);
        }

        $date = new \DateTime('now');
        $article->setCreatedAt($date);
    }

    /**
     * @PreUpdate()
     *
     * @param Article            $article
     * @param LifecycleEventArgs $event
     */
    public function preUpdateHandler(Article $article, LifecycleEventArgs $event)
    {
        $slug = $this->slugify->slugify($article->getTitle());
        $article->setSlug($slug);

        $date = new \DateTime('now');
        $article->setUpdatedAt($date);
    }
}
