//=== OCEAN WP ===
//or filter ocean_search_readmore_link_text to change 'Continue Reading'
//or filter ocean_post_subheading to change 'You searched for:'
function filter_search( $translation, $text, $domain)
{
	if ($text=='Search') 
		return '�����';
	if ($text=='Continue Reading')
		return '���������� ������';
	if ($text=='You searched for:')
		return '������ �� �����:';	
	if ($text=='Search Results Found')
		return '���������(�/��) �������';
	if ($text=='Sorry, but nothing matched your search terms. Please try again with different keywords.')
		return '� ���������, �� ������� ������ �� �������. ����������, ���������� ������ ��������� ������';
	return $translation;
}
add_filter( 'gettext', 'filter_search', 10, 3 );