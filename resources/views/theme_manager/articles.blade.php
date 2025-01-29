@extends('layouts.app')

@section('title', 'Moderate Articles')

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

        .btn-edit {
            background-color: #007bff;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-create {
            background-color: #28a745;
            margin-bottom: 20px;
        }

        .btn-create:hover {
            background-color: #218838;
        }
    </style>

    <div class="container">
        <h1>My Articles</h1>

        <a href="{{ route('theme_manager.articles.create') }}" class="btn btn-create">Post New Article</a>

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
                            <a href="{{ route('subscriber.article', $article->id) }}" class="btn btn-edit">View</a>
                            <a href="{{ route('theme_manager.articles.edit', $article->id) }}" class="btn btn-edit">Edit</a>
                            <form action="{{ route('theme_manager.articles.delete', $article->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No articles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection