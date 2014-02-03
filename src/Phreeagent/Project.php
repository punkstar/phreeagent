<?php
namespace Phreeagent;

/**
 * Class Project
 *
 * @package Phreeagent
 */
class Project extends Resource
{
    const CREATE_ENDPOINT = '/v2/projects';
    const FETCH_ENDPOINT  = '/v2/projects/%s';

    /**
     * @var Contact
     */
    protected $contact;
    protected $starts_on;
    protected $ends_on;

    public $name;
    public $budget;
    public $is_ir35;
    public $contract_po_reference;
    public $status = "Active"; // "Active", "Completed", "Cancelled", "Hidden"
    public $budget_units = "Hours"; // "Hours", "Days", "Monetary"
    public $normal_billing_rate;
    public $hours_per_day;
    public $uses_project_invoice_sequence;
    public $currency;
    public $billing_period;

    /**
     * @param Contact $contact
     */
    public function setContact(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * @param \DateTime $starts_on
     */
    public function setStartsOn(\DateTime $starts_on)
    {
        $this->starts_on = $starts_on->format(\DateTime::ISO8601);
    }

    /**
     * @param \DateTime $ends_on
     */
    public function setEndsOn(\DateTime $ends_on)
    {
        $this->ends_on = $ends_on->format(\DateTime::ISO8601);
    }

    /**
     * @return Task[]
     */
    public function getAllTasks()
    {
        $tasks = array();

        $result = $this->config->transport->get(
            $this->getFullEndpoint(sprintf(Task::FETCH_BY_PROJECT_ENDPOINT, $this->getId())),
            $this->getAuthHeaders()
        );

        $raw_response = json_decode($result->body);

        if (isset($raw_response->tasks)) {
            foreach ($raw_response->tasks as $task_data) {
                $task = new Task($this->config);

                $task->setProject($this);
                $task->setUrl($task_data->url);

                $task_data_obj = new \stdClass();
                $task_data_obj->task = $task_data;

                $task->loadData($task_data_obj);

                $tasks[] = $task;
            }
        }

        return $tasks;
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
        $project_data = array(
            'name'                          => $this->name,
            'contact'                       => ($this->contact !== null) ? $this->contact->getUrl() : null,
            'starts_on'                     => $this->starts_on,
            'ends_on'                       => $this->ends_on,
            'budget'                        => $this->budget,
            'is_ir35'                       => $this->is_ir35,
            'contract_po_reference'         => $this->contract_po_reference,
            'status'                        => $this->status,
            'budget_units'                  => $this->budget_units,
            'normal_billing_rate'           => $this->normal_billing_rate,
            'hours_per_day'                 => $this->hours_per_day,
            'uses_project_invoice_sequence' => $this->uses_project_invoice_sequence,
            'currency'                      => $this->currency,
            'billing_period'                => $this->billing_period
        );

        $project_data = $this->cleanParameters($project_data);

        return array(
            'project' => $project_data
        );
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
            'name', 'budget', 'status', 'budget_units', 'normal_billing_rate', 'hours_per_day',
            'uses_project_invoice_sequence', 'currency', 'billing_period'
        );

        foreach ($keys as $key) {
            if (isset($response_data->project->$key)) {
                $this->$key = $response_data->project->$key;
            }
        }
    }
}
