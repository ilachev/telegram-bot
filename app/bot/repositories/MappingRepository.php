<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Models\Mapping;

class MappingRepository
{
    public function getMappings()
    {
        return Mapping::all()->toArray();
    }

    public function saveMapping($country, $mapping)
    {
        $newMapping = new Mapping();
        $newMapping->country = $country;
        $newMapping->mapping = $mapping;
        if ($newMapping->save()) {
            return true;
        }
        return null;
    }

    public function getMappingByMask($mask)
    {
        return Mapping::all()->where('mapping', '=', $mask)->first();
    }

    public function deleteMapping($mapping)
    {
        $delMapping = Mapping::all()->where('mapping', '=', $mapping)->first();

        if ($delMapping->delete()) {
            return true;
        } else {
            return null;
        }
    }
}