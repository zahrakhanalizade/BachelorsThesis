<?php

/**
 * Created by PhpStorm.
 * User: Moradnejad
 * Date: 4/5/2016
 * Time: 4:08 PM
 */
class IISTestUtilites extends PHPUnit_Extensions_Selenium2TestCase
{
    protected $browserName = "firefox";//firefox,chrome
    /*
     * $id is element's name, id or text
     *                         $this->byLinkText();
                            $this->byCssSelector();
                        $this->byXPath();
                        $this->byTag();
                        $this->byClassName();
                        $this->byId();
                        $this->byName();
     */
    public function waitUntilElementLoaded($searchMethod,$id,$wait_ms=15000)
    {
        $webdriver = $this;
        $this->waitUntil(function() use($webdriver,$searchMethod,$id){
            try{
                if($searchMethod =='byLinkText'){
                    $webdriver->byLinkText($id);
                }
                else if($searchMethod == 'byCssSelector'){
                    $webdriver->byCssSelector($id);
                }
                else if($searchMethod == 'byXPath'){
                    $webdriver->byXPath($id);
                }
                else if($searchMethod == 'byTag'){
                    $webdriver->byTag($id);
                }
                else if($searchMethod == 'byClassName'){
                    $webdriver->byClassName($id);
                }
                else if($searchMethod == 'byId'){
                    $webdriver->byId($id);
                }
                else if($searchMethod =='byName'){
                    $webdriver->byName($id);
                }
                return true;
            }catch (Exception $ex){
                $this->assertTrue(false);
            }

        }, $wait_ms);
    }
    public function checkIfXPathExists($id,$wait_ms=1000)
    {
        $webDriver = $this;
        $exists = $this->waitUntil(function() use($webDriver,$id){
            try{
                $webDriver->byXPath($id);
                return true;
            }catch (Exception $ex){
                return false;
            }
        }, $wait_ms);
        return $exists;
    }
	public function sign_in($identity,$password,$should_success=true,$fillCaptcha=false, $sessionId=0){
        //$should_success==true gives error when function can't login and vice versa.
        //$sessionId is only needed when function should fill captcha field.
        //see privacyTest::testScenario1 for more info

		$this->url(OW_URL_HOME.'sign-in');

        //FILL CAPTCHA IF EXISTS AND $fillCaptcha
        try{
            if($fillCaptcha) {
                if($this->checkIfXPathExists('//input[@name="captchaField"]')) {
                    $cp = $this->byName('captchaField');
                    if ($cp->displayed()) {
                        //------------------CAPTCHA, SESSIONS-----------
                        session_id($sessionId);
                        session_start();
                        $captchaText = ($_SESSION['securimage_code_value']);
                        session_write_close();
                        //---------------------------------------------------/
                        $cp->clear();
                        $cp->value($captchaText);
                    }
                }
            }
        }catch(Exception $ex){}

        //FILL Other fields
        try{
            $this->byName('identity')->clear();
            $this->byName('identity')->value($identity);
            $this->byName('password')->clear();
            $this->byName('password')->value($password);

            $this->byName('sign-in')->submit();
            /*
            $this->execute(array(
                'script' => "document.getElementsByName('submit')[0].setAttribute('name', 'btn_submit');",
                'args' => array()
            ));
            $this->byCssSelector('div.ow_sign_in input[name=submit2]')->click();
            $this->byName('sign-in')->submit();

            $this->execute(array(
                'script' => "document.getElementsByName('sign-in')[0].submit();",
                'args' => array()
            ));
            */

            /* $this->waitUntilElementLoaded('byClassName','ow_message_node');
			if($should_success)
	            $this->byCssSelector('.ow_message_node.info');
			else
			    $this->byCssSelector('.ow_message_node.error');*/
        }catch (Exception $ex){
            echo $ex;
            $this->assertTrue(false);
        }	
	}
    public function mobile_sign_in($identity,$password,$fillCaptcha=false, $sessionId=0){
        //$sessionId is only needed when function should fill captcha field.
        //see mForumTest::testScenario1 for more info

        $this->url(OW_URL_HOME.'');
        $this->execute(array(
            'script' => 'document.getElementById(\'owm_header_right_btn\').scrollIntoView(true);',
            'args' => array()
        ));
        $this->byId('owm_header_right_btn')->click();
        $form = $this->byCssSelector('form[name="sign-in"]');
        //FILL CAPTCHA IF EXISTS AND $fillCaptcha
        try{
            if($fillCaptcha) {
                sleep(2);
                $this->waitUntilElementLoaded("byName","captchaField",3000);
                if($this->checkIfXPathExists('//input[@name="captchaField"]')) {
                    $form = $this->byCssSelector('form[name="sign-in"]');
                    $cp = $form->byName('captchaField');
                    if ($cp->displayed()) {
                        session_id($sessionId);
                        session_start();
                        $captchaText = ($_SESSION['securimage_code_value']);
                        session_write_close();
                        $cp->clear();
                        $cp->value($captchaText);
                    }
                }
            }
        }catch(Exception $ex){}

        //FILL Other fields
        try{
            $form->byName('identity')->clear();
            $form->byName('identity')->value($identity);
            $form->byName('password')->clear();
            $form->byName('password')->value($password);

            $this->byName('sign-in')->submit();
            sleep(3);
            /*
            $this->execute(array(
                'script' => "document.getElementsByName('submit')[0].setAttribute('name', 'btn_submit');",
                'args' => array()
            ));
            $this->byCssSelector('div.ow_sign_in input[name=submit2]')->click();
            $this->byName('sign-in')->submit();

            $this->execute(array(
                'script' => "document.getElementsByName('sign-in')[0].submit();",
                'args' => array()
            ));
            */
        }catch (Exception $ex){
            echo $ex;
            $this->assertTrue(false);
        }
    }

    protected function hide_element($className,$style_name="visibility",$value="hidden"){
        try {
            $this->execute(array(
                'script' => "document.getElementsByClassName('" . $className . "')[0].style.$style_name = '$value';",
                'args' => array()
            ));
        }catch(Exception $ex){
            //$this->echoText('hide_element_error:'.$ex);
        }
    }
    protected function echoText($text, $bounding_box=false, $title="LOG")
    {
        if ($bounding_box) {
            echo "\n-----------------------------$title------------------------------------\n";
            echo "$text\n";
            echo "---------------------------------------------------------------------\n";
        }else
            echo "\n==========$title====>$text\n";
    }

}