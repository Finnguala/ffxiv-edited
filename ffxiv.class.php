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

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

if(!class_exists('ffxiv')) {
	class ffxiv extends game_generic {
		protected static $apiLevel	= 20;
		public $version				= '3.0.5';
		protected $this_game		= 'ffxiv';
		protected $types			= array('classes', 'races', 'factions', 'filters');
		protected $classes			= array();
		protected $races			= array();
		protected $filters			= array();
		protected $professions		= array();
		public $langs				= array('english', 'german');

		protected $class_dependencies = array(
			array(
				'name'		=> 'faction',
				'type'		=> 'factions',
				'admin' 	=> true,
				'decorate'	=> false,
				'parent'	=> false,
			),
			array(
				'name'		=> 'race',
				'type'		=> 'races',
				'admin'		=> false,
				'decorate'	=> true,
				'parent'	=> array(
					'faction' => array(
						'gridania'	=> 'all',
						'limsa'		=> 'all',
						'uldah'		=> 'all',
					),
				),
			),
			array(
				'name'		=> 'class',
				'type'		=> 'classes',
				'admin'		=> false,
				'decorate'	=> true,
				'primary'	=> true,
				'colorize'	=> true,
				'roster'	=> true,
				'recruitment' => true,
				'parent'	=> array(
					'race' => array(
						0 	=> 'all',		// Unknown
						1 	=> 'all',		// Elezen
						2 	=> 'all',		// Roegadyn
						3 	=> 'all',		// Hyuran
						4 	=> 'all',		// Miqote
						5 	=> 'all',		// Lalafell
						6 	=> 'all',		// Au Ra
					),
				),
			),
		);
		
		protected $class_colors = array(
			0	=> '#808080',
			1	=> '#808080',
			2	=> '#808080',
			3	=> '#808080',
			4	=> '#808080',
			5	=> '#808080',
			6	=> '#808080',
			7	=> '#009900',
			8	=> '#808080',
			9	=> '#009900',
		);

		protected $glang		= array();
		protected $lang_file	= array();
		protected $path			= '';
		public $lang			= false;

		public function profilefields(){
			$xml_fields = array(
				'gender'	=> array(
					'type'			=> 'dropdown',
					'category'		=> 'character',
					'lang'			=> 'uc_gender',
					'options'		=> array(
										'Male'			=> 'uc_male',
										'Female'		=> 'uc_female'
										),
					'undeletable'	=> true,
					'tolang'		=> true,
				),
				'grand_company'		=> array(
					'type'			=> 'dropdown',
					'category'		=> 'character',
					'lang'			=> 'uc_grandcompany',
					'options'		=> array(
										'Twin Adder'	=> 'uc_twinadder',
										'Maelstrom'		=> 'uc_maelstrom',
										'Flames'		=> 'uc_flames'
										),
					'undeletable'	=> true,
					'tolang'		=> true,
				),
				'city'		=> array(
					'type'			=> 'dropdown',
					'category'		=> 'character',
					'lang'			=> 'uc_city',
					'options'		=> array(
										'Uldah'			=> 'uc_uldah',
										'Limsa'			=> 'uc_limsa',
										'Gridania'		=> 'uc_gridania'
										),
					'undeletable'	=> true,
					'tolang'		=> true,
				),
				'guild'		=> array(
					'type'			=> 'text',
					'category'		=> 'character',
					'lang'			=> 'uc_guild',
					'size'			=> 32,
					'undeletable'	=> true,
				),
				'alchemist' => array(
					'type'			=> 'int',
					'category'		=> 'berufe',
					'lang'			=> 'up_alchemist',
					'sort'			=> 1,
					'size'			=> 2,
					'undeletable'	=> true,
				),
				'leatherworker' => array(
					'type'			=> 'int',
					'category'		=> 'berufe',
					'lang'			=> 'up_leatherworker',
					'sort'			=> 2,
					'size'			=> 2,
					'undeletable'	=> true,
				),
				'goldsmith' => array(
					'type'			=> 'int',
					'category'		=> 'berufe',
					'lang'			=> 'up_goldsmith',
					'sort'			=> 3,
					'size'			=> 2,
					'undeletable'	=> true,
				),
				'culinarian' => array(
					'type'			=> 'int',
					'category'		=> 'berufe',
					'lang'			=> 'up_culinarian',
					'sort'			=> 4,
					'size'			=> 2,
					'undeletable'	=> true,
				),
				'blacksmith' => array(
					'type'			=> 'int',
					'category'		=> 'berufe',
					'lang'			=> 'up_blacksmith',
					'sort'			=> 5,
					'size'			=> 2,
					'undeletable'	=> true,
				),
				'armorer' => array(
					'type'			=> 'int',
					'category'		=> 'berufe',
					'lang'			=> 'up_armorer',
					'sort'			=> 6,
					'size'			=> 2,
					'undeletable'	=> true,
				),
				'weaver' => array(
					'type'			=> 'int',
					'category'		=> 'berufe',
					'lang'			=> 'up_weaver',
					'sort'			=> 7,
					'size'			=> 2,
					'undeletable'	=> true,
				),
				'carpenter' => array(
					'type'			=> 'int',
					'category'		=> 'berufe',
					'lang'			=> 'up_carpenter',
					'sort'			=> 8,
					'size'			=> 2,
					'undeletable'	=> true,
				),
				'fisher' => array(
					'type'			=> 'int',
					'category'		=> 'berufe',
					'lang'			=> 'up_fisher',
					'sort'			=> 9,
					'size'			=> 2,
					'undeletable'	=> true,
				),
				'botanist' => array(
					'type'			=> 'int',
					'category'		=> 'berufe',
					'lang'			=> 'up_botanist',
					'sort'			=> 10,
					'size'			=> 2,
					'undeletable'	=> true,
				),
				'miner' => array(
					'type'			=> 'int',
					'category'		=> 'berufe',
					'lang'			=> 'up_miner',
					'sort'			=> 11,
					'size'			=> 2,
					'undeletable'	=> true,
				),
				'level'			=> array(
					'type'			=> 'int',
					'category'		=> 'character',
					'lang'			=> 'uc_level',
					'size'			=>	2,
					'undeletable'	=> true,
				),
			);
			return $xml_fields;
		}

		protected function load_filters($langs){
			if(!$this->classes) {
				$this->load_type('classes', $langs);
			}
			foreach($langs as $lang) {
				$names = $this->classes[$this->lang];
				$this->filters[$lang][] = array('name' => '-----------', 'value' => false);
				foreach($names as $id => $name) {
					$this->filters[$lang][] = array('name' => $name, 'value' => 'class:'.$id);
				}
				$this->filters[$lang] = array_merge($this->filters[$lang], array(
					array('name' => '-----------', 'value' => false),
					array('name' => $this->glang('tank', true, $lang), 'value' => 'class:2,5'),
					array('name' => $this->glang('support', true, $lang), 'value' => 'class:7,9'),
					array('name' => $this->glang('damage_dealer', true, $lang), 'value' => 'class:1,3,4,6,8'),
				));
			}
		}

		public function install($install=false){}
	}
}
?>
