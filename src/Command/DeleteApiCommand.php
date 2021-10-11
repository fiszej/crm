<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\ApiDataRepository;

class DeleteApiCommand extends Command
{
    protected static $defaultName = 'api:delete';
    protected static $defaultDescription = 'Add a short description for your command';

    public $repository;
    public function __construct(ApiDataRepository $repository)
    {
        $this->repository = $repository;

        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->repository->deleteBeforeGetNewData();
        return Command::SUCCESS;
    }
}
