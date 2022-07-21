<?php

namespace App\Tests\Infrastructure\ConsoleCommand;

use App\Domain\ContactList\ContactListCreatedDomainEvent;
use App\Domain\ContactList\ContactListRenamedDomainEvent;
use App\Infrastructure\Bus\SymfonyMessengerCommandBus;
use App\Infrastructure\ConsoleCommand\ImportContactListsConsoleCommand;
use App\Tests\BaseKernelTestCase;
use App\Tests\Domain\ContactList\ContactListIdMother;
use App\Tests\Domain\ContactList\ContactListNameMother;
use Grisendo\DDD\Bus\Event\EventBus;
use Mockery;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ImportContactListsConsoleCommandTest extends BaseKernelTestCase
{
    public function testNoChanges(): void
    {
        $application = new Application();
        $eventBus = Mockery::mock(EventBus::class);
        $eventBus
            ->shouldReceive('getPublishedEvents')
            ->once()
            ->with()
            ->andReturn([]);
        $application->add(
            new ImportContactListsConsoleCommand(
                new SymfonyMessengerCommandBus(
                    self::getContainer()->get('command.bus')
                ),
                $eventBus
            )
        );
        $command = $application->find(
            ImportContactListsConsoleCommand::getDefaultName()
        );
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString(
            '0 list(s) created',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            '0 list(s) renamed',
            $commandTester->getDisplay()
        );
    }

    public function testListCreated(): void
    {
        $application = new Application();
        $eventBus = Mockery::mock(EventBus::class);
        $eventBus
            ->shouldReceive('getPublishedEvents')
            ->once()
            ->with()
            ->andReturn([
                new ContactListCreatedDomainEvent(
                    ContactListIdMother::random()->getValue()->getValue(),
                    ContactListNameMother::random()->getValue()
                ),
            ]);
        $application->add(
            new ImportContactListsConsoleCommand(
                new SymfonyMessengerCommandBus(
                    self::getContainer()->get('command.bus')
                ),
                $eventBus
            )
        );
        $command = $application->find(
            ImportContactListsConsoleCommand::getDefaultName()
        );
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString(
            '1 list(s) created',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            '0 list(s) renamed',
            $commandTester->getDisplay()
        );
    }

    public function testListRenamed(): void
    {
        $application = new Application();
        $eventBus = Mockery::mock(EventBus::class);
        $eventBus
            ->shouldReceive('getPublishedEvents')
            ->once()
            ->with()
            ->andReturn([
                new ContactListRenamedDomainEvent(
                    ContactListIdMother::random()->getValue()->getValue(),
                    ContactListNameMother::random()->getValue()
                ),
            ]);
        $application->add(
            new ImportContactListsConsoleCommand(
                new SymfonyMessengerCommandBus(
                    self::getContainer()->get('command.bus')
                ),
                $eventBus
            )
        );
        $command = $application->find(
            ImportContactListsConsoleCommand::getDefaultName()
        );
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString(
            '0 list(s) created',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            '1 list(s) renamed',
            $commandTester->getDisplay()
        );
    }
}
