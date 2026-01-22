import { mount } from '@vue/test-utils'
import ToastNotification from './ToastNotification.vue'

describe('ToastNotification', () => {
    it('renders the message when show is true', () => {
        const wrapper = mount(ToastNotification, {
            props: {
                show: true,
                message: 'Test Message',
                type: 'success'
            }
        })
        expect(wrapper.text()).toContain('Test Message')
        expect(wrapper.find('.toast-container').exists()).toBe(true)
    })

    it('applies the correct class based on type', () => {
        const wrapper = mount(ToastNotification, {
            props: {
                show: true,
                message: 'Error Message',
                type: 'error'
            }
        })
        expect(wrapper.find('.toast-container').classes()).toContain('error')
    })
})
