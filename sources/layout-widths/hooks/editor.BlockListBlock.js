/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';

/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * Add custom width classes and inline styles in the Editor.
 *
 * @param {Object} BlockListBlock
 */
function addClasses( BlockListBlock ) {

    return ( props ) => {
        const { name, attributes } = props;

        if ( name !== 'core/group' ) {
            return <BlockListBlock {...props} />;
        }

        let alignKey = attributes?.align ?? null;

		if( !alignKey ) {
			alignKey = attributes?.customWidth ?? null ;
		};

        // Valeurs d'alignement standard à ignorer pour les styles custom
        const standardAligns = ['left', 'center', 'right', 'wide', 'full', 'none'];
        const isCustomWidth = alignKey && !standardAligns.includes( alignKey );

        const classes = classnames( props?.className, {
            'has-layout-width': isCustomWidth,
            [`has-layout-width--${alignKey}`]: isCustomWidth,
            [`align${alignKey}`]: alignKey,
        });

        return <BlockListBlock {...props} className={classes} />;

		// if( ! isCustomWidth ){
		// 	return <BlockListBlock {...props} className={classes} />;
		// } 
		
        // // Valeur maxWidth depuis customWidths si custom
        // let jsonWidthValue = null;
        // if ( isCustomWidth && customWidths?.[alignKey]?.value ) {
        //     jsonWidthValue = customWidths[alignKey].value;
        // }

        // // Wrapper div pour appliquer le style inline même si aucune valeur custom
        // const style = jsonWidthValue ? { maxWidth: jsonWidthValue } : {};

        // return (
        //     <div style={style}>
        //         <BlockListBlock {...props} className={classes} />
        //     </div>
        // );
    };
}

addFilter(
    'editor.BlockListBlock',
    'mxp-layout-widths/add-classes',
    addClasses
);