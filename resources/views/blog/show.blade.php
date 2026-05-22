@extends('layouts.app')

@section('title', $blog->title)

@push('styles')
<style>
    .blog-detail-layout { display: grid; grid-template-columns: 1fr 320px; gap: 3rem; padding: 4rem 0; align-items: start; }
    .blog-hero-img { width: 100%; height: 420px; object-fit: cover; border-radius: 16px; margin-bottom: 2.5rem; }
    .blog-hero-placeholder { width: 100%; height: 420px; background: linear-gradient(135deg, var(--black), #2d2d4e); border-radius: 16px; margin-bottom: 2.5rem; display: flex; align-items: center; justify-content: center; color: var(--gold); font-size: 5rem; }
    .blog-title { font-family: var(--font-display); font-size: 2.25rem; color: var(--black); line-height: 1.25; margin-bottom: 1rem; }
    .blog-meta { display: flex; align-items: center; gap: 1.5rem; font-size: .85rem; color: var(--gray); margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border); }
    .blog-content { font-size: 1rem; line-height: 1.85; color: var(--charcoal); }
    .blog-content p { margin-bottom: 1.25rem; }
    .blog-sidebar-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; position: sticky; top: 90px; }
    .blog-sidebar-card h4 { font-family: var(--font-display); font-size: 1rem; margin-bottom: 1rem; }
    .recent-post { display: flex; gap: .75rem; padding: .75rem 0; border-bottom: 1px solid var(--border); text-decoration: none; color: inherit; }
    .recent-post:last-child { border-bottom: none; }
    .recent-post-img { width: 52px; height: 52px; background: var(--warm-white); border-radius: 6px; overflow: hidden; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: var(--gray); }
    .recent-post-img img { width: 100%; height: 100%; object-fit: cover; }
    .recent-post-title { font-size: .85rem; font-weight: 500; line-height: 1.4; color: var(--black); margin-bottom: .2rem; }
    .recent-post-title:hover { color: var(--gold); }
    .recent-post-date { font-size: .75rem; color: var(--gray); }
    @media(max-width:1024px) { .blog-detail-layout { grid-template-columns: 1fr; } .blog-sidebar-card { position: static; } }
</style>
@endpush

@section('content')
<div class="page-hero" style="padding:2.5rem;">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> <span>/</span>
        <a href="{{ route('blog') }}">Blog</a> <span>/</span>
        <span>{{ Str::limit($blog->title, 40) }}</span>
    </div>
</div>

<div class="container">
    <div class="blog-detail-layout">
        <article>
            @if($blog->blog_image)
                <img src="{{ asset('storage/'.$blog->blog_image) }}" alt="{{ $blog->title }}" class="blog-hero-img">
            @else
                <div class="blog-hero-placeholder"><i class="fas fa-newspaper"></i></div>
            @endif

            <h1 class="blog-title">{{ $blog->title }}</h1>
            @if($blog->subtitle)<p style="font-size:1.1rem;color:var(--gray);margin-bottom:1.25rem;font-style:italic;">{{ $blog->subtitle }}</p>@endif

            <div class="blog-meta">
                <span><i class="fas fa-user-circle" style="color:var(--gold);"></i> {{ $blog->blog->author_name ?? 'LuxeStore' }}</span>
                <span><i class="fas fa-calendar-alt" style="color:var(--gold);"></i> {{ $blog->created_at->format('F d, Y') }}</span>
                {{-- @if($blog->tags)<span><i class="fas fa-tag" style="color:var(--gold);"></i> {{ $blog->tags }}</span>@endif --}}
            </div>

            <div class="blog-content">
                {!! nl2br(e($blog->description)) !!}
            </div>

            <div style="margin-top:2.5rem;padding-top:2rem;border-top:1px solid var(--border);">
                <a href="{{ route('blog') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Blog</a>
            </div>
        </article>

        <aside>
            @if($recent->isNotEmpty())
            <div class="blog-sidebar-card">
                <h4>Recent Posts</h4>
                @foreach($recent as $rp)
                <a href="{{ route('blog.show', $rp->blog_detail_id) }}" class="recent-post">
                    <div class="recent-post-img">
                        @if($rp->blog_image)<img src="{{ asset('storage/'.$rp->blog_image) }}" alt="">
                        @else <i class="fas fa-newspaper"></i> @endif
                    </div>
                    <div>
                        <div class="recent-post-title">{{ Str::limit($rp->title, 55) }}</div>
                        <div class="recent-post-date">{{ $rp->created_at->format('M d, Y') }}</div>
                    </div>
                </a>
                @endforeach
            </div>
            @endif

            <div class="blog-sidebar-card" style="background:var(--black);color:#fff;">
                <h4 style="color:#fff;">Subscribe</h4>
                <p style="font-size:.85rem;color:rgba(255,255,255,.65);margin-bottom:1rem;">Get the latest posts and style inspiration delivered to your inbox.</p>
                <input type="email" placeholder="your@email.com" style="width:100%;padding:.65rem .9rem;border:1.5px solid rgba(255,255,255,.2);background:rgba(255,255,255,.1);border-radius:6px;color:#fff;font-family:var(--font-body);font-size:.875rem;margin-bottom:.75rem;">
                <button class="btn btn-gold" style="width:100%;justify-content:center;">Subscribe</button>
            </div>
        </aside>
    </div>
</div>
@endsection
