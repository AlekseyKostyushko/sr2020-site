//we are interested in coloring 'glossaryLink'

change: wp-content\plugins\enhanced-tooltipglossary\frontend\cm-tooltip-glossary-frontend.php
	$additionalClassValue = get_post_meta( $id, 'glossaryClass', true);
        $additionalClass = $additionalClassValue;

change:
	individual custom field 'glossaryClass' for glossary terms
	currently: green, magenta, cyan
