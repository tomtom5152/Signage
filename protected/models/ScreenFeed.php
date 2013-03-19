<?php

/**
 * This is the model class for table "{{screen_feed}}".
 *
 * The followings are the available columns in table '{{screen_feed}}':
 * @property integer $idscreenfeed
 * @property integer $idscreen
 * @property integer $idfeed
 *
 * The followings are the available model relations:
 * @property Screen $idscreen0
 * @property Feed $idfeed0
 */
class ScreenFeed extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ScreenFeed the static model class
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
		return '{{screen_feed}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idscreen, idfeed', 'required'),
			array('idscreen, idfeed', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idscreenfeed, idscreen, idfeed', 'safe', 'on'=>'search'),
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
			'idscreen0' => array(self::BELONGS_TO, 'Screen', 'idscreen'),
			'idfeed0' => array(self::BELONGS_TO, 'Feed', 'idfeed'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idscreenfeed' => 'Idscreenfeed',
			'idscreen' => 'Idscreen',
			'idfeed' => 'Idfeed',
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

		$criteria->compare('idscreenfeed',$this->idscreenfeed);
		$criteria->compare('idscreen',$this->idscreen);
		$criteria->compare('idfeed',$this->idfeed);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}