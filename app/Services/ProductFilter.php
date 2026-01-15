<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilter
{
    protected Builder $query;
    protected Request $request;

    public function __construct(Builder $query, Request $request)
    {
        $this->query = $query;
        $this->request = $request;
    }

    public function apply(): Builder
    {
        $this->filterByName();
        $this->filterByPrice();
        $this->filterByCategory();
        $this->filterByStock();
        $this->filterByRating();
        $this->applySort();

        return $this->query;
    }

    protected function filterByName(): void
    {
        if ($this->request->filled('q')) {
            $this->query->where('name', 'like', '%' . $this->request->q . '%');
        }
    }

    protected function filterByPrice(): void
    {
        if ($this->request->filled('price_from')) {
            $this->query->where('price', '>=', $this->request->price_from);
        }

        if ($this->request->filled('price_to')) {
            $this->query->where('price', '<=', $this->request->price_to);
        }
    }

    protected function filterByCategory(): void
    {
        if ($this->request->filled('category_id')) {
            $this->query->where('category_id', $this->request->category_id);
        }
    }

    protected function filterByStock(): void
    {
        if ($this->request->filled('in_stock')) {
            $this->query->where('in_stock', filter_var($this->request->in_stock, FILTER_VALIDATE_BOOLEAN));
        }
    }

    protected function filterByRating(): void
    {
        if ($this->request->filled('rating_from')) {
            $this->query->where('rating', '>=', $this->request->rating_from);
        }
    }

    protected function applySort(): void
    {
        match ($this->request->get('sort')) {
            'price_asc'  => $this->query->orderBy('price', 'asc'),
            'price_desc' => $this->query->orderBy('price', 'desc'),
            'rating_desc'=> $this->query->orderBy('rating', 'desc'),
            'newest'     => $this->query->orderBy('created_at', 'desc'),
            default      => $this->query->orderBy('id', 'desc'),
        };
    }
}
