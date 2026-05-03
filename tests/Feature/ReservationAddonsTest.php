<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Services\Payments\StripePaymentIntentService;
use App\Services\Settings\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationAddonsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(SettingService::class)->putMany([
            SettingService::KEY_STRIPE_ENABLED => true,
            SettingService::KEY_STRIPE_PUBLISHABLE => 'pk_test_demo',
            SettingService::KEY_STRIPE_SECRET => 'sk_test_demo',
            SettingService::KEY_RESERVATION_CURRENCY => 'USD',
            SettingService::KEY_RESERVATION_DEPOSIT_PERCENTAGE => 20,
            SettingService::KEY_RESERVATION_ADDON_GROUPS => [
                [
                    'code' => 'airport_transfer',
                    'title' => 'Airport transfer',
                    'selection_type' => 'single',
                    'is_required' => false,
                    'sort_order' => 0,
                    'options' => [
                        ['code' => 'none', 'label' => 'No transfer', 'price' => 0, 'price_type' => 'flat', 'is_active' => true, 'sort_order' => 0],
                        ['code' => 'vip', 'label' => 'VIP transfer', 'price' => 50, 'price_type' => 'flat', 'is_active' => true, 'sort_order' => 1],
                    ],
                ],
                [
                    'code' => 'optional_tours',
                    'title' => 'Optional tours',
                    'selection_type' => 'multiple',
                    'is_required' => false,
                    'sort_order' => 1,
                    'options' => [
                        ['code' => 'balloon', 'label' => 'Balloon', 'price' => 30, 'price_type' => 'per_person', 'is_active' => true, 'sort_order' => 0],
                        ['code' => 'dinner', 'label' => 'Dinner', 'price' => 40, 'price_type' => 'flat', 'is_active' => true, 'sort_order' => 1],
                    ],
                ],
            ],
        ]);

        $this->app->bind(StripePaymentIntentService::class, function () {
            return new class extends StripePaymentIntentService
            {
                public function create(string $secretKey, int $amount, string $currency, array $metadata = []): array
                {
                    return [
                        'id' => 'pi_test_123',
                        'client_secret' => 'pi_test_123_secret',
                    ];
                }
            };
        });
    }

    public function test_single_select_group_rejects_multiple_values(): void
    {
        $response = $this->post(route('reservation.intent'), [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'adults' => 2,
            'children' => 0,
            'estimated_total' => 1000,
            'addons' => [
                'airport_transfer' => ['none', 'vip'],
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['addons.airport_transfer']);
    }

    public function test_multi_select_group_accepts_many_values(): void
    {
        $response = $this->postJson(route('reservation.intent'), [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'adults' => 2,
            'children' => 0,
            'estimated_total' => 1000,
            'addons' => [
                'optional_tours' => ['balloon', 'dinner'],
            ],
        ]);

        $response->assertOk();
    }

    public function test_unknown_option_is_rejected(): void
    {
        $response = $this->postJson(route('reservation.intent'), [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'adults' => 2,
            'children' => 0,
            'estimated_total' => 1000,
            'addons' => [
                'optional_tours' => ['missing_option'],
            ],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['addons.optional_tours.0']);
    }

    public function test_deposit_uses_total_including_per_person_addons(): void
    {
        $response = $this->postJson(route('reservation.intent'), [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'adults' => 2,
            'children' => 1,
            'estimated_total' => 1000,
            'addons' => [
                'optional_tours' => ['balloon'],
            ],
        ]);

        $response->assertOk();

        /** @var Reservation $reservation */
        $reservation = Reservation::query()->firstOrFail();
        $this->assertSame(9000, $reservation->addons_total); // 30 * 3 travelers * 100
        $this->assertSame(109000, $reservation->estimated_total); // (1000 + 90) * 100
        $this->assertSame(21800, $reservation->deposit_amount); // 20%
    }
}
