Mozilla Persona (BrowserID) Extension for Yii Framework
===============================================

Installation:
-------------

 - go into your extensions folder
 - clone this repo in your extensions folder
 - add next lines to **import** into your **config** file
 
**'ext.persona.Persona'** and **'ext.persona.PersonaUserIdentity'**

your import section should look like this:

    'import' => array(
        '...',
        'ext.persona.Persona',
        'ext.persona.PersonaUserIdentity',
        '...',
    ),

Installation complete. Hooray! :)

Usage:
------
For adding Persona Login button into your view you can invoke the widget like that: 

       <?php $this->widget('ext.persona.PersonaWidget'); ?>
       
**PersonaWidget** may take several parameters like:

 - **button_text** - is text on persona button
 - **button_style** - style of the persona button (orange, dark or default if not set)
 - **login_url** - url address for login into system
 - **logout_url** - url address for logout from system 

**PersonaWidget** is default Yii widget so you can set all this parameters like that:

    $this->widget('ext.persona.PersonaWidget', array(
     'button_text' => 'Login button text',
     'button_style' => 'dark',
     'login_url' => $this->createUrl('site/login'),
     'logout_url' => $this->createUrl('site/logout'),
    ));
    

Into your **login action** you should add next lines:


    $identity = new PersonaUserIdentity('User'); 
    if($identity->authenticate()) {
        Yii::app()->user->login($identity);
        echo json_encode(array('url'=>Yii::app()->user->returnUrl));
        Yii::app()->end();
    }
    
Let's look on what happen here.

    $identity = new PersonaUserIdentity();
this line create the PersonaUserIdentity object. This similar to default Yii UserIdentity but you can pass some parameters into. 

 - first parameter is Model Name. (**User** by default)
 - second parameter is email field name (**email** by default)
 
next line is:

    if($identity->authenticate()) {

This is also similar to default UserIdentity. Here you just checking on exist user by email.

    Yii::app()->user->login($identity);
    
This is also a default line of UserIdentity. This line a write login information too the SESSION

        echo json_encode(array('url'=>Yii::app()->user->returnUrl));
        Yii::app()->end();
        
This a **very important** lines. After success login you maybe want to redirect a user into some url or previous user url in case above. You should make JSON response with '**url**' field that contain url for redirect. And after this resonse you should ending the Yii application.

Because Persona working through Ajax you maybe want to check on persona request into your action. You can check it using:

    if(Persona::isPersonaRequest()) {/* continue persona auth */ }
    
Thats all. Feel free to send Pull Request :)


