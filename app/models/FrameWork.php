<?php

/**
 * This class works as an Interface, defining a series of contract methods
 * which external frameworks must implement.
 *
 * An external framework is understood as a class which allows CrowdTruth to
 * communicate with an external platform (such as CrowdFlower or Dr. Detective game.
 */
abstract class FrameWork {

	/**
	* @return String the label of the platform
	*/
	abstract public function getLabel();

	/**
	* @return String the name of the platform, displayed in the 'platform' tab
	*/
	abstract public function getName();

	/**
	* @return String the extension of a question template
	*/
	abstract public function getExtension();
	
	/**
	* @return Array with Laravel style validationrules, to validate the fields added to JobConf.
	* @link http://laravel.com/docs/validation
	*/
	abstract public function getJobConfValidationRules();

	/**
	* Create the view where the user can select JobConf settings specific to this platform.
	* @return return View::make('[platform]::create');
	*/
	abstract public function createView();

	/**
	* Is fired after submitting the specific JobConf settings. 
	* Can handle the Input of the view, optionally performing some logic on it.
	* @param JobConfiguration
	* @return JobConfiguration
	*/
	public function updateJobConf($jc){
		return $jc;
	}

	/**
	* Create the job on the platform and in our database, but don't order it yet.
	* Important: when you catch an error in creating, call $this->undoCreation
	* @param Job $job
	* @param boolean $sandbox (should be TRUE in our current implementation).
	* @return array with at least 'id' => $platformJobId. Optional: 'url' to the job.
	*/
	abstract public function publishJob($job, $sandbox);

	/**
	* If an error occurs during creation, this method gets called. Make sure the job is deleted on the platform.
	* @param mixed $ids may be an array or just one, depending on your implementation.
	* @throws Exception
	*/
	abstract public function undoCreation($ids);

	/**
	* @throws Exception
	*/
    abstract public function orderJob($job);

	/**
	* @throws Exception
	*/
	abstract public function pauseJob($id);

	/**
	* @throws Exception
	*/
	abstract public function resumeJob($id);

	/**
	* Send message to workers. If your platform doesn't support multiple messages, make sure you handle this yourself.
	* @param string $subject
	* @param string $body
	* @param array $workerids 
	* @throws Exception
	*/
	abstract public function sendMessage($subject, $body, $workerids);

	// TODO: add documentation
	abstract public function blockWorker($id, $message);
	
	// TODO: add documentation
	abstract public function unBlockWorker($id, $message);
}
