import { bus } from 'lib/appState'

window.onYouTubeIframeAPIReady = () => bus.$emit('onYouTubeIframeAPIReady')