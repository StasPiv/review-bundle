<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 23.07.16
 * Time: 16:25.
 */

namespace StasPiv\Review\Checker;

use StasPiv\Review\ClimateAwareTrait;
use StaticReview\Reporter\ReporterInterface;
use StasPiv\Review\AbstractFileReview;
use StaticReview\Review\ReviewableInterface;

/**
 * Class PhpMdChecker.
 */
class PhpMdChecker extends AbstractFileReview implements CheckerInterface
{
    use ClimateAwareTrait;

    private $pathToPhpMdXml = __DIR__.'/../../config/phpmd.xml';

    /**
     * @param ReporterInterface   $reporter
     * @param ReviewableInterface $subject
     * @param string              $message
     */
    protected function scanMessage(ReporterInterface $reporter, ReviewableInterface $subject, string $message)
    {
        $this->getClimate()->yellow($message);
        $reporter->warning($message, $this, $subject);
    }

    /**
     * @param ReviewableInterface $subject
     *
     * @return string
     */
    protected function getCommandLine(ReviewableInterface $subject) : string
    {
        return 'vendor/bin/phpmd'.' '.$subject->getName().' text '.$this->pathToPhpMdXml;
    }

    /**
     * @param string $pathToPhpMdXml
     */
    public function setPathToPhpMdXml($pathToPhpMdXml)
    {
        $this->pathToPhpMdXml = $pathToPhpMdXml;
    }
}
