<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 24.07.16
 * Time: 18:56.
 */

namespace StasPiv\Review\Command;

use StasPiv\Review\Handler\ReviewHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PrecommitReviewCommand.
 */
class PrecommitReviewCommand extends Command
{
    /**
     *
     */
    protected function configure()
    {
        $this->setName('review:run');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        (new ReviewHandler())->run();
    }
}
