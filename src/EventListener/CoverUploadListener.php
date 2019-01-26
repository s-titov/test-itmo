<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Book;
use App\Service\FileUploader;

class CoverUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Book) {
            return;
        }

        if ($fileName = $entity->getCover()) {
            $entity->setCover(new File($this->uploader->getTargetDirectory().'/'.$fileName));
        }
    }

    public function preRemove (LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Book) {
            return;
        }

        if ($file = $entity->getCover()) {
            unlink($file);
        }
    }

    private function uploadFile($entity)
    {
        // upload only works for Book entities
        if (!$entity instanceof Book) {
            return;
        }

        $file = $entity->getCover();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setCover($fileName);
        } elseif ($file instanceof File) {
            // prevents the full file path being saved on updates
            // as the path is set on the postLoad listener
            $entity->setCover($file->getFilename());
        }
    }
}