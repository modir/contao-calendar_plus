<?php

namespace HeimrichHannot;

class ModuleEventChooser extends \Module
{
	
	protected $strTemplate = 'mod_event_chooser';
	
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### EVENT CHOOSER ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
			return $objTemplate->parse();
		}
		return parent::generate();
	}
	
	protected function compile()
	{
		$this->loadLanguageFile('tl_calendar_events');
		
		$objEvents = \CalendarEventsModel::findAll();
		
		// HOOK: modify event objects for the chooser
		if (isset($GLOBALS['TL_HOOKS']['getEventObjectsForChooser']) && is_array($GLOBALS['TL_HOOKS']['getEventObjectsForChooser']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getEventObjectsForChooser'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($objEvents);
			}
		}
		
		$items = array();
		if ($objEvents !== null) {
			while ($objEvents->next()) {
				$items[$objEvents->id] = $objEvents->shortTitle ? $objEvents->shortTitle : $objEvents->title;
			}
		}
		
		// HOOK: modify event items for the chooser
		if (isset($GLOBALS['TL_HOOKS']['getEventItemsForChooser']) && is_array($GLOBALS['TL_HOOKS']['getEventItemsForChooser']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getEventItemsForChooser'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($objEvents, $items);
			}
		}
		
		$this->Template->items = $items;
		if ($this->jumpTo)
			$this->Template->action = $this->generateFrontendUrl($this->jumpTo);
		else
			$this->Template->action = '';
	}
}