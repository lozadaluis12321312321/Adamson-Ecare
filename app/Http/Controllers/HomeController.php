<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\HealthArticle;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestAnnouncements = Announcement::published()
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $featuredArticles = HealthArticle::published()
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('public.home', compact('latestAnnouncements', 'featuredArticles'));
    }

    public function healthServices()
    {
        return view('public.services');
    }

    public function articles(Request $request)
    {
        $query = HealthArticle::published();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate(6)->withQueryString();

        $categories = [
            'General Wellness',
            'Mental Well-Being',
            'Dental Health',
            'Hygiene and Prevention',
            'Healthy Lifestyle',
            'Preventive Healthcare',
        ];

        return view('public.articles', compact('articles', 'categories'));
    }

    public function articleDetail($slug)
    {
        $article = HealthArticle::where('slug', $slug)->firstOrFail();
        $relatedArticles = HealthArticle::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->take(3)
            ->get();

        return view('public.article-detail', compact('article', 'relatedArticles'));
    }

    public function announcements(Request $request)
    {
        $query = Announcement::published();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $announcements = $query->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(6)
            ->withQueryString();

        $categories = [
            'Urgent Advisory',
            'Clinic Schedule Update',
            'Health Advisory',
            'Vaccination Drive',
            'General Announcement',
        ];

        return view('public.announcements', compact('announcements', 'categories'));
    }

    public function announcementDetail($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('public.announcement-detail', compact('announcement'));
    }

    public function about()
    {
        return view('public.about');
    }
}
