<?php

namespace App;

final class Git
{
    public static function getCurrentGitCommitHash(): ?string
    {
        $dotGit = base_path('.git/');

        if (! is_dir($dotGit)) {
            return null;
        }

        $head = file_get_contents($dotGit.'HEAD');
        if ($head === false) {
            return null;
        }
        $head = trim(substr($head, 4));

        $hash = file_get_contents($dotGit.$head);
        if ($hash === false) {
            return null;
        }

        return trim($hash);
    }
}
