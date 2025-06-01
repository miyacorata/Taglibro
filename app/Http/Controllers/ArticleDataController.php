<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class ArticleDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with('user')->get();
        return view('admin.article.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.article.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'description' => ['nullable'],
            'top_image_url' => ['nullable', 'url'],
            'slug' => ['nullable', 'unique:articles,slug', "regex:/^[A-Za-z0-9\-._~!$&'()*+,;=:@%]*$/"],
            'content' => ['required'],
            'published' => ['required', 'boolean'],
        ]);

        $article = new Article();
        $article->title = $request->post('title');
        $article->description = $request->post('description');
        $article->top_image_url = $request->post('top_image_url');
        $article->slug = $request->post('slug') ?? Str::orderedUuid();
        $article->content = $request->post('content');
        $article->published = boolval($request->post('published'));
        $article->user = User::whereSub(Auth::user()->getAuthIdentifier())->firstOrFail()->id;
        $article->save();

        return redirect()->route('article.index')->with('message', '記事を作成しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}
