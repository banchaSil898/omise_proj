<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'นโยบายคุ้มครองข้อมูลส่วนบุคคล',
            'url' => ['site/privacy-policy'],
        ]
    ],
]);
?>
<div id="privacy-page" class="container">
    <div class="text-content">
        <?php
        Page::begin([
            'title' => 'นโยบายคุ้มครองข้อมูลส่วนบุคคล',
            'subtitle' => 'บริษัท มติชน จำกัด (มหาชน)',
        ])
        ?>
        <div class="mb-2">
            <h3>ข้อมูลส่วนบุคคล คืออะไร?</h3>
            <p>ข้อมูลส่วนบุคคล หมายถึง ข้อมูลเกี่ยวกับบุคคลซึ่งทำให้สามารถระบุตัวตนบุคคลนั้นได้ ไม่ว่าทางตรงหรือทางอ้อม แต่ไม่รวมถึงข้อมูลของผู้ถึงแก่กรรมโดยเฉพาะ</p>
        </div>
        <div class="mb-2">
            <h3>ลักษณะข้อมูลส่วนบุคคลที่เราเก็บรวบรวม</h3>
            <p>เราจะเก็บรวบรวมข้อมูลส่วนบุคคลดังต่อไปนี้</p>
            <ol>
                <li>ข้อมูลที่บ่งชี้ตัวตน อาทิ ชื่อ อายุ สัญชาติ วันเกิด อายุ</li>
                <li>ข้อมูลช่องทางการติดต่อ อาทิ ที่อยู่ สถานที่ติดต่อ เบอร์โทร อีเมล</li>
                <li>ข้อมูลบัญชี อาทิ รายละเอียดการชำระเงิน และบัญชีธนาคาร</li>
                <li>ข้อมูลทางธุรกรรม อาทิ ประวัติการรับบริการต่างๆ ประวัติการซื้อขาย</li>
                <li>ข้อมูลส่วนตัว อาทิ ชื่อบัญชีผู้ใช้ รหัสผ่าน การสั่งซื้อ ความสนใจของท่านที่มีต่อบริการต่างๆของผู้ให้บริการ</li>
                <li>ข้อมูลทางเทคนิค อาทิ Google Analytics หมายเลขระบุตำแหน่งคอมพิวเตอร์ (IP Address) ข้อมูลการเข้าระบบ ข้อมูลการใช้งาน และ การตั้งค่า (log)</li>
                <li>ข้อมูลทางการตลาด อาทิ ความพึงพอใจของท่านต่อบริการที่ได้รับ และความเห็นต่อการให้บริการของบุคลากร</li>
            </ol>
        </div>
        <div class="mb-2">
            <h3>แหล่งที่มาของข้อมูลส่วนบุคคล</h3>
            <p>เราได้รับข้อมูลส่วนบุคคลของท่านจาก 2 ช่องทาง ดังนี้</p>
            <ol>
                <li>
                    เราได้รับข้อมูลส่วนบุคคลจากท่านโดยตรง โดยเราจะเก็บรวบรวมข้อมูลส่วนบุคคลของท่านจากขั้นตอนการให้บริการดังนี้
                    <ul>
                        <li>เมื่อท่านลงทะเบียนบัญชีเพื่อใช้บริการกับเรา หรือเมื่อท่านยื่นคำร้องขอใช้สิทธิต่างๆ กับเรา</li>
                        <li>เมื่อท่านสมัครรับข้อมูลสื่อโฆษณาหรือข้อมูลด้านการตลาดจากเรา</li>
                        <li>จากความสมัครใจของท่านในการทำแบบสอบถาม</li>
                        <li>จากการเก็บข้อมูลการใช้แพลตฟอร์มของท่านผ่านบราวเซอร์คุกกี้</li>
                        <li>จากการติดต่อสอบถามของท่าน หรือผ่านการโต้ตอบทางอีเมลหรือ ช่องทางการสื่อสารอื่นๆ เช่น โทรศัพท์ เพื่อที่ผู้ให้บริการสามารถติดต่อท่านกลับได้</li>
                        <li>เมื่อท่านกดซื้อบริการหรือผลิตภัณฑ์จากเรา</li>
                        <li>เมื่อท่านเข้าสู่ระบบบัญชีผู้ใช้บนแพลตฟอร์มของเรา หรือแอพพลิเคชั่นอื่นๆที่เกี่ยวข้อง อาทิ เฟสบุ๊ค และ กูเกิ้ล</li>
                    </ul>
                </li>
                <li>เราได้รับข้อมูลส่วนบุคคลของท่านมาจากบุคคลที่สาม ดังต่อไปนี้ Facebook Login, Google Login, LINE Login, Email & Password Login โดยได้รับข้อมูลเมื่อท่านสมัครระบบหรือเข้าใช้งานระบบผ่านช่องทางของบุคคลที่สาม</li>
            </ol>
        </div>
        <div class="mb-2">
            <h3>วัตถุประสงค์ในการประมวลผลข้อมูล</h3>
            <ol>
                <li>เราจัดเก็บข้อมูลส่วนบุคคลของท่านเพื่อประโยชน์ในการจัดฐานข้อมูลในการวิเคราะห์ และเสนอสิทธิประโยชน์ตามความสนใจของท่าน</li>
                <li>เราจัดเก็บข้อมูลส่วนบุคคลของท่านเพื่อการดำเนินการชำระเงินค่าบริการหรือสินค้าที่ท่านซื้อในระบบ</li>
                <li>เราจัดเก็บข้อมูลส่วนบุคคลของท่านเพื่อวัตถุประสงค์ในการรับข้อมูลหลังการบริการ เช่น การสอบถามข้อมูล การแสดงความคิดเห็นหลังการบริการ หรือ การส่งคำร้องแก่ผู้ให้บริการ เป็นต้น</li>
                <li>เราจัดเก็บข้อมูลส่วนบุคคลเพื่อยืนยันตัวตนว่าท่านเป็นผู้เดียวในการเข้าถึงบัญชีของท่าน</li>
                <li>เราจัดเก็บข้อมูลส่วนบุคคลของท่านเพื่อวิจัยการตลาดและบริหารความสัมพันธ์ระหว่างผู้  ให้บริการและผู้ใช้บริการ</li>
                <li>เราจัดเก็บข้อมูลส่วนบุคคลของท่านเพื่อปฏิบัติตามข้อกฎหมาย และระเบียบบังคับใช้ของรัฐ</li>
                <li>เราจัดเก็บข้อมูลส่วนบุคคลของท่านเพื่อปฏิบัติตามกฎระเบียบที่ใช้บังคับกับผู้บริการ รวมถึงการยินยอมให้ผู้ให้บริการสามารถโอนข้อมูลส่วนบุคคลให้แก่กลุ่มธุรกิจและพันธมิตรของผู้ให้บริการ ผู้ประมวลผลข้อมูล หรือหน่วยงานใดๆที่มีสัญญากับผู้ให้บริการ</li>
            </ol>
        </div>
        <div class="mb-2">
            <h3>การประมวลผลข้อมูลส่วนบุคคล</h3>
            <p>เมื่อได้รับข้อมูลส่วนบุคคลจากท่านแล้ว เราจะดำเนินการดังนี้</p>
            <ol>
                <li>เก็บรวบรวมโดยมีการบันทึกในระบบคอมพิวเตอร์ ที่ใช้บริการ ได้แก่ ตั้งเซิฟเวอร์ด้วยตนเอง, cs loxinfo cloud</li>
                <li>เราจะใช้ข้อมูลส่วนบุคคลของท่านที่ได้เก็บรวบรวมมาในการดำเนินของบริษัทตามวัตถุประสงค์ที่ระบุไว้ในหัวข้อ "วัตถุประสงค์ในการประมวลผลข้อมูล"</li>
                <li>บริษัทจะเปิดเผยข้อมูลกับพนักงานภายใต้สัญญาจ้างของบริษัทที่มีขอบเขตหน้าที่เกี่ยวข้องกับข้อมูล เปิดเผยบนแพลตฟอร์มของบริษัท หรือช่องทางสังคมออนไลน์เพื่อการโฆษณา นอกจากนี้ เราอาจจำเป็นต้องส่งข้อมูลส่วนบุคคลของท่านไปยังหน่วยงานข้อมูลเครดิต เพื่อตรวจสอบ และอาจใช้ผลการตรวจสอบข้อมูลดังกล่าวเพื่อการป้องกันการฉ้อโกง</li>
            </ol>
        </div>
        <div class="mb-2">
            <h3>การเก็บรักษาและระยะเวลาในการเก็บรักษาข้อมูลส่วนบุคคล</h3>
            <h4>การเก็บรักษาข้อมูลส่วนบุคคล</h4>
            <p>ผู้ควบคุมทำการเก็บรักษาข้อมูลส่วนบุคคลของท่าน ดังนี้</p>
            <ol>
                <li>ข้อมูลส่วนบุคคลที่ทางบริษัทจัดเก็บจะอยู่ในลักษณะของ Soft Copy</li>
                <li>ข้อมูลส่วนบุคคลจะถูกจัดเก็บไว้ในเครื่องมืออุปกรณ์ของบริษัท ได้แก่ คอมพิวเตอร์ โทรศัพท์มือถือ ของทางบริษัท รวมถึงมีการเก็บข้อมูลในบนระบบคอมพิวเตอร์ ซึ่งได้แก่ ตั้งเซิฟเวอร์ด้วยตนเอง, cs loxinfo cloud</li>
                <li>ระยะเวลาจัดเก็บ เป็นไปตามหัวข้อ "ระยะเวลาในการประมวลผลข้อมูลส่วนบุคคล"</li>
            </ol>
        </div>
        <div class="mb-2">
            <h3>Data Retention Period</h3>
            <table class="table table-bordered table-condensed table-xs">
                <thead>
                    <tr>
                        <th class="text-center">ลำดับที่</th>
                        <th class="text-center">ประเภท / รายการข้อมูลส่วนบุคคล</th>
                        <th class="text-center">ระยะเวลาประมวลผล</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1.</td>
                        <td>ข้อมูลที่บ่งชี้ตัวตน อาทิ ชื่อ อายุ สัญชาติ วันเกิด อายุ</td>
                        <td>10 ปี นับแต่วันที่เลิกสัญญา</td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>ข้อมูลช่องทางการติดต่อ อาทิ ที่อยู่ สถานที่ติดต่อ เบอร์โทร อีเมล</td>
                        <td>10 ปี นับแต่วันที่เลิกสัญญา</td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>ข้อมูลบัญชี อาทิ รายละเอียดการชำระเงิน และบัญชีธนาคาร</td>
                        <td>10 ปี นับแต่วันที่เลิกสัญญา</td>
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>ข้อมูลทางธุรกรรม อาทิ ประวัติการรับบริการต่างๆ ประวัติการซื้อขาย</td>
                        <td>10 ปี นับแต่วันที่เลิกสัญญา</td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>ข้อมูลส่วนตัว อาทิ ชื่อบัญชีผู้ใช้ รหัสผ่าน การสั่งซื้อ ความสนใจของท่านที่มีต่อบริการต่างๆของผู้ให้บริการ</td>
                        <td>5 ปี นับแต่วันที่เลิกสัญญา</td>
                    </tr>
                    <tr>
                        <td>6.</td>
                        <td>ข้อมูลทางเทคนิค อาทิ Google Analytics หมายเลขระบุตำแหน่งคอมพิวเตอร์ (IP Address) ข้อมูลการเข้าระบบ ข้อมูลการใช้งาน และ การตั้งค่า (log)</td>
                        <td>5 ปี นับแต่วันที่เลิกสัญญา</td>
                    </tr>
                    <tr>
                        <td>7.</td>
                        <td>ข้อมูลทางการตลาด อาทิ ความพึงพอใจของท่านต่อบริการที่ได้รับ และความเห็นต่อการให้บริการของบุคลากร</td>
                        <td>5 ปี นับแต่วันที่เลิกสัญญา</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mb-2">
            <h3>สิทธิของเจ้าของข้อมูล</h3>
            <p>ท่านมีสิทธิในการดำเนินการ ดังต่อไปนี้</p>
            <ol>
                <li>สิทธิในการได้รับแจ้ง (right to be informed): ท่านมีสิทธิที่จะได้รับแจ้งเมื่อข้อมูลส่วนบุคคลของท่านถูกจัดเก็บ รวมถึงรายละเอียดต่างๆที่เกี่ยวข้อง อาทิ วิธีการจัดเก็บและระยะเวลาการจัดเก็บ</li>
                <li>สิทธิในการเพิกถอนความยินยอม (right to withdraw consent): ท่านมีสิทธิในการเพิกถอนความยินยอมในการประมวลผลข้อมูลส่วนบุคคลที่ท่านได้ให้ความยินยอมกับเราได้ ตลอดระยะเวลาที่ข้อมูลส่วนบุคคลของท่านอยู่กับเรา</li>
                <li>สิทธิในการเข้าถึงข้อมูลส่วนบุคคล (right of access): ท่านมีสิทธิในการเข้าถึงข้อมูลส่วนบุคคลของท่านและขอให้เราทำสำเนาข้อมูลส่วนบุคคลดังกล่าวให้แก่ท่าน รวมถึงขอให้เราเปิดเผยการได้มาซึ่งข้อมูลส่วนบุคคลที่ท่านไม่ได้ให้ความยินยอมต่อเรา</li>
                <li>สิทธิในการแก้ไขข้อมูลส่วนบุคคลให้ถูกต้อง (right to rectification): ท่านมีสิทธิในการขอให้เราแก้ไขข้อมูลที่ไม่ถูกต้องหรือเพิ่มเติมข้อมูลที่ไม่สมบูรณ์</li>
                <li>สิทธิในการลบข้อมูลส่วนบุคคล (right to erasure): ท่านมีสิทธิในการขอให้เราทำการลบข้อมูลของท่านด้วยเหตุบางประการได้</li>
                <li>สิทธิในการระงับการใช้ข้อมูลส่วนบุคคล (right to restriction of processing): ท่านมีสิทธิในการระงับการใช้ข้อมูลส่วนบุคคลของท่านด้วยเหตุบางประการได้</li>
                <li>สิทธิในการให้โอนย้ายข้อมูลส่วนบุคคล (right to data portability): ท่านมีสิทธิในการโอนย้ายข้อมูลส่วนบุคคลของท่านไปให้แก่ผู้ควบคุมข้อมูลรายอื่นหรือตัวท่านเองด้วยเหตุบางประการได้</li>
                <li>สิทธิในการคัดค้านการประมวลผลข้อมูลส่วนบุคคล (right to object): ท่านมีสิทธิในการคัดค้านการประมวลผลข้อมูลส่วนบุคคลของท่านด้วยเหตุบางประการได้</li>    
            </ol>
            <p>ท่านสามารถติดต่อมายังเจ้าหน้าที่ DPO หรือเจ้าหน้าที่ฝ่ายควบคุมข้อมูลของเราได้ เพื่อดำเนินการยื่นคำร้องขอดำเนินการตามสิทธิข้างต้นได้ (รายละเอียดการติดต่อปรากฏในหัวข้อ “ช่องทางการติดต่อ” ด้านล่างนี้) หรือ ท่านสามารถศึกษารายละเอียดเงื่อนไข ข้อยกเว้นการใช้สิทธิต่างๆ ได้ที่ แนวปฏิบัติเกี่ยวกับการคุ้มครองข้อมูลส่วนบุคคล (TDPG2.0) และ เว็บไซต์กระทรวงดิจิทัลเพื่อเศรษฐกิจและสังคม http://www.mdes.go.th</p>
            <p>ทั้งนี้ ท่านไม่จำเป็นต้องเสียค่าใช้จ่ายใดๆ ในการดำเนินตามสิทธิข้างต้น โดยเราจะพิจารณา และแจ้งผลการพิจารณาคำร้องของท่านภายใน 30 วันนับแต่วันที่เราได้รับคำร้องขอดังกล่าว</p>
        </div>
        <div class="mb-2">
            <h3>การยื่นคำร้องเพื่อการจัดการข้อมูลส่วนบุคคล</h3>
            <p>หากท่านมีความประสงค์ในการยื่นคำร้องเรียนเพื่อจัดการข้อมูลส่วนบุคคลของท่าน ซึ่งรวมไปถึงการขอเข้าถึงข้อมูลส่วนบุคคล การแก้ไขข้อมูลส่วนบุคคล การขอเพิกถอนการยินยอมให้ข้อมูลส่วนบุคคล และการส่งความคิดเห็นต่อการบริการ ท่านสามารถติดต่อได้ทางเจ้าหน้าที่คุ้มครองข้อมูลส่วนบุคคลตามรายละเอียดในหัวข้อ "ช่องทางการติดต่อ"</p>
        </div>

        <div class="mb-2">
            <h3>กิจกรรมส่งเสริมการตลาด</h3>
            <p>ในระหว่างการใช้บริการ เราจะส่งข้อมูลข่าวสารเกี่ยวกับกิจกรรมทางการตลาด และการส่งเสริม การตลาด ผลิตภัณฑ์ การให้บริการ ที่เราคิดว่าท่านอาจสนใจเพื่อประโยชน์ในการให้บริการกับ ท่านอย่างเต็มประสิทธิภาพ หากท่านได้ตกลงที่จะรับข้อมูลข่าวสารดังกล่าวจากเราแล้ว ท่านมีสิทธิ ยกเลิกความยินยอมดังกล่าวได้ทุกเมื่อ โดยท่านสามารดำเนินการยกเลิกความยินยอมในการรับแจ้งข้อมูล ข่าวสารได้โดยสามารถติดต่อมาได้ผ่านทางที่อยู่อีเมล webadmin@matichon.co.th</p>
        </div>
        <div class="mb-2">
            <h3>Cookies คืออะไร?</h3>
            <p>Cookies คือ text files ที่อยู่ในคอมพิวเตอร์ของท่าน ใช้เพื่อจัดเก็บรายละเอียดข้อมูล log การใช้งานอินเตอร์เน็ตของท่าน หรือ พฤติกรรมการเยี่ยมชมเว็บไซต์ของท่าน ท่านสามารถศึกษารายละเอียด เพิ่มเติมของ Cookies ได้จาก https://www.allaboutcookies.org/</p>
        </div>
        <div class="mb-2">
            <h3>เราใช้ Cookies อย่างไร?</h3>
            <p>เราจะจัดเก็บข้อมูลการเข้าเยี่ยมชมเว็บไซต์จากผู้เข้าเยี่ยมชมทุกรายผ่าน Cookies หรือ เทคโนโลยีที่ใกล้เคียง และเราใช้ Cookies เพื่อประโยชน์ในการพัฒนาประสิทธิภาพในการเข้าถึงบริการของเราผ่านระบบอินเตอร์เน็ต รวมถึงพัฒนาประสิทธิภาพในการใช้งานแพลตฟอร์ม โดยจะใช้เพื่อกรณี ดังต่อไปนี้</p>
            <ol>
                <li>เพื่อให้ท่านสามารถเข้าสู่ระบบบัญชีของท่านในแพลตฟอร์มของเราได้อย่างต่อเนื่อง และปลอดภัย</li>
                <li>เพื่อบันทึกข้อมูลการใช้งานบนแพลตฟอร์มของท่าน จัดเนื้อหาข้อมูล รวมถึงรูปแบบแพลตฟอร์มที่ท่านได้ตั้งค่าไว้</li>
                <li>เพื่อศึกษาพฤติกรรมการใช้งานและเยี่ยมชมแพลตฟอร์มของท่าน เพื่อปรับเนื้อหาให้ตรงกับความสนใจ และสามารถตอบสนองความต้องการของท่านมากขึ้น</li>
                <li>เพื่อศึกษาพฤติกรรมของผู้เยี่ยมชมแพลตฟอร์มโดยรวม และนำไปพัฒนาให้สามารถใช้งานได้ง่าย รวดเร็ว และมีประสิทธิภาพยิ่งขึ้น</li>
            </ol>
        </div>
        <div class="mb-2">
            <h3>ประเภทของ Cookies ที่เราใช้</h3>
            <p>เว็บไซต์ของเราประกอบไปด้วย Cookies ดังต่อไปนี้</p>
            <ol>
                <li>Functionality Cookies: คุกกี้ประกอบการทำงานของแพลตฟอร์ม ถูกใช้ในการจดจำสิ่งที่ท่านเลือกหรือตั้งค่าบนแพลตฟอร์ม รวมถึงการนำเสนอข้อมูลที่ตรงความต้องการ เฉพาะบุคคลมากขึ้น เช่น ชื่อบัญชีผู้ใช้ ภาษา ฟ้อนต์ และรูปแบบแพลตฟอร์ม</li>
                <li>Advertising Cookies: คุกกี้สำหรับการโฆษณา ใช้ในการจดจำสิ่งที่ท่านเคยเยี่ยมชมและรวมถึงลักษณะการใช้แพลตฟอร์มของท่าน เพื่อนำเสนอสินค้า บริการ หรือ สื่อโฆษณาที่เกี่ยวข้องและตรงกับความสนใจของท่าน และใช้เพื่อการประเมินประสิทธิผลของแคมเปญโฆษณาต่างๆ</li>
                <li>Strictly Necessary Cookies: คุกกี้ทางเทคนิค เป็นประเภทคุกกี้ที่มีความจำเป็นต่อการใช้งานแพลตฟอร์ม ทำให้ท่านสามารถเข้าถึงข้อมูลได้อย่างทั่วถึงและปลอดภัย</li>
                <li>Performance Cookies: คุกกี้เพื่อวัดผลการทำงานของแพลตฟอร์ม คุกกี้ประเภทนี้จะจัดเก็บข้อมูลของผู้เข้าชมแพลตฟอร์มแบบไม่ระบุตัวตน และนำมาวิเคราะห์จำนวนและพฤติกรรมของผู้เข้าชม เพื่อปรับปรุงแพลตฟอร์มให้มีประสิทธิภาพและตรงกับความต้องการของผู้ใช้มากขึ้น</li>
                <li>Third-party Cookies: คุกกี้บุคคลที่สาม คุกกี้ชนิดนี้จะถูกกำหนดใช้โดยผู้บริการซึ่งเป็นบุคคลที่สาม อาทิ Google Analytics</li>
            </ol>
        </div>
        <div class="mb-2">
            <h3>การตั้งค่าคุกกี้</h3>
            <p>ท่านสามารถตั้งค่าเพื่อปฏิเสธการใช้การคุกกี้ในบราวเซอร์ของท่านได้ โดยมีขั้นตอนในการจัดการดังนี้ :</p>
            <h4>สำหรับผู้ใช้ระบบซาฟารี (Safari)</h4>
            <ol>
                <li>เข้าแอพพลิเคชั่นซาฟารีและเลือก “การตั้งค่า”</li>
                <li>เลือก “ความเป็นส่วนตัว” และจัดการข้อมูลการใช้คุกกี้ตามความต้องการของท่านดังนี้</li>
                <li>เลือก “ป้องกันไม่ให้ติดตามข้ามไซต์” เพื่อยกเลิกตัวติดตามใช้คุกกี้และข้อมูลเว็บไซต์</li>
                <li>เลือก “ปิดกั้นคุกกี้ทั้งหมด” เพื่อปิดกั้นไม่ให้เว็บไซต์บุคคลที่สามและผู้โฆษณาจัดเก็บข้อมูลต่างๆไว้บนเครื่องคอมพิวเตอร์ของคุณ</li>
                <li>เลือก “จัดการข้อมูลเว็บไซต์” เพื่อดูว่าเว็บไซต์ใดบ้างที่จัดเก็บคุกกี้และข้อมูลของท่าน</li>
            </ol>
            <h4>สำหรับผู้ใช้กูเกิ้ลโครม (Google Chrome)</h4>
            <ol>
                <li>เข้าระบบกูเกิ้ลโครมในคอมพิวเตอร์ และเลือก “การตั้งค่า” ด้านขวาบน</li>
                <li>เลือก “ขั้นสูง” ด้านล่าง</li>
                <li>เลือก “การตั้งค่าและความปลอดภัย”</li>
                <li>เลือก “การตั้งค่าเว็บไซต์”</li>
                <li>คลิก “คุกกี้”</li>
                <li>หากต้องการยกเลิกการใช้คุกกี้ เลือก “ปิดการอนุญาตให้เว็บไซต์บันทึกและอ่านข้อมูลคุกกี้”</li>
            </ol>
            <h4>สำหรับผู้ใช้ Internet Explorer</h4>
            <ol>
                <li>เมื่อเข้าระบบ Internet Explorer เลือก “การตั้งค่า”</li>
                <li>หากต้องการลบข้อมูลคุกกี้:
                    <ul>
                        <li>เลือก “ความปลอดภัย”</li>
                        <li>เลือก “ลบประวัติการเรียกดู”</li>
                        <li>เลือก “คุกกี้และข้อมูลเว็บไซต์” และกด “ลบ”</li>
                    </ul>
                </li>
                <li>หากต้องการลบข้อมูลคุกกี้:
                    <ul>
                        <li>เลือก “เครื่องมือ”</li>
                        <li>เลือก “ตัวเลือกอินเทอร์เน็ต”</li>
                        <li>เลือก “ความเป็นส่วนตัว”</li>
                        <li>กด “ขั้นสูง” และเลือกให้อนุญาตหรือบล็อกคุกกี้</li>
                    </ul>
                </li>
            </ol>
        </div>
        <div class="mb-2">
            <h3>นโยบายคุ้มครองข้อมูลส่วนบุคคลของเว็บไซต์อื่น</h3>
            <p>นโยบายความเป็นส่วนตัวฉบับนี้ ใช้เฉพาะสำหรับการให้บริการของเราและการใช้งานเว็บไซต์ของเราเท่านั้น หากท่านได้กดลิ้งก์ไปยังเว็บไซต์อื่น แม้จะผ่านช่องทางในเว็บไซต์ของเราก็ตาม ท่านจะต้องศึกษาและปฏิบัติตามนโยบายความเป็นส่วนตัวที่ปรากฏในเว็บไซต์นั้นๆ แยกจากของเว็บไซต์ของเราอย่างสิ้นเชิง</p>
        </div>
        <div class="mb-2">
            <h3>การเปลี่ยนแปลงนโยบายคุ้มครองข้อมูลส่วนบุคคล</h3>
            <p>เราจะทำการพิจารณาทบทวนนโยบายความเป็นส่วนตัวเป็นประจำเพื่อให้สอดคล้องกับแนวทางการปฏิบัติ และกฎหมายข้อบังคับที่เกี่ยวข้อง ทั้งนี้หากมีการเปลี่ยนแปลงนโยบายความเป็นส่วนตัว เราจะแจ้งให้ท่านทราบด้วยการปรับเปลี่ยนข้อมูลลงในเว็บไซต์ของเราโดยเร็วที่สุด ปัจจุบันนโยบายความเป็นส่วนตัวถูกทบทวนครั้งล่าสุดเมื่อ 13/05/2020 และจะมีผลตั้งแต่วันที่ 13/05/2020</p>
        </div>
        <div class="mb-2">
            <h3>ช่องทางการติดต่อ</h3>

            <h4>รายละเอียดผู้ควบคุมข้อมูล</h4>
            <blockquote>
                ชื่อ: บริษัท มติชน จำกัด (มหาชน)<br/>
                สถานที่ติดต่อ: บริษัท มติชน จำกัด (มหาชน) เลขที่ 12 เทศบาลนฤมาล หมู่บ้านประชานิเวศน์ 1 แขวงลาดยาว เขตจตุจักร กรุงเทพฯ 10900<br/>
                ช่องทางการติดต่อ: 025890020<br/>
                อีเมล: webadmin@matichon.co.th<br/>
                เว็บไซต์: www.matichon.co.th, www.khaosod.co.th, www.prachachat.net, www.sentangsedtee.com, www.technologychaoban.com, www.silpa-mag.com, www.matichonweekly.com, www.khaosodenglish.com, www.matichonacademy.com, www.matichonbook.com, www.matichonelibrary.com
            </blockquote>
            <h4>รายละเอียดเจ้าหน้าที่คุ้มครองข้อมูลส่วนบุคคล (Data Protection Officer: DPO)</h4>
            <blockquote>
                ชื่อ: นายวโรดม ลิ้มประพันธุ์<br/>
                สถานที่ติดต่อ: บริษัท มติชน จำกัด (มหาชน) เลขที่ 12 เทศบาลนฤมาล หมู่บ้านประชานิเวศน์ 1 แขวงลาดยาว เขตจตุจักร กรุงเทพฯ 10900<br/>
                ช่องทางการติดต่อ: 025890020 ต่อ 1308<br/>
                อีเมล: webadmin@matichon.co.th
            </blockquote>
            <h4>รายละเอียดหน่วยงานกำกับดูแล</h4>
            <p>ในกรณีที่พนักงานของเราหรือบุคคลที่เกี่ยวข้องฝ่าฝืนหรือไม่ปฏิบัติตามกฎหมายเกี่ยวกับการคุ้มครองข้อมูลส่วนบุคคล ท่านสามารถร้องเรียนต่อหน่วยงานกำกับดูแลตามรายละเอียดดังนี้</p>
            <blockquote>
                ชื่อ: สำนักงานคณะกรรมการคุ้มครองข้อมูลส่วนบุคคล<br/>
                สถานที่ติดต่อ: ชั้น 7 อาคารรัฐประศาสนภักดี ศูนย์ราชการ เฉลิมพระเกียรติ 80 พรรษาฯ ถนนแจ้งวัฒนะ แขวงทุ่งสองห้อง เขตหลักสี่ กรุงเทพมหานคร 10210<br/>
                ช่องทางการติดต่อ: (+66) 02-142-2233
            </blockquote>
        </div>
        <hr/>
        <?php Page::end(); ?>
        <?php
        Page::begin([
            'title' => 'เอกสารแจ้งข้อมูลการประมวลผลข้อมูล (แบบย่อ)',
        ])
        ?>
        <div class="mb-2">
            <h3>ข้อมูลส่วนบุคคลที่จะทำการประมวลผล</h3>
            <p>รายละเอียดปรากฏตามนโยบายคุ้มครองข้อมูลส่วนบุคคล หัวข้อ “ข้อมูลส่วนบุคคลที่เราเก็บรวบรวม” และ “ระยะเวลาในการเก็บรักษาข้อมูลส่วนบุคคล” วัตถุประสงค์และฐานในการประมวลผลข้อมูล รายละเอียดปรากฏตามนโยบายคุ้มครองข้อมูลส่วนบุคคล หัวข้อ “วัตถุประสงค์ในการประมวลผลข้อมูล” เราดำเนินการประมวลผลข้อมูลส่วนบุคคลของท่านภายใต้ฐาน ดังต่อไปนี้</p>
            <ol>
                <li>การปฏิบัติตามสัญญา ได้แก่ การจัดส่งใบเสร็จให้ผู้ใช้งานทางที่อยู่อีเมลภายหลังการซื้อบริการจากทางบริษัท หรือการโอนเงินไปยังบัญชีธนาคารของผู้ใช้ และสัญญาอื่นที่ทางเจ้าของข้อมูลส่วนบุคคลได้ทำไว้กับทางบริษัท</li>
                <li>ความยินยอม ตามที่ท่านได้ให้ความยินยอมเมื่อสมัครใช้บริการ ทั้งนี้หากท่านประสงค์จะถอนความยินยอม ท่านสามารถดำเนินการได้ดังนี้ท่านสามารถแจ้งเพิกถอนความยินยอมในการให้ใช้หรือเปิดเผยข้อมูลในวัตถุประสงค์หรือลักษณะใดๆที่ได้ระบุไว้ข้างต้นได้ตลอดเวลา รวมถึงการร้องขอให้ลบหรือทำให้ข้อมูลส่วนบุคคลเป็นนิรนามได้ โดยติดต่อเจ้าหน้าที่ป้องกันข้อมูลส่วนบุคคลซึ่งระบุเอาไว้ด้านบน แนวทางการถอนความยินยอมประกอบไปด้วย :
                    <ul>
                        <li>ช่องทางอิเล็กทรอนิกส์ อาทิ อีเมล หรือ เว็บไซต์</li>
                        <li>แจ้งผ่านวาจา อาทิ โทรศัพท์ หรือ ต่อหน้าบุคลากร</li>
                        <li>แจ้งผ่านลายลักษณ์อักษร</li>
                    </ul>
                </li>
            </ol>
            <p>โดยเมื่อบุคลากรได้รับคำรองของท่านแล้ว จะทำการส่งต่อให้แก่ฝ่ายบริหารจัดการข้อมูลเพื่อดำเนินการในขั้นตอนต่อไป</p>
            <p>ทั้งนี้ทางผู้ให้บริการไม่สามารถเก็บข้อมูลส่วนบุคคลของท่านหากไม่ได้รับการยินยอม เว้นแต่เพื่อวัตถุประสงค์ต่อไปนี้</p>
            <ol>
                <li>เพื่อผลประโยชน์สำคัญจำเป็น เพื่อระงับหรือป้องกันอันตรายต่อชีวิต สุขภาพ หรือร่างกายของบุคคล</li>
                <li>เพื่อจัดทำเอกสารทางประวัติศาสตร์ จดหมายเหตุ การวิจัยหรือสถิติ</li>
                <li>เพื่อการปฎิบัติตามพันธะสัญญาซึ่งเจ้าของข้อมูลส่วนบุคคลเป็นคู่สัญญา</li>
                <li>เพื่อดำเนินงานตามระเบียบหรือกฎหมายของภาครัฐ หรือการใช้อำนาจรัฐของผู้ควบคุมส่วนบุคคล</li>
                <li>เพื่อประโยชน์โดยชอบด้วยกฎหมายของผู้ให้บริการ ผู้ควบคุมข้อมูลส่วนบุคคล หรือนิติบุคคลอื่น เว้นแต้ประโยชน์ดังกล่าวมีความสำคัญน้อยกว่าสิทธิขั้นพื้นฐานในการให้ข้อมูลส่วนบุคคล</li>
                <li>เพื่อประโยชน์สาธารณะของผู้ควบคุมข้อมูลส่วนบุคคล</li>
            </ol>
        </div>
        <div class="mb-2">
            <h3>แหล่งที่มาของข้อมูลส่วนบุคคล</h3>
            <p>เราได้รับข้อมูลส่วนบุคคลของท่าน จากกรณีดังนี้</p>
            <ol>
                <li>เมื่อท่านได้เข้าถึงหรือใช้บริการเว็บไซต์ของเรา ลงทะเบียน หรือสมัครบัญชีผู้ใช้กับเรา</li>
                <li>เมื่อท่านส่งแบบฟอร์ม รวมถึงแบบฟอร์มการสมัครหรือแบบฟอร์มอื่นๆ ที่เกี่ยวข้องกับผลิตภัณฑ์และบริการของเรา ไม่ว่าจะเป็นรูปแบบออนไลน์หรือรูปแบบเอกสาร</li>
                <li>เมื่อท่านทำข้อตกลงใดๆ หรือให้เอกสารหรือข้อมูลอื่นใดที่เกี่ยวข้องกับการติดต่อระหว่างท่านกับเรา หรือเมื่อท่านใช้ผลิตภัณฑ์และบริการของเรา</li>
                <li>เมื่อท่านติดต่อกับเรา เช่น ผ่านทางโทรศัพท์ (ซึ่งจะได้รับการบันทึก) จดหมาย แฟกซ์ การประชุม การติดต่าผ่านแพลตฟอร์มสื่อทางสังคม และอีเมล</li>
                <li>เมื่อท่านใช้บริการทางอิเล็กทรอนิกส์ หรือใช้บริการเว็บไซต์ของเรา ซึ่งรวมถึงแต่ไม่จำกัดเพียง การใช้ผ่านคุกกี้ ซึ่งเราอาจปรับใช้เมื่อท่านได้เข้าถึงเว็บไซต์</li>
                <li>เมื่อท่านดำเนินธุรกรรมผ่านบริการของเรา</li>
                <li>เมื่อท่านแสดงความคิดเห็นหรือส่งคำร้องเรียนแก่เรา</li>
                <li>เมื่อท่านลงทะเบียน เข้าทำสัญญา เข้าร่วมในการแข่งขัน และ/หรือกิจกรรมส่งเสริมการขายซึ่งรวมถึงกิจกรรมส่งเสริมการขายร่วมกับหุ้นส่วนหรือบริษัทในกลุ่มของเรา</li>
                <li>เมื่อท่านใช้หรือร้องขอการใช้บริการที่เกี่ยวข้องกับผู้ให้บริการภายนอกของเรา เช่น การชำระเงิน บริการด้านโลจิสติกส์ เป็นต้น</li>
                <li>เมื่อท่านเข้าเยี่ยมชมหรือใช้เว็บไซต์และแอปพลิเคชั่นของหุ้นส่วนหรือบริษัทในกลุ่มของเรา</li>
                <li>เมื่อท่านเข้าเยี่ยมชมหรือใช้เว็บไซต์และแอปพลิเคชั่นของบุคคลภายนอกที่เกี่ยวข้องกับบริการของเรา</li>
                <li>เมื่อท่านติดตั้งแอปพลิเคชั่นบนคอมพิวเตอร์ โทรศัพท์ หรืออุปกรณ์อื่นๆ เพื่อเข้าถึงบริการของเรา</li>
                <li>เมื่อท่านเชื่อมต่อบัญชีของท่านกับเว็บไซต์หรือแอปพลิเคชั่นของบุคคลภายนอก</li>
                <li>เมื่อท่านส่งข้อมูลส่วนบุคคลของท่านให้แก่เราด้วยเหตุผลใดก็ตาม</li>
            </ol>
        </div>
        <div class="mb-2">
            <h3>การประมวลผลข้อมูลส่วนบุคคล</h3>
            <h4>การประมวลผลข้อมูล</h4>
            <p>รายละเอียดปรากฏตามนโยบายคุ้มครองข้อมูลส่วนบุคคล หัวข้อ “การประมวลผลข้อมูลส่วนบุคคล”</p>
            <h4>การเก็บรักษาข้อมูลส่วนบุคคล</h4>
            <p>รายละเอียดปรากฏตามนโยบายคุ้มครองข้อมูลส่วนบุคคล หัวข้อ “การเก็บรักษาข้อมูลส่วนบุคคล”</p>
            <h4>สิทธิของเจ้าของข้อมูล</h4>
            <p>รายละเอียดปรากฏตามนโยบายคุ้มครองข้อมูลส่วนบุคคล เรื่อง “สิทธิของเจ้าของข้อมูล”</p>
        </div>
        <hr/>
        <?php Page::end(); ?>
        <?php
        Page::begin([
            'title' => 'Data Protection Notice for Customers',
        ])
        ?>
        Data Protection Notice for Customers
        Matichon Public Company Limited




        What is personal data?
        Personal data is any information that relates to or identifies a living individual, both directly or indirectly. This also includes the collection of less explicit data that leads to an identification of a particular person.

        Collection of Personal Data
        We may collect the following data from the users:
        Personal identification data including age, nationality, date of birth, etc.
        Contact information including address, phone number, e-mail address, etc.
        Payment data including bank account, payment method, etc.
        Transaction data including transaction number, purchase history, etc.
        On-site personal data including account name, password, personal interest regarding services, etc.
        Technical data including IP address number, log-in history, setting, web browser connection, etc.
        Marketing data including service satisfaction and feedback, etc.

        Access to Personal Data
        We access users personal data through 2 main following channels:
        We access personal data directly from the users via the collection during the provision of service including:
        User’s account registration, and the submission of application for any  services
        User’s subscription of any marketing and sales information including advertisement and promotion
        User’s consensual agreement when filling out the survey
        User’s cookies when accessing the platform
        Mutual contact between users and service providers both offline and online such as email enquiries and phone calls
        User’s purchase of any particular service or product
        User’s log-in on both service provider’s platform and associated sites such as Facebook and Google
        We collect personal data of the users indirectly from the third-parties in the following cases: Facebook Login, Google Login, LINE Login, Email & Password Login

        Data Collection Objectives
        We may collect and use users personal data for any or all of the following purposes:
        To perform obligations in the course of or in connection with our provision of the goods and/or services requested by the user
        To process the payment of a particular service or product such as tours, attraction tickets, transportation service, etc.
        For post-sale customer service such as promotions and feedback enquiries
        To verify user’s identity
        For marketing research and customer relationship optimization
        To comply with any applicable laws, regulations, codes of practice, guidelines, or rules, or to assist in law enforcement and investigations conducted by any governmental and/or regulatory authority
        To transfer data to any unaffiliated third parties including our third party service providers and agents, relevant governmental and/or regulatory authorities, for the aforementioned purposes

        Sharing Personal Data
        We may share and disclose your personal data to third parties in the following circumstances:
        Within Matichon Public Company Limited for the purpose outlined in this policy.
        To third-party service providers who require access to personal data in order to assist in the provision of the website, products and services, and other business-related functions such as website analytics providers or our third-party advertisement partners. These third-party service providers are limited to only accessing or using this data to provide services to us and must provide reasonable assurances they will appropriately safeguard the data.
        We may also disclose visits information to third-parties:
        Where required by law or regulatory requirement, court order or other judicial authorization
        In response to lawful requested by public authorities including for the purposes of meeting national security and law enforcement requirements
        In connection with the sale, transfer, merger, bankruptcy, restructuring or other reorganization of a business
        To protect or defend our rights interests or property, or that of third parties
        To investigate any wrongdoing in connection with the website or service
        To protect the vital interests of an individual.
        We may also share Personal Information collected through the Website with third party companies so that they may offer products and services that we believe will be of interest to visitors, as well as third party advertising-serving companies to better target the adverts and other content displayed on website and to provide offers we think may be interests of visitors. The unsubscription of marketing is stated in the “Personal Data and Marketing Activities” section.

        Personal Data Processing
        After the collection of users personal data, we may store the data regarding the following processes:
        Computer System including On-premise, cs loxinfo cloud
        We may use the collected personal data with the purposes mentioned in “Data Collection Objectives”
        We may disclose users personal data:
        to staff members under Matichon Public Company Limited, in order to perform obligations in the course of or in connection with our provision of the goods and services requested by users; or
        to third party service providers, agents and other organisations we have engaged to perform any of the functions with reference to the above mentioned objectives and for safety purposes.

        Data Storage and Data Retention
        We may store users personal data using the following systems:
        Users personal data will be saved as soft copy data stored in our computer systems.
        Users personal data will be saved in service provider’s devices, such as computers, phones, and tablets. This includes the mentioned Computer System (On-premise, cs loxinfo cloud).
        Data retention period will be illustrated in the “Data Retention” section below.

        Data Retention Period

        No.
        Type of Personal Data
        Retention Period
        1.
        Personal identification data including age, nationality, date of birth, etc.
        10 years from contract termination date
        2.
        Contact information including address, phone number, e-mail address, etc.
        10 years from contract termination date
        3.
        Payment data including bank account, payment method, etc.
        10 years from contract termination date
        4.
        Transaction data including transaction number, purchase history, etc.
        10 years from contract termination date
        5.
        On-site personal data including account name, password, personal interest regarding services, etc.
        5 years from contract termination date
        6.
        Technical data including IP address number, log-in history, setting, web browser connection, etc.
        5 years from contract termination date
        7.
        Marketing data including service satisfaction and feedback, etc.
        5 years from contract termination date

        Data Subject Rights
        Data subjects have the following rights:
        Right to withdraw consent: Users can withdraw their consent and request the processors to stop collecting their personal data.
        Right of Access: Users can submit data access requests, which oblige processors to provide a copy of any personal data they hold regarding data subjects. This includes a request for a disclosure of platforms and methods in which the processors collected the data from.
        Right of Rectification: Users can request an update on an inaccurate or incomplete personal data.
        Right to Erasure: Users can request that the service provider erase their data in certain circumstances, such as when the data is no longer necessary, the data was unlawfully processed or is no longer meeting the lawful ground for which it was collected. This includes the instance where the individual withdraws consent.
        Right to Restriction of Processing: Users can request the service provider to limit the way their personal data is used.
        Right to Data Portability: Users are permitted to obtain and reuse their personal data for their own purposes across different services. 
        Right to be informed: Users have the right to be notified about the collection of their personal data such as storage periods and purposes.
        Right to Object: Users can object to the processing of personal data that is collected on the grounds of legitimate interests or the performance of a task in the interest/exercise of official authority. 
        Y
        ou may contact our Data Protection Officer if you have any enquiries or feedback on our personal data protection policies and procedures, or if you wish to make any request, in the following manner

        If you wish to learn more about our terms and conditions, please visit {  th: 'www.matichon.co.th/privacy-policy',  en: 'www.matichon.co.th/privacy-policy'}. For full guidelines, please visit  Thailand Data Protection Guideline 2.0, Ministry of Digital Economy and Society website http://www.mdes.go.th, GDPR Guideline https://gdpr.eu/, European Commission https://ec.europa.eu/.
        Note: There is no additional charge regarding the mentioned right entitlement, and users will be contacted within 30 days from the date of request 

        Personal Information Request Form
        In the case that you would like to manage your personal data, including:
        Data access request
        Data correction request
        General Consent Withdrawal request
        Complaint submission
        Please contact our Data Protection Officer through the following details in the "Contact Us" section down below.

        Personal Data and Marketing Activities
        To optimize users satisfaction, we may distribute the information regarding our marketing activities such as promotion, discount, and news. This also includes information related to your preferences. Users can withdraw their consents after the subscription contacting webadmin@matichon.co.th.

        What are Cookies?
        Cookies are text files stored on your computer's browser directory or program data subfolders. Cookies are created when you use your browser to visit a website that uses cookies to keep track of your movements within the site. If you wish to learn more about cookies, visit https://www.allaboutcookies.org/.

        How Do We Use Cookies?
        To increase the efficiency and safety in users log-in process
        To collect user’s platform usage, content adjustments, and personal settings
        To research an individual user’s behaviour on the platform for satisfaction optimization purposes
        To research the behavioral trend of all users in order to increase the efficiency of the platform

        Types of Cookies
        Functionality Cookies: Functionality cookies record information about choices you have made in the platform such as personal settings, languages, and fonts. This allows us to tailor our platform to you.
        Advertising Cookies: Advertising cookies record your on-site behaviour and history of sites visited. This allows us to provide you the services, products, and advertisements that suit your preferences.
        Strictly Necessary Cookies: These cookies are essential for you to browse the website and use its features, such as accessing secure areas of the site. 
        Performance Cookies: These are cookies used for gathering site visitors data anonymously, including the page that is visited most frequently in the website. This allows us to efficiently improve our platform regarding users preferences.
        Third-party Cookies: These cookies will be determined by the third parties such as Google Analytics

        Cookies Setting
        Users can disable cookies on their browsers, by following these steps:

        For Safari Users:
        Click the “Safari” menu, and tap “Preferences”.
        Click the “Privacy” tap and manage the following setting choices:
        Enabling  “Prevent cross-site tracking” to block third-party cookies and prevent third-party websites from storing data on your computer. 
        Enabling “Block all cookies” will prevent First-Party cookies, as well as Third-Party cookies. Enabling this may cause websites to work improperly, and Safari will warn you about this if you choose this option.
        Choose the “Manage Website Data” button to remove certain website domains which have stored data on your computer.

        For Google Chrome Users:
        Open Chrome and tap on “Settings” at the top right.
        Click “Advanced” at the bottom.
        Under 'Privacy and security', click “Site Settings”.
        Click “Cookies”, then tap “ See all Cookies and Site Data”.
        If you wish to remove your cookies, tap “Remove All”.

        For Internet Explorer Users:
        Open Internet Explorer and click “Tools” in the browser toolbar.
        Choose “Internet” Option.
        Click the “Privacy” tap.
        Under “Settings” move the slider to the top to block all cookies or to the bottom to allow all cookies, and then click OK.

        Effect of Notice and Changes to Notice

        This Notice applies in conjunction with any other notices, contractual clauses and consensual clauses that apply in relation to the collection, usage and disclosure of your personal data by us. Any links from other domains found on our site may be under a different personal data protection act.

        We may revise this notice from time to time without any prior notification. You may determine if any such revision has taken place by referring to the date on which this notice was last updated. Your continual use of our services constitutes your acknowledgement and acceptance of such changes.

        Effective date:
        13/05/2020
        Last updated:
        13/05/2020

        Contact Us
        Data Processor
        Name: Matichon Public Company Limited
        Address: 12 TESABANNARUMAN ROAD, LATYAO, CHATU CHAK Bangkok10900
        Contact detail: 025890020
        Email address: webadmin@matichon.co.th
        Website: www.matichon.co.th, www.khaosod.co.th, www.prachachat.net, www.sentangsedtee.com, www.technologychaoban.com, www.silpa-mag.com, www.matichonweekly.com, www.khaosodenglish.com, www.matichonacademy.com, www.matichonbook.com, www.matichonelibrary.com

        Data Protection Officer
        Name: Varodom Limpabandhu
        Address: 12 TESABANNARUMAN ROAD, LATYAO, CHATU CHAK Bangkok10900
        Contact detail: 025890020 extention 1308
        Email address: webadmin@matichon.co.th

        In the case that you find any of our staff members does not act in compliance with the aforementioned regulations, you can file a request or a complaint at the following organisation:
        Data Protection Committee, Ministry of Digital Economy and Society, Thailand
        Address: The Government Complex Commemorating His Majesty the King's 80th BirthDay Anniversary 5th December, B.E.2550 (2007), Building B 6th - 7th Floor 120 Chaengwattana Road, Lak Si Intersection, Bangkok, 10210
        Phone number: +662 142 2233

        Information Commissioner’s Office, United Kingdom
        Address: Wycliffe House, Water Lane, Wimslow, Cheshire SK9 5AF
        Phone number: +44303 123 1113




        Data Protection Notice for Customers



        Personal Data Processing
        The basis of our personal data processing is illustrated in (a) The Collection of Personal Data (b) Data Storage and Data Retention. The purposes of our data collection are as stated in (b) Data Collection Objectives. Our procedure are complied with the following standards:
        Contract execution including payment confirmation emails after the purchase of services provided by the company, bank account transfer, or any other agreements that you have executed with the company.
        Data Subject's Consent: Regarding your agreement during our service registration, you can withdraw your consent by following these steps:
        You can withdraw personal data disclosure consent, used in the mentioned objectives, at any circumstances. This includes the request of personal data removal and data anonymity change. To withdraw your consent, contact our data processors through following channels:
        Electronic channel such as email and website
        Verbal channel such as phone call or staff member
        Written channel such as information letter 

        Once our staff members have received your withdrawal submission, the request will be forwarded to our data processors. However, your consent withdrawal has no effect on the data processing of your previous consensual personal data.

        Exception of Personal Data Collection
        The service providers are unable to collect your personal data without your consent, except for the following purposes:
        To obtain data for public benefits such as scientific research and historical data.
        To assure critical benefits concerning the security of data subjects including physical health and safetiness.
        To act in compliance with the consensual agreement that the data subject is a contract party.
        To act in compliance with applicable laws, regulations, codes of practice, guidelines, or rules, or to assist in law enforcements and investigations conducted by any governmental or regulatory authority.
        For benefits legally obtained by service providers, data processors, and associated legal entities, except that the mentioned benefits are incompatible to basic data subjects right. 
        For public and legitimate interests of data processors.

        Access to Personal Data
        We access your personal data via following processes:
        When you log-in or register your account on our platform.
        When you submit registration forms, request forms, or any form associated with our service, both online and offline.
        When you make agreements or submit any information and file associated with our services.
        When you make contact with our service providers such as recorded phone calls, letters, faxes, face-to-face meetings, social media platforms, and emails.
        When you use our services on electronic platforms, including the use of cookies which will be adjusted once you have logged-in.
        When you process your transactions through our services.
        When you submit feedback or complaints.
        When you register or make any consensual agreement during our marketing and advertising campaigns such as contests and discounts. This includes the activities launched with our partners and associated third parties.
        When you request certain services provided by our on-site outsource service providers such as transaction and logistics services.
        When you visit or log-in the websites or applications of our partners and associated third parties.
        When you install our application on your devices such as mobile phones, computers, or tablets.
        When you connect your account with third party’s websites or applications.
        When you submit your personal information to us for any reason.

        Persona Data Storage and Retention
        Further details are clarified in the Data Protection Notice for Customer’s “Data Storage and Data Retention” section.


        Data Subject’s Right
        Further details are clarified in the Data Protection Notice for Customer’s “Data Subject’s Right” section.



        3rd Party Cookies

        ชื่อผู้ให้บริการ : Google Analytics
        วัตถุประสงค์ : เพื่อช่วยตัดสินใจการดำเนินแผนงานทางธุรกิจ และการปรับปรุงการให้บริการ
        - ข้อมูลแหล่งที่มาของผู้ใช้งาน เช่น ผู้ใช้เข้าสู่เว็บไซต์ จาก Google Search หรือ Social Network
        - ข้อมูลโฆษณา เช่น ผู้ใช้ที่กำหนดเป้าหมายโดยแคมเปญ AdWords
        - ข้อมูลพฤติกรรม เช่น ระยะเวลา, เวลา, หน้า landing page และการติดตามกิจกรรมอื่นๆ
        -  ข้อมูลเนื้อหา เช่น ผู้ใช้อ่านบทความเกี่ยวกับการเดินทางไปต่างจังหวัด
        -  ปฏิสัมพันธ์ทางสังคม เช่น ผู้ใช้แบ่งปันบทความบนเครือข่ายโซเชียล
        -  ข้อมูลส่วนบุคคลของผู้ใช้ Google Analytics สามารถดูในระดับภาพรวม แต่ไม่สามารถเจาะจงเป็นรายบุคคลได้ ช่น. เพศ, อายุ, สถานที่ตั้ง
        -  การทดสอบ A/B test สำหรับการปรับปรุงเว็บไซต์
        นโยบายความเป็นส่วนตัว  https://policies.google.com/privacy?hl=en
        ยกเลิกบริการ https://tools.google.com/dlpage/gaoptout/

        ชื่อผู้ให้บริการ : Taboola
        วัตถุประสงค์ : โฆษณา
        นโยบายความเป็นส่วนตัว  https://www.taboola.com/privacy-policy
        ยกเลิกบริการ https://www.taboola.com/privacy-policy

        ชื่อผู้ให้บริการ : Geniee
        วัตถุประสงค์ : โฆษณา
        นโยบายความเป็นส่วนตัว  https://en.geniee.co.jp/privacy/
        ยกเลิกบริการ https://en.geniee.co.jp/privacy/

        ชื่อผู้ให้บริการ : iZooto
        วัตถุประสงค์ : โฆษณา
        นโยบายความเป็นส่วนตัว  https://www.izooto.com/privacy-policy
        ยกเลิกบริการ https://www.izooto.com/privacy-policy

        ชื่อผู้ให้บริการ : AIS
        วัตถุประสงค์ : โฆษณา
        นโยบายความเป็นส่วนตัว  http://www.ais.co.th/cookiesPolicy/th/
        ยกเลิกบริการ http://www.ais.co.th/cookiesPolicy/th/

        ชื่อผู้ให้บริการ : MGID
        วัตถุประสงค์ : โฆษณา
        นโยบายความเป็นส่วนตัว  https://blog.mgid.com/2018/03/gdpr-goes-effect-mgid-goes-action/
        ยกเลิกบริการ https://blog.mgid.com/2018/03/gdpr-goes-effect-mgid-goes-action/

        ชื่อผู้ให้บริการ : Mediawayss
        วัตถุประสงค์ : โฆษณา
        นโยบายความเป็นส่วนตัว https://ssp.mox.tv/legal/privacy?policies=cookie
        ยกเลิกบริการ https://ssp.mox.tv/legal/privacy?policies=cookie

        ชื่อผู้ให้บริการ : Appier
        วัตถุประสงค์ : โฆษณา
        นโยบายความเป็นส่วนตัว  https://www.appier.com/privacy-policy/
        ยกเลิกบริการ https://www.appier.com/privacy-policy/

        <?php Page::end(); ?>
    </div>
</div>

