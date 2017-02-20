import Vue from 'vue'
import IfCreateClass from '../components/IfCreateClass.vue'

describe('IfCreateClass', () => {
  
  // Asserting default data
  it('sets default data', () => {
    expect(IfCreateClass.data).toBeDefined()
    expect(typeof IfCreateClass.data).toBe('function')  
  })

  const data = IfCreateClass.data()
  describe('legend', () => {
    it('should be a valid string', () => {
      expect(typeof data.legend).toBe('string')
      expect(data.legend).toBeTruthy()      
    })
  })

  // Asserting computed properties
  it('has a fields property', () => {
    expect(IfCreateClass.computed.fields).toBeDefined()
    expect(typeof IfCreateClass.computed.fields).toBe('function')
  })

  const vm = new Vue(IfCreateClass).$mount() 
  const fields = vm.fields
  describe('fields', () => {    
    for (let i = 0; i < fields.length; i++) {
      it('should have a label', () => {
        expect(fields[i].label).toBeDefined()
        expect(typeof fields[i].label).toBe('string')  
        expect(fields[i].label).toBeTruthy()     
      })
      it('should have a type', () => {
        expect(fields[i].type).toBeDefined()
        expect(typeof fields[i].type).toBe('string')  
        expect(fields[i].type).toBeTruthy()
      })
      it('should have a placeholder', () => {
        expect(fields[i].placeholder).toBeDefined()
        expect(typeof fields[i].placeholder).toBe('string')
      })
      it('should have a value', () => {
        expect(fields[i].value).toBeDefined()
        expect(typeof fields[i].value).toBe('string')
      })
    }
  })
  
})