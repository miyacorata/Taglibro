<?php

namespace App\Http\Controllers;

use App\Models\Article;
// use Illuminate\Http\Request;
use Cache;
use Embed\Embed;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;
use League\CommonMark\Extension\Embed\EmbedExtension;
use League\CommonMark\Extension\Embed\EmbedRenderer;
use League\CommonMark\Extension\Embed\Embed as CMEmbed;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\Footnote\FootnoteExtension;
use League\CommonMark\Extension\InlinesOnly\InlinesOnlyExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Renderer\HtmlDecorator;

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

        $embed_lib = new Embed();
        $embed_lib->setSettings([
            'oembed:query_parameters' => [
                'omit_script' => 'true',
                'maxwidth' => 800,
                'maxheight' => 600,
            ],
        ]);
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
            'footnote' => [
                'backref_class'      => 'footnote-backref',
                'backref_symbol'     => '↩',
                'container_add_hr'   => true,
                'container_class'    => 'footnotes',
                'ref_class'          => 'footnote-ref',
                'ref_id_prefix'      => 'fn-ref:',
                'footnote_class'     => 'footnote',
                'footnote_id_prefix' => 'fn:',
            ],
            'embed' => [
                'adapter' => new OscaroteroEmbedAdapter($embed_lib),
                'allowed_domains' => [],
                'fallback' => 'link',
            ],
        ];

        // 本文用変換・キャッシュ処理
        $converted_article_content = Cache::remember(
            'article.'.$article->id .'_'.$article->updated_at->timestamp,
            now()->addDays(30),
            function () use ($config, $article) {
                $article_env = new Environment($config);
                $article_env->addExtension(new CommonMarkCoreExtension());
                $article_env->addExtension(new GithubFlavoredMarkdownExtension());
                $article_env->addExtension(new ExternalLinkExtension());
                $article_env->addExtension(new FootnoteExtension());
                $article_env->addExtension(new EmbedExtension());
                $article_env->addRenderer(CMEmbed::class, new HtmlDecorator(new EmbedRenderer(), 'div', ['class' => 'embed-content']));
                $article_converter = new MarkdownConverter($article_env);
                return $article_converter->convert($article->content);
            },
        );

        // プロフィール用変換・キャッシュ処理
        $converted_profile_biography = Cache::remember(
            'user.profile.'.$article->user->id.'_'.$article->user->updated_at->timestamp,
            now()->addDays(30),
            function () use ($config, $article) {
                $profile_env = new Environment($config);
                $profile_env->addExtension(new ExternalLinkExtension());
                $profile_env->addExtension(new InlinesOnlyExtension());
                $profile_converter = new MarkdownConverter($profile_env);
                return $profile_converter->convert($article->user->biography ?? '');
            },
        );

        return view('article', compact('article', 'converted_article_content', 'converted_profile_biography'));
    }
}
