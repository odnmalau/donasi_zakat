<?php

use App\Models\Campaign;
use App\Models\Category;

describe('Campaign Category Feature', function () {
    beforeEach(function () {
        $this->artisan('migrate:refresh', ['--seed' => false]);
    });

    describe('Category Management', function () {
        it('can create a category', function () {
            $category = Category::factory()->create([
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'is_active' => true,
            ]);

            expect($category)->toBeTruthy();
            expect($category->name)->toBe('Pendidikan');
            expect($category->is_active)->toBeTrue();
        });

        it('can update a category', function () {
            $category = Category::factory()->create();

            $category->update([
                'name' => 'Kesehatan Masyarakat',
                'description' => 'Program kesehatan untuk masyarakat luas',
            ]);

            expect($category->fresh()->name)->toBe('Kesehatan Masyarakat');
            expect($category->fresh()->description)->toContain('kesehatan');
        });

        it('can delete a category', function () {
            $category = Category::factory()->create();
            $categoryId = $category->id;

            $category->delete();

            expect(Category::find($categoryId))->toBeNull();
        });

        it('category slug is unique', function () {
            Category::factory()->create(['slug' => 'pendidikan']);

            expect(fn () => Category::factory()->create(['slug' => 'pendidikan']))->toThrow(\Illuminate\Database\QueryException::class);
        });
    });

    describe('Campaign Category Relationship', function () {
        it('campaign can be associated with a category', function () {
            $category = Category::factory()->create();
            $campaign = Campaign::factory()->create([
                'category_id' => $category->id,
            ]);

            expect($campaign->category_id)->toBe($category->id);
            expect($campaign->category->id)->toBe($category->id);
        });

        it('campaign category relationship works', function () {
            $category = Category::factory()->create();
            $campaign = Campaign::factory()->create(['category_id' => $category->id]);

            expect($campaign->category)->toBeInstanceOf(Category::class);
            expect($campaign->category->name)->toBe($category->name);
        });

        it('category has many campaigns', function () {
            $category = Category::factory()->create();
            Campaign::factory(5)->create(['category_id' => $category->id]);

            expect($category->campaigns()->count())->toBe(5);
        });

        it('campaign without category shows null', function () {
            $campaign = Campaign::factory()->create(['category_id' => null]);

            expect($campaign->category)->toBeNull();
        });
    });

    describe('Category Filtering', function () {
        it('can filter campaigns by category', function () {
            $category1 = Category::factory()->create();
            $category2 = Category::factory()->create();

            Campaign::factory(3)->create(['category_id' => $category1->id, 'status' => 'active']);
            Campaign::factory(2)->create(['category_id' => $category2->id, 'status' => 'active']);

            $filtered = Campaign::where('category_id', $category1->id)->count();

            expect($filtered)->toBe(3);
        });

        it('can get campaigns without category', function () {
            Campaign::factory(2)->create(['category_id' => null, 'status' => 'active']);
            Campaign::factory(2)->create(['status' => 'active']);

            $withoutCategory = Campaign::whereNull('category_id')->count();

            expect($withoutCategory)->toBeGreaterThanOrEqual(2);
        });

        it('can get active categories only', function () {
            Category::factory()->create(['is_active' => true]);
            Category::factory()->create(['is_active' => true]);
            Category::factory()->create(['is_active' => false]);

            $activeCategories = Category::where('is_active', true)->count();

            expect($activeCategories)->toBeGreaterThanOrEqual(2);
        });
    });
});
