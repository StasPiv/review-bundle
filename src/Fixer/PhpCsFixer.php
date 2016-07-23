<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 23.07.16
 * Time: 14:30.
 */

namespace StanislavPivovartsev\ReviewBundle\Fixer;

use StanislavPivovartsev\ReviewBundle\Service\ClimateAwareTrait;
use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractFileReview;
use StaticReview\Review\ReviewableInterface;

class PhpCsFixer extends AbstractFileReview implements FixerInterface
{
    use ClimateAwareTrait;

    protected function canReviewFile(FileInterface $file)
    {
        return $file->getExtension() === 'php';
    }

    public function review(ReporterInterface $reporter, ReviewableInterface $subject)
    {
        $cmd = 'vendor/bin/php-cs-fixer -vvv fix '.$subject->getName().' --level=symfony';
        $process = $this->getProcess($cmd);

        $process->run(
            function ($type, $message) use ($subject, $reporter) {
                if ($message == 'F') {
                    $message = 'Styling has been fixed in '.$subject->getName();

                    $this->getClimate()->backgroundYellow($message);
                    $reporter->warning($message, $this, $subject);
                }
            }
        );
    }
}
