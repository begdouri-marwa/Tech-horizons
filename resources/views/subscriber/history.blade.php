@extends('layouts.app')

@section('title', 'Browsing History')

@section('content')
    <style>
        .history-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .history-header {
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .history-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .history-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
        }

        .history-item:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .history-title {
            font-size: 1.2rem;
            margin: 0;
            color: #333;
        }

        .history-date {
            font-size: 0.9rem;
            color: #999;
        }

        .rating-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rating-select {
        	width: 60px;
            padding: 5px;
            font-size: 1rem;
        }

        .btn-submit {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        .navigation-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .btn-navigate {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1rem;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .btn-navigate:hover {
            background-color: #0056b3;
        }

        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-input {
            width: 300px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px 0 0 5px;
            outline: none;
        }

        .search-button {
            padding: 10px 20px;
            border: none;
            border-radius: 0 5px 5px 0;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.2s;
        }

        .search-button:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="history-container">
        <h1 class="history-header">Your Browsing History</h1>

        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <!-- Navigation Buttons -->
        <div class="navigation-buttons">
            <a href="{{ route('subscriber.proposeArticle') }}" class="btn-navigate">Propose an Article</a>
            <a href="{{ route('subscriber.myArticles') }}" class="btn-navigate">View My Articles</a>
        </div>

        <div class="search-container">
            <form action="{{ route('subscriber.history') }}" method="GET">
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Search history..." 
                    value="{{ request('search') }}">
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>

        <div class="history-list">
            @forelse($history as $item)
                @if($item->article) <!-- Check if article exists -->
                    <div class="history-item">
                        <a href="{{ route('subscriber.article', $item->article->id) }}">
                            <div>
                                <p class="history-title">{{ $item->article->title }}</p>
                                <p class="history-date">Viewed: {{ $item->created_at->format('M d, Y - H:i') }}</p>
                            </div>
                        </a>
                        <div>
                            <form action="{{ route('subscriber.rate') }}" method="POST" class="rating-form">
                                @csrf
                                <input type="hidden" name="article_id" value="{{ $item->article->id }}">
                                <select name="rating" class="rating-select">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" 
                                            {{ optional($item->article->ratings->firstWhere('user_id', auth()->id()))->rating == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                <button type="submit" class="btn-submit">Rate</button>
                            </form>
                        </div>
                    </div>
                @endif
            @empty
                <p>No articles available in your history.</p>
            @endforelse
        </div>
    </div>

@endsection
