<?php
/**
 * Created by PhpStorm.
 * User: CEBIT
 * Date: 8/4/2017
 * Time: 3:06 PM
 */

class JOBSEARCH_CTRL_Job extends OW_ActionController
{
    public function index()
    {
        $this->setPageTitle("Job Search");
        $this->setPageHeading("Job Search");

        $all = JOBSEARCH_BOL_RequirementDao::getInstance()->getALL();
        $this->assign('reqs' , $all) ;
        $count = count($all);
        $this->assign('count', $count);
        $creators = JOBSEARCH_BOL_RequirementDao::getInstance()->getCreators();
        $this->assign('creators', $creators);
    }
    public function addJob(){
        if (OW::getUser()->isAuthenticated()){
            $userId = OW::getUser()->getId();
            $form = new Form('addJob_form');
            $description = new TextField('description');
            $description->setLabel('توضیحات');
            $description->setRequired();
            $form->addElement($description);
            $skills = new TagsInputField('skills');
            $skills->setLabel('مهارت های مورد نیاز');
            $form->addElement($skills);

            $submit = new Submit('send');
            $submit->setValue('ایجاد');
            $form->addElement($submit);
            $this->addForm($form);

            if (OW::getRequest()->isPost()) {

                if ($form->isValid($_POST)) {
                    $values = $form->getValues();
                    $required_skills =  $values['skills'];
                    //TODO  sakhtane skill ha



                    JOBSEARCH_BOL_RequirementDao::getInstance()->add($values['description'], $userId);
                    $this->redirect('job');
                }
            }

        }
        else {
            throw new AuthenticateException();
        }
    }
}


?>