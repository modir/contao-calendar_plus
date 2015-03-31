<?php

$dc = &$GLOBALS['TL_DCA']['tl_calendar_events'];

/**
 * Palettes
 */

$dc['palettes']['default']      = str_replace('title,', 'title,shortTitle,', $dc['palettes']['default']);
$dc['palettes']['default']      = str_replace(
	'endDate',
	'endDate,parentEvent;{promoter_legend},promoter;{docents_legend},docents;{eventtypes_legend},eventtypes',
	$dc['palettes']['default']
);
$dc['palettes']['default']      =
	str_replace('location', '{location_legend},location,locationAdditional,street,postal,city,coordinates,addMap', $dc['palettes']['default']);
$dc['palettes']['default']      = str_replace('{location_legend}', '{contact_legend},website;{location_legend}', $dc['palettes']['default']);
$dc['palettes']['__selector__'] = array_merge($dc['palettes']['__selector__'], array('addMap'));
$dc['subpalettes']['addMap']    = 'map,mapText';

/**
 * Callbacks
 */

$dc['config']['onload_callback'][] = array('tl_extended_events_calendar_events', 'setDefaultParentEvent');

/**
 * Operations
 */

if (!isset($_GET['epid'])) {
	$dc['list']['operations']['showSubEvents'] = array
	(
		'button_callback' => array('tl_extended_events_calendar_events', 'showSubEvents'),
		'icon'            => '/system/modules/calendar_plus/assets/img/icons/show-sub-events.png'
	);
}

/**
 * Fields
 */
