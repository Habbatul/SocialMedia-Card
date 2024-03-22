<?php
require 'vendor/autoload.php'; 

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

$token = "tokenGithub";

$url = "https://api.github.com/user";

$headers = array(
    "Accept: application/vnd.github+json",
    "Authorization: Bearer $token",
    "User-Agent: My-App" // tambah user-agent header
);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

curl_close($ch);

$githubData = json_decode($response, true);

if ($githubData !== null) {
    $githubFollowers = $githubData['followers'];
    $githubRepos = $githubData['public_repos'];
} else {
    $githubFollowers = "";
    $githubRepos = "";
}


function getHeadContent($url, $sessionCookie) {
    try {
        $client = new Client();
        
        $cookieJar = CookieJar::fromArray(['sessionid' => $sessionCookie], 'instagram.com');

        $response = $client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'NamaAplikasi/1.0'
            ],
            'cookies' => $cookieJar
        ]);

        $html = $response->getBody()->getContents();

        $start = strpos($html, '<head>');
        $end = strpos($html, '</head>', $start);

        $headContent = substr($html, $start, $end - $start + strlen('</head>'));

        $metaTagPattern = '/(\d+) Followers,.*?(\d+) Posts/i';
        preg_match($metaTagPattern, $headContent, $matches);
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

$sessionCookie = 'Session_Instagram';
$username = 'hq.han'; //Ganti dengan nama pengguna Instagram yang sesuai
$url = 'https://www.instagram.com/' . $username . '/';
$metaContent = getHeadContent($url, $sessionCookie);


$url = 'https://www.linkedin.com/voyager/api/graphql?includeWebMetadata=true&variables=(vanityName:habbatul)&queryId=voyagerIdentityDashProfiles.a7bb25702ed96a9dc6301e12423eaa75';
$csrfToken = 'ikan';
$cookie = 'li_at="Session_Linkedin"; JSESSIONID="ikan"';

$headers = array(
    'Host: www.linkedin.com',
    'Csrf-Token: ' . $csrfToken,
    'Cookie: ' . $cookie,
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //Mengabaikan verifikasi SSL

$response = curl_exec($ch);
header("Content-type: json");
$connection = null;

if ($response === false) {
    $connection = null;
} else {
    $pattern = '/,"total":(\d+)/';
    if (preg_match_all($pattern, $response, $matches)) {
        $connection = $matches[1][1];
    } else {
        $connection = null;
    }
}

curl_close($ch);

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
