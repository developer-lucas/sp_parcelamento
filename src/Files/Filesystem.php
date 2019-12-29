<?php
namespace Softpay\Parcelamento\Files;

use Illuminate\Contracts\Filesystem\Factory;

class Filesystem
{
    private $filesystem;
	
    public function __construct(Factory $filesystem)
    {
        $this->filesystem = $filesystem;
    }
	
    public function disk(string $disk = null, array $diskOptions = []): Disk
    {
        return new Disk(
            $this->filesystem->disk($disk),
            $disk,
            $diskOptions
        );
    }
	
}