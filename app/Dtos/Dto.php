<?php

namespace App\Dtos;

use App\Traits\ArrayableTrait;
use Illuminate\Contracts\Support\Arrayable;

readonly abstract class Dto implements Arrayable
{
    use ArrayableTrait;
}
