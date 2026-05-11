<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable(['street_address', 'street_address_2', 'phone', 'full_name', 'customer_id', 'city', 'state', 'postal_code', 'country', 'is_default', 'type'])]
class Address extends Model
{
    //
    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    #[Scope()]
    public function default(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    #[Scope()]
    public function type(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getFullAddressAttribute(): string
    {
        $address = $this->street_address;

        if ($this->street_address_2) {
            $address .= ', ' . $this->street_address_2;
        }

        $address .= ', ' . $this->city;

        if ($this->state) {
            $address .= ', ' . $this->state;
        }

        $address .= ', ' . $this->postal_code;
        $address .= ', ' . $this->country;

        return $address;
    }
}
