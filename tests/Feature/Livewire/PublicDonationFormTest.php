<?php

use App\Livewire\PublicDonationForm;
use App\Models\Campaign;
use App\Models\Donation;
use Livewire\Livewire;

describe('PublicDonationForm Livewire Component', function () {
    beforeEach(function () {
        $this->artisan('migrate:refresh', ['--seed' => false]);
    });
    describe('rendering', function () {
        it('renders the component', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->assertStatus(200);
        });

        it('displays form fields', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->assertSee('donor_name')
                ->assertSee('donor_email')
                ->assertSee('donor_phone')
                ->assertSee('amount');
        });

        it('shows submitted message when form is submitted', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('submitted', true)
                ->assertSet('submitted', true);
        });
    });

    describe('validation', function () {
        it('requires donor name', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', '')
                ->set('donor_email', 'test@example.com')
                ->set('donor_phone', '081234567890')
                ->set('amount', 50000)
                ->call('submit')
                ->assertHasErrors(['donor_name' => 'required']);
        });

        it('requires donor email', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', '')
                ->set('donor_phone', '081234567890')
                ->set('amount', 50000)
                ->call('submit')
                ->assertHasErrors(['donor_email' => 'required']);
        });

        it('validates email format', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'invalid-email')
                ->set('donor_phone', '081234567890')
                ->set('amount', 50000)
                ->call('submit')
                ->assertHasErrors(['donor_email' => 'email']);
        });

        it('requires donor phone', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'test@example.com')
                ->set('donor_phone', '')
                ->set('amount', 50000)
                ->call('submit')
                ->assertHasErrors(['donor_phone' => 'required']);
        });

        it('requires amount', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'test@example.com')
                ->set('donor_phone', '081234567890')
                ->set('amount', '')
                ->call('submit')
                ->assertHasErrors(['amount' => 'required']);
        });

        it('validates amount is numeric', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'test@example.com')
                ->set('donor_phone', '081234567890')
                ->set('amount', 'not-a-number')
                ->call('submit')
                ->assertHasErrors(['amount' => 'numeric']);
        });

        it('validates amount minimum of 10000', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'test@example.com')
                ->set('donor_phone', '081234567890')
                ->set('amount', 5000)
                ->call('submit')
                ->assertHasErrors(['amount' => 'min']);
        });

        it('allows optional payment proof', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'test@example.com')
                ->set('donor_phone', '081234567890')
                ->set('amount', 50000)
                ->call('submit')
                ->assertHasNoErrors();
        });

        it('allows optional notes', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'test@example.com')
                ->set('donor_phone', '081234567890')
                ->set('amount', 50000)
                ->set('notes', '')
                ->call('submit')
                ->assertHasNoErrors();
        });
    });

    describe('submission', function () {
        it('creates a donation when form is submitted with valid data', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'john@example.com')
                ->set('donor_phone', '081234567890')
                ->set('amount', 50000)
                ->call('submit');

            expect(Donation::where('donor_email', 'john@example.com')->exists())->toBeTrue();
        });

        it('sets donation status to pending', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'john@example.com')
                ->set('donor_phone', '081234567890')
                ->set('amount', 50000)
                ->call('submit');

            $donation = Donation::where('donor_email', 'john@example.com')->first();
            expect($donation->status)->toBe('pending');
        });

        it('associates donation with correct campaign', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'john@example.com')
                ->set('donor_phone', '081234567890')
                ->set('amount', 50000)
                ->call('submit');

            $donation = Donation::where('donor_email', 'john@example.com')->first();
            expect($donation->campaign_id)->toBe($campaign->id);
        });

        it('stores donation with all fields', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'john@example.com')
                ->set('donor_phone', '081234567890')
                ->set('amount', 50000)
                ->set('notes', 'Semoga bermanfaat')
                ->call('submit');

            $donation = Donation::where('donor_email', 'john@example.com')->first();
            expect($donation->donor_name)->toBe('John Doe');
            expect($donation->donor_phone)->toBe('081234567890');
            expect((float) $donation->amount)->toBe(50000.0);
            expect($donation->notes)->toBe('Semoga bermanfaat');
        });

        it('resets form after submission', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'john@example.com')
                ->set('donor_phone', '081234567890')
                ->set('amount', 50000)
                ->call('submit')
                ->assertSet('donor_name', '')
                ->assertSet('donor_email', '')
                ->assertSet('donor_phone', '')
                ->assertSet('amount', '');
        });

        it('sets submitted flag to true after submission', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'john@example.com')
                ->set('donor_phone', '081234567890')
                ->set('amount', 50000)
                ->call('submit')
                ->assertSet('submitted', true);
        });

        it('dispatches donationSubmitted event', function () {
            $campaign = Campaign::factory()->active()->create();

            Livewire::test(PublicDonationForm::class, ['campaign' => $campaign])
                ->set('donor_name', 'John Doe')
                ->set('donor_email', 'john@example.com')
                ->set('donor_phone', '081234567890')
                ->set('amount', 50000)
                ->call('submit')
                ->assertDispatched('donationSubmitted');
        });
    });
});
