<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 24.07.16
 * Time: 0:13.
 */

namespace StasPiv\Review;

use StaticReview\Commit\CommitMessageInterface;
use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractFileReview as BaseFileReview;
use StaticReview\Review\ReviewableInterface;

/**
 * Class AbstractFileReview.
 */
abstract class AbstractFileReview extends BaseFileReview
{
    /**
     * @param ReporterInterface   $reporter
     * @param ReviewableInterface $subject
     * @param string              $message
     */
    abstract protected function scanMessage(ReporterInterface $reporter, ReviewableInterface $subject, string $message);

    /**
     * @param ReviewableInterface $subject
     *
     * @return string
     */
    abstract protected function getCommandLine(ReviewableInterface $subject) : string;

    /**
     * @param ReporterInterface   $reporter
     * @param ReviewableInterface $subject
     */
    public function review(ReporterInterface $reporter, ReviewableInterface $subject)
    {
        $process = $this->getProcess($this->getCommandLine($subject));

        $process->run(
            function ($type, $message) use ($reporter, $subject) {
                if ($type) {
                }
                if (empty(trim($message))) {
                    return;
                }
                $this->scanMessage($reporter, $subject, $message);
            }
        );
    }

    /**
     * @param FileInterface $file
     *
     * @return bool
     */
    protected function canReviewFile(FileInterface $file)
    {
        return $file->getExtension() === 'php';
    }

    /**
     * @param CommitMessageInterface $message
     *
     * @return bool
     */
    protected function canReviewMessage(CommitMessageInterface $message)
    {
        return true;
    }
}
