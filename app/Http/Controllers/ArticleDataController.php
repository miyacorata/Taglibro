<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

final class ArticleDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::whereSub(Auth::user()->getAuthIdentifier())->firstOrFail();
        $articles = Article::with('user')->where('user_id', $user->id)->orderByDesc('created_at')->get();
        return view('admin.article.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();
        return view('admin.article.create', compact('tags'));
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
        ], [
            'slug.regex' => 'スラッグに使えない文字が含まれています。',
        ]);

        $article = new Article();
        try {
            DB::transaction(function () use ($request, $article) {
                $article->title = $request->post('title');
                $article->description = $request->post('description');
                $article->top_image_url = $request->post('top_image_url');
                $article->slug = $request->post('slug') ?? Str::orderedUuid();
                $article->content = $request->post('content');
                $article->published = boolval($request->post('published'));
                $article->user_id = User::whereSub(Auth::user()->getAuthIdentifier())->firstOrFail()->id;
                $article->save();
                $article->tags()->sync($this->tagsUpsert($request->post('tags', '')));
            });
        } catch (\Exception $e) {
            report($e);
            abort(500, '記事保存処理で異常が発生しました');
        }

        return redirect()->route('article.index')->with('message', '記事「'.$article->title.'」を作成しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return redirect()->route('article.edit', ['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $tags = Tag::all();
        return view('admin.article.edit', compact('article', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => ['required'],
            'description' => ['nullable'],
            'top_image_url' => ['nullable', 'url'],
            'slug' => ['nullable', Rule::unique('articles')->ignore($article->id), "regex:/^[A-Za-z0-9\-._~!$&'()*+,;=:@%]*$/"],
            'content' => ['required'],
            'published' => ['required', 'boolean'],
        ], [
            'slug.regex' => 'スラッグに使えない文字が含まれています。',
        ]);

        if (!$this->isOwnArticle($article)) {
            abort(403, 'それはあなたの記事ではない');
        }

        try {
            DB::transaction(function () use ($request, $article) {
                $article->title = $request->post('title');
                $article->description = $request->post('description');
                $article->top_image_url = $request->post('top_image_url');
                if ($request->post('slug')) $article->slug = $request->post('slug');
                $article->content = $request->post('content');
                $article->published = boolval($request->post('published'));
                $article->tags()->sync($this->tagsUpsert($request->post('tags', '')));
                $article->save();
            });
        } catch (\Exception $e) {
            report($e);
            abort(500, '記事上書き保存処理で異常が発生しました');
        }


        return redirect()->route('article.index')->with('message', '記事「'.$article->title.'」を編集しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        if (!$this->isOwnArticle($article)) {
            abort(403, 'それはあなたの記事ではない');
        }
        $article->delete();
        return redirect()->route('article.index')->with('message', '記事「'.$article->title.'」を削除しました');
    }

    private function isOwnArticle(Article $article): bool
    {
        return $article->user_id === User::whereSub(Auth::user()->getAuthIdentifier())->firstOrFail()->id;
    }

    /**
     * @param string $tag_string タグをカンマ区切りした文字列
     * @return array タグに対応するIDの配列
     */
    private function tagsUpsert(string $tag_string): array
    {
        $tags = explode(',', $tag_string);
        $tags_upsert = [];
        foreach($tags as $tag){
            $tags_upsert[] = ['tag' => $tag];
        }
        try {
            Tag::upsert($tags_upsert, ['tag'], ['tag']);
        } catch (\Exception $e) {
            report($e);
            abort(500, "タグのUPSERT処理で異常が発生しました\n".$e->getMessage());
        }
        return Tag::whereIn('tag', $tags)->get()->pluck('id')->toArray();
    }
}
