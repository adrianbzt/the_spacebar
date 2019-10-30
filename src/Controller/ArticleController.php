<?php

namespace App\Controller;

use App\Service\MarkdownHelper;
use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function show($slug, Environment $twigEnv, MarkdownHelper $markdownHelper, bool $isDebug, Client $slack)
    {

        if ($slug == 'why-asteriods-taste-like-bacon') {
            $message = $slack->createMessage();

            $text = "[" . date('Y-m-d H:i:s', time()) . "] This is a random test message X " .  $slug;

            $message
                ->to('#thespacebar')
                ->from('the_spacebar_app')
                ->withIcon(':poop:')
                ->setText($text);

            $slack->sendMessage($message);
        }

        $response = sprintf('New space work in progress! %s', $slug);

        $comments = [
            "Ana are mere!",
            "Merele sunt mari!",
            "Cine nu vrea mere?"
        ];

        $articleContent = <<<EOD
Example **of** string bacon string
spanning multiple **lines**
using heredoc __syntax__.
EOD;

        $articleContent = $markdownHelper->parse($articleContent);


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