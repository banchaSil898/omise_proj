<?php

namespace app\commands;

use app\models\Folder;
use app\models\Member;
use app\models\MemberAddress;
use app\models\Product;
use app\models\ProductFolder;
use app\models\Publisher;
use app\models\Purchase;
use app\models\PurchaseProduct;
use Exception;
use Yii;
use yii\console\Controller;
use yii\db\Connection;
use yii\db\DataReader;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\imagine\Image;

class InstallController extends Controller {

    public function getRemoteConnection() {
        return new Connection([
            'dsn' => 'mysql:host=mtb-db;dbname=mic_book_mg',
            'username' => 'micbook',
            'password' => 'Mat1b00k#',
            'charset' => 'utf8',
        ]);
    }

    public function actionFull() {
        echo "Installatoin process start :\n";
        $this->clearCustomer();
        $this->clearPurchase();
        $this->clearReport();
        $this->clearLog();
        $this->clearProduct();
        $this->clearProductCategory();
        $this->clearPromotion();
        $this->prepareProduct();
        $this->prepareCustomer();
        $this->importProduct();
        $this->importProductCategory();
        $this->importProductStock();
        $this->importProductFlag();
        $this->importCustomer();
    }

    public function actionPrepare() {
        $this->prepareProduct();
        $this->prepareCustomer();
    }

    public function actionSync() {
        //$this->prepareProduct();
        //$this->importProduct();
        echo 'Go for customer';
        //$this->prepareCustomer();
        //$this->clearCustomer();
        $this->importCustomer(true);
    }

    public function actionUpdate() {
        $this->importProduct(true);
        $this->importProductCategory();
        $this->importProductStock();
        $this->importProductFlag();
        $this->importCustomer(true);
    }

    public function actionImportProduct() {
        $this->clearProduct();
        $this->clearProductCategory();
        $this->importProduct();
        $this->importProductCategory();
        $this->importProductStock();
        $this->importProductFlag();
    }

    public function actionTest() {
        $this->importProductStock();
    }

    public function actionCustomer() {
        $this->clearCustomer();
        $this->clearPurchase();
        $this->importCustomer();
    }

    public function getMagentoCustomerQuery($isUpdate = false) {
        $mg = $this->getRemoteConnection();
        $core = $mg->createCommand('SELECT * FROM eav_attribute WHERE entity_type_id = 1');
        $attributes = $core->queryAll();

        $sql = [];
        $selects = [];

        $tables = [
            'datetime' => '_datetime',
            'decimal' => '_decimal',
            'int' => '_int',
            'text' => '_text',
            'varchar' => '_varchar',
        ];

        $selects[] = 'p.entity_id';
        $selects[] = 'p.email';
        $selects[] = 'p.created_at';
        $selects[] = 'p.updated_at';
        $selects[] = 'p.is_active';
        foreach ($attributes as $attribute) {
            if ($attribute['backend_type'] !== 'static') {
                $alias = 'attr_' . $attribute['backend_type'];
                $attr_id = $attribute['attribute_id'];
                $attr_code = $attribute['attribute_code'];
                $selects[] = str_replace(['{alias}', '{attr_code}', '{attr_id}'], [$alias, $attr_code, $attr_id], 'MAX(CASE WHEN {alias}.attribute_id = {attr_id} THEN {alias}.value END) as {attr_code}');
            }
        }

        $query = (new Query());
        $query->select($selects)->from('customer_entity p');
        foreach ($tables as $type => $suffix) {
            $query->join('LEFT JOIN', str_replace(['{suffix}', '{type}'], [$suffix, $type], 'customer_entity{suffix} attr_{type}'), str_replace(['{suffix}', '{type}'], [$suffix, $type], 'attr_{type}.entity_id = p.entity_id'));
        }
        if ($isUpdate) {
            $oldIds = array_values(ArrayHelper::map(Member::find()->all(), 'magento_id', 'magento_id'));
            $query->andWhere(['NOT IN', 'p.entity_id', $oldIds]);
        }

        $query->groupBy([
            'p.entity_id'
        ]);

        return $query;
    }

