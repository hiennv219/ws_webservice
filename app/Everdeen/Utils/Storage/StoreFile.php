<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-18
 * Time: 19:56
 */

namespace Katniss\Everdeen\Utils\Storage;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

class StoreFile implements IStoreFile
{
    private $storePath;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $targetPath;

    /**
     * @var File
     */
    protected $sourceFileInfo;

    /**
     * @var File
     */
    protected $targetFileInfo;

    /**
     * StoreFile constructor.
     * @param \SplFileInfo|string $sourceFile
     * @param string $targetDirectory
     */
    public function __construct($sourceFile, $targetDirectory = '', $prefix = 'file')
    {
        $this->storePath = dirSeparator(storage_path('app/_store'));
        $this->prefix = $prefix;

        $this->sourceFileInfo = $this->checkSourceFile($sourceFile);

        $this->checkTargetDirectory($targetDirectory);
        $this->targetPath = concatDirectories($this->storePath, $targetDirectory);

        $this->targetFileInfo = $this->sourceFileInfo->move($this->targetPath, $this->generateTargetBaseName());
    }

    public function getTargetFileRealPath()
    {
        return $this->targetFileInfo->getRealPath();
    }

    public function getTargetFileRelativePath()
    {
        $realPath = $this->getTargetFileRealPath();
        if (Str::startsWith($realPath, public_path())) {
            return str_replace(public_path() . DIRECTORY_SEPARATOR, '', $realPath);
        }
        return str_replace($this->storePath . DIRECTORY_SEPARATOR, '', $realPath);
    }

    /**
     * @param string|\SplFileInfo $sourceFile
     * @return File
     * @throws KatnissException
     */
    private function checkSourceFile($sourceFile)
    {
        if (is_string($sourceFile)) {
            if (!file_exists($sourceFile)) {
                throw new KatnissException(trans('error.file_not_found'));
            }

            return new File($sourceFile);
        }

        if (!is_a($sourceFile, \SplFileInfo::class)) {
            throw new KatnissException(trans('error.file_not_found'));
        }
        return new File($sourceFile->getRealPath());
    }

    private function checkTargetDirectory($targetDirectory)
    {
        if (containBackDirectory($targetDirectory)
            || (!is_dir(concatDirectories($this->storePath, $targetDirectory))
                && !mkdir(concatDirectories($this->storePath, $targetDirectory), 0777, true))
        ) {
            throw new KatnissException(trans('error.directory_not_found'));
        }
    }

    protected function getPrefix()
    {
        return $this->prefix;
    }

    protected function generateTargetBaseName()
    {
        return randomizeFilename($this->getPrefix(), $this->sourceFileInfo->guessExtension());
    }

    protected function tmpFilename($name = null, $autoExtension = true)
    {
        if (!empty($name)) {
            if ($autoExtension) {
                $name .= '.' . $this->targetFileInfo->getExtension();
            }
        } else {
            $name = null;
        }
        return $name;
    }

    protected function fileGetTarget($directory, $name = null)
    {
        if (!is_dir($directory)) {
            if (false === @mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new FileException(sprintf('Unable to create the "%s" directory', $directory));
            }
        } elseif (!is_writable($directory)) {
            throw new FileException(sprintf('Unable to write in the "%s" directory', $directory));
        }

        $target = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . (null === $name ? $this->targetFileInfo->getBasename() : $this->fileGetName($name));

        return new File($target, false);
    }

    /**
     * Returns locale independent base name of the given path.
     *
     * @param string $name The new file name
     *
     * @return string containing
     */
    protected function fileGetName($name)
    {
        $originalName = str_replace('\\', '/', $name);
        $pos = strrpos($originalName, '/');
        $originalName = false === $pos ? $originalName : substr($originalName, $pos + 1);

        return $originalName;
    }

    public function move($targetDirectory, $name = null, $autoExtension = true)
    {
        $name = $this->tmpFilename($name, $autoExtension);

        $targetFileInfo = $this->targetFileInfo->move($targetDirectory, $name);
        @unlink($this->targetFileInfo->getRealPath());
        $this->targetFileInfo = $targetFileInfo;
    }

    public function copy($targetDirectory, $name = null, $autoExtension = true)
    {
        $name = $this->tmpFilename($name, $autoExtension);

        $target = $this->fileGetTarget($targetDirectory, $name);

        if (!@copy($this->targetFileInfo->getPathname(), $target)) {
            $error = error_get_last();
            throw new FileException(sprintf('Could not move the file "%s" to "%s" (%s)', $this->targetFileInfo->getPathname(), $target, strip_tags($error['message'])));
        }

        @chmod($target, 0666 & ~umask());

        return $target;
    }

    /**
     * @param string $targetDirectory
     * @param string|null $name
     * @param bool $autoExtension
     * @return IStoreFile
     */
    public function duplicate($targetDirectory, $name = null, $autoExtension = true)
    {
        $targetFileInfo = clone $this->targetFileInfo;
        $this->targetFileInfo = $this->copy($targetDirectory, $name);

        $cloneStore = clone $this;
        $this->targetFileInfo = $targetFileInfo;
        return $cloneStore;
    }
}