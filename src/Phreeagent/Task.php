<?php
namespace Phreeagent;

/**
 * Class Task
 *
 * @package Phreeagent
 */
class Task extends Resource
{
    const CREATE_ENDPOINT = '/v2/tasks?project=%s';
    const FETCH_ENDPOINT  = '/v2/tasks/%s';

    /**
     * @var Project
     */
    protected $project;

    public $name;
    public $is_billable;
    public $billing_rate;
    public $billing_period;
    public $status = "Active"; // "Active", "Completed", "Hidden"

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Parse the raw response from the API and populate the resource.
     *
     * @param \stdClass $response_data
     *
     * @return void
     */
    public function loadData(\stdClass $response_data) {
        $keys = array(
            'name', 'is_billable', 'billing_rate', 'billing_period', 'status'
        );

        foreach ($keys as $key) {
            if (isset($response_data->task->$key)) {
                $this->$key = $response_data->task->$key;
            }
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
        $task_data = array(
            "name"           => $this->name,
            "is_billable"    => $this->is_billable,
            "billing_rate"   => $this->billing_rate,
            "billing_period" => $this->billing_period,
            "status"         => $this->status
        );

        $task_data = $this->cleanParameters($task_data);

        return array(
            "task" => $task_data
        );
    }

    public function getCreateEndpoint()
    {
        return $this->getFullEndpoint(sprintf(self::CREATE_ENDPOINT, $this->project->getId()));
    }
}
