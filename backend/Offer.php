<?php

/**
 * Created by PhpStorm.
 * User: Rafa
 * Date: 14/03/2017
 * Time: 1:15
 */
class Offer {

    public $code, $publicationDate, $start, $location, $pay, $duration, $hours, $profile, $tasks, $company, $description, $observations, $workingDay, $vacancies, $pfc, $continuity;

    /**
     * @return mixed
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @param mixed $publicationDate
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getPay()
    {
        return $this->pay;
    }

    /**
     * @param mixed $pay
     */
    public function setPay($pay)
    {
        $this->pay = $pay;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * @param mixed $hours
     */
    public function setHours($hours)
    {
        $this->hours = $hours;
    }

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return mixed
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param mixed $tasks
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getObservations() {
        return $this->observations;
    }

    /**
     * @param mixed $observations
     */
    public function setObservations($observations) {
        $this->observations = $observations;
    }

    /**
     * @return mixed
     */
    public function getWorkingDay() {
        return $this->workingDay;
    }

    /**
     * @param mixed $workingDay
     */
    public function setWorkingDay($workingDay) {
        $this->workingDay = $workingDay;
    }

    /**
     * @return mixed
     */
    public function getVacancies() {
        return $this->vacancies;
    }

    /**
     * @param mixed $vacancies
     */
    public function setVacancies($vacancies) {
        $this->vacancies = $vacancies;
    }

    /**
     * @return mixed
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code) {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getPfc() {
        return $this->pfc;
    }

    /**
     * @param mixed $pfc
     */
    public function setPfc($pfc) {
        $this->pfc = $pfc;
    }

    /**
     * @return mixed
     */
    public function getContinuity() {
        return $this->continuity;
    }

    /**
     * @param mixed $continuity
     */
    public function setContinuity($continuity) {
        $this->continuity = $continuity;
    }
}