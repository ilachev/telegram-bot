<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Models\Mapping;

class MappingRepository
{
    public function getMappings()
    {
        return Mapping::all()->toArray();
    }
}