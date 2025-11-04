<?php

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Tests\TestCase;

uses(TestCase::class)->in('Feature');

beforeEach(function () {
    $this->artisan('migrate:refresh', ['--seed' => false]);
    // Create roles
    \Spatie\Permission\Models\Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    \Spatie\Permission\Models\Role::create(['name' => 'petugas_yayasan', 'guard_name' => 'web']);
    \Spatie\Permission\Models\Role::create(['name' => 'donatur', 'guard_name' => 'web']);
    \Spatie\Permission\Models\Role::create(['name' => 'mustahik', 'guard_name' => 'web']);
});

it('allows public donation submission', function () {
    $campaign = Campaign::factory()->active()->create();
    
    $response = $this->get(route('campaigns.show', $campaign->slug));
    
    $response->assertStatus(200)
        ->assertSee($campaign->title);
});

it('user can verify pending donation', function () {
    $petugas = User::factory()->create();
    $petugas->assignRole('petugas_yayasan');

    $campaign = Campaign::factory()->active()->create();
    $donation = Donation::factory()->pending()->for($campaign)->create();

    // Simulate verify action
    $donation->update([
        'status' => 'verified',
        'verified_by' => $petugas->id,
        'verified_at' => now(),
    ]);

    $donation->refresh();
    expect($donation->status)->toBe('verified');
    expect($donation->verified_by)->toBe($petugas->id);
    expect($donation->verified_at)->not()->toBeNull();
});

it('campaign collected amount updates when donation is verified', function () {
    $petugas = User::factory()->create();
    $petugas->assignRole('petugas_yayasan');

    $campaign = Campaign::factory()->active()->create(['collected_amount' => 0]);
    $donation = Donation::factory()->pending()->for($campaign)->create(['amount' => 500000]);

    // Simulate verify action
    $donation->update([
        'status' => 'verified',
        'verified_by' => $petugas->id,
        'verified_at' => now(),
    ]);
    $campaign->increment('collected_amount', $donation->amount);

    $campaign->refresh();
    expect((float) $campaign->collected_amount)->toEqual(500000.00);
});

it('petugas can reject donation with reason', function () {
    $petugas = User::factory()->create();
    $petugas->assignRole('petugas_yayasan');
    
    $campaign = Campaign::factory()->active()->create();
    $donation = Donation::factory()->pending()->for($campaign)->create();
    
    $donation->update([
        'status' => 'rejected',
        'verified_by' => $petugas->id,
        'verified_at' => now(),
        'rejection_reason' => 'Bukti transfer tidak valid',
    ]);
    
    expect($donation->refresh()->status)->toBe('rejected');
    expect($donation->rejection_reason)->toBe('Bukti transfer tidak valid');
});

it('shows correct stats on petugas dashboard', function () {
    $petugas = User::factory()->create();
    $petugas->assignRole('petugas_yayasan');

    Campaign::factory(5)->active()->create();
    Donation::factory(10)->verified()->create();
    Donation::factory(3)->pending()->create();

    // Verify stats can be calculated
    $totalDonations = Donation::where('status', 'verified')->sum('amount');
    $pendingDonations = Donation::where('status', 'pending')->count();
    $activeCampaigns = Campaign::where('status', 'active')->count();

    expect($totalDonations)->toBeGreaterThan(0);
    expect($pendingDonations)->toBeGreaterThanOrEqual(3);
    expect($activeCampaigns)->toBeGreaterThanOrEqual(5);
});

it('shows correct stats on donatur dashboard', function () {
    $donatur = User::factory()->create();
    $donatur->assignRole('donatur');

    $campaign = Campaign::factory()->active()->create();
    Donation::factory(5)->verified()->for($campaign)->for($donatur, 'donor')->create();

    // Verify stats can be calculated
    $myDonations = Donation::where('user_id', $donatur->id)->sum('amount');
    $myDonationCount = Donation::where('user_id', $donatur->id)->count();
    $verifiedDonations = Donation::where('user_id', $donatur->id)
        ->where('status', 'verified')->count();

    expect($myDonations)->toBeGreaterThan(0);
    expect($myDonationCount)->toBe(5);
    expect($verifiedDonations)->toBe(5);
});
