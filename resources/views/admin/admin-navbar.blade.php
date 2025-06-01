<nav class="breadcrumb has-bullet-separator has-text-weight-medium is-centered my-2">
    <ul>
        <li><a href="{{ route('dashboard') }}">管理画面</a></li>
        <li><a href="{{ route('article.index') }}">記事一覧</a></li>
        <li><a href="{{ route('article.create') }}">記事新規作成</a></li>
    </ul>
</nav>
<section class="hero is-light is-small section py-0">
    <div class="hero-body container px-0">
        <p class="title mb-1 is-4">{{ $title ?? '管理画面' }}</p>
        <p class="subtitle is-6">{{ $title_eo ?? 'Administra ekrano' }}</p>
    </div>
</section>
