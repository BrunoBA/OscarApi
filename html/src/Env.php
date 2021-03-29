<?php


namespace OscarApi;

use Stringable;
use Throwable;

class Env implements Stringable
{
    private string $default;

    /**
     * Env constructor.
     * @param string $tag
     * @param string $default
     */
    public function __construct(
        private string $tag,
        string $default = ""
    ) {
        $this->default = $default;
    }

    private function getEnv(): string
    {
        try {
            $envValue = $_ENV[$this->tag];
            if ($envValue === false) {
                return $this->default;
            };

            return $envValue;
        } catch (Throwable) {
            return $this->default;
        }
    }

    public function __toString(): string
    {
        return $this->getEnv();
    }
}
