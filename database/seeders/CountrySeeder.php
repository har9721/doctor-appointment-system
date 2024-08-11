<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('countries')->delete();
 
        $countries = array(
            array(
                'id' => 1,
                'sortname' => 'US',
                'name' => 'United States'
            ),
            array(
                'id' => 2,
                'sortname' => 'CA',
                'name' => 'Canada'
            ),
            array(
                'id' => 3,
                'sortname' => 'AF',
                'name' => 'Afghanistan'
            ),
            array(
                'id' => 4,
                'sortname' => 'AL',
                'name' => 'Albania'
            ),
            array(
                'id' => 5,
                'sortname' => 'DZ',
                'name' => 'Algeria'
            ),
            array(
                'id' => 6,
                'sortname' => 'DS',
                'name' => 'American Samoa'
            ),
            array(
                'id' => 7,
                'sortname' => 'AD',
                'name' => 'Andorra'
            ),
            array(
                'id' => 8,
                'sortname' => 'AO',
                'name' => 'Angola'
            ),
            array(
                'id' => 9,
                'sortname' => 'AI',
                'name' => 'Anguilla'
            ),
            array(
                'id' => 10,
                'sortname' => 'AQ',
                'name' => 'Antarctica'
            ),
            array(
                'id' => 11,
                'sortname' => 'AG',
                'name' => 'Antigua and/or Barbuda'
            ),
            array(
                'id' => 12,
                'sortname' => 'AR',
                'name' => 'Argentina'
            ),
            array(
                'id' => 13,
                'sortname' => 'AM',
                'name' => 'Armenia'
            ),
            array(
                'id' => 14,
                'sortname' => 'AW',
                'name' => 'Aruba'
            ),
            array(
                'id' => 15,
                'sortname' => 'AU',
                'name' => 'Australia'
            ),
            array(
                'id' => 16,
                'sortname' => 'AT',
                'name' => 'Austria'
            ),
            array(
                'id' => 17,
                'sortname' => 'AZ',
                'name' => 'Azerbaijan'
            ),
            array(
                'id' => 18,
                'sortname' => 'BS',
                'name' => 'Bahamas'
            ),
            array(
                'id' => 19,
                'sortname' => 'BH',
                'name' => 'Bahrain'
            ),
            array(
                'id' => 20,
                'sortname' => 'BD',
                'name' => 'Bangladesh'
            ),
            array(
                'id' => 21,
                'sortname' => 'BB',
                'name' => 'Barbados'
            ),
            array(
                'id' => 22,
                'sortname' => 'BY',
                'name' => 'Belarus'
            ),
            array(
                'id' => 23,
                'sortname' => 'BE',
                'name' => 'Belgium'
            ),
            array(
                'id' => 24,
                'sortname' => 'BZ',
                'name' => 'Belize'
            ),
            array(
                'id' => 25,
                'sortname' => 'BJ',
                'name' => 'Benin'
            ),
            array(
                'id' => 26,
                'sortname' => 'BM',
                'name' => 'Bermuda'
            ),
            array(
                'id' => 27,
                'sortname' => 'BT',
                'name' => 'Bhutan'
            ),
            array(
                'id' => 28,
                'sortname' => 'BO',
                'name' => 'Bolivia'
            ),
            array(
                'id' => 29,
                'sortname' => 'BA',
                'name' => 'Bosnia and Herzegovina'
            ),
            array(
                'id' => 30,
                'sortname' => 'BW',
                'name' => 'Botswana'
            ),
            array(
                'id' => 31,
                'sortname' => 'BV',
                'name' => 'Bouvet Island'
            ),
            array(
                'id' => 32,
                'sortname' => 'BR',
                'name' => 'Brazil'
            ),
            array(
                'id' => 33,
                'sortname' => 'IO',
                'name' => 'British lndian Ocean Territory'
            ),
            array(
                'id' => 34,
                'sortname' => 'BN',
                'name' => 'Brunei Darussalam'
            ),
            array(
                'id' => 35,
                'sortname' => 'BG',
                'name' => 'Bulgaria'
            ),
            array(
                'id' => 36,
                'sortname' => 'BF',
                'name' => 'Burkina Faso'
            ),
            array(
                'id' => 37,
                'sortname' => 'BI',
                'name' => 'Burundi'
            ),
            array(
                'id' => 38,
                'sortname' => 'KH',
                'name' => 'Cambodia'
            ),
            array(
                'id' => 39,
                'sortname' => 'CM',
                'name' => 'Cameroon'
            ),
            array(
                'id' => 40,
                'sortname' => 'CV',
                'name' => 'Cape Verde'
            ),
            array(
                'id' => 41,
                'sortname' => 'KY',
                'name' => 'Cayman Islands'
            ),
            array(
                'id' => 42,
                'sortname' => 'CF',
                'name' => 'Central African Republic'
            ),
            array(
                'id' => 43,
                'sortname' => 'TD',
                'name' => 'Chad'
            ),
            array(
                'id' => 44,
                'sortname' => 'CL',
                'name' => 'Chile'
            ),
            array(
                'id' => 45,
                'sortname' => 'CN',
                'name' => 'China'
            ),
            array(
                'id' => 46,
                'sortname' => 'CX',
                'name' => 'Christmas Island'
            ),
            array(
                'id' => 47,
                'sortname' => 'CC',
                'name' => 'Cocos (Keeling) Islands'
            ),
            array(
                'id' => 48,
                'sortname' => 'CO',
                'name' => 'Colombia'
            ),
            array(
                'id' => 49,
                'sortname' => 'KM',
                'name' => 'Comoros'
            ),
            array(
                'id' => 50,
                'sortname' => 'CG',
                'name' => 'Congo'
            ),
            array(
                'id' => 51,
                'sortname' => 'CK',
                'name' => 'Cook Islands'
            ),
            array(
                'id' => 52,
                'sortname' => 'CR',
                'name' => 'Costa Rica'
            ),
            array(
                'id' => 53,
                'sortname' => 'HR',
                'name' => 'Croatia (Hrvatska)'
            ),
            array(
                'id' => 54,
                'sortname' => 'CU',
                'name' => 'Cuba'
            ),
            array(
                'id' => 55,
                'sortname' => 'CY',
                'name' => 'Cyprus'
            ),
            array(
                'id' => 56,
                'sortname' => 'CZ',
                'name' => 'Czech Republic'
            ),
            array(
                'id' => 57,
                'sortname' => 'DK',
                'name' => 'Denmark'
            ),
            array(
                'id' => 58,
                'sortname' => 'DJ',
                'name' => 'Djibouti'
            ),
            array(
                'id' => 59,
                'sortname' => 'DM',
                'name' => 'Dominica'
            ),
            array(
                'id' => 60,
                'sortname' => 'DO',
                'name' => 'Dominican Republic'
            ),
            array(
                'id' => 61,
                'sortname' => 'TP',
                'name' => 'East Timor'
            ),
            array(
                'id' => 62,
                'sortname' => 'EC',
                'name' => 'Ecudaor'
            ),
            array(
                'id' => 63,
                'sortname' => 'EG',
                'name' => 'Egypt'
            ),
            array(
                'id' => 64,
                'sortname' => 'SV',
                'name' => 'El Salvador'
            ),
            array(
                'id' => 65,
                'sortname' => 'GQ',
                'name' => 'Equatorial Guinea'
            ),
            array(
                'id' => 66,
                'sortname' => 'ER',
                'name' => 'Eritrea'
            ),
            array(
                'id' => 67,
                'sortname' => 'EE',
                'name' => 'Estonia'
            ),
            array(
                'id' => 68,
                'sortname' => 'ET',
                'name' => 'Ethiopia'
            ),
            array(
                'id' => 69,
                'sortname' => 'FK',
                'name' => 'Falkland Islands (Malvinas)'
            ),
            array(
                'id' => 70,
                'sortname' => 'FO',
                'name' => 'Faroe Islands'
            ),
            array(
                'id' => 71,
                'sortname' => 'FJ',
                'name' => 'Fiji'
            ),
            array(
                'id' => 72,
                'sortname' => 'FI',
                'name' => 'Finland'
            ),
            array(
                'id' => 73,
                'sortname' => 'FR',
                'name' => 'France'
            ),
            array(
                'id' => 74,
                'sortname' => 'FX',
                'name' => 'France, Metropolitan'
            ),
            array(
                'id' => 75,
                'sortname' => 'GF',
                'name' => 'French Guiana'
            ),
            array(
                'id' => 76,
                'sortname' => 'PF',
                'name' => 'French Polynesia'
            ),
            array(
                'id' => 77,
                'sortname' => 'TF',
                'name' => 'French Southern Territories'
            ),
            array(
                'id' => 78,
                'sortname' => 'GA',
                'name' => 'Gabon'
            ),
            array(
                'id' => 79,
                'sortname' => 'GM',
                'name' => 'Gambia'
            ),
            array(
                'id' => 80,
                'sortname' => 'GE',
                'name' => 'Georgia'
            ),
            array(
                'id' => 81,
                'sortname' => 'DE',
                'name' => 'Germany'
            ),
            array(
                'id' => 82,
                'sortname' => 'GH',
                'name' => 'Ghana'
            ),
            array(
                'id' => 83,
                'sortname' => 'GI',
                'name' => 'Gibraltar'
            ),
            array(
                'id' => 84,
                'sortname' => 'GR',
                'name' => 'Greece'
            ),
            array(
                'id' => 85,
                'sortname' => 'GL',
                'name' => 'Greenland'
            ),
            array(
                'id' => 86,
                'sortname' => 'GD',
                'name' => 'Grenada'
            ),
            array(
                'id' => 87,
                'sortname' => 'GP',
                'name' => 'Guadeloupe'
            ),
            array(
                'id' => 88,
                'sortname' => 'GU',
                'name' => 'Guam'
            ),
            array(
                'id' => 89,
                'sortname' => 'GT',
                'name' => 'Guatemala'
            ),
            array(
                'id' => 90,
                'sortname' => 'GN',
                'name' => 'Guinea'
            ),
            array(
                'id' => 91,
                'sortname' => 'GW',
                'name' => 'Guinea-Bissau'
            ),
            array(
                'id' => 92,
                'sortname' => 'GY',
                'name' => 'Guyana'
            ),
            array(
                'id' => 93,
                'sortname' => 'HT',
                'name' => 'Haiti'
            ),
            array(
                'id' => 94,
                'sortname' => 'HM',
                'name' => 'Heard and Mc Donald Islands'
            ),
            array(
                'id' => 95,
                'sortname' => 'HN',
                'name' => 'Honduras'
            ),
            array(
                'id' => 96,
                'sortname' => 'HK',
                'name' => 'Hong Kong'
            ),
            array(
                'id' => 97,
                'sortname' => 'HU',
                'name' => 'Hungary'
            ),
            array(
                'id' => 98,
                'sortname' => 'IS',
                'name' => 'Iceland'
            ),
            array(
                'id' => 99,
                'sortname' => 'IR',
                'name' => 'Iran (Islamic Republic of)'
            ),
            array(
                'id' => 100,
                'sortname' => 'ID',
                'name' => 'Indonesia'
            ),
            array(
                'id' => 101,
                'sortname' => 'IN',
                'name' => 'India'
            ),
            array(
                'id' => 102,
                'sortname' => 'IQ',
                'name' => 'Iraq'
            ),
            array(
                'id' => 103,
                'sortname' => 'IE',
                'name' => 'Ireland'
            ),
            array(
                'id' => 104,
                'sortname' => 'IL',
                'name' => 'Israel'
            ),
            array(
                'id' => 105,
                'sortname' => 'IT',
                'name' => 'Italy'
            ),
            array(
                'id' => 106,
                'sortname' => 'CI',
                'name' => 'Ivory Coast'
            ),
            array(
                'id' => 107,
                'sortname' => 'JM',
                'name' => 'Jamaica'
            ),
            array(
                'id' => 108,
                'sortname' => 'JP',
                'name' => 'Japan'
            ),
            array(
                'id' => 109,
                'sortname' => 'JO',
                'name' => 'Jordan'
            ),
            array(
                'id' => 110,
                'sortname' => 'KZ',
                'name' => 'Kazakhstan'
            ),
            array(
                'id' => 111,
                'sortname' => 'KE',
                'name' => 'Kenya'
            ),
            array(
                'id' => 112,
                'sortname' => 'KI',
                'name' => 'Kiribati'
            ),
            array(
                'id' => 113,
                'sortname' => 'KP',
                'name' => 'Korea, Democratic People\'s Republic of'
            ),
            array(
                'id' => 114,
                'sortname' => 'KR',
                'name' => 'Korea, Republic of'
            ),
            array(
                'id' => 115,
                'sortname' => 'KW',
                'name' => 'Kuwait'
            ),
            array(
                'id' => 116,
                'sortname' => 'KG',
                'name' => 'Kyrgyzstan'
            ),
            array(
                'id' => 117,
                'sortname' => 'LA',
                'name' => 'Lao People\'s Democratic Republic'
            ),
            array(
                'id' => 118,
                'sortname' => 'LV',
                'name' => 'Latvia'
            ),
            array(
                'id' => 119,
                'sortname' => 'LB',
                'name' => 'Lebanon'
            ),
            array(
                'id' => 120,
                'sortname' => 'LS',
                'name' => 'Lesotho'
            ),
            array(
                'id' => 121,
                'sortname' => 'LR',
                'name' => 'Liberia'
            ),
            array(
                'id' => 122,
                'sortname' => 'LY',
                'name' => 'Libyan Arab Jamahiriya'
            ),
            array(
                'id' => 123,
                'sortname' => 'LI',
                'name' => 'Liechtenstein'
            ),
            array(
                'id' => 124,
                'sortname' => 'LT',
                'name' => 'Lithuania'
            ),
            array(
                'id' => 125,
                'sortname' => 'LU',
                'name' => 'Luxembourg'
            ),
            array(
                'id' => 126,
                'sortname' => 'MO',
                'name' => 'Macau'
            ),
            array(
                'id' => 127,
                'sortname' => 'MK',
                'name' => 'Macedonia'
            ),
            array(
                'id' => 128,
                'sortname' => 'MG',
                'name' => 'Madagascar'
            ),
            array(
                'id' => 129,
                'sortname' => 'MW',
                'name' => 'Malawi'
            ),
            array(
                'id' => 130,
                'sortname' => 'MY',
                'name' => 'Malaysia'
            ),
            array(
                'id' => 131,
                'sortname' => 'MV',
                'name' => 'Maldives'
            ),
            array(
                'id' => 132,
                'sortname' => 'ML',
                'name' => 'Mali'
            ),
            array(
                'id' => 133,
                'sortname' => 'MT',
                'name' => 'Malta'
            ),
            array(
                'id' => 134,
                'sortname' => 'MH',
                'name' => 'Marshall Islands'
            ),
            array(
                'id' => 135,
                'sortname' => 'MQ',
                'name' => 'Martinique'
            ),
            array(
                'id' => 136,
                'sortname' => 'MR',
                'name' => 'Mauritania'
            ),
            array(
                'id' => 137,
                'sortname' => 'MU',
                'name' => 'Mauritius'
            ),
            array(
                'id' => 138,
                'sortname' => 'TY',
                'name' => 'Mayotte'
            ),
            array(
                'id' => 139,
                'sortname' => 'MX',
                'name' => 'Mexico'
            ),
            array(
                'id' => 140,
                'sortname' => 'FM',
                'name' => 'Micronesia, Federated States of'
            ),
            array(
                'id' => 141,
                'sortname' => 'MD',
                'name' => 'Moldova, Republic of'
            ),
            array(
                'id' => 142,
                'sortname' => 'MC',
                'name' => 'Monaco'
            ),
            array(
                'id' => 143,
                'sortname' => 'MN',
                'name' => 'Mongolia'
            ),
            array(
                'id' => 144,
                'sortname' => 'MS',
                'name' => 'Montserrat'
            ),
            array(
                'id' => 145,
                'sortname' => 'MA',
                'name' => 'Morocco'
            ),
            array(
                'id' => 146,
                'sortname' => 'MZ',
                'name' => 'Mozambique'
            ),
            array(
                'id' => 147,
                'sortname' => 'MM',
                'name' => 'Myanmar'
            ),
            array(
                'id' => 148,
                'sortname' => 'NA',
                'name' => 'Namibia'
            ),
            array(
                'id' => 149,
                'sortname' => 'NR',
                'name' => 'Nauru'
            ),
            array(
                'id' => 150,
                'sortname' => 'NP',
                'name' => 'Nepal'
            ),
            array(
                'id' => 151,
                'sortname' => 'NL',
                'name' => 'Netherlands'
            ),
            array(
                'id' => 152,
                'sortname' => 'AN',
                'name' => 'Netherlands Antilles'
            ),
            array(
                'id' => 153,
                'sortname' => 'NC',
                'name' => 'New Caledonia'
            ),
            array(
                'id' => 154,
                'sortname' => 'NZ',
                'name' => 'New Zealand'
            ),
            array(
                'id' => 155,
                'sortname' => 'NI',
                'name' => 'Nicaragua'
            ),
            array(
                'id' => 156,
                'sortname' => 'NE',
                'name' => 'Niger'
            ),
            array(
                'id' => 157,
                'sortname' => 'NG',
                'name' => 'Nigeria'
            ),
            array(
                'id' => 158,
                'sortname' => 'NU',
                'name' => 'Niue'
            ),
            array(
                'id' => 159,
                'sortname' => 'NF',
                'name' => 'Norfork Island'
            ),
            array(
                'id' => 160,
                'sortname' => 'MP',
                'name' => 'Northern Mariana Islands'
            ),
            array(
                'id' => 161,
                'sortname' => 'NO',
                'name' => 'Norway'
            ),
            array(
                'id' => 162,
                'sortname' => 'OM',
                'name' => 'Oman'
            ),
            array(
                'id' => 163,
                'sortname' => 'PK',
                'name' => 'Pakistan'
            ),
            array(
                'id' => 164,
                'sortname' => 'PW',
                'name' => 'Palau'
            ),
            array(
                'id' => 165,
                'sortname' => 'PA',
                'name' => 'Panama'
            ),
            array(
                'id' => 166,
                'sortname' => 'PG',
                'name' => 'Papua New Guinea'
            ),
            array(
                'id' => 167,
                'sortname' => 'PY',
                'name' => 'Paraguay'
            ),
            array(
                'id' => 168,
                'sortname' => 'PE',
                'name' => 'Peru'
            ),
            array(
                'id' => 169,
                'sortname' => 'PH',
                'name' => 'Philippines'
            ),
            array(
                'id' => 170,
                'sortname' => 'PN',
                'name' => 'Pitcairn'
            ),
            array(
                'id' => 171,
                'sortname' => 'PL',
                'name' => 'Poland'
            ),
            array(
                'id' => 172,
                'sortname' => 'PT',
                'name' => 'Portugal'
            ),
            array(
                'id' => 173,
                'sortname' => 'PR',
                'name' => 'Puerto Rico'
            ),
            array(
                'id' => 174,
                'sortname' => 'QA',
                'name' => 'Qatar'
            ),
            array(
                'id' => 175,
                'sortname' => 'RE',
                'name' => 'Reunion'
            ),
            array(
                'id' => 176,
                'sortname' => 'RO',
                'name' => 'Romania'
            ),
            array(
                'id' => 177,
                'sortname' => 'RU',
                'name' => 'Russian Federation'
            ),
            array(
                'id' => 178,
                'sortname' => 'RW',
                'name' => 'Rwanda'
            ),
            array(
                'id' => 179,
                'sortname' => 'KN',
                'name' => 'Saint Kitts and Nevis'
            ),
            array(
                'id' => 180,
                'sortname' => 'LC',
                'name' => 'Saint Lucia'
            ),
            array(
                'id' => 181,
                'sortname' => 'VC',
                'name' => 'Saint Vincent and the Grenadines'
            ),
            array(
                'id' => 182,
                'sortname' => 'WS',
                'name' => 'Samoa'
            ),
            array(
                'id' => 183,
                'sortname' => 'SM',
                'name' => 'San Marino'
            ),
            array(
                'id' => 184,
                'sortname' => 'ST',
                'name' => 'Sao Tome and Principe'
            ),
            array(
                'id' => 185,
                'sortname' => 'SA',
                'name' => 'Saudi Arabia'
            ),
            array(
                'id' => 186,
                'sortname' => 'SN',
                'name' => 'Senegal'
            ),
            array(
                'id' => 187,
                'sortname' => 'SC',
                'name' => 'Seychelles'
            ),
            array(
                'id' => 188,
                'sortname' => 'SL',
                'name' => 'Sierra Leone'
            ),
            array(
                'id' => 189,
                'sortname' => 'SG',
                'name' => 'Singapore'
            ),
            array(
                'id' => 190,
                'sortname' => 'SK',
                'name' => 'Slovakia'
            ),
            array(
                'id' => 191,
                'sortname' => 'SI',
                'name' => 'Slovenia'
            ),
            array(
                'id' => 192,
                'sortname' => 'SB',
                'name' => 'Solomon Islands'
            ),
            array(
                'id' => 193,
                'sortname' => 'SO',
                'name' => 'Somalia'
            ),
            array(
                'id' => 194,
                'sortname' => 'ZA',
                'name' => 'South Africa'
            ),
            array(
                'id' => 195,
                'sortname' => 'GS',
                'name' => 'South Georgia South Sandwich Islands'
            ),
            array(
                'id' => 196,
                'sortname' => 'ES',
                'name' => 'Spain'
            ),
            array(
                'id' => 197,
                'sortname' => 'LK',
                'name' => 'Sri Lanka'
            ),
            array(
                'id' => 198,
                'sortname' => 'SH',
                'name' => 'St. Helena'
            ),
            array(
                'id' => 199,
                'sortname' => 'PM',
                'name' => 'St. Pierre and Miquelon'
            ),
            array(
                'id' => 200,
                'sortname' => 'SD',
                'name' => 'Sudan'
            ),
            array(
                'id' => 201,
                'sortname' => 'SR',
                'name' => 'Suriname'
            ),
            array(
                'id' => 202,
                'sortname' => 'SJ',
                'name' => 'Svalbarn and Jan Mayen Islands'
            ),
            array(
                'id' => 203,
                'sortname' => 'SZ',
                'name' => 'Swaziland'
            ),
            array(
                'id' => 204,
                'sortname' => 'SE',
                'name' => 'Sweden'
            ),
            array(
                'id' => 205,
                'sortname' => 'CH',
                'name' => 'Switzerland'
            ),
            array(
                'id' => 206,
                'sortname' => 'SY',
                'name' => 'Syrian Arab Republic'
            ),
            array(
                'id' => 207,
                'sortname' => 'TW',
                'name' => 'Taiwan'
            ),
            array(
                'id' => 208,
                'sortname' => 'TJ',
                'name' => 'Tajikistan'
            ),
            array(
                'id' => 209,
                'sortname' => 'TZ',
                'name' => 'Tanzania, United Republic of'
            ),
            array(
                'id' => 210,
                'sortname' => 'TH',
                'name' => 'Thailand'
            ),
            array(
                'id' => 211,
                'sortname' => 'TG',
                'name' => 'Togo'
            ),
            array(
                'id' => 212,
                'sortname' => 'TK',
                'name' => 'Tokelau'
            ),
            array(
                'id' => 213,
                'sortname' => 'TO',
                'name' => 'Tonga'
            ),
            array(
                'id' => 214,
                'sortname' => 'TT',
                'name' => 'Trinidad and Tobago'
            ),
            array(
                'id' => 215,
                'sortname' => 'TN',
                'name' => 'Tunisia'
            ),
            array(
                'id' => 216,
                'sortname' => 'TR',
                'name' => 'Turkey'
            ),
            array(
                'id' => 217,
                'sortname' => 'TM',
                'name' => 'Turkmenistan'
            ),
            array(
                'id' => 218,
                'sortname' => 'TC',
                'name' => 'Turks and Caicos Islands'
            ),
            array(
                'id' => 219,
                'sortname' => 'TV',
                'name' => 'Tuvalu'
            ),
            array(
                'id' => 220,
                'sortname' => 'UG',
                'name' => 'Uganda'
            ),
            array(
                'id' => 221,
                'sortname' => 'UA',
                'name' => 'Ukraine'
            ),
            array(
                'id' => 222,
                'sortname' => 'AE',
                'name' => 'United Arab Emirates'
            ),
            array(
                'id' => 223,
                'sortname' => 'GB',
                'name' => 'United Kingdom'
            ),
            array(
                'id' => 224,
                'sortname' => 'UM',
                'name' => 'United States minor outlying islands'
            ),
            array(
                'id' => 225,
                'sortname' => 'UY',
                'name' => 'Uruguay'
            ),
            array(
                'id' => 226,
                'sortname' => 'UZ',
                'name' => 'Uzbekistan'
            ),
            array(
                'id' => 227,
                'sortname' => 'VU',
                'name' => 'Vanuatu'
            ),
            array(
                'id' => 228,
                'sortname' => 'VA',
                'name' => 'Vatican City State'
            ),
            array(
                'id' => 229,
                'sortname' => 'VE',
                'name' => 'Venezuela'
            ),
            array(
                'id' => 230,
                'sortname' => 'VN',
                'name' => 'Vietnam'
            ),
            array(
                'id' => 231,
                'sortname' => 'VG',
                'name' => 'Virigan Islands (British)'
            ),
            array(
                'id' => 232,
                'sortname' => 'VI',
                'name' => 'Virgin Islands (U.S.)'
            ),
            array(
                'id' => 233,
                'sortname' => 'WF',
                'name' => 'Wallis and Futuna Islands'
            ),
            array(
                'id' => 234,
                'sortname' => 'EH',
                'name' => 'Western Sahara'
            ),
            array(
                'id' => 235,
                'sortname' => 'YE',
                'name' => 'Yemen'
            ),
            array(
                'id' => 236,
                'sortname' => 'YU',
                'name' => 'Yugoslavia'
            ),
            array(
                'id' => 237,
                'sortname' => 'ZR',
                'name' => 'Zaire'
            ),
            array(
                'id' => 238,
                'sortname' => 'ZM',
                'name' => 'Zambia'
            ),
            array(
                'id' => 239,
                'sortname' => 'ZW',
                'name' => 'Zimbabwe'
            )
        );
 
        \DB::table('countries')->insert($countries);
    }
}