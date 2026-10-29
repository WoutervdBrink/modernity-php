<?php

namespace App\Services\Git;

use App\Contracts\Git\RepositoryArchive;
use App\Data\Analysis\RepositoryFile;
use Generator;
use ZipArchive;

final readonly class LocalRepositoryArchive implements RepositoryArchive
{
    private ZipArchive $zip;

    public function __construct(private string $path)
    {
        $this->zip = new ZipArchive;

        $this->zip->open($this->path);
    }

    public function phpFiles(): Generator
    {
        for ($i = 0; $i < $this->zip->numFiles; $i++) {
            $file = $this->zip->statIndex($i);

            if ($file === false) {
                continue;
            }

            $pathInfo = pathinfo($file['name']);
            if (! empty($pathInfo['extension']) && $pathInfo['extension'] === 'php') {
                yield RepositoryFile::from([
                    'path' => $file['name'],
                    'contents' => $this->zip->getFromIndex($i),
                ]);
            }
        }
    }

    public function destroy(): void
    {
        unlink($this->path);
    }
}
