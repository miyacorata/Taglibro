<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\MarkdownConverterService;
use League\CommonMark\Exception\CommonMarkException;

final class VisitorController extends Controller
{
    public function __construct(
        private readonly MarkdownConverterService $markdownConverter
    ) {}

    public function index()
    {
        $articles = Article::wherePublished(true)->latest()->paginate(10);

        return view('index', compact('articles'));
    }

    public function article(string $slug)
    {
        $article = Article::wherePublished(true)->where('slug', $slug)->firstOrFail();

        // 記事本文の変換
        try {
            $converted_article_content = $this->markdownConverter->convertArticle(
                $article->content,
                $this->markdownConverter->generateCacheKeyByArticle($article)
            );
        } catch (CommonMarkException $e) {
            report($e);
            abort(500, '記事表示処理で問題が発生しました');
        }

        // プロフィールの変換
        $converted_profile_biography = $this->markdownConverter->convertProfile(
            $article->user
        );

        return view('article', compact('article', 'converted_article_content', 'converted_profile_biography'));
    }
}
