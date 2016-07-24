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
use StaticReview\Issue\Issue;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractFileReview as BaseFileReview;
use StaticReview\Review\ReviewableInterface;

/**
 * Class AbstractFileReview.
 */
abstract class AbstractFileReview extends BaseFileReview
{
    use ClimateAwareTrait;

    /**
     * @param string $message
     *
     * @return int Issue level
     */
    abstract protected function scanMessage(string &$message) : int;

    /**
     * @param FileInterface $subject
     *
     * @return string
     */
    abstract protected function getCommandLine(FileInterface $subject) : string;

    /**
     * @param ReporterInterface   $reporter
     * @param ReviewableInterface $subject
     */
    public function review(ReporterInterface $reporter, ReviewableInterface $subject)
    {
        if ($subject instanceof FileInterface) {
            $this->reviewFile($reporter, $subject);
        }
    }

    /**
     * @param FileInterface $file
     *
     * @return bool
     */
    protected function canReviewFile(FileInterface $file)
    {
        return file_exists($file->getName()) && $file->getExtension() === 'php';
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

    /**
     * @param ReporterInterface $reporter
     * @param FileInterface     $subject
     */
    private function reviewFile(ReporterInterface $reporter, FileInterface $subject)
    {
        $process = $this->getProcess($this->getCommandLine($subject));

        $process->run(
            function ($type, $message) use ($reporter, $subject) {
                if ($type) {
                }
                if (empty(trim($message))) {
                    return;
                }
                $result = $this->scanMessage($message);

                $message = $subject->getName().': '.$message;
                switch ($result) {
                    case Issue::LEVEL_ERROR:
                        $reporter->error($message, $this, $subject);
                        break;
                    case Issue::LEVEL_WARNING:
                        $reporter->warning($message, $this, $subject);
                        break;
                    case Issue::LEVEL_INFO:
                        $reporter->info($message, $this, $subject);
                        break;

                }
            }
        );
    }
}
