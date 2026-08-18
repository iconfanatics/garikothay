<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admin = \App\Models\Admin::first();
auth('admin')->login($admin);

$request = \Illuminate\Http\Request::create('/admin/reviews', 'GET');
$response = app()->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() == 500) {
    echo "500 Error!\n";
    echo $response->getContent() . "\n";
}
