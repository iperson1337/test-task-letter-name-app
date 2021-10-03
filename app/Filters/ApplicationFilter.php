<?php

namespace App\Filters;

class ApplicationFilter extends QueryFilter
{
    public function name($value)
    {
        $this->builder->where('name', 'LIKE', "%$value%");
    }

    public function status_id($value)
    {
        $this->builder->where('status_id', $value);
    }

    public function created_at($value)
    {
        $this->builder->whereDate('created_at', $value);
    }

}
