wp.domReady( () => {
  const { removeEditorPanel } = wp.data.dispatch('core/edit-post');

	// Remove discussion panel from sidebar
  removeEditorPanel( 'discussion-panel' );
})