<?php

/**
 * Created by PhpStorm.
 * Audit: gkratz
 * Date: 23/05/2019
 * Time: 09:41.
 */

namespace App\Service;

/**
 * Trait FileServiceTrait.
 */
trait FileServiceTrait
{
    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @return FileService
     */
    public function getFileService(): FileService
    {
        return $this->fileService;
    }

    /**
     * @param FileService $fileService
     *
     * @required
     */
    public function setFileService(FileService $fileService): void
    {
        $this->fileService = $fileService;
    }
}
