<?php

namespace app\models;

use app\components\Helper;
use app\models\base\Product as BaseProduct;
use app\modules\admin\components\Html;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Product extends BaseProduct {

    const TYPE_GENERAL = 0;
    const TYPE_BUNDLE = 1;
    const TYPE_FOLDER = 2;

    public $image_url;
    public $ebook_file;
    public $search;
    public $thumb_width = 193;
    public $thumb_height = 261;
    public $cover_width = 386;
    public $cover_height = 522;
    private $_price;

    public static function find() {
        return Yii::createObject(ProductQuery::className(), [get_called_class()]);
    }

    public static function getEbookUploadUrl() {
        return Yii::getAlias('@web/uploads/ebooks');
    }

    public static function getEbookUploadPath() {
        return Yii::getAlias('@webroot/uploads/ebooks');
    }

    public static function getProductTypeOptions($code = null) {
        $ret = [
            self::TYPE_GENERAL => 'สินค้าทั่วไป',
            self::TYPE_BUNDLE => 'ชุดรวมสินค้า',
            self::TYPE_FOLDER => 'กลุ่มสินค้า',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if (!$this->meta_title) {
            $this->meta_title = $this->name;
        }
        if (!$this->description) {
            $this->meta_description = $this->brief;
        }
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'product_type' => 'ประเภทสินค้า',
            'name' => 'ชื่อสินค้า',
            'stock' => 'คงเหลือ',
            'stock_est' => 'เหลือ (รวมจอง)',
            'price' => 'ราคา',
            'price_sell' => 'ราคาขาย',
            'cover_url' => 'รูปปก',
            'image_url' => 'รูปประกอบ',
            'writer_id' => 'ผู้แต่ง',
            'publisher_id' => 'จัดพิมพ์โดย',
            'info_author' => 'ผู้แต่ง',
            'sku' => 'SKU',
            'info_page' => 'จำนวนหน้า',
            'info_compiled' => 'เรียบเรียง',
            'info_translate' => 'ผู้แปล',
            'info_cover' => 'ปกหนังสือ',
            'info_publish' => 'พิมพ์ครั้งที่',
            'info_publisher' => 'สำนักพิมพ์',
            'publisher_name' => 'จัดพิมพ์โดย',
            'info_paper' => 'กระดาษ',
            'brief' => 'คำอธิบาย',
            'description' => 'รายละเอียด',
            'isbn' => 'ISBN',
            'info_width' => 'กว้าง',
            'info_height' => 'สูง',
            'info_depth' => 'หนา',
            'info_weight' => 'น้ำหนัก',
            'is_new' => 'ใหม่',
            'is_recommended' => 'แนะนำ',
            'is_bestseller' => 'ขายดี',
            'is_promotion' => 'โปรโมชั่น',
            'is_hide' => 'แสดง',
            'ebook_file' => 'ไฟล์ PDF',
            'meta_title' => 'Title',
            'meta_description' => 'Description',
            'meta_keywords' => 'Keywords',
            'free_shipping' => 'ไม่คิดค่าจัดส่ง',
            'is_out_of_stock' => 'สินค้าหมดสต๊อก',
            'is_own' => 'เครือมติชน',
            'is_pin' => 'แสดงเป็นลำดับต้น',
            'is_delivery_std' => 'มีจัดส่งแบบธรรมดา',
            'delivery_std_cost' => 'ค่าจัดส่งธรรมดา',
            'delivery_register_cost' => 'ค่าจัดส่งลงทะเบียน',
        ]);
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['product_type'], 'required'];

        $rules[] = ['delivery_std_cost', 'required', 'when' => function($m) {
                return boolval($m->is_delivery_std);
            }];

        // Update Cover
        $rules[] = [['cover_url'], 'required', 'on' => 'update-photo'];
        $rules[] = [['image_url'], 'required', 'on' => 'update-image'];


        // Update Price
        $rules[] = [['price', 'price_sell'], 'required', 'on' => 'update-price'];

        // Search
        $rules[] = ['search', 'safe', 'on' => 'search'];
        return $rules;
    }

    public function getIsDeliveryStd() {
        return boolval($this->is_delivery_std);
    }

    public function getValidateDimension() {
        $ret = [];
        $tmpScenario = $this->scenario;
        foreach ($this->scenarios() as $scenario => $attributes) {
            $this->scenario = $scenario;
            $ret[$scenario] = $this->validate();
        }
        $this->scenario = $tmpScenario;
        return $ret;
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['update-price'] = ['price', 'price_sell'];
        $scenarios['update-ebook'] = ['ebook_name'];
        $scenarios['update-meta'] = ['meta_keywords', 'meta_description'];
        $scenarios['update-cover'] = ['cover_url'];
        $scenarios['update-image'] = ['image_url'];
        return $scenarios;
    }

    public function search() {
        $query = $this->find();
        $query->andFilterCompare('name', $this->name);
        $query->andFilterCompare('publisher_id', $this->publisher_id);
        $query->andFilterCompare('is_new', $this->is_new);
        $query->andFilterCompare('is_bestseller', $this->is_bestseller);
        $query->andFilterCompare('is_recommended', $this->is_recommended);
        $query->andFilterCompare('is_hide', $this->is_hide);
        $query->andFilterCompare('is_own', $this->is_own);
        $query->andFilterCompare('is_out_of_stock', $this->is_out_of_stock);
        $query->andFilterCompare('is_pin', $this->is_pin);
        $query->andFilterCompare('is_promotion', $this->is_promotion);
        $query->andFilterWhere(['LIKE', 'info_publisher', $this->info_publisher]);
        $query->andWhere([
            'is_deleted' => 0,
        ]);
        if (isset($this->search['folder_id']) && ($this->search['folder_id'])) {
            $folders = [];
            $folder = Folder::findOne([
                        'id' => $this->search['folder_id'],
            ]);
            $folders[] = $folder->id;
            $parent = $folder->parent;
            while ($parent) {
                if ($parent->level < 3) {
                    break;
                }
                $folders[] = $parent->id;
                $parent = $parent->parent;
            }
            $query->joinWith(['productFolders']);
            $query->andFilterWhere(['product_folder.folder_id' => $folders]);
        }

        if (isset($this->search['only_folder_id'])) {
            $query->joinWith(['productFolders']);
            $query->andFilterWhere(['product_folder.folder_id' => $this->search['only_folder_id']]);
        }

        if (isset($this->search['text'])) {
            $words = $this->search['text'];
            $converts = ArrayHelper::map(WordConvert::find()->all(), 'word_from', 'word_to');
            $words = str_replace(array_keys($converts), array_values($converts), $words);

            $query->andWhere([
                'OR',
                [
                    'OR',
                    ['LIKE', 'product.name', $words],
                    [
                        'OR',
                        ['LIKE', 'info_translate', $words],
                        ['LIKE', 'publisher_name', $words]
                    ],
                ],
                [
                    'OR',
                    ['LIKE', 'isbn', $words],
                    [
                        'OR',
                        ['LIKE', 'info_author', $words],
                        ['LIKE', 'info_compiled', $words],
                    ],
                ]
            ]);
        }

        if (isset($this->search['condition'])) {
            switch ($this->search['condition']) {
                case 'outofstock':
                    $query->andWhere(['stock' => 0]);
                    break;
                case 'lowstock':
                    $query->andWhere(['<=', 'stock', 10]);
                    $query->andWhere(['>', 'stock', 0]);
                    break;
                case 'nofolder':
                    $query->joinWith('folders');
                    $query->andWhere('folder.id IS NULL');
                    $query->groupBy('product.id');
                    break;
            }
        }

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'is_pin' => SORT_DESC,
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);
    }

    public function getAccountWishlists() {
        return $this->hasMany(AccountWishlist::className(), ['product_id' => 'id']);
    }

    public function getImageUrl() {
        return Yii::getAlias($this->cover_url);
    }

    public function getCoverUrl() {
        return Yii::getAlias($this->cover_url);
    }

    public function getThumbUrl() {
        return Yii::getAlias($this->thumb_url);
    }

    public function getSeoUrl($schema = false) {
        return Url::to(['p/' . $this->id . '/' . Helper::slug($this->name) . '.html'], $schema);
    }

    public function getInfoAuthor() {
        return 'xxxxx';
    }

    public function getCurrentPromotions() {
        $today = date('Y-m-d');
        $categories = array_values(ArrayHelper::map($this->productFolders, 'folder_id', 'folder_id'));

        $query = Promotion::find();
        $query->joinWith('promotionFolders');
        $query->andWhere(['<=', 'DATE(date_start)', $today]);
        $query->andWhere(['>=', 'DATE(date_end)', $today]);
        $query->andWhere(['folder_id' => $categories]);
        $query->andWhere(['is_active' => true]);
        $query->groupBy([
            'id',
        ]);

        return $query->all();
    }

    public function getIsCartable() {
        if ($this->product_type == self::TYPE_FOLDER) {
            return false;
        }
        return true;
    }

    public function getCurrentPrice() {
        if (!isset($this->_price)) {
            $price = $this->price;
            $promotions = Promotion::find()->active()->beforeCart()->orderBy(['order_no' => SORT_ASC])->all();
            if (count($promotions)) {
                foreach ($promotions as $promotion) {
                    $manager = $promotion->promotionManager;
                    $manager->product_id = $this->id;
                    if ($manager->isValid()) {
                        $price = $manager->processPrice($price);
                    }
                }
            }
            $this->_price = ($price < 0 || ($price == $this->price )) ? $this->price_sell : $price;
        }
        return $this->_price;
    }

    public function getEbookUrl() {
        if ($this->ebook_name) {
            return self::getEbookUploadUrl() . DIRECTORY_SEPARATOR . $this->ebook_name;
        }
        return false;
    }

    public function saveCategoryItems($items) {
        $this->clearCategoeryItems();
        $data = [];
        foreach ($items as $item) {
            $folder = Folder::findOne($item);
            $data[] = [
                $this->id,
                $folder->id,
            ];
        }
        if (count($data)) {
            return $this->getDb()->createCommand()->batchInsert('product_folder', ['product_id', 'folder_id'], $data)->execute();
        }
        return false;
    }

    public function clearCategoeryItems() {
        ProductFolder::deleteAll([
            'product_id' => $this->id,
        ]);
    }

    public function getProductBundles() {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->via('productBundleItems');
    }

    public function getCategoryItems() {
        return $this->hasMany(ProductCategory::className(), ['product_id' => 'id']);
    }

    public function getProductFolders() {
        return $this->hasMany(ProductFolder::className(), ['product_id' => 'id']);
    }

    public function afterFind() {
        parent::afterFind();
        $this->info_author = $this->info_author === '' ? [] : explode(',', $this->info_author);
        $this->info_compiled = $this->info_compiled === '' ? [] : explode(',', $this->info_compiled);
        $this->info_translate = $this->info_translate === '' ? [] : explode(',', $this->info_translate);
        $this->meta_keywords = $this->meta_keywords === '' ? [] : explode(',', $this->meta_keywords);
    }

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            $this->info_author = $this->info_author ? implode(',', (array) $this->info_author) : '';
            $this->info_compiled = $this->info_compiled ? implode(',', (array) $this->info_compiled) : '';
            $this->info_translate = $this->info_translate ? implode(',', (array) $this->info_translate) : '';
            $this->meta_keywords = $this->meta_keywords ? implode(',', (array) $this->meta_keywords) : '';
            return true;
        }
    }

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            if ($this->getPurchaseProducts()->count()) {
                $this->updateAttributes([
                    'is_deleted' => 1,
                ]);
                return false;
            } else {
                ProductAuthor::deleteAll([
                    'product_id' => $this->id,
                ]);
                ProductBundleItem::deleteAll([
                    'bundle_id' => $this->id,
                ]);
                ProductFolder::deleteAll([
                    'product_id' => $this->id,
                ]);
                ProductImage::deleteAll([
                    'product_id' => $this->id,
                ]);
                ProductRelate::deleteAll([
                    'product_id' => $this->id,
                ]);
                ProductRelate::deleteAll([
                    'relate_id' => $this->id,
                ]);
                ProductStock::deleteAll([
                    'product_id' => $this->id,
                ]);
                ProductTag::deleteAll([
                    'product_id' => $this->id,
                ]);
                PromotionItem::deleteAll([
                    'product_id' => $this->id,
                ]);
                PromotionProduct::deleteAll([
                    'product_id' => $this->id,
                ]);
                ProductAddon::deleteAll([
                    'product_id' => $this->id,
                ]);
                SlideProduct::deleteAll([
                    'product_id' => $this->id,
                ]);
                return true;
            }
        }
    }

    public function getIsDeletable() {
        $model = PurchaseProduct::findOne([
                    'product_id' => $this->id,
        ]);
        if (isset($model)) {
            return false;
        }
        return true;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if (isset($this->publisher_name)) {
                $publisher = Publisher::findOne([
                            'name' => $this->publisher_name,
                ]);
                if (!isset($publisher)) {
                    $publisher = new Publisher;
                    $publisher->name = $this->publisher_name;
                    $publisher->is_hide = 0;
                    $publisher->is_recommended = 0;
                    $publisher->is_own = 0;
                    $publisher->save();
                }
                $this->info_publisher = $publisher->name;
                $this->publisher_id = $publisher->id;
                $this->is_own = $publisher->is_own;
            }
            return true;
        }
    }

    public function getIsBundle() {
        return in_array($this->product_type, [self::TYPE_BUNDLE, self::TYPE_FOLDER]) ? true : false;
    }

    public function getProductRelates() {
        return $this->hasMany(ProductRelate::className(), ['relate_id' => 'id']);
    }

    public function getProductCategories() {
        return $this->hasMany(ProductCategory::className(), ['product_id' => 'id']);
    }

    public function getProductImages() {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id']);
    }

    public function getHtmlFlags() {
        $ret = [];
        if ($this->is_pin) {
            $ret[] = Html::tag('span', Html::awesome('thumb-tack'), ['class' => 'label label-danger']);
        }
        if ($this->is_new) {
            $ret[] = Html::tag('span', 'ใหม่', ['class' => 'label label-info']);
        }
        if ($this->is_recommended) {
            $ret[] = Html::tag('span', 'แนะนำ', ['class' => 'label label-warning']);
        }
        if ($this->is_bestseller) {
            $ret[] = Html::tag('span', 'ขายดี', ['class' => 'label label-success']);
        }
        if ($this->free_shipping) {
            $ret[] = Html::tag('span', 'ฟรีค่าจัดส่ง', ['class' => 'label label-primary']);
        }
        if ($this->is_out_of_stock) {
            $ret[] = Html::tag('span', 'หมดสต๊อก', ['class' => 'label label-danger']);
        }
        return implode(' ', $ret);
    }

    public function relateAdd($id) {
        $model = ProductRelate::findOne([
                    'product_id' => $this->id,
                    'relate_id' => $id,
        ]);
        if (!isset($model)) {
            $model = new ProductRelate;
            $model->product_id = $this->id;
            $model->relate_id = $id;
            return $model->save();
        }
        return false;
    }

    public function relateRemove($id) {
        $model = ProductRelate::findOne([
                    'product_id' => $this->id,
                    'relate_id' => $id,
        ]);
        if (isset($model)) {
            return $model->delete();
        }
        return false;
    }

    public function bundleAdd($id) {
        $model = ProductBundleItem::findOne([
                    'bundle_id' => $this->id,
                    'product_id' => $id,
        ]);
        if (!isset($model)) {
            $model = new ProductBundleItem;
            $model->bundle_id = $this->id;
            $model->product_id = $id;
            return $model->save();
        }
        return false;
    }

    public function bundleRemove($id) {
        $model = ProductBundleItem::findOne([
                    'bundle_id' => $this->id,
                    'product_id' => $id,
        ]);
        if (isset($model)) {
            return $model->delete();
        }
        return false;
    }

    public function toggleLove($account_id = null) {
        $account_id = isset($account_id) ? $account_id : Yii::$app->user->id;
        $model = AccountWishlist::findOne([
                    'account_id' => $account_id,
                    'product_id' => $this->id,
        ]);
        if (!isset($model)) {
            $model = new AccountWishlist;
            $model->account_id = $account_id;
            $model->product_id = $this->id;
            return $model->save();
        } else {
            return $model->delete();
        }
    }

    public function getIsLove($account_id = null) {
        $account_id = isset($account_id) ? $account_id : Yii::$app->user->id;
        $model = AccountWishlist::findOne([
                    'account_id' => $account_id,
                    'product_id' => $this->id,
        ]);
        return isset($model) ? true : false;
    }

    public function getProductAddons() {
        return $this->hasMany(ProductAddon::className(), ['product_id' => 'id']);
    }

    public function getProductBundleItems() {
        return $this->hasMany(ProductBundleItem::className(), ['bundle_id' => 'id']);
    }

    public function getIsOutOfStock() {
        if ($this->is_out_of_stock) {
            return true;
        }
        if ($this->stock_est <= 0) {
            return true;
        }
        return false;
    }

    public function getIsUserVisible() {
        return $this->is_hide ? false : true;
    }

    public function copy() {

        $dest = new Product;
        $dest->name = $this->name;
        $dest->sku = $this->sku;
        $dest->isbn = $this->isbn;
        $dest->brief = $this->brief;
        $dest->description = $this->description;
        $dest->writer_id = $this->writer_id;
        $dest->writer_name = $this->writer_name;
        $dest->publisher_id = $this->publisher_id;
        $dest->publisher_name = $this->publisher_name;
        $dest->info_page = $this->info_page;
        $dest->info_width = $this->info_width;
        $dest->info_height = $this->info_height;
        $dest->info_depth = $this->info_depth;
        $dest->info_weight = $this->info_weight;
        $dest->info_paper = $this->info_paper;
        $dest->info_publish = $this->info_publish;
        $dest->info_cover = $this->info_cover;
        $dest->info_compiled = $this->info_compiled;
        $dest->info_translate = $this->info_translate;
        $dest->ebook_name = $this->ebook_name;
        $dest->price = $this->price;
        $dest->price_sell = $this->price_sell;
        $dest->rating = $this->rating;
        $dest->meta_title = $this->meta_title;
        $dest->meta_keywords = $this->meta_keywords;
        $dest->meta_description = $this->meta_description;
        $dest->is_bestseller = $this->is_bestseller;
        $dest->is_new = $this->is_new;
        $dest->is_recommended = $this->is_recommended;
        $dest->is_hide = $this->is_hide;
        $dest->stock = 0;
        $dest->magento_id = null;
        $dest->info_size = $this->info_size;
        $dest->slug = $this->slug;
        $dest->info_author = $this->info_author;
        $dest->info_publisher = $this->info_publisher;
        $dest->info_translator = $this->info_translator;
        $dest->free_shipping = $this->free_shipping;
        $dest->product_type = $this->product_type;
        $dest->is_out_of_stock = $this->is_out_of_stock;
        $dest->is_own = $this->is_own;
        $dest->is_pin = $this->is_pin;
        $dest->is_promotion = $this->is_promotion;
        $dest->is_deleted = $this->is_deleted;
        $dest->stock_est = 0;
        if ($dest->save()) {
            if ($this->cover_url) {
                $srcPath = Yii::getAlias(strtr($this->cover_url, ['@web' => '@webroot']));
                $filename = $dest->id . '.jpg';
                copy($srcPath, dirname($srcPath) . '/' . $filename);
                $dest->updateAttributes([
                    'cover_url' => dirname($this->cover_url) . '/' . $filename,
                ]);
            }
            if ($this->thumb_url) {
                $srcPath = Yii::getAlias(strtr($this->thumb_url, ['@web' => '@webroot']));
                $filename = $dest->id . '_thumb.jpg';
                copy($srcPath, dirname($srcPath) . '/' . $filename);
                $dest->updateAttributes([
                    'thumb_url' => dirname($this->thumb_url) . '/' . $filename,
                ]);
            }
            return $dest;
        }
    }

    public function getHasAddOns() {
        return $this->getProductAddons()->count();
    }

}

class ProductQuery extends ActiveQuery {

    public function active() {
        $this->andWhere([
            'is_hide' => 0,
            'is_deleted' => 0,
        ]);
        return $this;
    }

    public function notPublisher($id) {
        $this->andWhere(['<>', 'publisher_id', $id]);
        return $this;
    }

    public function isOwner() {
        $this->andWhere(['is_own' => 1]);
        return $this;
    }

    public function isNew() {
        $this->andWhere(['is_new' => 1]);
        return $this;
    }

    public function isRecommended() {
        $this->andWhere(['is_recommended' => 1]);
        return $this;
    }

    public function isBestSeller() {
        $this->andWhere(['is_bestseller' => 1]);
        return $this;
    }

    public function isHide() {
        $this->andWhere(['is_hide' => 1]);
        return $this;
    }

    public function isOutOfStock() {
        $this->andWhere(['OR', ['is_out_of_stock' => 1], ['<=', 'stock_est', 0]]);
        return $this;
    }

    public function isStockAvailable() {
        $this->andWhere(['AND', ['is_out_of_stock' => 0], ['>', 'stock_est', 0]]);
        return $this;
    }

    public function isNotOwner() {
        $this->andWhere(['is_own' => 0]);
        return $this;
    }

}
