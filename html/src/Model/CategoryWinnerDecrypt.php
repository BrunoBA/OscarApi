<?php


namespace OscarApi\Model;


use Exception;
use Oscar\Oscar;
use OscarApi\Env;

class CategoryWinnerDecrypt
{
    private array $hashIndex = [];

    /**
     * CategoryWinnerDecrypt constructor.
     * @param string $hash
     */
    public function __construct(
        private string $hash
    ) {
        $this->loadHash();
    }

    /** @return CategoryWinner[] */
    public function decrypt(): array
    {
        $arrayHash = str_split($this->hash, Env::HASH_FULL_SIZE);

        $categoryWinner = [];
        foreach ($arrayHash as $hash) {
            $categoryHash = $this->getCategoryHash($hash);
            $winnerHash = $this->getWinnerHash($hash);

            $categoryId = $this->getValueByHash($categoryHash);
            $winner = $this->getValueByHash($winnerHash);

            if (is_null($categoryId) || is_null($winner)) {
                continue;
            }

            $categoryWinner[] = new CategoryWinner(intval($winner), intval($categoryId));
        }

        return $categoryWinner;
    }

    private function loadHash()
    {
        $size = count((new Oscar())->getCategories());
        for ($i = 0; $i <= $size; $i++) {
            $hash = (string)substr(md5($i), 0, Env::NUMBER_HASH);
            $this->hashIndex[$hash] = $i;
        }
    }

    private function getValueByHash(string $hash): ?int
    {
        try {
            return $this->hashIndex[$hash];
        } catch (Exception) {
            return null;
        }
    }

    public function getCategoryHash(mixed $hash): bool|string
    {
        return substr($hash, 0, Env::HASH_HALF_SIZE);
    }


    public function getWinnerHash(mixed $hash): bool|string
    {
        return substr($hash, -Env::HASH_HALF_SIZE);
    }
}
