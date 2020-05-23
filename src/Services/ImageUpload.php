<?php
namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Intervention\Image\ImageManagerStatic as IMG;

use App\Services\Token;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ImageUpload
{
    private $directorio = false;
    private $nombre = false;

    public function __construct($targetDirectory)
    {
        $this->directorio = $this->mkdir( $targetDirectory );
    }

    # Desencadenador
    public function upload(UploadedFile $file)
    {
        $this->setOriginal($file);
        $this->setSize(800, 400);
        $this->setSize(300, 300);
        $this->setSize(100, 100);
        return $this->getNombre();
    }

    # Mover archivo
    public function setOriginal($file){
        try {
            $file->move( $this->mkdir( $this->getDirectorio()."/original" ) , $this->getNombre($file) );
        } catch (FileException $e) {
            echo "--Error al mover el archivo";
        }
    }

    # Verifica Nombre
    public function getNombre($file=false){
        if(!$this->nombre && $file){
            $this->nombre = Token::generar(5,'L')."-".( (new \DateTime())->format('YmdHis') ).'.'.$file->guessExtension(); //uniqid()
        }
        return $this->nombre;
    }

    # Borrar
    public function borrar($name){
        $finder = new Finder();
        $finder->directories()->in( $this->getDirectorio() );
        if ($finder->hasResults()) {
            $fs = new Filesystem;
            foreach ($finder as $file) {
                $d = $this->getDirectorio()."/".$file->getRelativePathname()."/".$name;
                // var_dump($d); exit;
                $fs->remove( $d );
                // $absoluteFilePath = $file->getRealPath();
                // $fileNameWithExtension = $file->getRelativePathname();
            }
        }
    }

    # Copiar Miniatura
    public function setSize($w, $h){
        $IMG = IMG::make( $this->getDirectorio()."/original/".$this->getNombre() )
            // ->resize($w, $h, function ($constraint) {
            //     $constraint->aspectRatio();
            //     $constraint->upsize();
            // });
            ->fit($w, $h);
        return $IMG->save( $this->mkdir( $this->getDirectorio() . '/' .$w. 'X' .$h ).'/'.$this->getNombre() );
    }

    # Crea directorio
    public function mkdir( $path ){
        if(!file_exists($path)){
            $fs = new Filesystem;
            try {
                $fs->mkdir($path);
            }catch(IOException $e){
                echo "--Error al crear el directorio: ".$e->getPath();
            }
        }
        return $path;
    }

    # Directorio sistema
    public function getDirectorio()
    {
        return $this->directorio;
    }
}