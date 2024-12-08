<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property string $sku
 * @property string $isbn
 * @property string $created_at
 * @property string $updated_at
 * @property string $brief
 * @property string $description
 * @property int $writer_id
 * @property string $writer_name
 * @property int $publisher_id
 * @property string $publisher_name
 * @property int $info_page
 * @property string $info_width
 * @property string $info_height
 * @property string $info_depth
 * @property string $info_weight
 * @property string $info_paper
 * @property string $info_publish
 * @property string $info_cover
 * @property string $info_compiled เรียบเรียง
 * @property string $info_translate
 * @property string $cover_url
 * @property string $thumb_url
 * @property string $ebook_name
 * @property string $price
 * @property string $price_sell
 * @property int $rating
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property int $is_bestseller
 * @property int $is_new
 * @property int $is_recommended
 * @property int $is_hide
 * @property int $stock
 * @property int $magento_id
 * @property string $info_size
 * @property string $slug
 * @property string $info_author
 * @property string $info_publisher
 * @property string $info_translator
 * @property int $free_shipping
 * @property int $product_type
 * @property int $is_out_of_stock
 * @property int $is_own
 * @property int $is_pin
 * @property int $is_promotion
 * @property int $is_deleted
 * @property int $stock_est
 * @property int $is_delivery_std
 * @property string $delivery_std_cost
 * @property string $delivery_register_cost
 *
 * @property DiscountExclude[] $discountExcludes
 * @property Discount[] $discounts
 * @property GiftImage[] $giftImages
 * @property Publisher $publisher
 * @property Writer $writer
 * @property ProductAuthor[] $productAuthors
 * @property Author[] $authors
 * @property ProductBundleItem[] $productBundleItems
 * @property ProductBundleItem[] $productBundleItems0
 * @property Product[] $products
 * @property Product[] $bundles
 * @property ProductFolder[] $productFolders
 * @property Folder[] $folders
 * @property ProductImage[] $productImages
 * @property ProductRelate[] $productRelates
 * @property ProductRelate[] $productRelates0
 * @property Product[] $relates
 * @property Product[] $products0
 * @property ProductStock[] $productStocks
 * @property ProductTag[] $productTags
 * @property Tag[] $tags
 * @property PromotionItem[] $promotionItems
 * @property Promotion[] $promotions
 * @property PromotionProduct[] $promotionProducts
 * @property Promotion[] $promotions0
 * @property PurchaseProduct[] $purchaseProducts
 * @property Purchase[] $purchases
 * @property SlideProduct[] $slideProducts
 * @property Slide[] $slides
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'sku'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['brief', 'description', 'meta_keywords', 'meta_description', 'info_author', 'info_publisher', 'info_translator'], 'string'],
            [['writer_id', 'publisher_id', 'info_page', 'rating', 'is_bestseller', 'is_new', 'is_recommended', 'is_hide', 'stock', 'magento_id', 'free_shipping', 'product_type', 'is_out_of_stock', 'is_own', 'is_pin', 'is_promotion', 'is_deleted', 'stock_est', 'is_delivery_std'], 'integer'],
            [['info_width', 'info_height', 'info_depth', 'info_weight', 'price', 'price_sell', 'delivery_std_cost', 'delivery_register_cost'], 'number'],
            [['name', 'writer_name', 'publisher_name', 'info_compiled', 'info_translate'], 'string', 'max' => 200],
            [['sku', 'isbn'], 'string', 'max' => 64],
            [['info_paper', 'info_cover'], 'string', 'max' => 100],
            [['info_publish', 'cover_url', 'ebook_name', 'meta_title'], 'string', 'max' => 150],
            [['thumb_url', 'info_size', 'slug'], 'string', 'max' => 160],
            [['publisher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Publisher::className(), 'targetAttribute' => ['publisher_id' => 'id']],
            [['writer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Writer::className(), 'targetAttribute' => ['writer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sku' => 'Sku',
            'isbn' => 'Isbn',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'brief' => 'Brief',
            'description' => 'Description',
            'writer_id' => 'Writer ID',
            'writer_name' => 'Writer Name',
            'publisher_id' => 'Publisher ID',
            'publisher_name' => 'Publisher Name',
            'info_page' => 'Info Page',
            'info_width' => 'Info Width',
            'info_height' => 'Info Height',
            'info_depth' => 'Info Depth',
            'info_weight' => 'Info Weight',
            'info_paper' => 'Info Paper',
            'info_publish' => 'Info Publish',
            'info_cover' => 'Info Cover',
            'info_compiled' => 'Info Compiled',
            'info_translate' => 'Info Translate',
            'cover_url' => 'Cover Url',
            'thumb_url' => 'Thumb Url',
            'ebook_name' => 'Ebook Name',
            'price' => 'Price',
            'price_sell' => 'Price Sell',
            'rating' => 'Rating',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'is_bestseller' => 'Is Bestseller',
            'is_new' => 'Is New',
            'is_recommended' => 'Is Recommended',
            'is_hide' => 'Is Hide',
            'stock' => 'Stock',
            'magento_id' => 'Magento ID',
            'info_size' => 'Info Size',
            'slug' => 'Slug',
            'info_author' => 'Info Author',
            'info_publisher' => 'Info Publisher',
            'info_translator' => 'Info Translator',
            'free_shipping' => 'Free Shipping',
            'product_type' => 'Product Type',
            'is_out_of_stock' => 'Is Out Of Stock',
            'is_own' => 'Is Own',
            'is_pin' => 'Is Pin',
            'is_promotion' => 'Is Promotion',
            'is_deleted' => 'Is Deleted',
            'stock_est' => 'Stock Est',
            'is_delivery_std' => 'Is Delivery Std',
            'delivery_std_cost' => 'Delivery Std Cost',
            'delivery_register_cost' => 'Delivery Register Cost',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscountExcludes()
    {
        return $this->hasMany(DiscountExclude::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscounts()
    {
        return $this->hasMany(Discount::className(), ['id' => 'discount_id'])->viaTable('discount_exclude', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGiftImages()
    {
        return $this->hasMany(GiftImage::className(), ['gift_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPublisher()
    {
        return $this->hasOne(Publisher::className(), ['id' => 'publisher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWriter()
    {
        return $this->hasOne(Writer::className(), ['id' => 'writer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAuthors()
    {
        return $this->hasMany(ProductAuthor::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::className(), ['id' => 'author_id'])->viaTable('product_author', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductBundleItems()
    {
        return $this->hasMany(ProductBundleItem::className(), ['bundle_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductBundleItems0()
    {
        return $this->hasMany(ProductBundleItem::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_bundle_item', ['bundle_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBundles()
    {
        return $this->hasMany(Product::className(), ['id' => 'bundle_id'])->viaTable('product_bundle_item', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductFolders()
    {
        return $this->hasMany(ProductFolder::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFolders()
    {
        return $this->hasMany(Folder::className(), ['id' => 'folder_id'])->viaTable('product_folder', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductRelates()
    {
        return $this->hasMany(ProductRelate::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductRelates0()
    {
        return $this->hasMany(ProductRelate::className(), ['relate_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelates()
    {
        return $this->hasMany(Product::className(), ['id' => 'relate_id'])->viaTable('product_relate', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts0()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_relate', ['relate_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductStocks()
    {
        return $this->hasMany(ProductStock::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTags()
    {
        return $this->hasMany(ProductTag::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('product_tag', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionItems()
    {
        return $this->hasMany(PromotionItem::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotions()
    {
        return $this->hasMany(Promotion::className(), ['id' => 'promotion_id'])->viaTable('promotion_item', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionProducts()
    {
        return $this->hasMany(PromotionProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotions0()
    {
        return $this->hasMany(Promotion::className(), ['id' => 'promotion_id'])->viaTable('promotion_product', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchases()
    {
        return $this->hasMany(Purchase::className(), ['id' => 'purchase_id'])->viaTable('purchase_product', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlideProducts()
    {
        return $this->hasMany(SlideProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlides()
    {
        return $this->hasMany(Slide::className(), ['id' => 'slide_id'])->viaTable('slide_product', ['product_id' => 'id']);
    }
}
