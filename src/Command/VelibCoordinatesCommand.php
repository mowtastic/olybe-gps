<?php

namespace App\Command;

use App\Service\VelibCoordinates;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class VelibCoordinatesCommand extends Command
{
    protected static $defaultName = 'app:velib-coordinates';

    /**
     * @var VelibCoordinates $velibCoordinates
     */
    private $velibCoordinates;

    /**
     * VelibCoordinatesCommand constructor.
     *
     * @param VelibCoordinates $velibCoordinates
     */
    public function __construct(VelibCoordinates $velibCoordinates)
    {
        parent::__construct();
        $this->velibCoordinates = $velibCoordinates;
    }

    protected function configure()
    {
        $this
            ->addArgument('geoloc', InputArgument::REQUIRED, 'latitude and longitude from address')
            ->addOption('nbRows', null, InputOption::VALUE_REQUIRED, 'number of stations to fetch', 5)
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $stations = $this->velibCoordinates->getClosestStations(
            $input->getArgument('geoloc'),
            $input->getOption('nbRows')
        );

        if (empty($stations)) {
            $output->writeln('No stations closed');
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Name', 'Coordinates GPS', 'Distance']);

        foreach ($stations as $station) {
            $table->addRow([$station['name'], $station['coordinates'], $station['dist']]);
        }
        $table->render();

        return Command::SUCCESS;
    }

}