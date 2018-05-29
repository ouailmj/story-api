<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Developed by MIT <contact@mit-agency.com>
 *
 */

namespace AppBundle\Filesystem;

use AppBundle\Utils\HashUtils;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
    /** @var FilesystemMap */
    protected $filesystemMap;

    const FS_DEFAULT_KEY = 'default';

    public function __construct(FilesystemMap $filesystemMap)
    {
        $this->filesystemMap = $filesystemMap;
    }

    public function getFileSystem($fsKey = self::FS_DEFAULT_KEY)
    {
        if ($this->filesystemMap->has($fsKey)) {
            return $this->filesystemMap->get($fsKey);
        }
        throw new \RuntimeException(sprintf('There is no filesystem configured with key %s', $fsKey));
    }

    public function getDefaultFileSystem()
    {
        return $this->getFileSystem(self::FS_DEFAULT_KEY);
    }

    /**
     * @param $file
     * @param string $dir
     * @param string $fileSystem
     *
     * @return \Gaufrette\File|mixed
     */
    private function write($file, $dir = '', $fileSystem = 'default')
    {
        $filePath = $this->buildFileKey($file, $dir);
        $this
            ->getFileSystem($fileSystem)
            ->write($filePath, file_get_contents($file), true);

        return
            $this
                ->getDefaultFileSystem()
                ->get($filePath);
    }

    /**
     * @param $file
     * @param string $dir
     * @param string $fileSystem
     * @return bool
     */
    private function delete($file, $dir = '', $fileSystem = 'default')
    {
        $filePath = $this->buildFileKey($file, $dir);
        return $this
            ->getFileSystem($fileSystem)
            ->delete($filePath);

    }

    /**
     * @param $file
     * @param string $dir
     * @param string $fileSystem
     *
     * @return \Gaufrette\File|mixed
     */
    public function createFile($file, $dir = '', $fileSystem = 'default')
    {
        return $this->write($file, $dir, $fileSystem);
    }

    /**
     * @param $file
     * @param string $dir
     * @param string $fileSystem
     * @return bool
     */
    public function deleteFile($file, $dir = '', $fileSystem = 'default')
    {
        return $this->delete($file, $dir, $fileSystem);
    }

    /**
     * @param File | UploadedFile $file
     * @param $dir
     *
     * @return string
     */
    private function buildFileKey($file, $dir)
    {
        $dir = rtrim(trim($dir), '/ ');
        $filename = $file->getFilename();
        $extension = $file->getExtension();
        if ($file instanceof UploadedFile) {
            $filename = $file->getClientOriginalName();
            $extension = $file->guessClientExtension();
        }
        $fileKey = HashUtils::hash($filename.microtime()).'.'.$extension;
        $fileKey = (!empty($dir)) ? $dir.'/'.$fileKey : $fileKey;

        return $fileKey;
    }
}
