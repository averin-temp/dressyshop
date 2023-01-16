<?php

namespace app\models;

use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\helpers\Url;
use Yii;


class Image extends ActiveRecord
{
    const SCENARIO_UPLOAD = 'scenario_upload';
    const SCENARIO_CLIPPING = 'scenario_clipping';

    /** @var хранит загруженную картинку */
    public $image;

    /** @var string|array Область отсечения */
    public $selection;


    /**
     * Имя формы для ActiveForm
     *
     * @return string
     */
    public function formName()
    {
        return '';
    }

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['model_id', 'color_id', 'primary','order','id'], 'safe'],
            [
                'image',
                'file',
                'extensions' => ['png', 'jpg', 'jpeg'],
                'maxSize' => 2*1024*1024,
                'message' => 'Возможна загрузка только изображений png, jpg,. Размером не более 2мб.',
                'tooBig' => 'Файл "{file}" слишком большой. Размер не может превышать {formattedLimit}.',
                'tooSmall' => 'Файл {file} слишком маленький. Размер не может быть меньше {formattedLimit}.',
                'wrongExtension' => 'Допустимы файлы только с расширениями: {extensions}.',
                'wrongMimeType' => 'Допустимые MIME типы файлов: {mimeTypes}.',
                'tooMany' => "Вы можете загрузить не больше {limit, number} {limit, plural, one{file} other{files}}.",
                'on' => [ static::SCENARIO_UPLOAD ]
            ],
            [
                ['model_id', 'color_id'],
                'required',
                'message' => 'не указан обязательный параметр',
                'on' => [ static::SCENARIO_UPLOAD ]
            ],
            [
                ['image'],
                'required',
                'message' => 'отсутствует файл изображения',
                'on' => [ static::SCENARIO_UPLOAD ]
            ],


            ['selection', 'filter', 'filter' => function ($value) {
                $data = json_decode($value, true);
                if($data === null) $this->addError('selection', 'неверный формат данных выделения');
                return $data;
            }, 'on' => static::SCENARIO_CLIPPING ],
            ['id', 'required', 'on' => static::SCENARIO_CLIPPING ],
            ['id', 'number', 'on' => static::SCENARIO_CLIPPING ]
        ];
    }

    /**
     * Возвращает настройки миниатюр.
     *
     * @return array
     */
    public static function picturesParams()
    {
        $params = [
            'small' => [
                'size' => [ "width" => 200 ],
                'suffix' => "_small"
            ],
            'medium' => [
                'size' => [ "width" => 300 ],
                'suffix' => "_medium"
            ],
            'normal' => [
                'size' => [ "width" => 500 ],
                'suffix' => "_normal"
            ],
            'notscaled' => [
                'suffix' => "_notscaled"
            ]
        ];
        return $params;
    }

    /**
     * Создает картинки миниатюр из загруженного файла
     */
    public function createPictures()
    {
        if(!($this->image instanceof UploadedFile)) ;
            // TODO: типа ошибка

        $file = $this->image;
        if($file->type == 'image/png')      $inputImage = imagecreatefrompng($file->tempName);
        elseif($file->type == 'image/jpeg') $inputImage = imagecreatefromjpeg($file->tempName);
        unlink($file->tempName);

        $this->filename = static::createName();
        imagejpeg($inputImage, $this->fullName, 100);
        $this->createMiniatures($inputImage);
        return true;
    }

    /**
     * Создает миниатюры. Если у модели установлено поле $selection
     *
     * @param null $_origin
     */
    public function createMiniatures($_origin = null)
    {
        $origin = $_origin ? $_origin : imagecreatefromjpeg($this->fullName);
        // TODO: проверить существует ли файл

        if($this->selection)
            $origin = $this->clipImage($origin,$this->selection);

        foreach(static::picturesParams() as $key => $options)
        {
            if( $key === 'notscaled' ) {
                $miniature = $origin;
            } else {
                $miniature = $this->createMiniature($origin, $options);
            }
            $path = static::uploadPath().$this->filename.$options['suffix'].'.jpg';
            imagejpeg($miniature, $path , 100);
            // TODO: проверить затираются ли файлы
        }
    }

    /**
     * Создает уникальное имя. Проверяет все сгенерированные имена миниатюр на уникальность.
     *
     * @return string
     */
    public static function createName()
    {
        $params = static::picturesParams();
        $uploadPath = static::uploadPath();

        do{
            $notUnique = false;
            $basename = uniqid();

            foreach($params as $options)
            {
                $suffix = $options['suffix'];
                $name = $uploadPath.$basename.$suffix.'.jpeg';
                if(file_exists($name)) {
                    $notUnique = true;
                    break;
                }
            }

        } while($notUnique);

        return $basename;
    }

    /**
     * Возвращает путь для загрузки изображений
     *
     * @return string
     */
    public static function uploadPath()
    {
        return Yii::getAlias('@webroot').'/images/models/';
    }

    /**
     * Возвращает Url папки изображений.
     *
     * @return string
     */
    public static function uploadUrl()
    {
        return Url::to('@web/images/models/');
    }

    /**
     * Возвращает суффикс имени картинки
     *
     * @param $type
     * @param string $name
     * @return string
     */
    private static function suffix($type, $name = '')
    {
        $params = static::picturesParams();
        $suffix = $params[$type]['suffix'];
        return $name.$suffix;
    }

    public function prepareInput()
    {
        $this->selection = json_decode($this->selection);
    }

    /**
     * Возвращает url миниатюры среднего размера
     *
     * @return string
     */
    public function getMedium(){
        return static::uploadUrl().self::suffix('medium', $this->filename).'.jpg';
    }

    /**
     * Возвращает url миниатюры нормального размера
     *
     * @return string
     */
    public function getNormal(){
        return static::uploadUrl().self::suffix('normal', $this->filename).'.jpg';
    }

    /**
     * Возвращает url миниатюры маленького размера
     *
     * @return string
     */
    public function getSmall(){
        return static::uploadUrl().self::suffix('small', $this->filename).'.jpg';
    }

    /**
     * Возвращает url исходного изображения
     *
     * @return string
     */
    public function getSource()
    {
        return static::uploadUrl().$this->filename.'.jpg';
    }

    /**
     * Возвращает url изображения исходного размера, но с отсечением.
     *
     * @return string
     */
    public function getNoscaled()
    {
        return static::uploadUrl().self::suffix('notscaled', $this->filename).'.jpg';
    }

    /**
     * Возвращает объект Color связанный с картинкой
     *
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'color_id']);
    }

    /**
     * Обрезает изображение $origin по выделению $selection.
     *
     * @param $origin
     * @param $selection
     * @return resource Обрезанное изображение
     */
    private function clipImage($origin, $selection)
    {
        $clippedImage = imagecreatetruecolor($selection['w'], $selection['h']);;
        imagecopyresampled($clippedImage, $origin, 0, 0, $selection['x'], $selection['y'], $selection['w'], $selection['h'], $selection['w'], $selection['h']);
        return $clippedImage;
    }

    /**
     * Создает изображение миниатюры.
     *
     * @param $origin Исходное изображение.
     * @param $options Массив с настройками создания миниатюры.
     * пример: $options = [ 'size' =>['width' => 100] ]
     * @return resource Изображение миниатюры.
     */
    private function createMiniature($origin, $options)
    {
        $originWidth = imagesx($origin);
        $originHeight = imagesy($origin);

        $miniatureWidth = $options['size']['width'];

        // коэффициент масштабирования
        $ratio = $miniatureWidth/$originWidth;
        $miniatureHeight = $ratio * $originHeight;

        $miniatureImage = imagecreatetruecolor($miniatureWidth, $miniatureHeight);
        imagecopyresampled($miniatureImage, $origin, 0, 0, 0, 0, $miniatureWidth, $miniatureHeight, $originWidth, $originHeight);

        return $miniatureImage;
    }

    /**
     * Возвращает полное имя исходного изображения
     *
     * @return string
     */
    public function getFullName()
    {
        return static::uploadPath().$this->filename.'.jpg';
    }


    /**
     * Удаляет файлы миниатюр.
     */
    public function deleteMiniatures()
    {
        foreach(static::picturesParams() as $params) {
             unlink($this->imagePath($params));
        }
    }

    public function deletePictures()
    {
        unlink($this->fullName);
        $this->deleteMiniatures();
    }

    private function imagePath($params){
        return static::uploadPath().$this->filename.$params['suffix'].'.jpg';
    }

}