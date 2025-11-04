<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class PublicCampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = Campaign::where('status', 'active');

        // Search by title and description
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($category = $request->get('category')) {
            $query->where('category_id', $category);
        }

        // Sort options
        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'ending_soon' => $query->orderBy('end_date', 'asc'),
            'most_funded' => $query->orderByRaw('(collected_amount / target_amount) DESC'),
            'alphabetical' => $query->orderBy('title', 'asc'),
            default => $query->orderBy('created_at', 'desc'), // newest
        };

        $campaigns = $query->paginate(12)->appends($request->query());
        $categories = \App\Models\Category::where('is_active', true)->get();

        return view('campaigns.index', compact('campaigns', 'categories'));
    }

    public function show(Campaign $campaign)
    {
        abort_if($campaign->status !== 'active', 404);

        $donations = $campaign->donations()
            ->where('status', 'verified')
            ->latest()
            ->limit(5)
            ->get();

        $progress = $campaign->target_amount > 0
            ? ($campaign->collected_amount / $campaign->target_amount) * 100
            : 0;

        return view('campaigns.show', compact('campaign', 'donations', 'progress'));
    }
}
