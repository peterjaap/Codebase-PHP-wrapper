<?php

	require_once("../bin/Codebase.class.php");
	require_once("../bin/config.php");

	class Authentication_test extends \Enhance\TestFixture {

		public static $instance = NULL;

		private function debug($obj) {
			if ($this->config->debugMode) {
				echo '<pre>';
				print_r($obj);
				echo '</pre>';
			}
		}
		
		// Singleton pattern to avoid creating a new 
		private function getInstance() {
			$this->config = (object)$GLOBALS['config'];
			if (!isset(self::$instance)) {
				self::$instance = new Codebase($this->config->apiuser, $this->config->apikey, $this->config->hostname, $this->config->secure);
				self::debug(self::$instance);
			}
			return self::$instance;
		}

		public function setUp() {
			$this->cb = $this->getInstance();
		}
				
		public function Is_Instance_Of_Codebase () {
			\Enhance\Assert::isInstanceOfType('Codebase', $this->cb);
		}

		public function Authentication_Matches () {
			\Enhance\Assert::areIdentical($this->config->apiuser, $this->cb->username);
			\Enhance\Assert::areIdentical($this->config->apikey, $this->cb->password);
			\Enhance\Assert::areIdentical($this->config->hostname, $this->cb->hostname);
		}

		public function has_projects () {
			\Enhance\Assert::isNotNull($this->cb->projects());
		}
		
		public function has_tickets () {
			$this->tickets = $this->cb->tickets($this->config->project);
			\Enhance\Assert::isNotNull($this->tickets);
		}

		public function has_notes () {
			$this->tickets = $this->cb->tickets($this->config->project);
			\Enhance\Assert::isNotNull($this->cb->notes($this->tickets[0]['ticket-id'], $this->config->project));
		}

		public function project_has_data () {
			\Enhance\Assert::isNotNull($this->cb->project($this->config->project));
		}

		public function has_categories () {
			\Enhance\Assert::isNotNull($this->cb->categories($this->config->project));
		}

		public function has_statuses () {
			\Enhance\Assert::isNotNull($this->cb->statuses($this->config->project));
		}

		public function has_priorities () {
			\Enhance\Assert::isNotNull($this->cb->priorities($this->config->project));
		}
	}