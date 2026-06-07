<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Dossier;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Hash;
use Illuminate\Support\Str;

function createTokenForUser($user)
{
    $token = $user->createToken('test-token-'.Str::random(6))->plainTextToken;
    echo "USER_TOKEN {$user->id} {$user->role} {$token}\n";

    return $token;
}

// Agent: prefer id=1 if exists
$agent = User::find(1);
if (! $agent) {
    echo "No user with id=1 found, aborting.\n";
    exit(1);
}
$agent->role = 'agent';
$agent->save();
$agentToken = createTokenForUser($agent);

// Expert: find existing expert or create one
$expert = User::where('role', 'expert')->first();
if (! $expert) {
    $email = 'expert+'.time().'@example.com';
    $expert = User::create([
        'name' => 'Expert',
        'prenom' => 'Test',
        'email' => $email,
        'telephone' => '0000000000',
        'pays' => 'FR',
        'date_naissance' => date('Y-m-d'),
        'password' => Hash::make('secret123'),
        'role' => 'expert',
    ]);
    echo "Created expert user {$expert->id} {$expert->email}\n";
}
$expertToken = createTokenForUser($expert);

// Find a dossier to attach notifications to
$dossier = Dossier::first();
if (! $dossier) {
    echo "No dossier found. Create a dossier before running tests.\n";
    exit(1);
}
$dossierId = $dossier->id;

function sendNotification($token, $payload)
{
    $ch = curl_init('http://localhost:8000/api/notifications');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer '.$token,
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $res = curl_exec($ch);
    $err = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "HTTP {$code}: ";
    if ($err) {
        echo "CURL ERROR: $err\n";
    }
    echo $res."\n";
}

echo "Sending agent -> expert notification...\n";
sendNotification($agentToken, [
    'type' => 'message',
    'message' => 'Test message from agent to expert',
    'dossier_id' => $dossierId,
    'recipient_role' => 'expert',
]);

echo "Sending expert -> agent notification...\n";
sendNotification($expertToken, [
    'type' => 'message',
    'message' => 'Test message from expert to agent',
    'dossier_id' => $dossierId,
    'recipient_role' => 'agent',
]);

// Fetch notifications for agent
function fetchNotifications($token)
{
    $ch = curl_init('http://localhost:8000/api/notifications');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.$token,
    ]);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "\nAgent notifications (HTTP {$code}):\n";
    if ($err) {
        echo "CURL ERROR: $err\n";
    }
    echo $res."\n";
}

fetchNotifications($agentToken);

echo "Done.\n";
