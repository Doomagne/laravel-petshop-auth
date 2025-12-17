<?php

/**
 * CSRF/session smoke test for local debugging of 419 Page Expired.
 *
 * Usage:
 *   php tools/csrf_smoke_test.php http://127.0.0.1:8000
 *
 * What it does:
 *   - GET /login (captures cookies + CSRF token)
 *   - POST /login with wrong credentials using captured token + cookies
 *   - Prints status codes + Set-Cookie headers so you can spot:
 *       - Secure cookies being set on http://
 *       - Wrong Domain attribute
 *       - No session cookie being set
 */

declare(strict_types=1);

function fail(string $msg): void {
    fwrite(STDERR, $msg . PHP_EOL);
    exit(1);
}

function http_request(string $method, string $url, array $headers = [], ?string $body = null): array {
    $ch = curl_init();
    if ($ch === false) {
        fail('Failed to init curl');
    }

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 10,
    ]);

    if ($body !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }

    $raw = curl_exec($ch);
    if ($raw === false) {
        $err = curl_error($ch);
        curl_close($ch);
        fail("curl_exec failed: {$err}");
    }

    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);

    $rawHeaders = substr($raw, 0, $headerSize);
    $content = substr($raw, $headerSize);

    $headerLines = preg_split("/\\r\\n|\\n|\\r/", trim($rawHeaders)) ?: [];
    $setCookies = [];
    foreach ($headerLines as $line) {
        if (stripos($line, 'Set-Cookie:') === 0) {
            $setCookies[] = trim(substr($line, strlen('Set-Cookie:')));
        }
    }

    return [
        'status' => $status,
        'headers_raw' => $rawHeaders,
        'set_cookies' => $setCookies,
        'body' => $content,
    ];
}

function cookie_header_from_set_cookie(array $setCookies): string {
    $pairs = [];
    foreach ($setCookies as $sc) {
        // Take only the "name=value" part before first ';'
        $parts = explode(';', $sc, 2);
        $nv = trim($parts[0] ?? '');
        if ($nv !== '') {
            $pairs[] = $nv;
        }
    }
    return implode('; ', $pairs);
}

$base = $argv[1] ?? 'http://127.0.0.1:8000';
$base = rtrim($base, '/');

echo "Base: {$base}" . PHP_EOL;

// 1) GET /login
$get = http_request('GET', "{$base}/login");
echo PHP_EOL . "GET /login status: {$get['status']}" . PHP_EOL;
echo "GET Set-Cookie (" . count($get['set_cookies']) . "):" . PHP_EOL;
foreach ($get['set_cookies'] as $c) {
    echo "  - {$c}" . PHP_EOL;
}

if ($get['status'] !== 200) {
    fail("Expected 200 from GET /login, got {$get['status']}");
}

if (!preg_match('/name="_token"\\s+value="([^"]+)"/', $get['body'], $m)) {
    fail('Could not find CSRF token input in /login HTML');
}
$token = $m[1];
echo "CSRF token length: " . strlen($token) . PHP_EOL;

$cookieHeader = cookie_header_from_set_cookie($get['set_cookies']);
echo "Cookie header for POST: " . ($cookieHeader === '' ? '(none)' : $cookieHeader) . PHP_EOL;

// 2) POST /login (intentionally wrong credentials; we only care about 419 vs 302/422)
$postBody = http_build_query([
    '_token' => $token,
    'email' => 'nope@example.com',
    'password' => 'wrong-password',
]);

$post = http_request('POST', "{$base}/login", [
    'Content-Type: application/x-www-form-urlencoded',
    'Cookie: ' . $cookieHeader,
], $postBody);

echo PHP_EOL . "POST /login status: {$post['status']}" . PHP_EOL;
echo "POST Set-Cookie (" . count($post['set_cookies']) . "):" . PHP_EOL;
foreach ($post['set_cookies'] as $c) {
    echo "  - {$c}" . PHP_EOL;
}

if ($post['status'] === 419) {
    echo PHP_EOL . "Result: 419 reproduced. This strongly indicates the session cookie is not persisting." . PHP_EOL;
    echo "Check above Set-Cookie for 'Secure' on HTTP, or an unexpected 'Domain=' attribute." . PHP_EOL;
    exit(0);
}

echo PHP_EOL . "Result: POST did not return 419. (Status={$post['status']})" . PHP_EOL;
echo "If your browser still shows 419, it may be using a different host (localhost vs 127.0.0.1) or cached cookies." . PHP_EOL;




