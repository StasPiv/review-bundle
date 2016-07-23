<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 23.07.16
 * Time: 13:01.
 */

namespace StasPiv\Review\Checker;

use StasPiv\Review\AbstractFileReview;
use StasPiv\Review\ClimateAwareTrait;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\ReviewableInterface;

/**
 * Class PhpCodeShifferChecker.
 */
class PhpCodeShifferChecker extends AbstractFileReview implements CheckerInterface
{
    use ClimateAwareTrait;

    /**
     * @param ReporterInterface   $reporter
     * @param ReviewableInterface $subject
     * @param string              $message
     */
    protected function scanMessage(ReporterInterface $reporter, ReviewableInterface $subject, string $message)
    {
        $this->getClimate()->out($message);

        if (strpos($message, 'ERROR')) {
            $reporter->warning($message, $this, $subject);
        }
    }

    /**
     * @param ReviewableInterface $subject
     *
     * @return string
     */
    protected function getCommandLine(ReviewableInterface $subject) : string
    {
        return 'vendor/bin/phpcs'.' --standard='.'vendor/leaphub/phpcs-symfony2-standard/leaphub/phpcs/Symfony2/'.' '.$subject->getName();
    }
}