    public function getMagentoCustomerAddressQuery($id, $isUpdate = false) {
        $mg = $this->getRemoteConnection();
        $core = $mg->createCommand('SELECT * FROM eav_attribute WHERE entity_type_id = 2');
        $attributes = $core->queryAll();

        $sql = [];
        $selects = [];

        $tables = [
            'datetime' => '_datetime',
            'decimal' => '_decimal',
            'int' => '_int',
            'text' => '_text',
            'varchar' => '_varchar',
        ];

        $selects[] = 'p.entity_id';
        $selects[] = 'p.created_at';
        $selects[] = 'p.updated_at';
        foreach ($attributes as $attribute) {
            if ($attribute['backend_type'] !== 'static') {
                $alias = 'attr_' . $attribute['backend_type'];
                $attr_id = $attribute['attribute_id'];
                $attr_code = $attribute['attribute_code'];
                $selects[] = str_replace(['{alias}', '{attr_code}', '{attr_id}'], [$alias, $attr_code, $attr_id], 'MAX(CASE WHEN {alias}.attribute_id = {attr_id} THEN {alias}.value END) as {attr_code}');
            }
        }

        $query = (new Query());
        $query->select($selects)->from('customer_address_entity p');
        foreach ($tables as $type => $suffix) {
            $query->join('LEFT JOIN', str_replace(['{suffix}', '{type}'], [$suffix, $type], 'customer_address_entity{suffix} attr_{type}'), str_replace(['{suffix}', '{type}'], [$suffix, $type], 'attr_{type}.entity_id = p.entity_id'));
        }
        if ($isUpdate) {
            $oldIds = array_values(ArrayHelper::map(Member::find()->all(), 'magento_id', 'magento_id'));
            $query->wandWhere(['NOT IN', 'p.entity_id', $oldIds]);
        }
        $query->andWhere(['p.entity_id' => $id]);
        $query->groupBy([
            'p.entity_id'
        ]);

        return $query;
    }

    public function getPrepareCustomerOrderQuery($magento_id, $mg) {
        $ret = [];
        $core = $mg->createCommand('SELECT * FROM sales_flat_order WHERE customer_id = :id')->bindValue(':id', $magento_id);
        $orders = $core->queryAll();

        foreach ($orders as $order) {
            $items = $mg->createCommand('SELECT * FROM sales_flat_order_item WHERE order_id = :id')->bindValue(':id', $order['entity_id'])->queryAll();
            $order['items'] = $items;
            $ret[] = $order;
        }
        return $ret;
    }

    public function getMagentoCustomerOrderQuery(Member $customer, $mg) {
        $core = $mg->createCommand('SELECT * FROM sales_flat_order WHERE state = "complete" AND status = "complete" AND customer_id = :id')->bindValue(':id', $customer->magento_id);
        $orders = $core->queryAll();

        foreach ($orders as $order) {
            $purchase = new Purchase;
            $purchase->purchase_type = Purchase::TYPE_MAGENTO;
            $purchase->purchase_no = $order['increment_id'];
            $purchase->member_id = $customer->id;
            $purchase->magento_id = $order['entity_id'];
            $purchase->buyer_firstname = $order['customer_firstname'];
            $purchase->buyer_lastname = $order['customer_lastname'];
            $purchase->buyer_email = $order['customer_email'];
            $purchase->status = Purchase::STATUS_DELIVERIED;
            $purchase->status_comment = 'จากระบบ Megento';
            $purchase->status_date = $order['updated_at'];
            $purchase->amount = $order['total_item_count'];
            $purchase->price_total = $order['subtotal'];
            $purchase->price_grand = $order['grand_total'];
            $purchase->is_paid = 1;
            $purchase->payment_method = Purchase::METHOD_MAGENTO;


            $purchase->delivery_weight = $order['weight'];
            $purchase->delivery_fee = $order['shipping_amount'];
            $purchase->magento_quote_id = $order['quote_id'];
            $purchase->magento_billing_address_id = $order['billing_address_id'];
            $purchase->magento_shipping_address_id = $order['shipping_address_id'];
            if ($purchase->save(false)) {
                $purchase->updateAttributes([
                    'created_at' => $order['created_at'],
                    'updated_at' => $order['updated_at'],
                ]);
                $items = $mg->createCommand('SELECT * FROM sales_flat_order_item WHERE order_id = :id')->bindValue(':id', $purchase->magento_id)->queryAll();
                foreach ($items as $item) {
                    $product = Product::findOne([
                                'magento_id' => $item['product_id'],
                    ]);
                    if (isset($product)) {
                        $pItem = new PurchaseProduct;
                        $pItem->purchase_id = $purchase->id;
                        $pItem->product_id = $product->id;
                        $pItem->price = $item['price'];
                        $pItem->price_total = $item['row_total'];
                        $pItem->weight = $item['weight'];
                        $pItem->weight_total = $item['row_weight'];
                        $pItem->amount = (int) $item['qty_ordered'];
                        $pItem->sku = $item['sku'];
                        $pItem->name = $item['name'];
                        $pItem->purchase_magento_id = $item['order_id'];
                        $pItem->product_magento_id = $item['product_id'];
                        $pItem->item_magento_id = $item['item_id'];
                        if (!$pItem->save()) {
                            var_dump($pItem->errors);
                            exit;
                        }
                    }
                }
            }
        }
    }

