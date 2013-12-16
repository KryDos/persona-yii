<?php 
/**
 * Persona Component. Contain helpers
 *
 * @author Ruslan (KryDos) Bekenev <furyinbox@gmail.com>
 * @license MPL 1.1 http://www.mozilla.org/MPL/1.1/index.txt
 */
class Persona extends CComponent
{
    /** contain response from persona verifier service (JSON) **/
    private $_response;

    /**
     * make request to the persona verifier service 
     * and get all information about the user
     */
    function __construct()
    {
        if(!empty($_POST['persona_assertion'])) {
            $url = 'https://verifier.login.persona.org/verify';
            $c = curl_init($url);
            $data = 'assertion='.$_POST['persona_assertion'].'&audience=' . $_SERVER['HTTP_HOST'];

            curl_setopt_array($c, array(
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_POST            => true,
                CURLOPT_POSTFIELDS      => $data,
                CURLOPT_SSL_VERIFYPEER  => true,
                CURLOPT_SSL_VERIFYHOST  => 2
            ));

            $result = curl_exec($c);
            curl_close($c);

            $this->_response = json_decode($result);
        }
    }

    /**
     * check on persona request. Persona workign through ajax
     * and if POST request contain the 'persona_assertion field 
     * then return true
     *
     * @return bool
     */
    public static function isPersonaRequest() 
    {
        return isset($_POST['persona_assertion']);
    }

    /**
     * return JSON object with user information from persona service
     *
     * @return JSON object
     */
    public function getLoginData()
    {
        return $this->_response;
    }

    /**
     * verify the login status (persona side)
     * 
     * @return bool
     */
    public function isStatusSuccess()
    {
        return $this->_response->status == 'okay';
    }

    /**
     * get user email from persona response
     *
     * @return string
     */
    public function getEmail() 
    {
        return $this->_response->email;
    }
}
