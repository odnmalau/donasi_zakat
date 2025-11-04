<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class PublicCampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('campaigns.index', compact('campaigns'));
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
