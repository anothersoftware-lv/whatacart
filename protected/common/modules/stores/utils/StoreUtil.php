<?php
namespace common\modules\stores\utils;

use usni\UsniAdaptor;

class StoreUtil
{
    public static function getSupportConfig()
    {
        return UsniAdaptor::app()->storeManager->getConfigurationByCode('support', 'storeconfig');
    }

    public static function getCompanyConfig()
    {
        return UsniAdaptor::app()->storeManager->getConfigurationByCode('company', 'storeconfig');
    }

    private static function getOfficeConfig()
    {
        return UsniAdaptor::app()->storeManager->getConfigurationByCode('office', 'storeconfig');
    }

    public static function getSupportPhone()
    {
        $support = self::getSupportConfig();

        return isset($support['phone']) ? $support['phone'] : '';
    }

    public static function getSupportEmail()
    {
        $support = self::getSupportConfig();

        return isset($support['email']) ? $support['email'] : '';
    }

    public static function getSupportSkypeName()
    {
        $support = self::getSupportConfig();

        return isset($support['skype_name']) ? $support['skype_name'] : '';
    }

    public static function getSupportWhatsappNumber()
    {
        $support = self::getSupportConfig();

        return isset($support['whatsapp_number']) ? $support['whatsapp_number'] : '';
    }

    public static function getCompanyName()
    {
        $company = self::getCompanyConfig();

        return isset($company['name']) ? $company['name'] : '';
    }

    public static function getCompanyAddress()
    {
        $company = self::getCompanyConfig();

        return isset($company['address']) ? $company['address'] : '';
    }

    public static function getCompanyRegistrationNumber()
    {
        $company = self::getCompanyConfig();

        return isset($company['registration_number']) ? $company['registration_number'] : '';
    }

    public static function getCompanyVatNumber()
    {
        $company = self::getCompanyConfig();

        return isset($company['vat_number']) ? $company['vat_number'] : '';
    }

    public static function getCompanyBankIban()
    {
        $company = self::getCompanyConfig();

        return isset($company['bank_iban']) ? $company['bank_iban'] : '';
    }

    public static function getCompanyBankName()
    {
        $company = self::getCompanyConfig();

        return isset($company['bank_name']) ? $company['bank_name'] : '';
    }

    public static function getCompanyBankSwiftCode()
    {
        $company = self::getCompanyConfig();

        return isset($company['bank_swift_code']) ? $company['bank_swift_code'] : '';
    }

    public static function getCompanyPhone()
    {
        $company = self::getCompanyConfig();

        return isset($company['phone']) ? $company['phone'] : '';
    }

    public static function getCompanyEmail()
    {
        $company = self::getCompanyConfig();

        return isset($company['email']) ? $company['email'] : '';
    }

    public static function getOfficeAddress()
    {
        $company = self::getOfficeConfig();

        return isset($company['address']) ? $company['address'] : '';
    }

    public static function getOfficeGpsLatitude()
    {
        $company = self::getOfficeConfig();

        return isset($company['gps_latitude']) ? $company['gps_latitude'] : '';
    }

    public static function getOfficeGpsLongitude()
    {
        $company = self::getOfficeConfig();

        return isset($company['gps_longitude']) ? $company['gps_longitude'] : '';
    }

    public static function getOfficeWorkingTimes()
    {
        $company = self::getOfficeConfig();

        return isset($company['working_times']) ? $company['working_times'] : '';
    }

    public static function getCompanyDetailsContent($asList = false, $lineWrapper = [])
    {
        $data = [
            'Company Name'                => self::getCompanyName(),
            'Company Address'             => self::getCompanyAddress(),
            'Company Registration Number' => self::getCompanyRegistrationNumber(),
            'Company Vat Number'          => self::getCompanyVatNumber(),
            'Company Bank Name'           => self::getCompanyBankName(),
            'Company Bank IBAN'           => self::getCompanyBankIban(),
            'Company Bank SWIFT Code'     => self::getCompanyBankSwiftCode(),
            'Company Phone'               => self::getCompanyPhone(),
            'Company Email'               => self::getCompanyEmail(),
        ];

        $content = '';

        foreach ($data as $name => $value) {
            if(!empty($value)) {
                if($asList) {
                    $startTag = '<li>';
                    $endTag = '</li>';
                } else {
                    $startTag = isset($lineWrapper['tag']) ? '<' . $lineWrapper['tag'] . '>' : '';
                    $endTag = isset($lineWrapper['tag']) ? '</' . $lineWrapper['tag'] . '>' : '';
                }
                $newline = ! $asList ? '<br/>' : '';

                $name = UsniAdaptor::t('application', $name);

                if(isset($lineWrapper['name_wrapper_tag'])) {
                    $name = '<' . $lineWrapper['name_wrapper_tag'] . '>'
                        . $name . '</' . $lineWrapper['name_wrapper_tag'] . '>';
                }

                if(!empty($name)) {
                    $name .= ': ';
                }

                $content .= $startTag . $name  . $value . $endTag . $newline;
            }
        }

        return $content;
    }

    public static function getCompanyAddressContent()
    {

    }
}