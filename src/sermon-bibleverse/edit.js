import { __, _x } from '@wordpress/i18n';
import { TextControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import { useBlockProps } from '@wordpress/block-editor';

import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit() {
	const blockProps = useBlockProps();
	const postType = useSelect(
		( select ) => select( 'core/editor' ).getCurrentPostType(),
		[]
	);
	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );
	const metaBibleVerse = meta[ 'stw_bibleverse' ];

	function updateMetaBibleVerse( newValue ) {
		setMeta( { ...meta, stw_bibleverse: newValue } );
	}

	return (
		<div { ...blockProps }>
			<TextControl
				label={ __( 'Bible Verse', 'share-the-word' ) }
				placeholder={ _x(
					'Johannes 3, 16',
					'Placeholder for a bible verse',
					'share-the-word'
				) }
				value={ metaBibleVerse }
				onChange={ updateMetaBibleVerse }
			/>
		</div>
	);
}