    public function prepareProduct() {
        echo 'Load product data from magento.' . "\n";
        $file = Yii::getAlias('@app/runtime/sync_product');
        @unlink($file);
        $query = $this->getMagentoProductQuery(true);
        $ret = [];
        foreach ($query as $count => $item) {
            $ret[] = $item;
            echo ($count + 1) . ' : ' . $item['name'] . "\n";
        }
        file_put_contents($file, Json::encode($ret));
        echo 'Done.' . "\n";
    }

    public function prepareCustomer() {
        echo 'Load customer data from magento.' . "\n";
        $file = Yii::getAlias('@app/runtime/sync_customer');
        @unlink($file);
        $mg = $this->getRemoteConnection();
        $query = $this->getMagentoCustomerQuery();
        $ret = [];
        foreach ($query->each(1000, $mg) as $count => $item) {
            if ($item['default_billing']) {
                $addr = $this->getMagentoCustomerAddressQuery($item['default_billing'])->one($mg);
                $addr['default_billing'] = true;
                if ($item['default_billing'] == $item['default_shipping']) {
                    $addr['default_shipping'] = true;
                }
                $item['addresses'][] = $addr;
            }

            if (isset($item['default_shipping']) && ($item['default_billing'] != $item['default_shipping'])) {
                $addr = $this->getMagentoCustomerAddressQuery($item['default_shipping'])->one($mg);
                $addr['default_shipping'] = true;
                $item['addresses'][] = $addr;
            }

            $item['orders'] = $this->getPrepareCustomerOrderQuery($item['entity_id'], $mg);
            $ret[] = $item;
            echo ($count + 1) . ' : ' . $item['email'] . "\n";
        }
        file_put_contents($file, Json::encode($ret));
        echo 'Done.' . "\n";
        echo "customer data (" . $count . ") .. imported\n";
    }

    public function actionTestCustomer() {
        
    }

