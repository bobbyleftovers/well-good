import ResizeSensor from 'css-element-queries/src/ResizeSensor'
import { bus } from 'lib/appState'

new ResizeSensor(document.body, size => {
  bus.$emit('body-resize', size)
})

export default ResizeSensor