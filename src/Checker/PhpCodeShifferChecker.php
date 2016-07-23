<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 23.07.16
 * Time: 13:01.
 */

namespace StasPiv\Checker;

use StasPiv\Service\ClimateAwareTrait;
use StaticReview\Commit\CommitMessageInterface;
use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;
use StaticReview\Review\ReviewableInterface;

class PhpCodeShifferChecker extends AbstractReview implements CheckerInterface
{
    use ClimateAwareTrait;

    protected function canReviewFile(FileInterface $file)
    {
        return $file->getExtension() === 'php';
    }

    protected function canReviewMessage(CommitMessageInterface $message)
    {
        return true;
    }

    public function review(ReporterInterface $reporter, ReviewableInterface $subject)
    {
        $pathToBeautifier = 'vendor/bin/phpcs';
        $codeStandard = 'vendor/leaphub/phpcs-symfony2-standard/leaphub/phpcs/Symfony2/';
        $cmd = $pathToBeautifier.' --standard='.$codeStandard.' '.$subject->getName();

        $process = $this->getProcess($cmd);

        $process->run(
            function ($type, $message) {
                $this->getClimate()->out($message);
            }
        );
    }
}
