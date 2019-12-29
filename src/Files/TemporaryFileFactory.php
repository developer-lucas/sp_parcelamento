<?php
namespace Softpay\Parcelamento\Files;
use Illuminate\Support\Str;

class TemporaryFileFactory
{
    private $temporaryPath;
    private $temporaryDisk;
	
    public function __construct(string $temporaryPath = null, string $temporaryDisk = null) {
        $this->temporaryPath = $temporaryPath;
        $this->temporaryDisk = $temporaryDisk;
    }
    public function make(string $fileExtension = null): TemporaryFile
    {
        if (null !== $this->temporaryDisk) {
            return $this->makeRemote();
        }
        return $this->makeLocal(null, $fileExtension);
    }
	
    public function makeLocal(string $fileName = null, string $fileExtension = null): LocalTemporaryFile
    {
        if (!file_exists($this->temporaryPath) && !mkdir($concurrentDirectory = $this->temporaryPath) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
        return new LocalTemporaryFile(
            $this->temporaryPath . DIRECTORY_SEPARATOR . ($fileName ?: $this->generateFilename($fileExtension))
        );
    }
	
    private function makeRemote(): RemoteTemporaryFile
    {
        $filename = $this->generateFilename();
        return new RemoteTemporaryFile(
            $this->temporaryDisk,
            $filename,
            $this->makeLocal($filename)
        );
    }
	
    private function generateFilename(string $fileExtension = null): string
    {
        return 'softpay-logs-' . Str::random(32) . ($fileExtension ? '.' . $fileExtension : '');
    }
}