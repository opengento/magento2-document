<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Import\StatusUpdater;

use InvalidArgumentException;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Model\Document\Import\StatusUpdaterInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\Output;
use function basename;

final class Cli implements StatusUpdaterInterface
{
    /**
     * @var ProgressBar|null
     */
    private $progressBar;

    /**
     * @var Output|null
     */
    private $output;

    public function init(array $arguments = []): void
    {
        if (!isset($arguments['output'])) {
            throw new InvalidArgumentException('The argument "output" is mandatory.');
        }

        $this->output = $arguments['output'];
    }

    public function start(int $units): void
    {
        $this->progressBar = new ProgressBar($this->output);
        $this->progressBar->setFormat(
            '<info>%message%</info> %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%'
        );
        $this->progressBar->start($units);
    }

    public function advance(string $file, ?DocumentInterface $document): void
    {
        $this->progressBar->setMessage(basename($file) . '...');
        $this->progressBar->advance();
    }

    public function finish(): void
    {
        $this->progressBar->finish();
        $this->output->writeln('');
    }
}
