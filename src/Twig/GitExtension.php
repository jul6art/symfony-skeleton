<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Twig;

use DateTime;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class GitExtension.
 */
class GitExtension extends AbstractExtension
{
    const SESSION_KEY = 'git_version';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var string
     */
    private $project_dir;

    /**
     * @var string
     */
    private $skeleton_version;

    /**
     * GitExtension constructor.
     *
     * @param SessionInterface $session
     * @param string           $project_dir
     * @param string           $skeleton_version
     */
    public function __construct(SessionInterface $session, string $project_dir, string $skeleton_version)
    {
        $this->session = $session;
        $this->project_dir = $project_dir;
        $this->skeleton_version = $skeleton_version;
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
     *
     * @throws \Exception
     */
    public function getGitVersion(): string
    {
        $fileSystem = new Filesystem();

        if (!$fileSystem->exists("$this->project_dir/.git") or null === $this->session) {
            return 'NaN';
        }

        if (!$this->session->has(self::SESSION_KEY)) {
            $hash = trim(exec('git log --pretty="%h" -n1 HEAD'));

            $date = new DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
            $date->setTimezone(new \DateTimeZone('UTC'));

            $gitVersion = sprintf('v%s-dev.%s (%s)', $this->skeleton_version, $hash, $date->format('Y-m-d'));

            $this->session->set(self::SESSION_KEY, $gitVersion);
            $this->session->save();

            return $gitVersion;
        }

        return $this->session->get(self::SESSION_KEY);
    }
}
