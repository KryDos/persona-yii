<?php
/**
 * This file contain only Persona class
 *
 * @author Ruslan (KryDos) Bekenev <furyinbox@gmail.com>
 * @license MIT https://raw.github.com/KryDos/persona-yii/master/LICENSE.txt
 */

/**
 * Persona Class
 *
 * @author Ruslan (KryDos) Bekenev <furyinbox@gmail.com>
 */
class PersonaWidget extends CWidget
{
    public $button_text  = 'Sign in with Persona';
    public $button_style = '';
    public $login_url    = '';
    public $logout_url   = '';

    /**
     * init function. Invoke when widget is called
     */
    public function init()
    {
        $this->registerAssets();
        echo $this->getButtonHtml();
    }

    /**
     * generate html for persona button
     *
     * @return string 
     */
    private function getButtonHtml()
    {
        return CHtml::openTag('a', array('class'=>'persona-button ' . $this->button_style, 'id'=>'persona-login')) 
            . CHtml::openTag('span') 
            . $this->button_text
            . CHtml::closeTag('span')
            . CHtml::closeTag('a');
    }

    /**
     * registrate all needed javascript's and css files
     *
     * @return void
     */
    private function registerAssets()
    {
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCssFile(Yii::app()->assetManager->publish(realpath(__DIR__ . '/assets') . '/css/persona-buttons.css'));
        Yii::app()->clientScript->registerScriptFile('http://login.persona.org/include.js');

        /** write inline because this script should use the php variables **/
        Yii::app()->clientScript->registerScript('persona-yii',
        '$(function() {
            $("#persona-login").click(function(e){ navigator.id.request(); });
            $("#persona-logout").click(function(e){ navigator.id.logout(); });
            navigator.id.watch({
                loggedInUser: null,
                onlogin: function(assertion) {
                    $.post(
                        "'.$this->login_url.'",
                        {persona_assertion:assertion},
                        function(msg) { msg = $.parseJSON(msg); window.location = msg.url; }
                    );
                },
                onlogout: function() {
                    $.post(
                        "'.$this->logout_url.'",
                        {logout:1},
                        function(msg) { location.reload(); }
                    );
                }
            });
        });
            ',
            CClientScript::POS_END
        );
    }
}
