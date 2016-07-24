<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 24.07.16
 * Time: 19:09.
 */

namespace StasPiv\Review\Handler;

use StasPiv\Review\Checker\PhpCodeShifferChecker;
use StasPiv\Review\Checker\PhpMdChecker;
use StasPiv\Review\Checker\DebugChecker;
use StasPiv\Review\ClimateAwareTrait;
use StasPiv\Review\ProcessAwareTrait;
use StaticReview\Issue\Issue;
use StaticReview\Reporter\Reporter;
use StaticReview\StaticReview;
use StaticReview\VersionControl\GitVersionControl;
use StasPiv\Review\Fixer\PhpCsFixer;

/**
 * Class ReviewHandler.
 */
class ReviewHandler
{
    use ClimateAwareTrait, ProcessAwareTrait;

    /**
     * @var StaticReview
     */
    private $review;

    /**
     * @var int
     */
    private $info = 0;

    /**
     * @var int
     */
    private $warnings = 0;
    /**
     * @var int
     */
    private $errors = 0;

    /**
     * @var Reporter
     */
    private $reporter;

    /**
     * ReviewHandler constructor.
     */
    public function __construct()
    {
        $this->reporter = new Reporter();
        $this->review = new StaticReview($this->reporter);
    }

    /**
     *
     */
    public function run()
    {
        // Add any reviews to the StaticReview instance, supports a fluent interface.
        foreach ($this->getReviews() as $review) {
            $this->review->addReview($review);
        }

        // Review the staged files.
        $git = new GitVersionControl();

        $this->review->files($git->getStagedFiles());

        echo PHP_EOL;

        $this->check()->report();
    }

    /**
     * @return $this
     */
    private function check()
    {
        if ($this->reporter->hasIssues()) {
            foreach ($this->reporter->getIssues() as $issue) {
                /** @var Issue $issue */
                switch ($issue->getLevel()) {
                    case Issue::LEVEL_INFO:
                        ++$this->info;
                        break;
                    case Issue::LEVEL_WARNING:
                        ++$this->warnings;
                        break;
                    case Issue::LEVEL_ERROR:
                        ++$this->errors;
                        break;
                }
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function report()
    {
        if (!$this->reporter->hasIssues()) {
            $this->getClimate()->green('✔ Looking good. Have you tested everything?');

            return $this;
        }

        if ($this->info > 0) {
            $this->reportInfo();
        }

        if ($this->warnings > 0) {
            $this->reportWarnings();
        }

        if ($this->errors > 0) {
            $this->reportErrors();
        }

        return $this;
    }

    /**
     * @return array
     */
    private function getReviews()
    {
        return [
            new PhpCsFixer(),
            new PhpMdChecker(),
            new DebugChecker(),
            new PhpCodeShifferChecker(),
        ];
    }

    private function reportErrors()
    {
        foreach ($this->reporter->getIssues() as $issue) {
            /** @var Issue $issue */
            if ($issue->getLevel() == Issue::LEVEL_ERROR) {
                $this->getClimate()->red($issue->getMessage());
            }
        }

        $this->getClimate()->backgroundRed('<white>✘ Review has found errors.</white>');
    }

    private function reportWarnings()
    {
        foreach ($this->reporter->getIssues() as $issue) {
            /** @var Issue $issue */
            if ($issue->getLevel() == Issue::LEVEL_WARNING) {
                $this->getClimate()->yellow($issue->getMessage());
            }
        }
        $this->getClimate()->backgroundYellow(
            'Review has found warnings. It\'s not recommended to commit'
        );
    }

    private function reportInfo()
    {
        foreach ($this->reporter->getIssues() as $issue) {
            /** @var Issue $issue */
            if ($issue->getLevel() == Issue::LEVEL_INFO) {
                $this->getClimate()->green($issue->getMessage());
            }
        }

        $this->getClimate()->backgroundGreen(
            'Review has found some problems and fixed them manually. But you need to commit these changes'
        );
    }
}
