<?php

declare(strict_types=1);

namespace PharIo\Mediator\Service;

use DirectoryIterator;
use RecursiveDirectoryIterator;
use SplFileInfo;
use function rmdir;

class DeleteFiles
{
	private $filesOrFolders = [];

	public function __construct(SplFileInfo ...$fileOrFolder)
	{
		$this->filesOrFolders = $fileOrFolder;
	}

	public function __invoke(): void
	{
		foreach ($this->filesOrFolders as $item) {
			$this->remove($item);
		}
	}

	private function remove(SplFileInfo $fileOrFolder): void
	{
		if ($fileOrFolder->isDir()) {
			$iterator = new RecursiveDirectoryIterator($fileOrFolder);
			foreach ($iterator as $item) {
				$this->remove($item);
			}
			rmdir($fileOrFolder->getRealPath());
		}

		unlink($fileOrFolder->getRealPath());
	}
}
