const { wp } = window
const { registerBlockType } = wp.blocks;
const { PlainText } = wp.blockEditor;

registerBlockType( 'wg/plain-text', {
    
  title: 'Plain Text',
  icon: 'editor-textcolor',
  category: 'common',

	attributes: {
		text: {
      type: 'string',
      source: 'text',
      selector: 'span'
    },
    placeholder: {
			type: "string"
		}
  },

	edit( { attributes, setAttributes, isSelected } ) {
    return (
      <PlainText 
          value={ attributes.text }
          onChange={ ( text ) => setAttributes( { text } ) }
          placeholder = {
            attributes.placeholder ||
            'Select to edit'
          } 
        /> 
      )
  },
  
  save( { attributes } ) {
		return <span>{ attributes.text }</span>;
	}
} )