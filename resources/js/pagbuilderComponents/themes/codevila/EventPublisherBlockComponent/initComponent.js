import { createVNode, render } from 'vue';
import EventPublisherBlockComponent from './EventPublisherBlockComponent.vue';
/**
 * 
 * @param { string } elementId 
 * @param { string } event - event props
 * @param { string } publisher - event props
 */
export default function BlockEventPublisherComponent(elementId, event = '', publisher = '') {
    const vnode = createVNode(EventPublisherBlockComponent, { event: event, publisher: publisher });
    render(vnode, document.getElementById(elementId));
    
}