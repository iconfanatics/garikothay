<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
auth()->login($user);

$product = \App\Models\Product::first();

$request = \App\Http\Requests\StoreReviewRequest::create('/reviews', 'POST', [
    'product_id' => $product->id,
    'rating' => 5,
    'title' => 'Great!',
    'comment' => 'Awesome product',
]);
$request->setContainer($app);

$controller = new \App\Http\Controllers\Frontend\ReviewController();
try {
    $response = $controller->store($request);
    echo "Response status: " . $response->getStatusCode() . "\n";
    echo "Redirect URL: " . $response->getTargetUrl() . "\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
