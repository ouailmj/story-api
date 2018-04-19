<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 19/04/2018
 * Time: 13:29
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
        throw new \RuntimeException(sprintf("There is no filesystem configured with key %", $fsKey));
    }

    public function getDefaultFileSystem()
    {
        return $this->getFileSystem( self::FS_DEFAULT_KEY);
    }

    /**
     * @param $file
     * @param string $dir
     * @param string $fileSystem
     * @return \Gaufrette\File|mixed
     */
    private function buildFile($file, $dir = '', $fileSystem = 'default')
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
     * @return \Gaufrette\File|mixed
     */
    public function createFile($file, $dir = '', $fileSystem = 'default')
    {
        return $this->buildFile($file, $dir, $fileSystem);
    }

    /**
     * @param File | UploadedFile $file
     * @param $dir
     * @return string
     */
    private function buildFileKey($file, $dir)
    {
        $dir = rtrim(trim($dir), "/ ");
        $filename = $file->getFilename();
        $extension = $file->getExtension();
        if ($file instanceof UploadedFile) {
            $filename = $file->getClientOriginalName();
            $extension = $file->guessClientExtension();
        }
        $fileKey = HashUtils::hash($filename . microtime()).'.'.$extension;
        $fileKey = (!empty($dir)) ? $dir . '/' . $fileKey : $fileKey;

        return $fileKey;
    }
}
