@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .header {
            margin-bottom: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
            color: #333;
        }

        .statistics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .stat-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #555;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .stat-category {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            color: #777;
        }
    </style>

    <div class="dashboard-container">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <p>Overview of all statistics on the platform.</p>
        </div>

        <div class="statistics-grid">
            <!-- User Statistics -->
            <div class="stat-card">
                <p class="stat-title">Total Subscribers</p>
                <p class="stat-value">{{ $totalSubscribers }}</p>
                <p class="stat-category">Users</p>
            </div>
            <div class="stat-card">
                <p class="stat-title">Total Theme Managers</p>
                <p class="stat-value">{{ $totalThemeManagers }}</p>
                <p class="stat-category">Users</p>
            </div>
            <div class="stat-card">
                <p class="stat-title">Total Editors</p>
                <p class="stat-value">{{ $totalEditors }}</p>
                <p class="stat-category">Users</p>
            </div>

            <!-- Subscription Statistics -->
            <div class="stat-card">
                <p class="stat-title">Pending Subscriptions</p>
                <p class="stat-value">{{ $subscriptionsPending }}</p>
                <p class="stat-category">Subscriptions</p>
            </div>
            <div class="stat-card">
                <p class="stat-title">Approved Subscriptions</p>
                <p class="stat-value">{{ $subscriptionsApproved }}</p>
                <p class="stat-category">Subscriptions</p>
            </div>
            <div class="stat-card">
                <p class="stat-title">Rejected Subscriptions</p>
                <p class="stat-value">{{ $subscriptionsRejected }}</p>
                <p class="stat-category">Subscriptions</p>
            </div>

            <!-- Issue Statistics -->
            <div class="stat-card">
                <p class="stat-title">Published Issues</p>
                <p class="stat-value">{{ $issuesPublished }}</p>
                <p class="stat-category">Issues</p>
            </div>
            <div class="stat-card">
                <p class="stat-title">Unpublished Issues</p>
                <p class="stat-value">{{ $issuesUnpublished }}</p>
                <p class="stat-category">Issues</p>
            </div>
            <div class="stat-card">
                <p class="stat-title">Disactivated Issues</p>
                <p class="stat-value">{{ $issuesDisactivated }}</p>
                <p class="stat-category">Issues</p>
            </div>

            <!-- Article Statistics -->
            <div class="stat-card">
                <p class="stat-title">Pending Articles</p>
                <p class="stat-value">{{ $articlesPending }}</p>
                <p class="stat-category">Articles</p>
            </div>
            <div class="stat-card">
                <p class="stat-title">Accepted Articles</p>
                <p class="stat-value">{{ $articlesAccepted }}</p>
                <p class="stat-category">Articles</p>
            </div>
            <div class="stat-card">
                <p class="stat-title">Rejected Articles</p>
                <p class="stat-value">{{ $articlesRejected }}</p>
                <p class="stat-category">Articles</p>
            </div>

            <!-- Themes -->
            <div class="stat-card">
                <p class="stat-title">Total Themes</p>
                <p class="stat-value">{{ $totalThemes }}</p>
                <p class="stat-category">Themes</p>
            </div>
        </div>
    </div>
@endsection

