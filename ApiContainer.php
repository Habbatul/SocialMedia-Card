<?php
require 'vendor/autoload.php'; // Load Composer's autoloader

use GuzzleHttp\Client;

// Mengambil data dari GitHub API
$token = "asdasdasd";

// URL API GitHub
$url = "https://api.github.com/user";

// Header request
$headers = array(
    "Accept: application/vnd.github+json",
    "Authorization: Bearer $token",
    "User-Agent: My-App" // tambah user-agent header
);

// Inisialisasi cURL
$ch = curl_init();

// Set URL dan opsi cURL untuk GitHub
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Jalankan cURL dan simpan respons
$response = curl_exec($ch);

// Tutup cURL
curl_close($ch);

// Dekode respons JSON dari GitHub
$githubData = json_decode($response, true);

// Jika dekoding berhasil, simpan data pengikut dan repositori
if ($githubData !== null) {
    $githubFollowers = $githubData['followers'];
    $githubRepos = $githubData['public_repos'];
} else {
    $githubFollowers = "";
    $githubRepos = "";
}

// Mengambil data dari Instagram
function getHeadContent($url) {
    $client = new Client();
    $response = $client->request('GET', $url);

    $html = $response->getBody()->getContents();

    // Temukan posisi awal dan akhir dari tag head
    $start = strpos($html, '<head>');
    $end = strpos($html, '</head>', $start);

    // Ambil bagian head dari HTML
    $headContent = substr($html, $start, $end - $start + strlen('</head>'));

    // Temukan tag meta dengan property="og:description"
    $metaTagPattern = '/(\d+) Followers,.*?(\d+) Posts/i';
    preg_match($metaTagPattern, $headContent, $matches);

    // Jika ditemukan, ambil angka Followers dan Posts
    if (!empty($matches)) {
        $followers = $matches[1];
        $posts = $matches[2];
        return array('followers' => $followers, 'posts' => $posts);
    } else {
        return null;
    }
}


$username = 'hq.han'; // Ganti dengan nama pengguna Instagram yang sesuai
$url = 'https://www.instagram.com/' . $username . '/';
$metaContent = getHeadContent($url);

// Simpan data ke file JSON
$data = array(
    "instagram" => $metaContent,
    "github" => array(
        "followers" => $githubFollowers,
        "repos" => $githubRepos
    )
);

// Tulis data ke file JSON
file_put_contents('data.json', json_encode($data));

?>
