@extends('layouts.app')

@section('title', 'Manage Issues')

@section('content')
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .header {
            margin-bottom: 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
            color: #333;
        }

        .issues-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .issue-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .issue-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .issue-title {
            font-size: 1.5rem;
            color: #333;
        }

        .issue-status {
            font-size: 0.9rem;
            color: #999;
        }

        .issue-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
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

        .btn-publish {
            background-color: #28a745;
        }

        .btn-publish:hover {
            background-color: #218838;
        }

        .btn-disactivate {
            background-color: #dc3545;
        }

        .btn-disactivate:hover {
            background-color: #c82333;
        }
    </style>

    <div class="container">
        <div class="header">
            <h1>Manage Issues</h1>
        </div>

        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="issues-list">
            @foreach($issues as $issue)
                <div class="issue-card">
                    <div class="issue-header">
                        <div>
                            <h2 class="issue-title">{{ $issue->title ?: 'Untitled Issue' }}</h2>
                            <p class="issue-status">Status: {{ ucfirst($issue->status) }} @if($issue->status == 'published') ({{ $issue->published_at->format('M d, Y') }}) @endif</p>
                            <p>Number of Articles: {{ $issue->articles_count }}</p>
                        </div>
                        <div class="issue-actions">
                            <a href="{{ route('editor.issues.edit', $issue->id) }}" class="btn btn-edit">Edit</a>
                            @if($issue->status === 'unpublished')
                                <form action="{{ route('editor.issues.publish', $issue->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-publish">Publish</button>
                                </form>
                            @elseif($issue->status === 'published')
                                <form action="{{ route('editor.issues.disactivate', $issue->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-disactivate">Disactivate</button>
                                </form>
                            @elseif($issue->status === 'disactivated')
                                <form action="{{ route('editor.issues.activate', $issue->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-publish">Activate</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <p>{{ $issue->description ?: 'No description available.' }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection