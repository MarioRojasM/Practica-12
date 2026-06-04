<script setup>
defineProps({
  label: { type: String, required: true },
  name: { type: String, required: true },
  modelValue: { type: [String, Number], default: '' },
  type: { type: String, default: 'text' },
  placeholder: { type: String, default: '' },
  error: { type: String, default: '' },
  step: { type: String, default: null }
})

defineEmits(['update:modelValue'])
</script>

<template>
  <div class="form-group">
    <label :for="name">{{ label }}</label>
    
    <input
      :id="name"
      :type="type"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :placeholder="placeholder"
      :step="step"
      :class="{ 'input-error': error }"
    />
    
    <span class="error-msg" v-if="error">{{ error }}</span>
  </div>
</template>

<style scoped>
.form-group { margin-bottom: 1.5rem; }
label { display: block; font-weight: bold; margin-bottom: 0.5rem; color: #4b5563; }
input { width: 100%; padding: 0.8rem; border: 2px solid #e2e8f0; border-radius: 6px; box-sizing: border-box; outline: none; font-family: inherit; transition: border-color 0.3s; }
input:focus { border-color: #e83e8c; }
.input-error { border-color: #ef4444 !important; background-color: #fef2f2; }
.error-msg { color: #ef4444; font-size: 0.85rem; margin-top: 0.3rem; display: block; font-weight: bold; }
</style>