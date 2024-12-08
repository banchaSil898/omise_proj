<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property int $id
 * @property string $country_code
 * @property string $country_name
 * @property string $currency_code
 * @property string $fips_code
 * @property string $iso_numeric
 * @property string $north
 * @property string $south
 * @property string $east
 * @property string $west
 * @property string $capital
 * @property string $continent_name
 * @property string $continent
 * @property string $languages
 * @property string $iso_alpha3
 * @property int $geoname_id
 * @property string $shipping_fee
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geoname_id'], 'integer'],
            [['shipping_fee'], 'number'],
            [['country_code', 'fips_code', 'continent'], 'string', 'max' => 2],
            [['country_name'], 'string', 'max' => 45],
            [['currency_code', 'iso_alpha3'], 'string', 'max' => 3],
            [['iso_numeric'], 'string', 'max' => 4],
            [['north', 'south', 'east', 'west', 'capital'], 'string', 'max' => 30],
            [['continent_name'], 'string', 'max' => 15],
            [['languages'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_code' => 'Country Code',
            'country_name' => 'Country Name',
            'currency_code' => 'Currency Code',
            'fips_code' => 'Fips Code',
            'iso_numeric' => 'Iso Numeric',
            'north' => 'North',
            'south' => 'South',
            'east' => 'East',
            'west' => 'West',
            'capital' => 'Capital',
            'continent_name' => 'Continent Name',
            'continent' => 'Continent',
            'languages' => 'Languages',
            'iso_alpha3' => 'Iso Alpha3',
            'geoname_id' => 'Geoname ID',
            'shipping_fee' => 'Shipping Fee',
        ];
    }
}
