<?php // this great piece is based off code that comes from an example found on http://php.net/manual/es/function.timezone-identifiers-list.php ?>

<label for="timezone"><?php echo $options_timezone; ?></label><select id="timezone" name="timezone">

<?php
function timezonechoice($selectedzone) {
$all = timezone_identifiers_list();

$i = 0;
foreach($all AS $zone) {
	$zone = explode('/',$zone);
	$zonen[$i]['continent'] = isset($zone[0]) ? $zone[0] : '';
	$zonen[$i]['city'] = isset($zone[1]) ? $zone[1] : '';
	$zonen[$i]['subcity'] = isset($zone[2]) ? $zone[2] : '';
	$i++;
}

asort($zonen);
$structure = '';
foreach($zonen AS $zone) {
  extract($zone);
  if($continent == 'Africa' || $continent == 'America' || $continent == 'Antarctica' || $continent == 'Arctic' || $continent == 'Asia' || $continent == 'Atlantic' || $continent == 'Australia' || $continent == 'Europe' || $continent == 'Indian' || $continent == 'Pacific') {
	if(!isset($selectcontinent)) {
	  $structure .= '<optgroup id="opt_'.$continent.'" label="'.$continent.'">'."\n"; // continent
	} elseif($selectcontinent != $continent) {
	  $structure .= '</optgroup>'."\n".'<optgroup label="'.$continent.'">'."\n"; // continent
	}

	if(isset($city) != ''){
	  if (!empty($subcity) != ''){
		$city = $city . '/'. $subcity;
	  }
	  $structure .= "\t<option ".((($continent.'/'.$city)==$selectedzone)?'selected="selected "':'')."value=\"".($continent.'/'.$city)."\">".str_replace('_',' ',$city)."</option>\n"; //Timezone
	} else {
	  if (!empty($subcity) != ''){
		$city = $city . '/'. $subcity;
	  }
	  $structure .= "\t<option ".(($continent==$selectedzone)?'selected="selected "':'')."value=\"".$continent."\">".$continent."</option>\n"; //Timezone
	}

	$selectcontinent = $continent;
  }
}
$structure .= '</optgroup>';
return $structure;
}
echo timezonechoice($timezone);
?>
</select>