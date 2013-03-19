<?php

/**
 * This is the model class for table "{{content}}".
 *
 * The followings are the available columns in table '{{content}}':
 * @property integer $idcontent
 * @property integer $idfeed
 * @property string $content
 * @property string $content_type
 * @property integer $duration
 * @property integer $approved
 * @property string $start
 * @property string $end
 * @property integer $iduser
 *
 * The followings are the available model relations:
 * @property User $iduser0
 * @property Feed $idfeed0
 */
class Content extends CActiveRecord
{
        public $img;
        public $imgs;
        
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Content the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{content}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idfeed, content_type, content, duration, iduser', 'required'),
			array('idfeed, duration, approved, iduser', 'numerical', 'integerOnly'=>true),
                        array('start, end', 'date', 'format'=>array('yyyy-MM-dd')),
                        array('end','compare','compareAttribute'=>'start','operator'=>'>=', 'allowEmpty'=>false , 'message'=>'{attribute} must be greater than "{compareValue})".'),
			array('content_type', 'length', 'max'=>6),
                        array('duration', 'numerical', 'min'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idcontent, idfeed, content, content_type, duration, approved, start, end, iduser', 'safe', 'on'=>'search'),
                        // content type checks
                        array('content', 'length', 'max'=>140, 'on'=>'ticker'),
                        array('img', 'required', 'on'=>'img'),
                        array('img', 'file', 'on'=>'img', 'types'=>Yii::app()->params['imgFormats']),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'iduser0' => array(self::BELONGS_TO, 'User', 'iduser'),
			'idfeed0' => array(self::BELONGS_TO, 'Feed', 'idfeed'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idcontent' => Yii::t('signage','idcontent'),
			'idfeed' => Yii::t('signage','content_feed'),
			'content' => Yii::t('signage','content'),
			'content_type' => Yii::t('signage','content_type'),
			'duration' => Yii::t('signage','content_duration'),
			'approved' => Yii::t('signage','content_approved'),
			'start' => Yii::t('signage','content_start'),
			'end' => Yii::t('signage','content_end'),
			'iduser' => Yii::t('signage','content_iduser'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('idcontent',$this->idcontent);
		$criteria->compare('idfeed',$this->idfeed);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('content_type',$this->content_type,true);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('start',$this->start,true);
		$criteria->compare('end',$this->end,true);
		$criteria->compare('iduser',$this->iduser);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function beforeSave()
        {
                // Check the approval of the user for this content object
                if($this->approved === true || 
                        GroupMembership::model()->countByAttributes(array(
                            'idgroup'=>$this->idfeed0->idgroup,
                            'iduser'=>$this->iduser,
                ))) {
                        $this->approved = true;
                }
                
                return parent::beforeSave();
        }
}