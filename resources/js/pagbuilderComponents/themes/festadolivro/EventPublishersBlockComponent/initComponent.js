import { createVNode, render } from 'vue';
import EventPublishersBlockComponent from './EventPublishersBlockComponent.vue';
/**
 * 
 * @param { string } elementId 
 * @param { string } event - event props
 */
export default function BlockEventPublishersComponent(elementId, event = '') {
    const vnode = createVNode(EventPublishersBlockComponent, { event: event });
    render(vnode, document.getElementById(elementId));
    
}