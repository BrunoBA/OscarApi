<?php


namespace OscarApi\Model;

use JetBrains\PhpStorm\Pure;
use OscarApi\Env;
use Stringable;

class CategoryWinnerEncrypt implements Stringable
{
    public function __construct(
        private CategoryWinner $categoryWinner
    ) {
    }

    public function encypt(): string
    {
        $winner = $this->categoryWinner->getWinner();
        $categoryId = $this->categoryWinner->getCategoryId();

        if (is_null($winner)) {
            return "";
        }

        $hash = $this->encryptByValue($categoryId);
        $winnerHash = $this->encryptByValue($winner);

        return $hash.$winnerHash;
    }

    #[Pure]
    public function encryptByValue(int $value): string
    {
        return substr(md5($value), 0, Env::NUMBER_HASH);
    }


    public function __toString(): string
    {
        return $this->encypt();
    }
}
