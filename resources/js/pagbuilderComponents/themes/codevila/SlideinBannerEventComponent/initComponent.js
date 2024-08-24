import { createVNode, render } from 'vue';
import SlideinBannerComponentEvent from './SlideinBannerEventComponent.vue';
/**
 * 
 * @param { string } elementId 
 * @param { string } event - event props
 */
export default function SlideinBannerEventComponent(elementId, event = '') {
    const vnode = createVNode(SlideinBannerComponentEvent, { event: event });
    render(vnode, document.getElementById(elementId));
}