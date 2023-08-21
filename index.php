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
</body>
</html>
