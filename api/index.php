<?php

// Fungsi untuk mengenkripsi pesan menggunakan kunci publik
function encryptRSA($publicKey, $message)
{
    openssl_public_encrypt($message, $encryptedMessage, $publicKey);
    return base64_encode($encryptedMessage);
}

// Fungsi untuk mendekripsi pesan menggunakan kunci privat
function decryptRSA($privateKey, $encryptedMessage)
{
    $encryptedMessage = base64_decode($encryptedMessage);
    openssl_private_decrypt($encryptedMessage, $decryptedMessage, $privateKey);
    return $decryptedMessage;
}

// Cek jika form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'encrypt') {
            $publicKeyInput = $_POST['public_key'];
            $message = $_POST['message'];
            $encryptedMessage = encryptRSA($publicKeyInput, $message);
        } elseif ($_POST['action'] == 'decrypt') {
            $privateKeyInput = $_POST['private_key'];
            $encryptedMessage = $_POST['encrypted_message'];
            $decryptedMessage = decryptRSA($privateKeyInput, $encryptedMessage);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSA Encryption and Decryption</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="container mx-auto my-10 p-5 bg-white rounded shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-center bg-blue-500 text-white p-3 rounded">
            Enkripsi dan Dekripsi Dengan Algoritma RSA
        </h1>

        <div class="mb-4">
            <a href="https://www.lddgo.net/en/encrypt/rsakey" target="_blank" class="px-4 py-2 bg-blue-500 text-white rounded-md">Buat Kunci RSA</a>
            <a href="http://www.steyrerbrains.at/math/rsa.html" target="_blank" class="px-4 py-2 bg-yellow-500 text-white rounded-md">Cara Membuat Kunci</a>
        </div>

        <!-- Error Message -->
        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <strong>Error:</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Encrypt Form -->
        <div class="mb-6">
            <h2 class="text-2xl mb-4">Enkripsi Pesan</h2>
            <form action="" method="POST">
                <input type="hidden" name="action" value="encrypt">
                <textarea name="message" class="w-full p-2 bg-gray-100 rounded-md" placeholder="Masukkan pesan yang ingin dienkripsi" required></textarea>
                <textarea name="public_key" class="w-full p-2 bg-gray-100 rounded-md mt-4" placeholder="Masukkan kunci publik" required></textarea>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md mt-4">Enkripsi</button>
            </form>
        </div>

        <?php if (isset($encryptedMessage)): ?>
            <div class="mb-6">
                <p><strong>Hasil Enkripsi:</strong></p>
                <textarea rows="4" class="w-full p-2 bg-gray-100 rounded-md" readonly><?php echo $encryptedMessage; ?></textarea>
            </div>
        <?php endif; ?>

        <!-- Decrypt Form -->
        <div class="mb-6">
            <h2 class="text-2xl mb-4">Dekripsi Pesan</h2>
            <form action="" method="POST">
                <input type="hidden" name="action" value="decrypt">
                <textarea name="encrypted_message" class="w-full p-2 bg-gray-100 rounded-md" placeholder="Masukkan pesan terenkripsi" required></textarea>
                <textarea name="private_key" class="w-full p-2 bg-gray-100 rounded-md mt-4" placeholder="Masukkan kunci privat" required></textarea>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md mt-4">Dekripsi</button>
            </form>
        </div>

        <?php if (isset($decryptedMessage)): ?>
            <div class="mb-6">
                <p><strong>Hasil Dekripsi:</strong></p>
                <textarea rows="4" class="w-full p-2 bg-gray-100 rounded-md" readonly><?php echo $decryptedMessage; ?></textarea>
            </div>
        <?php endif; ?>
        <br>
        <p class="text-center">©presented_by_Rangga-Aulia</p>
        <p class="text-center">thanks to: lddgo.net & steyrerbrains.at</p>
    </div>

</body>

</html>