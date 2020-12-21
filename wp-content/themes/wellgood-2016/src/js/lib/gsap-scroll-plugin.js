import Gsap from "gsap";
import ScrollTriggerPlugin from "gsap/ScrollTrigger"
import { bus } from 'lib/appState'
import 'lib/resize-sensor'

// Register plugin
Gsap.registerPlugin(ScrollTriggerPlugin)

// Export plugin
export const ScrollTrigger = ScrollTriggerPlugin

// Resize viewport listener & Refresh
window.__ScrollTrigger_Timeout__ = false
window.__ScrollTrigger_Refresh_Count__ = 0
window.__ScrollTrigger_Height__ = 0

// Refresh function
export const refresh = (size = false) => {
  if(size && Math.abs(window.__ScrollTrigger_Height__ - size.height) < 10) return
  window.__ScrollTrigger_Height__ = size.height
  if(!window.__ScrollTrigger_Timeout__) ScrollTriggerPlugin.refresh()
  clearTimeout(window.__ScrollTrigger_Timeout__)
  window.__ScrollTrigger_Timeout__ = setTimeout(() => {
    window.__ScrollTrigger_Refresh_Count__+=1
    window.__ScrollTrigger_Timeout__ = false
    ScrollTriggerPlugin.refresh()
  }, 1500)
}

// After refresh
ScrollTriggerPlugin.addEventListener("refresh", () => {
  clearTimeout(window.__ScrollTrigger_AfterRefresh_Timeout__)
  window.__ScrollTrigger_AfterRefresh_Timeout__ = setTimeout(() => {
    if(window.__ScrollTrigger_Refresh_Count__) window.__ScrollTrigger_Refresh_Count__--
    if(!window.__ScrollTrigger_Refresh_Count__) bus.$emit('gsap-scroll-refresh:ready')
  }, 500)
})

// Body size Listener
bus.$on('body-resize', refresh)

