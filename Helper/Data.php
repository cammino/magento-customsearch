<?php
class Cammino_Customsearch_Helper_Data extends Mage_CatalogSearch_Helper_Data
{
	protected $_ignored = array("a","o","e","de","do","com","sem","que","na","no","se","para");

	public function getQueryText()
	{
		if (!isset($this->_queryText)) {
			$this->_queryText = $this->_getRequest()->getParam($this->getQueryParamName());
			if ($this->_queryText === null) {
				$this->_queryText = '';
			} else {
				$stringHelper = Mage::helper('core/string');
				$this->_queryText = is_array($this->_queryText) ? ''
					: $stringHelper->cleanString(trim($this->_queryText));

				$maxQueryLength = $this->getMaxQueryLength();
				if ($maxQueryLength !== '' && $stringHelper->strlen($this->_queryText) > $maxQueryLength) {
					$this->_queryText = $stringHelper->substr($this->_queryText, 0, $maxQueryLength);
					$this->_isMaxLength = true;
				}

				$this->removeIgnored();
			}
		}
		return $this->_queryText;
	}

	private function removeIgnored() {
		$words = Mage::helper('core/string')->splitWords($this->_queryText, true);
		$validatedWords = array();

		foreach ($words as $word) {
			if (!in_array(trim($word), $this->_ignored)) {
				$validatedWords[] = $word;
			}
		}

		$this->_queryText = implode(" ", $validatedWords);
	}

}