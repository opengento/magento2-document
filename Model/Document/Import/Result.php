<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Import;

use function count;

final class Result implements ResultInterface
{
    /**
     * @var array
     */
    private $messages;

    public function __construct(
        array $messages
    ) {
        $this->messages = $messages;
    }

    public function count(): int
    {
        return count($this->messages);
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
