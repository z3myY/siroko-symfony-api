<?php

namespace App\Shared\Domain\ValueObject;

use JsonSerializable;

interface ValueObjectInterface extends JsonSerializable
{
    public function value(): mixed;
}
