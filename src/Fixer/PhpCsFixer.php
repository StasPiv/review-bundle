<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 23.07.16
 * Time: 14:30.
 */

namespace StasPiv\Review\Fixer;

use StaticReview\File\FileInterface;
use StaticReview\Issue\Issue;
use StasPiv\Review\AbstractFileReview;

/**
 * Class PhpCsFixer.
 */
class PhpCsFixer extends AbstractFileReview implements FixerInterface
{
    /**
     * @param string $message
     *
     * @return int
     */
    protected function scanMessage(string &$message) : int
    {
        if ($message !== 'F') {
            return Issue::LEVEL_ALL;
        }

        $message = 'Styling fixed';

        return Issue::LEVEL_INFO;
    }

    /**
     * @param FileInterface $subject
     *
     * @return string
     */
    protected function getCommandLine(FileInterface $subject) : string
    {
        return 'vendor/bin/php-cs-fixer -vvv fix '.$subject->getName().' --level=symfony';
    }
}
