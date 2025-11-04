<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active campaigns
        $campaigns = Campaign::where('status', 'active')->get();

        foreach ($campaigns as $campaign) {
            // Create 10-20 donations per campaign
            $donationCount = rand(10, 20);

            // Create pending donations (50% of total)
            Donation::factory()
                ->count(intval($donationCount * 0.5))
                ->pending()
                ->for($campaign, 'campaign')
                ->create();

            // Create verified donations (40% of total)
            Donation::factory()
                ->count(intval($donationCount * 0.4))
                ->verified()
                ->for($campaign, 'campaign')
                ->create();

            // Create rejected donations (10% of total)
            Donation::factory()
                ->count(intval($donationCount * 0.1))
                ->rejected()
                ->for($campaign, 'campaign')
                ->create();
        }

        // Also create donations for completed campaigns
        $completedCampaigns = Campaign::where('status', 'completed')->get();
        foreach ($completedCampaigns as $campaign) {
            Donation::factory()
                ->count(30)
                ->verified()
                ->for($campaign, 'campaign')
                ->create();
        }
    }
}
