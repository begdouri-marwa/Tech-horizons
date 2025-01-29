@extends('layouts.app')

@section('title', 'My Articles')

@section('content')
    <style>
        .my-articles-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .my-articles-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f4f4f4;
            color: black;
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

        .btn-view {
            display: inline-block;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-view:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="my-articles-container">
        <h1 class="my-articles-header">My Proposed Articles</h1>

        @if($myArticles->isEmpty())
            <p>No articles proposed yet.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myArticles as $article)
                        <tr>
                            <td>{{ $article->title }}</td>
                            <td>
                                <span class="status-{{ strtolower($article->status) }}">
                                    {{ ucfirst($article->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('subscriber.viewArticle', $article->id) }}" class="btn-view">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
