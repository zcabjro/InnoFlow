import Vue from 'vue'
import IfMessage from '../components/IfMessage.vue'

describe('IfMessage', () => {

  // asserting default data
  it('should set correct default data', function () {
    expect(typeof IfMessage.data).toBe('function')
    var defaultData = IfMessage.data()
    expect(defaultData.classList).toBe('if-message hidden-load')
    expect(defaultData.content).toBe('')
    expect(defaultData.timeout).toBe(null)
  })

  // asserting rendered result by actually rendering the component
  it('should render correct message', function () {
    let message = "Hello, World!"
    var vm = new Vue({
      template: '<div><if-message ref="message"></if-message></div>',
      components: {
        IfMessage
      }
    }).$mount()
    vm.$refs.message.display(message)
    expect(vm.$refs.message.content).toBe(message)
  })
})