<?php

namespace App\Controller;

use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ArticleController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        return $this->render('article/homepage.html.twig');
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     * @param $slug
     * @return Response
     */
    public function show($slug, Environment $twigEnv, MarkdownInterface $markdown, AdapterInterface $cache)
    {
        $response = sprintf('New space work in progress! %s', $slug);

        dump($cache); die;

        $comments = [
            "Ana are mere!",
            "Merele sunt mari!",
            "Cine nu vrea mere?"
        ];

        $articleContent = <<<EOD
Example **of** string
spanning multiple **lines**
using heredoc __syntax__.
EOD;

        $articleContent = $markdown->transform($articleContent);

        $item = $cache->getItem('markdown_' . md5($articleContent));

        if (!$item->isHit()) {
            $item->set($markdown->transform(($articleContent)));
            $cache->save($item);
        }

        $articleContent = $item->get();


        $html = $twigEnv->render('article/show.html.twig',
            [
                "title" => ucwords(str_replace('-', ' ', $slug)),
                "article_content" => $articleContent,
                "slug" => $slug,
                "comments" => $comments
            ]);

        return new Response($html);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart($slug, LoggerInterface $logger)
    {

        $logger->info('Article is being toggled!');

        return new JsonResponse(['hearts' => rand(5, 100)]);

    }


}