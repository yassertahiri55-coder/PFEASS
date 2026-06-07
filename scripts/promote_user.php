<?php

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$uid = $argv[1] ?? 1;
$user = User::find($uid);
if (! $user) {
    echo "User not found\n";
    exit(1);
}
$user->role = 'agent';
$user->save();
echo "updated: {$user->id}:{$user->role}\n";
