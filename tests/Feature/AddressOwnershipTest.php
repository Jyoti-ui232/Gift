<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_address_ignores_submitted_user_id(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($owner)
            ->post(route('account.addresses.store'), $this->addressData([
                'user_id' => $otherUser->id,
            ]))
            ->assertRedirect(route('account.addresses'));

        $address = Address::firstOrFail();
        $this->assertSame($owner->id, $address->user_id);
        $this->assertDatabaseMissing('addresses', ['user_id' => $otherUser->id]);
    }

    public function test_updating_address_ignores_submitted_user_id(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $address = $owner->addresses()->create($this->addressData());

        $this->actingAs($owner)
            ->put(route('account.addresses.update', $address), $this->addressData([
                'user_id' => $otherUser->id,
                'city' => 'Updated City',
            ]))
            ->assertRedirect(route('account.addresses'));

        $this->assertSame($owner->id, $address->fresh()->user_id);
        $this->assertSame('Updated City', $address->fresh()->city);
    }

    public function test_user_cannot_update_or_delete_another_users_address(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $address = $owner->addresses()->create($this->addressData());

        $this->actingAs($otherUser)
            ->put(route('account.addresses.update', $address), $this->addressData([
                'name' => 'Unauthorized Change',
            ]))
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->delete(route('account.addresses.destroy', $address))
            ->assertForbidden();

        $this->assertSame('Recipient Name', $address->fresh()->name);
        $this->assertDatabaseHas('addresses', ['id' => $address->id, 'user_id' => $owner->id]);
    }

    public function test_guest_cannot_create_update_or_delete_addresses(): void
    {
        $owner = User::factory()->create();
        $address = $owner->addresses()->create($this->addressData());

        $this->post(route('account.addresses.store'), $this->addressData())
            ->assertRedirect(route('login'));

        $this->put(route('account.addresses.update', $address), $this->addressData())
            ->assertRedirect(route('login'));

        $this->delete(route('account.addresses.destroy', $address))
            ->assertRedirect(route('login'));
    }

    public function test_invalid_address_field_values_are_rejected(): void
    {
        $user = User::factory()->create();
        $data = $this->addressData([
            'name' => str_repeat('a', 256),
            'phone' => ['invalid'],
            'address_line2' => str_repeat('b', 256),
            'city' => str_repeat('c', 101),
            'type' => 'warehouse',
            'is_default' => 'not-a-boolean',
        ]);

        $this->actingAs($user)
            ->from(route('account.addresses'))
            ->post(route('account.addresses.store'), $data)
            ->assertSessionHasErrors([
                'name',
                'phone',
                'address_line2',
                'city',
                'type',
                'is_default',
            ]);

        $this->assertDatabaseCount('addresses', 0);
    }

    private function addressData(array $overrides = []): array
    {
        return array_replace([
            'name' => 'Recipient Name',
            'phone' => '9876543210',
            'address_line1' => '123 Sample Street',
            'address_line2' => 'Near the park',
            'city' => 'Delhi',
            'state' => 'Delhi',
            'pincode' => '110001',
            'type' => 'home',
            'is_default' => 1,
        ], $overrides);
    }
}
