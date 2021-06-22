<?php
/**
 * @copyright Copyright (C) 2016 Usha Singhai Neo Informatique Pvt. Ltd
 * @license https://www.gnu.org/licenses/gpl-3.0.html
 */
namespace common\utils;

use usni\UsniAdaptor;
use common\utils\ApplicationUtil;
use cart\models\Cart;
use yii\base\Model;
use products\models\CompareProducts;
use cart\models\CheckoutCustom as Checkout;
use wishlist\models\Wishlist;
/**
 * ApplicationUtil class file.
 * 
 * @package common\utils
 */
class ImageUtil
{
    /**
     * Requires PHP Imagick extension installed
     * @param string|\Imagick $image
     * @param array $watermarkOptions
     * @return \Imagick
     */
    public static function watermarkWithText($image, array $watermarkOptions )
    {
        // If not existing Imagic resource
        if (is_string($image)) {
            $image = new \Imagick($image);
        }

        $watermarkDefaultOptions = [
            'width'       => 260,
            'height'      => 60,
            'font'        => 'Helvetica',
            'font_size'   => 16,
            'text_color'  => 'grey',
            'opacity'     => '.1',
            'x_position'  => 20,
            'y_position'  => 20,
        ];

        $watermarkOptions = array_merge($watermarkDefaultOptions, $watermarkOptions);

        $imageWidth = $image->getImageWidth();
        $imageHeight = $image->getImageHeight();

        $watermark = new \Imagick();

        // Create a new drawing palette
        $draw = new \ImagickDraw();
        $watermark->newImage($watermarkOptions['width'], $watermarkOptions['height'], new \ImagickPixel('none'));

        $watermarkImageWidth = $watermark->getImageWidth();
        $watermarkImageHeight = $watermark->getImageHeight();

        // Set font properties
        $fonts = $image->queryFonts();

        if(strstr($watermarkOptions['font'], '.')) {
            if(! file_exists($watermarkOptions['font'])) {
                echo 'Font: ' . $watermarkOptions['font'] . ' not exists by specified path. Using default: '
                    . $watermarkDefaultOptions['font'];
                $watermarkOptions['font'] = $watermarkDefaultOptions['font'];
            }
        } else if(!in_array($watermarkOptions['font'], $fonts)) {
            echo 'Font: ' . $watermarkOptions['font'] . ' not supported by system. Using default: '
                . $watermarkDefaultOptions['font'];
            $watermarkOptions['font'] = $watermarkDefaultOptions['font'];
        }
        $draw->setFont($watermarkOptions['font']);
        $draw->setFontSize($watermarkOptions['font_size']);
        $draw->setFillColor($watermarkOptions['text_color']);
        $draw->setFillOpacity($watermarkOptions['opacity']);

        // Position text at the top left of the watermark
        //$draw->setGravity(\Imagick::GRAVITY_NORTHWEST);
        // Position text at the bottom right of the watermark
        $draw->setGravity(\Imagick::GRAVITY_SOUTHEAST);

        //$draw->setTextEncoding("UTF-8");
        //$draw->setStrokeAntialias(true);
        //$draw->setTextAntialias(true);

        // Draw text on the watermark
        $watermark->annotateImage($draw, 10, 10, 0, $watermarkOptions['text']);

        if (isset($watermarkOptions['repeat_random'])) {
            // Repeatedly overlay watermark on image
            for ($w = 0; $w < $imageWidth; $w += 140) {
                for ($h = 0; $h < $imageHeight; $h += 80) {
                    $image->compositeImage($watermark, \Imagick::COMPOSITE_OVER, $w, $h);
                }
            }
        }

        $composite = \Imagick::COMPOSITE_OVER;

        if(isset($watermarkOptions['center_position'])) {
            $composite = \Imagick::COMPOSITE_DEFAULT;
            $watermarkOptions['x_position'] = ($imageWidth - $watermarkImageWidth) / 2;
            $watermarkOptions['y_position'] = ($imageHeight - $watermarkImageHeight) / 2;
        }

        $image->compositeImage(
            $watermark,
            $composite,
            $watermarkOptions['x_position'],
            $watermarkOptions['y_position']
        );

        return $image;
    }

    public static function watermarkFromFile($imageFile, $watermarkFile)
    {

    }

    public static function resize($image, $width, $height, $filter = \Imagick::FILTER_LANCZOS, $bestFit = true)
    {
        // If not existing Imagic resource
        if (is_string($image)) {
            $image = new \Imagick($image);
        }

        $image->resizeImage($width, $height, $filter, $bestFit);

        return $image;
    }

    public static function getThemeFont()
    {
        //$font = 'Material-Design-Iconic-Font.ttf';
        $font = 'Material-Design-Iconic-Font.ttf';
        return UsniAdaptor::getAlias('@approot') . '/themes/subas/assets/fonts/' . $font;
    }

    public static function testFonts($imagick = null, $outputImage = 'test-fonts.png')
    {
        // If not existing Imagic resource
        if ($imagick == null) {
            $imagick = new \Imagick();
        }

        $fonts = $imagick->queryFonts();
        $x = 10;
        $y = 10;

        foreach ($fonts as $font) {
            $opts = [
                'text' => 'Šie ir teksta glāzšķūņrūķi',
                'x_position' => $x,
                'y_position' => $y + 18,
                'opacity'    => '.9',
            ];
            $imagick = self::watermarkImageWithText($imagick, $opts);
        }

        $imagick->writeImage($outputImage);
    }

    public static function listAvailableFonts()
    {
        $fontList = \Imagick::queryFonts('*');
        foreach ( $fontList as $fontName ) {
            echo $fontName . PHP_EOL;
        }
    }

    public static function readFromContent($content)
    {
        $imagick = new \Imagick();

        $imagick->readImageBlob($content);

        return $imagick;
    }

    public static function writeFromContent($content, $file)
    {
        $imagick = self::readFromContent($content);

        $imagick->writeImage($file);

        return $imagick;
    }
}
