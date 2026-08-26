<?php

namespace App\Filament\Resources\ShippingZoneResource\Pages;

use App\Filament\Resources\ShippingZoneResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateShippingZone extends CreateRecord
{
    protected static string $resource = ShippingZoneResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $type = $data['zone_type'] ?? null;
        
        if ($type === 'Division' && isset($data['coverage_areas_division'])) {
            $data['coverage_areas'] = $data['coverage_areas_division'];
        } elseif ($type === 'District' && isset($data['coverage_areas_district'])) {
            $data['coverage_areas'] = $data['coverage_areas_district'];
        } elseif ($type === 'Upazila-Thana' && isset($data['coverage_areas_upazila'])) {
            $data['coverage_areas'] = $data['coverage_areas_upazila'];
        } elseif ($type === 'Custom Area' && isset($data['coverage_areas_tags'])) {
            $data['coverage_areas'] = $data['coverage_areas_tags'];
        } else {
            $data['coverage_areas'] = [];
        }

        unset(
            $data['coverage_areas_division'],
            $data['coverage_areas_district'],
            $data['coverage_areas_upazila'],
            $data['coverage_areas_tags'],
            $data['division_filter'],
            $data['district_filter']
        );

        return $data;
    }
}
