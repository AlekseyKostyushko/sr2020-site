//=== OCEAN WP ===
//or filter ocean_search_readmore_link_text to change 'Continue Reading'
//or filter ocean_post_subheading to change 'You searched for:'
function filter_search( $translation, $text, $domain)
{
	if ($text=='Search') 
		return 'Поиск';
	if ($text=='Continue Reading')
		return 'Продолжить чтение';
	if ($text=='You searched for:')
		return 'Запрос на поиск:';	
	if ($text=='Search Results Found')
		return 'Результат(а/ов) Найдено';
	if ($text=='Sorry, but nothing matched your search terms. Please try again with different keywords.')
		return 'К сожалению, по запросу ничего не найдено. Пожалуйста, попробуйте другой поисковой запрос';
	return $translation;
}
add_filter( 'gettext', 'filter_search', 10, 3 );