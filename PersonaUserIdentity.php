<?php
/**
 * PersonaUserIdentity class implements from IUserIdentity
 *
 * @author Ruslan <KryDos> Bekenev <furyinbox@gmail.com>
 */

class PersonaUserIdentity implements IUserIdentity
{
    public  $id;
    private $_model_name;
    private $_email_field;

    private $_isAuthenticated = false;
    private $_name;
    private $_states = array();

    /**
     * save model name and email field name
     */
    function __construct($model_name = 'User', $email_field = 'email')
    {
        $this->_model_name = $model_name;
        $this->_email_field = $email_field;
    }

    /**
     * main function of this class. 
     * trying to find user by email
     *
     * @return bool
     */
    public function authenticate()
    {
        $persona = new Persona();
        $model = $this->_model_name;
        $email_field = $this->_email_field;

        if ($persona->isStatusSuccess()) {
            $criteria = new CDbCriteria;
            $criteria->compare($email_field, $persona->getEmail());

            $user_model = $model::model()->find($criteria);

            if($user_model == null)
                return false;

            $this->id = $user_model->id;
            $this->_isAuthenticated = true;
            $this->_name = $user_model->$email_field;
            return true;
        }
        else
            return false;
    }

    /**
     * get the user id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * get the auth status
     *
     * @return bool
     */
    public function getIsAuthenticated()
    {
        return $this->_isAuthenticated;
    }

    /**
     * get name of the user
     *
     * @return string email of the user
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return empty array
     */
    public function getPersistentStates()
    {
        return $this->_states;
    }
}
