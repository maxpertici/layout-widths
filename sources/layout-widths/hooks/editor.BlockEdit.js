import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment, useMemo } from '@wordpress/element';
import { BlockControls } from '@wordpress/block-editor';
import { ToolbarDropdownMenu } from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';
import { __, getLocaleData } from '@wordpress/i18n';
import { alignNone } from '@wordpress/icons';
import { select, useSelect } from '@wordpress/data';
import { createElement } from '@wordpress/element';

const withCustomWidthControl = createHigherOrderComponent((BlockEdit) => {

    return (props) => {

        const { name, attributes, setAttributes, isSelected } = props;

        if (!['core/group'].includes(name)) {
            return <BlockEdit {...props} />;
        }

        if (!isSelected) {
            return <BlockEdit {...props} />;
        }
        
        const locale = useSelect(
            ( select ) =>
                select( 'core/preferences' ).get( 'core', 'locale' ) ||
                document.documentElement.lang ||
                'en_US',
            []
        );

        const options = useMemo(() => {

            const opts = [];

            // Ajout de l'option "None" par défaut
            opts.push({ label: __('None'), value: '', icon: alignNone });

            // Construction des options à partir de customWidths
            if ( customWidths && typeof customWidths === 'object') {

                Object.entries( customWidths["widths"] ).forEach( ( [ key, widthConfig ] ) => {

                    const localeLabels = customWidths["labels"] ;
                    const localizedLabel = localeLabels[locale]?.[key] ?? null;

                    let icon = widthConfig.icon || 'align-wide';

                    if( key === 'full' ) {
                        icon = 'align-full-width';
                    } else if( key === 'wide' ) {
                        icon = 'align-wide';
                    } else if( key === 'left' ) {
                        icon = 'align-left';
                    } else if( key === 'right' ) {
                        icon = 'align-right';
                    }

                    // const isBuildInLabel = ['left', 'center', 'right', 'wide', 'full', 'none'].includes(key);
                    const isBuildInLabel = false ;

                    opts.push({
                        label: ( isBuildInLabel ) ? __( widthConfig.label ) :  localizedLabel || widthConfig.label,
                        value: key, // La clé (ex: 'wider') est utilisée comme valeur pour l'attribut align
                        icon: icon,
                    });
                });
            }
            
            return opts;
        }, [locale]);

        const current = attributes.customWidth || '' ;

        // check is is custom Width
        const standardAligns = ['left', 'center', 'right', 'wide', 'full', 'none', ''];
        const isCustomWidth = current && !standardAligns.includes(current);

        return (
            <Fragment>
                <BlockControls>
                    <ToolbarDropdownMenu
                        label={__('Block Width')}
                        icon={options.find(opt => opt.value === current)?.icon || 'align-wide'}
                        controls={options.map(opt => ({
                            title: opt.label,
                            isActive: current === opt.value,
                            icon: opt.icon,
                            onClick: () => {
                                setAttributes({ align: opt.value });
                                setAttributes({ customWidth: opt.value });
                            },
                        }))}
                    />
                </BlockControls>
                <BlockEdit {...props} />
            </Fragment>
        );
    };
}, 'withCustomWidthControl');

addFilter(
    'editor.BlockEdit',
    'mxp-layout-widths/with-custom-width-control',
    withCustomWidthControl
);