    /**
     * 
     * @param bool $isUpdate
     * @return DataReader
     */
    public function getMagentoProductQuery($isUpdate = false) {
        $mg = $this->getRemoteConnection();
        $core = $mg->createCommand('SELECT * FROM eav_attribute WHERE entity_type_id = 4');
        $attributes = $core->queryAll();

        $sql = [];
        $select = [];
        $sync = [
            3238,
            3235,
            3219,
            3218,
            3217,
            3216,
            3215,
            3214,
            3213,
            3212,
            3211,
            3210,
            3209,
            3208,
            3207,
            3206,
            3166,
            2905,
            2904,
            2903,
            2902,
            2901,
            2900,
            2899,
            2898,
            2787,
            2776,
            2775,
            2774,
            2773,
            2772,
            2640,
            2639,
            2635,
            2634,
            2633,
            2537,
            2496,
            2495,
            2494,
            2493,
            2492,
            2388,
            1923,
            1909,
            1334,
            1201,
        ];
        $tables = [
            'datetime' => '_datetime',
            'decimal' => '_decimal',
            'gallery' => '_gallery',
            'int' => '_int',
            'media_gallery' => '_media_gallery',
            'text' => '_text',
            'varchar' => '_varchar',
        ];
        $joins[] = 'SELECT p.entity_id, p.sku, p.created_at, p.updated_at, {select} FROM catalog_product_entity p';
        foreach ($tables as $type => $suffix) {
            $joins[] = str_replace(['{suffix}', '{type}'], [$suffix, $type], 'LEFT JOIN catalog_product_entity{suffix} attr_{type} ON attr_{type}.entity_id = p.entity_id');
        }

        if ($isUpdate) {
            //$oldIds = array_values(ArrayHelper::map(Product::find()->all(), 'magento_id', 'magento_id'));
            $joins[] = 'WHERE p.entity_id IN (' . implode(', ', $sync) . ')';
        }

        foreach ($attributes as $attribute) {
            if ($attribute['backend_type'] !== 'static') {
                $alias = 'attr_' . $attribute['backend_type'];
                $attr_id = $attribute['attribute_id'];
                $attr_code = $attribute['attribute_code'];
                $select[] = str_replace(['{alias}', '{attr_code}', '{attr_id}'], [$alias, $attr_code, $attr_id], 'MAX(CASE WHEN {alias}.attribute_id = {attr_id} THEN {alias}.value END) as {attr_code}');
            }
        }
        $sql = str_replace('{select}', implode(',', $select), implode(' ', $joins)) . ' GROUP BY p.entity_id';
        $cmd = $mg->createCommand($sql);
        echo '====== SQL to execute ==== ' . "\n";
        echo $cmd->sql . "\n";
        echo '==================== ' . "\n";
        return $cmd->query();
    }

