<?php


namespace OscarApi\Model;

use JsonSerializable;

class CategoryWinner implements JsonSerializable
{
    /**
     * CategoryWinner constructor.
     * @param int|null $winner
     * @param int $id
     */
    public function __construct(
        public ?int $winner,
        public int $id
    ) {
    }

    /**
     * @return int|null
     */
    public function getWinner(): ?int
    {
        return $this->winner;
    }


    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->id;
    }

    public function jsonSerialize(): array
    {
        return [
            "winner" => $this->winner,
            "id" => $this->id,
        ];
    }
}
