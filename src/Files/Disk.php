<?php
namespace Softpay\Parcelamento\Files;
use Illuminate\Contracts\Filesystem\Filesystem as IlluminateFilesystem;

class Disk
{
	
    protected $disk;
    protected $name;
    protected $diskOptions;
	
    public function __construct(IlluminateFilesystem $disk, string $name = null, array $diskOptions = []) {
        $this->disk        = $disk;
        $this->name        = $name;
        $this->diskOptions = $diskOptions;
    }
	
    public function __call($name, $arguments) {
        return $this->disk->{$name}(...$arguments);
    }
	
    public function put(string $destination, $contents): bool {
        return $this->disk->put($destination, $contents, $this->diskOptions);
    }
	
    public function copy(TemporaryFile $source, string $destination): bool {
        $readStream = $source->readStream();
        if (realpath($destination)) {
            $tempStream = fopen($destination, 'rb+');
            $success    = stream_copy_to_stream($readStream, $tempStream) !== false;
            if (is_resource($tempStream)) {
                fclose($tempStream);
            }
        } else {
            $success = $this->put($destination, $readStream);
        }
		
        if (is_resource($readStream)) {
            fclose($readStream);
        }
		
        return $success;
    }
	
    public function touch(string $filename) {
		
        $this->disk->put($filename, '', $this->diskOptions);
		
    }
}