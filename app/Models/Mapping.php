<?php
/**
 * Created by Solaiman Kmail <psokmail@gmail.com>
 */

namespace App\Models;

use App\Http\Mapping\MappingInterface;
use Illuminate\Database\Eloquent\Builder;

trait Mapping
{
    public function scopeMapping(Builder $builder, MappingInterface $mapping)
    {
        $mapping->handle($builder);
    }
}

