<?php

namespace OscarApi\Tests\Model;

use OscarApi\Model\CategoryWinnerEncrypt;
use PHPUnit\Framework\TestCase;

class CategoryWinnerEncryptTest extends TestCase
{
    public function testIfIsPossibleToHaveUniqueNumbers()
    {
        $values = [];
        for ($i = 1; $i <= 100; $i++) {
            $hash = md5($i);
            $hash = substr($hash, 0, 4);

            if (!in_array($hash, $values)) {
                echo PHP_EOL;
                echo $i." - ".$hash;
                echo PHP_EOL;
                $values[] = $hash;
            }
        }

        $this->assertEquals(100, count($values));
    }
}
