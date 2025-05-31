<?php


namespace App\HTTP\Controllers;

use App\Core\Response;
use App\HTTP\Services\HomeService;

session_start();

class HomeController
{
    //handles home page
    public function index()
    {
        //unset($_SESSION['content']);
        Response::view("index");
        //print_r($_SESSION["content"]);
    }

    public function search()
    {
        $url = null;

        if (isset($_POST['q'])) $url = $_POST['q'];

        if (isset($url)) {
            $data = HomeService::fetchBasicInfo($url);

            if ($data) {
                if (!isset($_SESSION['content']) || !is_array($_SESSION['content'])) {
                    $_SESSION['content'] = [];
                }
                array_push($_SESSION["content"], $data);
            }
        }
        header("Location: /");
    }

    public function reset()
    {
        unset($_SESSION["content"]);

        header("Location: /");
    }

    public function download()
    {
        // ───────────────────────────────────────────────────────────────────────────
        // 1) Retrieve & validate input
        // ───────────────────────────────────────────────────────────────────────────
        $encodedUrl = $_GET['url']   ?? null;
        $title      = $_GET['title'] ?? 'video';

        if (! $encodedUrl) {
            http_response_code(400);
            echo "Missing video URL";
            exit;
        }

        // 1a) Decode the URL and fix HTML entities
        $url = urldecode($encodedUrl);
        $url = str_replace('&amp;', '&', $url);

        // ───────────────────────────────────────────────────────────────────────────
        // 2) Send “download” headers
        // ───────────────────────────────────────────────────────────────────────────
        header('Content-Description: File Transfer');
        header('Content-Type: video/mp4');
        header('Content-Disposition: attachment; filename="' . basename($title) . '.mp4"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Transfer-Encoding: binary');

        // ───────────────────────────────────────────────────────────────────────────
        // 3) Disable PHP timeouts & output buffering
        // ───────────────────────────────────────────────────────────────────────────
        set_time_limit(0);
        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', '1');
        }
        @ini_set('zlib.output_compression', 'Off');
        while (ob_get_level() > 0) {
            ob_end_flush();
        }
        ob_implicit_flush(true);

        // ───────────────────────────────────────────────────────────────────────────
        // 4) Open php://output for streaming
        // ───────────────────────────────────────────────────────────────────────────
        $outStream = fopen('php://output', 'wb');
        if (! $outStream) {
            // If php://output cannot be opened, we cannot stream anything
            http_response_code(500);
            echo "Failed to open output stream.";
            exit;
        }

        // ───────────────────────────────────────────────────────────────────────────
        // 5) Initialize cURL
        // ───────────────────────────────────────────────────────────────────────────
        $ch = curl_init($url);
        if (! $ch) {
            http_response_code(500);
            echo "Failed to initialize cURL.";
            fclose($outStream);
            exit;
        }

        // 5a) Tell cURL to write raw data directly to our $outStream
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_FILE, $outStream);

        // 5b) Follow redirects & set a reasonable timeout
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0); // no maximum total timeout
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 1024 * 1024); // 1 MiB chunks

        // 5c) Don’t include HTTP response headers in the data
        curl_setopt($ch, CURLOPT_HEADER, false);

        // 5d) Send a “Range” header so YouTube actually hands us the bytes
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: Mozilla/5.0',
            'Referer: https://www.youtube.com',
            'Range: bytes=0-',
        ]);

        // ───────────────────────────────────────────────────────────────────────────
        // 6) Execute cURL
        // ───────────────────────────────────────────────────────────────────────────
        $curlResult = curl_exec($ch);
        $curlErr    = curl_error($ch);
        $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // 6a) Clean up
        curl_close($ch);
        fclose($outStream);

        // ───────────────────────────────────────────────────────────────────────────
        // 7) Check for errors
        // ───────────────────────────────────────────────────────────────────────────
        if ($curlResult === false) {
            // cURL-level failure (network error, DNS, SSL, etc.)
            error_log('cURL error during download(): ' . $curlErr);
            echo "Download failed: cURL error.";
            exit;
        }

        if ($httpCode !== 200 && $httpCode !== 206) {
            // We expected YouTube to return 200 (OK) or 206 (Partial Content),
            // but instead we got something else (403, 404, 416, etc.).
            error_log(sprintf(
                'Download failed: got HTTP/%d on %s',
                $httpCode,
                $url
            ));
            http_response_code(502);
            // If you want to see the server’s HTML/json response, you could
            // fetch it via a second cURL call (with RETURNTRANSFER=true) and echo it.
            echo "Download failed: YouTube responded with HTTP {$httpCode}.";
            exit;
        }

        // ───────────────────────────────────────────────────────────────────────────
        // 8) If we reach here, cURL streamed the data straight to php://output,
        //    so the browser/client should now be downloading the MP4.
        // ───────────────────────────────────────────────────────────────────────────
        exit;
    }
}
