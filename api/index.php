<?php
// Fungsi untuk menghasilkan pasangan kunci RSA (public dan private key)
function generateRSAKeys()
{
    // Menyimpan kunci publik dan kunci privat
    $config = [
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ];

    // Generate pasangan kunci RSA
    $resource = openssl_pkey_new($config);

    // Periksa apakah kunci berhasil dibuat
    if ($resource === false) {
        return ['error' => 'Gagal menghasilkan pasangan kunci RSA.'];
    }

    // Ekspor kunci privat
    $privateKey = '';
    if (!openssl_pkey_export($resource, $privateKey)) {
        return ['error' => 'Gagal mengekspor kunci privat.'];
    }

    // Ambil kunci publik
    $publicKeyDetails = openssl_pkey_get_details($resource);
    if ($publicKeyDetails === false) {
        return ['error' => 'Gagal mendapatkan detail kunci publik.'];
    }

    $publicKey = $publicKeyDetails['key'];

    return [
        'private_key' => $privateKey,
        'public_key' => $publicKey
    ];
}

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
        if ($_POST['action'] == 'generate_keys') {
            $keys = generateRSAKeys();
            if (isset($keys['error'])) {
                $error = $keys['error'];
            } else {
                $privateKey = $keys['private_key'];
                $publicKey = $keys['public_key'];
            }
        } elseif ($_POST['action'] == 'encrypt') {
            $encryptedMessage = encryptRSA($_POST['public_key'], $_POST['message']);
        } elseif ($_POST['action'] == 'decrypt') {
            $decryptedMessage = decryptRSA($_POST['private_key'], $_POST['encrypted_message']);
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
        <h1 class="text-3xl font-bold mb-6 text-center">RSA Encryption & Decryption</h1>

        <!-- Error Message -->
        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <strong>Error:</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Generate Keys Form -->
        <div class="mb-6">
            <form action="" method="POST">
                <input type="hidden" name="action" value="generate_keys">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Generate RSA Keys</button>
            </form>
        </div>

        <?php if (isset($publicKey) && isset($privateKey)): ?>
            <div class="mb-6">
                <p><strong>Public Key:</strong></p>
                <textarea rows="4" class="w-full p-2 bg-gray-100 rounded-md" readonly><?php echo $publicKey; ?></textarea>
            </div>
            <div class="mb-6">
                <p><strong>Private Key:</strong></p>
                <textarea rows="4" class="w-full p-2 bg-gray-100 rounded-md" readonly><?php echo $privateKey; ?></textarea>
            </div>
        <?php endif; ?>

        <!-- Encrypt Form -->
        <div class="mb-6">
            <h2 class="text-2xl mb-4">Encrypt Message</h2>
            <form action="" method="POST">
                <input type="hidden" name="action" value="encrypt">
                <textarea name="message" class="w-full p-2 bg-gray-100 rounded-md" placeholder="Enter message to encrypt" required></textarea>
                <input type="hidden" name="public_key" value="<?php echo $publicKey ?? ''; ?>">
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md mt-4">Encrypt</button>
            </form>
        </div>

        <?php if (isset($encryptedMessage)): ?>
            <div class="mb-6">
                <p><strong>Encrypted Message:</strong></p>
                <textarea rows="4" class="w-full p-2 bg-gray-100 rounded-md" readonly><?php echo $encryptedMessage; ?></textarea>
            </div>
        <?php endif; ?>

        <!-- Decrypt Form -->
        <div class="mb-6">
            <h2 class="text-2xl mb-4">Decrypt Message</h2>
            <form action="" method="POST">
                <input type="hidden" name="action" value="decrypt">
                <textarea name="encrypted_message" class="w-full p-2 bg-gray-100 rounded-md" placeholder="Enter encrypted message" required></textarea>
                <input type="hidden" name="private_key" value="<?php echo $privateKey ?? ''; ?>">
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md mt-4">Decrypt</button>
            </form>
        </div>

        <?php if (isset($decryptedMessage)): ?>
            <div class="mb-6">
                <p><strong>Decrypted Message:</strong></p>
                <textarea rows="4" class="w-full p-2 bg-gray-100 rounded-md" readonly><?php echo $decryptedMessage; ?></textarea>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>