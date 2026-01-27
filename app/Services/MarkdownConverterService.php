<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Cache;
use Embed\Embed;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;
use League\CommonMark\Extension\Embed\Embed as CMEmbed;
use League\CommonMark\Extension\Embed\EmbedExtension;
use League\CommonMark\Extension\Embed\EmbedRenderer;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\Footnote\FootnoteExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\InlinesOnly\InlinesOnlyExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Renderer\HtmlDecorator;

final class MarkdownConverterService
{
    private array $baseConfig;

    private Embed $embedLib;

    public function __construct()
    {
        $this->embedLib = new Embed();
        $this->embedLib->setSettings([
            'oembed:query_parameters' => [
                'omit_script' => 'true',
                'maxwidth' => 800,
                'maxheight' => 600,
            ],
        ]);

        $this->baseConfig = [
            'html_input' => 'escape',
            'arrow_unsafe_links' => false,
            'external_link' => [
                'internal_hosts' => explode(':', $_SERVER['HTTP_HOST'])[0],
                'open_in_new_window' => true,
                'html_class' => 'external-link',
                'nofollow' => '',
                'noopener' => 'external',
                'noreferrer' => 'external',
            ],
            'footnote' => [
                'backref_class' => 'footnote-backref',
                'backref_symbol' => '↩',
                'container_add_hr' => true,
                'container_class' => 'footnotes',
                'ref_class' => 'footnote-ref',
                'ref_id_prefix' => 'fn-ref:',
                'footnote_class' => 'footnote',
                'footnote_id_prefix' => 'fn:',
            ],
            'embed' => [
                'adapter' => new OscaroteroEmbedAdapter($this->embedLib),
                'allowed_domains' => [],
                'fallback' => 'link',
            ],
        ];
    }

    /**
     * 記事本文用の変換・キャッシュ操作
     * @throws CommonMarkException
     */
    public function convertArticle(string $markdown, ?string $cacheKey = null): string
    {
        if ($cacheKey) {
            return Cache::rememberForever(
                $cacheKey,
                fn () => $this->performArticleConversion($markdown)
            );
        }

        return $this->performArticleConversion($markdown);
    }

    /**
     * プロフィール用の変換・キャッシュ操作
     */
    public function convertProfile(User $user)
    {
        return Cache::rememberForever(
            $this->generateCacheKey('user.profile', $user->id, $user->updated_at->timestamp),
            fn () => $this->performProfileConversion($user->biography)
        );
    }

    /**
     * キャッシュキーを生成
     */
    public function generateCacheKey(string $prefix, int $id, int $timestamp): string
    {
        return "{$prefix}.{$id}_{$timestamp}";
    }

    /**
     * 記事データからキャッシュキーを生成
     */
    public function generateCacheKeyByArticle(Article $article): string
    {
        return "article.{$article->id}_{$article->updated_at->timestamp}";
    }

    /**
     * 記事本文の実変換処理
     * @throws CommonMarkException
     */
    private function performArticleConversion(string $markdown): string
    {
        $env = new Environment($this->baseConfig);
        $env->addExtension(new CommonMarkCoreExtension());
        $env->addExtension(new GithubFlavoredMarkdownExtension());
        $env->addExtension(new ExternalLinkExtension());
        $env->addExtension(new FootnoteExtension());
        $env->addExtension(new EmbedExtension());
        $env->addRenderer(
            CMEmbed::class,
            new HtmlDecorator(new EmbedRenderer(), 'div', ['class' => 'embed-content'])
        );

        $converter = new MarkdownConverter($env);

        return $converter->convert($markdown)->getContent();
    }

    /**
     * プロフィールの実変換処理
     * @throws CommonMarkException
     */
    private function performProfileConversion(string $markdown): string
    {
        $env = new Environment($this->baseConfig);
        $env->addExtension(new ExternalLinkExtension());
        $env->addExtension(new InlinesOnlyExtension());

        $converter = new MarkdownConverter($env);

        return $converter->convert($markdown)->getContent();
    }
}
