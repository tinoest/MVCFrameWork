<?php

class data extends baseModel
{

	function __construct($loader) {{{
		parent::__construct( $loader );

		$this->sql = $this->loader->add_lib( SQL_CLASS, SQL_IP, SQL_USER, SQL_PASS, SQL_DB );
		return TRUE;

	}}}

	function graph_data($dtStart, $dtStop, $node) {{{

		$sql = "SELECT ts_round( log_dt, 60*15 ) AS dt_log , ROUND(AVG(tmpr),3) AS tmpr , ROUND(AVG(batt),3) AS battery , ROUND(AVG(humidity),3) AS humidity , node 
								FROM raw_data WHERE log_dt >= '$dtStart' AND log_dt <= '$dtStop' AND node = '$node' GROUP BY dt_log ,  node ORDER BY dt_log;";

		$this->loader->log->syslog( $sql );
		return $this->sql->query_rows( $sql );

	}}}

	function graph_power_data($dtStart, $dtStop, $node) {{{

		$sql = "
			SELECT ts_round( log_dt, 60*15 ) AS dt_log , 
				MIN(log_dt) AS min_dt_log , 
				MAX(log_dt) AS max_dt_log , 
				MIN(pulse_count) AS min_pulse_count , 
				MAX(pulse_count) AS max_pulse_count , 
				node 
			FROM power_data 
			WHERE log_dt >= '$dtStart' 
				AND log_dt <= '$dtStop' 
				AND node = '$node' 
			GROUP BY dt_log ,  node 
			ORDER BY dt_log;
		";

		$this->loader->log->syslog( $sql );
		return $this->sql->query_rows( $sql );

	}}}

	function graph_power_data_day($dtStart, $dtStop, $node) {{{

		$sql = "
			SELECT date_trunc('day', log_dt) AS dt_log , 
				( MAX(pulse_count)::float - MIN(pulse_count)::float ) / 1000 AS kwh_count ,
				node 
			FROM power_data 
			WHERE log_dt >= '$dtStart' 
				AND log_dt <= '$dtStop' 
				AND node = '$node' 
			GROUP BY dt_log ,  node 
			ORDER BY dt_log;
		";

		$this->loader->log->syslog( $sql );
		return $this->sql->query_rows( $sql );

	}}}

	function graph_power_data_month($dtStart, $dtStop, $node) {{{

		$sql = "
			SELECT date_trunc('month', log_dt) AS dt_log , 
				( MAX(pulse_count)::float - MIN(pulse_count)::float ) / 1000 AS kwh_count ,
				node 
			FROM power_data 
			WHERE log_dt >= '$dtStart' 
				AND log_dt <= '$dtStop' 
				AND node = '$node' 
			GROUP BY dt_log ,  node 
			ORDER BY dt_log;
		";

		$this->loader->log->syslog( $sql );
		return $this->sql->query_rows( $sql );

	}}}

	function power_now($node) {{{

		$sql = "SELECT log_dt AS dt_log , pulse_count FROM power_data WHERE log_dt <= NOW() AND node = '$node' ORDER BY dt_log DESC LIMIT 2;";

		$this->loader->log->syslog( $sql );
		return $this->sql->query_rows( $sql );

	}}}

	function power_day($node, $date) {{{

		$sql = "SELECT MIN(log_dt) AS min_dt_log , MAX(log_dt) AS max_dt_log , MIN(pulse_count) AS min_pulse_count , MAX(pulse_count) AS max_pulse_count 
								FROM power_data WHERE log_dt >= '$date'::date AND log_dt <= '$date'::date + interval '1 days' AND node = '$node';";

		$this->loader->log->syslog( $sql );
		return $this->sql->query_rows( $sql );

	}}}

}

?>
