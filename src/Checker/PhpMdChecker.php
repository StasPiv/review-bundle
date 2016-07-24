<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 23.07.16
 * Time: 16:25.
 */

namespace StasPiv\Review\Checker;

use StaticReview\File\FileInterface;
use StaticReview\Issue\Issue;
use StasPiv\Review\AbstractFileReview;

/**
 * Class PhpMdChecker.
 */
class PhpMdChecker extends AbstractFileReview implements CheckerInterface
{
    /**
     * @var string
     */
    private $pathToPhpMdXml = __DIR__.'/../../config/phpmd.xml';

    /**
     * @param string $message
     *
     * @return int
     */
    protected function scanMessage(string &$message) : int
    {
        return Issue::LEVEL_WARNING;
    }

    /**
     * @param FileInterface $subject
     *
     * @return string
     */
    protected function getCommandLine(FileInterface $subject) : string
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
