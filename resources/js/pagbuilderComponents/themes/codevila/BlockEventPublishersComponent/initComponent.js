import { createVNode, render } from 'vue';
import EventPublishersComponent from './BlockEventPublishersComponent.vue';
/**
 * 
 * @param { string } elementId 
 * @param { string } event - event props
 */
export default function BlockEventPublishersComponent(elementId, event = '') {
    const vnode = createVNode(EventPublishersComponent, { event: event });
    render(vnode, document.getElementById(elementId));
    
}