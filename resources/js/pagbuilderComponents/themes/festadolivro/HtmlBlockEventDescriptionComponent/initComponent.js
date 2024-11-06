import { createVNode, render } from 'vue';
import HtmlBlockEventComponent from './HtmlBlockEventDescriptionComponent.vue';
/**
 * 
 * @param { string } elementId 
 * @param { string } event - event props
 */
export default function HtmlBlockEventDescriptionComponent(elementId, event = '') {
    const vnode = createVNode(HtmlBlockEventComponent, { event: event });
    render(vnode, document.getElementById(elementId));
}