<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class SearchPageTest extends TestCase
{
    public function test_search_page_accepts_a_missing_query(): void
    {
        $this->get('/search')
            ->assertOk()
            ->assertViewHas('query', '');
    }

    public function test_search_page_accepts_a_null_query_value(): void
    {
        $this->get('/search?q')
            ->assertOk()
            ->assertViewHas('query', '');
    }

    public function test_search_inputs_require_at_least_two_characters(): void
    {
        $this->get('/search')
            ->assertOk()
            ->assertSeeHtml('required minlength="2"')
            ->assertSee('Please type something to search.');
    }
}
