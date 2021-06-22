<?php
namespace common\modules\order\lib\numtotext;
/**
 * 
 * Class NumToText
 * Converts integer numbers to text representation in English, Latvian or Russian.
 * @author Aleksejs Ivanovs <ivanovs.aleksejs@gmail.com>
 * @version 1.0
 * 
 */
class NumToText {

    public static $i;

    public
        $lang       = false,
        $step       = 0,
        $currency   = array();

    public function getCurrencies()
    {

        $currencies = array(
            'LV'    =>array(
                'LVL' => array(
                    array('lati', 'lats'),
                    array('santīmi', 'santīms'),
                ),
                'USD' => array(
                    array('dolāri', 'dolārs'),
                    array('centi', 'cents'),
                ),
                'EUR' => array(
                    array('eiro', 'eiro'),
                    array('centi', 'cents'),
                ),
            ),
            'RU'    =>array(
                'LVL' => array(
                    array('латов', 'лат', 'лата'),
                    array('сантимов', 'сантим', 'сантима'),
                ),
                'USD' => array(
                    array('долларов', 'доллар', 'доллара'),
                    array('центов', 'цент', 'цента'),
                ),
                'EUR' => array(
                    array('евро', 'евро', 'евро'),
                    array('центов', 'цент', 'цента'),
                ),
            ),
            'EN'    =>array(
                'LVL' => array(
                    array('lats', 'lat'),
                    array('centimes', 'centime'),
                ),
                'USD' => array(
                    array('dollars', 'dollar'),
                    array('cents', 'cent'),
                ),
                'EUR' => array(
                    array('euros', 'euro'),
                    array('cents', 'cent'),
                ),
            )
        );

        return $currencies;
    }

    /**
     * Converts 3-digit portions (like thousands, millions etc) of number to a text
     *
     * @param integer $int
     *
     * @return string
     */
    public function threeDigitsToWord($int){

        $div100 = $int / 100;
        $mod100 = $int % 100;

        return 
            $this->digitToWord(floor($div100), 2) .

            ($mod100 > 9 && $mod100 < 20
                // 10, 11, .. 19
                ? $this->teens[$mod100 - 10]
                //any other number
                : $this->digitToWord(floor($mod100 / 10), 1) . $this->digitToWord($int % 10));
    }

    /**
     * Returns currency string
     *
     * @param integer $int
     * @param boolean $cent
     *
     * @return string
     */
    public function getCurrencyString($int, $cent = false) {
        return  $this->currency[(int)($cent > 0)][(int)($int % 100 == 1)];
    }

    /**
     * Sets currency
     * See currencies.php example file
     *
     * @param mixed $currency
     */
    public function setCurrency(&$currency) {
        $this->currency = $currency;
    }

    /**
     * Returns price as text
     *
     * @param integer $int
     * @param boolean $cents_as_number
     *
     * @return string
     */
    public function displayPrice($int, $cents_as_number = false, $display_zero_cents = false) {


        $part_int = (int)abs($int);
        $part_decimal = (int)round(abs($int) * 100 - floor(abs($int)) * 100);

        return ($int < 0 ? $this->negative . ' ' : '')
            . $this->toWords($part_int)
            . " " . $this->getCurrencyString($part_int) .
            (($int == floor($int) and !$display_zero_cents)
                ? ''
                :
                    " " . ($cents_as_number ? $part_decimal : $this->toWords($part_decimal)) .
                    " " . $this->getCurrencyString($part_decimal, true)
            );
    }
}

/**
 * Shorthand function for the class
 *
 * @param integer $int
 * @param string $lang
 *
 * @return string
 * @example echo NumToText(123456, 'EN');
 * Echoes 'one hundred twenty three thousand four hundred fifty six'
 */
function NumToText($int, $lang = 'LV'){

    $name = 'NumToText_' . $lang;

    return 
        class_exists($name)
        ? call_user_func_array(array($name, '__i'), array())
            ->toWords((int)$int)
        : false;

}

/**
 * Shorthand function to display price as text
 *
 * @param integer $int
 * @param mixed $currencies
 * @param string $lang
 * @param boolean $cents_as_number
 *
 * @return string
 * @example echo PriceToText(123456.78, array(array('dollars', 'dollar'), array('cents', 'cent')), 'EN', true);
 * Echoes 'one hundred twenty three thousand four hundred fifty six dollars 78 cents'
 * @example echo PriceToText(123456.78, array(array('dollars', 'dollar'), array('cents', 'cent')), 'EN');
 * Echoes 'one hundred twenty three thousand four hundred fifty six dollars seventy eight cents'
 */
function PriceToText($int, $currencies, $lang = 'LV', $cents_as_number = false, $display_zero_cents = false) {

    $name = 'NumToText_' . $lang;

    class_exists($name) AND call_user_func_array(array($name, '__i'), array())
                                ->setCurrency($currencies);

    return 
        class_exists($name)
        ? call_user_func_array(array($name, '__i'), array())
            ->displayPrice($int, $cents_as_number, $display_zero_cents)
        : false;
}
