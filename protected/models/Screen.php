<?php

/**
 * This is the model class for table "{{screen}}".
 *
 * The followings are the available columns in table '{{screen}}':
 * @property integer $idscreen
 * @property string $screenname
 * @property string $location
 * @property string $hash
 *
 * The followings are the available model relations:
 * @property ScreenFeed[] $screenFeeds
 */
class Screen extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Screen the static model class
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
		return '{{screen}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hash', 'required'),
			array('screenname, location', 'length', 'max'=>80),
			array('hash', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idscreen, screenname, location, hash', 'safe', 'on'=>'search'),
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
			'screenFeeds' => array(self::HAS_MANY, 'ScreenFeed', 'idscreen'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idscreen' => Yii::t('signage','idscreen'),
			'screenname' => Yii::t('signage','screenname'),
			'location' => Yii::t('signage','screenlocation'),
			'hash' => Yii::t('signage','screenhash'),
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

		$criteria->compare('idscreen',$this->idscreen);
		$criteria->compare('screenname',$this->screenname,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('hash',$this->hash,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}