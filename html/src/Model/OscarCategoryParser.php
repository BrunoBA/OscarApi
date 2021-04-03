<?php

namespace OscarApi\Model;

use Oscar\Oscar;

class OscarCategoryParser
{
    private ?Oscar $oscar = null;

    /** @var CategoryWinner[] */
    public $categories;

    /**
     * OscarCategoryParser constructor.
     * @param CategoryWinner[] $categories
     */
    public function __construct(array $categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return Oscar|null
     */
    public function getOscar(): ?Oscar
    {
        return $this->oscar;
    }


    public function attachChoices()
    {
        $this->oscar = new Oscar();

        foreach ($this->categories as $categoryWinner) {
            $this->setWinnerByCategoryWinner($categoryWinner);
        }
    }


    public function setWinnerByCategoryWinner(CategoryWinner $categoryWinner): void
    {
        foreach ($this->oscar->getCategories() as $category) {
            if (is_null($categoryWinner->getWinner())) {
                continue;
            }

            if ($category->getId() == $categoryWinner->getCategoryId()) {
                $category->setWinner($categoryWinner->getWinner());
            }
        }
    }

    public function parseToHash()
    {
        $hash = "";

        foreach ($this->categories as $category) {
            $category->id;
        }
    }

}
