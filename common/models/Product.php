<?php

namespace common\models;

use common\models\query\ProductQuery;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $name
 * @property string|UploadedFile $image
 * @property string $price
 * @property integer $owner_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $owner
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'image', 'price'], 'required'],
            [['price'], 'number'],
            [['owner_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['image'], 'image', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024*1024*1024*5]
        ];
    }

    /**
     * @inheritdoc
     * @return ProductQuery
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'image' => 'Изображение',
            'price' => 'Цена',
            'owner_id' => 'Владелец',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата редактирования',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['owner_id']
                ]
            ]
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    public function getImage()
    {
        return '/uploads/product/' . $this->image;
    }

    public static function getImageUploadPath($fileName = null)
    {
        if (empty($fileName)) $fileName = Yii::$app->security->generateRandomString(12) . '.jpg';
        $path = Yii::getAlias('@uploads/product/' . $fileName);
        if (FileHelper::createDirectory(dirname($path)))
            return $path;
    }

    public function deleteImage()
    {
        return @unlink(self::getImageUploadPath($this->getOldAttribute('image')));
    }

    public function getPrice()
    {
        // TODO  add currencies
        return round($this->price, 2) . ' UAH';
    }

    public function uploadImage()
    {
        return $this->image = UploadedFile::getInstance($this, 'image');
    }

    public function afterValidate()
    {
        if (!empty($this->image) && $this->image instanceof UploadedFile) {
            $filePath = self::getImageUploadPath();
            Image::thumbnail($this->image->tempName, 200, 200, ImageInterface::THUMBNAIL_OUTBOUND)
                ->save($filePath);
            if (file_exists($filePath)) {
                $this->image = basename($filePath);
                $this->deleteImage();
            }
            else
                $this->addError('image', 'Невозможно сохранить файл');
        }
        parent::afterValidate();
    }


}