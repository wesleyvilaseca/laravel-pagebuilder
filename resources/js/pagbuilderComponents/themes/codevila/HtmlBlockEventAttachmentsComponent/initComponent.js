import { createVNode, render } from 'vue';
import HtmlBlockAtthachmentEventComponent from './HtmlBlockEventAttachmentsComponent.vue';
/**
 * 
 * @param { string } elementId 
 * @param { string } event - event props
 */
export default function HtmlBlockEventAttachmentsComponent(elementId, event = '') {
    const vnode = createVNode(HtmlBlockAtthachmentEventComponent, { event: event });
    render(vnode, document.getElementById(elementId));
}