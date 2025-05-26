<?php
//
//namespace App\Services;
//
//use App\Dtos\Factories\ReviewDtoFactory;
//use App\Dtos\Models\ReviewDto;
//use App\Models\Review;
//use App\Services\BaseService;
//use Facebook\WebDriver\Exception\StaleElementReferenceException;
//use Facebook\WebDriver\Remote\DesiredCapabilities;
//use Facebook\WebDriver\Remote\RemoteWebDriver;
//use Facebook\WebDriver\WebDriverBy;
//use Facebook\WebDriver\WebDriverExpectedCondition;
//
//class YandexMapApiService extends BaseService
//{
//    protected const YANDEX_PAGE = 'https://yandex.ru/maps/org/garden_coffee/145158305596/reviews/';
//
//    protected const serverUrl = 'http://selenium:4444/wd/hub';
//    protected const API_REVIEWS_URL = 'https://public-api.reviews.2gis.com/2.0/orgs/70000001020476016/reviews';
//
//    public function __construct(
//        protected ReviewDtoFactory $reviewDtoFactory,
//    ) {
//    }
//
//    /**
//     * @return array<ReviewDto>
//     */
//    public function requestReviewsFromApi(): array
//    {
////        $oCUrlSession = curl_init();
////
////        curl_setopt($oCUrlSession, CURLOPT_CUSTOMREQUEST, 'GET');
////
////        curl_setopt($oCUrlSession, CURLOPT_SSL_VERIFYPEER, false);
////        curl_setopt($oCUrlSession, CURLOPT_SSL_VERIFYHOST, false);
////
////        curl_setopt($oCUrlSession, CURLOPT_RETURNTRANSFER, true);
////        curl_setopt($oCUrlSession, CURLOPT_HEADER, true);
////
////        curl_setopt($oCUrlSession, CURLOPT_URL, self::YANDEX_PAGE);
////
////        $response = curl_exec($oCUrlSession);
////
////        curl_close($oCUrlSession);
//
//
//        $driver = RemoteWebDriver::create(self::serverUrl, DesiredCapabilities::chrome());
//        $driver->get(self::YANDEX_PAGE);
//        $driver->manage()->window()->maximize();
//       try {
//            $driver->findElement(WebDriverBy::cssSelector('.card-section-header__right-content'))->click();
//            $driver->findElements(WebDriverBy::cssSelector('.rating-ranking-view__popup-line'))[1]->click();
//        } catch (StaleElementReferenceException  $exception) {
//           dump(1);
//       }
////        dump($driver->findElement(WebDriverBy::name('.rating-ranking-view__popup-line'))->takeElementScreenshot());
//
//        $page = $driver->();
//        $driver->quit();
//        die($page);
//
////        $params = [
////            'key' => env('TWO_GIS_API_KEY'),
////            'locale' => 'ru_RU',
////            'limit' => 50,
////            'sort_by' => 'date_edited',
////            'without_my_first_review' => true,
////
////        ];
//
//
//
//        $reviews = json_decode($response, true)['reviews'];
//
//        return array_map(
//            function ($review) {
//                return $this->reviewDtoFactory->createFromTwoGis($review);
//            },
//            $reviews
//        );
//    }
//}
