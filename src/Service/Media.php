<?php
namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Media
{
public function __construct( private $media_lieu_public, private  $media_lieu, private Filesystem $fs)
{
    
}



    public function media(UploadedFile $image, string $oldMedia = null ): string
    {
        $ext = $image->guessExtension();

        $folder = $this->media_lieu_public;
        $filename = bin2hex(random_bytes(20)) . '.' . $ext ;
        $image->move($folder, $filename);

        if ($oldMedia) {
            $this->fs->remove($folder . '/ ' . pathinfo($oldMedia,PATHINFO_BASENAME));
        }

        return $this->media_lieu . '/' . $folder; 
    }
    
}



?>