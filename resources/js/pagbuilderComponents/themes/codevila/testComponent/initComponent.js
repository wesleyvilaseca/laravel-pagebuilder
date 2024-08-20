import { createVNode, render } from 'vue';
import testeComponent from './testeComponent.vue';

/**
 * 
 * @param { string } elementId 
 * @param { string } event - event props
 */
export default function mountMeuBlocoComponent (elementId, event) {
    const vnode = createVNode(testeComponent, { event: event });
    render(vnode, document.getElementById(elementId));
}