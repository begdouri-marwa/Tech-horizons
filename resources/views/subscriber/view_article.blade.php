@extends('layouts.app')

@section('title', $article->title)

@section('content')
    <style>
        .article-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .article-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .article-title {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #333;
        }

        .article-image {
            width: 100%;
            height: 300px;
            background-color: #f0f0f0;
            color: #888;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .article-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .article-content {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
        }

        .article-status {
            margin-top: 20px;
            font-size: 1rem;
        }

        .status-pending {
            color: orange;
        }

        .status-accepted {
            color: green;
        }

        .status-rejected {
            color: red;
        }
    </style>

    <div class="article-container">
        <div class="article-header">
            <h1 class="article-title">{{ $article->title }}</h1>
        </div>

        <div class="article-image">
            @if($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
            @else
                <span>No Image</span>
            @endif
        </div>

        <div class="article-content">
            <p>{{ $article->content }}</p>
        </div>

        <div class="article-status">
            <strong>Status:</strong> 
            <span class="status-{{ strtolower($article->status) }}">
                {{ ucfirst($article->status) }}
            </span>
        </div>
    </div>
@endsection
