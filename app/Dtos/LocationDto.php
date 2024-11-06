<?php

namespace App\Dtos;

readonly class LocationDto extends Dto
{
    public function __construct(
        public string $city,
        public string $state,
    ) {}
}
