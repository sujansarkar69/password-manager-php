<?php

class PasswordGenerator
{
    private string $lowercase = "abcdefghijklmnopqrstuvwxyz";
    private string $uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    private string $numbers = "0123456789";
    private string $specials = "!@#$%^&*()_+-=[]{};:,.<>?";

    private function getRandomCharacters(string $source, int $count): string
    {
        $result = "";

        for ($i = 0; $i < $count; $i++) {
            $index = random_int(0, strlen($source) - 1);
            $result .= $source[$index];
        }

        return $result;
    }

    public function generate(
        int $lowercaseCount,
        int $uppercaseCount,
        int $numberCount,
        int $specialCount
    ): string {
        $password = "";

        $password .= $this->getRandomCharacters($this->lowercase, $lowercaseCount);
        $password .= $this->getRandomCharacters($this->uppercase, $uppercaseCount);
        $password .= $this->getRandomCharacters($this->numbers, $numberCount);
        $password .= $this->getRandomCharacters($this->specials, $specialCount);

        $characters = str_split($password);
        shuffle($characters);

        return implode("", $characters);
    }
}