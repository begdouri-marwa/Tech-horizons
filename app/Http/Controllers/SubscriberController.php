<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Models\Theme;
use App\Models\Subscription;
use App\Models\Article;
use App\Models\Issue;
use App\Models\History;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;


class SubscriberController extends Controller
{
    public function dashboard()
    {
        // Get approved subscriptions for the authenticated user
        $approvedSubscriptions = Auth::user()
            ->subscriptions()
            ->where('status', 'approved')
            ->pluck('theme_id');

        // Fetch published issues with articles in the user's subscriptions or public articles
        $issues = Issue::where('status', 'published')
            ->with(['articles' => function ($query) use ($approvedSubscriptions) {
                $query->where(function ($q) use ($approvedSubscriptions) {
                    $q->whereIn('theme_id', $approvedSubscriptions)
                      ->orWhere('target', 'public');
                })
                ->where('status', 'accepted')
                ->latest('created_at');
            }])
            ->latest('id') // Latest issues first
            ->get();

        return view('subscriber.dashboard', compact('issues'));
    }


    public function showArticle($id)
    {
        // $article = Article::findOrFail($id);
        $article = Article::with('chats.user')->findOrFail($id);

        $isAuthorized = $article->target === 'public' ||
            Subscription::where('user_id', Auth::id())
                ->where('theme_id', $article->theme_id)
                ->where('status', 'approved')
                ->exists();

        $theme_manager_access = (Auth::check() && Auth::user()->role === 'theme_manager' && $article->theme->user_id === Auth::id());
        $editor_access = (Auth::check() && Auth::user()->role === 'editor');

        if (($isAuthorized && $article->status === 'accepted') || $theme_manager_access || $editor_access) {
            // Add the article to the user's history
            if (Auth::check() && Auth::user()->role === 'subscriber') {
                History::updateOrCreate(
                    ['user_id' => Auth::id(), 'article_id' => $id],
                    ['created_at' => now()]
                );
            }

            return view('subscriber.article', [
                'content' => $article,
                'isAuthorized' => $isAuthorized,
            ]);
            // return view('subscriber.article', ['content' => $article]);
        }

        return redirect()->route('subscriber.dashboard')->with('error', 'You are not authorized to view this article.');
    }

    public function storeMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $article = Article::findOrFail($id);

        $isAuthorized = $article->target === 'public' ||
            Subscription::where('user_id', Auth::id())
                ->where('theme_id', $article->theme_id)
                ->where('status', 'approved')
                ->exists();

        if (!$isAuthorized || $article->status !== 'accepted') {
            return back()->with('error', 'You are not authorized to post a message.');
        }

        Chat::create([
            'user_id' => Auth::id(),
            'article_id' => $id,
            'message' => $request->message,
        ]);

