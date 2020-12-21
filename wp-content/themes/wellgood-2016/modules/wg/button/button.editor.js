const { wp } = window
const { registerBlockType } = wp.blocks;
const { createElement } = wp.element;
const { PlainText, URLInputButton, BlockControls } = wp.blockEditor;
const { ToolbarButton, ToggleControl } = wp.components
const { isURL } = wp.url
const el = createElement

registerBlockType( 'wg/button', {
    
  title: 'Button',
  icon: 'button',
  category: 'common',
  supports: {
    align: [ 'left', 'right', 'center' ]
  },

	attributes: {
		content: {
      type: 'string',
      source: 'text',
      selector: 'a'
    },
    newTab: {
      type: Boolean
    },
    url: {
      type: 'string'
    },
    inputUrl: {
      type: 'string',
      source: 'attribute',
      selector: 'a',
      attribute: 'href'
    },
    post: {
      type: 'object'
    }
  },

    
  example: {
    attributes: {
    content: 'This is a CTA'
    }
  },
  
  styles: [
    {
        name: 'primary',
        label: "Primary",
        isDefault: true
    },
    {
        name: 'white',
        label: "White",
    }
  ],

	edit( { attributes, setAttributes, isSelected, className } ) {
    attributes.newTab = attributes.newTab | false

    var style = 'primary'
    if(className.includes('is-style-white')) style = 'white' 

    return [
      <div>
        <a className={ `${className} no-underline text-link base-button base-button--${style}` }>
          <PlainText 
            value={attributes.content}
            onChange={ ( content ) => setAttributes( { content } ) } 
          />
        </a>
        { isSelected &&
        <div class="wg-button__url">
          <div class="flex items-center w-1/1 mb-e10">
          <URLInputButton 
            url={ attributes.inputUrl } 
            onChange={ ( url, post ) => {
              var attrs = { 
                inputUrl: url, 
                } 
              if(isURL(url)) {
                attrs.url = url
                attrs.post = null
              }
              if(post) attrs.post = post
              setAttributes( attrs ) }
            } />
            <span class="text-small text-gray-60 font-sans">{ attributes.post ?  attributes.post.title : attributes.url }</span>
          </div>
          <ToggleControl 
            label="Open in a new tab"
            checked={ attributes.newTab }
            onChange={ () => setAttributes( { newTab: ! attributes.newTab } ) }
            />
        </div>
        }
      </div>
    ]
  },
  
  save( { attributes } ) {
		return <a href={ attributes.url }>{ attributes.content }</a>;
	}
} )