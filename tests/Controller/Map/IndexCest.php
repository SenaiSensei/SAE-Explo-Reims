<?php

namespace App\Tests\Controller\Map;

use App\Factory\CategoryFactory;
use App\Factory\PlaceFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function _before(ControllerTester $I)
    {
    }

    /**
     * Test the presence of places markers on the map page.
     * It verifies that the markers are loaded and rendered as expected.
     *
     * @param ControllerTester $I the test actor object
     */
    public function verficationPlacesMarker(ControllerTester $I): void
    {
        $category = CategoryFactory::createOne();
        PlaceFactory::createMany(5, ['category' => $category]);

        $I->amOnPage('/map');
        $I->waitForElement('img.leaflet-marker-icon', 10);
    }

    /**
     * Test redirection from a map marker to the corresponding place page.
     *
     * This test verifies that clicking on a marker displayed on the map
     * redirects the user to the correct place's details page.
     *
     * @param ControllerTester $I the test actor object
     */
    public function redirectionVerificationFromMapToPlace(ControllerTester $I): void
    {
        $category = CategoryFactory::createOne();
        PlaceFactory::createMany(1, ['category' => $category]);

        $I->amOnPage('/map');
        $I->waitForElementClickable('img.leaflet-marker-icon', 10);
        $I->click('img.leaflet-marker-icon.leaflet-zoom-animated.leaflet-interactive');
        $I->waitForElementClickable('div.leaflet-popup-content a', 5);
        $I->click('div.leaflet-popup-content a');
        $I->seeCurrentUrlEquals('/place/1');
        $category = CategoryFactory::createOne();
        PlaceFactory::createMany(5, ['category' => $category]);

        $I->amOnPage('/map');
        $I->waitForElement('img.leaflet-marker-icon', 10);
    }
}
