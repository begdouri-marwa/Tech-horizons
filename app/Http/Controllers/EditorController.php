<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Theme;
use App\Models\Article;
use App\Models\Subscription;
use App\Models\Issue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class EditorController extends Controller
{
    public function dashboard()
    {
        // User statistics
        $totalSubscribers = User::where('role', 'subscriber')->count();
        $totalThemeManagers = User::where('role', 'theme_manager')->count();
        $totalEditors = User::where('role', 'editor')->count();

        // Subscription statistics
        $subscriptionsPending = Subscription::where('status', 'pending')->count();
        $subscriptionsApproved = Subscription::where('status', 'approved')->count();
        $subscriptionsRejected = Subscription::where('status', 'rejected')->count();

        // Issue statistics
        $issuesPublished = Issue::where('status', 'published')->count();
        $issuesUnpublished = Issue::where('status', 'unpublished')->count();
        $issuesDisactivated = Issue::where('status', 'disactivated')->count();

        // Article statistics
        $articlesPending = Article::where('status', 'pending')->count();
        $articlesAccepted = Article::where('status', 'accepted')->count();
        $articlesRejected = Article::where('status', 'rejected')->count();

        // Theme statistics
        $totalThemes = Theme::count();

        return view('editor.dashboard', compact(
            'totalSubscribers',
            'totalThemeManagers',
            'totalEditors',
            'subscriptionsPending',
            'subscriptionsApproved',
            'subscriptionsRejected',
            'issuesPublished',
            'issuesUnpublished',
            'issuesDisactivated',
            'articlesPending',
            'articlesAccepted',
            'articlesRejected',
            'totalThemes'
        ));
    }

    public function manageUsers()
    {
        $users = User::orderByDesc('id')->get();

        return view('editor.users', compact('users'));
    }

    public function createUser()
    {
        $roles = ['subscriber', 'theme_manager', 'editor'];

        return view('editor.create_user', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:subscriber,theme_manager,editor',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('editor.users')->with('message', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        $roles = ['subscriber', 'theme_manager', 'editor'];

        return view('editor.edit_user', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:subscriber,theme_manager,editor',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('editor.users')->with('message', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        return redirect()->route('editor.users')->with('message', 'User deleted successfully.');
    }

    public function manageIssues()
    {
        // Fetch issues ordered by latest id with accepted articles count
        $issues = Issue::withCount(['articles' => function ($query) {
            $query->where('status', 'accepted');
        }])->orderByDesc('id')->get();

        return view('editor.issues', compact('issues'));
    }

    public function publishIssue(Issue $issue)
    {
        // Publish the issue
        $issue->update(['status' => 'published', 'published_at' => now()]);

        // Create a new issue with empty values
        Issue::create([
            'title' => '',
            'description' => '',
            'status' => 'unpublished',
        ]);

        return redirect()->route('editor.issues')->with('message', 'Issue published and a new issue created.');
    }

    public function disactivateIssue(Issue $issue)
    {
        // Disactivate the issue
        $issue->update(['status' => 'disactivated']);

        return redirect()->route('editor.issues')->with('message', 'Issue disactivated successfully.');
    }

    public function activateIssue(Issue $issue)
    {
        // Activate the issue
        $issue->update(['status' => 'published']);

        return redirect()->route('editor.issues')->with('message', 'Issue activated successfully.');
    }

    public function editIssue(Issue $issue)
    {
        return view('editor.edit_issue', compact('issue'));
    }

    public function updateIssue(Request $request, Issue $issue)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $issue->update($request->only('title', 'description'));

        return redirect()->route('editor.issues')->with('message', 'Issue updated successfully.');
    }

    


    public function manageThemes()
    {
        $themes = Theme::with('user')->orderByDesc('id')->get();

        return view('editor.themes', compact('themes'));
    }

    public function createTheme()
    {
        $themeManagers = User::where('role', 'theme_manager')->get();

        return view('editor.create_theme', compact('themeManagers'));
    }

    public function storeTheme(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('themes', 'public');
        }

        Theme::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'image' => $imagePath,
        ]);

        return redirect()->route('editor.themes')->with('message', 'Theme created successfully.');
    }

    public function editTheme(Theme $theme)
    {
        $themeManagers = User::where('role', 'theme_manager')->get();

        return view('editor.edit_theme', compact('theme', 'themeManagers'));
    }

    public function updateTheme(Request $request, Theme $theme)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old image if new image is uploaded
        if ($request->hasFile('image')) {
            if ($theme->image) {
                Storage::disk('public')->delete($theme->image);
            }
            $imagePath = $request->file('image')->store('themes', 'public');
            $theme->image = $imagePath;
        }

        $theme->update([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('editor.themes')->with('message', 'Theme updated successfully.');
    }

    public function deleteTheme(Theme $theme)
    {
        // Delete theme image if exists
        if ($theme->image) {
            Storage::disk('public')->delete($theme->image);
        }

        $theme->delete();

        return redirect()->route('editor.themes')->with('message', 'Theme deleted successfully.');
    }
}

