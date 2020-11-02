<?php

namespace App\Tests\Command;


use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class VelibCoordinatesCommandTest
 *
 * @package App\Tests\Command
 */
class VelibCoordinatesCommandTest extends KernelTestCase
{
    /**
     * @var CommandTester $commandTester
     */
    private $commandTester;

    public function setUp()
    {
        parent::setUp();
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:velib-coordinates');
        $this->commandTester = new CommandTester($command);
    }

    public function testExecute()
    {
        $this->commandTester->execute([
            'geoloc' => '48.8350927878,2.35346813513',
            '--nbRows' => 5
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Name', $output);
        $this->assertStringContainsString('Coordinates GPS', $output);
        $this->assertStringContainsString('Distance', $output);
        $this->assertStringContainsString('Le Brun - Gobelins', $output);
        $this->assertStringContainsString('0.0', $output);
    }
}