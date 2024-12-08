<?php

use app\modules\admin\assets\AppAsset;
use codesk\components\Html;
use yiister\gentelella\widgets\Panel;

$bundle = AppAsset::register($this);
?>
<?php
Panel::begin([
    'header' => 'รายงานกิจกรรมการเกษตร',
    'icon' => 'file',
]);
?>
<div class="text-center">
    <?= Html::img($bundle->baseUrl . '/images/report1.png', ['width' => '100%']); ?>
</div>
<table class="table table-condensed table-bordered font-sm stick sticky-enabled" border="1" style="margin: 0px; width: 100%;">
    <thead>
        <tr class="bg-primary">
            <th rowspan="2" class="text-center">ลำดับ</th>
            <th rowspan="2" class="text-center">พื้นที่/แมลง</th>
            <th class="text-center" colspan="3">
                จำนวนครัวเรือนเกษตรกร                    </th>
        </tr>
        <tr class="bg-primary">
            <th class="text-center">
                2558                            </th>
            <th class="text-center">
                2559                            </th>
            <th class="text-center">
                2560                            </th>
        </tr>
    </thead>
    <tbody>
        <tr class="bg-info">
            <td colspan="2" class="text-bold text-center">รวมทั้งหมด</td>
            <td class="text-right">
                5,062,499.00                                                            </td>
            <td class="text-right">
                4,942,794.00                                                            </td>
            <td class="text-right">
                4,870,439.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">1</td>
            <td style="padding-left:5px;">กระบี่</td>
            <td class="text-right">
                100.00                                                            </td>
            <td class="text-right">
                179.00                                                            </td>
            <td class="text-right">
                19.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">2</td>
            <td style="padding-left:5px;">กรุงเทพมหานคร</td>
            <td class="text-right">
                2,912.00                                                            </td>
            <td class="text-right">
                3,141.00                                                            </td>
            <td class="text-right">
                3,010.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">3</td>
            <td style="padding-left:5px;">กาญจนบุรี</td>
            <td class="text-right">
                21,447.00                                                            </td>
            <td class="text-right">
                24,254.00                                                            </td>
            <td class="text-right">
                24,311.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">4</td>
            <td style="padding-left:5px;">กาฬสินธุ์</td>
            <td class="text-right">
                162,491.00                                                            </td>
            <td class="text-right">
                154,128.00                                                            </td>
            <td class="text-right">
                141,860.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">5</td>
            <td style="padding-left:5px;">กำแพงเพชร</td>
            <td class="text-right">
                50,272.00                                                            </td>
            <td class="text-right">
                55,800.00                                                            </td>
            <td class="text-right">
                47,408.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">6</td>
            <td style="padding-left:5px;">ขอนแก่น</td>
            <td class="text-right">
                280,381.00                                                            </td>
            <td class="text-right">
                245,892.00                                                            </td>
            <td class="text-right">
                251,471.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">7</td>
            <td style="padding-left:5px;">จันทบุรี</td>
            <td class="text-right">
                1,165.00                                                            </td>
            <td class="text-right">
                1,468.00                                                            </td>
            <td class="text-right">
                1,201.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">8</td>
            <td style="padding-left:5px;">ฉะเชิงเทรา</td>
            <td class="text-right">
                23,317.00                                                            </td>
            <td class="text-right">
                27,520.00                                                            </td>
            <td class="text-right">
                56,195.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">9</td>
            <td style="padding-left:5px;">ชลบุรี</td>
            <td class="text-right">
                3,312.00                                                            </td>
            <td class="text-right">
                3,476.00                                                            </td>
            <td class="text-right">
                2,600.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">10</td>
            <td style="padding-left:5px;">ชัยนาท</td>
            <td class="text-right">
                34,094.00                                                            </td>
            <td class="text-right">
                30,340.00                                                            </td>
            <td class="text-right">
                33,299.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">11</td>
            <td style="padding-left:5px;">ชัยภูมิ</td>
            <td class="text-right">
                181,306.00                                                            </td>
            <td class="text-right">
                202,682.00                                                            </td>
            <td class="text-right">
                177,040.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">12</td>
            <td style="padding-left:5px;">ชุมพร</td>
            <td class="text-right">
                542.00                                                            </td>
            <td class="text-right">
                577.00                                                            </td>
            <td class="text-right">
                334.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">13</td>
            <td style="padding-left:5px;">เชียงราย</td>
            <td class="text-right">
                121,453.00                                                            </td>
            <td class="text-right">
                124,828.00                                                            </td>
            <td class="text-right">
                110,223.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">14</td>
            <td style="padding-left:5px;">เชียงใหม่</td>
            <td class="text-right">
                63,981.00                                                            </td>
            <td class="text-right">
                64,170.00                                                            </td>
            <td class="text-right">
                68,189.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">15</td>
            <td style="padding-left:5px;">ตรัง</td>
            <td class="text-right">
                2,185.00                                                            </td>
            <td class="text-right">
                2,484.00                                                            </td>
            <td class="text-right">
                131.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">16</td>
            <td style="padding-left:5px;">ตราด</td>
            <td class="text-right">
                925.00                                                            </td>
            <td class="text-right">
                879.00                                                            </td>
            <td class="text-right">
                1,759.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">17</td>
            <td style="padding-left:5px;">ตาก</td>
            <td class="text-right">
                22,942.00                                                            </td>
            <td class="text-right">
                22,243.00                                                            </td>
            <td class="text-right">
                27,292.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">18</td>
            <td style="padding-left:5px;">นครนายก</td>
            <td class="text-right">
                8,098.00                                                            </td>
            <td class="text-right">
                9,058.00                                                            </td>
            <td class="text-right">
                11,696.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">19</td>
            <td style="padding-left:5px;">นครปฐม</td>
            <td class="text-right">
                17,129.00                                                            </td>
            <td class="text-right">
                12,058.00                                                            </td>
            <td class="text-right">
                10,466.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">20</td>
            <td style="padding-left:5px;">นครพนม</td>
            <td class="text-right">
                178,450.00                                                            </td>
            <td class="text-right">
                175,546.00                                                            </td>
            <td class="text-right">
                136,936.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">21</td>
            <td style="padding-left:5px;">นครราชสีมา</td>
            <td class="text-right">
                242,904.00                                                            </td>
            <td class="text-right">
                211,929.00                                                            </td>
            <td class="text-right">
                234,824.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">22</td>
            <td style="padding-left:5px;">นครศรีธรรมราช</td>
            <td class="text-right">
                11,831.00                                                            </td>
            <td class="text-right">
                9,898.00                                                            </td>
            <td class="text-right">
                11,484.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">23</td>
            <td style="padding-left:5px;">นครสวรรค์</td>
            <td class="text-right">
                67,445.00                                                            </td>
            <td class="text-right">
                96,842.00                                                            </td>
            <td class="text-right">
                74,452.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">24</td>
            <td style="padding-left:5px;">นนทบุรี</td>
            <td class="text-right">
                5,081.00                                                            </td>
            <td class="text-right">
                3,000.00                                                            </td>
            <td class="text-right">
                3,458.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">25</td>
            <td style="padding-left:5px;">นราธิวาส</td>
            <td class="text-right">
                7,086.00                                                            </td>
            <td class="text-right">
                7,079.00                                                            </td>
            <td class="text-right">
                1,621.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">26</td>
            <td style="padding-left:5px;">น่าน</td>
            <td class="text-right">
                41,778.00                                                            </td>
            <td class="text-right">
                21,059.00                                                            </td>
            <td class="text-right">
                11,262.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">27</td>
            <td style="padding-left:5px;">บึงกาฬ</td>
            <td class="text-right">
                55,243.00                                                            </td>
            <td class="text-right">
                51,289.00                                                            </td>
            <td class="text-right">
                49,059.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">28</td>
            <td style="padding-left:5px;">บุรีรัมย์</td>
            <td class="text-right">
                199,655.00                                                            </td>
            <td class="text-right">
                202,211.00                                                            </td>
            <td class="text-right">
                209,749.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">29</td>
            <td style="padding-left:5px;">ปทุมธานี</td>
            <td class="text-right">
                11,440.00                                                            </td>
            <td class="text-right">
                11,034.00                                                            </td>
            <td class="text-right">
                10,778.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">30</td>
            <td style="padding-left:5px;">ประจวบคีรีขันธ์</td>
            <td class="text-right">
                1,489.00                                                            </td>
            <td class="text-right">
                713.00                                                            </td>
            <td class="text-right">
                742.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">31</td>
            <td style="padding-left:5px;">ปราจีนบุรี</td>
            <td class="text-right">
                15,461.00                                                            </td>
            <td class="text-right">
                14,273.00                                                            </td>
            <td class="text-right">
                15,839.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">32</td>
            <td style="padding-left:5px;">ปัตตานี</td>
            <td class="text-right">
                6,146.00                                                            </td>
            <td class="text-right">
                820.00                                                            </td>
            <td class="text-right">
                0.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">33</td>
            <td style="padding-left:5px;">พระนครศรีอยุธยา</td>
            <td class="text-right">
                27,041.00                                                            </td>
            <td class="text-right">
                22,204.00                                                            </td>
            <td class="text-right">
                28,409.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">34</td>
            <td style="padding-left:5px;">พะเยา</td>
            <td class="text-right">
                68,584.00                                                            </td>
            <td class="text-right">
                66,195.00                                                            </td>
            <td class="text-right">
                70,414.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">35</td>
            <td style="padding-left:5px;">พังงา</td>
            <td class="text-right">
                274.00                                                            </td>
            <td class="text-right">
                268.00                                                            </td>
            <td class="text-right">
                253.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">36</td>
            <td style="padding-left:5px;">พัทลุง</td>
            <td class="text-right">
                19,016.00                                                            </td>
            <td class="text-right">
                15,338.00                                                            </td>
            <td class="text-right">
                10,405.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">37</td>
            <td style="padding-left:5px;">พิจิตร</td>
            <td class="text-right">
                53,899.00                                                            </td>
            <td class="text-right">
                57,802.00                                                            </td>
            <td class="text-right">
                56,085.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">38</td>
            <td style="padding-left:5px;">พิษณุโลก</td>
            <td class="text-right">
                75,942.00                                                            </td>
            <td class="text-right">
                82,867.00                                                            </td>
            <td class="text-right">
                80,516.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">39</td>
            <td style="padding-left:5px;">เพชรบุรี</td>
            <td class="text-right">
                17,216.00                                                            </td>
            <td class="text-right">
                12,776.00                                                            </td>
            <td class="text-right">
                18,932.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">40</td>
            <td style="padding-left:5px;">เพชรบูรณ์</td>
            <td class="text-right">
                60,843.00                                                            </td>
            <td class="text-right">
                58,779.00                                                            </td>
            <td class="text-right">
                115,222.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">41</td>
            <td style="padding-left:5px;">แพร่</td>
            <td class="text-right">
                44,222.00                                                            </td>
            <td class="text-right">
                48,716.00                                                            </td>
            <td class="text-right">
                48,039.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">42</td>
            <td style="padding-left:5px;">ภูเก็ต</td>
            <td class="text-right">
                1.00                                                            </td>
            <td class="text-right">
                1.00                                                            </td>
            <td class="text-right">
                16.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">43</td>
            <td style="padding-left:5px;">มหาสารคาม</td>
            <td class="text-right">
                241,524.00                                                            </td>
            <td class="text-right">
                225,565.00                                                            </td>
            <td class="text-right">
                254,009.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">44</td>
            <td style="padding-left:5px;">มุกดาหาร</td>
            <td class="text-right">
                72,134.00                                                            </td>
            <td class="text-right">
                67,428.00                                                            </td>
            <td class="text-right">
                70,502.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">45</td>
            <td style="padding-left:5px;">แม่ฮ่องสอน</td>
            <td class="text-right">
                13,189.00                                                            </td>
            <td class="text-right">
                14,708.00                                                            </td>
            <td class="text-right">
                42,263.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">46</td>
            <td style="padding-left:5px;">ยโสธร</td>
            <td class="text-right">
                141,556.00                                                            </td>
            <td class="text-right">
                245,805.00                                                            </td>
            <td class="text-right">
                127,512.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">47</td>
            <td style="padding-left:5px;">ยะลา</td>
            <td class="text-right">
                2,531.00                                                            </td>
            <td class="text-right">
                1,559.00                                                            </td>
            <td class="text-right">
                3,677.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">48</td>
            <td style="padding-left:5px;">ร้อยเอ็ด</td>
            <td class="text-right">
                288,024.00                                                            </td>
            <td class="text-right">
                261,284.00                                                            </td>
            <td class="text-right">
                263,571.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">49</td>
            <td style="padding-left:5px;">ระนอง</td>
            <td class="text-right">
                0.00                                                            </td>
            <td class="text-right">
                28.00                                                            </td>
            <td class="text-right">
                0.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">50</td>
            <td style="padding-left:5px;">ระยอง</td>
            <td class="text-right">
                859.00                                                            </td>
            <td class="text-right">
                868.00                                                            </td>
            <td class="text-right">
                993.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">51</td>
            <td style="padding-left:5px;">ราชบุรี</td>
            <td class="text-right">
                14,562.00                                                            </td>
            <td class="text-right">
                13,380.00                                                            </td>
            <td class="text-right">
                13,998.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">52</td>
            <td style="padding-left:5px;">ลพบุรี</td>
            <td class="text-right">
                23,699.00                                                            </td>
            <td class="text-right">
                25,355.00                                                            </td>
            <td class="text-right">
                24,897.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">53</td>
            <td style="padding-left:5px;">ลำปาง</td>
            <td class="text-right">
                66,007.00                                                            </td>
            <td class="text-right">
                75,433.00                                                            </td>
            <td class="text-right">
                102,298.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">54</td>
            <td style="padding-left:5px;">ลำพูน</td>
            <td class="text-right">
                18,107.00                                                            </td>
            <td class="text-right">
                20,735.00                                                            </td>
            <td class="text-right">
                20,448.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">55</td>
            <td style="padding-left:5px;">เลย</td>
            <td class="text-right">
                43,682.00                                                            </td>
            <td class="text-right">
                54,534.00                                                            </td>
            <td class="text-right">
                70,835.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">56</td>
            <td style="padding-left:5px;">ศรีสะเกษ</td>
            <td class="text-right">
                206,597.00                                                            </td>
            <td class="text-right">
                223,556.00                                                            </td>
            <td class="text-right">
                234,106.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">57</td>
            <td style="padding-left:5px;">สกลนคร</td>
            <td class="text-right">
                251,337.00                                                            </td>
            <td class="text-right">
                204,934.00                                                            </td>
            <td class="text-right">
                178,567.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">58</td>
            <td style="padding-left:5px;">สงขลา</td>
            <td class="text-right">
                22,392.00                                                            </td>
            <td class="text-right">
                20,282.00                                                            </td>
            <td class="text-right">
                11,842.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">59</td>
            <td style="padding-left:5px;">สตูล</td>
            <td class="text-right">
                10,252.00                                                            </td>
            <td class="text-right">
                4,213.00                                                            </td>
            <td class="text-right">
                5,197.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">60</td>
            <td style="padding-left:5px;">สมุทรปราการ</td>
            <td class="text-right">
                1,006.00                                                            </td>
            <td class="text-right">
                890.00                                                            </td>
            <td class="text-right">
                1,013.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">61</td>
            <td style="padding-left:5px;">สมุทรสงคราม</td>
            <td class="text-right">
                183.00                                                            </td>
            <td class="text-right">
                136.00                                                            </td>
            <td class="text-right">
                142.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">62</td>
            <td style="padding-left:5px;">สมุทรสาคร</td>
            <td class="text-right">
                250.00                                                            </td>
            <td class="text-right">
                290.00                                                            </td>
            <td class="text-right">
                212.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">63</td>
            <td style="padding-left:5px;">สระแก้ว</td>
            <td class="text-right">
                36,122.00                                                            </td>
            <td class="text-right">
                36,992.00                                                            </td>
            <td class="text-right">
                31,511.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">64</td>
            <td style="padding-left:5px;">สระบุรี</td>
            <td class="text-right">
                11,293.00                                                            </td>
            <td class="text-right">
                12,349.00                                                            </td>
            <td class="text-right">
                11,483.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">65</td>
            <td style="padding-left:5px;">สิงห์บุรี</td>
            <td class="text-right">
                12,239.00                                                            </td>
            <td class="text-right">
                12,815.00                                                            </td>
            <td class="text-right">
                13,110.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">66</td>
            <td style="padding-left:5px;">สุโขทัย</td>
            <td class="text-right">
                64,102.00                                                            </td>
            <td class="text-right">
                65,940.00                                                            </td>
            <td class="text-right">
                65,395.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">67</td>
            <td style="padding-left:5px;">สุพรรณบุรี</td>
            <td class="text-right">
                87,462.00                                                            </td>
            <td class="text-right">
                45,157.00                                                            </td>
            <td class="text-right">
                49,584.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">68</td>
            <td style="padding-left:5px;">สุราษฎร์ธานี</td>
            <td class="text-right">
                498.00                                                            </td>
            <td class="text-right">
                390.00                                                            </td>
            <td class="text-right">
                325.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">69</td>
            <td style="padding-left:5px;">สุรินทร์</td>
            <td class="text-right">
                247,496.00                                                            </td>
            <td class="text-right">
                195,779.00                                                            </td>
            <td class="text-right">
                178,955.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">70</td>
            <td style="padding-left:5px;">หนองคาย</td>
            <td class="text-right">
                48,903.00                                                            </td>
            <td class="text-right">
                47,132.00                                                            </td>
            <td class="text-right">
                99,367.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">71</td>
            <td style="padding-left:5px;">หนองบัวลำภู</td>
            <td class="text-right">
                86,218.00                                                            </td>
            <td class="text-right">
                75,899.00                                                            </td>
            <td class="text-right">
                71,161.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">72</td>
            <td style="padding-left:5px;">อ่างทอง</td>
            <td class="text-right">
                9,839.00                                                            </td>
            <td class="text-right">
                11,337.00                                                            </td>
            <td class="text-right">
                11,324.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">73</td>
            <td style="padding-left:5px;">อำนาจเจริญ</td>
            <td class="text-right">
                134,056.00                                                            </td>
            <td class="text-right">
                122,150.00                                                            </td>
            <td class="text-right">
                112,543.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">74</td>
            <td style="padding-left:5px;">อุดรธานี</td>
            <td class="text-right">
                217,532.00                                                            </td>
            <td class="text-right">
                188,519.00                                                            </td>
            <td class="text-right">
                170,633.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">75</td>
            <td style="padding-left:5px;">อุตรดิตถ์</td>
            <td class="text-right">
                46,988.00                                                            </td>
            <td class="text-right">
                37,687.00                                                            </td>
            <td class="text-right">
                40,403.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">76</td>
            <td style="padding-left:5px;">อุทัยธานี</td>
            <td class="text-right">
                19,043.00                                                            </td>
            <td class="text-right">
                28,466.00                                                            </td>
            <td class="text-right">
                29,811.00                                                            </td>
        </tr>
        <tr class="bg-white">
            <td class="text-center">77</td>
            <td style="padding-left:5px;">อุบลราชธานี</td>
            <td class="text-right">
                413,718.00                                                            </td>
            <td class="text-right">
                445,383.00                                                            </td>
            <td class="text-right">
                401,753.00                                                            </td>
        </tr>
    </tbody>
</table>
<?php Panel::end(); ?>