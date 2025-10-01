/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';

/**
 * Add the attributes needed for button icons.
 *
 * @since 0.1.0
 * @param {Object} settings
 */
function addAttributes( settings ) {
	if ( 'core/group' !== settings.name ) {
		return settings;
	}

	const customWidthsAttributes = {
		customWidth: {
			type: 'string',
		}
	};

	const newSettings = {
		...settings,
		attributes: {
			...settings.attributes,
			...customWidthsAttributes,
		},
	};

	return newSettings;
}

addFilter(
	'blocks.registerBlockType',
	'mxp-layout-widths/add-attributes',
	addAttributes
);
