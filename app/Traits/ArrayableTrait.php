<?php
namespace App\Traits;

trait ArrayableTrait
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
