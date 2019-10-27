<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class GitExtension.
 */
class GitExtension extends AbstractExtension
{
    /**
     * @var string
     */
    private $project_dir;

    /**
     * GitExtension constructor.
     *
     * @param string $project_dir
     */
    public function __construct(string $project_dir)
    {
        $this->project_dir = $project_dir;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('git_version', [$this, 'getGitVersion']),
        ];
    }

    /**
     * @return string
     */
    public function getGitVersion(): string
    {
        return substr(file_get_contents("$this->project_dir/.git/refs/heads/master"), 0, 7);
    }
}
