<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 24.07.16
 * Time: 13:34.
 */

namespace StasPiv\Review\Checker;

use StasPiv\Review\AbstractFileReview;
use StaticReview\File\FileInterface;
use StaticReview\Issue\Issue;

/**
 * Class VarDumpChecker.
 */
class DebugChecker extends AbstractFileReview implements CheckerInterface
{
    /**
     * @param FileInterface $file
     *
     * @return bool
     */
    protected function canReviewFile(FileInterface $file)
    {
        return parent::canReviewFile($file) && strstr(__FILE__, $file->getName()) === false;
    }

    /**
     * @param string $message
     *
     * @return int
     */
    protected function scanMessage(string &$message) : int
    {
        if (empty($message)) {
            return Issue::LEVEL_ALL;
        }

        $message = 'Debug string is found'.PHP_EOL.$message;

        return Issue::LEVEL_ERROR;
    }

    /**
     * @param FileInterface $subject
     *
     * @return string
     */
    protected function getCommandLine(FileInterface $subject) : string
    {
        return 'grep -Hn "dump(\|print_r(" '.$subject->getName();
    }
}
