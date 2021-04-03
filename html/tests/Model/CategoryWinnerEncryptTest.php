<?php

namespace OscarApi\Tests\Model;

#use OscarApi\Model\CategoryWinnerEncrypt;
use PHPUnit\Framework\TestCase;

class CategoryWinnerEncryptTest extends TestCase
{
    const MAX_SIZE = 210;

    public function testIfIsPossibleToHaveUniqueNumbers()
    {
        $values = [];
        for ($i = 1; $i <= self::MAX_SIZE; $i++) {
            $hash = md5($i);
            $hash = substr($hash, 0, 4);

            if (!in_array($hash, $values)) {
                echo PHP_EOL;
                echo $i." - ".$hash;
                echo PHP_EOL;
                $values[] = $hash;
            }
        }

        $this->assertEquals(self::MAX_SIZE, count($values));
    }
}
