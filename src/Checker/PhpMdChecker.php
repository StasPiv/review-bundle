<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 23.07.16
 * Time: 16:25
 */

namespace StanislavPivovartsev\ReviewBundle\Checker;

use StanislavPivovartsev\ReviewBundle\Service\ClimateAwareTrait;
use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractFileReview;
use StaticReview\Review\ReviewableInterface;

class PhpMdChecker extends AbstractFileReview implements CheckerInterface
{
    use ClimateAwareTrait;

    protected function canReviewFile(FileInterface $file)
    {
        return $file->getExtension() === 'php';
    }

    public function review(ReporterInterface $reporter, ReviewableInterface $subject)
    {
        $pathToPhpMd = 'vendor/bin/phpmd';
        $pathToRules = 'app/phpmd.xml';
        $cmd = $pathToPhpMd.' '.$subject->getName().' text '.$pathToRules;

        $process = $this->getProcess($cmd);

        $process->run(
            function ($type, $message) {
                $this->getClimate()->out($message);
            }
        );
    }

}