<?php

namespace app\commands;

use app\models\Folder;
use app\models\Member;
use app\models\Product;
use app\models\ProductFolder;
use Yii;
use yii\console\Controller;
use yii\db\Connection;
use yii\imagine\Image;

class MagentoController extends Controller {

    public function getConnection() {
        return new Connection([
            'dsn' => 'mysql:host=localhost;dbname=mic_book_bk',
            'username' => 'root',
            'password' => 'sql9414',
            'charset' => 'utf8',
        ]);
    }

    public function actionImportCustomer() {
        $conn = $this->getConnection();

        $query = $conn->createCommand('SELECT * FROM customer_entity')->query();
        $count = 0;
        foreach ($query as $id => $item) {
            $model = Member::findOne([
                        'username' => $item['email'],
            ]);
            if (!isset($model)) {
                $model = new Member;
                $model->username = $item['email'];
                $model->magento_id = $item['entity_id'];
                $model->save();
                $count++;

                echo $item['email'] . ' ... added' . "\n";
            }
        }
        echo 'Total ' . $count . ' items added.';
    }

    public function actionImportPrice() {
        $conn = $this->getConnection();
        $query = $conn->createCommand('SELECT * FROM catalog_product_index_price WHERE customer_group_id = 0')->query();
        $count = 0;
        foreach ($query as $item) {
            $model = Product::findOne([
                        'magento_id' => $item['entity_id'],
            ]);
            if (isset($model)) {
                $model->price = $item['price'];
                $model->price_sell = $item['final_price'];
                $model->save();
                $count++;
                echo $item['entity_id'] . ' ... added' . "\n";
            }
        }
        echo 'Total ' . $count . ' items added.';
    }

    public function actionImportCover() {
        Yii::setAlias('@webroot', dirname(__FILE__) . '/../web');
        $conn = $this->getConnection();
        $query = $conn->createCommand('SELECT * FROM catalog_product_entity_media_gallery')->query();
        $count = 0;
        foreach ($query as $item) {
            $model = Product::findOne([
                        'magento_id' => $item['entity_id'],
            ]);
            if (isset($model)) {
                if (!$model->cover_url) {
                    $url = 'http://www.matichonbook.com/media/catalog/product' . $item['value'];

                    Image::thumbnail($url, $model->cover_width, $model->cover_height)
                            ->save(Yii::getAlias('@webroot/uploads/products/') . $model->id . '.jpg');
                    Image::thumbnail($url, $model->thumb_width, $model->thumb_height)
                            ->save(Yii::getAlias('@webroot/uploads/products/') . $model->id . '_thumb.jpg');

                    $model->cover_url = '@web/uploads/products/' . $model->id . '.jpg';
                    $model->thumb_url = '@web/uploads/products/' . $model->id . '_thumb.jpg';
                    $model->save();
                    $count++;

                    echo $item['entity_id'] . ' ... added' . "\n";
                }
            }
        }
        echo 'Total ' . $count . ' items added.';
    }

    public function actionImportCategory() {
        $conn = $this->getConnection();
        $db = Yii::$app->db;
        $folders = Folder::find()->andWhere(['<>', 'level', 3])->all();
        foreach ($folders as $folder) {
            $folder->delete();
        }
        $folders = Folder::find()->andWhere(['level' => 3])->all();
        foreach ($folders as $folder) {
            $query = $conn->createCommand('SELECT * FROM catalog_category_flat_store_1 WHERE parent_id = :parent_id')->bindValue(':parent_id', $folder->id)->query();
            $rows = [];
            foreach ($query as $item) {
                $rows[] = [
                    $item['entity_id'],
                    $item['parent_id'],
                    $item['name'],
                    $item['position'],
                    $item['level'],
                    $item['url_key'],
                ];
            }
            $db->createCommand()->batchInsert('folder', ['id', 'folder_id', 'name', 'position', 'level', 'url_key'], $rows)->execute();
        }

        ProductFolder::deleteAll();
        $folders = Folder::find()->all();
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
                }
            }
        }
    }

}
