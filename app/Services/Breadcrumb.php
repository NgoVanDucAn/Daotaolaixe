<?php

namespace App\Services;

class Breadcrumb
{
    protected array $items = [];

    public function add(string $title, ?string $url = null): static
    {
        $this->items[] = [
            'title' => $title,
            'url' => $url,
        ];
        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function merge(array $additional): static
    {
        $this->items = array_merge($this->items, $additional);
        return $this;
    }

    public function all(): array
    {
        return $this->getItems();
    }
}