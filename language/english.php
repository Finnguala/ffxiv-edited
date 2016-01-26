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
$english_array = array(
	'classes' => array(
		0	=> 'Unknown',
		1	=> 'Blackmage',
		2	=> 'Warrior',
		3	=> 'Dragoon',
		4	=> 'Monk',
		5	=> 'Paladin',
		6	=> 'Bard',
		7	=> 'Whitemage',
		8 	=> 'Summoner',
		9	=> 'Scholar',
		10	=> 'Ninja',
		11	=> 'Dark Knight',
		12	=> 'Astrologian',
		13	=> 'Machinist',
	),
	'races' => array(
		0	=> 'Unknown',
		1	=> 'Elezen',
		2	=> 'Roegadyn',
		3	=> 'Hyuran',
		4	=> 'Miqote',
		5	=> 'Lalafell',
		6	=> 'Au Ra',
	),
	'factions' => array(
		'twin_adder'	=> 'Order of the Twin Adder',
		'maelstrom'		=> 'Maelstrom',
		'flames'		=> 'The Immortal Flames',
	),
	'lang' => array(
		'ffxiv'							=> 'Final Fantasy XIV',
		'tank'							=> 'Tank',
		'support'						=> 'Healer',
		'damage_dealer'					=> 'Damage Dealer',

		// Profile information
		'uc_gender'						=> 'Gender',
		'uc_male'						=> 'Male',
		'uc_female'						=> 'Female',
		'uc_guild'						=> 'Free Company',
		'uc_race'						=> 'Race',
		'uc_class'						=> 'Class',
		'uc_cat_berufe'					=> 'Professions',
		'uc_level'						=> 'Level',
		
		// Grand Company Information
		'uc_grandcompany'				=> 'Grand Company',
		'uc_twinadder'					=> 'Order of the Twin Adder',
		'uc_maelstrom'					=> 'Maelstrom',
		'uc_flames'						=> 'The Immortal Flames',
		
		// City-State Information
		'uc_city'						=> 'City-state',
		'uc_uldah'						=> "Ul'dah",
		'uc_limsa'						=> 'Limsa Lominsa',
		'uc_gridania'					=> 'Gridania',
		
		// Profession information
		'up_alchemist'					=> 'Alchemist',
		'up_leatherworker'				=> 'Leatherworker',
		'up_goldsmith'					=> 'Goldsmith',
		'up_culinarian'					=> 'Culinarian',
		'up_blacksmith'					=> 'Blacksmith',
		'up_armorer'					=> 'Armorer',
		'up_weaver'						=> 'Weaver',
		'up_carpenter'					=> 'Carpenter',
		'up_fisher'						=> 'Fisher',
		'up_botanist'					=> 'Botanist',
		'up_miner'						=> 'Miner',
		
		// Admin Settings
		'core_sett_fs_gamesettings'	=> 'Final Fantasy XIV Settings',
		'uc_faction'				=> 'Grand Company',
		'uc_faction_help'			=> 'Select the Grand Company for your Free Company.',
	),
);
?>