<?php
namespace common\modules\order\lib\numtotext;

class NumToText_RU extends NumToText{

    public
        $lang       = 'RU',
        $negative   = 'минус',
        $zero       = 'ноль';

    /**
     * @return instanceof self
     */
    public static function __i(){
        return (!self::$i instanceof self) ? (self::$i = new self) : self::$i;
    }

    public
        $digits     = array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять', 'одна', 'две'),
        $tens       = array('', 'десять ', 'двадцать ', 'тридцать ', 'сорок ', 'пятьдесят ', 'шестьдесят ', 'семьдесят ', 'восемьдесят ', 'девяносто '),
        $teens      = array('десять ', 'одиннадцать ', 'двенадцать ', 'тринадцать ', 'четырнадцать ', 'пятнадцать ', 'шестнадцать ', ' семнадцать ', 'восемнадцать ', 'девятнадцать '),
        $hundreds   = array('', ' сто ', ' двести ', ' триста ', ' четыреста ', ' пятьсот ', ' шестьсот ', ' семьсот ', ' восемьсот ', ' девятьсот '),
        $exp        = array('', ' тысяч ', ' миллионов ', ' миллиардов '),
        $exp1       = array('', ' тысяча ', ' миллион ', ' миллиард '),
        $exp2       = array('', ' тысячи ', ' миллиона ', ' миллиарда ');

    /**
     * Converts single digit to text
     * $suf is the parameter that shows if digit is tens, hundreds etc
     *
     * @param integer $digit
     * @param suffix $suf
     *
     * @return string
     */
    public function digitToWord($digit, $suf = 0){

        return $digit > 0
            ? $suf == 2
                ? $this->hundreds[$digit]
                : ($suf == 1
                    ? $this->tens[$digit]
                    : (($digit == 1 || $digit == 2) && $this->step == 1
                        ? $this->digits[$digit+9]
                        : $this->digits[$digit])
                )
            : '';

    }

    /**
     * Main method
     *
     * @param integer $int
     *
     * @return string
     */
    public function toWords($int){

        $sign = $int < 0 ? $this->negative . ' ' : '';
        $int = abs($int);

        $this->step = 0;
        $return = $int == 0 ? $this->zero : '';

        while (($three = $int % 1000) || ($int >= 1)) {
            $int /= 1000;

            $mod10 = $three % 10;
            $mod100 = $three % 100;

            $exp = $mod10 > 1 && $mod10 < 5 && ($mod100 < 11 || $mod100 > 19) ? $this->exp2[$this->step] : $this->exp[$this->step];
            $exp = $mod10 == 1 && ($mod100 < 11 || $mod100 > 19) ? $this->exp1[$this->step] : $exp;

            $return = ($three >= 1
                    ? $this->threeDigitsToWord($three) . $exp
                    : '')
                . $return;
            $this->step++;
        }

        return $sign.$return;

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
        $cent = $cent ? 1 : 0;
        return (($int % 100 > 9 && $int % 100 < 20) || $int % 10 == 0 || $int % 10 > 4)
            ? $this->currency[$cent][0]
            : ($int % 10 == 1 ? $this->currency[$cent][1] : $this->currency[$cent][2] );
    }

}
