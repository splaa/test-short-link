<?php


namespace SL;


class Link
{
    public function __construct(
       public ?int $id,
       public string $url,
       public string $shortUrl
    ){}
}