@extends('layouts.app')

@section('title', 'Proposed Articles')

@section('content')
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            color: black;
            background-color: #f4f4f4;
        }

        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-view {
            background-color: #007bff;
        }

        .btn-view:hover {
            background-color: #0056b3;
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

    <div class="container">
        <h1>Proposed Articles</h1>

        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Theme</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td>{{ $article->title }}</td>
                        <td>{{ $article->theme->name }}</td>
                        <td>
                            <a href="{{ route('theme_manager.proposed_articles.view', $article->id) }}" class="btn btn-view">View</a>
                            <form action="{{ route('theme_manager.proposed_articles.accept', $article->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-accept">Accept</button>
                            </form>
                            <form action="{{ route('theme_manager.proposed_articles.reject', $article->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-reject">Reject</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No proposed articles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
