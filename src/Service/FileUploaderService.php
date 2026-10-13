<?php

namespace App\Service;

use Random\RandomException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderService
{
    public function __construct(private readonly string $uploadDirectory, private readonly SluggerInterface $slugger)
    {

    }

    /**
     * @throws RandomException
     */
    public function upload(UploadedFile $file): string
    {
        $safe = $this->slugger->slug($file->getClientOriginalName(), PATHINFO_FILENAME);
        $name = $safe . '-' . bin2hex(random_bytes(8)) . '.' . $file->guessExtension();
        $file->move($this->uploadDirectory, $name);
        return $name;
    }
}
