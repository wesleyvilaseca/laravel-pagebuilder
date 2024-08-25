import { createVNode, render } from 'vue';
import ListBenchMapGallery from './ListBenchMapGalleryEventComponent.vue';
/**
 * 
 * @param { string } elementId 
 * @param { string } event - event props
 */
export default function ListBenchMapGalleryEventComponent(elementId, event = '') {
    const vnode = createVNode(ListBenchMapGallery, { event: event });
    render(vnode, document.getElementById(elementId));
}