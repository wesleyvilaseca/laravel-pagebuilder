import { createVNode, render } from 'vue';
import EventBooksComponent from './BooksEventComponent.vue';
/**
 * 
 * @param { string } elementId 
 * @param { string } event - event props
 */
export default function BooksEventComponent(elementId, event = '') {
    const vnode = createVNode(EventBooksComponent, { event: event});
    render(vnode, document.getElementById(elementId));
}