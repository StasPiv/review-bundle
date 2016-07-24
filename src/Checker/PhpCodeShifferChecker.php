<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 23.07.16
 * Time: 13:01.
 */

namespace StasPiv\Review\Checker;

use StasPiv\Review\AbstractFileReview;
use StaticReview\File\FileInterface;
use StaticReview\Issue\Issue;

/**
 * Class PhpCodeShifferChecker.
 */
class PhpCodeShifferChecker extends AbstractFileReview implements CheckerInterface
{
    /**
     * @param string $message
     *
     * @return int|void
     */
    protected function scanMessage(string &$message) : int
    {
        return Issue::LEVEL_WARNING;
    }

    /**
     * @param FileInterface $subject
     *
     * @return string
     */
    protected function getCommandLine(FileInterface $subject) : string
    {
        return 'vendor/bin/phpcs --standard=vendor/leaphub/phpcs-symfony2-standard/leaphub/phpcs/Symfony2/'.' '.$subject->getName();
    }
}
