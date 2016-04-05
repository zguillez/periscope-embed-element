<?php

	namespace Z;
	class PeriscopeMeta
	{
		private $userName;
		private $metadata;
		private $broadcast_data;
		private $user_data;
		private $broadcast_id;
		public function __construct($userName)
		{
			$this->userName = $userName;
			$this->getMetadata();
		}
		private function getMetadata()
		{
			$url = "https://www.periscope.tv/" . $this->userName;
			$ch  = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$html = curl_exec($ch);
			curl_close($ch);
			$doc = new \DOMDocument();
			@$doc->loadHTML($html);
			$this->metadata = $doc->getElementsByTagName('meta');
			for ($i = 0; $i < $this->metadata->length; $i++) {
				$meta = $this->metadata->item($i);
				if ($meta->getAttribute('id') == 'broadcast-data') {
					$data                 = $meta->getAttribute('content');
					$this->metadata       = json_decode($data);
					$this->broadcast_id   = $this->metadata->broadcast->id;
					$this->user_data      = json_encode($this->metadata->user);
					$this->broadcast_data = json_encode($this->metadata->broadcast);
				}
			}
		}
		public function getId()
		{
			return $this->broadcast_id;
		}
		public function getUser()
		{
			return $this->user_data;
		}
		public function getBroadcast()
		{
			return $this->broadcast_data;
		}
	}


	