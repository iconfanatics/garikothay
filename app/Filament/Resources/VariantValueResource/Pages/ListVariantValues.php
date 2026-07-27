<?php

namespace App\Filament\Resources\VariantValueResource\Pages;

use App\Filament\Resources\VariantValueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVariantValues extends ListRecords
{
    protected static string $resource = VariantValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