    public function importCustomer($isUpdate = false) {

        $products = Product::find()->indexBy('magento_id')->all();
        echo 'Load data from prepared data.' . "\n";
        $file = Yii::getAlias('@app/runtime/sync_customer');
        $data = Json::decode(file_get_contents($file));

        $count = 0;
        foreach ($data as $item) {
            if ($isUpdate) {
                $model = Member::findOne([
                            'magento_id' => $item['entity_id'],
                ]);
                if (isset($model)) {
                    continue;
                }
            }
            $model = new Member;
            $model->username = $item['email'];
            $model->secret = rand(1000, 9999) . '@%$%!$@$&' . rand(1000, 9999);
            $model->firstname = $item['firstname'];
            $model->lastname = $item['lastname'];
            $model->name = $model->firstname . ' ' . $model->lastname;
            $model->magento_id = $item['entity_id'];
            $model->birth_date = $item['dob'] == '0000-00-00' ? date('Y-m-d', strtotime($item['dob'])) : null;
            $model->default_addr_billing = $item['default_billing'];
            $model->default_addr_shipping = $item['default_shipping'];
            $model->status = Member::STATUS_NORMAL;
            if ($model->save(false)) {
                $model->updateAttributes([
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at'],
                ]);
                $addrCount = 0;
                if (isset($item['addresses'])) {
                    foreach ($item['addresses'] as $addr) {
                        $addrModel = new MemberAddress;
                        $addrModel->address_id = $addr['entity_id'];
                        $addrModel->member_id = $model->id;
                        $addrModel->created_at = $addr['created_at'];
                        $addrModel->updated_at = $addr['updated_at'];
                        $addrModel->amphur = $addr['city'];
                        $addrModel->company_name = $addr['company'];
                        $addrModel->country_id = $addr['country_id'];
                        $addrModel->phone = $addr['telephone'];
                        $addrModel->firstname = $addr['firstname'];
                        $addrModel->lastname = $addr['lastname'];
                        $addrModel->postcode = $addr['postcode'];
                        $addrModel->province = $addr['region'];
                        $addrModel->street = $addr['street'];
                        $addrModel->address = $addr['street'];
                        $addrModel->magento_id = $addr['entity_id'];
                        if ($addrModel->save(false)) {
                            $addrCount ++;
                        }
                    }
                }

                if (isset($item['orders'])) {
                    foreach ($item['orders'] as $order) {
                        $purchase = new Purchase;
                        $purchase->purchase_type = Purchase::TYPE_MAGENTO;
                        $purchase->purchase_no = $order['increment_id'];
                        $purchase->member_id = $model->id;
                        $purchase->magento_id = $order['entity_id'];
                        $purchase->buyer_firstname = $order['customer_firstname'];
                        $purchase->buyer_lastname = $order['customer_lastname'];
                        $purchase->buyer_email = $order['customer_email'];

                        if ($order['state'] == 'canceled' && $order['status'] == 'canceled') {
                            $purchase->status = Purchase::STATUS_CANCELLED;
                            $purchase->status_comment = 'จากระบบ Megento';
                            $purchase->is_paid = 0;
                            $purchase->payment_method = Purchase::METHOD_MAGENTO;
                        } else if ($order['state'] == 'complete' && $order['status'] == 'complete') {
                            $purchase->status = Purchase::STATUS_DELIVERIED;
                            $purchase->status_comment = 'จากระบบ Megento';
                            $purchase->is_paid = 1;
                            $purchase->payment_method = Purchase::METHOD_MAGENTO;
                        } else if ($order['state'] == 'holded' && $order['status'] == 'holded') {
                            $purchase->status = Purchase::STATUS_DELIVERIED;
                            $purchase->status_comment = 'จากระบบ Megento';
                            $purchase->is_paid = 0;
                            $purchase->payment_method = Purchase::METHOD_MAGENTO;
                        } else if ($order['state'] == 'new' && $order['status'] == 'pending') {
                            $purchase->status = Purchase::STATUS_NEW;
                            $purchase->status_comment = 'จากระบบ Megento';
                            $purchase->is_paid = 0;
                            $purchase->payment_method = Purchase::METHOD_MAGENTO;
                        } else if ($order['state'] == 'new' && $order['status'] == 'processing') {
                            $purchase->status = Purchase::STATUS_TRANSFER_CHECK;
                            $purchase->status_comment = 'จากระบบ Megento';
                            $purchase->is_paid = 0;
                            $purchase->payment_method = Purchase::METHOD_MAGENTO;
                        } else if ($order['state'] == 'processing' && $order['status'] == 'processing') {
                            $purchase->status = Purchase::STATUS_PAID;
                            $purchase->status_comment = 'จากระบบ Megento';
                            $purchase->is_paid = 1;
                            $purchase->payment_method = Purchase::METHOD_MAGENTO;
                        }
                        $purchase->status_date = $order['updated_at'];
                        $purchase->amount = $order['total_item_count'];
                        $purchase->price_total = $order['subtotal'];
                        $purchase->price_grand = $order['grand_total'];

                        $purchase->delivery_weight = $order['weight'];
                        $purchase->delivery_fee = $order['shipping_amount'];
                        $purchase->magento_quote_id = $order['quote_id'];
                        $purchase->magento_billing_address_id = $order['billing_address_id'];
                        $purchase->magento_shipping_address_id = $order['shipping_address_id'];
                        if ($purchase->save(false)) {
                            $purchase->updateAttributes([
                                'created_at' => $order['created_at'],
                                'updated_at' => $order['updated_at'],
                            ]);

                            if (isset($order['items'])) {
                                foreach ($order['items'] as $item) {
                                    if (isset($products[$item['product_id']])) {
                                        $product = $products[$item['product_id']];
                                        $pItem = new PurchaseProduct;
                                        $pItem->purchase_id = $purchase->id;
                                        $pItem->product_id = $product->id;
                                        $pItem->price = $item['price'];
                                        $pItem->price_total = $item['row_total'];
                                        $pItem->weight = $item['weight'];
                                        $pItem->weight_total = $item['row_weight'];
                                        $pItem->amount = (int) $item['qty_ordered'];
                                        $pItem->sku = $item['sku'];
                                        $pItem->name = $item['name'];
                                        $pItem->purchase_magento_id = $item['order_id'];
                                        $pItem->product_magento_id = $item['product_id'];
                                        $pItem->item_magento_id = $item['item_id'];
                                        if (!$pItem->save(false)) {
                                            var_dump($pItem->errors);
                                            exit;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            echo ($count + 1) . " : " . date('Y-m-d H:i:s') . " : " . $model->username . " ... with " . $addrCount . " addresses ... saved.\n";
            $count++;
        }
        echo "customer data (" . $count . ") .. imported\n";
    }

    public function importProduct($isUpdate = false) {
        Yii::setAlias('@webroot', dirname(__FILE__) . '/../web');

        echo 'Load data from prepared data.' . "\n";
        $file = Yii::getAlias('@app/runtime/sync_product');
        $data = Json::decode(file_get_contents($file));
        $count = 0;

        $sync = [
            3238,
            3235,
            3222,
            3219,
            3218,
            3217,
            3216,
            3215,
            3214,
            3213,
            3212,
            3211,
            3210,
            3209,
            3208,
            3207,
            3206,
            3166,
            2905,
            2904,
            2903,
            2902,
            2901,
            2900,
            2899,
            2898,
            2787,
            2776,
            2775,
            2774,
            2773,
            2772,
            2640,
            2639,
            2635,
            2634,
            2633,
            2537,
            2496,
            2495,
            2494,
            2493,
            2492,
            2388,
            1923,
            1909,
            1334,
            1201,
        ];

        foreach ($data as $item) {
            if ($isUpdate) {
                $model = Product::findOne([
                            'magento_id' => $item['entity_id'],
                ]);
                if (isset($model)) {
                    continue;
                }
            }
            if (!in_array($item['entity_id'], $sync)) {
                continue;
            }
            $model = new Product;
            $model->name = $item['name'];
            $model->sku = $item['sku'];
            $model->product_type = Product::TYPE_GENERAL;
            $model->description = $item['description'];
            $model->brief = $item['short_description'];
            $model->price = $item['price'];
            $model->price_sell = $item['special_price'];
            $model->info_weight = is_numeric($item['weight']) ? $item['weight'] : null;
            $model->info_page = is_numeric($item['page']) ? (int) $item['page'] : null;
            $model->is_hide = $item['status'] == 1 ? 0 : 1;
            $model->slug = $item['url_key'];
            $model->info_author = explode(',', $item['writer']);
            $model->info_compiled = explode(',', $item['compose']);
            $model->info_translate = explode(',', $item['translator']);
            $model->info_translator = $item['translator'];
            $model->info_publish = $item['print'];
            $model->info_paper = $item['paper'];

            $dimensions = explode('x', str_replace(['cm.'], [''], $item['size']));
            $model->info_width = isset($dimensions[0]) ? trim($dimensions[0]) : null;
            $model->info_height = isset($dimensions[1]) ? trim($dimensions[1]) : null;
            $model->info_depth = isset($dimensions[2]) ? trim($dimensions[2]) : null;

            $model->info_width = is_numeric($model->info_width) ? $model->info_width : null;
            $model->info_height = is_numeric($model->info_height) ? $model->info_height : null;
            $model->info_depth = is_numeric($model->info_depth) ? $model->info_depth : null;

            $model->isbn = $item['isbn'];
            $model->info_cover = $item['cover'];

            $model->magento_id = $item['entity_id'];
            if ($model->save()) {
                if ($item['publisher']) {
                    $publisher = Publisher::findOne([
                                'name' => trim($item['publisher']),
                    ]);
                    if (!isset($publisher)) {
                        $publisher = new Publisher;
                        $publisher->name = trim($item['publisher']);
                        $publisher->save();
                    }
                    $model->updateAttributes([
                        'info_publisher' => $publisher->name,
                        'publisher_id' => $publisher->id,
                        'publisher_name' => $publisher->name,
                    ]);
                }

                if ($item['thumbnail']) {
                    try {
                        $url = 'http://www2.matichonbook.com/media/catalog/product' . $item['image'];
                        Image::thumbnail($url, $model->cover_width, $model->cover_height)
                                ->save(Yii::getAlias('@webroot/uploads/products/') . $model->id . '.jpg');
                        Image::thumbnail($url, $model->thumb_width, $model->thumb_height)
                                ->save(Yii::getAlias('@webroot/uploads/products/') . $model->id . '_thumb.jpg');
                        $model->updateAttributes([
                            'cover_url' => '@web/uploads/products/' . $model->id . '.jpg',
                            'thumb_url' => '@web/uploads/products/' . $model->id . '_thumb.jpg',
                        ]);
                    } catch (Exception $e) {
                        $url = Yii::getAlias('@webroot/images/web/book.jpg');
                        Image::thumbnail($url, $model->cover_width, $model->cover_height)
                                ->save(Yii::getAlias('@webroot/uploads/products/') . $model->id . '.jpg');
                        Image::thumbnail($url, $model->thumb_width, $model->thumb_height)
                                ->save(Yii::getAlias('@webroot/uploads/products/') . $model->id . '_thumb.jpg');
                        $model->updateAttributes([
                            'cover_url' => '@web/uploads/products/' . $model->id . '.jpg',
                            'thumb_url' => '@web/uploads/products/' . $model->id . '_thumb.jpg',
                        ]);
                    }
                }
                $model->updateAttributes([
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at'],
                ]);
                echo ($count + 1) . " : " . date('Y-m-d H:i:s') . " : " . $model->name . " ... saved.\n";
                $count++;
            } else {
                var_dump($model->errors, $model->attributes);
                exit;
            }
        }
        echo "product data (" . $count . ") .. imported\n";
    }

    public function importProductCategory() {
        $conn = $this->getRemoteConnection();
        $folders = Folder::find()->all();
        $count = 0;
        foreach ($folders as $folder) {
            $query = $conn->createCommand('SELECT * FROM catalog_category_product WHERE category_id = :category_id')->bindValue(':category_id', $folder->id)->query();
            foreach ($query as $product) {
                $p = Product::findOne([
                            'magento_id' => $product['product_id'],
                ]);
                if (isset($p)) {
                    $m = new ProductFolder;
                    $m->folder_id = $folder->id;
                    $m->product_id = $p->id;
                    $m->save();
                    $count++;
                }
            }
        }
        echo "category data (" . $count . ") .. imported\n";
    }

    public function importProductStock() {
        $conn = $this->getRemoteConnection();
        $query = $conn->createCommand('SELECT * FROM cataloginventory_stock_item WHERE stock_id = 1')->query();
        $count = 0;
        foreach ($query as $product) {
            $p = Product::findOne([
                        'magento_id' => $product['product_id'],
            ]);
            if (isset($p)) {
                $p->updateAttributes([
                    'stock' => $product['qty'],
                    'is_hide' => $product['qty'] == 0 ? 1 : 0,
                ]);
                echo ($count + 1) . " : " . date('Y-m-d H:i:s') . " : " . $p->name . " ... -- set stock to -- " . $product['qty'] . "saved.\n";
                $count++;
            }
        }
        echo "stock data (" . $count . ") .. imported\n";
    }

    public function importProductFlag() {
        $conn = $this->getRemoteConnection();

        $query = $conn->createCommand('SELECT * FROM catalog_category_product WHERE category_id = 164')->query();
        $count = 0;
        foreach ($query as $product) {
            $p = Product::findOne([
                        'magento_id' => $product['product_id'],
            ]);
            if (isset($p)) {
                $p->updateAttributes([
                    'is_new' => 1
                ]);
                echo ($count + 1) . " : " . date('Y-m-d H:i:s') . " : " . $p->name . "saved.\n";
                $count++;
            }
        }
        echo "new data (" . $count . ") .. imported\n";

        $query = $conn->createCommand('SELECT * FROM catalog_category_product WHERE category_id = 165')->query();
        $count = 0;
        foreach ($query as $product) {
            $p = Product::findOne([
                        'magento_id' => $product['product_id'],
            ]);
            if (isset($p)) {
                $p->updateAttributes([
                    'is_recommended' => 1
                ]);
                echo ($count + 1) . " : " . date('Y-m-d H:i:s') . " : " . $p->name . "saved.\n";
                $count++;
            }
        }
        echo "recommended data (" . $count . ") .. imported\n";

        $query = $conn->createCommand('SELECT * FROM catalog_category_product WHERE category_id = 239')->query();
        $count = 0;
        foreach ($query as $product) {
            $p = Product::findOne([
                        'magento_id' => $product['product_id'],
            ]);
            if (isset($p)) {
                $p->updateAttributes([
                    'is_bestseller' => 1
                ]);
                echo ($count + 1) . " : " . date('Y-m-d H:i:s') . " : " . $p->name . "saved.\n";
                $count++;
            }
        }
        echo "bestseller data (" . $count . ") .. imported\n";
    }

    public function clearCustomer() {
        $db = Yii::$app->db;
        $db->createCommand('SET foreign_key_checks = 0')->execute();
        $db->createCommand('TRUNCATE TABLE member')->execute();
        $db->createCommand('TRUNCATE TABLE member_address')->execute();
        $db->createCommand('TRUNCATE TABLE purchase')->execute();
        $db->createCommand('TRUNCATE TABLE purchase_month')->execute();
        $db->createCommand('TRUNCATE TABLE purchase_product')->execute();
        $db->createCommand('TRUNCATE TABLE purchase_status')->execute();
        $db->createCommand('TRUNCATE TABLE report_sell_daily')->execute();
        $db->createCommand('TRUNCATE TABLE report_sell_monthly')->execute();
        $db->createCommand('SET foreign_key_checks = 1')->execute();
        echo "customer data .. cleared\n";
    }

    public function clearPurchase() {
        $db = Yii::$app->db;
        $db->createCommand('SET foreign_key_checks = 0')->execute();
        $db->createCommand('TRUNCATE TABLE purchase')->execute();
        $db->createCommand('TRUNCATE TABLE purchase_month')->execute();
        $db->createCommand('TRUNCATE TABLE purchase_product')->execute();
        $db->createCommand('TRUNCATE TABLE purchase_status')->execute();
        $db->createCommand('SET foreign_key_checks = 1')->execute();
        echo "purchase data .. cleared\n";
    }

    public function clearReport() {
        $db = Yii::$app->db;
        $db->createCommand('SET foreign_key_checks = 0')->execute();
        $db->createCommand('TRUNCATE TABLE report_sell_daily')->execute();
        $db->createCommand('TRUNCATE TABLE report_sell_monthly')->execute();
        $db->createCommand('SET foreign_key_checks = 1')->execute();
        echo "report data .. cleared\n";
    }

    public function clearLog() {
        $db = Yii::$app->db;
        $db->createCommand('SET foreign_key_checks = 0')->execute();
        $db->createCommand('TRUNCATE TABLE log_search')->execute();
        $db->createCommand('TRUNCATE TABLE log_search_keyword')->execute();
        $db->createCommand('SET foreign_key_checks = 1')->execute();
        echo "log data .. cleared\n";
    }

    public function clearProduct() {
        $db = Yii::$app->db;
        $db->createCommand('SET foreign_key_checks = 0')->execute();
        $db->createCommand('TRUNCATE TABLE product')->execute();
        $db->createCommand('TRUNCATE TABLE product_author')->execute();
        $db->createCommand('TRUNCATE TABLE product_image')->execute();
        $db->createCommand('TRUNCATE TABLE product_relate')->execute();
        $db->createCommand('TRUNCATE TABLE product_stock')->execute();
        $db->createCommand('TRUNCATE TABLE product_tag')->execute();
        $db->createCommand('TRUNCATE TABLE publisher')->execute();
        $db->createCommand('SET foreign_key_checks = 1')->execute();
        echo "product data .. cleared\n";
    }

    public function clearProductCategory() {
        $db = Yii::$app->db;
        $db->createCommand('TRUNCATE TABLE product_folder')->execute();
        echo "product category data .. cleared\n";
    }

    public function clearPromotion() {
        $db = Yii::$app->db;
        $db->createCommand('SET foreign_key_checks = 0')->execute();
        $db->createCommand('TRUNCATE TABLE discount')->execute();
        $db->createCommand('TRUNCATE TABLE discount_folder')->execute();
        $db->createCommand('TRUNCATE TABLE gift')->execute();
        $db->createCommand('TRUNCATE TABLE promotion')->execute();
        $db->createCommand('TRUNCATE TABLE promotion_buffet')->execute();
        $db->createCommand('TRUNCATE TABLE promotion_exclude')->execute();
        $db->createCommand('TRUNCATE TABLE promotion_folder')->execute();
        $db->createCommand('TRUNCATE TABLE promotion_gift')->execute();
        $db->createCommand('TRUNCATE TABLE promotion_gift_item')->execute();
        $db->createCommand('SET foreign_key_checks = 1')->execute();
        echo "promotion data .. cleared\n";
    }

}
