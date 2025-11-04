<?php

beforeEach(function () {
    $this->artisan('migrate:refresh', ['--seed' => false]);
});

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
