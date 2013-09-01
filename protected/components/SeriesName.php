<?php
class SeriesName
{
    private static $SERIES_NAME = array(
            'F0' => 'F0',
            'M6' => 'M6',
            '6B' => '思锐'
    );

	public static function getName ($series) {
        $seriesName = self::$SERIES_NAME[$series];
        return $seriesName;
	}
}
