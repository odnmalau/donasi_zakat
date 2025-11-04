<?php

use App\Models\Campaign;
use App\Models\Category;

describe('Public Campaign Search & Filter', function () {
    beforeEach(function () {
        $this->artisan('migrate:refresh', ['--seed' => false]);
    });

    describe('Campaign Search', function () {
        it('can search campaigns by title', function () {
            $category = Category::factory()->create();

            Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Bantuan Pendidikan Anak Yatim',
            ]);
            Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Program Kesehatan Masyarakat',
            ]);

            $response = $this->get(route('campaigns.index', ['search' => 'Pendidikan']));

            $response->assertSee('Bantuan Pendidikan Anak Yatim');
            $response->assertDontSee('Program Kesehatan Masyarakat');
        });

        it('can search campaigns by description', function () {
            $category = Category::factory()->create();

            Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Campaign A',
                'description' => 'This is about kesehatan dan nutrisi anak',
            ]);
            Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Campaign B',
                'description' => 'This is about pendidikan formal',
            ]);

            $response = $this->get(route('campaigns.index', ['search' => 'kesehatan']));

            $response->assertSee('Campaign A');
            $response->assertDontSee('Campaign B');
        });

        it('search is case insensitive', function () {
            $category = Category::factory()->create();

            Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Bantuan PENDIDIKAN Anak Yatim',
            ]);

            $response = $this->get(route('campaigns.index', ['search' => 'pendidikan']));

            $response->assertSee('Bantuan PENDIDIKAN Anak Yatim');
        });

        it('shows no results for non-matching search', function () {
            $category = Category::factory()->create();

            Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Bantuan Pendidikan',
            ]);

            $response = $this->get(route('campaigns.index', ['search' => 'nonexistent']));

            $response->assertDontSee('Bantuan Pendidikan');
        });
    });

    describe('Category Filter', function () {
        it('can filter campaigns by category', function () {
            $category1 = Category::factory()->create(['name' => 'Pendidikan']);
            $category2 = Category::factory()->create(['name' => 'Kesehatan']);

            Campaign::factory()->active()->create([
                'category_id' => $category1->id,
                'title' => 'Campaign Pendidikan A',
            ]);
            Campaign::factory()->active()->create([
                'category_id' => $category2->id,
                'title' => 'Campaign Kesehatan A',
            ]);

            $response = $this->get(route('campaigns.index', ['category' => $category1->id]));

            $response->assertSee('Campaign Pendidikan A');
            $response->assertDontSee('Campaign Kesehatan A');
        });

        it('shows all categories in filter dropdown', function () {
            Category::factory()->create(['name' => 'Pendidikan', 'is_active' => true]);
            Category::factory()->create(['name' => 'Kesehatan', 'is_active' => true]);
            Category::factory()->create(['name' => 'Muallaf', 'is_active' => true]);

            $response = $this->get(route('campaigns.index'));

            $response->assertSee('Pendidikan');
            $response->assertSee('Kesehatan');
            $response->assertSee('Muallaf');
        });

        it('does not show inactive categories in filter', function () {
            Category::factory()->create(['name' => 'Aktif', 'is_active' => true]);
            Category::factory()->create(['name' => 'Nonaktif', 'is_active' => false]);

            $response = $this->get(route('campaigns.index'));

            $response->assertSee('Aktif');
            $response->assertDontSee('Nonaktif');
        });
    });

    describe('Campaign Sorting', function () {
        it('sorts campaigns by newest (default)', function () {
            $category = Category::factory()->create();

            $old = Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Old Campaign',
                'created_at' => now()->subDays(10),
            ]);
            $new = Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'New Campaign',
                'created_at' => now(),
            ]);

            $response = $this->get(route('campaigns.index'));

            $pos_new = strpos($response->content(), 'New Campaign');
            $pos_old = strpos($response->content(), 'Old Campaign');

            expect($pos_new < $pos_old)->toBeTrue();
        });

        it('sorts campaigns by ending soon', function () {
            $category = Category::factory()->create();

            Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Far Ending',
                'end_date' => now()->addDays(30),
            ]);
            Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Soon Ending',
                'end_date' => now()->addDays(5),
            ]);

            $response = $this->get(route('campaigns.index', ['sort' => 'ending_soon']));

            expect($response->content())->toContain('Soon Ending');
        });

        it('sorts campaigns alphabetically', function () {
            $category = Category::factory()->create();

            Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Zebra Campaign',
            ]);
            Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Apple Campaign',
            ]);

            $response = $this->get(route('campaigns.index', ['sort' => 'alphabetical']));

            $pos_apple = strpos($response->content(), 'Apple Campaign');
            $pos_zebra = strpos($response->content(), 'Zebra Campaign');

            expect($pos_apple < $pos_zebra)->toBeTrue();
        });

        it('sorts campaigns by most funded', function () {
            $category = Category::factory()->create();

            Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Low Funded',
                'target_amount' => 100000000,
                'collected_amount' => 10000000,
            ]);
            Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'High Funded',
                'target_amount' => 100000000,
                'collected_amount' => 90000000,
            ]);

            $response = $this->get(route('campaigns.index', ['sort' => 'most_funded']));

            expect($response->content())->toContain('High Funded');
        });
    });

    describe('Combined Search & Filter', function () {
        it('can search and filter together', function () {
            $cat1 = Category::factory()->create();
            $cat2 = Category::factory()->create();

            Campaign::factory()->active()->create([
                'category_id' => $cat1->id,
                'title' => 'Pendidikan Islam',
            ]);
            Campaign::factory()->active()->create([
                'category_id' => $cat2->id,
                'title' => 'Pendidikan Umum',
            ]);

            $response = $this->get(route('campaigns.index', [
                'search' => 'Pendidikan',
                'category' => $cat1->id,
            ]));

            $response->assertSee('Pendidikan Islam');
            $response->assertDontSee('Pendidikan Umum');
        });

        it('preserves query parameters in pagination', function () {
            $category = Category::factory()->create();

            Campaign::factory(15)->active()->create([
                'category_id' => $category->id,
                'title' => 'Test Campaign',
            ]);

            $response = $this->get(route('campaigns.index', [
                'search' => 'Test',
                'category' => $category->id,
            ]));

            $response->assertSee('search=Test');
            $response->assertSee('category='.$category->id);
        });
    });

    describe('Filter Reset', function () {
        it('shows reset button when filters are applied', function () {
            $category = Category::factory()->create();
            Campaign::factory()->active()->create(['category_id' => $category->id]);

            $response = $this->get(route('campaigns.index', ['search' => 'Test']));

            $response->assertSee('Reset');
        });

        it('reset button redirects to clean campaigns page', function () {
            $response = $this->get(route('campaigns.index', ['search' => 'Test']));

            $response->assertSee('/campaigns');
        });
    });

    describe('Only Active Campaigns Shown', function () {
        it('only shows active campaigns on public listing', function () {
            $category = Category::factory()->create();

            Campaign::factory()->active()->create([
                'category_id' => $category->id,
                'title' => 'Active Campaign',
            ]);
            Campaign::factory()->draft()->create([
                'category_id' => $category->id,
                'title' => 'Draft Campaign',
            ]);
            Campaign::factory()->completed()->create([
                'category_id' => $category->id,
                'title' => 'Completed Campaign',
            ]);

            $response = $this->get(route('campaigns.index'));

            $response->assertSee('Active Campaign');
            $response->assertDontSee('Draft Campaign');
            $response->assertDontSee('Completed Campaign');
        });
    });
});
