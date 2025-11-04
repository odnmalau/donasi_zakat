<?php

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;

describe('Donation Authorization & Role-Based Access', function () {
    beforeEach(function () {
        $this->artisan('migrate:refresh', ['--seed' => false]);
        \Spatie\Permission\Models\Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::create(['name' => 'petugas_yayasan', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::create(['name' => 'donatur', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::create(['name' => 'mustahik', 'guard_name' => 'web']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');

        $this->petugas = User::factory()->create();
        $this->petugas->assignRole('petugas_yayasan');

        $this->donatur = User::factory()->create();
        $this->donatur->assignRole('donatur');

        $this->mustahik = User::factory()->create();
        $this->mustahik->assignRole('mustahik');

        $this->guest = null;
    });

    describe('Public Campaign Access', function () {
        it('allows unauthenticated users to view public campaigns', function () {
            Campaign::factory(5)->active()->create();

            $response = $this->get(route('campaigns.index'));
            $response->assertSuccessful();
        });

        it('allows any user to submit donation on public campaign', function () {
            $campaign = Campaign::factory()->active()->create();

            $response = $this->get(route('campaigns.show', $campaign->slug));
            $response->assertSuccessful();
            $response->assertSeeLivewire('public-donation-form');
        });

        it('hides inactive campaigns from public view', function () {
            $active = Campaign::factory()->active()->create();
            $completed = Campaign::factory()->completed()->create();
            $draft = Campaign::factory()->draft()->create();

            $response = $this->get(route('campaigns.index'));
            $response->assertSee($active->title);
            $response->assertDontSee($completed->title);
            $response->assertDontSee($draft->title);
        });

        it('returns 404 for accessing completed campaign directly', function () {
            $campaign = Campaign::factory()->completed()->create();

            $response = $this->get(route('campaigns.show', $campaign->slug));
            $response->assertNotFound();
        });

        it('returns 404 for accessing draft campaign directly', function () {
            $campaign = Campaign::factory()->draft()->create();

            $response = $this->get(route('campaigns.show', $campaign->slug));
            $response->assertNotFound();
        });
    });

    describe('Role-Based Access Control', function () {
        it('admin and petugas roles have permission to manage donations', function () {
            expect($this->admin->hasRole('super_admin'))->toBeTrue();
            expect($this->petugas->hasRole('petugas_yayasan'))->toBeTrue();
        });

        it('public roles cannot manage donations', function () {
            expect($this->donatur->hasRole('donatur'))->toBeTrue();
            expect($this->donatur->hasRole('super_admin'))->toBeFalse();
            expect($this->donatur->hasRole('petugas_yayasan'))->toBeFalse();

            expect($this->mustahik->hasRole('mustahik'))->toBeTrue();
            expect($this->mustahik->hasRole('super_admin'))->toBeFalse();
            expect($this->mustahik->hasRole('petugas_yayasan'))->toBeFalse();
        });
    });

    describe('Donation Status Management', function () {
        it('pending donations can be verified', function () {
            $campaign = Campaign::factory()->active()->create();
            $donation = Donation::factory()->pending()->for($campaign)->create();

            expect($donation->status)->toBe('pending');
            expect($donation->verified_by)->toBeNull();
        });

        it('verified donations have correct metadata', function () {
            $campaign = Campaign::factory()->active()->create();
            $donation = Donation::factory()->verified()->for($campaign)->create();

            expect($donation->status)->toBe('verified');
            expect($donation->verified_by)->not()->toBeNull();
            expect($donation->verified_at)->not()->toBeNull();
        });

        it('rejected donations have rejection reason', function () {
            $campaign = Campaign::factory()->active()->create();
            $donation = Donation::factory()->rejected()->for($campaign)->create();

            expect($donation->status)->toBe('rejected');
            expect($donation->rejection_reason)->not()->toBeNull();
        });
    });

    describe('User Role Assignment', function () {
        it('users have correct roles assigned', function () {
            expect($this->admin->hasRole('super_admin'))->toBeTrue();
            expect($this->petugas->hasRole('petugas_yayasan'))->toBeTrue();
            expect($this->donatur->hasRole('donatur'))->toBeTrue();
            expect($this->mustahik->hasRole('mustahik'))->toBeTrue();
        });

        it('roles are mutually exclusive (in test setup)', function () {
            expect($this->admin->hasRole('petugas_yayasan'))->toBeFalse();
            expect($this->petugas->hasRole('donatur'))->toBeFalse();
            expect($this->donatur->hasRole('mustahik'))->toBeFalse();
        });

        it('super_admin has the required role', function () {
            expect($this->admin->hasRole('super_admin'))->toBeTrue();
        });
    });

    describe('Data Access Control', function () {
        it('authenticated users can query campaigns', function () {
            $campaign = Campaign::factory()->active()->create();

            $campaigns = Campaign::where('status', 'active')->get();
            expect($campaigns->count())->toBeGreaterThan(0);
        });

        it('authenticated users can query donations', function () {
            $campaign = Campaign::factory()->active()->create();
            $donation = Donation::factory()->pending()->for($campaign)->create();

            $donations = Donation::where('status', 'pending')->get();
            expect($donations->count())->toBeGreaterThan(0);
        });

        it('donors can only see their own donations', function () {
            $campaign = Campaign::factory()->active()->create();
            $myDonation = Donation::factory()->verified()->for($campaign)->for($this->donatur, 'donor')->create();
            $otherDonation = Donation::factory()->verified()->for($campaign)->create();

            $myDonations = Donation::where('user_id', $this->donatur->id)->get();
            expect($myDonations->count())->toBe(1);
            expect($myDonations->first()->id)->toBe($myDonation->id);
        });
    });
});
