<?php
namespace Phreeagent;

/**
 * Class Timeslip
 *
 * @package Phreeagent
 */
class Timeslip extends Resource
{
    const CREATE_ENDPOINT = '/v2/timeslips';
    const FETCH_ENDPOINT  = '/v2/timeslips/%s';

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Project
     */
    protected $project;

    /**
     * @var Task
     */
    protected $task;

    /**
     * @var \DateTime
     */
    protected $dated_on;

    public $hours;
    public $comment;

    /**
     * @param \DateTime $dated_on
     */
    public function setDatedOn($dated_on) {
        $this->dated_on = $dated_on->format(\DateTime::ISO8601);
    }

    public function getDatedOn()
    {
        return $this->dated_on;
    }

    /**
     * @param Project $project
     */
    public function setProject($project) {
        $this->project = $project;
    }

    /**
     * @param Task $task
     */
    public function setTask($task) {
        $this->task = $task;
    }

    /**
     * @param User $user
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     * Parse the raw response from the API and populate the resource.
     *
     * @param \stdClass $response_data
     *
     * @return void
     */
    public function loadData(\stdClass $response_data) {
        /*
         * Add the basic data
         */
        $keys = array(
            'hours', 'comment'
        );

        foreach ($keys as $key) {
            if (isset($response_data->timeslip->$key)) {
                $this->$key = $response_data->timeslip->$key;
            }
        }

        if (isset($response_data->timeslip->dated_on)) {
            $this->setDatedOn(new \DateTime($response_data->timeslip->dated_on));
        }
    }

    /**
     * @return string
     */
    public function toJson() {
        return json_encode($this->toArray());
    }

    /**
     * @return array
     */
    public function toArray() {
        $timeslip_data = array(
            "user"     => ($this->user !== null) ? $this->user->getUrl() : null,
            "project"  => ($this->project !== null) ? $this->project->getUrl() : null,
            "task"     => ($this->task !== null) ? $this->task->getUrl() : null,
            "dated_on" => $this->dated_on,
            "hours"    => $this->hours,
            "comment"  => $this->comment
        );

        $timeslip_data = $this->cleanParameters($timeslip_data);

        return array(
            "timeslip" => $timeslip_data
        );
    }
}
