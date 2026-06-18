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
}
