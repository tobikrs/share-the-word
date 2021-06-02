import { verse as icon } from '@wordpress/icons';

import './style.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';
import save from './save';
import metadata from '../../block.json';

const { name } = metadata;

export { metadata, name };

export const settings = {
	icon,
	edit: Edit,
	save,
};
