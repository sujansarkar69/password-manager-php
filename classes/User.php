<?php

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    private function encryptKey(string $key, string $plainPassword): string
    {
        $encryptionKey = hash('sha256', $plainPassword, true);
        $iv = random_bytes(16);

        $encrypted = openssl_encrypt(
            $key,
            'AES-256-CBC',
            $encryptionKey,
            OPENSSL_RAW_DATA,
            $iv
        );

        return base64_encode($iv . $encrypted);
    }

    private function decryptKey(string $encryptedKey, string $plainPassword): string
    {
        $data = base64_decode($encryptedKey);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);

        $encryptionKey = hash('sha256', $plainPassword, true);

        return openssl_decrypt(
            $encrypted,
            'AES-256-CBC',
            $encryptionKey,
            OPENSSL_RAW_DATA,
            $iv
        );
    }

    public function register(string $username, string $plainPassword): bool
    {
        $passwordHash = password_hash($plainPassword, PASSWORD_DEFAULT);

        // Permanent user encryption key
        $userKey = bin2hex(random_bytes(32));

        // Encrypt permanent key using login password
        $encryptedKey = $this->encryptKey($userKey, $plainPassword);

        $stmt = $this->db->prepare(
            "INSERT INTO users (username, password_hash, encrypted_key)
             VALUES (:username, :password_hash, :encrypted_key)"
        );

        return $stmt->execute([
            ':username' => $username,
            ':password_hash' => $passwordHash,
            ':encrypted_key' => $encryptedKey
        ]);
    }

    public function login(string $username, string $plainPassword): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        if (!password_verify($plainPassword, $user['password_hash'])) {
            return false;
        }

        $userKey = $this->decryptKey($user['encrypted_key'], $plainPassword);

        return [
            'id' => $user['id'],
            'username' => $user['username'],
            'user_key' => $userKey
        ];
    }

    public function changePassword(int $userId, string $oldPassword, string $newPassword): bool
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($oldPassword, $user['password_hash'])) {
            return false;
        }

        // Decrypt the existing permanent key using old password
        $userKey = $this->decryptKey($user['encrypted_key'], $oldPassword);

        // Re-encrypt the same permanent key using new password
        $newEncryptedKey = $this->encryptKey($userKey, $newPassword);

        // Hash the new login password
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $update = $this->db->prepare(
            "UPDATE users
             SET password_hash = :password_hash, encrypted_key = :encrypted_key
             WHERE id = :id"
        );

        return $update->execute([
            ':password_hash' => $newPasswordHash,
            ':encrypted_key' => $newEncryptedKey,
            ':id' => $userId
        ]);
    }
}