        return back()->with('message', 'Message posted successfully.');
    }

    public function deleteMessage($id)
    {
        $chat = Chat::findOrFail($id);

        // Ensure the logged-in subscriber owns the message
        if (Auth::id() !== $chat->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $chat->delete();

        return back()->with('message', 'Your message has been deleted.');
    }


    public function viewThemes()
    {
        $themes = Theme::all();
        return view('subscriber.themes', compact('themes'));
    }

    public function subscribeRequest(Request $request)
    {
        $request->validate([
            'theme_id' => 'required|exists:themes,id',
        ]);

        $user = Auth::user();
        $themeId = $request->input('theme_id');

        // Check if the user is already subscribed to this theme
        $existingSubscription = Subscription::where('user_id', $user->id)
                                            ->where('theme_id', $themeId)
                                            ->first();

        if ($existingSubscription) {
            return back()->with('error', 'You have already requested to subscribe to this theme.');
        }

        // Create a new subscription
        Subscription::create([
            'user_id' => $user->id,
            'theme_id' => $themeId,
            'status' => 'pending',
        ]);

        return back()->with('message', 'Subscription request sent successfully.');
    }

    public function unsubscribeRequest(Request $request)
    {
        $request->validate([
            'theme_id' => 'required|exists:themes,id',
        ]);

        // Find the subscription for the authenticated user and the specified theme
        $subscription = Subscription::where('user_id', Auth::id())
                                    ->where('theme_id', $request->input('theme_id'))
                                    ->where('status', 'approved')
                                    ->first();

        if ($subscription) {
            // Delete the subscription
            $subscription->delete();

            // Check if the user has any remaining approved subscriptions
            $remainingSubscriptions = Subscription::where('user_id', Auth::id())
                                                  ->where('status', 'approved')
                                                  ->exists();

            // If no approved subscriptions remain, update the user's role to 'guest'
            if (!$remainingSubscriptions) {
                $user = Auth::user();
                $user->update(['role' => 'guest']);
            }

            return back()->with('message', 'Unsubscribed successfully.');
        }

        return back()->with('error', 'You are not subscribed to this theme or your subscription is not approved.');
    }


    public function cancelRequest(Request $request)
    {
        $request->validate([
            'theme_id' => 'required|exists:themes,id',
        ]);

        $subscription = Subscription::where('user_id', Auth::id())
                                    ->where('theme_id', $request->input('theme_id'))
                                    ->where('status', 'pending')
                                    ->first();

        if ($subscription) {
            $subscription->delete();
            return back()->with('message', 'Subscription request canceled.');
        }

        return back()->with('error', 'No pending subscription request found.');
    }


    public function viewHistory(Request $request)
    {
        // Get the search term from the request
        $search = $request->input('search');

        // Get the IDs of themes with approved subscriptions for the authenticated user
        $approvedThemeIds = Auth::user()
            ->subscriptions()
            ->where('status', 'approved')
            ->pluck('theme_id');

        // Fetch history entries with articles that match the approved themes or are public
        $historyQuery = History::with(['article' => function ($query) use ($approvedThemeIds, $search) {
            $query->where(function ($query) use ($approvedThemeIds) {
                $query->whereIn('theme_id', $approvedThemeIds)
                      ->orWhere('target', 'public');
            })
            ->where('status', 'accepted'); // Ensure the articles are accepted

            // If a search term is provided, apply the search filter
            if (!empty($search)) {
                $query->where('title', 'like', '%' . $search . '%');
            }
        }])
        ->where('user_id', Auth::id())
        ->latest('created_at');

        $history = $historyQuery->get();

        return view('subscriber.history', compact('history', 'search'));
    }


    public function viewMyArticles()
    {
        $myArticles = Article::where('user_id', Auth::id())
            ->latest('created_at')
            ->get();

        return view('subscriber.my_articles', compact('myArticles'));
    }

    public function viewProposedArticle($id)
    {
        $article = Article::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('subscriber.view_article', compact('article'));
    }
    public function showProposeArticleForm()
    {
        // Get the themes the user is subscribed to and approved
        $subscribedThemes = Auth::user()->subscriptions()
            ->where('status', 'approved')
            ->with('theme')
            ->get()
            ->pluck('theme');

        return view('subscriber.propose_article', compact('subscribedThemes'));
    }

    public function submitProposeArticle(Request $request)
    {
        $request->validate([
            'theme_id' => 'required|exists:themes,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Store the image if uploaded
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        // Find the last issue with status 'unpublished'
        $latestUnpublishedIssue = Issue::where('status', 'unpublished')->latest('id')->first();

        if (!$latestUnpublishedIssue) {
            return redirect()->back()->with('error', 'No unpublished issue available. Please contact the administrator.');
        }

        // Create a new article with 'pending' status
        Article::create([
            'user_id' => Auth::id(),
            'theme_id' => $request->theme_id,
            'issue_id' => $latestUnpublishedIssue->id,
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'status' => 'pending',
            'target' => 'subscribers', // Default target for proposed articles
        ]);

        return redirect()->route('subscriber.dashboard')->with('message', 'Article proposed successfully and is awaiting approval.');
    }


    public function rateArticle(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Rating::updateOrCreate(
            ['user_id' => auth()->id(), 'article_id' => $request->article_id],
            ['rating' => $request->rating]
        );

        return back()->with('message', 'Rating submitted successfully');
    }

}
