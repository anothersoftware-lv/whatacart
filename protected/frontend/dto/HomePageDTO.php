<?php
/**
 * @copyright Copyright (C) 2016 Usha Singhai Neo Informatique Pvt. Ltd
 * @license https://www.gnu.org/licenses/gpl-3.0.html
 */
namespace frontend\dto;
/**
 * Data transfer object for home page
 *
 * @package frontend\dto
 */
class HomePageDTO extends \usni\library\dto\BaseDTO
{
    private $_latestProducts;
    private $_mostPopularProducts;
    private $_bestsellerProducts;
    private $_specialOfferProducts;
    private $_brands;

    public function getLatestProducts()
    {
        return $this->_latestProducts;
    }

    public function getMostPopularProducts()
    {
        return $this->_mostPopularProducts;
    }

    public function getBestsellerProducts()
    {
        return $this->_bestsellerProducts;
    }

    public function getSpecialOfferProducts()
    {
        return $this->_specialOfferProducts;
    }

    public function getBrands()
    {
        return $this->_brands;
    }

    public function setLatestProducts($latestProducts)
    {
        $this->_latestProducts = $latestProducts;
    }

    public function setMostPopularProducts($mostPopularProducts)
    {
        $this->_mostPopularProducts = $mostPopularProducts;
    }

    public function setBestsellerProducts($bestsellerProducts)
    {
        $this->_bestsellerProducts = $bestsellerProducts;
    }

    public function setSpecialOfferProducts($specialOfferProducts)
    {
        $this->_specialOfferProducts = $specialOfferProducts;
    }

    public function setBrands($brands)
    {
        $this->_brands = $brands;
    }

}
