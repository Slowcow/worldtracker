<?php
//Usage: (new worldtracker(delay, spike))->tracker();
(new worldtracker(20, 5))->tracker();

class worldtracker {
	public function __construct($delay, $spike) {
		$this->delay = $delay;
		$this->spike = $spike;
		$this->placeholder = [];
	}
	public function tracker() {
		$source = array_filter(array_map('trim', file("http://oldschool.runescape.com/slu.ws?order=WMLPA")));
		preg_match_all('/"(.+?)",(\d+),"/', implode("\n", $source), $source);
		$filtered = array_combine($source[1], $source[2]);
		if (!empty($this->placeholder)) {
			if (count($this->placeholder) === count($filtered)) {
				foreach ($filtered as $key => $world) {
					if ($filtered[$key] > ($this->placeholder[$key] + $this->spike)) {
						print date('H:i:s') . " | " . $key . " went up by " . ($filtered[$key] - $this->placeholder[$key]) . " players.\n";
					}
				}
				$this->rerun($filtered);
			} else {
				print date('H:i:s') . " | The count has changed in either of the arrays. Checking spike on next call.\n";
				$this->rerun($filtered);
			}
		} else {
			print date('H:i:s') . " | Placeholder array is empty. Checking spike on next call.\n";
			$this->rerun($filtered);
		}
	}
	function rerun($filtered) {
		$this->placeholder = $filtered;
		sleep($this->delay);
		$this->tracker();
	}
}
?>
