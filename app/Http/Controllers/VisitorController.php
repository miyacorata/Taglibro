<?php

namespace App\Http\Controllers;

use App\Models\Article;
// use Illuminate\Http\Request;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\InlinesOnly\InlinesOnlyExtension;
use League\CommonMark\MarkdownConverter;

class VisitorController extends Controller
{
    public function index()
    {
        $articles = Article::wherePublished(true)->latest()->paginate(10);

        return view('index', compact('articles'));
    }

    public function article(string $slug)
    {
        $article = Article::wherePublished(true)->where('slug', $slug)->firstOrFail();

        $config = [
            'html_input' => 'escape',
            'arrow_unsafe_links' => false,
            'external_link' => [
                'internal_hosts' => explode(':' ,$_SERVER['HTTP_HOST'])[0],
                'open_in_new_window' => true,
                'html_class' => 'external-link',
                'nofollow' => '',
                'noopener' => 'external',
                'noreferrer' => 'external',
            ],
        ];

        // 本文用変換処理
        $article_env = new Environment($config);
        $article_env->addExtension(new CommonMarkCoreExtension());
        $article_env->addExtension(new GithubFlavoredMarkdownExtension());
        $article_env->addExtension(new ExternalLinkExtension());
        $article_converter = new MarkdownConverter($article_env);
        $converted_article_content = $article_converter->convert($article->content);

        // プロフィール用変換処理
        $profile_env = new Environment($config);
        $profile_env->addExtension(new ExternalLinkExtension());
        $profile_env->addExtension(new InlinesOnlyExtension());
        $profile_converter = new MarkdownConverter($profile_env);
        $converted_profile_biography = $profile_converter->convert($article->user->biography ?? '');



        return view('article', compact('article', 'converted_article_content', 'converted_profile_biography'));
    }
}
