@extends('layouts.app')

@section('title', 'Moderate Themes')

@section('content')
    <style>
        .themes-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .theme-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .theme-header {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .theme-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
        }

        .theme-image {
            width: 100px;
            height: 100px;
            background-color: #f0f0f0;
            color: #888;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .theme-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-edit {
            padding: 5px 10px;
            font-size: 0.9rem;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .subscription-list {
            margin-top: 15px;
        }

        .subscription-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }

        .subscription-item:hover {
            background-color: #f4f4f4;
        }

        .btn-accept {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }

        .btn-accept:hover {
            background-color: #218838;
        }

        .btn-reject {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            background-color: #dc3545;
            color: white;
            cursor: pointer;
        }

        .btn-reject:hover {
            background-color: #c82333;
        }
    </style>

    <div class="themes-container">
        <h1 class="text-center">Moderate Themes</h1>

        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @forelse($themes as $theme)
            <div class="theme-card">
                <div class="theme-header">
                    <span>{{ $theme->name }}</span>
                    <a href="{{ route('theme_manager.themes.edit', $theme->id) }}" class="btn-edit">Edit Theme</a>
                </div>
                <!-- <div>
                    <img src="{{ $theme->image ? asset('storage/' . $theme->image) : 'https://via.placeholder.com/100' }}" alt="{{ $theme->name }}" class="theme-image">
                </div> -->

                <div class="theme-image">
                    @if($theme->image)
                        <img src="{{ asset('storage/' . $theme->image) }}" alt="{{ $theme->name }}">
                    @else
                        <span>No Image</span>
                    @endif
                </div>


                <div class="theme-description">
                    {{ Str::limit($theme->description, 100, '...') }}
                </div>

                @if($theme->subscriptions->isEmpty())
                    <p>No subscription requests for this theme.</p>
                @else
                    <div class="subscription-list">
                        @foreach($theme->subscriptions as $subscription)
                            <div class="subscription-item">
                                <div>
                                    <p><strong>User:</strong> {{ $subscription->user->name }}</p>
                                </div>
                                <div>
                                    <form action="{{ route('theme_manager.subscriptions.accept', [$theme, $subscription]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-accept">Accept</button>
                                    </form>

                                    <form action="{{ route('theme_manager.subscriptions.reject', [$theme, $subscription]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-reject">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p>No themes to moderate.</p>
        @endforelse
    </div>
@endsection