$dc['fields']['shortTitle'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['shortTitle'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$dc['fields']['parentEvent'] = array
(
	'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['parentEvent'],
	'inputType'  => 'select',
	'exclude'    => true,
	'foreignKey' => 'tl_calendar_events.title',
	'eval'       => array('tl_class' => 'long', 'includeBlankOption' => true, 'submitOnChange' => true, 'chosen' => true),
	'wizard'     => array
	(
		array('tl_extended_events_calendar_events', 'editParentEvent')
	),
	'sql'        => "int(16) NOT NULL",
);

$dc['fields']['location']['eval']['tl_class'] = 'w50 clr';

$dc['fields']['locationAdditional'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['locationAdditional'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$dc['fields']['street'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['street'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$dc['fields']['postal'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['postal'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$dc['fields']['city'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['city'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$dc['fields']['coordinates'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['coordinates'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$dc['fields']['addMap'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['addMap'],
	'exclude'   => true,
	'inputType' => 'checkbox',
	'eval'      => array('submitOnChange' => true, 'doNotCopy' => true, 'tl_class' => 'long clr'),
	'sql'       => "char(1) NOT NULL default ''"
);

$dc['fields']['map'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['map'],
	'inputType' => 'fileTree',
	'exclude'   => true,
	'eval'      => array(
		'filesOnly'  => true,
		'extensions' => Config::get('validImageTypes'),
		'fieldType'  => 'radio',
		'mandatory'  => true,
		'tl_class'   => 'long'
	),
	'sql'       => "binary(16) NULL"
);

$dc['fields']['mapText'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['mapText'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);


$dc['fields']['promoter'] = array
(
	'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['promoter'],
	'inputType'  => 'select',
	'foreignKey' => 'tl_calendar_promoters.title',
	'exclude'    => true,
	'eval'       => array('chosen' => true, 'includeBlankOption' => true, 'tl_class' => 'long'),
	'wizard'     => array
	(
		array('tl_extended_events_calendar_events', 'editPromoter')
	),
	'sql'        => "int(10) unsigned NOT NULL default '0'"
);

$dc['fields']['docents'] = array
(
	'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['docents'],
	'exclude'    => true,
	'search'     => true,
	'inputType'  => 'select',
	'foreignKey' => 'tl_calendar_docents.title',
	'eval'       => array('multiple' => true, 'chosen' => true, 'tl_class' => 'clr', 'style' => 'width: 853px'),
	'sql'        => "blob NULL",
	'relation'   => array('type' => 'hasMany', 'load' => 'lazy')
);

$dc['fields']['eventtypes'] = array
(
	'label'            => &$GLOBALS['TL_LANG']['tl_calendar_events']['eventtypes'],
	'exclude'          => true,
	'search'           => true,
	'inputType'        => 'select',
	'options_callback' => array('tl_extended_events_calendar_events', 'getEventTypes'),
	'eval'             => array('multiple' => true, 'chosen' => true, 'tl_class' => 'clr', 'style' => 'width: 853px'),
	'sql'              => "blob NULL"
);

$dc['fields']['website'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['website'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50', 'rgxp' => 'url', 'maxlength' => 255),
	'sql'       => "varchar(255) NOT NULL default ''"
);

/**
 * Filter list / show subevents
 */
if ($_GET['table'] == 'tl_calendar_events') {
	$objDatabase = \Database::getInstance();

	if (isset($_GET['epid'])) {
		if (($objEvents = HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findByParentEvent($_GET['epid'])) !== null) {
			while ($objEvents->next()) {
				$dc['list']['sorting']['root'][] = $objEvents->id;
			}
		} else {
			$dc['list']['sorting']['root'] = array(-1); // don't display anything
		}
	} else {
		if (($objEvents = HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findByParentEvent(0)) !== null) {
			while ($objEvents->next()) {
				$dc['list']['sorting']['root'][] = $objEvents->id;
			}
		}
	}
}

class tl_extended_events_calendar_events extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function editPromoter(DataContainer $dc)
	{
		return ($dc->value < 1)
			? ''
			: ' <a href="contao/main.php?do=calendar&amp;table=tl_calendar_promoters&amp;act=edit&amp;id=' . $dc->value
			  . '&amp;popup=1&amp;nb=1&amp;rt=' . REQUEST_TOKEN . '" title="' . sprintf(
				  specialchars($GLOBALS['TL_LANG']['tl_calendar_events']['editpromoter'][1]),
				  $dc->value
			  ) . '" style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'' . specialchars(
				  str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_calendar_events']['editpromoter'][1], $dc->value))
			  ) . '\',\'url\':this.href});return false">' . Image::getHtml(
				'alias.gif',
				$GLOBALS['TL_LANG']['tl_calendar_events']['editpromoter'][0],
				'style="vertical-align:top"'
			) . '</a>';
	}

	public function editParentEvent(DataContainer $dc)
	{
		return ($dc->value < 1)
			? ''
			: ' <a href="contao/main.php?do=calendar&amp;table=tl_calendar_events&amp;act=edit&amp;id=' . $dc->value . '&amp;popup=1&amp;nb=1&amp;rt='
			  . REQUEST_TOKEN . '" title="' . sprintf(specialchars($GLOBALS['TL_LANG']['tl_calendar_events']['editparentevent'][1]), $dc->value)
			  . '" style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'' . specialchars(
				  str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_calendar_events']['editparentevent'][1], $dc->value))
			  ) . '\',\'url\':this.href});return false">' . Image::getHtml(
				'alias.gif',
				$GLOBALS['TL_LANG']['tl_calendar_events']['editparentevent'][0],
				'style="vertical-align:top"'
			) . '</a>';
	}

	/**
	 * Return the manage docents button
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function showSubEvents($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->hasAccess('subevents', 'calendarp') ?
			'<a href="contao/main.php?do=calendar&amp;table=tl_calendar_events&amp;pid=' . $row['pid'] . '&amp;epid=' . $row['id'] . '" title="' . specialchars($title) . '"' . $attributes . '>'
			. Image::getHtml($icon, $label) . '</a> ' : Image::getHtml(preg_replace('/\.png/i', '_.png', $icon)) . ' ';
	}

	public function setDefaultParentEvent($dc)
	{
		if (isset($_GET['id'])) {
			$objEvent       = HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findByPk($_GET['id']);
			$objParentEvent = HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findByPk($_GET['epid']);
			if ($objEvent !== null && !$objEvent->parentEvent && isset($_GET['epid'])) {
				if (!$objEvent->pid) {
					$objEvent->pid = $objParentEvent->pid;
				}
				$objEvent->parentEvent = $_GET['epid'];
				$objEvent->save();
			}
		}
	}

	public function getEventTypes(DataContainer $dc)
	{
		$arrOptions = array();

		$objCalendar = \HeimrichHannot\CalendarPlus\CalendarPlusModel::findByPk($dc->activeRecord->pid);

		if($objCalendar === null) return $arrOptions;

		// get additional archives from calendar config
		$arrArchives = deserialize($objCalendar->eventTypeArchives, true);

		$objCurrentEventTypeArchives = \HeimrichHannot\CalendarPlus\CalendarEventtypesArchiveModel::findBy('pid', $dc->activeRecord->pid);
		
		if($objCurrentEventTypeArchives !== null)
		{
			$arrArchives = array_merge($arrArchives, $objCurrentEventTypeArchives->fetchEach('id'));
		}

		$objEventTypes = \HeimrichHannot\CalendarPlus\CalendarEventtypesModel::findByPids($arrArchives);

		if($objEventTypes !== null)
		{
			$arrOptions = $objEventTypes->fetchEach('title');
		}

		return $arrOptions;
	}
}