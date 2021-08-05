function glossary_index_filter_callback($glossary_item)
{
	$id = get_the_ID();
	$class = get_post_meta( $id, 'glossaryClass', true);
	return ($class == get_post_meta( $glossary_item->ID, 'glossaryClass', true));
}

function glossary_index_filter( $glossary_index, $glossary_query, $shortcodeAtts)
{
	$id = get_the_ID();
	if ($id) {
		$class = get_post_meta( $id, 'glossaryClass', true);
		if ($class != '') {
			//echo '<H1>'.get_post_meta( $id, 'glossaryClass', true).'</H1>';
			$glossary_index = array_values(array_filter($glossary_index,'glossary_index_filter_callback'));
			//foreach($glossary_index as $glossary_item) {
			//	echo '<H2>'.$glossary_item->ID.' - '.get_post_meta( $glossary_item->ID, 'glossaryClass', true).'</H2>';
			//}
		}
	}
	return $glossary_index;
}
add_filter( 'cmtt_glossary_index_term_list', 'glossary_index_filter', 10, 3);