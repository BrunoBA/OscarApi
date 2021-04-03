<?php


namespace OscarApi\Model;

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

        $hash = substr(md5($categoryId), 0, 4);
        $winnerHash = substr(md5($winner), 0, 4);

        return $hash.$winnerHash;
    }


    public function __toString(): string
    {
        return $this->encypt();
    }
}
