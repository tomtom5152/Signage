<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $iduser
 * @property string $username
 * @property string $password
 * @property string $passwordc
 * @property string $initialPassword
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property integer $isAdmin
 */
class User extends CActiveRecord
{
        public $passwordc;
        public $initialPassword;
        
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, email', 'required'),
                        array('username', 'unique'),
                        array('password, passwordc', 'required', 'on'=>'insert'),
			array('isAdmin', 'numerical', 'integerOnly'=>true),
			array('username, firstname, lastname', 'length', 'max'=>50),
			array('password, passwordc', 'length', 'max'=>128),
                        array('password', 'compare', 'compareAttribute'=>'passwordc'),
			array('email', 'email', 'checkMX'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('iduser, username, password, firstname, lastname, email, isAdmin', 'safe', 'on'=>'search'),
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
                    'contents' => array(self::HAS_MANY, 'Content', 'iduser'),
                    'groupMemberships' => array(self::HAS_MANY, 'GroupMembership', 'iduser'),
                );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'iduser' => Yii::t('signage','iduser'),
			'username' => Yii::t('signage','username'),
			'password' => Yii::t('signage','password'),
                        'passwordc' => Yii::t('signage','passwordc'),
			'firstname' => Yii::t('signage','firstname'),
			'lastname' => Yii::t('signage','lastname'),
			'email' => Yii::t('signage','email'),
			'isAdmin' => Yii::t('signage','isAdmin'),
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

		$criteria->compare('iduser',$this->iduser);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('isAdmin',$this->isAdmin);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        public function beforeSave()
        {
                // in this case, we will use the old hashed password.
                if(empty($this->password) && empty($this->passwordc) && !empty($this->initialPassword)) {
                    $this->password=$this->passwordc=$this->initialPassword;
                } elseif(isset($this->password) && isset($this->passwordc) && $this->password==$this->passwordc) {
                        $ph=new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                        $this->password=$ph->HashPassword($this->password);
                }

                return parent::beforeSave();
        }

        public function afterFind()
        {
                //reset the password to null because we don't want the hash to be shown.
                $this->initialPassword = $this->password;
                $this->password = null;

                parent::afterFind();
        }
        
        public function saveModel($data=array())
        {
                //because the hashes needs to match
                if(!empty($data['password']) && !empty($data['passwordc']))
                {
                }

                $this->attributes=$data;

                if(!$this->save())
                        return CHtml::errorSummary($this);

                return true;
        }
}