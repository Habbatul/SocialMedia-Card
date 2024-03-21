<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Images Example</title>
  <style>
    img {
      width: 40%; /* Ukuran default untuk layar besar */
    }

    @media (max-width: 768px) { /* Perubahan ukuran pada layar <= 768px (ukuran tablet) */
      img {
        width: 100%; /* Ukuran untuk tablet dan lebih kecil */
      }
    }
  </style>
</head>
<body>
  <img src="github-card.php" alt="han's github">
  <img src="instagram-card.php" alt="han's instagram">
  <img src="linkedin-card.php" alt="han's linkedin">

  <?php

// Set token Anda di sini
$token = "rahasia";

// URL API GitHub
$url = "https://api.github.com/user";

// Header permintaan
$headers = array(
    "Accept: application/vnd.github+json",
    "Authorization: Bearer $token",
    "User-Agent: My-App" // Add a user-agent header
);

// Inisialisasi curl
$ch = curl_init();

// Set URL dan opsi curl
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Jalankan curl dan simpan responsnya
$response = curl_exec($ch);

// Periksa jika ada kesalahan
if ($response === false) {
    // Jika ada kesalahan, tampilkan pesan kesalahan curl
    echo "Error: " . curl_error($ch);
} else {
    // Jika tidak ada kesalahan, tampilkan respons API GitHub
    echo "Response from GitHub API: <br>";
    echo $response;
}

// Tutup curl
curl_close($ch);

?>

</body>
</html>
