<?php

use App\Filament\Resources\Donations\Pages\EditDonation;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Livewire\Livewire;

describe('Donation Filament Resource', function () {
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
    });

    describe('Edit Donation - Verify Action', function () {
        it('shows verify button for pending donations', function () {
            $campaign = Campaign::factory()->active()->create();
            $donation = Donation::factory()->pending()->for($campaign)->create();

            Livewire::actingAs($this->admin)
                ->test(EditDonation::class, ['record' => $donation->getKey()])
                ->assertActionVisible('verify');
        });

        it('hides verify button for verified donations', function () {
            $campaign = Campaign::factory()->active()->create();
            $donation = Donation::factory()->verified()->for($campaign)->create();

            Livewire::actingAs($this->admin)
                ->test(EditDonation::class, ['record' => $donation->getKey()])
                ->assertActionHidden('verify');
        });

        it('hides verify button for rejected donations', function () {
            $campaign = Campaign::factory()->active()->create();
            $donation = Donation::factory()->rejected()->for($campaign)->create();

            Livewire::actingAs($this->admin)
                ->test(EditDonation::class, ['record' => $donation->getKey()])
                ->assertActionHidden('verify');
        });
    });

    describe('Edit Donation - Reject Action', function () {
        it('shows reject button for pending donations', function () {
            $campaign = Campaign::factory()->active()->create();
            $donation = Donation::factory()->pending()->for($campaign)->create();

            Livewire::actingAs($this->admin)
                ->test(EditDonation::class, ['record' => $donation->getKey()])
                ->assertActionVisible('reject');
        });

        it('hides reject button for verified donations', function () {
            $campaign = Campaign::factory()->active()->create();
            $donation = Donation::factory()->verified()->for($campaign)->create();

            Livewire::actingAs($this->admin)
                ->test(EditDonation::class, ['record' => $donation->getKey()])
                ->assertActionHidden('reject');
        });

        it('hides reject button for rejected donations', function () {
            $campaign = Campaign::factory()->active()->create();
            $donation = Donation::factory()->rejected()->for($campaign)->create();

            Livewire::actingAs($this->admin)
                ->test(EditDonation::class, ['record' => $donation->getKey()])
                ->assertActionHidden('reject');
        });
    });
});
