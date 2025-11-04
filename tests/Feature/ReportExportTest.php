<?php

use App\Exports\CampaignExport;
use App\Exports\DonationExport;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;

describe('Report Export Feature', function () {
    beforeEach(function () {
        $this->artisan('migrate:refresh', ['--seed' => false]);
    });

    describe('Campaign Export', function () {
        it('campaign export class exists', function () {
            $export = new CampaignExport;

            expect($export)->toBeInstanceOf(CampaignExport::class);
        });

        it('campaign export can query all campaigns', function () {
            $category = Category::factory()->create();
            Campaign::factory(5)->create(['category_id' => $category->id]);

            $export = new CampaignExport;
            $campaigns = $export->query()->get();

            expect($campaigns->count())->toBe(5);
        });

        it('campaign export can filter by status', function () {
            $category = Category::factory()->create();

            Campaign::factory(3)->active()->create(['category_id' => $category->id]);
            Campaign::factory(2)->draft()->create(['category_id' => $category->id]);

            $export = new CampaignExport('active');
            $campaigns = $export->query()->get();

            expect($campaigns->count())->toBe(3);
        });

        it('campaign export has correct headings', function () {
            $export = new CampaignExport;
            $headings = $export->headings();

            expect($headings)->toContain('ID');
            expect($headings)->toContain('Judul');
            expect($headings)->toContain('Kategori');
            expect($headings)->toContain('Target Dana');
            expect($headings)->toContain('Dana Terkumpul');
            expect($headings)->toContain('Persentase');
            expect($headings)->toContain('Status');
        });

        it('campaign export maps data correctly', function () {
            $category = Category::factory()->create(['name' => 'Pendidikan']);
            $campaign = Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Test Campaign',
                'target_amount' => 1000000,
                'collected_amount' => 500000,
            ]);

            $export = new CampaignExport;
            $mapped = $export->map($campaign);

            expect($mapped[0])->toBe($campaign->id);
            expect($mapped[1])->toBe('Test Campaign');
            expect($mapped[2])->toBe('Pendidikan');
            expect($mapped[4])->toContain('500');
        });
    });

    describe('Donation Export', function () {
        it('donation export class exists', function () {
            $export = new DonationExport;

            expect($export)->toBeInstanceOf(DonationExport::class);
        });

        it('donation export can query all donations', function () {
            $category = Category::factory()->create();
            $campaign = Campaign::factory()->active()->create(['category_id' => $category->id]);

            Donation::factory(5)->verified()->for($campaign)->create();

            $export = new DonationExport;
            $donations = $export->query()->get();

            expect($donations->count())->toBe(5);
        });

        it('donation export can filter by status', function () {
            $category = Category::factory()->create();
            $campaign = Campaign::factory()->active()->create(['category_id' => $category->id]);

            Donation::factory(3)->verified()->for($campaign)->create();
            Donation::factory(2)->pending()->for($campaign)->create();

            $export = new DonationExport('verified');
            $donations = $export->query()->get();

            expect($donations->count())->toBe(3);
        });

        it('donation export can filter by campaign', function () {
            $category = Category::factory()->create();
            $campaign1 = Campaign::factory()->active()->create(['category_id' => $category->id]);
            $campaign2 = Campaign::factory()->active()->create(['category_id' => $category->id]);

            Donation::factory(3)->verified()->for($campaign1)->create();
            Donation::factory(2)->verified()->for($campaign2)->create();

            $export = new DonationExport(null, $campaign1->id);
            $donations = $export->query()->get();

            expect($donations->count())->toBe(3);
        });

        it('donation export has correct headings', function () {
            $export = new DonationExport;
            $headings = $export->headings();

            expect($headings)->toContain('ID');
            expect($headings)->toContain('Kampanye');
            expect($headings)->toContain('Nama Donatur');
            expect($headings)->toContain('Jumlah Donasi');
            expect($headings)->toContain('Status');
        });

        it('donation export maps data correctly', function () {
            $category = Category::factory()->create();
            $campaign = Campaign::factory()->active()->create(['category_id' => $category->id]);
            $donation = Donation::factory()->verified()->for($campaign)->create([
                'donor_name' => 'John Doe',
                'amount' => 250000,
            ]);

            $export = new DonationExport;
            $mapped = $export->map($donation);

            expect($mapped[0])->toBe($donation->id);
            expect($mapped[2])->toBe('John Doe');
            expect($mapped[5])->toContain('250');
        });
    });

    describe('Export Views', function () {
        it('campaigns pdf view exists', function () {
            $campaigns = Campaign::all();

            $view = view('exports.campaigns-pdf', compact('campaigns'));

            expect($view)->toBeTruthy();
        });

        it('donations pdf view exists', function () {
            $donations = Donation::all();

            $view = view('exports.donations-pdf', compact('donations'));

            expect($view)->toBeTruthy();
        });

        it('campaigns pdf view has campaign data', function () {
            $category = Category::factory()->create();
            $campaign = Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Test Campaign',
            ]);

            $campaigns = collect([$campaign]);
            $content = view('exports.campaigns-pdf', compact('campaigns'))->render();

            expect($content)->toContain('Test Campaign');
            expect($content)->toContain('Laporan Kampanye');
        });

        it('donations pdf view has donation data', function () {
            $category = Category::factory()->create();
            $campaign = Campaign::factory()->active()->create(['category_id' => $category->id]);
            $donation = Donation::factory()->verified()->for($campaign)->create([
                'donor_name' => 'Jane Doe',
            ]);

            $donations = collect([$donation]);
            $content = view('exports.donations-pdf', compact('donations'))->render();

            expect($content)->toContain('Jane Doe');
            expect($content)->toContain('Laporan Data Donasi');
        });
    });
});
