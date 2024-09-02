import { createVNode, render } from 'vue';
import ScheduleComponent from './ScheduleEventComponent.vue';
/**
 * 
 * @param { string } elementId 
 * @param { string } event - event props
 */
export default function ScheduleEventComponent(elementId, event = '', listType = 'list') {
    const vnode = createVNode(ScheduleComponent, { event: event, listType: listType });
    render(vnode, document.getElementById(elementId));
}