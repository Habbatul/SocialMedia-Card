<?php
require 'vendor/autoload.php'; // Load Composer's autoloader

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

// Mengambil data dari GitHub API
$token = "tokenGithub";

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


function getHeadContent($url, $sessionCookie) {
    try {
        $client = new Client();
        
        // Menggunakan CookieJar untuk menyimpan session ID
        $cookieJar = CookieJar::fromArray(['sessionid' => $sessionCookie], 'instagram.com');

        $response = $client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'NamaAplikasi/1.0' // Ganti dengan nama aplikasi dan versi Anda
            ],
            'cookies' => $cookieJar // Menggunakan session ID dalam cookies
        ]);

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
            return array('followers' => 'Null', 'posts' => 'Null');
        }
    } catch (GuzzleHttp\Exception\ClientException $e) {
        // Tangani exception di sini
        echo "Error: " . $e->getMessage();
        return array('followers' => 'Null', 'posts' => 'Null');
    }
}

// Tentukan cookies session ID
$sessionCookie = 'Session_Instagram';
$username = 'hq.han'; // Ganti dengan nama pengguna Instagram yang sesuai
$url = 'https://www.instagram.com/' . $username . '/';
$metaContent = getHeadContent($url, $sessionCookie);


// Set URL dan data yang dibutuhkan
$url = 'https://www.linkedin.com/voyager/api/graphql?includeWebMetadata=true&variables=(vanityName:habbatul)&queryId=voyagerIdentityDashProfiles.a7bb25702ed96a9dc6301e12423eaa75';
$csrfToken = 'ikan';
$cookie = 'li_at="Session_Linkedin"; JSESSIONID="ikan"';

// Set header
$headers = array(
    'Host: www.linkedin.com',
    'Csrf-Token: ' . $csrfToken,
    'Cookie: ' . $cookie,
);

// Inisialisasi cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// Set URL dan option cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Mengabaikan verifikasi SSL

// Eksekusi cURL
$response = curl_exec($ch);
header("Content-type: json");
$connection = null;
echo $response;
// Tangani hasil
if ($response === false) {
    // Gagal melakukan permintaan
    $connection = null;
} else {
    // Sukses, tampilkan respons
    $pattern = '/,"total":(\d+)/';
    if (preg_match_all($pattern, $response, $matches)) {
        $connection = $matches[1][1];
    } else {
        $connection = null;
    }
}

// Tutup koneksi cURL
curl_close($ch);



// Simpan data ke file JSON
$data = array(
    "linkedin" => array(
        "connection" => $connection
    ),
    "instagram" => $metaContent,
    "github" => array(
        "followers" => $githubFollowers,
        "repos" => $githubRepos
    )
);

// Tulis data ke file JSON
file_put_contents('data.json', json_encode($data));

?>
