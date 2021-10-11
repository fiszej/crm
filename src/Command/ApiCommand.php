<?php

namespace App\Command;

use App\Repository\ApiDataRepository;
use App\Service\ApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ApiCommand extends Command
{
    protected static $defaultName = 'api:update';
    protected static $defaultDescription = 'Add a short description for your command';

    public $repository;
    public $service;
    public function __construct(ApiDataRepository $repository, ApiService $service)
    {
        $this->repository = $repository;
        $this->service = $service;

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
        $this->repository->save($this->service);
        return Command::SUCCESS;
    }
}
