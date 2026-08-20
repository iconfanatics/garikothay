<?php

$content = file_get_contents('/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/ShippingZoneResource.php');

// 1. Update labels for filters
$content = str_replace("->label('Filter by Division')", "->label('Select Division (Optional)')", $content);
$content = str_replace("->label('Filter by District')", "->label('Select District (Optional)')", $content);

// 2. Update shipping_type
$shippingTypeOld = <<<CODE
                                Forms\Components\Select::make('shipping_type')
                                    ->label('Shipping Type')
                                    ->options([
                                        'Standard' => 'Standard',
                                        'Express' => 'Express',
                                        'Pickup' => 'Pickup',
                                        'Same Day' => 'Same Day',
                                    ])
                                    ->default('Standard')
                                    ->required(),
CODE;

$shippingTypeNew = <<<CODE
                                Forms\Components\Select::make('shipping_type')
                                    ->label('Shipping Type')
                                    ->options([
                                        'Standard' => 'Standard',
                                        'Express' => 'Express',
                                        'Pickup' => 'Pickup',
                                        'Same Day' => 'Same Day',
                                    ])
                                    ->default('Standard')
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Set \$set, \$state) {
                                        \$defaults = [
                                            'Standard' => ['charge' => 60, 'time' => '3-5 Business Days'],
                                            'Express' => ['charge' => 120, 'time' => '1-2 Business Days'],
                                            'Pickup' => ['charge' => 0, 'time' => 'Same Day'],
                                            'Same Day' => ['charge' => 150, 'time' => 'Same Day'],
                                        ];
                                        if (isset(\$defaults[\$state])) {
                                            \$set('base_charge', \$defaults[\$state]['charge']);
                                            \$set('estimated_delivery_time', \$defaults[\$state]['time']);
                                        }
                                    })
                                    ->required(),
CODE;
$content = str_replace($shippingTypeOld, $shippingTypeNew, $content);

// 3. Update estimated_delivery_time
$estTimeOld = <<<CODE
                                Forms\Components\Select::make('estimated_delivery_time')
                                    ->label('Est. Delivery Time')
                                    ->options([
                                        'Same Day' => 'Same Day',
                                        'Next Day' => 'Next Day',
                                        '1-2 Business Days' => '1-2 Business Days',
                                        '2-3 Business Days' => '2-3 Business Days',
                                        '3-5 Business Days' => '3-5 Business Days',
                                        '5-7 Business Days' => '5-7 Business Days',
                                    ])
                                    ->required(),
CODE;

$estTimeNew = <<<CODE
                                Forms\Components\Select::make('estimated_delivery_time')
                                    ->label('Est. Delivery Time')
                                    ->options(function(Forms\Get \$get) {
                                        \$type = \$get('shipping_type');
                                        if (\$type === 'Same Day') {
                                            return ['Same Day' => 'Same Day'];
                                        }
                                        if (\$type === 'Pickup') {
                                            return ['Same Day' => 'Same Day', 'Next Day' => 'Next Day'];
                                        }
                                        return [
                                            'Same Day' => 'Same Day',
                                            'Next Day' => 'Next Day',
                                            '1-2 Business Days' => '1-2 Business Days',
                                            '2-3 Business Days' => '2-3 Business Days',
                                            '3-5 Business Days' => '3-5 Business Days',
                                            '5-7 Business Days' => '5-7 Business Days',
                                        ];
                                    })
                                    ->required(),
CODE;
$content = str_replace($estTimeOld, $estTimeNew, $content);

file_put_contents('/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/ShippingZoneResource.php', $content);
echo "Updated ShippingZoneResource.php\n";
