<?php
session_start();

// Configuration
$base_url = "https://sh.mrbean.dev/"; // Replace with your domain
$json_file = "urls.json";
$recaptcha_secret_key = "6LeGVDoqAAAAANihT8BXwmSPra2MZetxSWhybTdh"; // Replace with your reCAPTCHA secret key
$recaptcha_site_key = "6LeGVDoqAAAAAHhtHWt3tUFbz-1mvbj0eG3A5BmZ"; // Replace with your reCAPTCHA site key

// Helper functions
function generate_short_code() {
    return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6);
}

function load_urls() {
    global $json_file;
    return file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];
}

function save_urls($urls) {
    global $json_file;
    file_put_contents($json_file, json_encode($urls, JSON_PRETTY_PRINT));
}

function shorten_url($long_url) {
    $urls = load_urls();
    
    // Check if the URL has already been shortened
    foreach ($urls as $short_code => $stored_url) {
        if ($stored_url === $long_url) {
            return $short_code;
        }
    }
    
    // If not, create a new short code
    do {
        $short_code = generate_short_code();
    } while (isset($urls[$short_code]));
    
    $urls[$short_code] = $long_url;
    save_urls($urls);
    return $short_code;
}

function verify_recaptcha($response) {
    global $recaptcha_secret_key;
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $recaptcha_secret_key,
        'response' => $response,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return json_decode($result)->success;
}

// Handle API requests
if (isset($_GET['url'])) {
    $long_url = filter_var($_GET['url'], FILTER_SANITIZE_URL);
    if (filter_var($long_url, FILTER_VALIDATE_URL)) {
        $short_code = shorten_url($long_url);
        echo json_encode(['short_url' => $base_url . $short_code]);
    } else {
        echo json_encode(['error' => 'Invalid URL']);
    }
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $long_url = filter_var($_POST["url"], FILTER_SANITIZE_URL);
    $recaptcha_response = $_POST['g-recaptcha-response'];

    if (!verify_recaptcha($recaptcha_response)) {
        $_SESSION["error"] = "reCAPTCHA verification failed. Please try again.";
    } elseif (filter_var($long_url, FILTER_VALIDATE_URL)) {
        $short_code = shorten_url($long_url);
        $_SESSION["short_url"] = $base_url . $short_code;
    } else {
        $_SESSION["error"] = "Invalid URL. Please enter a valid URL.";
    }
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

// Handle URL redirection
$request_uri = $_SERVER["REQUEST_URI"];
if (preg_match("/^\/([a-zA-Z0-9]{6})$/", $request_uri, $matches)) {
    $short_code = $matches[1];
    $urls = load_urls();
    if (isset($urls[$short_code])) {
        header("Location: " . $urls[$short_code]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL-Short</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>URL-Short</h1>
        <form method="post" action="">
            <div class="input-group">
                <input type="url" name="url" placeholder="Enter your long URL" required>
                <button type="submit"><i class="fas fa-link"></i></button>
            </div>
            <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_site_key; ?>"></div>
        </form>
        <?php if (isset($_SESSION["short_url"])): ?>
            <div class="result">
                <p>Your shortened URL:</p>
                <div class="short-url-container">
                    <input type="text" id="shortUrl" value="<?php echo $_SESSION["short_url"]; ?>" readonly>
                    <button id="copyButton" onclick="copyShortUrl()">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
            <?php unset($_SESSION["short_url"]); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION["error"])): ?>
            <div class="error"><?php echo $_SESSION["error"]; ?></div>
            <?php unset($_SESSION["error"]); ?>
        <?php endif; ?>
    </div>
    <script src="script.js"></script>
</body>
</html>