//1) css for custom class
function glossary_link_css( $dynamicCss)
{
	ob_start();
    ?>
span.glossaryLink.white, a.glossaryLink.white { color: #dddddd !important; }
span.glossaryLink.green, a.glossaryLink.green { color: #2dd02d !important; }
span.glossaryLink.magenta, a.glossaryLink.magenta { color: #d02dd0 !important;}
span.glossaryLink.red, a.glossaryLink.red { color: #d02d2d !important;}
span.glossaryLink.pink, a.glossaryLink.pink { color: #d08d8f !important;}
span.glossaryLink.cyan, a.glossaryLink.cyan { color: #2dd0d0 !important;}
    <?php
	$dynamicCss .= ob_get_clean();
	return $dynamicCss;
}
add_filter( 'cmtt_dynamic_css_before', 'glossary_link_css');

//2) custom class from field
function glossary_link_class( $additionalClass, $glossary_item)
{
	return ' '.get_post_meta( $glossary_item->ID, 'glossaryClass', true);
}
add_filter( 'cmtt_term_tooltip_additional_class', 'glossary_link_class', 10, 2);