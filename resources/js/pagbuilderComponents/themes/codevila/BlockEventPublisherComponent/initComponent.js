import { createVNode, render } from 'vue';
import EventPublisherComponent from './BlockEventPublisherComponent.vue';
/**
 * 
 * @param { string } elementId 
 * @param { string } event - event props
 */
export default function BlockEventPublisherComponent(elementId, event = '', publisher = '') {
    const vnode = createVNode(EventPublisherComponent, { event: event, publisher: publisher });
    render(vnode, document.getElementById(elementId));
    
}