<?php

use App\Models\Campaign;
use App\Models\Donation;

describe('PublicCampaignController', function () {
    beforeEach(function () {
        $this->artisan('migrate:refresh', ['--seed' => false]);
    });
    describe('index', function () {
        it('displays active campaigns on index page', function () {
            Campaign::factory(5)->active()->create();
            Campaign::factory(2)->completed()->create();
            Campaign::factory(1)->draft()->create();

            $response = $this->get(route('campaigns.index'));

            $response->assertStatus(200)
                ->assertViewIs('campaigns.index')
                ->assertViewHas('campaigns');

            expect($response->viewData('campaigns')->count())->toBe(5);
        });

        it('paginates campaigns with 12 per page', function () {
            Campaign::factory(15)->active()->create();

            $response = $this->get(route('campaigns.index'));

            expect($response->viewData('campaigns')->perPage())->toBe(12);
            expect($response->viewData('campaigns')->count())->toBe(12);
        });

        it('orders campaigns by latest created first', function () {
            $oldest = Campaign::factory()->active()->create(['created_at' => now()->subDays(10)]);
            $newest = Campaign::factory()->active()->create(['created_at' => now()]);

            $response = $this->get(route('campaigns.index'));
            $campaigns = $response->viewData('campaigns');

            expect($campaigns->first()->id)->toBe($newest->id);
            expect($campaigns->last()->id)->toBe($oldest->id);
        });

        it('only shows active campaigns', function () {
            $active = Campaign::factory(3)->active()->create();
            $completed = Campaign::factory(2)->completed()->create();
            $draft = Campaign::factory(1)->draft()->create();

            $response = $this->get(route('campaigns.index'));
            $campaigns = $response->viewData('campaigns');

            $campaignIds = $campaigns->pluck('id')->toArray();
            $active->each(fn($c) => expect(in_array($c->id, $campaignIds))->toBeTrue());
            $completed->each(fn($c) => expect(in_array($c->id, $campaignIds))->toBeFalse());
            $draft->each(fn($c) => expect(in_array($c->id, $campaignIds))->toBeFalse());
        });
    });

    describe('show', function () {
        it('displays active campaign detail page', function () {
            $campaign = Campaign::factory()->active()->create();

            $response = $this->get(route('campaigns.show', $campaign->slug));

            $response->assertStatus(200)
                ->assertViewIs('campaigns.show')
                ->assertViewHas('campaign', $campaign);
        });

        it('returns 404 for inactive campaign', function () {
            $campaign = Campaign::factory()->completed()->create();

            $response = $this->get(route('campaigns.show', $campaign->slug));

            $response->assertNotFound();
        });

        it('returns 404 for draft campaign', function () {
            $campaign = Campaign::factory()->draft()->create();

            $response = $this->get(route('campaigns.show', $campaign->slug));

            $response->assertNotFound();
        });

        it('shows verified donations on campaign detail', function () {
            $campaign = Campaign::factory()->active()->create();
            $verified = Donation::factory(3)->verified()->for($campaign)->create();
            $pending = Donation::factory(2)->pending()->for($campaign)->create();
            $rejected = Donation::factory(1)->rejected()->for($campaign)->create();

            $response = $this->get(route('campaigns.show', $campaign->slug));
            $donations = $response->viewData('donations');

            expect($donations->count())->toBe(3);
            $donations->each(fn($d) => expect($d->status)->toBe('verified'));
        });

        it('limits verified donations to 5 most recent', function () {
            $campaign = Campaign::factory()->active()->create();
            Donation::factory(10)->verified()->for($campaign)->create();

            $response = $this->get(route('campaigns.show', $campaign->slug));
            $donations = $response->viewData('donations');

            expect($donations->count())->toBe(5);
        });

        it('calculates progress percentage correctly', function () {
            $campaign = Campaign::factory()->active()->create([
                'target_amount' => 1000000,
                'collected_amount' => 500000,
            ]);

            $response = $this->get(route('campaigns.show', $campaign->slug));
            $progress = $response->viewData('progress');

            expect($progress)->toBe(50.0);
        });

        it('handles zero target amount for progress calculation', function () {
            $campaign = Campaign::factory()->active()->create([
                'target_amount' => 0,
                'collected_amount' => 0,
            ]);

            $response = $this->get(route('campaigns.show', $campaign->slug));
            $progress = $response->viewData('progress');

            expect($progress)->toBe(0);
        });

        it('shows campaign title on detail page', function () {
            $campaign = Campaign::factory()->active()->create(['title' => 'Kampanye Test Khusus']);

            $response = $this->get(route('campaigns.show', $campaign->slug));

            $response->assertSee('Kampanye Test Khusus');
        });

        it('shows campaign description on detail page', function () {
            $campaign = Campaign::factory()->active()->create(['description' => 'Deskripsi kampanye test']);

            $response = $this->get(route('campaigns.show', $campaign->slug));

            $response->assertSee('Deskripsi kampanye test');
        });
    });
});
