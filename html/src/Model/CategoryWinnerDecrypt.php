<?php


namespace OscarApi\Model;


use Exception;
use Oscar\Oscar;

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
        $arrayHash = str_split($this->hash, 8);

        $categoryWinner = [];
        foreach ($arrayHash as $hash) {
            $categoryHash = substr($hash, 0, 4);
            $winnerHash = substr($hash, -4);

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
            $hash = (string) substr(md5($i), 0, 4);
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
}
