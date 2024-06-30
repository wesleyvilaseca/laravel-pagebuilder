import { createVNode, render } from 'vue';
import testeComponent from './testeComponent.vue';

export default function mountMeuBlocoComponent (elementId, title, content) {
    const vnode = createVNode(testeComponent, { title, content });
    render(vnode, document.getElementById(elementId));
}