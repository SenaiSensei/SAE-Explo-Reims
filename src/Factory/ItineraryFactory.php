<?php

namespace App\Factory;

use App\Entity\Itinerary;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

use function Zenstruck\Foundry\faker;

/**
 * @extends PersistentProxyObjectFactory<Itinerary>
 */
final class ItineraryFactory extends PersistentProxyObjectFactory
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
        return Itinerary::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $itineraryName = mb_convert_case(self::faker()->word(), MB_CASE_TITLE, 'UTF-8');

        if (faker()->boolean(50)) {
            return [
                'name' => $itineraryName,
                'description' => self::faker()->text(150),
                'user' => UserFactory::random(),
                'time' => faker()->numberBetween(30, 600),
                'distance' => faker()->numberBetween(500, 10000),
                'note' => faker()->randomFloat(2, 0, 5),
            ];
        } else {
            return [
                'name' => $itineraryName,
                'description' => self::faker()->text(150),
                'user' => UserFactory::random(),
            ];
        }
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Itinerary $itinerary): void {})
        ;
    }
}
