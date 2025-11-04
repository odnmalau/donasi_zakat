<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Donation;
use Livewire\Component;
use Livewire\WithFileUploads;

class PublicDonationForm extends Component
{
    use WithFileUploads;

    public Campaign $campaign;

    public string $donor_name = '';
    public string $donor_email = '';
    public string $donor_phone = '';
    public string $amount = '';
    public $payment_proof;
    public string $notes = '';
    public bool $submitted = false;

    protected $rules = [
        'donor_name' => 'required|string|max:255',
        'donor_email' => 'required|email|max:255',
        'donor_phone' => 'required|string|max:20',
        'amount' => 'required|numeric|min:10000',
        'payment_proof' => 'nullable|image|max:5120',
        'notes' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'donor_name.required' => 'Nama donatur harus diisi',
        'donor_email.required' => 'Email harus diisi',
        'donor_email.email' => 'Email harus valid',
        'donor_phone.required' => 'Nomor telepon harus diisi',
        'amount.required' => 'Jumlah donasi harus diisi',
        'amount.min' => 'Jumlah donasi minimal Rp 10.000',
        'payment_proof.image' => 'File harus berupa gambar',
        'payment_proof.max' => 'Ukuran file maksimal 5 MB',
    ];

    public function submit()
    {
        $this->validate();

        $paymentProofPath = null;
        if ($this->payment_proof) {
            $paymentProofPath = $this->payment_proof->store('donations', 'public');
        }

        Donation::create([
            'campaign_id' => $this->campaign->id,
            'donor_name' => $this->donor_name,
            'donor_email' => $this->donor_email,
            'donor_phone' => $this->donor_phone,
            'amount' => (int) $this->amount,
            'payment_proof_path' => $paymentProofPath,
            'notes' => $this->notes,
            'status' => 'pending',
        ]);

        $this->submitted = true;
        $this->reset(['donor_name', 'donor_email', 'donor_phone', 'amount', 'payment_proof', 'notes']);

        $this->dispatch('donationSubmitted');
    }

    public function render()
    {
        return view('livewire.public-donation-form');
    }
}
