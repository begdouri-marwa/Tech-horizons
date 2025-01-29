@extends('layouts.app')

@section('title', 'Subscriber Dashboard')

@section('content')
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .issue-card {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .issue-header {
            margin-bottom: 15px;
        }

        .issue-title {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 5px;
        }

        .issue-description {
            font-size: 1rem;
            color: #666;
            margin-bottom: 10px;
        }

        .issue-publish-date {
            font-size: 0.9rem;
            color: #999;
        }

        .articles-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .article-card {
            flex: 0 0 calc(33.333% - 20px);
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background-color: #fff;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .article-image {
            height: 200px;
            background-color: #f0f0f0;
            color: #888;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .article-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .article-body {
            padding: 15px;
        }

        .article-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #333;
        }

        .article-content {
            font-size: 0.9rem;
            margin-bottom: 10px;
            color: #666;
        }

        .article-date {
            font-size: 0.8rem;
            color: #999;
            padding-bottom: 5px;
        }

        .btn-view {
            margin-top: auto;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .btn-view:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .article-card {
                flex: 0 0 calc(50% - 20px);
            }
        }

        @media (max-width: 480px) {
            .article-card {
                flex: 0 0 100%;
            }
        }
    </style>

    <div class="dashboard-container">
        <h1>Welcome, {{ auth()->user()->name }}</h1>
        <p>Here are your subscribed themes' articles and proposals organized by issues.</p>
        <br />

        @forelse($issues as $issue)
            <div class="issue-card">
                <div class="issue-header">
                    <h2 class="issue-title">{{ $issue->title }}</h2>
                    <p class="issue-description">{{ $issue->description }}</p>
                    <p class="issue-publish-date">
                        Published on:
                        {{ $issue->published_at ? $issue->published_at->format('M d, Y') : 'Not published yet' }}
                    </p>
                </div>

                <div class="articles-grid">
                    @forelse($issue->articles as $article)
                        <div class="article-card">
                            <div class="article-image">
                                @if($article->image)
                                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
                                @else
                                    <span>No Image</span>
                                @endif
                            </div>
                            <div class="article-body">
                                <h2 class="article-title">{{ $article->title }}</h2>
                                <p class="article-content">{{ \Illuminate\Support\Str::limit($article->content, 100) }}</p>
                                <p class="article-date">Published: {{ $article->created_at->format('M d, Y') }}</p>
                                <a href="{{ route('subscriber.article', $article->id) }}" class="btn-view">View Article</a>
                            </div>
                        </div>
                    @empty
                        <p>No articles for this issue.</p>
                    @endforelse
                </div>
            </div>
        @empty
            <p>No issues found.</p>
        @endforelse
    </div>
@endsection
