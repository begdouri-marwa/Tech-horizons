@extends('layouts.app')

@section('title', 'View Proposed Article')

@section('content')
    <style>
        .article-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
            max-height: 300px;
            object-fit: cover;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .article-content {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
        }

        .article-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }

        .btn-accept {
            background-color: #28a745;
        }

        .btn-accept:hover {
            background-color: #218838;
        }

        .btn-reject {
            background-color: #dc3545;
        }

        .btn-reject:hover {
            background-color: #c82333;
        }
    </style>

    <div class="article-container">
        <div class="article-header">
            <h1 class="article-title">{{ $article->title }}</h1>
        </div>
        @if($article->image)
            <img src="{{ asset('storage/' . $article->image) }}" alt="Article Image" class="article-image">
        @endif
        <div class="article-content">
            <p>{{ $article->content }}</p>
        </div>
        <div class="article-actions">
            <form action="{{ route('theme_manager.proposed_articles.accept', $article->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-accept">Accept</button>
            </form>
            <form action="{{ route('theme_manager.proposed_articles.reject', $article->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-reject">Reject</button>
            </form>
        </div>
    </div>
@endsection