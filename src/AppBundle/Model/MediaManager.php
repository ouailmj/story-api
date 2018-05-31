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

namespace AppBundle\Model;

use AppBundle\Entity\Image;
use AppBundle\Entity\Media;
use AppBundle\Entity\User;
use AppBundle\Entity\Video;
use AppBundle\Exception\FileNotAuthorizedException;
use AppBundle\Filesystem\FileManager;
use AppBundle\Filesystem\UploadManager;
use Doctrine\ORM\EntityManagerInterface;
use Gaufrette\File;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

    /** @var RequestStack */
    protected $requestStack;

    /**
     * MediaManager constructor.
     *
     * @param UploadManager          $uploadManager
     * @param FileManager            $fileManager
     * @param TokenStorageInterface  $tokenStorage
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UploadManager $uploadManager, FileManager $fileManager, TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->uploadManager = $uploadManager;
        $this->fileManager = $fileManager;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->requestStack =  $requestStack;
    }

    /**
     * Creates a media from a Gaufrette file.
     *
     * @param File   $file
     * @param User   $by
     * @param bool   $andSave
     * @param string $type
     *
     * @return Media
     */
    public function createMediaFromFile(File $file, User $by, $andSave = true, $type = Media::class)
    {
        /** @var Media $media * */
        $media = new $type();
        $media->setSrc($file->getKey());
        $media->setDownloadLink($this->requestStack->getCurrentRequest()->getUriForPath('/uploads/'.$file->getKey()));
        $media->setUploadedAt(new \DateTime());

        $media->setCreatedBy($by);

        if ($andSave) {
            $this->saveMedia($media, true);
        }

        return $media;
    }

    /**
     * Creates a media from a file in the local filesystem.
     *
     * @param $filePath
     * @param User|null $by
     * @param bool      $andSave
     *
     * @return Media
     */
    public function createMediaFromLocalFile($filePath, User $by = null, $andSave = true)
    {
        if (file_exists($filePath)) {
            $file = $this->fileManager
                ->createFile(new SymfonyFile($filePath));

            return $this->createMediaFromFile($file, $by, $andSave);
        }
        throw new FileNotFoundException($filePath);
    }

    public function saveMedia(Media $media, $andFlush = true)
    {
        $this->entityManager->persist($media);
        if ($andFlush) {
            $this->entityManager->flush();
        }

        return $media;
    }

    /**
     * @param Media $media
     * @param bool $removeBdd
     * @return bool
     */
    public function deleteMedia(Media $media, $removeBdd=false)
    {
        if($removeBdd){
            $this->entityManager->remove($media);
            $this->entityManager->flush();
        }
        $filePath =__DIR__."/../../../web/uploads/".$media->getSrc();
        if (file_exists($filePath)) {
            return unlink($filePath);
    }

    }

    public function trashMedia(int $mediaId)
    {
        // Put $media to trash
    }

    public function unTrashMedia(int $mediaId)
    {
    }

    /**
     * @param UploadedFile $file
     * @param User|null    $by
     * @param bool         $andSave
     *
     * @throws FileNotAuthorizedException
     *
     * @return Media
     */
    public function uploadImage(UploadedFile $file, User $by = null, $andSave = true)
    {
        if ($this->isImage($file)) {
            $file = $this->uploadManager->upload($file);

            return $this->createMediaFromFile($file, $by, $andSave, Image::class);
        }
        throw new FileNotAuthorizedException();
    }

    /**
     * @param UploadedFile $file
     * @param User|null    $by
     * @param bool         $andSave
     *
     * @throws FileNotAuthorizedException
     *
     * @return Media
     */
    public function uploadVideo(UploadedFile $file, User $by = null, $andSave = true)
    {
        if ($this->isVideo($file)) {
            $file = $this->uploadManager->upload($file);

            return $this->createMediaFromFile($file, $by, $andSave, Video::class);
        }
        throw new FileNotAuthorizedException();
    }

    /**
     * @param UploadedFile $file
     * @return bool
     */
    private function isImage(UploadedFile $file)
    {
        $mimeType = $file->getMimeType();
        $type = explode('/',$mimeType)[0];
        if( 'image' === $type ) return true;
        return false;
    }

    /**
     * @param UploadedFile $file
     * @return bool
     */
    private function isVideo(UploadedFile $file)
    {
        $mimeType = $file->getMimeType();
        $type = explode('/',$mimeType)[0];
        if( 'video' === $type ) return true;
        return false;
    }
}
