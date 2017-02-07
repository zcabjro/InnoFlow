import Vue from 'vue'
import IfMessage from '../components/IfMessage.vue'

describe('IfMessage', () => {

  // asserting default data
  it('should set correct default data', function () {
    expect(typeof IfMessage.data).toBe('function')
    var defaultData = IfMessage.data()
    expect(defaultData.defaultClass).toBe('if-message hidden-load')
  })

  // asserting rendered result by actually rendering the component
  it('should render correct message', function () {
    let message = "Hello, World!"
    var vm = new Vue({
      template: '<div><if-message id="test">' + message + '</if-message></div>',
      components: {
        IfMessage
      }
    }).$mount()
    expect(vm.$el.querySelector('#test').textContent).toBe(message)
  })
})