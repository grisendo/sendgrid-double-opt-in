<?php

namespace App\Tests\Infrastructure\ConsoleCommand;

use App\Infrastructure\Bus\SymfonyMessengerCommandBus;
use App\Infrastructure\Bus\SymfonyMessengerEventBus;
use App\Infrastructure\ConsoleCommand\ImportContactListsConsoleCommand;
use App\Tests\BaseKernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ImportContactListsConsoleCommandTest extends BaseKernelTestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        parent::setUp();
        $application = new Application();
        $application->add(
            new ImportContactListsConsoleCommand(
                new SymfonyMessengerCommandBus(
                    self::getContainer()->get('command.bus')
                ),
                new SymfonyMessengerEventBus(
                    self::getContainer()->get('event.bus')
                ),
            )
        );
        $command = $application->find(
            ImportContactListsConsoleCommand::getDefaultName()
        );
        $this->commandTester = new CommandTester($command);
    }

    public function testFoo(): void
    {
        $exitCode = $this->commandTester->execute([]);

        $this->assertEquals(Command::SUCCESS, $exitCode);
    }
}
