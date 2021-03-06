<?php

namespace OscarApi\Tests\Model;

use Oscar\Category;
use OscarApi\Model\CategoryWinner;
use OscarApi\Model\CategoryWinnerEncrypt;
use Oscar\Oscar;
use PHPUnit\Framework\TestCase;

class CategoryWinnerEncryptTest extends TestCase
{
    /** @var Category[]|null */
    private ?array $oscarCategories;

    protected function setUp(): void
    {
        $this->oscarCategories = (new Oscar())->getCategories();
    }

    /**
     * @testdox Verify if hashes don't colide and generate unique winners and categories
     */
    public function testIfIsPossibleToHaveUniqueNumbers()
    {
        $totalOfPossibilities = $this->getTotalOfPossibilities();

        $hashes = [];
        foreach ($this->oscarCategories as $category) {
            $arrayOfHashes = $this->getArrayHashesByCategory($category);
            $hashes = array_merge($hashes, $arrayOfHashes);
        }

        $this->assertEquals($totalOfPossibilities, count($hashes));
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

    private function getTotalOfPossibilities(): int
    {
        $totalOfOptions = 0;
        foreach ($this->oscarCategories as $category) {
            $totalOfOptions += count($category->getNominees());
        }

        return $totalOfOptions;
    }
}
