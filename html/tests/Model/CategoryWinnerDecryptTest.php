<?php

namespace OscarApi\Tests\Model;

use Oscar\Category;
use Oscar\Oscar;
use OscarApi\Model\CategoryWinner;
use OscarApi\Model\CategoryWinnerDecrypt;
use OscarApi\Model\CategoryWinnerEncrypt;
use PHPUnit\Framework\TestCase;

class CategoryWinnerDecryptTest extends TestCase
{
    /** @var Category[]|null */
    private ?array $oscarCategories;

    protected function setUp(): void
    {
        $this->oscarCategories = (new Oscar())->getCategories();
    }

    public function testIfThereIsTheCorrectQuantityOfPossibilities()
    {
        $arrayHashes = $this->loadHashes();
        $hashes = implode('', $arrayHashes);

        $categoryWinnerDecrypt = new CategoryWinnerDecrypt($hashes);
        $categoryWinners = $categoryWinnerDecrypt->decrypt();

        foreach ($categoryWinners as $categoryWinner) {
            $this->assertIsNumeric($categoryWinner->getCategoryId(), "Check if the Category is valid");
            $this->assertIsNumeric($categoryWinner->getWinner(), "Check if the winner is an index");
        }

        $this->assertEquals(count($arrayHashes), count($categoryWinners));
    }

    private function loadHashes(): array
    {
        $hashes = [];
        foreach ($this->oscarCategories as $category) {
            $arrayOfHashes = $this->getArrayHashesByCategory($category);
            $hashes = array_merge($hashes, $arrayOfHashes);
        }

        return $hashes;
    }

    private function getArrayHashesByCategory(Category $category): array
    {
        $arrayOfHashes = [];
        foreach ($category->getNominees() as $index => $nominee) {
            $categoryWinner = new CategoryWinner(intval($index), $category->getId());
            $arrayOfHashes[] = (new CategoryWinnerEncrypt($categoryWinner))->encypt();
        }

        return $arrayOfHashes;
    }

}
