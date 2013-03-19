<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
        private $_id;
        
	/**
	 * Authenticates a user.
         * Performs a fallback check against MD5 and will then do a rolling 
         * update to use phpass update.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
                $record=User::model()->findByAttributes(array('username'=>$this->username));
                $ph=new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                if($record===null)
                        $this->errorCode=self::ERROR_USERNAME_INVALID;
                else if(md5($this->password)!==$record->initialPassword && !$ph->CheckPassword($this->password, $record->initialPassword))
                        $this->errorCode=self::ERROR_PASSWORD_INVALID;
                else
                {
                        //Is this a vanilla hash?
                        if($record->password{0}!=='$')
                        {
                                $record->password=$ph->HashPassword($this->password);
                                $record->save();
                        }
                                $this->_id=$record->iduser;
                                $this->errorCode=self::ERROR_NONE;
                        }
                return !$this->errorCode;
	}
        
        public function getId()
        {
                return $this->_id;
        }
}