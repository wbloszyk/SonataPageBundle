<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PageBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use Sonata\PageBundle\Command\BaseCommand;
use Sonata\PageBundle\Model\SiteManagerInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Vincent Composieux <vincent.composieux@gmail.com>
 */
class BaseCommandTest extends TestCase
{
    /**
     * @var BaseCommand
     */
    private $command;

    /**
     * Sets up a new BaseCommand instance.
     */
    protected function setUp(): void
    {
        $this->command = $this->createMock(BaseCommand::class);
    }

    /**
     * Tests the getSites() method with different parameters.
     */
    public function testGetSites(): void
    {
        // Given
        $method = new \ReflectionMethod($this->command, 'getSites');
        $method->setAccessible(true);

        $input = $this->createMock(InputInterface::class);
        $siteManager = $this->createMock(SiteManagerInterface::class);

        $this->command->method('getSiteManager')->willReturn($siteManager);

        $input->expects($this->exactly(3))->method('getOption')->with('site')->willReturnOnConsecutiveCalls(
            ['all'],
            ['10'],
            ['10', '11']
        );

        $siteManager->expects($this->exactly(3))->method('findBy')->withConsecutive(
            [[]],
            [['id' => 10]],
            [['id' => [10, 11]]]
        );

        $method->invoke($this->command, $input);
        $method->invoke($this->command, $input);
        $method->invoke($this->command, $input);
    }
}
