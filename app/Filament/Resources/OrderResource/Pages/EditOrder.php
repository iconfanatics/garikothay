<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Save')
                ->action('save')
                ->color('primary'),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Handle deduplication of items in the cart
        if (isset($data['items']) && is_array($data['items'])) {
            $mergedItems = [];
            foreach ($data['items'] as $item) {
                $key = $item['product_id'] . '_' . ($item['variant_id'] ?? '0');
                if (isset($mergedItems[$key])) {
                    $mergedItems[$key]['quantity'] += (float) $item['quantity'];
                    $mergedItems[$key]['total_price'] += (float) $item['total_price'];
                } else {
                    $mergedItems[$key] = $item;
                }
            }
            $data['items'] = array_values($mergedItems);
        }

        return $data;
    }
}
