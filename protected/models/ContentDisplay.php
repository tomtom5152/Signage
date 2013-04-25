<?php

/**
 * This is the model class for table "{{content_display}}".
 *
 * The followings are the available columns in table '{{content_display}}':
 * @property integer $idcontentdisplay
 * @property integer $idcontent
 * @property integer $idscreen
 * @property integer $lastshown
 *
 * The followings are the available model relations:
 * @property Screen $idscreen0
 * @property Content $idcontent0
 */
class ContentDisplay extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ContentDisplay the static model class
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
		return '{{content_display}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idcontent, idscreen, lastshown', 'required'),
			array('idcontent, idscreen, lastshown', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idcontentdisplay, idcontent, idscreen, lastshown', 'safe', 'on'=>'search'),
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
			'idcontent0' => array(self::BELONGS_TO, 'Content', 'idcontent'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idcontentdisplay' => 'Idcontentdisplay',
			'idcontent' => 'Idcontent',
			'idscreen' => 'Idscreen',
			'lastshown' => 'Lastshown',
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

		$criteria->compare('idcontentdisplay',$this->idcontentdisplay);
		$criteria->compare('idcontent',$this->idcontent);
		$criteria->compare('idscreen',$this->idscreen);
		$criteria->compare('lastshown',$this->lastshown);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * Calculates the time since a piece of content was last shown on a specific screen
     * @return int time the number of seconds since this peice of content was last shown on this screen
     */
    public function timeSinceLast() {
        return time() - $this->lastshown;
    }
}