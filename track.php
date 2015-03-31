<?php
//Usage: (new worldtracker(delay, spike))->tracker();
(new worldtracker(30, 20))->tracker();

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
			foreach ($filtered as $key => $world) {
				if ($filtered[$key] > ($this->placeholder[$key] + $this->spike)) {
					print $key . " went up by " . ($filtered[$key] - $this->placeholder[$key]) . " players.\n";
				}
			}
			sleep($this->delay);
			$this->tracker();
		} else {
			print "Placeholder array is empty or count didn't match. Checking spike on next call.\n";
			$this->placeholder = $filtered;
			sleep($this->delay);
			$this->tracker();
		}
	}
}
?>