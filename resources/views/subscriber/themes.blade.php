@extends('layouts.app')

@section('title', 'Themes')

@section('content')
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .col-md-4 {
            flex: 0 0 calc(33.333% - 20px);
            box-sizing: border-box;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background-color: #fff;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
            background-color: #f0f0f0;
            color: #888;
            font-size: 1.2rem;
        }

        .card-img-top img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1.25rem;
            margin-bottom: 10px;
            color: #333;
        }

        .card-text {
            font-size: 0.9rem;
            margin-bottom: 15px;
            color: #666;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
            font-size: 0.9rem;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-danger:hover {
            background-color: #a71d2a;
        }

        @media (max-width: 768px) {
            .col-md-4 {
                flex: 0 0 calc(50% - 20px);
            }
        }

        @media (max-width: 480px) {
            .col-md-4 {
                flex: 0 0 100%;
            }
        }
    </style>

    <div class="container">
        <h1>Available Themes</h1>

        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
            <br />
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            <br />
        @endif

        <div class="row">
            @foreach($themes as $theme)
                <div class="col-md-4">
                    <div class="card">
                        @if($theme->image)
                            <div class="card-img-top">
                                <img src="{{ asset('storage/' . $theme->image) }}" alt="{{ $theme->name }}">
                            </div>
                        @else
                            <div class="card-img-top">
                                <span>No Image</span>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $theme->name }}</h5>
                            <p class="card-text">{{ $theme->description }}</p>
                            @if(Auth::check() && Auth::user()->role === "subscriber")
                                @php
                                    $subscription = \App\Models\Subscription::where('user_id', auth()->id())->where('theme_id', $theme->id)->first();
                                @endphp
                                @if($subscription)
                                    @if($subscription->status === 'pending')
                                        <form action="{{ route('subscriber.cancel') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="theme_id" value="{{ $theme->id }}">
                                            <button type="submit" class="btn btn-warning">Cancel Request</button>
                                        </form>
                                    @elseif($subscription->status === 'approved')
                                        <form action="{{ route('subscriber.unsubscribe') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="theme_id" value="{{ $theme->id }}">
                                            <button type="submit" class="btn btn-danger">Unsubscribe</button>
                                        </form>
                                    @endif
                                @else
                                    <form action="{{ route('subscriber.subscribe') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="theme_id" value="{{ $theme->id }}">
                                        <button type="submit" class="btn btn-primary">Subscribe</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
