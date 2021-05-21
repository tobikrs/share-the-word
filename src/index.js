import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal imports
 */
import * as sermonBibleverse from './sermon-bibleverse';

/**
 * Function to register an individual block.
 *
 * @param {Object} block The block to be registered.
 *
 */
const registerBlock = ( block ) => {
	if ( ! block ) {
		return;
	}

	const { name, settings } = block;
	registerBlockType( name, settings );
};

/**
 * List of Blocks to register
 */
const blocksToRegister = [ sermonBibleverse ];

/**
 * Registers all blocks
 */
blocksToRegister.forEach( registerBlock );
