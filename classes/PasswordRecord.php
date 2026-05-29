<?php

class PasswordRecord
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    private function encryptPassword(string $password, string $userKey): string
    {
        $key = hash('sha256', $userKey, true);
        $iv = random_bytes(16);

        $encrypted = openssl_encrypt(
            $password,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return base64_encode($iv . $encrypted);
    }

    private function decryptPassword(string $encryptedPassword, string $userKey): string
    {
        $data = base64_decode($encryptedPassword);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);

        $key = hash('sha256', $userKey, true);

        return openssl_decrypt(
            $encrypted,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
    }

    public function save(int $userId, string $serviceName, string $plainPassword, string $userKey): bool
    {
        $encryptedPassword = $this->encryptPassword($plainPassword, $userKey);

        $stmt = $this->db->prepare(
            "INSERT INTO password_records (user_id, service_name, encrypted_password)
             VALUES (:user_id, :service_name, :encrypted_password)"
        );

        return $stmt->execute([
            ':user_id' => $userId,
            ':service_name' => $serviceName,
            ':encrypted_password' => $encryptedPassword
        ]);
    }

    public function getAllByUser(int $userId, string $userKey): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM password_records
             WHERE user_id = :user_id
             ORDER BY created_at DESC"
        );

        $stmt->execute([':user_id' => $userId]);

        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($records as &$record) {
            $record['plain_password'] = $this->decryptPassword(
                $record['encrypted_password'],
                $userKey
            );
        }

        return $records;
    }
}