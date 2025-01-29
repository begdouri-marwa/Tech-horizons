<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Theme;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Chat;
use App\Models\Article;
use App\Models\Issue;
use App\Models\History;
use App\Models\Rating;

class ThemeManagerController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();

        // Fetch themes managed by the current user
        $themes = Theme::where('user_id', $userId)
            ->withCount(['subscriptions' => function ($query) {
                $query->where('status', 'approved');
            }])->get();

        // Count total subscribers across all themes
        $totalSubscribers = $themes->sum('subscriptions_count');

        // Count articles for each theme and in total
        $articles = Article::whereIn('theme_id', $themes->pluck('id'))
            ->where('status', 'accepted')
            ->get();
        $articlesCountByTheme = $articles->groupBy('theme_id')->map->count();
        $totalArticles = $articles->count();

        // Ratings and history
        $ratings = Rating::whereIn('article_id', $articles->pluck('id'))->get();
        $averageRatingByTheme = $ratings->groupBy('article_id')->map->avg('rating');

        $historyCount = History::whereIn('article_id', $articles->pluck('id'))->count();

        return view('theme_manager.dashboard', compact(
            'themes',
            'articles',
            'totalSubscribers',
            'articlesCountByTheme',
            'totalArticles',
            'averageRatingByTheme',
            'historyCount'
        ));
    }

    public function moderateThemes()
    {
        $themes = Theme::where('user_id', auth()->id())
            ->with(['subscriptions' => function ($query) {
                $query->where('status', 'pending')->with('user');
            }])
            ->get();

        return view('theme_manager.themes', compact('themes'));
    }

    public function acceptSubscription(Request $request, Theme $theme, Subscription $subscription)
    {
        // Ensure the subscription belongs to the theme managed by the logged-in user
        if ($theme->user_id !== auth()->id() || $subscription->theme_id !== $theme->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Update subscription status to 'approved'
        $subscription->update(['status' => 'approved']);

        // Update the user's role to 'subscriber' if they were a 'guest'
        $user = User::find($subscription->user_id);
        if ($user->role === 'guest') {
            $user->update(['role' => 'subscriber']);
        }

        return redirect()->back()->with('message', 'Subscription approved successfully.');
    }

    public function rejectSubscription(Request $request, Theme $theme, Subscription $subscription)
    {
        // Ensure the subscription belongs to the theme managed by the logged-in user
        if ($theme->user_id !== auth()->id() || $subscription->theme_id !== $theme->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Update subscription status to 'rejected'
        $subscription->update(['status' => 'rejected']);

        return redirect()->back()->with('message', 'Subscription rejected successfully.');
    }

    public function editTheme(Theme $theme)
    {
        // Ensure the logged-in user is the owner of the theme
        if ($theme->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('theme_manager.edit_theme', compact('theme'));
    }

    public function updateTheme(Request $request, Theme $theme)
    {
        // Ensure the logged-in user is the owner of the theme
        if ($theme->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle the image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('themes', 'public');
            $theme->image = $imagePath;
        }

        // Update the theme's description
        $theme->description = $request->description;
        $theme->save();

        return redirect()->route('theme_manager.themes')->with('message', 'Theme updated successfully.');
    }

    public function moderateArticles()
    {
        // Get the IDs of themes managed by the logged-in theme manager
        $managedThemeIds = Theme::where('user_id', Auth::id())->pluck('id');

        // Fetch all articles belonging to the managed themes
        $articles = Article::whereIn('theme_id', $managedThemeIds)->with('theme')->get();

        return view('theme_manager.articles', compact('articles'));
    }

    public function createArticle()
    {
        $themes = Theme::where('user_id', Auth::id())->get();
        return view('theme_manager.create_article', compact('themes'));
    }

    public function storeArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'theme_id' => 'required|exists:themes,id',
            'content' => 'required|string',
            'target' => 'required|in:subscribers,public',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('articles', 'public') : null;

        // Find the last issue with status 'unpublished'
        $latestUnpublishedIssue = Issue::where('status', 'unpublished')->latest('id')->first();

        if (!$latestUnpublishedIssue) {
            return redirect()->back()->with('error', 'No unpublished issue available. Please contact the administrator.');
        }

        Article::create([
            'user_id' => Auth::id(),
            'theme_id' => $request->theme_id,
            'issue_id' => $latestUnpublishedIssue->id,
            'title' => $request->title,
            'content' => $request->content,
            'status' => 'accepted',
            'target' => $request->target,
            'image' => $imagePath,
        ]);

        return redirect()->route('theme_manager.articles')->with('message', 'Article created successfully.');
    }

    public function editArticle(Article $article)
    {
        // Check if the article belongs to a theme managed by the logged-in user
        $isManagedByUser = Theme::where('user_id', Auth::id())
            ->where('id', $article->theme_id)
            ->exists();

        if (!$isManagedByUser) {
            abort(403, 'Unauthorized action.');
        }

        $themes = Theme::where('user_id', Auth::id())->get();
        return view('theme_manager.edit_article', compact('article', 'themes'));
    }

    public function updateArticle(Request $request, Article $article)
    {
        // Check if the article belongs to a theme managed by the logged-in user
        $isManagedByUser = Theme::where('user_id', Auth::id())
            ->where('id', $article->theme_id)
            ->exists();

        if (!$isManagedByUser) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'theme_id' => 'required|exists:themes,id',
            'content' => 'required|string',
            'target' => 'required|in:subscribers,public',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete the existing image if it exists
            if ($article->image && \Storage::disk('public')->exists($article->image)) {
                \Storage::disk('public')->delete($article->image);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('articles', 'public');
            $article->image = $imagePath;
        }

        $article->update([
            'title' => $request->title,
            'theme_id' => $request->theme_id,
            'content' => $request->content,
            'target' => $request->target,
        ]);

        return redirect()->route('theme_manager.articles')->with('message', 'Article updated successfully.');
    }

    public function deleteArticle(Article $article)
    {
        // Check if the article belongs to a theme managed by the logged-in user
        $isManagedByUser = Theme::where('user_id', Auth::id())
            ->where('id', $article->theme_id)
            ->exists();

        if (!$isManagedByUser) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the image if it exists
        if ($article->image && \Storage::disk('public')->exists($article->image)) {
            \Storage::disk('public')->delete($article->image);
        }

        $article->delete();
        return redirect()->route('theme_manager.articles')->with('message', 'Article deleted successfully.');
    }

    public function deleteMessage($id)
    {
        $chat = Chat::findOrFail($id);
        $article = Article::findOrFail($chat->article_id);

        if (Auth::id() !== $article->theme->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $chat->delete();

        return back()->with('message', 'Message deleted successfully.');
    }

    public function proposedArticles()
    {
        // Get proposed articles for the themes moderated by the current user
        $articles = Article::whereHas('theme', function ($query) {
            $query->where('user_id', Auth::id());
        })->where('status', 'pending')->with('theme')->get();

        return view('theme_manager.proposed_articles', compact('articles'));
    }

    public function viewProposedArticle(Article $article)
    {
        // Ensure the current user is the moderator of the theme
        if ($article->theme->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('theme_manager.view_proposed_article', compact('article'));
    }

    public function acceptProposedArticle(Article $article)
    {
        // Ensure the current user is the moderator of the theme
        if ($article->theme->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $article->update(['status' => 'accepted']);

        return redirect()->route('theme_manager.proposed_articles')->with('message', 'Article accepted successfully.');
    }

    public function rejectProposedArticle(Article $article)
    {
        // Ensure the current user is the moderator of the theme
        if ($article->theme->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $article->update(['status' => 'rejected']);

        return redirect()->route('theme_manager.proposed_articles')->with('message', 'Article rejected successfully.');
    }

}
