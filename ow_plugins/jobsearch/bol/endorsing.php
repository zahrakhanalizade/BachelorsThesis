<?php

class JOBSEARCH_BOL_Endorsing extends OW_Entity {

    public $who;        //FK to user
    public $whom;       //FK to user
    public $skill;

    /**
     * @return mixed
     */
    public function getWho()
    {
        return $this->who;
    }

    /**
     * @param mixed $who
     */
    public function setWho($who)
    {
        $this->who = $who;
    }

    /**
     * @return mixed
     */
    public function getWhom()
    {
        return $this->whom;
    }

    /**
     * @param mixed $whom
     */
    public function setWhom($whom)
    {
        $this->whom = $whom;
    }

    /**
     * @return mixed
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * @param mixed $skill
     */
    public function setSkill($skill)
    {
        $this->skill = $skill;
    }      //FK to skill

}