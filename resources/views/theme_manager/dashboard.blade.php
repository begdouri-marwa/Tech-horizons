@extends('layouts.app')

@section('title', 'Theme Manager Dashboard')

@section('content')
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            font-size: 2rem;
            color: #333;
        }

        .statistics-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card h3 {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 10px;
        }

        .stat-card p {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .theme-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .theme-table th, .theme-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .theme-table th {
        	color: black;
            background: #f4f4f4;
        }

        .rating-bar {
            background: #f0f0f0;
            height: 10px;
            border-radius: 5px;
            overflow: hidden;
        }

        .rating-bar-inner {
            height: 100%;
            background: #28a745;
        }
    </style>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Welcome, {{ auth()->user()->name }}</h1>
            <p>Your statistics overview</p>
        </div>

        <div class="statistics-container">
            <div class="stat-card">
                <h3>Total Subscribers</h3>
                <p>{{ $totalSubscribers }}</p>
            </div>
            <div class="stat-card">
                <h3>Total Articles</h3>
                <p>{{ $totalArticles }}</p>
            </div>
            <div class="stat-card">
                <h3>Total Views</h3>
                <p>{{ $historyCount }}</p>
            </div>
        </div>

        <h2>Theme Statistics</h2>
        <table class="theme-table">
            <thead>
                <tr>
                    <th>Theme Name</th>
                    <th>Subscribers</th>
                    <th>Articles</th>
                    <th>Avg Rating</th>
                </tr>
            </thead>
            <tbody>
                @foreach($themes as $theme)
                    <tr>
                        <td>{{ $theme->name }}</td>
                        <td>{{ $theme->subscriptions_count }}</td>
                        <td>{{ $articlesCountByTheme[$theme->id] ?? 0 }}</td>
                        <td>
                            @php
                                $averageRating = $articles->where('theme_id', $theme->id)->pluck('id')->map(function($id) use ($averageRatingByTheme) {
                                    return $averageRatingByTheme[$id] ?? 0;
                                })->avg();
                            @endphp
                            <div class="rating-bar">
                                <div class="rating-bar-inner" style="width: {{ $averageRating * 20 }}%;"></div>
                            </div>
                            <small>{{ number_format($averageRating, 1) }}/5</small>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection