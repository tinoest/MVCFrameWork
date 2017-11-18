<?php

class dataController extends baseController
{

	function __construct($loader, $controller, $action) {{{
		parent::__construct( $loader, $controller, $action );


	}}}

	function display($data) {{{

		if( $this->login == TRUE ) { 
			
			$data	= $this->get( 'menu' );
			$menu	= $data['data'];

			$content = '<ul>';
			foreach( $menu as $k => $v ) {
				$content	.= '<li><a href="'.SITE_URL.'data/'.str_replace( ' ', '_', $v ).'">'.ucfirst( $v ).'</a></li>';
			}
			$content		.= '</ul>';
			$this->set( 'content', $content );
			return TRUE;

		}

		return FALSE;

	}}}

	function graph_button() {{{

		$this->set( 'enabled', FALSE );

		$data = $this->loader->get( 'url' );
		if( !is_null( $data ) ) {
			if( strpos( $data, '?' ) !== false ) {
				$date = substr( $data, strpos( $data, '?' ) + 1 );
				if( strtotime( $date ) != strtotime( date( 'Y-m-d' ) ) ) {
					$this->set( 'enabled', TRUE );
				}
			}
		}

		return TRUE; 

	}}}

	function graph_temperature( ) {{{

		if( $this->login == TRUE ) { 
			return $this->graph_button();
		}

		return FALSE;

	}}}

	function graph_battery( ) {{{

		if( $this->login == TRUE ) { 
			return $this->graph_button();
		}

		return FALSE;
		
	}}}

	function graph_humidity( ) {{{

		if( $this->login == TRUE ) { 
			return $this->graph_button();
		}

		return FALSE;
		
	}}}

	function graph_power( ) {{{

		if( $this->login == TRUE ) { 
			$this->set( 'loggedin', TRUE );
			$this->graph_button();	

			$powerNow = $this->model->power_now( 10 );
			$timediff	= $this->time_diff( $powerNow[0]['dt_log'], $powerNow[1]['dt_log'], 1 );
			$powerNow = (($powerNow[0]['pulse_count'] - $powerNow[1]['pulse_count']) * (3600 / $timediff));
			$this->set( 'powerNow', number_format( $powerNow, 2 ) );

			$data = $this->loader->get( 'url' );
			if( strpos( $data, '?' ) !== false ) {
				$date = substr( $data, strpos( $data, '?' ) + 1 );
				$powerToday = $this->model->power_day( 10, $date );
				$timediff = $this->time_diff( $powerToday[0]['min_dt_log'], $powerToday[0]['max_dt_log'], 1 );
				$powerToday = (($powerToday[0]['max_pulse_count'] - $powerToday[0]['min_pulse_count']) * (86400 / (int) $timediff));
				$this->set( 'powerToday', number_format( $powerToday, 2 ) );
				$this->set( 'date', $date );
			}

			return TRUE;
		}

		return TRUE;

	}}}


	function get_data($data) {{{

		$this->loader->log->syslog( "Get Data ".var_export( $data, TRUE ) );
		
		if( !is_array( $data ) ) {
			$call	= $data;
		}
		elseif( count( $data ) == 2 ) {
			$call	= $data[0];
		}
		elseif( count( $data ) == 3 ) {
			$call	= $data[0];
		}
		else {
			return FALSE;
		}
		
		if( $call == 'graph_power' ) {
			$view = $this->loader->get( 'view' );
			switch( strtolower( $view ) ) {
				case 'day':
					return $this->_get_data_day( $data );
					break;
				case 'month':
					return $this->_get_data_month( $data );
					break;
				default:
				return $this->_get_data_minute( $data );
			}
		}
		else {
			return $this->_get_data_minute( $data );
		}

	}}}

	function _get_data_minute($data) {{{
		
		if( !is_array( $data ) ) {
			$call	= $data;
		}
		elseif( count( $data ) == 2 ) {
			$call	= $data[0];
			$date	= $data[1];
		}
		elseif( count( $data ) == 3 ) {
			$call	= $data[0];
			$date	= $data[1];
		}
		else {
			return FALSE;
		}

		if( empty($date) ) {
			$dtStart = date( 'Y-m-d 00:00:00' );
			$dtStop = date( 'Y-m-d 23:59:59' );
		}
		else {
			$dtStart = date( 'Y-m-d 00:00:00', strtotime( $date ) );
			$dtStop = date( 'Y-m-d 23:59:59', strtotime( $date ) );
		}

		// Must rewrite this so its a bit neater
		$tmp = '';
		if( $call != 'graph_power' ) {
			$data 		= $this->model->graph_data( $dtStart, $dtStop, 8 );
			$data1 = $this->model->graph_data( $dtStart, $dtStop, 30 );
			$node			= array('8', '30');
		}
		else {
			$data 		= $this->model->graph_power_data( $dtStart, $dtStop, 10 );
			$node			= array('10');
		}
		
		for( $i = 0; $i < count( $data ); $i++ ) {
				$labels[] = date( "H:i:s", strtotime( $data[$i]['dt_log'] ) );
				switch( $call ) {
					case 'graph_battery':
						$tmp1[]		= $data[$i]['battery'];
						$tmp2[]		= $data1[$i]['battery'];
						break;
					case 'graph_humidity':
						$node = array('30');
						$tmp1[] = $data1[$i]['humidity'];
						//$tmp2[]		= $data1[$i]['humidity'];
						break;		
					case 'graph_power':
						$seconds = 60;
						if( $i == 0 ) {
							$timediff = $this->time_diff( $data[$i]['min_dt_log'], $data[$i]['max_dt_log'], $seconds );
							$power = ($data[$i]['max_pulse_count'] - $data[$i]['min_pulse_count']) * ($seconds / $timediff);
						}
						else {
							$timediff = $this->time_diff( $data[$i - 1]['max_dt_log'], $data[$i]['max_dt_log'], $seconds );
							$power = ($data[$i]['max_pulse_count'] - $data[$i - 1]['max_pulse_count']) * ($seconds / $timediff);
						}

						if( $power < 0 ) {
							$power = null;
						}

						$tmp1[] = number_format( $power, 3, '.', '' );
						//$tmp2[]		= $power;
						break;			
					default:
						$tmp1[]		= $data[$i]['tmpr'];
						$tmp2[]		= $data1[$i]['tmpr'];
						break;
				}
		}
		$tmp = array();
		$tmp[] = $tmp1;
		if( !empty($tmp2) ) {
			$tmp[] = $tmp2;
		}

		$this->set( 'get_data', json_encode( array($labels, $tmp, $node) ) );

		return TRUE;

	}}}

	function _get_data_day($data) {{{
		
		$this->loader->log->syslog( "Get Data Day".var_export( $data, TRUE ) );

		if( !is_array( $data ) ) {
			$call	= $data;
			$node = 8;
		}
		elseif( count( $data ) == 2 ) {
			$call	= $data[0];
			$date	= $data[1];
			$node = 8;
		}
		elseif( count( $data ) == 3 ) {
			$call	= $data[0];
			$date	= $data[1];
			$node = $data[2];
		}
		else {
			return FALSE;
		}

		if( empty($date) ) {
			$dtStart = date( 'Y-m-01 00:00:00' );
			$dtStop = date( 'Y-m-d 23:59:59' );
		}
		else {
			$dtStart = date( 'Y-m-01 00:00:00', strtotime( $date ) );
			$dtStop = date( 'Y-m-d 23:59:59', strtotime( $date ) );
		}

		$data 		= $this->model->graph_power_data_day( $dtStart, $dtStop, 10 );
		$node			= array('10');
		
		for( $i = 0; $i < count( $data ); $i++ ) {
				$labels[] = date( "Y-m-d", strtotime( $data[$i]['dt_log'] ) );
				if( $i == 0 ) {
					$power		= $data[$i]['kwh_count'];
				}
				else {
					$power		= $data[$i]['kwh_count'];
				}

				if( $power < 0 ) {
					$power = null;
				}

				$tmp1[] = number_format( $power, 3, '.', '' );
		}
		$tmp = array();
		$tmp[] = $tmp1;
		if( !empty($tmp2) ) {
			$tmp[] = $tmp2;
		}

		$this->set( 'get_data', json_encode( array($labels, $tmp, $node) ) );

		return TRUE;

	}}}

	function _get_data_month($data) {{{
		
		$this->loader->log->syslog( "Get Data Month".var_export( $data, TRUE ) );

		if( !is_array( $data ) ) {
			$call	= $data;
			$node = 8;
		}
		elseif( count( $data ) == 2 ) {
			$call	= $data[0];
			$date	= $data[1];
			$node = 8;
		}
		elseif( count( $data ) == 3 ) {
			$call	= $data[0];
			$date	= $data[1];
			$node = $data[2];
		}
		else {
			return FALSE;
		}

		if( empty($date) ) {
			$dtStart = date( 'Y-01-01 00:00:00' );
			$dtStop = date( 'Y-12-31 23:59:59' );
		}
		else {
			$dtStart = date( 'Y-01-01 00:00:00', strtotime( $date ) );
			$dtStop = date( 'Y-12-31 23:59:59', strtotime( $date ) );
		}

		$data 		= $this->model->graph_power_data_month( $dtStart, $dtStop, 10 );
		$node			= array('10');
		
		for( $i = 0; $i < count( $data ); $i++ ) {
				$labels[] = date( "Y-m", strtotime( $data[$i]['dt_log'] ) );
				if( $i == 0 ) {
					$power		= $data[$i]['kwh_count'];
				}
				else {
					$power		= $data[$i]['kwh_count'];
				}

				if( $power < 0 ) {
					$power = null;
				}

				$tmp1[] = number_format( $power, 3, '.', '' );
		}
		$tmp = array();
		$tmp[] = $tmp1;
		if( !empty($tmp2) ) {
			$tmp[] = $tmp2;
		}

		$this->set( 'get_data', json_encode( array($labels, $tmp, $node) ) );

		return TRUE;

	}}}	

	/**
	 * @param integer $seconds
	 */
	function time_diff($startTime, $endTime, $seconds) {{{

		$datetime1 = strtotime( $startTime );
		$datetime2 = strtotime( $endTime );
		$interval  = abs( $datetime2 - $datetime1 );
		$minutes   = number_format( $interval / $seconds, 4, '.', '' );
	
		return $minutes;

	}}}

}




?>
