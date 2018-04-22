<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Developed by MIT <contact@mit-agency.com>
 *
 */

namespace AppBundle\Command;

use AppBundle\Model\EventManager;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppEventArchiveCommand extends AppCommand
{
    use LockableTrait;

    private $commandName = 'app:event:archive';

    /** @var EventManager */
    private $eventManager;

    /**
     * AppEventArchiveCommand constructor.
     *
     * @param EventManager $eventManager
     */
    public function __construct(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;

        parent::__construct($this->commandName);
    }

    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription('Archive passed events and its medias.')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $argument = $input->getArgument('argument');

            if ($input->getOption('option')) {
                // ...
            }

            $output->writeln('AppEventArchiveCommand result.');
        }

        $this->release();
    }
}
