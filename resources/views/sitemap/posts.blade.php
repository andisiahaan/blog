<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($posts as $post)
    <url>
        <loc>{{ route('blog.post', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toW3cString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        @if($post->featured_image)
        <image:image>
            <image:loc>{{ asset('storage/' . $post->featured_image) }}</image:loc>
            <image:title>{{ $post->title }}</image:title>
        </image:image>
        @endif
    </url>
@endforeach
</urlset>
