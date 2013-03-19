<?php

/**
 * This is the model class for table "{{feed}}".
 *
 * The followings are the available columns in table '{{feed}}':
 * @property integer $idfeed
 * @property string $feedname
 * @property integer $idgroup
 *
 * The followings are the available model relations:
 * @property Content[] $contents
 * @property Group $idgroup0
 * @property ScreenFeed[] $screenFeeds
 */
class Feed extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Feed the static model class
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
		return '{{feed}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('feedname, idgroup', 'required'),
			array('idgroup', 'numerical', 'integerOnly'=>true),
			array('feedname', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idfeed, feedname, idgroup', 'safe', 'on'=>'search'),
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
			'contents' => array(self::HAS_MANY, 'Content', 'idfeed'),
			'idgroup0' => array(self::BELONGS_TO, 'Group', 'idgroup'),
			'screenFeeds' => array(self::HAS_MANY, 'ScreenFeed', 'idfeed'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idfeed' => Yii::t('signage','idfeed'),
			'feedname' =>  Yii::t('signage','feedname'),
			'idgroup' =>  Yii::t('signage','feedgroup'),
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

		$criteria->compare('idfeed',$this->idfeed);
		$criteria->compare('feedname',$this->feedname,true);
		$criteria->compare('idgroup',$this->idgroup);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}