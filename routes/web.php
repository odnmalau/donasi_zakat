<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicCampaignController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Campaign Routes
Route::get('/campaigns', [PublicCampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{campaign:slug}', [PublicCampaignController::class, 'show'])->name('campaigns.show');
