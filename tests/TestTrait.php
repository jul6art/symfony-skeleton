<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 29/07/2019
 * Time: 09:21.
 */

namespace App\Tests;

/**
 * Trait TestTrait.
 */
trait TestTrait
{
    /**
     * @param $filename
     * @param string $content
     */
    public function save($filename, string $content): void
    {
        $testClassExploded = explode('\\', get_class($this));

        $dir = sprintf(
            '%s%s%s%s%s%s%s',
            __DIR__,
            DIRECTORY_SEPARATOR,
            'result',
            DIRECTORY_SEPARATOR,
            end($testClassExploded),
            DIRECTORY_SEPARATOR,
            $this->getName()
        );

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents(sprintf(
            '%s%s%s',
            $dir,
            DIRECTORY_SEPARATOR,
            $filename
        ), $content);
    }
}
