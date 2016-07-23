<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 23.07.16
 * Time: 14:30.
 */

namespace StasPiv\Review\Fixer;

use StasPiv\Review\ClimateAwareTrait;
use StaticReview\Reporter\ReporterInterface;
use StasPiv\Review\AbstractFileReview;
use StaticReview\Review\ReviewableInterface;

/**
 * Class PhpCsFixer.
 */
class PhpCsFixer extends AbstractFileReview implements FixerInterface
{
    use ClimateAwareTrait;

    /**
     * @param ReporterInterface   $reporter
     * @param ReviewableInterface $subject
     * @param string              $message
     */
    protected function scanMessage(ReporterInterface $reporter, ReviewableInterface $subject, string $message)
    {
        if ($message == 'F') {
            $message = 'Styling has been fixed in '.$subject->getName();

            $this->getClimate()->green($message);
            $reporter->info($message, $this, $subject);
        }
    }

    /**
     * @param ReviewableInterface $subject
     *
     * @return string
     */
    protected function getCommandLine(ReviewableInterface $subject) : string
    {
        return 'vendor/bin/php-cs-fixer -vvv fix '.$subject->getName().' --level=symfony';
    }
}
