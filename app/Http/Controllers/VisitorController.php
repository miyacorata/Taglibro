<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

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
        return view('article', compact('article'));
    }
}
