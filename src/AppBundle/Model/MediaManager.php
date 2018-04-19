<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 19/04/2018
 * Time: 12:44
 */

namespace AppBundle\Model;


use AppBundle\Entity\Media;
use AppBundle\Filesystem\FileManager;
use AppBundle\Filesystem\UploadManager;
use Doctrine\ORM\EntityManagerInterface;
use Gaufrette\File;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

class MediaManager
{
    /**
     * @var UploadManager
     */
    protected $uploadManager;

    /**
     * @var FileManager
     */
    protected $fileManager;

    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /** @var EntityManagerInterface */
    protected $entityManager;

    /**
     * MediaManager constructor.
     *
     * @param UploadManager $uploadManager
     * @param FileManager $fileManager
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UploadManager $uploadManager, FileManager $fileManager, TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->uploadManager = $uploadManager;
        $this->fileManager = $fileManager;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    /**
     * Creates a media from a Gaufrette file.
     *
     * @param File $file
     * @param bool $andSave
     * @return Media
     */
    public function createMediaFromFile(File $file, $andSave = true)
    {
        $media = new Media();
        $media->setSrc($file->getKey());
        $media->setUploadedAt(new \DateTime());

        if ($andSave){
            $this->saveMedia($media ,true);
        }

        return $media;
    }

    /**
     * Creates a media from a file in the local filesystem.
     *
     * @param $filePath
     * @throws FileNotFoundException
     * @return Media
     */
    public function createMediaFromLocalFile($filePath)
    {
        if (file_exists($filePath)){
            $file = $this->fileManager
                ->createFile(new SymfonyFile($filePath));

            return $this->createMediaFromFile($file);
        }
        throw new FileNotFoundException($filePath);
    }

    public function saveMedia(Media $media, $andFlush = true)
    {
        $this->entityManager->persist($media);
        if ($andFlush) $this->entityManager->flush();
        return $media;
    }
}
