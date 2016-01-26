<?php
/*	Project:	EQdkp-Plus
 *	Package:	Final Fantasy IV game package
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if(!class_exists('zam')) {
	class zam extends itt_parser {
		public static $shortcuts = array('pdl', 'puf' => 'urlfetcher', 'pfh' => array('file_handler', array('infotooltips')));

		public $supported_games = array('ffxiv');
		public $av_langs = array();

		public $settings = array();

		public $itemlist = array();
		public $recipelist = array();

		private $searched_langs = array();

		public function __construct($init=false, $config=false, $root_path=false, $cache=false, $puf=false, $pdl=false){
			parent::__construct($init, $config, $root_path, $cache, $puf, $pdl);
			$g_settings = array(
				'ffxiv' => array('icon_loc' => 'http://img.finalfantasyxiv.com/lds/pc/global/images/', 'icon_ext' => '.png', 'default_icon' => 'unknown'),
			);
			$this->settings = array(
				'itt_icon_loc' => array(	'name' => 'itt_icon_loc',
											'language' => 'pk_itt_icon_loc',
											'type' => 'text',
											'default' => ((isset($g_settings[$this->config['game']]['icon_loc'])) ? $g_settings[$this->config['game']]['icon_loc'] : ''),
				),
				'itt_icon_ext' => array(	'name' => 'itt_icon_ext',
											'language' => 'pk_itt_icon_ext',
											'type' => 'text',
											'default' => ((isset($g_settings[$this->config['game']]['icon_ext'])) ? $g_settings[$this->config['game']]['icon_ext'] : ''),
				),
				'itt_default_icon' => array('name' => 'itt_default_icon',
											'language' => 'pk_itt_default_icon',
											'type' => 'text',
											'default' => ((isset($g_settings[$this->config['game']]['default_icon'])) ? $g_settings[$this->config['game']]['default_icon'] : ''),
				),
			);
			$g_lang = array(
				'ffxiv' => array('en' => 'en_US', 'de' => 'de_DE', 'fr' => 'fr_FR'),
			);
			$this->av_langs = ((isset($g_lang[$this->config['game']])) ? $g_lang[$this->config['game']] : '');
		}

		public function __destruct(){
			unset($this->itemlist);
			unset($this->recipelist);
			unset($this->searched_langs);
			parent::__destruct();
		}
		
		private function getLangID($strLang){
			$arrLang = array(
				'en' => 1,
				'de' => 2,
				'fr' => 3,
			);
			return $arrLang[$strLang];
		}


		private function getItemIDfromUrl($itemname, $lang, $searchagain=0){
			$searchagain++;
			$encoded_name = urlencode($itemname);
			if (!$lang) $lang = "en";
			//$link = "http://xivdb.com/modules/search/search.php?query=".$encoded_name."&page=1&pagearray=%7B%7D&language=".$this->getLangID($lang)."&filters=null&showview=0";			
			
			$link = "http://api.xivdb.com/search?page=0&string=".$encoded_name."&language=".$lang;
			$data = $this->puf->fetch($link);
			$item_id = false;
			
			$arrJson = json_decode($data, true);

			$this->searched_langs[] = $lang;
			
			if(is_array($arrJson) && isset($arrJson['items'])){
				foreach($arrJson['items']['results'] as $var){
					if(strcasecmp($itemname, $var['name']) == 0) {
						return array($var['id'], 'items');
					}
				}
			}
			
			//search in other languages
			if(!$item_id AND $searchagain < count($this->av_langs)) {
				$this->pdl->log('infotooltip', 'No Items found.');
				if(count($this->config['lang_prio']) >= $searchagain) {
					$this->pdl->log('infotooltip', 'Search again in other language.');
					$this->searched_langs[] = $lang;
					foreach($this->config['lang_prio'] as $slang) {
						if(!in_array($slang, $this->searched_langs)) {
							return $this->getItemIDfromUrl($itemname, $slang, $searchagain);
						}
					}
				}
			}
			
			return $item_id;
		}

		protected function searchItemID($itemname, $lang){
			return $this->getItemIDfromUrl($itemname, $lang);
		}

		protected function getItemData($item_id, $lang, $itemname='', $type='items'){
			$item = array('id' => $item_id);
			if(!$item_id) return null;
			
			$url = "http://xivdb.com/tooltip?list[item]=".$item_id."&language=".$lang;
			$item['link'] = $url;
			$itemdata = $this->puf->fetch($item['link']);
			//$itemdata = substr(trim($itemdata), 1);
			//$itemdata = substr($itemdata, 0, -1);			
			$arrData = json_decode($itemdata, true);
			
			if(is_array($arrData) && isset($arrData[0])){
				$strItemName = trim(strip_tags($arrData[0][4]['name']));
				
				if ($strItemName != ""){
					$item['icon'] = $arrData[0][4]['icon'];
					$item['color'] = $arrData[0][4]['color'];
					$template_html = trim(file_get_contents($this->root_path.'games/ffxiv/infotooltip/templates/ffxiv_popup.tpl'));
					$template_html = str_replace('{ITEM_HTML}', $arrData[0][3], $template_html);
					$item['html'] = $template_html;
					$item['lang'] = $lang;
					$item['name'] = $strItemName;
					
					return $item;
				}
			}
			
			$item['baditem'] = true;

			return $item;
		}
	}
}
?>