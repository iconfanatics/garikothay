<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
auth()->login($user);

$order = \App\Models\Order::where('user_id', $user->id)->first();
if (!$order) {
    echo "No order found\n";
    exit;
}

$request = \Illuminate\Http\Request::create('/account/orders/' . $order->order_number, 'GET');
$response = app()->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() == 500) {
    echo "500 Error!\n";
    echo $response->getContent() . "\n";
}
