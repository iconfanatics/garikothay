<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
auth()->login($user);

$product = \App\Models\Product::first();

$validator = validator([
    'product_id' => $product->id,
    'rating' => 5,
    'title' => 'Great!',
    'comment' => 'Awesome product',
], [
    'product_id' => ['required', 'exists:products,id'],
    'rating' => ['required', 'integer', 'min:1', 'max:5'],
    'title' => ['nullable', 'string', 'max:255'],
    'comment' => ['nullable', 'string', 'max:2000'],
]);

if ($validator->fails()) {
    echo "Validation failed\n";
    print_r($validator->errors()->toArray());
    exit;
}

try {
    $exists = \App\Models\Review::where('user_id', auth()->id())
        ->where('product_id', $product->id)
        ->exists();

    if ($exists) {
        echo "Already reviewed\n";
    } else {
        \App\Models\Review::create([
            ...$validator->validated(),
            'user_id' => auth()->id(),
            'is_approved' => false,
        ]);
        echo "Created successfully\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
