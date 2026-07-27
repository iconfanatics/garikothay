<?php

namespace App\Filament\Resources\VariantValueResource\Pages;

use App\Filament\Resources\VariantValueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVariantValue extends EditRecord
{
    protected static string $resource = VariantValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
