<?php

/**
 * This is the model class for table "{{group_membership}}".
 *
 * The followings are the available columns in table '{{group_membership}}':
 * @property integer $idgroupmembership
 * @property integer $idgroup
 * @property integer $iduser
 *
 * The followings are the available model relations:
 * @property Group $idgroup0
 * @property User $iduser0
 */
class GroupMembership extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GroupMembership the static model class
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
		return '{{group_membership}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idgroup, iduser', 'required'),
			array('idgroup, iduser', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idgroupmembership, idgroup, iduser', 'safe', 'on'=>'search'),
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
			'idgroup0' => array(self::BELONGS_TO, 'Group', 'idgroup'),
			'iduser0' => array(self::BELONGS_TO, 'User', 'iduser'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idgroupmembership' => 'Idgroupmembership',
			'idgroup' => Yii::t('signage', 'idgroup'),
			'iduser' => Yii::t('signage', 'iduser'),
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

		$criteria->compare('idgroupmembership',$this->idgroupmembership);
		$criteria->compare('idgroup',$this->idgroup);
		$criteria->compare('iduser',$this->iduser);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}