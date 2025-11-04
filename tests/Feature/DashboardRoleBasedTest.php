<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

describe('Dashboard Role-Based Widgets', function () {
    beforeEach(function () {
        $this->artisan('migrate:refresh', ['--seed' => false]);

        // Create roles
        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::create(['name' => 'petugas_yayasan', 'guard_name' => 'web']);
        Role::create(['name' => 'donatur', 'guard_name' => 'web']);
        Role::create(['name' => 'mustahik', 'guard_name' => 'web']);
    });

    describe('Super Admin Dashboard', function () {
        it('super admin has super_admin role', function () {
            $admin = User::factory()->create();
            $admin->assignRole('super_admin');

            expect($admin->hasRole('super_admin'))->toBeTrue();
        });

        it('super admin widget method returns all widgets', function () {
            $admin = User::factory()->create();
            $admin->assignRole('super_admin');

            $this->actingAs($admin);

            $dashboard = new \App\Filament\Pages\Dashboard;
            $widgets = $dashboard->getWidgets();

            // Super admin should have 3 widgets
            expect(count($widgets))->toBe(3);
        });
    });

    describe('Petugas Yayasan Dashboard', function () {
        it('petugas is assigned correct role', function () {
            $petugas = User::factory()->create();
            $petugas->assignRole('petugas_yayasan');

            expect($petugas->hasRole('petugas_yayasan'))->toBeTrue();
        });

        it('petugas widget method returns only petugas widget', function () {
            $petugas = User::factory()->create();
            $petugas->assignRole('petugas_yayasan');

            $this->actingAs($petugas);

            $dashboard = new \App\Filament\Pages\Dashboard;
            $widgets = $dashboard->getWidgets();

            // Petugas should have 1 widget
            expect(count($widgets))->toBe(1);
        });
    });

    describe('Donatur Dashboard', function () {
        it('donatur is assigned correct role', function () {
            $donatur = User::factory()->create();
            $donatur->assignRole('donatur');

            expect($donatur->hasRole('donatur'))->toBeTrue();
        });

        it('donatur widget method returns only donatur widget', function () {
            $donatur = User::factory()->create();
            $donatur->assignRole('donatur');

            $this->actingAs($donatur);

            $dashboard = new \App\Filament\Pages\Dashboard;
            $widgets = $dashboard->getWidgets();

            // Donatur should have 1 widget
            expect(count($widgets))->toBe(1);
        });
    });

    describe('Mustahik Dashboard', function () {
        it('mustahik is assigned correct role', function () {
            $mustahik = User::factory()->create();
            $mustahik->assignRole('mustahik');

            expect($mustahik->hasRole('mustahik'))->toBeTrue();
        });

        it('mustahik widget method returns only mustahik widget', function () {
            $mustahik = User::factory()->create();
            $mustahik->assignRole('mustahik');

            $this->actingAs($mustahik);

            $dashboard = new \App\Filament\Pages\Dashboard;
            $widgets = $dashboard->getWidgets();

            // Mustahik should have 1 widget
            expect(count($widgets))->toBe(1);
        });
    });

    describe('Role Validation', function () {
        it('user with multiple roles uses correct role', function () {
            $user = User::factory()->create();
            $user->assignRole('super_admin');

            // Should have super_admin role
            expect($user->hasRole('super_admin'))->toBeTrue();

            // Should not have other roles (assuming single role per user in this app)
            expect($user->hasRole('donatur'))->toBeFalse();
        });

        it('each role is distinct', function () {
            $admin = User::factory()->create();
            $petugas = User::factory()->create();
            $donatur = User::factory()->create();
            $mustahik = User::factory()->create();

            $admin->assignRole('super_admin');
            $petugas->assignRole('petugas_yayasan');
            $donatur->assignRole('donatur');
            $mustahik->assignRole('mustahik');

            expect($admin->hasRole('super_admin'))->toBeTrue();
            expect($petugas->hasRole('petugas_yayasan'))->toBeTrue();
            expect($donatur->hasRole('donatur'))->toBeTrue();
            expect($mustahik->hasRole('mustahik'))->toBeTrue();

            // Cross-check to ensure roles don't overlap
            expect($admin->hasRole('donatur'))->toBeFalse();
            expect($donatur->hasRole('super_admin'))->toBeFalse();
        });

        it('user without role cannot access admin panel', function () {
            $user = User::factory()->create();

            $response = $this->actingAs($user)
                ->get(route('filament.admin.pages.dashboard'));

            // Should redirect to login or show 403
            expect($response->status())->toBeIn([403, 404]);
        });
    });

    describe('Dashboard Accessibility', function () {
        it('user without role has no widgets', function () {
            $user = User::factory()->create();

            $this->actingAs($user);

            $dashboard = new \App\Filament\Pages\Dashboard;
            $widgets = $dashboard->getWidgets();

            // User without role should have 0 widgets
            expect(count($widgets))->toBe(0);
        });

        it('all roles have appropriate widget counts', function () {
            $testCases = [
                'super_admin' => 3,
                'petugas_yayasan' => 1,
                'donatur' => 1,
                'mustahik' => 1,
            ];

            foreach ($testCases as $role => $expectedCount) {
                $user = User::factory()->create();
                $user->assignRole($role);

                $this->actingAs($user);

                $dashboard = new \App\Filament\Pages\Dashboard;
                $widgets = $dashboard->getWidgets();

                expect(count($widgets))->toBe($expectedCount);
            }
        });
    });
});
