<?php

namespace Plug4Market;

class ProdutoDTO
{
    public function __construct(
        public string $brand,
        public string $description,
        public float $height,
        public float $lenght,
        public string $name,
        public float $price,
        public string $productId,
        public string $productname,
        public string $sku,
        public float $weight,
        public \DateTime $data,
        public ?string $active = null,
        public float $width
    ) {}

    public function toArray(): array
    {
        return [
            'brand'        => $this->brand,
            'description'  => $this->description,
            'height'       => $this->height,
            'lenght'       => $this->lenght,
            'name'         => $this->name,
            'price'        => $this->price,
            'productId'    => $this->productId,
            'productname'  => $this->productname,
            'sku'          => $this->sku,
            'weight'       => $this->weight,
            'width'        => $this->width,
            'data'         => $this->data->format('Y-m-d H:i:s'),
            'active'       => $this->active,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}
