import { createVNode, render } from 'vue';
import BlockEventGoogleMapsLocationComponent from './BlockEventGoogleMapsLocationComponent.vue';
/**
 * 
 * @param { string } elementId 
 * @param { string } event - event props
 */
export default function BlockEventGoogleMapsComponent(elementId, event = '') {
    const vnode = createVNode(BlockEventGoogleMapsLocationComponent, { event: event });
    render(vnode, document.getElementById(elementId));
}