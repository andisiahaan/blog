@extends('layouts.main')

@section('title', $page->title . ' - ' . config('app.name'))
@section('description', $page->getMeta('description', Str::limit(strip_tags($page->content), 160)))

@section('content')
<article class="max-w-3xl">
    <header class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">{{ $page->title }}</h1>
    </header>

    <div class="prose prose-lg dark:prose-invert prose-violet max-w-none">
        {!! $page->content !!}
    </div>
</article>
@endsection
