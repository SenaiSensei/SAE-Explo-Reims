<?php

namespace App\Factory;

use App\Entity\Image;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Image>
 */
final class ImageFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Image::class;
    }

    /**
     * Default values for the Image entity attributes.
     * It generates simulated binary data for the BLOB type.
     * Create images with picsum then download in base 64.
     * And place is either null or defined.
     */
    protected function defaults(): array|callable
    {
        $imageUrl = 'https://picsum.photos/640/480';

        $imageBinary = file_get_contents($imageUrl);

        return [
            'image' => $imageBinary,
            'place' => null,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Image $image): void {})
        ;
    }
}
