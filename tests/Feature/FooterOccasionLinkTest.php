<?php

namespace Tests\Feature;

use App\Models\Occasion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FooterOccasionLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_footer_occasion_link_uses_the_seeded_anniversary_slug(): void
    {
        Occasion::create([
            'name' => 'Anniversary',
            'slug' => 'anniversary',
            'is_active' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(
                'href="' . route('occasions.show', 'anniversary') . '">Occasion Gifts</a>',
                false
            );

        $this->get(route('occasions.show', 'anniversary'))
            ->assertOk()
            ->assertSee('Anniversary');
    }
}
