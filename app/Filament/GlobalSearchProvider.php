<?php

namespace App\Filament;

use Filament\GlobalSearch\DefaultGlobalSearchProvider;
use Filament\GlobalSearch\GlobalSearchResults;
use Filament\GlobalSearch\GlobalSearchResult;
use Filament\Facades\Filament;
use Illuminate\Support\Str;

class GlobalSearchProvider extends DefaultGlobalSearchProvider
{
    public function getResults(string $query): ?GlobalSearchResults
    {
        // Get the default resource results (Products, Orders, Customers, etc.)
        $builder = parent::getResults($query) ?? GlobalSearchResults::make();

        // Custom search for Modules / Navigation Items
        $navigationGroups = Filament::getNavigation();
        $moduleResults = [];

        foreach ($navigationGroups as $group) {
            foreach ($group->getItems() as $item) {
                if (Str::contains(strtolower((string) $item->getLabel()), strtolower($query))) {
                    $moduleResults[] = new GlobalSearchResult(
                        title: $item->getLabel(),
                        url: $item->getUrl(),
                        details: ['Category' => $group->getLabel() ?? 'General']
                    );
                }
            }
        }

        if (count($moduleResults) > 0) {
            $builder->category('Modules & Settings', $moduleResults);
        }

        return $builder;
    }
}
